<?php
/**
 * STAFF/ADMIN ROUTES
 * Protected by auth and role:admin,petugas middleware
 * All routes prefixed with /admin and named with admin.* prefix
 */

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin,petugas'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ─────────────────────────────────────────────────────
        // DASHBOARD
        // ─────────────────────────────────────────────────────
        Route::get('/dashboard', function () {
            $totalBooks = \App\Models\Book::count();
            $activeMembers = \App\Models\User::where('role', 'anggota')->where('status', 'aktif')->count();
            $borrowedToday = \App\Models\Transaksi::whereDate('created_at', today())->where('status', 'dipinjam')->count();
            $overdueCount = \App\Models\Transaksi::where('status', 'terlambat')->count();
            $recentTransactions = \App\Models\Transaksi::with(['user', 'book'])
                ->latest('created_at')
                ->limit(5)
                ->get();
            
            // Additional stats for trends
            $newBooksThisMonth = \App\Models\Book::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $newMembersThisMonth = \App\Models\User::where('role', 'anggota')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            return view('pages.admin.dashboard', compact(
                'totalBooks',
                'activeMembers',
                'borrowedToday',
                'overdueCount',
                'recentTransactions',
                'newBooksThisMonth',
                'newMembersThisMonth'
            ));
        })->name('dashboard');

        // ─────────────────────────────────────────────────────
        // USER MANAGEMENT
        // ─────────────────────────────────────────────────────
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/export', [UserController::class, 'export'])->name('export');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::get('/{user}/show', [UserController::class, 'show'])->name('show');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

            // Soft delete restoration
            Route::get('/trashed', [UserController::class, 'trashed'])->name('trashed');
            Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
        });

        // ─────────────────────────────────────────────────────
        // BOOK MANAGEMENT
        // ─────────────────────────────────────────────────────
        Route::prefix('books')->name('books.')->group(function () {
            Route::get('/', [BookController::class, 'index'])->name('index');
            Route::get('/export', [BookController::class, 'export'])->name('export');
            Route::get('/create', [BookController::class, 'create'])->name('create');
            Route::post('/', [BookController::class, 'store'])->name('store');
            Route::get('/{book}', [BookController::class, 'show'])->name('show');
            Route::get('/{book}/edit', [BookController::class, 'edit'])->name('edit');
            Route::put('/{book}', [BookController::class, 'update'])->name('update');
            Route::patch('/{book}/toggle-visibility', [BookController::class, 'toggleVisibility'])->name('toggle-visibility');
            Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
        });

        // ─────────────────────────────────────────────────────
        // TRANSACTION MANAGEMENT
        // ─────────────────────────────────────────────────────
        Route::prefix('transaksi')->name('transaksi.')->group(function () {
            Route::get('/export', [TransaksiController::class, 'export'])->name('export');

            Route::get('/', [TransaksiController::class, 'index'])->name('index');
            Route::get('/create', [TransaksiController::class, 'create'])->name('create');
            Route::post('/', [TransaksiController::class, 'store'])->name('store');
            Route::get('/{transaksi}', [TransaksiController::class, 'show'])->name('show');

            // Transaction actions
            Route::patch('/{transaksi}/approve', [TransaksiController::class, 'approve'])->name('approve');
            Route::patch('/{transaksi}/reject', [TransaksiController::class, 'reject'])->name('reject');
            Route::patch('/{transaksi}/return', [TransaksiController::class, 'returnBook'])->name('return');
        });

    });
