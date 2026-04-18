<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Ryl',
            'email' => 'ryl@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Eserel',
            'email' => 'eserel@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Zenki',
            'email' => 'zenki@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Alya Rahmawati',
            'email' => 'alya@email.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Dimas Pratama',
            'email' => 'dimas@email.com',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Eka Salsabila',
            'email' => 'eka@email.com',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
            'status' => 'nonaktif',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Fina Maulida',
            'email' => 'fina@email.com',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
            'status' => 'aktif',
            'email_verified_at' => now(),
        ]);
    }
}
