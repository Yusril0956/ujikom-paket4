<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user && in_array($user->role, ['admin', 'petugas']);
    }

    public function rules(): array
    {
        $bookId = $this->route('book')?->id;

        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|digits:4|min:1000|max:' . (date('Y') + 1),
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                $bookId
                    ? Rule::unique('books', 'isbn')->ignore($bookId)
                    : Rule::unique('books', 'isbn'),
            ],
            'issn' => 'nullable|string|max:20',

            'category' => ['required', Rule::in([
                'Fiksi',
                'Fiksi - Sastra Indonesia',
                'Sejarah & Antropologi',
                'Filosofi & Spiritualitas',
                'Pengembangan Diri & Bisnis',
                'Psikologi & Sains Kognitif',
                'Sastra',
            ])],
            'classification' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:100',
            'availability_status' => ['required', Rule::in(['tersedia', 'dipinjam', 'reservasi', 'arsip', 'perbaikan'])],

            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'synopsis' => 'nullable|string|max:2000',
            'curator_notes' => 'nullable|string|max:1000',

            'stock_total' => 'nullable|integer|min:1',
            'is_public' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'cover_image.dimensions' => 'Sampul harus memiliki rasio aspek 2:3 (contoh: 400x600 px).',
            'published_year.digits' => 'Tahun terbit harus 4 digit (contoh: 2024).',
            'isbn.unique' => 'ISBN ini sudah terdaftar di katalog.',
        ];
    }
}
