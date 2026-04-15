<x-layouts.auth>
    <div class="p-6 md:p-8">
        {{-- Header Form --}}
        <div class="text-center mb-6 border-b border-ink pb-4">
            <h2 class="font-serif text-xl font-bold text-ink">Buat Akun Baru</h2>
            <p class="font-serif text-sm text-muted mt-1">Daftar untuk mengakses arsip perpustakaan</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                    Nama Lengkap <span class="text-red-700">*</span>
                </label>
                <input id="name" name="name" type="text" required autofocus value="{{ old('name') }}"
                    placeholder="Nama lengkap Anda"
                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('name') border-red-700 @enderror">
                @error('name')
                    <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                    Alamat Email <span class="text-red-700">*</span>
                </label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}"
                    placeholder="nama@domain.com"
                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('email') border-red-700 @enderror">
                @error('email')
                    <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                    Sandi <span class="text-red-700">*</span>
                </label>
                <input id="password" name="password" type="password" required placeholder="Minimal 8 karakter"
                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('password') border-red-700 @enderror">
                @error('password')
                    <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation"
                    class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                    Konfirmasi Sandi <span class="text-red-700">*</span>
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    placeholder="Ulangi sandi Anda"
                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all">
            </div>

            <div>
                <label for="role" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                    Tipe Akun
                </label>
                <select id="role" name="role"
                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all">
                    <option value="anggota">Anggota Perpustakaan</option>
                    <option value="petugas">Petugas / Staff</option>
                    {{-- Admin hanya bisa dibuat via seed/admin panel --}}
                </select>
                <p class="font-mono text-[10px] text-muted mt-1">* Akun Admin hanya dapat dibuat oleh kurator utama</p>
            </div>

            <button type="submit"
                class="w-full px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif font-semibold hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2 mt-2">
                <x-lucide-user-plus class="w-4 h-4" /> Daftar Akun
            </button>
        </form>

        <div class="my-6 flex items-center gap-3">
            <div class="flex-1 h-px bg-ink/20"></div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-muted">atau</span>
            <div class="flex-1 h-px bg-ink/20"></div>
        </div>

        <p class="text-center font-serif text-sm text-muted">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="text-coffee hover:text-ink font-semibold transition-colors">
                Masuk sekarang
            </a>
        </p>
    </div>
</x-layouts.auth>
