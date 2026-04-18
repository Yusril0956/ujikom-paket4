<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransaksiStoreRequest;
use App\Models\Book;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaksi::with(['user', 'book', 'verifiedBy']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $transaksi = $query->latest()->paginate(6)->withQueryString();


        return view('pages.admin.transaksi.index', compact('transaksi'));
    }

    public function create(): View
    {
        $members = User::where('role', 'anggota')
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $availableBooks = Book::where('availability_status', 'tersedia')
            ->where('stock_available', '>', 0)
            ->orderBy('title')
            ->get(['id', 'title', 'author', 'isbn']);

        return view('pages.admin.transaksi.create', compact('members', 'availableBooks'));
    }

    public function store(TransaksiStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::find($validated['user_id']);
        if ($user->status !== 'aktif') {
            return redirect()->back()
                ->with('error', "Anggota '{$user->name}' tidak memiliki status aktif. Ubah status terlebih dahulu.");
        }

        $activeCount = $user->transaksis()->where('status', 'dipinjam')->count();
        if ($activeCount >= 4) {
            return redirect()->back()
                ->with('error', "Anggota '{$user->name}' telah mencapai batas maksimal peminjaman (4 buku).");
        }

        $transaksi = DB::transaction(function () use ($validated, $user) {
            $book = Book::lockForUpdate()->find($validated['book_id']);

            if ($book->stock_available <= 0) {
                throw new \Exception('Stok buku tidak tersedia.');
            }

            $txn = Transaksi::create([
                'user_id' => $validated['user_id'],
                'book_id' => $validated['book_id'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            return $txn;
        });

        return redirect()->route('admin.transaksi.show', $transaksi)
            ->with('success', 'Pengajuan peminjaman berhasil dibuat. Tunggu konfirmasi dari anggota atau ubah status ke dipinjam jika sudah diserahkan.');
    }

    public function show(Transaksi $transaksi): View
    {
        $activityLog = collect([
            ['timestamp' => $transaksi->created_at, 'action' => 'Pengajuan dibuat', 'by' => $transaksi->user->name],
            ...($transaksi->verified_at ? [[
                'timestamp' => $transaksi->verified_at,
                'action' => $transaksi->status === 'ditolak' ? 'Ditolak' : 'Disetujui',
                'by' => $transaksi->verifiedBy?->name ?? 'Sistem'
            ]] : []),
            ...($transaksi->returned_date ? [[
                'timestamp' => $transaksi->returned_date,
                'action' => 'Dikembalikan',
                'by' => 'Petugas'
            ]] : []),
        ]);

        return view('pages.admin.transaksi.show', compact('transaksi', 'activityLog'));
    }

    public function approve(Transaksi $transaksi, Request $request): RedirectResponse
    {
        if ($transaksi->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi tidak dalam status pending.');
        }

        $success = $transaksi->approve($request->user());

        if ($success) {
            return redirect()->back()->with('success', 'Peminjaman disetujui. Buku dapat diserahkan.');
        }

        return redirect()->back()->with('error', 'Gagal menyetujui: cek stok buku atau kuota anggota.');
    }

    public function reject(Transaksi $transaksi, Request $request): RedirectResponse
    {
        if ($transaksi->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi tidak dalam status pending.');
        }

        $request->validate(['reason' => 'required|string|max:255']);

        $success = $transaksi->reject($request->user(), $request->reason);

        return redirect()->back()->with(
            $success ? 'success' : 'error',
            $success ? 'Peminjaman ditolak.' : 'Gagal menolak transaksi.'
        );
    }

    public function returnBook(Transaksi $transaksi): RedirectResponse
    {
        if (!in_array($transaksi->status, ['dipinjam', 'terlambat'])) {
            return redirect()->back()->with('error', 'Buku tidak dalam status dipinjam.');
        }

        $success = $transaksi->returnBook();

        $message = $success
            ? 'Pengembalian berhasil dicatat.' . ($transaksi->fine_amount > 0 ? " Denda: Rp " . number_format($transaksi->fine_amount) : '')
            : 'Gagal memproses pengembalian.';

        return redirect()->back()->with($success ? 'success' : 'error', $message);
    }

    public function receipt(string $bookingCode): View
    {
        $transaksi = Transaksi::with(['user', 'book'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        // Authorization: Only user, admin, or petugas can view
        if (auth()->id() !== $transaksi->user_id && !auth()->user()->isPetugas() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this receipt.');
        }

        return view('pages.admin.transaksi.bukti', compact('transaksi'));
    }

    public function export(Request $request)
    {
        $transaksis = Transaksi::with(['user', 'book'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transaksi-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($transaksis) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Booking Code', 'Anggota', 'Buku', 'Pinjam', 'Kembali', 'Status', 'Denda']);

            foreach ($transaksis as $txn) {
                fputcsv($file, [
                    $txn->formatted_id,
                    $txn->booking_code,
                    $txn->user->name,
                    $txn->book->title,
                    $txn->borrowed_date?->format('d/m/Y'),
                    $txn->due_date?->format('d/m/Y'),
                    $txn->status,
                    $txn->fine_amount > 0 ? 'Rp ' . number_format($txn->fine_amount) : '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
