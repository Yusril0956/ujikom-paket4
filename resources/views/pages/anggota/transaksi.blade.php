<x-layouts.app>
    <div class="max-w-5xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-3xl font-serif font-bold text-ink tracking-tight">Riwayat Peminjaman</h1>
                <p class="text-muted mt-1 font-serif">Catatan lengkap aktivitas peminjaman dan pengembalian buku Anda.</p>
            </div>
            <a href="{{ route('anggota.dashboard') }}" class="px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center gap-2 w-max">
                <x-lucide-arrow-left class="w-4 h-4" /> Kembali
            </a>
        </div>

        {{-- 2. FILTER & PENCARIAN --}}
        <div class="bg-surface border border-ink p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
            <form method="GET" action="{{ route('anggota.transaksi') }}" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative flex-1 sm:w-72">
                    <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-coffee/60" />
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul buku..."
                        class="w-full pl-9 pr-4 py-2 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-ink rounded-md">
                </div>
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-background border border-ink text-sm font-serif text-coffee focus:outline-none focus:ring-1 focus:ring-ink rounded-md">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="terlambat" {{ request('status') === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                </select>
            </form>
            <span class="text-xs font-mono text-muted">
                {{ $transaksis->total() }} transaksi ditemukan
            </span>
        </div>

        {{-- 3. LIST RIWAYAT (CARD BASED) --}}
        <div class="space-y-4">
            @forelse($transaksis as $txn)
            @php
            $badge = match($txn->status) {
            'pending' => ['text' => 'text-coffee', 'label' => 'Menunggu'],
            'dipinjam' => ['text' => 'text-ink', 'label' => 'Dipinjam'],
            'dikembalikan'=> ['text' => 'text-coffee', 'label' => 'Dikembalikan'],
            'terlambat' => ['text' => 'text-red-700','label' => 'Terlambat'],
            'ditolak' => ['text' => 'text-muted', 'label' => 'Ditolak'],
            'expired' => ['text' => 'text-muted', 'label' => 'Hangus'],
            default => ['text' => 'text-muted', 'label' => $txn->status],
            };
            @endphp

            <div class="bg-surface border border-ink p-4 md:p-5 hover:bg-ink/5 transition-colors">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                    {{-- Left: Book Info --}}
                    <div class="flex items-start gap-4 flex-1">
                        <div class="w-12 h-16 bg-background border border-ink flex-shrink-0 flex items-center justify-center rounded">
                            @if($txn->book->cover_image)
                            <img src="{{ $txn->book->cover_image }}"
                                alt="{{ $txn->book->title }}"
                                class="w-full h-full object-cover rounded">
                            @else
                            <x-lucide-book-open class="w-6 h-6 text-muted" />
                            @endif
                        </div>
                        <div>
                            <h3 class="font-serif font-semibold text-ink text-base leading-tight">{{ $txn->book->title }}</h3>
                            <p class="font-mono text-[10px] text-coffee mt-1">{{ $txn->book->author }} • ID: {{ $txn->book->formatted_id }}</p>
                            <p class="font-mono text-[10px] text-muted mt-0.5">Booking: {{ $txn->booking_code ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Center: Dates & Status --}}
                    <div class="flex flex-wrap items-center gap-4 md:gap-6 px-0 md:px-4 border-t border-ink pt-4 md:border-t-0 md:pt-0">
                        <div class="text-center min-w-[80px]">
                            <p class="font-mono text-[10px] uppercase tracking-wider text-muted">Pinjam</p>
                            <p class="font-mono text-xs text-ink mt-1">{{ $txn->borrowed_date?->format('d M Y') ?? '-' }}</p>
                        </div>
                        <div class="text-center min-w-[100px]">
                            <p class="font-mono text-[10px] uppercase tracking-wider text-muted">Jatuh Tempo</p>
                            <p class="font-mono text-xs {{ $txn->is_overdue && $txn->status !== 'dikembalikan' ? 'text-red-700 font-semibold' : 'text-ink' }} mt-1">
                                {{ $txn->due_date?->format('d M Y') ?? '-' }}
                            </p>
                        </div>
                        <div class="px-3 py-1 border border-ink rounded bg-surface">
                            <span class="text-[10px] font-mono uppercase tracking-wider {{ $badge['text'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Right: Fine & Actions --}}
                    <div class="flex flex-col md:items-end gap-3 border-t border-ink pt-4 md:border-t-0 md:pt-0">
                        <div class="text-right">
                            <p class="font-mono text-[10px] uppercase tracking-wider text-muted">Denda</p>
                            <p class="font-mono text-xs {{ $txn->fine_amount > 0 && !$txn->fine_paid ? 'text-red-700 font-semibold' : 'text-coffee' }} mt-1">
                                {{ $txn->fine_amount > 0 ? 'Rp '.number_format($txn->fine_amount, 0, ',', '.') : '-' }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            @if($txn->status === 'pending')
                                {{-- Pending: Show pickup info --}}
                                <a href="{{ route('anggota.transaksi.receipt', $txn->booking_code) }}"
                                    class="px-3 py-1.5 border border-coffee bg-coffee/5 text-xs font-serif text-coffee hover:bg-coffee/10 transition-colors rounded flex items-center gap-1">
                                    <x-lucide-receipt class="w-3.5 h-3.5" /> Lihat Bukti
                                </a>
                            @elseif($txn->status === 'dipinjam' && !$txn->is_overdue)
                                {{-- Active borrowing: Return button --}}
                                <form method="POST" action="{{ route('anggota.transaksi.return', $txn) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 border border-ink bg-surface text-xs font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-colors rounded" onclick="return confirm('Konfirmasi pengembalian buku ini?')">
                                        Kembalikan
                                    </button>
                                </form>
                                <a href="{{ route('anggota.transaksi.receipt', $txn->booking_code) }}"
                                    class="px-3 py-1.5 border border-coffee bg-coffee/5 text-xs font-serif text-coffee hover:bg-coffee/10 transition-colors rounded flex items-center gap-1">
                                    <x-lucide-receipt class="w-3.5 h-3.5" /> Bukti
                                </a>
                            @elseif($txn->is_overdue && !$txn->fine_paid)
                                {{-- Overdue: Return button and fine payment --}}
                                <form method="POST" action="{{ route('anggota.transaksi.return', $txn) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 border border-red-600 bg-red-50 text-xs font-serif text-red-600 hover:bg-red-100 transition-colors rounded">
                                        Kembalikan + Bayar Denda
                                    </button>
                                </form>
                            @elseif($txn->status === 'dikembalikan')
                                {{-- Returned: Show receipt only --}}
                                <a href="{{ route('anggota.transaksi.receipt', $txn->booking_code) }}"
                                    class="px-3 py-1.5 border border-coffee bg-coffee/5 text-xs font-serif text-coffee hover:bg-coffee/10 transition-colors rounded flex items-center gap-1">
                                    <x-lucide-receipt class="w-3.5 h-3.5" /> Bukti Kembalian
                                </a>
                            @elseif($txn->status === 'ditolak')
                                {{-- Rejected: Show rejection reason --}}
                                <button type="button" onclick="alert('Alasan Penolakan:\n\n{{ addslashes($txn->rejection_reason ?? 'Tidak tersedia') }}')" 
                                    class="px-3 py-1.5 border border-red-600 bg-red-50 text-xs font-serif text-red-600 hover:bg-red-100 transition-colors rounded">
                                    Lihat Alasan
                                </button>
                            @endif
                            <button type="button" onclick="document.getElementById('detail-{{ $txn->id }}').classList.toggle('hidden')" class="p-1.5 border border-ink bg-surface rounded hover:bg-ink/5 transition-colors" title="Lihat Detail">
                                <x-lucide-chevron-down class="w-4 h-4 text-coffee" />
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Expandable Detail Section --}}
                <div id="detail-{{ $txn->id }}" class="hidden mt-4 pt-4 border-t border-ink bg-background p-3 rounded">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="font-mono text-[10px] uppercase tracking-wider text-coffee mb-1">Diverifikasi Oleh</p>
                            <p class="font-serif text-ink">{{ $txn->verifiedBy?->name ?? 'Belum diverifikasi' }}</p>
                            <p class="font-mono text-[10px] text-muted mt-0.5">{{ $txn->verified_at?->format('d M Y, H:i') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-[10px] uppercase tracking-wider text-coffee mb-1">Catatan Admin</p>
                            <p class="font-serif text-muted">{{ $txn->notes ?? 'Tidak ada catatan internal' }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-[10px] uppercase tracking-wider text-coffee mb-1">Kondisi Buku</p>
                            <p class="font-serif text-ink">Baik / Sesuai Katalog</p>
                            @if($txn->returned_date)
                            <p class="font-mono text-[10px] text-muted mt-0.5">Dikembalikan: {{ $txn->returned_date->format('d M Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="border border-ink bg-surface p-12 text-center">
                <x-lucide-book-open class="w-10 h-10 mx-auto text-muted mb-3 opacity-40" />
                <p class="font-serif text-muted">Belum ada riwayat peminjaman.</p>
                <a href="{{ route('books.index') }}" class="text-coffee hover:text-ink mt-2 inline-block font-serif">Jelajahi katalog →</a>
            </div>
            @endforelse
        </div>

        {{-- 4. PAGINATION --}}
        <x-pagination :paginator="$transaksis" />

        {{-- 5. INFO PANEL --}}
        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-info class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Tips:</span> Klik ikon <x-lucide-chevron-down class="w-3 h-3 inline" /> pada setiap kartu untuk melihat detail verifikasi dan catatan admin. Status <span class="text-ink font-semibold">Menunggu</span> berarti pengajuan Anda sedang diproses oleh petugas perpustakaan.
            </div>
        </div>
    </div>
</x-layouts.app>