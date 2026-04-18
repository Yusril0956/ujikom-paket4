<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookStoreRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('availability_status', $request->status);
        }


        if (!auth()->check() || (auth()->user()->isAnggota())) {
            $query->publik();
        }

        $query->withoutTrashed();

        $books = $query->orderBy('created_at', 'desc')->paginate(6)->withQueryString();

        return view('pages.books.index', compact('books'));
    }

    public function create(): View
    {
        return view('pages.books.create');
    }

    public function store(BookStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('books/covers', 'public');
        }

        $validated['stock_available'] = $validated['stock_total'] ?? 1;
        $validated['created_by'] = auth()->id();

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan ke katalog.');
    }

    public function show(Book $book): View
    {
        // Only allow viewing public books, unless user is admin/petugas
        if (!$book->is_public && !auth()->user()?->isAdmin() && !auth()->user()?->isPetugas()) {
            abort(403, 'Anda tidak memiliki akses ke buku ini.');
        }
        return view('pages.books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        return view('pages.books.edit', compact('book'));
    }

    public function update(BookStoreRequest $request, Book $book): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            try {
                $validated['cover_image'] = $request->file('cover_image')
                    ->store('books/covers', 'public');
            } catch (Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal upload gambar: ' . $e->getMessage());
            }
        }

        if (isset($validated['stock_total'])) {
            $oldTotal = $book->stock_total;
            $newTotal = $validated['stock_total'];
            $diff = $newTotal - $oldTotal;
            $validated['stock_available'] = max(0, $book->stock_available + $diff);
        }

        $validated['updated_by'] = auth()->id();
        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->update(['deleted_by' => auth()->id()]);
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus dari katalog.');
    }

    public function toggleVisibility(Book $book): RedirectResponse
    {
        $book->update(['is_public' => !$book->is_public]);

        return redirect()->route('admin.books.index')
            ->with('success', 'Visibilitas buku diperbarui.');
    }
}
