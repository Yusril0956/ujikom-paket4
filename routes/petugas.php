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
            return view('pages.admin.dashboard');
        })->name('dashboard');

        // ─────────────────────────────────────────────────────
        // USER MANAGEMENT
        // ─────────────────────────────────────────────────────
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
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
            Route::get('/create', [BookController::class, 'create'])->name('create');
            Route::post('/', [BookController::class, 'store'])->name('store');
            Route::get('/{book}/edit', [BookController::class, 'edit'])->name('edit');
            Route::put('/{book}', [BookController::class, 'update'])->name('update');
            Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
        });

        // ─────────────────────────────────────────────────────
        // TRANSACTION MANAGEMENT
        // ─────────────────────────────────────────────────────
        Route::prefix('transaksi')->name('transaksi.')->group(function () {
            Route::get('/', [TransaksiController::class, 'index'])->name('index');
            Route::get('/create', [TransaksiController::class, 'create'])->name('create');
            Route::post('/', [TransaksiController::class, 'store'])->name('store');
            Route::get('/{transaksi}', [TransaksiController::class, 'show'])->name('show');

            // Transaction actions
            Route::patch('/{transaksi}/approve', [TransaksiController::class, 'approve'])->name('approve');
            Route::patch('/{transaksi}/reject', [TransaksiController::class, 'reject'])->name('reject');
            Route::patch('/{transaksi}/return', [TransaksiController::class, 'returnBook'])->name('return');

            // Data export
            Route::get('/export/data', [TransaksiController::class, 'export'])->name('export');
        });

    });
