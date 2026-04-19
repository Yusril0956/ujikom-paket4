<x-layouts.app>
    <div class="mx-auto max-w-5xl space-y-6 px-2">
        <div class="flex flex-col justify-between gap-4 border-b border-ink pb-5 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-serif font-bold tracking-tight text-ink">Buat Peminjaman Baru</h1>
                <p class="mt-1 font-serif text-sm text-muted">Catat transaksi peminjaman koleksi dengan data anggota dan buku.</p>
            </div>
            <x-ui.button href="{{ route('admin.transaksi.index') }}" variant="secondary" class="w-full md:w-auto px-4" icon="arrow-left">
                Kembali
            </x-ui.button>
        </div>

        <form method="POST" action="{{ route('admin.transaksi.store') }}" class="border border-ink bg-surface">
            @csrf
            <div class="space-y-8 p-6 md:p-8">
                <div>
                    <x-ui.select name="user_id" label="Pilih Anggota" required>
                        <option value="">-- Pilih nama atau ID anggota --</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}" @selected(old('user_id') == $member->id)>
                                USR-{{ str_pad($member->id, 3, '0', STR_PAD_LEFT) }} | {{ $member->name }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div>
                    <x-ui.select name="book_id" label="Pilih Buku" required>
                        <option value="">-- Pilih judul, ISBN, atau ID buku --</option>
                        @foreach ($availableBooks as $book)
                            <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                                BK-{{ str_pad($book->id, 4, '0', STR_PAD_LEFT) }} | {{ $book->title }} ({{ $book->author }})
                                @if ($book->stock_available < 3)
                                    [Stok: {{ $book->stock_available }}]
                                @endif
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-calendar-check class="w-4 h-4 text-coffee" /> III. Detail Peminjaman
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-ui.input name="borrowed_date" label="Tanggal Peminjaman" type="date"
                            :value="old('borrowed_date', date('Y-m-d'))" :max="date('Y-m-d')" required />

                        <x-ui.input name="due_date" label="Tanggal Jatuh Tempo" type="date"
                            :value="old('due_date', date('Y-m-d', strtotime('+7 days')))" :min="date('Y-m-d')" required />

                        <div class="md:col-span-2">
                            <x-ui.textarea name="notes" label="Catatan Internal" :value="old('notes')"
                                placeholder="Kondisi fisik buku, permintaan khusus, atau catatan kurator..." rows="3"
                                help="Opsional. Maksimal 500 karakter." />
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-ink bg-ink/5 p-4">
                    <h3 class="mb-3 flex items-center gap-2 font-serif text-sm font-semibold text-ink">
                        <x-lucide-info class="w-4 h-4" /> Preview Transaksi
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-mono text-xs text-coffee">Durasi</span>
                            <p id="preview-duration" class="mt-1 font-serif text-ink">7 hari</p>
                        </div>
                        <div>
                            <span class="font-mono text-xs text-coffee">Status Awal</span>
                            <p class="mt-1 font-serif text-ink">
                                <span class="rounded border border-ink bg-surface px-2 py-0.5 font-mono text-xs text-coffee">Menunggu</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#f4f1eb] border-t border-ink px-6 py-5 md:px-8 flex flex-col-reverse items-center justify-end gap-3 sm:flex-row">
                <x-ui.button href="{{ route('admin.transaksi.index') }}" variant="secondary" class="w-full sm:w-auto px-6">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto px-6" icon="check">
                    Simpan Transaksi
                </x-ui.button>
            </div>
        </form>

        <x-ui.alert type="info" title="Aturan Sistem" icon="alert-circle">
            Batas peminjaman standar adalah <span class="text-ink font-semibold">7 hari</span>.
            Sistem akan otomatis menandai status <span class="text-coffee">Terlambat</span> jika melewati tanggal jatuh tempo.
            Pastikan anggota tidak melebihi kuota peminjaman sebelum menyimpan.
        </x-ui.alert>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const borrowedInput = document.querySelector('input[name="borrowed_date"]');
            const dueInput = document.querySelector('input[name="due_date"]');
            const previewDuration = document.getElementById('preview-duration');

            borrowedInput?.addEventListener('change', function() {
                const borrowed = new Date(this.value);
                if (!isNaN(borrowed)) {
                    const due = new Date(borrowed);
                    due.setDate(due.getDate() + 7);
                    const dueValue = due.toISOString().split('T')[0];

                    if (dueInput) {
                        dueInput.value = dueValue;
                        dueInput.min = this.value;
                    }
                    updatePreview(this.value, dueValue);
                }
            });

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

                if (days > 14) {
                    previewDuration.classList.add('text-red-700', 'font-semibold');
                } else {
                    previewDuration.classList.remove('text-red-700', 'font-semibold');
                }
            }

            if (borrowedInput?.value && dueInput?.value) {
                updatePreview(borrowedInput.value, dueInput.value);
            }
        });
    </script>
</x-layouts.app>
