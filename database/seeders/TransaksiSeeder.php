<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'anggota')->where('status', 'aktif')->get();
        $books = Book::all();
        $admin = User::where('role', 'admin')->first();

        if ($users->isEmpty() || $books->isEmpty()) {
            throw new \Exception('UserSeeder dan BookSeeder harus dijalankan terlebih dahulu!');
        }

        $transaksis = [
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => null,
                'due_date' => null,
                'returned_date' => null,
                'status' => 'pending',
                'pickup_deadline' => now()->addHours(24),
                'notes' => 'Pengajuan peminjaman baru',
                'rejection_reason' => null,
                'verified_by' => null,
                'verified_at' => null,
                'fine_amount' => 0,
                'fine_paid' => false,
            ],
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => null,
                'due_date' => null,
                'returned_date' => null,
                'status' => 'pending',
                'pickup_deadline' => now()->subHours(2),
                'notes' => null,
                'rejection_reason' => null,
                'verified_by' => null,
                'verified_at' => null,
                'fine_amount' => 0,
                'fine_paid' => false,
            ],
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => now()->subDays(2)->toDateString(),
                'due_date' => now()->addDays(5)->toDateString(),
                'returned_date' => null,
                'status' => 'dipinjam',
                'pickup_deadline' => now()->subDays(1),
                'notes' => 'Pembaca setia yang selalu tepat waktu',
                'rejection_reason' => null,
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(2),
                'fine_amount' => 0,
                'fine_paid' => false,
            ],
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => now()->subDays(3)->toDateString(),
                'due_date' => now()->addDays(4)->toDateString(),
                'returned_date' => null,
                'status' => 'dipinjam',
                'pickup_deadline' => now()->subDays(2),
                'notes' => null,
                'rejection_reason' => null,
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(3),
                'fine_amount' => 0,
                'fine_paid' => false,
            ],
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => now()->subDays(10)->toDateString(),
                'due_date' => now()->subDays(2)->toDateString(),
                'returned_date' => null,
                'status' => 'terlambat',
                'pickup_deadline' => now()->subDays(9),
                'notes' => 'Tanggung jawab: sudah diberi pengingat',
                'rejection_reason' => null,
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(10),
                'fine_amount' => 2000,
                'fine_paid' => false,
            ],
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => now()->subDays(14)->toDateString(),
                'due_date' => now()->subDays(2)->toDateString(),
                'returned_date' => null,
                'status' => 'terlambat',
                'pickup_deadline' => now()->subDays(13),
                'notes' => null,
                'rejection_reason' => null,
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(14),
                'fine_amount' => 2000,
                'fine_paid' => false,
            ],
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => now()->subDays(8)->toDateString(),
                'due_date' => now()->subDays(2)->toDateString(),
                'returned_date' => now()->subDays(2)->toDateString(),
                'status' => 'dikembalikan',
                'pickup_deadline' => now()->subDays(7),
                'notes' => 'Dikembalikan dalam kondisi baik',
                'rejection_reason' => null,
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(8),
                'fine_amount' => 0,
                'fine_paid' => false,
            ],
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => now()->subDays(12)->toDateString(),
                'due_date' => now()->subDays(6)->toDateString(),
                'returned_date' => now()->subDays(2)->toDateString(),
                'status' => 'dikembalikan',
                'pickup_deadline' => now()->subDays(11),
                'notes' => 'Dikembalikan terlambat 4 hari, denda sudah dibayar',
                'rejection_reason' => null,
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(12),
                'fine_amount' => 4000,
                'fine_paid' => true,
            ],
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => null,
                'due_date' => null,
                'returned_date' => null,
                'status' => 'ditolak',
                'pickup_deadline' => now()->subDays(3),
                'notes' => null,
                'rejection_reason' => 'Stok buku tidak tersedia',
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(4),
                'fine_amount' => 0,
                'fine_paid' => false,
            ],
            [
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'booking_code' => 'SCR-' . strtoupper(Str::random(8)),
                'borrowed_date' => null,
                'due_date' => null,
                'returned_date' => null,
                'status' => 'ditolak',
                'pickup_deadline' => now()->subDays(5),
                'notes' => null,
                'rejection_reason' => 'Kuota peminjaman anggota sudah penuh (4 buku)',
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(5),
                'fine_amount' => 0,
                'fine_paid' => false,
            ],
        ];

        foreach ($transaksis as $transaksi) {
            Transaksi::create($transaksi);
        }

        $this->command->info('✓ 10 transaksi berhasil di-seed');
    }
}
