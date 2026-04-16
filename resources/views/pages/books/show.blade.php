<x-layouts.app>
    <div class="max-w-6xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div class="flex items-center gap-4">
                <a href="{{ route('books.index') }}"
                    class="p-2 border border-ink rounded hover:bg-ink/5 transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5 text-coffee" />
                </a>
                <div>
                    <h1 class="text-2xl font-serif font-bold text-ink tracking-tight">Detail Buku</h1>
                    <p class="text-muted mt-1 font-serif text-sm">Informasi lengkap koleksi dan riwayat peminjaman.</p>
                </div>
            </div>
            <div class="flex gap-2">
                {{-- Admin Actions --}}
                @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('admin.books.edit', $book) }}"
                        class="px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center gap-2">
                        <x-lucide-pencil class="w-4 h-4" /> Edit
                    </a>
                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline"
                        onsubmit="return confirm('Yakin ingin menghapus buku ini? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 border border-red-500 bg-surface text-sm font-serif text-red-500 hover:text-white hover:bg-red-500 transition-all rounded-md flex items-center gap-2">
                            <x-lucide-trash-2 class="w-4 h-4" /> Hapus
                        </button>
                    </form>
                @elseif(auth()->check() && auth()->user()->isPetugas())
                    {{-- Petugas: Hanya Edit --}}
                    <a href="{{ route('admin.books.edit', $book) }}"
                        class="px-4 py-2 border border-amber-600 bg-amber-50 text-sm font-serif text-amber-700 hover:bg-amber-100 transition-all rounded-md flex items-center gap-2">
                        <x-lucide-pencil class="w-4 h-4" /> Edit
                    </a>
                @elseif(auth()->check() && auth()->user()->isAnggota())
                    {{-- Member: Pinjam Button --}}
                    <button onclick="openBorrowModal()"
                        class="px-4 py-2.5 bg-blue-600 text-surface border border-blue-600 text-sm font-serif hover:bg-blue-700 transition-all rounded-md flex items-center gap-2">
                        <x-lucide-book-plus class="w-4 h-4" /> Pinjam Buku
                    </button>
                @else
                    {{-- Guest: Login Required --}}
                    <a href="{{ route('login') }}"
                        class="px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center gap-2">
                        <x-lucide-log-in class="w-4 h-4" /> Masuk untuk Meminjam
                    </a>
                @endif
            </div>
        </div>

        {{-- 2. MAIN CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Cover & Quick Info --}}
            <div class="space-y-6">
                {{-- Book Cover --}}
                <div class="bg-surface border border-ink p-4">
                    <div class="aspect-[2/3] bg-ink/5 border border-ink overflow-hidden">
                        @if ($book->cover_image)
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                class="w-full h-full object-cover opacity-90">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-b from-ink/20 to-ink/5">
                                <x-lucide-book class="w-16 h-16 text-ink/40" />
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="px-3 py-1.5 border border-ink bg-ink/5 text-xs font-mono uppercase tracking-wider text-ink rounded">
                            {{ $book->is_public ? 'Tersedia' : 'Private' }}
                        </span>
                        <span class="font-mono text-xs text-muted">{{ $book->stock_available }} dari {{ $book->stock_total }} tersedia</span>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="bg-surface border border-ink p-5">
                    <h3 class="font-serif font-semibold text-ink mb-4 border-b border-ink pb-2">Informasi Stok</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Total Stok</span>
                            <span class="font-serif text-ink font-semibold">{{ $book->stock_total }} eksemplar</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Tersedia</span>
                            <span class="font-serif text-ink font-semibold">{{ $book->stock_available }} eksemplar</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Sedang Dipinjam</span>
                            <span class="font-serif text-coffee text-sm">{{ $book->stock_total - $book->stock_available }} eksemplar</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-muted">Terakhir Update</span>
                            <span class="font-serif text-coffee text-sm">{{ $book->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Metadata & History --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Basic Info --}}
                <div class="bg-surface border border-ink p-6">
                    <h2 class="text-xl font-serif font-bold text-ink mb-1">{{ $book->title }}</h2>
                    <p class="font-serif text-lg text-coffee mb-4">{{ $book->author }}</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-ink">
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Penerbit</p>
                            <p class="font-serif text-ink">{{ $book->publisher }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Tahun Terbit</p>
                            <p class="font-serif text-ink">{{ $book->published_year }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">ISBN</p>
                            <p class="font-serif text-ink">{{ $book->isbn ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Kategori</p>
                            <p class="font-serif text-ink">{{ $book->category }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">ID Buku</p>
                            <p class="font-serif text-ink">{{ $book->formatted_id ?? 'BK-' . str_pad($book->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Lokasi</p>
                            <p class="font-serif text-ink">{{ $book->location ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Synopsis --}}
                <div class="bg-surface border border-ink p-6">
                    <h3 class="font-serif font-semibold text-ink mb-3 border-b border-ink pb-2">Sinopsis</h3>
                    <p class="font-serif text-muted leading-relaxed">
                        {{ $book->synopsis ?? 'Sinopsis tidak tersedia' }}
                    </p>
                </div>

                {{-- Borrowing History --}}
                <div class="bg-surface border border-ink">
                    <div class="px-6 py-4 border-b border-ink flex items-center justify-between">
                        <h3 class="font-serif font-semibold text-ink">Riwayat Peminjaman Terkini</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-ink bg-ink/5">
                                    <th class="text-left px-6 py-3 font-mono text-xs uppercase tracking-wider text-muted">ID</th>
                                    <th class="text-left px-6 py-3 font-serif text-xs uppercase tracking-wider text-muted">Informasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ink">
                                <tr class="hover:bg-ink/5 transition-colors">
                                    <td colspan="2" class="px-6 py-4 text-center text-muted font-serif">
                                        Riwayat peminjaman akan ditampilkan di sini
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Internal Notes --}}
                <div class="bg-surface border border-ink p-5">
                    <h3 class="font-serif font-semibold text-ink mb-3 flex items-center gap-2">
                        <x-lucide-file-text class="w-4 h-4 text-coffee" />
                        Catatan Kurator
                    </h3>
                    <p class="font-serif text-sm text-muted leading-relaxed">
                        {{ $book->curator_notes ?? 'Tidak ada catatan' }}
                    </p>
                    <p class="text-xs font-mono text-coffee/60 mt-3">— Diperbarui: {{ $book->updated_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- 3. INFO PANEL --}}
        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-info class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Akses Cepat:</span> Gunakan tombol <span
                    class="text-coffee">Pinjam</span> untuk membuat permintaan peminjaman. Tombol <span
                    class="text-coffee">Edit</span> untuk mengubah metadata atau status ketersediaan buku.
            </div>
        </div>
    </div>

    {{-- BORROW REQUEST MODAL (for members) --}}
    @if(auth()->check() && auth()->user()->isAnggota())
        <div id="borrowModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-surface border border-ink rounded-lg max-w-md w-full shadow-lg">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-ink flex items-center justify-between">
                    <h2 class="text-lg font-serif font-bold text-ink">Permintaan Peminjaman</h2>
                    <button onclick="closeBorrowModal()" class="text-coffee hover:text-ink">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>

                {{-- Body --}}
                <form action="{{ route('books.borrow', $book) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">

                    {{-- Book Info Display --}}
                    <div class="bg-ink/5 border border-ink rounded p-3">
                        <p class="font-mono text-xs uppercase tracking-wider text-coffee mb-1">Buku yang Diminta</p>
                        <p class="font-serif font-semibold text-ink">{{ $book->title }}</p>
                        <p class="font-serif text-sm text-muted mt-1">{{ $book->author }}</p>
                    </div>

                    {{-- Availability Info --}}
                    <div class="bg-warning/10 border border-warning rounded p-3">
                        <p class="font-mono text-xs text-warning font-semibold">
                            <x-lucide-alert-circle class="w-3.5 h-3.5 inline mr-1" />
                            Stok Tersedia: <span class="text-ink font-bold">{{ $book->stock_available }} / {{ $book->stock_total }}</span>
                        </p>
                    </div>

                    {{-- Notes (Optional) --}}
                    <div>
                        <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="notes" rows="3" placeholder="Misalnya: Perlu untuk tugas, dll..."
                            class="w-full px-3 py-2 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-ink rounded-md"></textarea>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 pt-4 border-t border-ink">
                        <button type="button" onclick="closeBorrowModal()"
                            class="flex-1 px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md">
                            Kirim Permintaan
                        </button>
                    </div>

                    {{-- Info Text --}}
                    <p class="font-serif text-xs text-muted text-center">
                        Permintaan Anda akan diverifikasi oleh admin dalam waktu singkat. Anda akan menerima notifikasi ketika disetujui.
                    </p>
                </form>
            </div>
        </div>

        <script>
            function openBorrowModal() {
                document.getElementById('borrowModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeBorrowModal() {
                document.getElementById('borrowModal').classList.add('hidden');
                document.body.style.overflow = '';
            }

            // Close modal when clicking outside
            document.getElementById('borrowModal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBorrowModal();
                }
            });

            // Close with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('borrowModal').classList.contains('hidden')) {
                    closeBorrowModal();
                }
            });
        </script>
    @endif
</x-layouts.app>
