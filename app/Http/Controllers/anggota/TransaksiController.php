<?php

namespace App\Http\Controllers\anggota;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = $user->transaksis()
            ->with(['book', 'verifiedBy'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('book', function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('isbn', 'like', "%{$request->search}%");
            });
        }

        $transaksis = $query->paginate(15)->withQueryString();

        return view('pages.anggota.transaksi', compact('transaksis'));
    }

    public function borrow(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isAnggota()) {
            return redirect()->back()
                ->with('error', 'Hanya anggota yang dapat meminjam buku.');
        }

        if ($user->status !== 'aktif') {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat meminjam buku karena status akun Anda tidak aktif. Hubungi admin.');
        }

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'notes' => 'nullable|string|max:255',
        ]);

        $book = \App\Models\Book::findOrFail($validated['book_id']);

        if ($book->stock_available <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia. Silakan coba lagi nanti.');
        }

        $activeCount = $user->transaksis()
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();

        if ($activeCount >= 4) {
            return redirect()->back()
                ->with('error', 'Anda telah mencapai batas maksimal peminjaman (4 buku). Kembalikan beberapa buku terlebih dahulu.');
        }

        try {
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            return redirect()
                ->route('anggota.transaksi')
                ->with('success', 'Permintaan peminjaman berhasil dibuat. Tunggu persetujuan dari petugas.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Transaksi creation failed', [
                'user_id' => $user->id,
                'book_id' => $book->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', 'Gagal membuat permintaan. Hubungi administrator.');
        } catch (\Exception $e) {
            Log::error('Unexpected error in borrow', ['error' => $e]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }


    public function returnBook(Transaksi $transaksi, Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($transaksi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengembalikan buku ini.');
        }

        if (!$user->isAnggota()) {
            abort(403, 'Hanya anggota yang dapat mengembalikan buku.');
        }

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
        $transaksi = Transaksi::with(['user', 'book', 'verifiedBy'])
            ->where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pages.admin.transaksi.bukti', compact('transaksi'));
    }
}
