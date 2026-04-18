<x-layouts.app>
    <div class="mx-auto max-w-4xl space-y-6 px-2">
        <div class="flex flex-col justify-between gap-4 border-b border-ink pb-5 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-serif font-bold tracking-tight text-ink">Edit Data Anggota</h1>
                <p class="mt-1 font-serif text-sm text-muted">Ubah informasi profil pengguna {{ $user->name }}.</p>
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

        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="border border-ink bg-surface">
            @csrf
            @method('PUT')

            <div class="space-y-8 p-6 md:p-8">
                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-id-card class="w-4 h-4 text-coffee" /> I. Identitas Pengguna
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block font-mono text-xs uppercase tracking-wider text-coffee">ID Pengguna</label>
                            <div class="flex items-center gap-2 rounded border border-ink bg-[#f4f1eb] px-4 py-2.5 font-serif text-sm text-muted">
                                <x-lucide-lock class="w-4 h-4" />
                                {{ $user->formatted_id }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block font-mono text-xs uppercase tracking-wider text-coffee">Bergabung Sejak</label>
                            <div class="flex items-center gap-2 rounded border border-ink bg-[#f4f1eb] px-4 py-2.5 font-serif text-sm text-muted">
                                <x-lucide-calendar class="w-4 h-4" />
                                {{ $user->created_at->format('d M Y H:i') }}
                            </div>
                        </div>

                        <x-ui.input name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" :value="$user->name" required />
                        <x-ui.input name="email" label="Alamat Email" type="email" placeholder="contoh@domain.com" :value="$user->email" required />
                        <x-ui.input name="id_number" label="ID / NIM / NIP" placeholder="Contoh: 20230101" :value="$user->id_number" />

                        <x-ui.select name="role" label="Role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="anggota" @selected(old('role', $user->role) === 'anggota')>Anggota</option>
                            @if (auth()->user()->isAdmin())
                                <option value="petugas" @selected(old('role', $user->role) === 'petugas')>Petugas</option>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                            @endif
                        </x-ui.select>
                    </div>
                </div>

                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-phone class="w-4 h-4 text-coffee" /> II. Kontak & Status
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-ui.input name="phone" label="Nomor Telepon" type="tel" placeholder="08xxxxxxxxxx" :value="$user->phone" />
                        <x-ui.select name="status" label="Status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif" @selected(old('status', $user->status) === 'aktif')>Aktif</option>
                            <option value="pending" @selected(old('status', $user->status) === 'pending')>Pending</option>
                            <option value="nonaktif" @selected(old('status', $user->status) === 'nonaktif')>Nonaktif</option>
                        </x-ui.select>
                        <div class="md:col-span-2">
                            <x-ui.textarea name="address" label="Alamat Lengkap"
                                placeholder="Masukkan alamat domisili lengkap" rows="3" :value="$user->address" />
                        </div>
                    </div>
                </div>

                <div class="rounded-md border border-blue-200 bg-blue-50 p-4">
                    <h2 class="flex items-center gap-2 border-b border-blue-200 pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-lock class="w-4 h-4 text-coffee" /> III. Keamanan (Opsional)
                    </h2>
                    <p class="mt-2 mb-4 font-serif text-xs text-muted">Kosongkan field password jika tidak ingin mengubahnya.</p>
                    <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-ui.input name="password" label="Password Baru" type="password"
                            placeholder="Minimal 8 karakter (kosongkan jika tidak diubah)" />
                        <x-ui.input name="password_confirmation" label="Konfirmasi Password Baru" type="password"
                            placeholder="Ketik ulang password baru" />
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

                            @if ($user->avatar)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                    class="mb-3 aspect-square w-full rounded border border-ink object-cover">
                                <p class="mb-2 font-serif text-xs text-muted">Foto profil saat ini</p>
                            @endif

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
                                placeholder="Tulis catatan internal terkait pengguna ini (opsional)..." rows="5"
                                :value="$user->admin_notes" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#f4f1eb] border-t border-ink px-6 py-5 md:px-8 flex flex-col-reverse items-center justify-between gap-3 sm:flex-row">
                <div class="flex w-full gap-3 sm:w-auto">
                    <x-ui.button href="{{ route('admin.users.show', $user) }}" variant="secondary" class="flex-1 px-6 sm:flex-none">
                        Lihat Profil
                    </x-ui.button>
                    <x-ui.button href="{{ route('admin.users.index') }}" variant="secondary" class="flex-1 px-6 sm:flex-none">
                        Batal
                    </x-ui.button>
                </div>
                <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto px-6" icon="check">
                    Simpan Perubahan
                </x-ui.button>
            </div>
        </form>

        <x-ui.alert type="info" title="Catatan Sistem" icon="info">
            Perubahan data akan dicatat dengan user yang mengubah dan timestamp. Jika mengubah password, pengguna perlu login ulang dengan password yang baru.
        </x-ui.alert>
    </div>

    <script>
        document.getElementById('avatar')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            const maxSize = 2 * 1024 * 1024;

            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, atau WebP.');
                this.value = '';
                return;
            }

            if (file.size > maxSize) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                const avatarPreview = document.getElementById('avatar-preview');
                if (avatarPreview) {
                    avatarPreview.classList.remove('hidden');
                    avatarPreview.querySelector('img').src = event.target.result;
                }
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
