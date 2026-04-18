<x-layouts.auth>
    <div class="p-6 md:p-8">
        <div class="mb-6 border-b border-ink pb-4 text-center">
            <h2 class="font-serif text-xl font-bold text-ink">Selamat Datang Kembali</h2>
            <p class="mt-1 font-serif text-sm text-muted">Masuk untuk mengakses arsip perpustakaan</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <x-ui.input name="email" label="Alamat Email" type="email" placeholder="nama@domain.com" required
                autofocus :value="old('email')" />

            <x-ui.input name="password" label="Sandi" type="password" placeholder="••••••••" required />

            <div class="flex items-center justify-between text-sm">
                <x-ui.checkbox name="remember" label="Ingat saya" />

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="font-serif text-coffee transition-colors hover:text-ink">
                        Lupa sandi?
                    </a>
                @endif
            </div>

            <x-ui.button type="submit" variant="primary" class="w-full" icon="log-in">
                Masuk
            </x-ui.button>
        </form>

        <div class="my-6 flex items-center gap-3">
            <div class="h-px flex-1 bg-ink/20"></div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-muted">atau</span>
            <div class="h-px flex-1 bg-ink/20"></div>
        </div>

        <p class="text-center font-serif text-sm text-muted">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-semibold text-coffee transition-colors hover:text-ink">
                Daftar sekarang
            </a>
        </p>
    </div>
</x-layouts.auth>
