<x-layouts.app>
    <div class="max-w-7xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-3xl font-serif font-bold text-ink tracking-tight">Transaksi Peminjaman</h1>
                <p class="text-muted mt-1 font-serif">Riwayat peminjaman, pengembalian, dan status keterlambatan koleksi.
                </p>
            </div>
            <a href="{{ route('admin.transaksi.create') }}"
                class="w-full md:w-auto px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2">
                <x-lucide-plus-circle class="w-4 h-4" /> Buat Peminjaman
            </a>
        </div>

        {{-- 2. FILTER & PENCARIAN --}}
        <form method="GET" action="{{ route('admin.transaksi.index') }}"
            class="bg-surface border border-ink p-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex w-full flex-col gap-3 md:w-auto md:flex-row">
                <div class="relative flex-1 md:min-w-[16rem]">
                    <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-coffee/60" />
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari ID, nama, atau judul..."
                        class="w-full pl-9 pr-4 py-2 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-ink rounded-md">
                </div>
                <select name="status"
                    class="w-full px-3 py-2 bg-background border border-ink text-sm font-serif text-coffee focus:outline-none focus:ring-1 focus:ring-ink rounded-md md:w-auto"
                    onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                    <option value="dipinjam" @selected(request('status') === 'dipinjam')>Dipinjam</option>
                    <option value="dikembalikan" @selected(request('status') === 'dikembalikan')>Dikembalikan</option>
                    <option value="terlambat" @selected(request('status') === 'terlambat')>Terlambat</option>
                    <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                    <option value="expired" @selected(request('status') === 'expired')>Hangus</option>
                </select>
                <button type="submit"
                    class="w-full px-4 py-2 border border-ink bg-ink text-surface text-sm font-serif hover:bg-ink/90 rounded-md md:w-auto">Cari</button>
            </div>
            <a href="{{ route('admin.transaksi.export') }}"
                class="w-full md:w-auto px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center justify-center gap-2">
                <x-lucide-download class="w-4 h-4" /> Ekspor CSV
            </a>
        </form>

        {{-- 3. TABEL TRANSAKSI --}}
        <div class="bg-surface border border-ink overflow-x-auto">
            <table class="panel-table w-full text-sm">
                <thead>
                    <tr class="border-b border-ink bg-ink/5">
                        <th class="text-left px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">ID</th>
                        <th class="text-left px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Anggota
                        </th>
                        <th class="text-left px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Judul
                            Buku</th>
                        <th class="text-center px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Pinjam
                        </th>
                        <th class="text-center px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">Kembali
                        </th>
                        <th class="text-center px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Status
                        </th>
                        <th class="text-right px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink">
                    @forelse($transaksi as $txn)
                        <tr class="hover:bg-ink/5 transition-colors">
                            <td class="px-6 py-4 font-mono text-coffee">{{ $txn->formatted_id }}</td>
                            <td class="px-6 py-4 font-serif text-ink">
                                {{ $txn->user->name }}
                                @if ($txn->booking_code && $txn->status === 'pending')
                                    <p class="font-mono text-[10px] text-coffee mt-1">{{ $txn->booking_code }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-serif text-muted">
                                @if($txn->book)
                                    {{ $txn->book->title }}
                                @else
                                    <span class="text-muted/50 italic">[Buku Dihapus]</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-muted">
                                {{ $txn->borrowed_date?->format('d M Y') ?? '-' }}</td>
                            <td
                                class="px-6 py-4 text-center font-mono text-muted {{ $txn->is_overdue ? 'text-red-700 font-semibold' : '' }}">
                                {{ $txn->due_date?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php $badge = $txn->status_badge; @endphp
                                <span
                                    class="px-2 py-0.5 text-xs font-mono border border-ink rounded uppercase tracking-wider {{ $badge['text'] }} {{ $badge['bg'] }}">
                                    {{ $badge['label'] }}
                                </span>
                                @if ($txn->fine_amount > 0 && !$txn->fine_paid)
                                    <p class="font-mono text-[10px] text-red-700 mt-1">Denda: Rp
                                        {{ number_format($txn->fine_amount) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    @if ($txn->status === 'pending')
                                        <form method="POST" action="{{ route('admin.transaksi.approve', $txn) }}"
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1.5 border border-ink bg-ink/5 text-xs font-serif text-ink hover:bg-ink hover:text-surface transition-colors rounded"
                                                onclick="return confirm('Setujui peminjaman ini?')">
                                                Setujui
                                            </button>
                                        </form>
                                        <button type="button"
                                            onclick="document.getElementById('reject-{{ $txn->id }}').classList.remove('hidden')"
                                            class="px-3 py-1.5 border border-ink text-xs font-serif text-coffee hover:bg-background hover:text-red-800 hover:border-red-800 transition-colors rounded">
                                            Tolak
                                        </button>
                                        {{-- Reject Modal Inline --}}
                                        <div id="reject-{{ $txn->id }}"
                                            class="hidden absolute right-0 mt-2 w-64 bg-surface border border-ink p-3 z-10">
                                            <form method="POST" action="{{ route('admin.transaksi.reject', $txn) }}">
                                                @csrf @method('PATCH')
                                                <label class="block font-mono text-[10px] text-coffee mb-1">Alasan
                                                    Penolakan</label>
                                                <textarea name="reason" rows="2" required
                                                    class="w-full px-2 py-1 bg-background border border-ink text-xs font-serif mb-2"></textarea>
                                                <div class="flex gap-2">
                                                    <button type="button"
                                                        onclick="document.getElementById('reject-{{ $txn->id }}').classList.add('hidden')"
                                                        class="flex-1 px-2 py-1 border border-ink text-xs rounded">Batal</button>
                                                    <button type="submit"
                                                        class="flex-1 px-2 py-1 bg-red-700 text-surface border border-red-700 text-xs rounded">Konfirmasi</button>
                                                </div>
                                            </form>
                                        </div>
                                    @elseif($txn->status === 'dipinjam')
                                        <form method="POST" action="{{ route('admin.transaksi.return', $txn) }}"
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1.5 border border-ink text-xs font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-colors rounded"
                                                onclick="return confirm('Konfirmasi pengembalian buku?')">
                                                Kembalikan
                                            </button>
                                        </form>
                                    @elseif($txn->status === 'terlambat')
                                        <form method="POST" action="{{ route('admin.transaksi.return', $txn) }}"
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1.5 border border-ink bg-ink/5 text-xs font-serif text-ink hover:bg-ink hover:text-surface transition-colors rounded">
                                                Proses Denda
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.transaksi.show', $txn) }}"
                                        class="p-1.5 border border-ink rounded hover:bg-ink/5 transition-colors group"
                                        title="Detail">
                                        <x-lucide-eye class="w-4 h-4 text-coffee/70 group-hover:text-ink" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="px-6 py-12 text-center text-muted font-serif border border-ink bg-surface">
                                <x-lucide-receipt class="w-10 h-10 mx-auto mb-3 opacity-40" />
                                <p>Belum ada transaksi terdaftar.</p>
                                <a href="{{ route('admin.transaksi.create') }}"
                                    class="text-coffee hover:text-ink mt-2 inline-block">Buat peminjaman pertama →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <x-pagination :paginator="$transaksi" />

        {{-- 5. INFO PANEL --}}
        <div class="bg-surface border border-ink p-5">
            <div class="flex items-center gap-2 mb-3">
                <x-lucide-alert-circle class="w-4 h-4 text-coffee" />
                <h3 class="font-serif font-semibold text-ink text-sm">Catatan Sistem</h3>
            </div>
            <ul class="space-y-2 font-serif text-sm text-muted">
                <li class="flex items-start gap-2">
                    <span class="text-coffee mt-1">•</span>
                    <span>Peminjaman maksimal <span class="text-ink font-semibold">7 hari</span>. Keterlambatan
                        dikenakan denda administrasi sesuai kebijakan perpustakaan.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-coffee mt-1">•</span>
                    <span>Transaksi dengan status <span class="text-coffee font-semibold">Menunggu</span>
                        akan otomatis hangus jika tidak diambil dalam <span class="text-ink font-semibold">24
                            jam</span>.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-coffee mt-1">•</span>
                    <span>Tombol <span class="text-ink font-semibold">Setujui</span> hanya muncul untuk transaksi
                        pending. Pastikan kode booking cocok dengan bukti yang ditunjukkan anggota.</span>
                </li>
            </ul>
        </div>
    </div>
</x-layouts.app>
