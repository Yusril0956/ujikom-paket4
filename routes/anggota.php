<?php

/**
 * anggota/ANGGOTA ROUTES
 * Protected by auth middleware
 */

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\anggota\TransaksiController as AnggotaTransaksiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // ─────────────────────────────────────────────────────
    // PROFILE MANAGEMENT
    // ─────────────────────────────────────────────────────
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::get('/anggota/dashboard', function () {
        $user = auth()->user();

        if (!$user->isAnggota()) {
            abort(403, 'Hanya anggota yang dapat mengakses dashboard ini.');
        }

        $borrowingStats = [
            'active' => $user->transaksis()
                ->where('status', 'dipinjam')
                ->orWhere('status', 'terlambat')
                ->count(),
            'pending' => $user->transaksis()->where('status', 'pending')->count(),
            'returned' => $user->transaksis()->where('status', 'dikembalikan')->count(),
            'limit' => 4,
        ];

        $activeBorrowings = $user->transaksis()
            ->with('book')
            ->where(function ($q) {
                $q->where('status', 'dipinjam')->orWhere('status', 'terlambat');
            })
            ->latest()
            ->get();

        $pendingBorrowings = $user->transaksis()
            ->with('book')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('pages.anggota.dashboard', compact('borrowingStats', 'activeBorrowings', 'pendingBorrowings'));
    })->name('anggota.dashboard');

    Route::post('/books/{book}/borrow', [AnggotaTransaksiController::class, 'borrow'])
        ->name('books.borrow');

    Route::get('/transaksi/receipt/{bookingCode}', [AnggotaTransaksiController::class, 'receipt'])
        ->name('anggota.transaksi.receipt');

    Route::get('/transaksi', [AnggotaTransaksiController::class, 'index'])
        ->name('anggota.transaksi');

    Route::patch('/transaksi/{transaksi}/return', [AnggotaTransaksiController::class, 'returnBook'])
        ->name('anggota.transaksi.return');
});
