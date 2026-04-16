<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scriptoria | Digital Archive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        section {
            scroll-margin-top: 5rem;
        }
    </style>
</head>

<body class="bg-background text-ink antialiased">

    {{-- 1. navbar --}}
    <nav class="fixed top-0 w-full bg-surface/95 backdrop-blur-sm border-b border-ink z-50" aria-label="Navigasi Utama">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <a href="/" class="flex items-center gap-2 group">
                    <x-lucide-book-open
                        class="w-6 h-6 text-ink stroke-[1.5] group-hover:text-coffee transition-colors" />
                    <span class="font-serif text-lg font-bold tracking-[0.1em] uppercase text-ink">Scriptoria</span>
                </a>

                <div class="hidden md:flex items-center gap-6">
                    <a href="#features"
                        class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted hover:text-ink transition-colors">Fitur</a>
                    <a href="#how-it-works"
                        class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted hover:text-ink transition-colors">Cara
                        Kerja</a>
                    <a href="{{ route('about') }}"
                        class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted hover:text-ink transition-colors">Tentang</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        @php
                            $role = auth()->user()->role ?? 'anggota';
                            $dashboardRoute = match ($role) {
                                'admin', 'petugas' => route('admin.dashboard'),
                                default => route('member.dashboard'),
                            };
                        @endphp
                        <a href="{{ $dashboardRoute }}"
                            class="hidden sm:inline-flex px-4 py-2 border border-ink text-[10px] font-mono uppercase tracking-[0.15em] text-ink hover:bg-ink/5 transition-colors rounded">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-ink text-surface border border-ink text-[10px] font-mono uppercase tracking-[0.15em] hover:bg-ink/90 transition-colors rounded">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="hidden sm:inline-flex px-4 py-2 border border-ink text-[10px] font-mono uppercase tracking-[0.15em] text-ink hover:bg-ink/5 transition-colors rounded">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 bg-ink text-surface border border-ink text-[10px] font-mono uppercase tracking-[0.15em] hover:bg-ink/90 transition-colors rounded">
                            Daftar Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- 2. hero --}}
    <header class="relative pt-32 pb-20 px-4 border-b border-ink">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-surface border border-ink rounded mb-6">
                <x-lucide-sparkles class="w-3 h-3 text-coffee" />
                <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted">Arsip Digital
                    Terintegrasi</span>
            </div>
            <h1 class="font-serif text-4xl md:text-6xl font-bold text-ink tracking-tight leading-[1.1]">
                Jelajahi Pengetahuan,<br class="hidden md:block"> Lestarikan Warisan.
            </h1>
            <p class="mt-6 font-serif text-lg text-muted max-w-2xl mx-auto leading-relaxed">
                Scriptoria adalah sistem manajemen perpustakaan modern yang dirancang untuk melestarikan,
                mengatalogisasi, dan memudahkan akses terhadap koleksi buku fisik maupun digital.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href="{{ route('register') }}"
                        class="px-6 py-3 bg-ink text-surface border border-ink font-serif hover:bg-ink/90 transition-colors rounded flex items-center justify-center gap-2">
                        <x-lucide-user-plus class="w-4 h-4" /> Mulai Bergabung
                    </a>
                @endguest

                <a href="#features"
                    class="px-6 py-3 bg-surface text-ink border border-ink font-serif hover:bg-ink/5 transition-colors rounded flex items-center justify-center gap-2">
                    <x-lucide-arrow-down class="w-4 h-4" /> Pelajari Lebih Lanjut
                </a>
                <a href="{{ route('books.index') }}"
                    class="px-6 py-3 bg-ink text-surface border border-ink font-serif hover:bg-ink/90 transition-colors rounded flex items-center justify-center gap-2">
                    <x-lucide-book class="w-4 h-4" /> Lihat Koleksi
                </a>
            </div>
        </div>
    </header>

    {{-- 3. fitur --}}
    <section id="features" class="py-20 px-4 border-b border-ink bg-surface">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="font-serif text-2xl md:text-3xl font-bold text-ink">Mengapa Scriptoria?</h2>
                <p class="mt-2 font-serif text-muted max-w-xl mx-auto">Sistem yang dirancang khusus untuk kebutuhan
                    arsip, katalogisasi, dan peminjaman yang terstruktur.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-background border border-ink p-6 hover:border-coffee transition-colors">
                    <div class="w-10 h-10 bg-surface border border-ink rounded flex items-center justify-center mb-4">
                        <x-lucide-book-open class="w-5 h-5 text-ink" />
                    </div>
                    <h3 class="font-serif text-lg font-semibold text-ink mb-2">Katalog Terstruktur</h3>
                    <p class="font-serif text-sm text-muted leading-relaxed">Pencarian cepat berdasarkan judul, penulis,
                        ISBN, atau klasifikasi DDC/LCC dengan metadata yang lengkap.</p>
                </div>
                
                <div class="bg-background border border-ink p-6 hover:border-coffee transition-colors">
                    <div class="w-10 h-10 bg-surface border border-ink rounded flex items-center justify-center mb-4">
                        <x-lucide-clock class="w-5 h-5 text-ink" />
                    </div>
                    <h3 class="font-serif text-lg font-semibold text-ink mb-2">Peminjaman Terkendali</h3>
                    <p class="font-serif text-sm text-muted leading-relaxed">Sistem booking otomatis, batas peminjaman 7
                        hari, verifikasi petugas, dan notifikasi jatuh tempo.</p>
                </div>

                <div class="bg-background border border-ink p-6 hover:border-coffee transition-colors">
                    <div class="w-10 h-10 bg-surface border border-ink rounded flex items-center justify-center mb-4">
                        <x-lucide-shield-check class="w-5 h-5 text-ink" />
                    </div>
                    <h3 class="font-serif text-lg font-semibold text-ink mb-2">Arsip & Keamanan</h3>
                    <p class="font-serif text-sm text-muted leading-relaxed">Pelacakan riwayat transaksi, manajemen
                        denda otomatis, dan backup data koleksi secara berkala.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. stats --}}
    <section class="py-0 border-b border-ink bg-surface">
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-0">
            <div class="p-6 text-center border-b md:border-b-0 md:border-r border-ink">
                <p class="font-mono text-3xl font-bold text-ink">12K+</p>
                <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted mt-1">Koleksi Buku</p>
            </div>
            <div class="p-6 text-center border-b md:border-b-0 md:border-r border-ink">
                <p class="font-mono text-3xl font-bold text-ink">800+</p>
                <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted mt-1">Anggota Aktif</p>
            </div>
            <div class="p-6 text-center border-b md:border-b-0 md:border-r border-ink">
                <p class="font-mono text-3xl font-bold text-ink">7 Hari</p>
                <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted mt-1">Masa Pinjam</p>
            </div>
            <div class="p-6 text-center">
                <p class="font-mono text-3xl font-bold text-ink">24/7</p>
                <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted mt-1">Akses Katalog</p>
            </div>
        </div>
    </section>

    {{-- 5. cara kerja --}}
    <section id="how-it-works" class="py-20 px-4 border-b border-ink">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="font-serif text-2xl md:text-3xl font-bold text-ink">Alur Peminjaman</h2>
                <p class="mt-2 font-serif text-muted">Proses sederhana untuk meminjam buku di Scriptoria.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="text-center">
                    <div
                        class="w-16 h-16 mx-auto bg-surface border border-ink rounded-full flex items-center justify-center mb-4">
                        <span class="font-mono text-xl font-bold text-ink">1</span>
                    </div>
                    <h3 class="font-serif text-lg font-semibold text-ink mb-2">Cari & Pilih Buku</h3>
                    <p class="font-serif text-sm text-muted">Jelajahi katalog digital, filter kategori, dan pilih
                        koleksi yang ingin dipinjam.</p>
                </div>
                
                <div class="text-center">
                    <div
                        class="w-16 h-16 mx-auto bg-surface border border-ink rounded-full flex items-center justify-center mb-4">
                        <span class="font-mono text-xl font-bold text-ink">2</span>
                    </div>
                    <h3 class="font-serif text-lg font-semibold text-ink mb-2">Dapatkan Kode Booking</h3>
                    <p class="font-serif text-sm text-muted">Sistem generate kode unik. Tunjukkan ke petugas dalam
                        waktu 24 jam.</p>
                </div>
                
                <div class="text-center">
                    <div
                        class="w-16 h-16 mx-auto bg-surface border border-ink rounded-full flex items-center justify-center mb-4">
                        <span class="font-mono text-xl font-bold text-ink">3</span>
                    </div>
                    <h3 class="font-serif text-lg font-semibold text-ink mb-2">Ambil & Kembalikan</h3>
                    <p class="font-serif text-sm text-muted">Verifikasi fisik oleh petugas. Kembalikan tepat waktu
                        untuk hindari denda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. FINAL CTA --}}
    @guest
        <section class="py-20 px-4 border-b border-ink bg-surface text-center">
            <h2 class="font-serif text-2xl md:text-3xl font-bold text-ink">Siap Mengakses Koleksi?</h2>
            <p class="mt-2 font-serif text-muted max-w-lg mx-auto mb-8">Daftarkan diri Anda sebagai anggota dan mulai
                pinjam buku secara digital maupun fisik.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                    class="px-6 py-3 bg-ink text-surface border border-ink font-serif hover:bg-ink/90 transition-colors rounded flex items-center justify-center gap-2">
                    <x-lucide-user-plus class="w-4 h-4" /> Buat Akun Gratis
                </a>
                <a href="{{ route('rules') }}"
                    class="px-6 py-3 bg-background text-ink border border-ink font-serif hover:bg-ink/5 transition-colors rounded flex items-center justify-center gap-2">
                    <x-lucide-scale class="w-4 h-4" /> Baca Tata Tertib
                </a>
            </div>
        </section>
    @endguest


    {{-- 7. FOOTER --}}
    <footer class="py-10 px-4 bg-background">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2">
                <x-lucide-book-open class="w-5 h-5 text-ink stroke-[1.5]" />
                <span class="font-serif font-bold tracking-[0.1em] uppercase text-ink">Scriptoria</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('about') }}"
                    class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted hover:text-ink transition-colors">Tentang</a>
                <a href="{{ route('rules') }}"
                    class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted hover:text-ink transition-colors">Tata
                    Tertib</a>
                <a href="{{ route('login') }}"
                    class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted hover:text-ink transition-colors">Masuk</a>
            </div>
            <p class="font-mono text-[10px] text-muted">&copy; {{ date('Y') }} Scriptoria Digital Archive. All
                rights reserved.</p>
        </div>
    </footer>

</body>

</html>
