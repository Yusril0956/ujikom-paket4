<x-layouts.app>
    <div class="mx-auto max-w-4xl space-y-6 px-2">
        <div class="flex flex-col justify-between gap-4 border-b border-ink pb-5 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-serif font-bold tracking-tight text-ink">Tambah Anggota Baru</h1>
                <p class="mt-1 font-serif text-sm text-muted">Lengkapi formulir berikut untuk mendaftarkan pengguna ke dalam arsip perpustakaan.</p>
            </div>
            <x-ui.button href="{{ route('admin.users.index') }}" variant="secondary" class="w-full md:w-auto px-4" icon="arrow-left">
                Kembali
            </x-ui.button>
        </div>

        @if ($errors->any())
            <x-ui.alert type="danger" icon="alert-circle" title="Ada kesalahan dalam form:">
                <ul class="list-disc space-y-1 pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="border border-ink bg-surface">
            @csrf

            <div class="space-y-8 p-6 md:p-8">
                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-id-card class="w-4 h-4 text-coffee" /> I. Identitas Pengguna
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-ui.input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" required />
                        <x-ui.input name="email" label="Alamat Email" type="email" placeholder="contoh@domain.com" required />
                        <x-ui.input name="id_number" label="ID / NIM / NIP" placeholder="Contoh: 20230101" />

                        <x-ui.select name="role" label="Role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="anggota" @selected(old('role') === 'anggota')>Anggota</option>
                            @if (auth()->user()->isAdmin())
                                <option value="petugas" @selected(old('role') === 'petugas')>Petugas</option>
                                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            @endif
                        </x-ui.select>
                    </div>
                </div>

                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-phone class="w-4 h-4 text-coffee" /> II. Kontak & Status
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-ui.input name="phone" label="Nomor Telepon" type="tel" placeholder="08xxxxxxxxxx" />

                        <x-ui.select name="status" label="Status Awal" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif" @selected(old('status') === 'aktif')>Aktif</option>
                            <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                            <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                        </x-ui.select>

                        <div class="md:col-span-2">
                            <x-ui.textarea name="address" label="Alamat Lengkap" placeholder="Masukkan alamat domisili lengkap"
                                rows="3" />
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-lock class="w-4 h-4 text-coffee" /> III. Keamanan & Autentikasi
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-ui.input name="password" label="Password" type="password" placeholder="Minimal 8 karakter" required />
                        <x-ui.input name="password_confirmation" label="Konfirmasi Password" type="password"
                            placeholder="Ketik ulang password" required />
                    </div>
                </div>

                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-image class="w-4 h-4 text-coffee" /> IV. Dokumen & Catatan
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="md:col-span-1">
                            <label for="avatar" class="mb-2 block font-mono text-xs uppercase tracking-wider text-coffee">
                                Foto Profil
                            </label>
                            <div class="group relative flex cursor-pointer flex-col items-center justify-center border-2 border-dashed border-ink bg-background p-6 text-center transition-colors hover:bg-ink/5"
                                id="avatar-drop">
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
                                <x-lucide-upload-cloud class="mb-2 h-8 w-8 text-coffee/60 transition-colors group-hover:text-ink" />
                                <span class="text-xs font-serif text-muted group-hover:text-ink">Klik atau seret file</span>
                                <span class="mt-1 font-mono text-[10px] text-coffee/50">JPG, PNG, WebP (Maks. 2MB)</span>
                                @error('avatar')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="avatar-preview" class="mt-2 hidden">
                                <img id="avatar-img" src="" alt="Preview" class="w-full rounded border border-ink">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <x-ui.textarea name="admin_notes" label="Catatan Admin"
                                placeholder="Tulis catatan internal terkait pendaftaran ini (opsional)..." rows="5" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#f4f1eb] border-t border-ink px-6 py-5 md:px-8 flex flex-col-reverse items-center justify-end gap-3 sm:flex-row">
                <x-ui.button href="{{ route('admin.users.index') }}" variant="secondary" class="w-full sm:w-auto px-6">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto px-6" icon="check">
                    Simpan Data
                </x-ui.button>
            </div>
        </form>

        <x-ui.alert type="info" title="Catatan Sistem" icon="info">
            Password minimal 8 karakter dengan kombinasi huruf, angka, dan simbol disarankan. Setelah disimpan, anggota dapat login menggunakan email dan password ini.
        </x-ui.alert>
    </div>

    <script>
        document.getElementById('avatar')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('avatar-img').src = event.target.result;
                document.getElementById('avatar-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });

        const avatarDrop = document.getElementById('avatar-drop');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            avatarDrop.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            avatarDrop.addEventListener(eventName, () => avatarDrop.classList.add('bg-ink/5'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            avatarDrop.addEventListener(eventName, () => avatarDrop.classList.remove('bg-ink/5'), false);
        });

        avatarDrop.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('avatar').files = files;
            document.getElementById('avatar').dispatchEvent(new Event('change', { bubbles: true }));
        });

        avatarDrop.addEventListener('click', () => {
            document.getElementById('avatar').click();
        });
    </script>
</x-layouts.app>
