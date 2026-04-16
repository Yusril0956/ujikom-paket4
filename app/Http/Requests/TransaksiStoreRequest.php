<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransaksiStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user && in_array($user->role, ['admin', 'petugas']);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id', function ($attr, $value, $fail) {
                $user = User::find($value);
                if ($user && $user->status !== 'aktif') {
                    $fail('Anggota harus berstatus Aktif untuk meminjam.');
                }
            }],
            'book_id' => ['required', 'exists:books,id', function ($attr, $value, $fail) {
                // Use pessimistic locking to prevent race condition
                $book = Book::lockForUpdate()->find($value);
                if ($book && $book->availability_status !== 'tersedia') {
                    $fail('Buku tidak tersedia untuk dipinjam.');
                }
                if ($book && $book->stock_available < 1) {
                    $fail('Stok buku habis.');
                }
            }],
            'borrowed_date' => 'nullable|date|before_or_equal:today',
            'due_date' => 'nullable|date|after_or_equal:borrowed_date',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'due_date.after' => 'Tanggal jatuh tempo harus setelah tanggal peminjaman.',
            'borrowed_date.before_or_equal' => 'Tanggal peminjaman tidak boleh di masa depan.',
        ];
    }
}
