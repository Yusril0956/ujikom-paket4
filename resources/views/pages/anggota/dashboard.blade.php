<x-layouts.app>
    <div class="max-w-7xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PERSONALIZED --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-3xl font-serif font-bold text-ink tracking-tight">Dashboard Anggota</h1>
                <p class="text-muted mt-1 font-serif">
                    Halo, {{ auth()->user()->name }}. Berikut ringkasan aktivitas peminjaman Anda.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 text-xs font-mono uppercase tracking-wider border border-ink rounded text-coffee bg-surface">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-1.5 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded">
                    <x-lucide-user class="w-4 h-4" /> Profil
                </a>
            </div>
        </div>

        {{-- 2. STATISTIK ANGGOTA --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
            $user = auth()->user();
            $activeLoans = $user->transaksis()->where('status', 'dipinjam')->count();
            $overdueCount = $user->transaksis()->where('status', 'terlambat')->count();
            $totalFines = $user->transaksis()->where('fine_paid', false)->sum('fine_amount');
            $booksBorrowed = $user->transaksis()->where('status', 'dikembalikan')->count();
            @endphp

            {{-- Active Loans --}}
            <div class="bg-surface border border-ink p-5 hover:border-coffee transition-colors group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-mono uppercase tracking-widest text-muted">Sedang Dipinjam</p>
                        <p class="text-3xl font-serif font-bold text-ink mt-2">{{ $activeLoans }}</p>
                        <p class="text-xs font-mono text-muted mt-1">dari maksimal 4 buku</p>
                    </div>
                    <div class="p-2 bg-ink/5 border border-ink rounded group-hover:bg-ink/10 transition-colors">
                        <x-lucide-book-open class="w-5 h-5 text-coffee" />
                    </div>
                </div>
                @if($activeLoans >= 4)
                <div class="mt-3 pt-3 border-t border-ink">
                    <span class="text-xs font-mono text-ink">Kuota penuh • Kembalikan buku untuk meminjam lagi</span>
                </div>
                @endif
            </div>

            {{-- Overdue --}}
            <div class="bg-surface border border-ink p-5 hover:border-red-700 transition-colors group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-mono uppercase tracking-widest text-muted">Terlambat Kembali</p>
                        <p class="text-3xl font-serif font-bold text-red-700 mt-2">{{ $overdueCount }}</p>
                        <p class="text-xs font-mono text-muted mt-1">perlu tindakan segera</p>
                    </div>
                    <div class="p-2 bg-red-700/5 border border-ink rounded group-hover:bg-red-700/10 transition-colors">
                        <x-lucide-alert-circle class="w-5 h-5 text-red-700" />
                    </div>
                </div>
                @if($overdueCount > 0)
                <div class="mt-3 pt-3 border-t border-ink">
                    <a href="#active-loans" class="text-xs font-mono text-coffee hover:text-ink">Lihat daftar ›</a>
                </div>
                @endif
            </div>

            {{-- Total Fines --}}
            <div class="bg-surface border border-ink p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-mono uppercase tracking-widest text-muted">Denda Belum Dibayar</p>
                        <p class="text-3xl font-serif font-bold text-ink mt-2">
                            Rp {{ number_format($totalFines, 0, ',', '.') }}
                        </p>
                        <p class="text-xs font-mono text-muted mt-1">
                            {{ $totalFines > 0 ? 'Segera lunasi' : 'Tidak ada tunggakan' }}
                        </p>
                    </div>
                    <div class="p-2 bg-ink/5 border border-ink rounded">
                        <x-lucide-credit-card class="w-5 h-5 text-coffee" />
                    </div>
                </div>
                @if($totalFines > 0)
                <div class="mt-3 pt-3 border-t border-ink">
                    <button class="text-xs font-mono text-coffee hover:text-ink">Bayar Sekarang ›</button>
                </div>
                @endif
            </div>

            {{-- History --}}
            <div class="bg-surface border border-ink p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-mono uppercase tracking-widest text-muted">Total Peminjaman</p>
                        <p class="text-3xl font-serif font-bold text-ink mt-2">{{ $booksBorrowed }}</p>
                        <p class="text-xs font-mono text-muted mt-1">buku telah dikembalikan</p>
                    </div>
                    <div class="p-2 bg-ink/5 border border-ink rounded">
                        <x-lucide-history class="w-5 h-5 text-coffee" />
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-ink">
                    <a href="{{ route('anggota.transaksi') }}" class="text-xs font-mono text-coffee hover:text-ink">Lihat riwayat ›</a>
                </div>
            </div>
        </div>

        {{-- 3. MAIN CONTENT: Active Loans + Quick Actions --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Active Loans Table --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-surface border border-ink" id="active-loans">
                    <div class="px-6 py-4 border-b border-ink flex items-center justify-between">
                        <h2 class="font-serif text-lg font-bold text-ink">Peminjaman Aktif</h2>
                        @if($activeLoans > 0)
                        <span class="text-xs font-mono text-muted">{{ $activeLoans }} buku</span>
                        @endif
                    </div>

                    @if($activeLoans > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-ink bg-ink/5">
                                    <th class="text-left px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Judul</th>
                                    <th class="text-center px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Pinjam</th>
                                    <th class="text-center px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Jatuh Tempo</th>
                                    <th class="text-right px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ink">
                                @foreach($user->transaksis()->with('book')->where('status', 'dipinjam')->latest()->get() as $loan)
                                <tr class="hover:bg-ink/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-serif text-ink">{{ $loan->book->title }}</p>
                                        <p class="font-mono text-[10px] text-muted">ID: {{ $loan->book->formatted_id }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono text-muted">
                                        {{ $loan->borrowed_date?->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono {{ $loan->is_overdue ? 'text-red-700 font-semibold' : 'text-muted' }}">
                                        {{ $loan->due_date?->format('d M Y') }}
                                        @if($loan->is_overdue)
                                        <p class="text-[10px] mt-1">+{{ $loan->due_date->diffInDays(now()) }} hari</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($loan->is_overdue)
                                        <button class="px-3 py-1.5 border border-ink bg-ink/5 text-xs font-serif text-ink hover:bg-ink hover:text-surface transition-colors rounded">
                                            Bayar Denda
                                        </button>
                                        @else
                                        <button class="px-3 py-1.5 border border-ink text-xs font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-colors rounded"
                                            onclick="confirm('Konfirmasi pengembalian buku ini?') || event.preventDefault()">
                                            Kembalikan
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="px-6 py-12 text-center">
                        <x-lucide-check-circle class="w-10 h-10 mx-auto text-muted mb-3 opacity-40" />
                        <p class="font-serif text-muted">Tidak ada peminjaman aktif.</p>
                        <a href="{{ route('books.index') }}" class="text-coffee hover:text-ink mt-2 inline-block font-serif">
                            Jelajahi katalog →
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Recent Activity --}}
                <div class="bg-surface border border-ink">
                    <div class="px-6 py-4 border-b border-ink">
                        <h3 class="font-serif text-lg font-bold text-ink">Aktivitas Terbaru</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @php
                        $recentActivity = $user->transaksis()
                        ->with(['book', 'verifiedBy'])
                        ->latest()
                        ->take(5)
                        ->get();
                        @endphp

                        @forelse($recentActivity as $activity)
                        <div class="flex items-start gap-3 pb-4 border-b border-ink last:border-b-0 last:pb-0">
                            <div class="w-2 h-2 mt-2 rounded-full {{ $activity->status === 'dikembalikan' ? 'bg-coffee' : ($activity->status === 'terlambat' ? 'bg-red-700' : 'bg-ink') }}"></div>
                            <div class="flex-1">
                                <p class="font-serif text-sm text-ink">
                                    @if($activity->status === 'dikembalikan')
                                    Mengembalikan <span class="font-semibold">{{ $activity->book->title }}</span>
                                    @elseif($activity->status === 'terlambat')
                                    <span class="text-red-700">Terlambat mengembalikan</span> {{ $activity->book->title }}
                                    @else
                                    Meminjam <span class="font-semibold">{{ $activity->book->title }}</span>
                                    @endif
                                </p>
                                <p class="font-mono text-[10px] text-muted mt-1">
                                    {{ $activity->updated_at?->format('d M Y, H:i') }} WIB
                                    @if($activity->verifiedBy)
                                    • Verifikasi: {{ $activity->verifiedBy->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @empty
                        <p class="font-serif text-muted text-center py-4">Belum ada aktivitas.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT: Quick Actions + Info --}}
            <div class="space-y-6">

                {{-- Quick Actions --}}
                <div class="bg-surface border border-ink p-5">
                    <h3 class="font-serif font-semibold text-ink mb-4 border-b border-ink pb-2">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('books.index') }}" class="flex items-center gap-3 p-3 border border-ink rounded hover:bg-ink/5 hover:border-coffee transition-all group">
                            <div class="p-2 bg-ink/5 border border-ink rounded group-hover:bg-ink/10 transition-colors">
                                <x-lucide-search class="w-4 h-4 text-coffee" />
                            </div>
                            <span class="font-serif text-sm text-ink group-hover:text-coffee transition-colors">Cari Buku</span>
                        </a>
                        <a href="{{ route('anggota.transaksi') }}" class="flex items-center gap-3 p-3 border border-ink rounded hover:bg-ink/5 hover:border-coffee transition-all group">
                            <div class="p-2 bg-ink/5 border border-ink rounded group-hover:bg-ink/10 transition-colors">
                                <x-lucide-bell class="w-4 h-4 text-coffee" />
                            </div>
                            <span class="font-serif text-sm text-ink group-hover:text-coffee transition-colors">Riwayat</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-3 border border-ink rounded hover:bg-ink/5 hover:border-coffee transition-all group">
                            <div class="p-2 bg-ink/5 border border-ink rounded group-hover:bg-ink/10 transition-colors">
                                <x-lucide-settings class="w-4 h-4 text-coffee" />
                            </div>
                            <span class="font-serif text-sm text-ink group-hover:text-coffee transition-colors">Pengaturan Akun</span>
                        </a>
                    </div>
                </div>

                {{-- Borrowing Rules Summary --}}
                <div class="bg-surface border border-ink p-5">
                    <h3 class="font-serif font-semibold text-ink mb-3 flex items-center gap-2">
                        <x-lucide-info class="w-4 h-4 text-coffee" />
                        Aturan Peminjaman
                    </h3>
                    <ul class="space-y-2 font-serif text-sm text-muted">
                        <li class="flex items-start gap-2">
                            <span class="text-coffee mt-1">•</span>
                            <span>Maksimal <span class="text-ink font-semibold">4 buku</span> per anggota.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-coffee mt-1">•</span>
                            <span>Jangka waktu peminjaman <span class="text-ink font-semibold">7 hari</span>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-coffee mt-1">•</span>
                            <span>Denda keterlambatan <span class="text-ink font-semibold">Rp 1.000/hari/buku</span>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-coffee mt-1">•</span>
                            <span>Perpanjangan dapat diajukan 1× sebelum jatuh tempo.</span>
                        </li>
                    </ul>
                    <a href="{{ route('rules') }}" class="block mt-4 text-xs font-mono text-coffee hover:text-ink">
                        Lihat tata tertib lengkap ›
                    </a>
                </div>

                {{-- Membership Info --}}
                <div class="bg-surface border border-ink p-5">
                    <h3 class="font-serif font-semibold text-ink mb-3">Status Keanggotaan</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">ID Anggota</span>
                            <span class="font-serif text-ink">USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Bergabung</span>
                            <span class="font-serif text-ink">{{ $user->created_at?->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Status</span>
                            <span class="px-2 py-0.5 text-xs font-mono border border-ink rounded uppercase tracking-wider text-ink bg-surface">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                    @if($user->status !== 'aktif')
                    <div class="mt-4 pt-3 border-t border-ink">
                        <p class="font-serif text-sm text-muted">
                            Akun Anda berstatus <span class="text-coffee">{{ ucfirst($user->status) }}</span>.
                            Hubungi admin untuk aktivasi.
                        </p>
                    </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- INFO PANEL --}}
        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-lightbulb class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Tips:</span> Aktifkan notifikasi email di <a href="{{ route('profile.edit') }}" class="text-coffee hover:text-ink underline">Pengaturan</a> agar tidak ketinggalan pengingat jatuh tempo peminjaman.
            </div>
        </div>
    </div>
</x-layouts.app>