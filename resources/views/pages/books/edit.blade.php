<x-layouts.app>
    <div class="max-w-5xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-2xl font-serif font-bold text-ink tracking-tight">Edit Buku</h1>
                <p class="text-muted mt-1 font-serif text-sm">Perbarui data katalog, metadata, dan berkas sampul.</p>
            </div>
            <a href="{{ route('books.index') }}"
                class="w-full md:w-auto px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center justify-center gap-2">
                <x-lucide-arrow-left class="w-4 h-4" /> Kembali
            </a>
        </div>

        {{-- 2. FORM UTAMA --}}
        <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data"
            class="bg-surface border border-ink">
            @csrf
            @method('PUT') {{-- ✅ Method spoofing untuk update --}}

            <div class="p-6 md:p-8 space-y-8">

                {{-- Seksi I: Metadata Dasar --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-book-open-text class="w-4 h-4 text-coffee" /> I. Metadata Dasar
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Title --}}
                        <div class="md:col-span-2">
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                                Judul Buku <span class="text-red-700">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $book->title) }}"
                                placeholder="Masukkan judul lengkap buku"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Author --}}
                        <div>
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                                Pengarang / Penulis <span class="text-red-700">*</span>
                            </label>
                            <input type="text" name="author" value="{{ old('author', $book->author) }}"
                                placeholder="Nama lengkap penulis"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('author') border-red-700 @enderror">
                            @error('author')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Publisher --}}
                        <div>
                            <label
                                class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Penerbit</label>
                            <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                                placeholder="Nama penerbit atau lembaga"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('publisher') border-red-700 @enderror">
                            @error('publisher')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Published Year --}}
                        <div>
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                                Tahun Terbit <span class="text-red-700">*</span>
                            </label>
                            <input type="number" name="published_year"
                                value="{{ old('published_year', $book->published_year) }}" placeholder="Contoh: 2023"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('published_year') border-red-700 @enderror">
                            @error('published_year')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ISBN --}}
                        <div>
                            <label
                                class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">ISBN</label>
                            <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                                placeholder="978-xxx-xxx-xxx-x"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('isbn') border-red-700 @enderror">
                            @error('isbn')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ISSN --}}
                        <div>
                            <label
                                class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">ISSN</label>
                            <input type="text" name="issn" value="{{ old('issn', $book->issn) }}"
                                placeholder="xxxx-xxxx"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('issn') border-red-700 @enderror">
                            @error('issn')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Seksi II: Klasifikasi & Lokasi --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-layers class="w-4 h-4 text-coffee" /> II. Klasifikasi & Lokasi
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Category --}}
                        <div>
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                                Kategori Utama <span class="text-red-700">*</span>
                            </label>
                            <select name="category"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('category') border-red-700 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Fiksi" @selected(old('category', $book->category) == 'Fiksi')>Fiksi & Sastra</option>
                                <option value="Fiksi - Sastra Indonesia" @selected(old('category', $book->category) == 'Fiksi - Sastra Indonesia')>Fiksi - Sastra Indonesia</option>
                                <option value="Sejarah & Antropologi" @selected(old('category', $book->category) == 'Sejarah & Antropologi')>Sejarah & Antropologi</option>
                                <option value="Filosofi & Spiritualitas" @selected(old('category', $book->category) == 'Filosofi & Spiritualitas')>Filosofi & Spiritualitas</option>
                                <option value="Pengembangan Diri & Bisnis" @selected(old('category', $book->category) == 'Pengembangan Diri & Bisnis')>Pengembangan Diri & Bisnis</option>
                                <option value="Psikologi & Sains Kognitif" @selected(old('category', $book->category) == 'Psikologi & Sains Kognitif')>Psikologi & Sains Kognitif</option>
                                <option value="Sastra" @selected(old('category', $book->category) == 'Sastra')>Sastra</option>
                            </select>
                            @error('category')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Classification --}}
                        <div>
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Klasifikasi
                                DDC / LCC</label>
                            <input type="text" name="classification"
                                value="{{ old('classification', $book->classification) }}"
                                placeholder="Contoh: 899.214 IND"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('classification') border-red-700 @enderror">
                            @error('classification')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Location --}}
                        <div>
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Lokasi Rak
                                / Gudang</label>
                            <input type="text" name="location" value="{{ old('location', $book->location) }}"
                                placeholder="Contoh: Rak A3 / Gudang B2"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('location') border-red-700 @enderror">
                            @error('location')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Availability Status --}}
                        <div>
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                                Status Ketersediaan <span class="text-red-700">*</span>
                            </label>
                            <select name="availability_status"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('availability_status') border-red-700 @enderror">
                                <option value="tersedia" @selected(old('availability_status', $book->availability_status) == 'tersedia')>Tersedia</option>
                                <option value="dipinjam" @selected(old('availability_status', $book->availability_status) == 'dipinjam')>Sedang Dipinjam</option>
                                <option value="reservasi" @selected(old('availability_status', $book->availability_status) == 'reservasi')>Hanya Reservasi</option>
                                <option value="arsip" @selected(old('availability_status', $book->availability_status) == 'arsip')>Arsip Internal</option>
                                <option value="perbaikan" @selected(old('availability_status', $book->availability_status) == 'perbaikan')>Perbaikan / Konservasi</option>
                            </select>
                            @error('availability_status')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Seksi III: Sampul & Deskripsi --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-image-plus class="w-4 h-4 text-coffee" /> III. Sampul & Deskripsi
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Upload Area --}}
                        <div class="md:col-span-1">
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Sampul
                                Buku</label>

                            {{-- Preview Cover yang Ada --}}
                            @if ($book->cover_image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover saat ini"
                                        id="current-cover" class="w-24 h-36 object-cover border border-ink rounded">
                                    <p class="text-[10px] font-mono text-muted mt-1 text-center">Cover saat ini</p>
                                </div>
                            @endif

                            <div id="cover-preview"
                                class="border-2 border-dashed border-ink bg-background p-6 flex flex-col items-center justify-center text-center hover:bg-ink/5 transition-colors cursor-pointer group">
                                <div
                                    class="w-24 h-36 bg-muted/20 border border-ink mb-3 flex items-center justify-center">
                                    <x-lucide-book-image class="w-8 h-8 text-muted/50" />
                                </div>
                                <span class="text-xs font-serif text-muted group-hover:text-ink">Klik atau seret
                                    file</span>
                                <span class="text-[10px] font-mono text-coffee/50 mt-1">JPG/PNG, Max 4MB (Rasio
                                    2:3)</span>
                            </div>
                            <input type="file" name="cover_image" id="cover-input" class="hidden"
                                accept="image/png,image/jpeg,image/jpg,image/webp">
                            @error('cover_image')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-[10px] font-mono text-muted mt-2">Kosongkan jika tidak ingin mengubah sampul
                            </p>
                        </div>

                        {{-- Text Fields --}}
                        <div class="md:col-span-2 space-y-4">

                            {{-- Synopsis --}}
                            <div>
                                <label
                                    class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Sinopsis
                                    / Abstrak</label>
                                <textarea name="synopsis" rows="5" placeholder="Tulis ringkasan isi buku atau deskripsi arsip..."
                                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all resize-y @error('synopsis') border-red-700 @enderror">{{ old('synopsis', $book->synopsis) }}</textarea>
                                @error('synopsis')
                                    <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Curator Notes --}}
                            <div>
                                <label
                                    class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Catatan
                                    Kurator / Admin</label>
                                <textarea name="curator_notes" rows="3"
                                    placeholder="Catatan internal terkait kondisi fisik, edisi, atau hak cipta..."
                                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all resize-y @error('curator_notes') border-red-700 @enderror">{{ old('curator_notes', $book->curator_notes) }}</textarea>
                                @error('curator_notes')
                                    <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Seksi IV: Inventory & Visibility (Opsional) --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-settings class="w-4 h-4 text-coffee" /> IV. Inventory & Visibility
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Stock Total --}}
                        <div>
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Jumlah
                                Stok</label>
                            <input type="number" name="stock_total"
                                value="{{ old('stock_total', $book->stock_total ?? 1) }}" min="1"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('stock_total') border-red-700 @enderror">
                            @error('stock_total')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-[10px] font-mono text-muted mt-1">Tersedia: <span
                                    class="text-ink font-semibold">{{ $book->stock_available ?? 0 }}</span> /
                                {{ $book->stock_total ?? 1 }}</p>
                        </div>

                        {{-- Is Public Toggle --}}
                        <div class="flex items-center gap-3 pt-6">
                            <input type="checkbox" name="is_public" id="is_public" value="1"
                                @checked(old('is_public', $book->is_public ?? true)) class="w-4 h-4 border-ink rounded focus:ring-ink">
                            <label for="is_public" class="text-sm font-serif text-ink">
                                Tampilkan di katalog publik
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Seksi V: Info Sistem (Read-Only) --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-info class="w-4 h-4 text-coffee" /> V. Informasi Sistem
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-mono text-xs uppercase text-coffee">ID Buku</span>
                            <p class="font-serif text-ink mt-1">
                                {{ $book->formatted_id ?? 'BK-' . str_pad($book->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <span class="font-mono text-xs uppercase text-coffee">Dibuat</span>
                            <p class="font-serif text-ink mt-1">{{ $book->created_at?->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="font-mono text-xs uppercase text-coffee">Terakhir Update</span>
                            <p class="font-serif text-ink mt-1">{{ $book->updated_at?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER ACTIONS --}}
            <div
                class="bg-[#f4f1eb] border-t border-ink px-6 md:px-8 py-5 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">

                {{-- Delete Button (Optional) --}}
                <button type="button" onclick="confirmDelete()"
                    class="w-full sm:w-auto px-6 py-2.5 border border-red-700 bg-red-50 text-sm font-serif text-red-700 hover:bg-red-100 transition-all rounded-md text-center mb-3 sm:mb-0">
                    <x-lucide-trash-2 class="w-4 h-4 inline mr-1" /> Hapus
                </button>

                <a href="{{ route('books.index') }}"
                    class="w-full sm:w-auto px-6 py-2.5 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md text-center">
                    Batal
                </a>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2">
                    <x-lucide-check class="w-4 h-4" /> Update Katalog
                </button>
            </div>
        </form>

        {{-- INFO PANEL --}}
        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-bookmark class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Panduan Edit:</span> Perubahan akan langsung tersimpan setelah
                klik "Update Katalog".
                Buku berstatus <span class="text-coffee">Arsip Internal</span> tidak akan tampil di pencarian publik.
                Upload sampul baru hanya jika diperlukan — file lama akan otomatis diganti.
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Script --}}
    <script>
        function confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus buku ini?\n\nTindakan ini tidak dapat dibatalkan.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.books.destroy', $book) }}";
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Cover Preview Logic (sama seperti create)
        document.getElementById('cover-preview')?.addEventListener('click', () => {
            document.getElementById('cover-input').click();
        });

        document.getElementById('cover-input')?.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                const maxSize = 4 * 1024 * 1024;

                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG, PNG, atau WebP.');
                    this.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 4MB.');
                    this.value = '';
                    return;
                }

                // Preview + validasi rasio 2:3
                const img = new Image();
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    img.onload = function() {
                        const ratio = img.width / img.height;
                        const expectedRatio = 2 / 3;
                        const tolerance = 0.1;

                        if (Math.abs(ratio - expectedRatio) > tolerance) {
                            if (!confirm(
                                    `Rasio gambar ${Math.round(ratio*100)/100} tidak sesuai 2:3. Lanjutkan?`
                                    )) {
                                document.getElementById('cover-input').value = '';
                                return;
                            }
                        }
                        // Update preview
                        document.getElementById('cover-preview').innerHTML =
                            `<img src="${e.target.result}" class="w-24 h-36 object-cover border border-ink rounded">` +
                            `<span class="text-xs font-serif text-muted mt-2">Ganti sampul</span>`;

                        // Hide current cover preview
                        const currentCover = document.getElementById('current-cover');
                        if (currentCover) currentCover.style.opacity = '0.5';
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layouts.app>
