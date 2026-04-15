<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('pages.admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {});

Route::view('/', 'pages.welcome')->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/rules', 'pages.rules')->name('rules');

// Placeholder static pages for navigation (will 404 or create later)
Route::view('/categories', 'categories')->name('categories');
Route::view('/new-arrivals', 'new-arrivals')->name('new-arrivals');
Route::view('/popular', 'popular')->name('popular');
Route::view('/fines', 'fines')->name('fines');

// Admin/Member dashboard placeholders (static for now)
Route::view('/admin/dashboard', 'pages.admin.dashboard')->name('admin.dashboard');
Route::view('/member/dashboard', 'pages.member.dashboard')->name('member.dashboard');
Route::view('/books', 'pages.admin.books.index')->name('books.index');
Route::view('/admin/books/create', 'pages.admin.books.create')->name('admin.books.create');


Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
Route::get('/admin/users-trashed', [UserController::class, 'trashed'])->name('admin.users.trashed');
Route::post('/admin/users/{id}/restore', [UserController::class, 'restore'])->name('admin.users.restore');

Route::get('/transaksi', function () {
    return view('pages.admin.transaksi.index');
})->name('admin.transaksi.index');


require __DIR__ . '/auth.php';
