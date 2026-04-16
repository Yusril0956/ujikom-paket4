<x-layouts.app>
    <div class="max-w-6xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.transaksi.index') }}"
                    class="p-2 border border-ink rounded hover:bg-ink/5 transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5 text-coffee" />
                </a>
                <div>
                    <h1 class="text-2xl font-serif font-bold text-ink tracking-tight">Detail Transaksi {{ $transaksi->formatted_id }}</h1>
                    <p class="text-muted mt-1 font-serif text-sm">Riwayat lengkap, log status, dan aksi peminjaman.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <span
                    class="px-3 py-1.5 border border-ink {{ $transaksi->status_badge['bg'] }} text-xs font-mono uppercase tracking-wider {{ $transaksi->status_badge['text'] }} rounded">{{ $transaksi->status_badge['label'] }}</span>
                <button
                    onclick="window.print()"
                    class="px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center gap-2">
                    <x-lucide-printer class="w-4 h-4" /> Cetak Struk
                </button>
            </div>
        </div>

        {{-- 2. GRID CONTENT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN: Info & Timeline --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Transaction Details --}}
                <div class="bg-surface border border-ink p-6">
                    <h2 class="text-lg font-serif font-semibold text-ink border-b border-ink pb-3 mb-4">Informasi
                        Peminjaman</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Tanggal Pinjam</p>
                            <p class="font-serif text-ink text-lg">{{ $transaksi->borrowed_date?->format('d M Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Jatuh Tempo</p>
                            <p class="font-serif {% if $transaksi->is_overdue %}text-red-700 font-semibold{% else %}text-ink{% endif %} text-lg">{{ $transaksi->due_date?->format('d M Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Buku Dipinjam</p>
                            <p class="font-serif text-ink">{{ $transaksi->book->title }}</p>
                            <p class="font-mono text-xs text-muted mt-1">ISBN: {{ $transaksi->book->isbn }} | Rak: {{ $transaksi->book->location }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Petugas Pencatat</p>
                            <p class="font-serif text-ink">{{ $transaksi->verifiedBy?->name ?? 'Sistem' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Member Info --}}
                <div class="bg-surface border border-ink p-6">
                    <h2 class="text-lg font-serif font-semibold text-ink border-b border-ink pb-3 mb-4">Data Peminjam
                    </h2>
                    <div class="flex items-start gap-4">
                        <img src="{{ asset('storage/' . $transaksi->user->avatar) }}" alt="{{ $transaksi->user->name }}" 
                            class="w-16 h-16 bg-background border border-ink rounded-full object-cover">
                        <div>
                            <h3 class="font-serif text-xl font-bold text-ink">{{ $transaksi->user->name }}</h3>
                            <p class="font-mono text-sm text-coffee mt-1">{{ $transaksi->user->formatted_id }} | {{ ucfirst($transaksi->user->role) }} | {{ ucfirst($transaksi->user->status) }}</p>
                            <p class="font-serif text-sm text-muted mt-2">{{ $transaksi->user->email }} | {{ $transaksi->user->phone }}</p>
                        </div>
                    </div>
                </div>

                {{-- Activity Timeline --}}
                <div class="bg-surface border border-ink p-6">
                    <h2 class="text-lg font-serif font-semibold text-ink border-b border-ink pb-3 mb-4">Log Aktivitas
                    </h2>
                    <div class="space-y-6 pl-4 border-l-2 border-ink">
                        @forelse($activityLog as $log)
                            <div class="relative">
                                <span
                                    class="absolute -left-[25px] top-1.5 w-4 h-4 bg-ink rounded-full border-4 border-surface"></span>
                                <p class="font-mono text-xs text-muted">{{ $log['timestamp']->format('d M Y, H:i') }} WIB</p>
                                <p class="font-serif text-ink mt-1">{{ $log['action'] }} <span class="text-coffee">oleh {{ $log['by'] }}</span></p>
                            </div>
                        @empty
                            <p class="font-serif text-muted">Belum ada aktivitas tercatat</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Actions & Notes --}}
            <div class="space-y-6">
                {{-- Quick Actions --}}
                <div class="bg-surface border border-ink p-5">
                    <h3 class="font-serif font-semibold text-ink mb-4">Aksi Tersedia</h3>
                    <div class="space-y-3">
                        @if ($transaksi->status === 'dipinjam' || $transaksi->status === 'terlambat')
                            <form method="POST" action="{{ route('admin.transaksi.return', $transaksi) }}" class="w-full">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="w-full px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2"
                                    onclick="return confirm('Konfirmasi pengembalian buku?')">
                                    <x-lucide-rotate-ccw class="w-4 h-4" /> Proses Pengembalian
                                </button>
                            </form>
                        @endif
                        @if ($transaksi->status === 'pending')
                            <form method="POST" action="{{ route('admin.transaksi.approve', $transaksi) }}" class="w-full">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="w-full px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2"
                                    onclick="return confirm('Setujui peminjaman ini?')">
                                    <x-lucide-check-circle class="w-4 h-4" /> Setujui Peminjaman
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.transaksi.index') }}"
                            class="w-full px-4 py-2.5 bg-surface border border-ink text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center justify-center gap-2">
                            <x-lucide-arrow-left class="w-4 h-4" /> Kembali ke Daftar
                        </a>
                    </div>
                </div>

                {{-- Fine Info (if applicable) --}}
                @if ($transaksi->fine_amount > 0)
                    <div class="bg-red-50 border border-red-700 p-5 rounded">
                        <h3 class="font-serif font-semibold text-red-700 mb-3">⚠ Informasi Denda</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="font-mono text-muted">Jumlah Denda:</span>
                                <span class="font-serif font-bold text-red-700">Rp {{ number_format($transaksi->fine_amount) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-mono text-muted">Status Pembayaran:</span>
                                <span class="font-serif {{ $transaksi->fine_paid ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $transaksi->fine_paid ? '✓ Sudah Dibayar' : 'Belum Dibayar' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Internal Notes --}}
                <div class="bg-surface border border-ink p-5">
                    <h3 class="font-serif font-semibold text-ink mb-3">Catatan Transaksi</h3>
                    <p class="font-serif text-sm text-ink bg-background border border-ink p-3 rounded mb-3 min-h-[80px]">
                        {{ $transaksi->notes ?? 'Tidak ada catatan' }}
                    </p>
                    @if ($transaksi->rejection_reason && $transaksi->status === 'ditolak')
                        <div class="pt-3 border-t border-ink">
                            <p class="font-mono text-xs text-red-700 mb-2">Alasan Penolakan:</p>
                            <p class="font-serif text-sm text-ink">{{ $transaksi->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3. INFO PANEL --}}
        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-info class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Catatan Sistem:</span> Pengembalian melebihi jatuh tempo akan otomatis memicu perhitungan denda (Rp 1.000/hari). Pastikan semua data sudah tepat sebelum mengonfirmasi aksi.
            </div>
        </div>
    </div>
</x-layouts.app>
