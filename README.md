# Scriptoria

[![Repository](https://img.shields.io/badge/Repository-GitHub-181717?style=flat-square&logo=github&logoColor=white)](https://github.com/Yusril0956/ujikom-paket4)
[![Live Demo](https://img.shields.io/badge/Live%20Demo-Scriptoria-16A34A?style=flat-square&logo=laravel&logoColor=white)](https://scriptoria.free.laravel.cloud/)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-0EA5E9?style=flat-square)](https://opensource.org/licenses/MIT)

Scriptoria adalah aplikasi web Laravel untuk digital archive dan library management. Sistem ini mencakup katalog buku, peminjaman berbasis booking code, verifikasi petugas, pengembalian, denda keterlambatan, export laporan, serta manajemen user berbasis role.

Repository: https://github.com/Yusril0956/ujikom-paket4.git

Demo online: https://scriptoria.free.laravel.cloud/

## Table of Contents

- [Ikhtisar](#ikhtisar)
- [Fitur Utama](#fitur-utama)
- [Tumpukan Teknologi](#tumpukan-teknologi)
- [Struktur Akses](#struktur-akses)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Akun Demo](#akun-demo)
- [Route Penting](#route-penting)
- [Perintah Operasional](#perintah-operasional)
- [Struktur Proyek](#struktur-proyek)
- [Deployment](#deployment)
- [Lisensi](#lisensi)

## Ikhtisar

Proyek ini dibangun dengan Laravel 12 dan berfokus pada workflow perpustakaan berikut:

- Public catalog untuk buku yang ditandai `is_public`
- Peminjaman buku dengan status `pending`, `dipinjam`, `dikembalikan`, `terlambat`, `ditolak`, dan `expired`
- Batas pinjam 4 buku per anggota aktif
- Masa pinjam 7 hari dan pickup deadline 24 jam untuk request yang masih `pending`
- Denda keterlambatan sebesar Rp 1.000 per hari
- Soft delete untuk `User`, `Book`, dan `Transaksi`
- Export data ke file `.xlsx`
- Dashboard terpisah untuk anggota dan staff

## Fitur Utama

- Katalog buku publik dengan pencarian berdasarkan title, author, ISBN, dan publisher
- Detail buku dengan kontrol visibilitas untuk admin dan petugas
- Registrasi, login, reset password, dan email verification melalui Laravel Breeze
- Dashboard anggota untuk melihat transaksi aktif, pending, dan riwayat
- Workflow peminjaman oleh anggota melalui booking code
- Approval, rejection, dan return processing oleh admin atau petugas
- Manajemen pengguna dengan role `admin`, `petugas`, dan `anggota`
- Manajemen buku dengan cover upload, stock control, dan toggle visibilitas publik
- Export laporan buku, pengguna, dan transaksi dalam format XLSX
- Command console untuk sinkronisasi status transaksi `pending` dan `dipinjam`

## Tumpukan Teknologi

- Backend: Laravel 12
- Bahasa: PHP 8.4+
- Frontend rendering: Blade
- Asset pipeline: Vite
- Styling: Tailwind CSS 4
- Auth scaffolding: Laravel Breeze
- Test stack: Pest
- Export format: XLSX berbasis `ZipArchive`
- Ikon: `mallardduck/blade-lucide-icons`

## Struktur Akses

| Role | Akses Utama |
| --- | --- |
| Public | Home, About, Rules, dan public book catalog |
| Anggota | Dashboard anggota, ajukan peminjaman, lihat receipt, lihat transaksi, return request, profile |
| Admin | Dashboard admin, manage user, manage book, manage transaksi, export data |
| Petugas | Akses operasional seperti admin, dengan pembatasan pada beberapa aksi user |

Catatan implementasi:

- Route admin berada di prefix `/admin`
- Route anggota berada di prefix `/anggota`
- Aplikasi ini menggunakan web routes, bukan public API

## Prasyarat

- PHP 8.4 atau lebih baru
- Composer
- Node.js dan npm
- Extension PHP `ext-zip`
- Extension PHP `ext-gd`
- Database engine sesuai konfigurasi `.env`

Default environment pada repository ini menggunakan SQLite.

## Instalasi

1. Clone repository.

   ```bash
   git clone https://github.com/Yusril0956/ujikom-paket4.git
   cd ujikom-paket4
   ```

2. Install dependency PHP dan JavaScript.

   ```bash
   composer install
   npm install
   ```

3. Siapkan file environment.

   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. Pastikan database tersedia.

   - Jika memakai SQLite, pastikan file `database/database.sqlite` ada dan writable.
   - Jika memakai MySQL atau PostgreSQL, sesuaikan konfigurasi di `.env`.

5. Jalankan migration dan seeder.

   ```bash
   php artisan migrate --seed
   ```

6. Buat symbolic link untuk storage public.

   ```bash
   php artisan storage:link
   ```

7. Jalankan aplikasi untuk development.

   ```bash
   composer run dev
   ```

   Script ini menjalankan `php artisan serve`, `php artisan queue:listen --tries=1`, dan `npm run dev` secara paralel.

## Konfigurasi Environment

Variabel berikut penting untuk operasi aplikasi:

| Variabel | Nilai Default | Catatan |
| --- | --- | --- |
| `APP_URL` | `http://localhost` | Sesuaikan dengan domain lokal atau produksi |
| `DB_CONNECTION` | `sqlite` | Default repository |
| `SESSION_DRIVER` | `database` | Membutuhkan tabel session |
| `CACHE_STORE` | `database` | Cache disimpan di database |
| `QUEUE_CONNECTION` | `database` | Queue worker dibutuhkan |
| `FILESYSTEM_DISK` | `local` | Upload tetap memakai disk `public` di code |

Konten file upload yang digunakan aplikasi:

- Cover buku: `storage/app/public/books/covers`
- Avatar user: `storage/app/public/avatars`
- Asset seed cover dapat berada di `public/images_covers`

## Akun Demo

Seeder menambahkan akun contoh berikut:

| Nama | Email | Password | Role | Status |
| --- | --- | --- | --- | --- |
| Ryl | `ryl@perpustakaan.com` | `password` | admin | aktif |
| Zenki | `zenki@perpustakaan.com` | `password` | petugas | aktif |
| Eserel | `eserel@perpustakaan.com` | `password` | anggota | aktif |
| Alya Rahmawati | `alya@email.com` | `password` | anggota | aktif |
| Dimas Pratama | `dimas@email.com` | `password123` | anggota | aktif |
| Eka Salsabila | `eka@email.com` | `password123` | anggota | nonaktif |
| Fina Maulida | `fina@email.com` | `password123` | anggota | aktif |

Gunakan akun admin atau petugas untuk menguji modul manajemen, dan akun anggota untuk menguji workflow peminjaman.

## Route Penting

| Path | Middleware | Fungsi |
| --- | --- | --- |
| `/` | public | Landing page Scriptoria |
| `/about` | public | Informasi aplikasi |
| `/rules` | public | Tata tertib perpustakaan |
| `/books` | public/auth | Katalog buku |
| `/books/{book}` | public/auth | Detail buku |
| `/transaksi-bukti/{bookingCode}` | `auth` | Receipt peminjaman |
| `/login` dan `/register` | guest | Autentikasi |
| `/profile` | `auth` | Profile management |
| `/anggota/dashboard` | `auth` | Dashboard anggota |
| `/anggota/transaksi` | `auth` | Riwayat transaksi anggota |
| `/admin/dashboard` | `auth`, `role:admin,petugas` | Dashboard staff |
| `/admin/users` | `auth`, `role:admin,petugas` | User management |
| `/admin/books` | `auth`, `role:admin,petugas` | Book management |
| `/admin/transaksi` | `auth`, `role:admin,petugas` | Transaction management |

## Perintah Operasional

```bash
php artisan migrate --seed
php artisan storage:link
php artisan transaksi:update-status
php artisan test
npm run build
```

- `php artisan transaksi:update-status` menandai transaksi `pending` yang melewati pickup deadline menjadi `expired`
- Command yang sama juga menandai transaksi aktif yang melewati due date menjadi `terlambat`
- Untuk produksi, jalankan command tersebut melalui scheduler atau cron sesuai kebutuhan operasional

## Struktur Proyek

- `app/Http/Controllers/Admin` - controller untuk user, book, dan transaksi
- `app/Http/Controllers/anggota` - controller untuk alur anggota
- `app/Console/Commands` - command sinkronisasi status transaksi
- `app/Models` - model `User`, `Book`, dan `Transaksi`
- `database/migrations` - definisi tabel dan relasi
- `database/seeders` - data awal untuk user, buku, dan transaksi
- `resources/views/pages` - halaman publik, auth, dashboard, dan panel admin
- `resources/views/components` - komponen Blade reusable
- `routes` - pemisahan route `public`, `auth`, `anggota`, dan `petugas`

## Deployment

Checklist minimum untuk production:

1. Set `APP_URL` ke domain yang benar.
2. Install dependency dengan mode production.

   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   ```

3. Jalankan migration.

   ```bash
   php artisan migrate --force
   ```

4. Aktifkan storage link.

   ```bash
   php artisan storage:link
   ```

5. Pastikan queue worker berjalan jika `QUEUE_CONNECTION=database`.
6. Daftarkan `php artisan transaksi:update-status` pada cron atau scheduler.
7. Arahkan document root web server ke folder `public`.

Demo yang tersedia saat ini berjalan di Laravel Cloud:

https://scriptoria.free.laravel.cloud/

## Lisensi

Proyek ini menggunakan lisensi MIT.

