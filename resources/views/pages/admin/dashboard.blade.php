<x-layouts.app>
    <div class="max-w-7xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER & QUICK ACTIONS --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink/20 pb-5">
            <div>
                <h1 class="text-3xl font-serif font-bold text-ink tracking-tight">Dashboard Perpustakaan</h1>
                <p class="text-muted mt-1 font-serif">Ringkasan koleksi, aktivitas peminjaman, dan status sistem.</p>
            </div>
            <div class="flex gap-3">
                <button
                    class="px-4 py-2.5 bg-surface border border-ink/20 text-sm font-serif text-coffee hover:text-ink hover:border-ink/40 transition-all rounded-md shadow-[var(--elevation-1)] flex items-center gap-2">
                    <x-lucide-plus class="w-4 h-4" /> Tambah Katalog
                </button>
                <button
                    class="px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md shadow-[var(--elevation-1)] flex items-center gap-2">
                    <x-lucide-download class="w-4 h-4" /> Ekspor Laporan
                </button>
            </div>
        </div>

        {{-- 2. STATISTIK UTAMA (4 Kartu dengan Garis Tepi) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $stats = [
                    [
                        'label' => 'Total Koleksi',
                        'value' => '12,458',
                        'icon' => 'book-open',
                        'trend' => '+24 buku',
                        'color' => 'text-coffee',
                    ],
                    [
                        'label' => 'Anggota Aktif',
                        'value' => '842',
                        'icon' => 'users',
                        'trend' => '+12 baru',
                        'color' => 'text-ink',
                    ],
                    [
                        'label' => 'Dipinjam Hari Ini',
                        'value' => '67',
                        'icon' => 'arrow-right-left',
                        'trend' => 'Sedang berjalan',
                        'color' => 'text-coffee',
                    ],
                    [
                        'label' => 'Jatuh Tempo',
                        'value' => '9',
                        'icon' => 'alert-circle',
                        'trend' => 'Perlu tindak lanjut',
                        'color' => 'text-red-700',
                    ],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="bg-surface border border-ink/50 p-5 hover:border-ink transition-colors group relative">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-mono uppercase tracking-widest text-muted">{{ $stat['label'] }}</p>
                            <p class="text-3xl font-serif font-bold text-ink mt-2">{{ $stat['value'] }}</p>
                        </div>
                        <div
                            class="p-2 bg-ink/5 border border-ink/10 rounded-md group-hover:bg-ink/10 transition-colors">
                            <x-lucide-{{ $stat['icon'] }} class="w-5 h-5 {{ $stat['color'] }}" />
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-ink/10 flex items-center justify-between">
                        <span class="text-xs font-mono {{ $stat['color'] }}">{{ $stat['trend'] }}</span>
                        <x-lucide-chevron-right
                            class="w-3 h-3 text-muted opacity-0 group-hover:opacity-100 transition-opacity" />
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 3. KONTEN UTAMA: Tabel & Panel Samping --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- TABEL TRANSAKSI TERBARU (2 Kolom) --}}
            <div class="lg:col-span-2 bg-surface border border-ink">
                <div class="px-6 py-4 border-b border-ink flex items-center justify-between">
                    <h2 class="font-serif text-lg font-bold text-ink">Transaksi Peminjaman</h2>
                    <a href="#"
                        class="text-xs font-mono uppercase tracking-widest text-coffee hover:text-ink transition-colors">Lihat
                        Arsip ›</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-ink/15 bg-ink/5">
                                <th class="text-left px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">ID
                                </th>
                                <th class="text-left px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">
                                    Peminjam</th>
                                <th class="text-left px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">
                                    Judul Buku</th>
                                <th
                                    class="text-center px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">
                                    Status</th>
                                <th class="text-right px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">
                                    Tenggat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink/10">
                            <tr class="hover:bg-ink/5 transition-colors">
                                <td class="px-6 py-4 font-mono text-coffee">#BRW-8842</td>
                                <td class="px-6 py-4 font-serif text-ink">Alya Rahmawati</td>
                                <td class="px-6 py-4 font-serif text-muted">Filosofi Teras</td>
                                <td class="px-6 py-4 text-center"><span
                                        class="px-2 py-0.5 text-xs font-mono border border-ink/20 rounded bg-ink/5 text-ink">Dipinjam</span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-muted">14 Jun 2024</td>
                            </tr>
                            <tr class="hover:bg-ink/5 transition-colors">
                                <td class="px-6 py-4 font-mono text-coffee">#BRW-8841</td>
                                <td class="px-6 py-4 font-serif text-ink">Budi Santoso</td>
                                <td class="px-6 py-4 font-serif text-muted">Laut Bercerita</td>
                                <td class="px-6 py-4 text-center"><span
                                        class="px-2 py-0.5 text-xs font-mono border border-green-700/30 rounded bg-green-700/5 text-green-800">Dikembalikan</span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-muted">07 Jun 2024</td>
                            </tr>
                            <tr class="hover:bg-ink/5 transition-colors">
                                <td class="px-6 py-4 font-mono text-coffee">#BRW-8840</td>
                                <td class="px-6 py-4 font-serif text-ink">Citra Dewi</td>
                                <td class="px-6 py-4 font-serif text-muted">Sapiens: Riwayat Singkat</td>
                                <td class="px-6 py-4 text-center"><span
                                        class="px-2 py-0.5 text-xs font-mono border border-red-700/30 rounded bg-red-700/5 text-red-800">Terlambat</span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-red-700 font-medium">01 Jun 2024</td>
                            </tr>
                            <tr class="hover:bg-ink/5 transition-colors">
                                <td class="px-6 py-4 font-mono text-coffee">#BRW-8839</td>
                                <td class="px-6 py-4 font-serif text-ink">Dimas Pratama</td>
                                <td class="px-6 py-4 font-serif text-muted">Atomic Habits</td>
                                <td class="px-6 py-4 text-center"><span
                                        class="px-2 py-0.5 text-xs font-mono border border-ink/20 rounded bg-ink/5 text-ink">Dipinjam</span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-muted">10 Jun 2024</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PANEL SIDEBAR: Aksi & Status (1 Kolom) --}}
            <div class="space-y-6">
                {{-- Quick Actions --}}
                <div class="bg-surface border border-ink">
                    <div class="px-6 py-4 border-b border-ink">
                        <h3 class="font-serif font-semibold text-ink">Aksi Cepat</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="#"
                            class="flex items-center gap-3 p-3 border border-ink/15 rounded hover:bg-ink/5 hover:border-ink/30 transition-all group">
                            <div
                                class="p-1.5 bg-coffee/10 rounded border border-coffee/20 group-hover:bg-coffee/20 transition-colors">
                                <x-lucide-book-plus class="w-4 h-4 text-coffee" />
                            </div>
                            <span class="font-serif text-sm text-ink group-hover:text-coffee transition-colors">Input
                                Buku Baru</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 p-3 border border-ink/15 rounded hover:bg-ink/5 hover:border-ink/30 transition-all group">
                            <div
                                class="p-1.5 bg-ink/5 rounded border border-ink/15 group-hover:bg-ink/10 transition-colors">
                                <x-lucide-user-plus class="w-4 h-4 text-muted group-hover:text-ink transition-colors" />
                            </div>
                            <span
                                class="font-serif text-sm text-ink group-hover:text-coffee transition-colors">Daftarkan
                                Anggota</span>
                        </a>
                    </div>
                </div>

                {{-- Catatan Sistem / Kurator --}}
                <div class="bg-surface border border-ink p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <x-lucide-pencil-line class="w-4 h-4 text-coffee" />
                        <h3 class="font-serif font-semibold text-ink text-sm">Catatan Kurator</h3>
                    </div>
                    <p class="font-serif text-sm leading-relaxed text-muted italic">
                        "Koleksi Fiksi Indonesia tahun 1980-1999 telah selesai didigitalisasi. Silakan lakukan
                        verifikasi metadata sebelum publikasi."
                    </p>
                    <p class="text-xs font-mono text-coffee/60 mt-3">— 07 Juni 2024, 09:15 WIB</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
