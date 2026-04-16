<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user?->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;

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
                Rule::in(['admin', 'petugas', 'anggota']),
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
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'password' => $this->isMethod('post')
                ? ['required', 'string', 'min:8', 'confirmed']
                : ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap harus diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'id_number.unique' => 'ID/NIM/NIP sudah terdaftar di sistem.',
            'role.required' => 'Tipe keanggotaan harus dipilih.',
            'role.in' => 'Tipe keanggotaan tidak valid.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'avatar.max' => 'Ukuran gambar maksimal 2MB.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'admin_notes.max' => 'Catatan admin maksimal 1000 karakter.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalize phone number
        if ($this->phone) {
            $this->merge([
                'phone' => preg_replace('/[^0-9+\-\s()]/', '', $this->phone),
            ]);
        }

        // Trim whitespace
        $this->merge([
            'name' => trim($this->name ?? ''),
            'email' => trim($this->email ?? ''),
            'address' => trim($this->address ?? ''),
            'admin_notes' => trim($this->admin_notes ?? ''),
        ]);
    }
}
