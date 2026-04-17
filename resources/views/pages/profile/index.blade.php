<x-layouts.app>
    <div class="max-w-5xl mx-auto space-y-6 px-2">

        {{-- 1. HEADER PAGE --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-2xl font-serif font-bold text-ink tracking-tight">Profil Pengguna</h1>
                <p class="text-muted mt-1 font-serif text-sm">Kelola informasi akun, preferensi, dan keamanan.</p>
            </div>
            <a href="{{ auth()->user()->isAnggota() ? route('anggota.dashboard') : route('admin.dashboard') }}"
                class="w-full md:w-auto px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md flex items-center justify-center gap-2">
                <x-lucide-arrow-left class="w-4 h-4" /> Kembali
            </a>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="border border-ink bg-surface p-4 flex items-start gap-3">
                <x-lucide-check-circle class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
                <div class="font-serif text-sm text-ink">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        {{-- 2. FORM UTAMA --}}
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
            class="bg-surface border border-ink">
            @csrf
            @method('PATCH')
            <div class="p-6 md:p-8 space-y-8">

                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-user class="w-4 h-4 text-coffee" /> I. Identitas & Foto
                    </h2>
                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        <div class="flex-shrink-0">
                            <div
                                class="w-24 h-24 bg-background border border-ink rounded-full flex items-center justify-center overflow-hidden">
                                @if ($user->avatar && Storage::disk('public')->exists($user->avatar))
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                        class="w-full h-full object-cover"
                                        onerror="this.parentElement.innerHTML='<x-lucide-circle-user-round class=\'w-10 h-10 text-muted\' />'">
                                @else
                                    <x-lucide-circle-user-round class="w-10 h-10 text-muted" />
                                @endif
                            </div>

                            <label for="avatar-upload"
                                class="mt-3 w-full px-3 py-1.5 border border-ink bg-surface text-xs font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-colors rounded text-center cursor-pointer block">
                                Ganti Foto
                            </label>

                            <input type="file" name="avatar" id="avatar-upload" class="hidden"
                                accept="image/png,image/jpeg,image/jpg,image/webp">

                            @error('avatar')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name"
                                    class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Nama
                                    Lengkap <span class="text-red-700">*</span></label>
                                <input id="name" name="name" type="text"
                                    value="{{ old('name', $user->name) }}" required
                                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('name') border-red-700 @enderror">
                                @error('name')
                                    <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email"
                                    class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Email
                                    <span class="text-red-700">*</span></label>
                                <input id="email" name="email" type="email"
                                    value="{{ old('email', $user->email) }}" required
                                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('email') border-red-700 @enderror">
                                @error('email')
                                    <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                                @enderror
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <p class="font-mono text-[10px] text-coffee mt-1">Email belum terverifikasi. <a
                                            href="{{ route('verification.send') }}"
                                            class="underline hover:text-ink">Kirim ulang</a></p>
                                @endif
                            </div>
                            <div>
                                <label class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">ID
                                    Pengguna</label>
                                <input type="text" value="USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}"
                                    disabled
                                    class="w-full px-4 py-2.5 bg-ink/5 border border-ink text-sm font-serif text-muted cursor-not-allowed">
                            </div>
                            <div>
                                <label
                                    class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Role</label>
                                <input type="text" value="{{ ucfirst($user->role ?? 'anggota') }}" disabled
                                    class="w-full px-4 py-2.5 bg-ink/5 border border-ink text-sm font-serif text-muted cursor-not-allowed">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <h2
                        class="text-lg font-serif font-semibold text-ink border-b border-ink pb-2 flex items-center gap-2">
                        <x-lucide-shield-check class="w-4 h-4 text-coffee" /> II. Keamanan & Sandi
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="current_password"
                                class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Sandi Saat
                                Ini</label>
                            <input id="current_password" name="current_password" type="password"
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('current_password') border-red-700 @enderror">
                            @error('current_password')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div></div>
                        <div>
                            <label for="password"
                                class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Sandi
                                Baru</label>
                            <input id="password" name="password" type="password" autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('password') border-red-700 @enderror">
                            @error('password')
                                <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Konfirmasi
                                Sandi Baru</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                autocomplete="new-password" placeholder="Ulangi sandi baru"
                                class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all">
                        </div>
                    </div>
                </div>

            </div>

            <div
                class="bg-[#f4f1eb] border-t border-ink px-6 md:px-8 py-5 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <button type="reset"
                    class="w-full sm:w-auto px-6 py-2.5 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-all rounded-md text-center">
                    Reset
                </button>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-ink text-surface border border-ink text-sm font-serif hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2">
                    <x-lucide-check class="w-4 h-4" /> Simpan Perubahan
                </button>
            </div>
        </form>

        <div class="bg-surface border border-ink p-6">
            <h3 class="font-serif font-semibold text-ink mb-2 flex items-center gap-2">
                <x-lucide-trash-2 class="w-4 h-4 text-red-700" /> Hapus Akun
            </h3>
            <p class="font-serif text-sm text-muted mb-4">
                Setelah akun dihapus, semua data dan riwayat peminjaman akan dihapus permanen. Tindakan ini tidak dapat
                dibatalkan.
            </p>
            <button type="button" onclick="document.getElementById('delete-user-form').classList.remove('hidden')"
                class="px-4 py-2 border border-ink text-sm font-serif text-red-700 hover:bg-red-50 hover:border-red-800 transition-colors rounded">
                Hapus Akun Saya
            </button>

            <div id="delete-user-form" class="hidden mt-4 pt-4 border-t border-ink">
                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('DELETE')
                    <div>
                        <label for="delete-password"
                            class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">Konfirmasi Sandi
                            <span class="text-red-700">*</span></label>
                        <input id="delete-password" name="password" type="password" required
                            placeholder="Masukkan sandi untuk konfirmasi"
                            class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('userDeletion.password', 'userDeletion') border-red-700 @enderror">
                        @error('userDeletion.password', 'userDeletion')
                            <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="button"
                            onclick="document.getElementById('delete-user-form').classList.add('hidden')"
                            class="px-4 py-2 border border-ink bg-surface text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 transition-colors rounded">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-700 text-surface border border-red-700 text-sm font-serif hover:bg-red-800 transition-colors rounded">
                            Ya, Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="border border-ink bg-surface p-4 flex items-start gap-3">
            <x-lucide-info class="w-5 h-5 text-coffee flex-shrink-0 mt-0.5" />
            <div class="font-serif text-sm text-muted">
                <span class="text-ink font-semibold">Catatan Keamanan:</span> Perubahan sandi akan berlaku segera.
                Pastikan Anda mengingat sandi baru. Untuk keamanan tambahan, pertimbangkan mengaktifkan autentikasi dua
                faktor (fitur akan tersedia pada pembaruan berikutnya).
            </div>
        </div>
    </div>
    <script>
        document.getElementById('avatar-upload')?.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                const maxSize = 2 * 1024 * 1024; // 2MB

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
                reader.onload = function(e) {
                    const container = document.querySelector('#avatar-upload').closest('.flex-shrink-0');
                    const previewEl = container?.querySelector('img, svg');
                    if (previewEl) {
                        previewEl.outerHTML =
                            `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layouts.app>
