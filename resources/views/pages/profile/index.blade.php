<x-layouts.app>
    <div class="max-w-5xl mx-auto space-y-6 px-2">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ink pb-5">
            <div>
                <h1 class="text-2xl font-serif font-bold text-ink tracking-tight">Profil Pengguna</h1>
                <p class="text-muted mt-1 font-serif text-sm">Kelola informasi akun, preferensi, dan keamanan.</p>
            </div>
            <x-ui.button href="{{ auth()->user()->isAnggota() ? route('anggota.dashboard') : route('admin.dashboard') }}"
                variant="secondary" class="w-full md:w-auto px-4" icon="arrow-left">
                Kembali
            </x-ui.button>
        </div>

        @if (session('status'))
            <x-ui.alert type="success" icon="check-circle">
                {{ session('status') }}
            </x-ui.alert>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
            class="bg-surface border border-ink">
            @csrf
            @method('PATCH')
            <div class="p-6 md:p-8 space-y-8">
                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-user class="w-4 h-4 text-coffee" /> I. Identitas & Foto
                    </h2>
                    <div class="flex flex-col items-start gap-6 md:flex-row">
                        <div class="flex-shrink-0">
                            <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border border-ink bg-background">
                                @if ($user->avatar && Storage::disk('public')->exists($user->avatar))
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                        class="h-full w-full object-cover"
                                        onerror="this.parentElement.innerHTML='<x-lucide-circle-user-round class=\'w-10 h-10 text-muted\' />'">
                                @else
                                    <x-lucide-circle-user-round class="w-10 h-10 text-muted" />
                                @endif
                            </div>

                            <label for="avatar-upload"
                                class="mt-3 block w-full cursor-pointer rounded border border-ink bg-surface px-3 py-1.5 text-center font-serif text-xs text-coffee transition-colors hover:bg-ink/5 hover:text-ink">
                                Ganti Foto
                            </label>

                            <input type="file" name="avatar" id="avatar-upload" class="hidden"
                                accept="image/png,image/jpeg,image/jpg,image/webp">

                            @error('avatar')
                                <p class="mt-1 font-mono text-[10px] text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid flex-1 grid-cols-1 gap-6 md:grid-cols-2">
                            <x-ui.input name="name" label="Nama Lengkap" :value="$user->name" required />
                            <div>
                                <x-ui.input name="email" label="Email" type="email" :value="$user->email" required />
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <p class="mt-1 font-mono text-[10px] text-coffee">
                                        Email belum terverifikasi.
                                        <a href="{{ route('verification.send') }}" class="underline hover:text-ink">Kirim ulang</a>
                                    </p>
                                @endif
                            </div>
                            <div>
                                <label class="mb-2 block font-mono text-xs uppercase tracking-wider text-coffee">ID
                                    Pengguna</label>
                                <input type="text" value="USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}" disabled
                                    class="w-full cursor-not-allowed border border-ink bg-ink/5 px-4 py-2.5 font-serif text-sm text-muted">
                            </div>
                            <div>
                                <label class="mb-2 block font-mono text-xs uppercase tracking-wider text-coffee">Role</label>
                                <input type="text" value="{{ ucfirst($user->role ?? 'anggota') }}" disabled
                                    class="w-full cursor-not-allowed border border-ink bg-ink/5 px-4 py-2.5 font-serif text-sm text-muted">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <h2 class="flex items-center gap-2 border-b border-ink pb-2 text-lg font-serif font-semibold text-ink">
                        <x-lucide-shield-check class="w-4 h-4 text-coffee" /> II. Keamanan & Sandi
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <x-ui.input name="current_password" label="Sandi Saat Ini" type="password"
                            autocomplete="current-password" placeholder="••••••••" />
                        <div></div>
                        <x-ui.input name="password" label="Sandi Baru" type="password" autocomplete="new-password"
                            placeholder="Minimal 8 karakter" />
                        <x-ui.input name="password_confirmation" label="Konfirmasi Sandi Baru" type="password"
                            autocomplete="new-password" placeholder="Ulangi sandi baru" />
                    </div>
                </div>
            </div>

            <div class="bg-[#f4f1eb] border-t border-ink px-6 py-5 md:px-8 flex flex-col-reverse items-center justify-end gap-3 sm:flex-row">
                <x-ui.button type="reset" variant="secondary" class="w-full sm:w-auto px-6">
                    Reset
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto px-6" icon="check">
                    Simpan Perubahan
                </x-ui.button>
            </div>
        </form>

        <div class="bg-surface border border-ink p-6">
            <h3 class="mb-2 flex items-center gap-2 font-serif font-semibold text-ink">
                <x-lucide-trash-2 class="w-4 h-4 text-red-700" /> Hapus Akun
            </h3>
            <p class="mb-4 font-serif text-sm text-muted">
                Setelah akun dihapus, semua data dan riwayat peminjaman akan dihapus permanen. Tindakan ini tidak dapat
                dibatalkan.
            </p>
            <button type="button" onclick="document.getElementById('delete-user-form').classList.remove('hidden')"
                class="rounded border border-ink px-4 py-2 font-serif text-sm text-red-700 transition-colors hover:border-red-800 hover:bg-red-50">
                Hapus Akun Saya
            </button>

            <div id="delete-user-form" class="hidden mt-4 border-t border-ink pt-4">
                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('DELETE')
                    <x-ui.input name="password" label="Konfirmasi Sandi" type="password"
                        placeholder="Masukkan sandi untuk konfirmasi" error-bag="userDeletion" />
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="button" onclick="document.getElementById('delete-user-form').classList.add('hidden')"
                            class="rounded border border-ink bg-surface px-4 py-2 font-serif text-sm text-coffee transition-colors hover:bg-ink/5 hover:text-ink">
                            Batal
                        </button>
                        <x-ui.button type="submit" variant="danger" class="px-4">
                            Ya, Hapus Akun
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>

        <x-ui.alert type="info" title="Catatan Keamanan" icon="info">
            Perubahan sandi akan berlaku segera. Pastikan Anda mengingat sandi baru. Untuk keamanan tambahan,
            pertimbangkan mengaktifkan autentikasi dua faktor (fitur akan tersedia pada pembaruan berikutnya).
        </x-ui.alert>
    </div>

    <script>
        document.getElementById('avatar-upload')?.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];

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
                reader.onload = function(e) {
                    const container = document.querySelector('#avatar-upload').closest('.flex-shrink-0');
                    const previewEl = container?.querySelector('img, svg');
                    if (previewEl) {
                        previewEl.outerHTML = `<img src="${e.target.result}" alt="Preview" class="h-full w-full object-cover">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layouts.app>
