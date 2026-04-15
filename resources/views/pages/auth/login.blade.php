<x-layouts.auth>
    <div class="p-6 md:p-8">
        {{-- Header Form --}}
        <div class="text-center mb-6 border-b border-ink pb-4">
            <h2 class="font-serif text-xl font-bold text-ink">Selamat Datang Kembali</h2>
            <p class="font-serif text-sm text-muted mt-1">Masuk untuk mengakses arsip perpustakaan</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block font-mono text-xs uppercase tracking-wider text-coffee mb-2">
                    Alamat Email <span class="text-red-700">*</span>
                </label>
                <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}"
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
                <input id="password" name="password" type="password" required placeholder="••••••••"
                    class="w-full px-4 py-2.5 bg-background border border-ink text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all @error('password') border-red-700 @enderror">
                @error('password')
                    <p class="font-mono text-[10px] text-red-700 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember & Forgot --}}
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember"
                        class="w-4 h-4 border border-ink text-ink focus:ring-ink rounded bg-background">
                    <span class="font-serif text-ink">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="font-serif text-coffee hover:text-ink transition-colors">
                        Lupa sandi?
                    </a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full px-4 py-2.5 bg-ink text-surface border border-ink text-sm font-serif font-semibold hover:bg-ink/90 transition-all rounded-md flex items-center justify-center gap-2">
                <x-lucide-log-in class="w-4 h-4" /> Masuk
            </button>
        </form>

        {{-- Divider --}}
        <div class="my-6 flex items-center gap-3">
            <div class="flex-1 h-px bg-ink/20"></div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-muted">atau</span>
            <div class="flex-1 h-px bg-ink/20"></div>
        </div>

        {{-- Register Link --}}
        <p class="text-center font-serif text-sm text-muted">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="text-coffee hover:text-ink font-semibold transition-colors">
                Daftar sekarang
            </a>
        </p>
    </div>
</x-layouts.auth>
