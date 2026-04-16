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
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransaksiStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $transaksi = DB::transaction(function () use ($validated) {
            $book = Book::lockForUpdate()->find($validated['book_id']);

            if ($book->stock_available <= 0) {
                throw new \Exception('Stok buku tidak tersedia.');
            }

            $txn = Transaksi::create([
                'user_id' => $validated['user_id'],
                'book_id' => $validated['book_id'],
                'borrowed_date' => $validated['borrowed_date'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'dipinjam',
            ]);

            $book->decrement('stock_available');

            if ($book->stock_available <= 0) {
                $book->update(['availability_status' => 'dipinjam']);
            }

            return $txn;
        });

        return redirect()->route('admin.transaksi.show', $transaksi)
            ->with('success', 'Pengajuan peminjaman berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Approve a pending transaksi.
     */
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

    /**
     * Reject a pending transaksi.
     */
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

    /**
     * Process book return.
     */
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

    /**
     * Show receipt/proof page for member.
     */
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


    /**
     * Export transaksis data.
     */
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
