<x-layouts.app>
    <div class="max-w-7xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-3xl font-serif font-bold text-ink tracking-tight">Katalog Perpustakaan</h1>
                <p class="text-muted mt-1 font-serif">Arsip koleksi buku fisik, digital, dan referensi langka.</p>
            </div>
            <a href="{{ route('admin.books.create') }}"
                class="px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center gap-2 w-max">
                <x-lucide-book-plus class="w-4 h-4" /> Tambah Buku
            </a>
        </div>

        {{-- 2. FILTER & PENCARIAN --}}
        <div class="bg-surface border border-ink p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative flex-1 sm:w-72">
                    <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-coffee/60" />
                    <input type="text" placeholder="Cari judul, ISBN, atau pengarang..."
                        class="w-full pl-9 pr-4 py-2 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-ink rounded-md">
                </div>
                <select
                    class="px-3 py-2 bg-background border border-ink text-sm font-serif text-coffee focus:outline-none focus:ring-1 focus:ring-ink rounded-md">
                    <option value="">Semua Kategori</option>
                    <option value="fiksi">Fiksi & Sastra</option>
                    <option value="sejarah">Sejarah & Arkeologi</option>
                    <option value="sains">Sains & Teknologi</option>
                    <option value="arsip">Dokumen Arsip</option>
                </select>
            </div>
            <span class="text-xs font-mono text-muted">Menampilkan 48 dari 12.458 entri</span>
        </div>

        {{-- 3. GRID KATALOG BUKU --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @php
                $books = [
                    [
                        'id' => 'BK-0892',
                        'title' => 'Filosofi Teras',
                        'author' => 'Henry Manampiring',
                        'isbn' => '978-602-291-578-0',
                        'year' => '2018',
                        'category' => 'Filsafat',
                        'status' => 'tersedia',
                        'img' => 'https://picsum.photos/seed/filosofi/400/600',
                    ],
                    [
                        'id' => 'BK-0411',
                        'title' => 'Laut Bercerita',
                        'author' => 'Leila S. Chudori',
                        'isbn' => '978-602-291-123-4',
                        'year' => '2017',
                        'category' => 'Sastra',
                        'status' => 'dipinjam',
                        'img' => 'https://picsum.photos/seed/laut/400/600',
                    ],
                    [
                        'id' => 'BK-1024',
                        'title' => 'Sapiens: Riwayat Singkat',
                        'author' => 'Yuval Noah Harari',
                        'isbn' => '978-602-291-567-8',
                        'year' => '2015',
                        'category' => 'Sejarah',
                        'status' => 'tersedia',
                        'img' => 'https://picsum.photos/seed/sapiens/400/600',
                    ],
                    [
                        'id' => 'BK-0077',
                        'title' => 'Atomic Habits',
                        'author' => 'James Clear',
                        'isbn' => '978-0-7352-1178-2',
                        'year' => '2018',
                        'category' => 'Self-Help',
                        'status' => 'reservasi',
                        'img' => 'https://picsum.photos/seed/atomic/400/600',
                    ],
                    [
                        'id' => 'BK-1502',
                        'title' => 'Bumi Manusia',
                        'author' => 'Pramoedya A. Toer',
                        'isbn' => '978-979-9023-00-5',
                        'year' => '1980',
                        'category' => 'Sastra Klasik',
                        'status' => 'tersedia',
                        'img' => 'https://picsum.photos/seed/bumi/400/600',
                    ],
                    [
                        'id' => 'BK-0991',
                        'title' => 'The Art of War',
                        'author' => 'Sun Tzu',
                        'isbn' => '978-0-14-043299-7',
                        'year' => '1998',
                        'category' => 'Sejarah Militer',
                        'status' => 'dipinjam',
                        'img' => 'https://picsum.photos/seed/war/400/600',
                    ],
                    [
                        'id' => 'BK-2048',
                        'title' => 'Dune',
                        'author' => 'Frank Herbert',
                        'isbn' => '978-0-441-17271-9',
                        'year' => '1965',
                        'category' => 'Fiksi Ilmiah',
                        'status' => 'tersedia',
                        'img' => 'https://picsum.photos/seed/dune/400/600',
                    ],
                    [
                        'id' => 'BK-0331',
                        'title' => 'Sejarah Indonesia Modern',
                        'author' => 'M.C. Ricklefs',
                        'isbn' => '978-979-433-008-4',
                        'year' => '2001',
                        'category' => 'Sejarah',
                        'status' => 'nonaktif',
                        'img' => 'https://picsum.photos/seed/sejarah/400/600',
                    ],
                ];
            @endphp

            @foreach ($books as $book)
                <div
                    class="bg-surface border border-ink flex flex-col group hover:shadow-[var(--elevation-1)] transition-all">
                    {{-- Cover Image --}}
                    <div class="aspect-[2/3] bg-ink/5 border-b border-ink overflow-hidden relative">
                        <img src="{{ $book['img'] }}" alt="{{ $book['title'] }}" loading="lazy"
                            class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute top-3 left-3">
                            @php
                                $badgeText = match ($book['status']) {
                                    'tersedia' => 'text-ink',
                                    'dipinjam' => 'text-coffee',
                                    'reservasi' => 'text-muted',
                                    'nonaktif' => 'text-muted',
                                };
                                $badgeBg = match ($book['status']) {
                                    'tersedia' => 'bg-surface',
                                    'dipinjam' => 'bg-coffee/10',
                                    'reservasi' => 'bg-ink/5',
                                    'nonaktif' => 'bg-muted/10',
                                };
                            @endphp
                            <span
                                class="px-2 py-1 text-[10px] font-mono uppercase tracking-wider border border-ink rounded shadow-sm {{ $badgeText }} {{ $badgeBg }}">
                                {{ $book['status'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex-1">
                            <h3 class="font-serif font-semibold text-ink text-base leading-tight line-clamp-2">
                                {{ $book['title'] }}</h3>
                            <p class="font-serif text-sm text-muted mt-1">{{ $book['author'] }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span
                                    class="text-[10px] font-mono border border-ink px-2 py-0.5 rounded bg-ink/5 text-coffee">{{ $book['category'] }}</span>
                                <span class="text-[10px] font-mono text-muted">{{ $book['year'] }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-ink flex items-center justify-between">
                            <span class="text-[10px] font-mono text-muted">ID: {{ $book['id'] }}</span>
                            <div class="flex gap-2">
                                <a href="#" class="p-1.5 border border-ink rounded hover:bg-ink/5 transition-colors">
                                    <x-lucide-eye class="w-3.5 h-3.5 text-coffee/70 hover:text-ink" />
                                </a>
                                <button class="p-1.5 border border-ink rounded hover:bg-ink/5 transition-colors">
                                    <x-lucide-pencil class="w-3.5 h-3.5 text-coffee/70 hover:text-ink" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 4. PAGINATION --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border border-ink bg-surface p-4">
            <span class="text-xs font-mono text-muted">Menampilkan halaman 1 dari 156</span>
            <nav class="flex items-center gap-2">
                <button
                    class="px-3 py-1.5 border border-ink text-xs font-mono text-muted hover:bg-ink/5 hover:text-ink transition-colors rounded disabled:opacity-50"
                    disabled>← Prev</button>
                <button class="px-3 py-1.5 border border-ink bg-ink text-surface text-xs font-mono rounded">1</button>
                <button
                    class="px-3 py-1.5 border border-ink text-xs font-mono text-coffee hover:bg-ink/5 hover:text-ink transition-colors rounded">2</button>
                <button
                    class="px-3 py-1.5 border border-ink text-xs font-mono text-coffee hover:bg-ink/5 hover:text-ink transition-colors rounded">3</button>
                <span class="text-muted">...</span>
                <button
                    class="px-3 py-1.5 border border-ink text-xs font-mono text-coffee hover:bg-ink/5 hover:text-ink transition-colors rounded">156</button>
                <button
                    class="px-3 py-1.5 border border-ink text-xs font-mono text-coffee hover:bg-ink/5 hover:text-ink transition-colors rounded">Next
                    →</button>
            </nav>
        </div>

        {{-- 5. INFO PANEL --}}
        <div class="bg-surface border border-ink p-5">
            <div class="flex items-center gap-2 mb-3">
                <x-lucide-bookmark class="w-4 h-4 text-coffee" />
                <h3 class="font-serif font-semibold text-ink text-sm">Panduan Katalogisasi</h3>
            </div>
            <ul class="space-y-2 font-serif text-sm text-muted">
                <li class="flex items-start gap-2">
                    <span class="text-coffee mt-1">•</span>
                    <span>Pastikan ISBN dan tahun terbit sesuai dengan halaman hak cipta sebelum menyimpan entri.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-coffee mt-1">•</span>
                    <span>Buku dengan status <span class="text-ink font-semibold">Nonaktif</span> akan otomatis
                        disembunyikan dari pencarian publik.</span>
                </li>
            </ul>
        </div>
    </div>
</x-layouts.app>
