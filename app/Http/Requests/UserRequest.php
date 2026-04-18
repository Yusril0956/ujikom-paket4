<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user && in_array($user->role, ['admin', 'petugas']);
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $authUser = auth()->user();
        $isCreate = $this->isMethod('post');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]*$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'id_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'id_number')->ignore($userId),
            ],
            'role' => [
                'required',
                $authUser->isAdmin()
                    ? Rule::in(['admin', 'petugas', 'anggota'])
                    : Rule::in(['anggota']),
            ],
            'status' => [
                'required',
                Rule::in(['aktif', 'pending', 'nonaktif']),
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            // Hanya admin yang bisa set admin_notes
            'admin_notes' => $authUser->isAdmin()
                ? ['nullable', 'string', 'max:1000']
                : ['prohibited'],
            'password' => $isCreate
                ? ['required', 'string', 'min:8', 'confirmed']
                : ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        $authUser = auth()->user();
        
        return [
            'name.required' => 'Nama lengkap harus diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'id_number.unique' => 'ID/NIM/NIP sudah terdaftar di sistem.',
            'role.required' => 'Tipe keanggotaan harus dipilih.',
            'role.in' => $authUser->isAdmin()
                ? 'Tipe keanggotaan tidak valid.'
                : 'Petugas hanya bisa membuat user dengan role Anggota.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'avatar.max' => 'Ukuran gambar maksimal 2MB.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'admin_notes.max' => 'Catatan admin maksimal 1000 karakter.',
            'admin_notes.prohibited' => 'Hanya admin yang dapat mengatur catatan admin.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->phone) {
            $this->merge([
                'phone' => preg_replace('/[^0-9+\-\s()]/', '', $this->phone),
            ]);
        }

        $this->merge([
            'name' => trim($this->name ?? ''),
            'email' => trim($this->email ?? ''),
            'address' => trim($this->address ?? ''),
            'admin_notes' => trim($this->admin_notes ?? ''),
        ]);
    }
}
