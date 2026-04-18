<x-layouts.auth>
    <div class="p-6 md:p-8">
        <div class="mb-6 border-b border-ink pb-4 text-center">
            <h2 class="font-serif text-xl font-bold text-ink">Buat Akun Baru</h2>
            <p class="mt-1 font-serif text-sm text-muted">Daftar untuk mengakses arsip perpustakaan</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <x-ui.input name="name" label="Nama Lengkap" type="text" placeholder="Nama lengkap Anda" required
                autofocus :value="old('name')" />

            <x-ui.input name="email" label="Alamat Email" type="email" placeholder="nama@domain.com" required
                :value="old('email')" />

            <x-ui.input name="password" label="Sandi" type="password" placeholder="Minimal 8 karakter" required />

            <x-ui.input name="password_confirmation" label="Konfirmasi Sandi" type="password"
                placeholder="Ulangi sandi Anda" required />

            <x-ui.button type="submit" variant="primary" class="mt-2 w-full" icon="user-plus">
                Daftar Akun
            </x-ui.button>
        </form>

        <div class="my-6 flex items-center gap-3">
            <div class="h-px flex-1 bg-ink/20"></div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-muted">atau</span>
            <div class="h-px flex-1 bg-ink/20"></div>
        </div>

        <p class="text-center font-serif text-sm text-muted">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold text-coffee transition-colors hover:text-ink">
                Masuk sekarang
            </a>
        </p>
    </div>
</x-layouts.auth>
