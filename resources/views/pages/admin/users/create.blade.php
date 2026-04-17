<x-layouts.app>
    <div class="max-w-4xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-2xl font-serif font-bold text-ink tracking-tight">Tambah Anggota Baru</h1>
                <p class="text-muted mt-1 font-serif text-sm">Lengkapi formulir berikut untuk mendaftarkan pengguna ke dalam arsip perpustakaan.</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="w-full md:w-auto px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center justify-center gap-2">
                <x-lucide-arrow-left class="w-4 h-4" /> Kembali
            </a>
        </div>

        {{-- ALERT: Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 rounded-md p-4">
                <div class="flex items-start gap-3">
                    <x-lucide-alert-circle class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" />
                    <div class="flex-1">
                        <h3 class="font-semibold text-red-900 mb-2">Ada kesalahan dalam form:</h3>
                        <ul class="list-disc list-inside space-y-1 text-sm text-red-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- 2. FORM UTAMA --}}
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data"
            class="bg-surface border border-ink">
            @csrf
            <div class="p-6 md:p-8 space-y-8">

                {{-- Seksi I: Identitas --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-id-card class="w-4 h-4 text-coffee" /> I. Identitas Pengguna
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="name" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Nama
                                Lengkap <span class="text-red-700">*</span></label>
                            <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap"
                                class="w-full px-4 py-2.5 bg-background border @error('name') border-red-500 @else border-ink @enderror text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Alamat
                                Email <span class="text-red-700">*</span></label>
                            <input type="email" id="email" name="email" placeholder="contoh@domain.com"
                                class="w-full px-4 py-2.5 bg-background border @error('email') border-red-500 @else border-ink @enderror text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ID Number --}}
                        <div>
                            <label for="id_number" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">ID / NIM / NIP</label>
                            <input type="text" id="id_number" name="id_number" placeholder="Contoh: 20230101"
                                class="w-full px-4 py-2.5 bg-background border @error('id_number') border-red-500 @else border-ink @enderror text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all"
                                value="{{ old('id_number') }}">
                            @error('id_number')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div>
                            <label for="role" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Tipe
                                Keanggotaan <span class="text-red-700">*</span></label>
                            <select id="role" name="role"
                                class="w-full px-4 py-2.5 bg-background border @error('role') border-red-500 @else border-ink @enderror text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all"
                                required>
                                <option value="">-- Pilih Role --</option>
                                <option value="anggota" @selected(old('role') === 'anggota')>Anggota</option>
                                <option value="petugas" @selected(old('role') === 'petugas')>Petugas</option>
                                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            </select>
                            @error('role')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Seksi II: Kontak & Status --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-phone class="w-4 h-4 text-coffee" /> II. Kontak & Status
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Telepon --}}
                        <div>
                            <label for="phone" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Nomor
                                Telepon</label>
                            <input type="tel" id="phone" name="phone" placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-2.5 bg-background border @error('phone') border-red-500 @else border-ink @enderror text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all"
                                value="{{ old('phone') }}">
                            @error('phone')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Status
                                Awal <span class="text-red-700">*</span></label>
                            <select id="status" name="status"
                                class="w-full px-4 py-2.5 bg-background border @error('status') border-red-500 @else border-ink @enderror text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all"
                                required>
                                <option value="">-- Pilih Status --</option>
                                <option value="aktif" @selected(old('status') === 'aktif')>Aktif</option>
                                <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                                <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                            </select>
                            @error('status')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label for="address" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Alamat
                                Lengkap</label>
                            <textarea id="address" name="address" rows="3" placeholder="Masukkan alamat domisili lengkap"
                                class="w-full px-4 py-2.5 bg-background border @error('address') border-red-500 @else border-ink @enderror text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all resize-y">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Seksi III: Keamanan --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-lock class="w-4 h-4 text-coffee" /> III. Keamanan & Autentikasi
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Password --}}
                        <div>
                            <label for="password" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Password
                                <span class="text-red-700">*</span></label>
                            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-2.5 bg-background border @error('password') border-red-500 @else border-ink @enderror text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all"
                                required>
                            @error('password')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Confirm --}}
                        <div>
                            <label for="password_confirmation" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Konfirmasi Password
                                <span class="text-red-700">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password"
                                class="w-full px-4 py-2.5 bg-background border @error('password') border-red-500 @else border-ink @enderror text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all"
                                required>
                        </div>
                    </div>
                </div>

                {{-- Seksi IV: Dokumen & Catatan --}}
                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-image class="w-4 h-4 text-coffee" /> IV. Dokumen & Catatan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Avatar --}}
                        <div class="md:col-span-1">
                            <label for="avatar" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Foto
                                Profil</label>
                            <div
                                class="border-2 border-dashed border-ink bg-background p-6 flex flex-col items-center justify-center text-center hover:bg-ink/5 transition-colors cursor-pointer group relative"
                                id="avatar-drop">
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
                                <x-lucide-upload-cloud
                                    class="w-8 h-8 text-coffee/60 group-hover:text-ink mb-2 transition-colors" />
                                <span class="text-xs font-serif text-muted group-hover:text-ink">Klik atau seret
                                    file</span>
                                <span class="text-[10px] font-mono text-coffee/50 mt-1">JPG, PNG, WebP (Maks. 2MB)</span>
                                @error('avatar')
                                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="avatar-preview" class="mt-2 hidden">
                                <img id="avatar-img" src="" alt="Preview" class="w-full border border-ink rounded">
                            </div>
                        </div>

                        {{-- Admin Notes --}}
                        <div class="md:col-span-2">
                            <label for="admin_notes" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Catatan
                                Admin</label>
                            <textarea id="admin_notes" name="admin_notes" rows="5" placeholder="Tulis catatan internal terkait pendaftaran ini (opsional)..."
                                class="w-full px-4 py-2.5 bg-background border @error('admin_notes') border-red-500 @else border-ink @enderror text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all resize-y">{{ old('admin_notes') }}</textarea>
                            @error('admin_notes')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- FOOTER ACTIONS --}}
            <div
                class="bg-[#f4f1eb] border-t border-ink px-6 md:px-8 py-5 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}"
                    class="w-full sm:w-auto px-6 py-2.5 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md text-center">
                    Batal
                </a>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2">
                    <x-lucide-check class="w-4 h-4" /> Simpan Data
                </button>
            </div>
        </form>

        {{-- INFO PANEL --}}
        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-info class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Catatan Sistem:</span> Password minimal 8 karakter dengan kombinasi huruf, angka, dan simbol disarankan. Setelah disimpan, anggota dapat login menggunakan email dan password ini.
            </div>
        </div>
    </div>

    <script>
        // Avatar Preview
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-img').src = e.target.result;
                    document.getElementById('avatar-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop
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
            const event = new Event('change', { bubbles: true });
            document.getElementById('avatar').dispatchEvent(event);
        });

        avatarDrop.addEventListener('click', () => {
            document.getElementById('avatar').click();
        });
    </script>
</x-layouts.app>
