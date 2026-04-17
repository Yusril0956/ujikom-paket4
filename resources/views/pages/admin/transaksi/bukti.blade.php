<x-layouts.app>
    <div class="flex flex-col items-center justify-center min-h-[calc(100vh-8rem)] px-4 py-8">
        <div class="w-full max-w-2xl bg-surface border border-ink p-6 md:p-10 relative">

            {{-- Header Bukti --}}
            <div class="text-center border-b border-ink pb-5 mb-6">
                <x-lucide-receipt class="w-8 h-8 text-coffee mx-auto mb-2" />
                <h1 class="text-lg font-serif font-bold text-ink uppercase tracking-wide">Bukti Pengajuan Peminjaman</h1>
                <p class="font-mono text-xs text-muted mt-1">Simpan halaman ini sebagai bukti pengambilan buku</p>
            </div>

            {{-- Data Utama --}}
            <div class="space-y-5">
                {{-- Kode Verifikasi --}}
                <div class="bg-background border border-ink p-5 text-center">
                    <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-coffee mb-2">Kode Booking</p>
                    <p class="text-2xl md:text-3xl font-mono font-bold text-ink tracking-[0.15em]">{{ $transaksi->booking_code }}</p>
                </div>

                {{-- Info Buku & Peminjam --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-ink p-4">
                        <p class="font-mono text-[10px] uppercase tracking-wider text-muted mb-1">Judul Buku</p>
                        @if($transaksi->book)
                            <p class="font-serif text-ink font-semibold text-base">{{ $transaksi->book->title }}</p>
                            <p class="font-mono text-xs text-coffee mt-1">{{ $transaksi->book->formatted_id }} | Penulis: {{ $transaksi->book->author }}</p>
                        @else
                            <p class="font-serif text-muted/50 italic">[Buku Dihapus]</p>
                        @endif
                    </div>
                    <div class="border border-ink p-4">
                        <p class="font-mono text-[10px] uppercase tracking-wider text-muted mb-1">Peminjam</p>
                        <p class="font-serif text-ink font-semibold text-base">{{ $transaksi->user->name }}</p>
                        <p class="font-mono text-xs text-coffee mt-1">{{ $transaksi->user->formatted_id }} | {{ ucfirst($transaksi->user->role) }}</p>
                    </div>
                </div>

                {{-- Waktu & Deadline --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-ink p-4">
                        <p class="font-mono text-[10px] uppercase tracking-wider text-muted mb-1">Waktu Pengajuan</p>
                        <p class="font-serif text-ink">{{ $transaksi->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div class="border border-ink p-4">
                        <p class="font-mono text-[10px] uppercase tracking-wider text-muted mb-1">Batas Pengambilan</p>
                        <p class="font-serif {{ $transaksi->pickup_deadline && now()->gt($transaksi->pickup_deadline) ? 'text-red-700' : 'text-ink' }} font-semibold">{{ $transaksi->pickup_deadline?->format('d M Y, H:i') }} WIB</p>
                        <p class="font-mono text-[10px] text-muted mt-1">Maksimal 24 Jam</p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="flex flex-col items-center justify-center gap-2 border border-ink bg-background p-3 text-center sm:flex-row">
                    @if ($transaksi->status === 'pending')
                        <x-lucide-clock class="w-4 h-4 text-coffee" />
                    @elseif ($transaksi->status === 'expired')
                        <x-lucide-x-circle class="w-4 h-4 text-red-700" />
                    @else
                        <x-lucide-check-circle class="w-4 h-4 text-green-700" />
                    @endif
                    <span
                        class="px-2 py-0.5 text-xs font-mono border border-ink bg-surface {{ $transaksi->status_badge['text'] }} {{ $transaksi->status_badge['bg'] }} uppercase tracking-wider rounded">
                        {{ $transaksi->status_badge['label'] }}
                    </span>
                </div>
            </div>

            {{-- Instruksi & Aksi --}}
            <div class="mt-6 pt-5 border-t border-ink space-y-4">
                <div class="bg-background border border-ink p-4">
                    <p class="font-serif text-sm text-muted leading-relaxed">
                        <span class="text-ink font-semibold">Langkah Selanjutnya:</span>
                        Tunjukkan kode di atas ke petugas perpustakaan dalam waktu <span
                            class="text-ink font-semibold">24 jam</span>
                        @if ($transaksi->status === 'pending')
                            . Petugas akan memverifikasi data secara fisik dan menyerahkan buku. Kode akan otomatis hangus jika melebihi batas waktu.
                        @elseif ($transaksi->status === 'expired')
                            . Sayangnya, batas waktu pengambilan Anda telah lewat. Silahkan buat pengajuan peminjaman baru.
                        @elseif ($transaksi->status === 'dipinjam')
                            . Buku sudah Anda terima. Harap kembalikan sebelum jatuh tempo: <span class="font-semibold">{{ $transaksi->due_date?->format('d M Y') }}</span>
                        @elseif ($transaksi->status === 'dikembalikan')
                            . Buku sudah dikembalikan. Terima kasih telah menggunakan layanan kami!
                        @else
                            . Pengajuan Anda telah ditolak. Silahkan coba lagi dengan data yang berbeda.
                        @endif
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <button
                        onclick="window.print()"
                        class="w-full sm:w-auto px-5 py-2.5 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-background transition-colors rounded flex items-center justify-center gap-2">
                        <x-lucide-printer class="w-4 h-4" /> Cetak / Simpan
                    </button>
                    <a href="{{ route('admin.transaksi.index') }}"
                        class="w-full sm:w-auto px-5 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-colors rounded flex items-center justify-center gap-2">
                        <x-lucide-arrow-left class="w-4 h-4" /> Kembali
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
