<x-layouts.app>
    <div class="mx-auto max-w-5xl space-y-6 px-2">
        <div class="flex flex-col justify-between gap-4 border-b border-ink pb-5 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-serif font-bold tracking-tight text-ink">Tambah Buku Baru</h1>
                <p class="mt-1 font-serif text-sm text-muted">Input data katalog lengkap dengan metadata, klasifikasi, dan berkas sampul.</p>
            </div>
            <x-ui.button href="{{ route('books.index') }}" variant="secondary" class="w-full md:w-auto px-4" icon="arrow-left">
                Kembali
            </x-ui.button>
        </div>

        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="border border-ink bg-surface">
            @csrf

            <div class="space-y-8 p-6 md:p-8">
                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-book-open-text class="w-4 h-4 text-coffee" /> I. Metadata Dasar
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <x-ui.input name="title" label="Judul Buku" placeholder="Masukkan judul lengkap buku" required />
                        </div>
                        <x-ui.input name="author" label="Pengarang / Penulis" placeholder="Nama lengkap penulis" required />
                        <x-ui.input name="publisher" label="Penerbit" placeholder="Nama penerbit atau lembaga" />
                        <x-ui.input name="published_year" label="Tahun Terbit" type="number" placeholder="Contoh: 2023" required />
                        <x-ui.input name="isbn" label="ISBN" placeholder="978-xxx-xxx-xxx-x" />
                        <x-ui.input name="issn" label="ISSN" placeholder="xxxx-xxxx" />
                    </div>
                </div>

                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-layers class="w-4 h-4 text-coffee" /> II. Klasifikasi & Lokasi
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-ui.select name="category" label="Kategori Utama" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Fiksi" @selected(old('category') == 'Fiksi')>Fiksi & Sastra</option>
                            <option value="Fiksi - Sastra Indonesia" @selected(old('category') == 'Fiksi - Sastra Indonesia')>Fiksi - Sastra Indonesia</option>
                            <option value="Sejarah & Antropologi" @selected(old('category') == 'Sejarah & Antropologi')>Sejarah & Antropologi</option>
                            <option value="Filosofi & Spiritualitas" @selected(old('category') == 'Filosofi & Spiritualitas')>Filosofi & Spiritualitas</option>
                            <option value="Pengembangan Diri & Bisnis" @selected(old('category') == 'Pengembangan Diri & Bisnis')>Pengembangan Diri & Bisnis</option>
                            <option value="Psikologi & Sains Kognitif" @selected(old('category') == 'Psikologi & Sains Kognitif')>Psikologi & Sains Kognitif</option>
                            <option value="Sastra" @selected(old('category') == 'Sastra')>Sastra</option>
                        </x-ui.select>
                        <x-ui.input name="classification" label="Klasifikasi DDC / LCC" placeholder="Contoh: 899.214 IND" />
                        <x-ui.input name="location" label="Lokasi Rak / Gudang" placeholder="Contoh: Rak A3 / Gudang B2" />
                        <x-ui.select name="availability_status" label="Status Ketersediaan" required>
                            <option value="tersedia" @selected(old('availability_status') == 'tersedia')>Tersedia</option>
                            <option value="dipinjam" @selected(old('availability_status') == 'dipinjam')>Sedang Dipinjam</option>
                            <option value="reservasi" @selected(old('availability_status') == 'reservasi')>Hanya Reservasi</option>
                            <option value="arsip" @selected(old('availability_status') == 'arsip')>Arsip Internal</option>
                            <option value="perbaikan" @selected(old('availability_status') == 'perbaikan')>Perbaikan / Konservasi</option>
                        </x-ui.select>
                    </div>
                </div>

                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-image-plus class="w-4 h-4 text-coffee" /> III. Sampul & Deskripsi
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="md:col-span-1">
                            <label class="mb-2 block font-mono text-xs uppercase tracking-wider text-coffee">Sampul Buku</label>
                            <div id="cover-preview" class="group flex cursor-pointer flex-col items-center justify-center border-2 border-dashed border-ink bg-background p-6 text-center transition-colors hover:bg-ink/5">
                                <div class="mb-3 flex h-36 w-24 items-center justify-center border border-ink bg-muted/20">
                                    <x-lucide-book-image class="w-8 h-8 text-muted/50" />
                                </div>
                                <span class="text-xs font-serif text-muted group-hover:text-ink">Klik atau seret file</span>
                                <span class="mt-1 font-mono text-[10px] text-coffee/50">JPG/PNG, Max 4MB (Rasio 2:3)</span>
                            </div>
                            <input type="file" name="cover_image" id="cover-input" class="hidden" accept="image/png,image/jpeg,image/jpg,image/webp">
                            @error('cover_image')
                                <p class="mt-1 font-mono text-[10px] text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-4 md:col-span-2">
                            <x-ui.textarea name="synopsis" label="Sinopsis / Abstrak"
                                placeholder="Tulis ringkasan isi buku atau deskripsi arsip..." rows="5" />
                            <x-ui.textarea name="curator_notes" label="Catatan Kurator / Admin"
                                placeholder="Catatan internal terkait kondisi fisik, edisi, atau hak cipta..." rows="3" />
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-settings class="w-4 h-4 text-coffee" /> IV. Inventory & Visibility
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-ui.input name="stock_total" label="Jumlah Stok" type="number" :value="old('stock_total', 1)" min="1" />
                        <x-ui.checkbox name="is_public" label="Tampilkan di katalog publik" :checked="old('is_public', true)" />
                    </div>
                </div>
            </div>

            <div class="bg-[#f4f1eb] border-t border-ink px-6 py-5 md:px-8 flex flex-col-reverse items-center justify-end gap-3 sm:flex-row">
                <x-ui.button href="{{ route('books.index') }}" variant="secondary" class="w-full sm:w-auto px-6">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto px-6" icon="check">
                    Simpan Katalog
                </x-ui.button>
            </div>
        </form>

        <x-ui.alert type="info" title="Panduan Input" icon="bookmark">
            Pastikan ISBN sesuai dengan halaman hak cipta. Buku berstatus <span class="text-coffee">Arsip Internal</span> tidak akan tampil di pencarian publik. Sampul harus berformat <span class="font-semibold text-ink">JPG/PNG</span> dengan rasio aspek <span class="font-semibold text-ink">2:3</span> untuk konsistensi katalog.
        </x-ui.alert>
    </div>

    <script>
        document.getElementById('cover-preview').addEventListener('click', () => {
            document.getElementById('cover-input').click();
        });

        document.getElementById('cover-input').addEventListener('change', function(e) {
            if (!this.files || !this.files[0]) return;

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

            const img = new Image();
            const reader = new FileReader();
            reader.onload = function(event) {
                img.src = event.target.result;
                img.onload = function() {
                    const ratio = img.width / img.height;
                    const expectedRatio = 2 / 3;
                    const tolerance = 0.1;

                    if (Math.abs(ratio - expectedRatio) > tolerance) {
                        if (!confirm(`Rasio gambar ${Math.round(ratio * 100) / 100} tidak sesuai 2:3. Lanjutkan?`)) {
                            document.getElementById('cover-input').value = '';
                            document.getElementById('cover-preview').innerHTML = `
                                <div class="mb-3 flex h-36 w-24 items-center justify-center border border-ink bg-muted/20">
                                    <x-lucide-book-image class="w-8 h-8 text-muted/50" />
                                </div>
                                <span class="text-xs font-serif text-muted">Klik atau seret file</span>
                                <span class="mt-1 font-mono text-[10px] text-coffee/50">JPG/PNG, Max 4MB (Rasio 2:3)</span>
                            `;
                            return;
                        }
                    }

                    document.getElementById('cover-preview').innerHTML =
                        `<img src="${event.target.result}" class="h-36 w-24 object-cover border border-ink">` +
                        `<span class="mt-2 text-xs font-serif text-muted">Ganti sampul</span>`;
                };
            };
            reader.readAsDataURL(file);
        });
    </script>
</x-layouts.app>
