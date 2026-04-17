<x-layouts.app>
    <div class="max-w-5xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-2xl font-serif font-bold text-ink tracking-tight">Buat Peminjaman Baru</h1>
                <p class="text-muted mt-1 font-serif text-sm">Catat transaksi peminjaman koleksi dengan data anggota dan
                    buku.</p>
            </div>
            <a href="{{ route('admin.transaksi.index') }}"
                class="w-full md:w-auto px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center justify-center gap-2">
                <x-lucide-arrow-left class="w-4 h-4" /> Kembali
            </a>
        </div>

        {{-- 2. FORM UTAMA --}}
        <form method="POST" action="{{ route('admin.transaksi.store') }}" class="bg-surface border border-ink">
            @csrf
            <div class="p-6 md:p-8 space-y-8">

                {{-- Seksi I: Data Anggota --}}
                <div>
                    <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                        Cari / Pilih Anggota <span class="text-red-700">*</span>
                    </label>
                    <select name="user_id" required
                        class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('user_id') border-red-700 @enderror">
                        <option value="">-- Ketik nama atau ID anggota --</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}" @selected(old('user_id') == $member->id)>
                                USR-{{ str_pad($member->id, 3, '0', STR_PAD_LEFT) }} | {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Seksi II: Data Koleksi --}}
                <div>
                    <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                        Cari / Pilih Buku <span class="text-red-700">*</span>
                    </label>
                    <select name="book_id" required
                        class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('book_id') border-red-700 @enderror">
                        <option value="">-- Ketik judul, ISBN, atau ID buku --</option>
                        @foreach ($availableBooks as $book)
                            <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                                BK-{{ str_pad($book->id, 4, '0', STR_PAD_LEFT) }} | {{ $book->title }}
                                ({{ $book->author }})
                                @if ($book->stock_available < 3)
                                    <span class="text-red-700">[Stok: {{ $book->stock_available }}]</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('book_id')
                        <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Seksi III: Detail Peminjaman --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-calendar-check class="w-4 h-4 text-coffee" /> III. Detail Peminjaman
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Borrowed Date --}}
                        <div>
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                                Tanggal Peminjaman <span class="text-red-700">*</span>
                            </label>
                            <input type="date" name="borrowed_date"
                                value="{{ old('borrowed_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('borrowed_date') border-red-700 @enderror">
                            @error('borrowed_date')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                                Tanggal Jatuh Tempo <span class="text-red-700">*</span>
                            </label>
                            <input type="date" name="due_date"
                                value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}"
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('due_date') border-red-700 @enderror">
                            @error('due_date')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="md:col-span-2">
                            <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Catatan
                                Internal</label>
                            <textarea name="notes" rows="3" placeholder="Kondisi fisik buku, permintaan khusus, atau catatan kurator..."
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all resize-y @error('notes') border-red-700 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-[10px] font-mono text-muted mt-1">Opsional. Maksimal 500 karakter.</p>
                        </div>
                    </div>
                </div>

                {{-- Seksi IV: Preview & Info (Opsional) --}}
                <div class="bg-ink/5 border border-ink rounded-lg p-4">
                    <h3 class="font-serif font-semibold text-ink text-sm mb-3 flex items-center gap-2">
                        <x-lucide-info class="w-4 h-4" /> Preview Transaksi
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-mono text-xs text-coffee">Durasi</span>
                            <p id="preview-duration" class="font-serif text-ink mt-1">7 hari</p>
                        </div>
                        <div>
                            <span class="font-mono text-xs text-coffee">Status Awal</span>
                            <p class="font-serif text-ink mt-1"><span
                                    class="px-2 py-0.5 text-xs font-mono border border-ink rounded bg-surface text-coffee">Menunggu</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER ACTIONS --}}
            <div
                class="bg-[#f4f1eb] border-t border-ink px-6 md:px-8 py-5 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('admin.transaksi.index') }}"
                    class="w-full sm:w-auto px-6 py-2.5 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md text-center">
                    Batal
                </a>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2">
                    <x-lucide-check class="w-4 h-4" /> Simpan Transaksi
                </button>
            </div>
        </form>

        {{-- INFO PANEL --}}
        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-alert-circle class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Aturan Sistem:</span> Batas peminjaman standar adalah <span
                    class="text-ink font-semibold">7 hari</span>.
                Sistem akan otomatis menandai status <span class="text-coffee">Terlambat</span> jika melewati tanggal
                jatuh tempo.
                Pastikan anggota tidak melebihi kuota peminjaman sebelum menyimpan.
            </div>
        </div>
    </div>

    {{-- JavaScript: Auto-calc due date + preview --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const borrowedInput = document.querySelector('input[name="borrowed_date"]');
            const dueInput = document.querySelector('input[name="due_date"]');
            const previewDuration = document.getElementById('preview-duration');

            // Auto-calculate due date (+7 days) when borrowed_date changes
            borrowedInput?.addEventListener('change', function() {
                const borrowed = new Date(this.value);
                if (!isNaN(borrowed)) {
                    // Set due date = borrowed + 7 days
                    const due = new Date(borrowed);
                    due.setDate(due.getDate() + 7);
                    const dueValue = due.toISOString().split('T')[0];

                    if (dueInput) {
                        dueInput.value = dueValue;
                        dueInput.min = this.value; // Due date can't be before borrowed
                    }
                    updatePreview(this.value, dueValue);
                }
            });

            // Update preview when due_date changes manually
            dueInput?.addEventListener('change', function() {
                if (borrowedInput?.value && this.value) {
                    updatePreview(borrowedInput.value, this.value);
                }
            });

            function updatePreview(borrowed, due) {
                if (!previewDuration) return;
                const start = new Date(borrowed);
                const end = new Date(due);
                const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                previewDuration.textContent = days + ' hari';

                // Warn if > 14 days
                if (days > 14) {
                    previewDuration.classList.add('text-red-700', 'font-semibold');
                } else {
                    previewDuration.classList.remove('text-red-700', 'font-semibold');
                }
            }

            // Initialize preview on load
            if (borrowedInput?.value && dueInput?.value) {
                updatePreview(borrowedInput.value, dueInput.value);
            }
        });
    </script>
</x-layouts.app>
