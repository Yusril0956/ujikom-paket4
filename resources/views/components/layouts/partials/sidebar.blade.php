<aside
    class="fixed inset-y-0 left-0 z-50 flex h-full w-72 max-w-[85vw] -translate-x-full flex-col border-r border-ink bg-surface transition-transform duration-200 lg:static lg:z-auto lg:w-64 lg:max-w-none lg:translate-x-0"
    data-sidebar-panel>
    <div class="border-b border-ink p-5 text-center sm:p-6">
        <div class="mb-4 flex items-center justify-between lg:hidden">
            <span class="font-mono text-[10px] uppercase tracking-[0.25em] text-muted">Navigasi</span>
            <x-ui.button type="button" variant="secondary" size="sm" class="h-9 w-9 p-0" data-sidebar-close
                aria-label="Tutup menu" icon="x" />
        </div>

        <a href="/" class="block mb-2">
            <x-app-logo :show-subtitle="false" />
        </a>

        @if (auth()->check())
            <div class="mt-4 flex justify-center">
                @if (auth()->user()->isAdmin())
                    <span
                        class="text-xs font-bold uppercase tracking-wider px-3 py-1 bg-red-500/10 text-red-700 rounded-full border border-red-200">
                        <x-lucide-shield-user class="w-3 h-3 inline mr-1" /> Administrator
                    </span>
                @elseif(auth()->user()->isPetugas())
                    <span
                        class="text-xs font-bold uppercase tracking-wider px-3 py-1 bg-amber-500/10 text-amber-700 rounded-full border border-amber-200">
                        <x-lucide-briefcase class="w-3 h-3 inline mr-1" /> Petugas
                    </span>
                @else
                    <span
                        class="text-xs font-bold uppercase tracking-wider px-3 py-1 bg-blue-500/10 text-blue-700 rounded-full border border-blue-200">
                        <x-lucide-user class="w-3 h-3 inline mr-1" /> Anggota
                    </span>
                @endif
            </div>
        @else
            {{-- Guest View --}}
            <div class="mt-4 flex justify-center">
                <span
                    class="text-xs font-bold uppercase tracking-wider px-3 py-1 bg-gray-500/10 text-gray-700 rounded-full border border-gray-200">
                    <x-lucide-globe class="w-3 h-3 inline mr-1" /> Guest
                </span>
            </div>
        @endif
    </div>

    <nav class="flex-1 overflow-y-auto py-4 sm:py-6">

        @if (auth()->check())
            @if (auth()->user()->isAnggota())
                <x-layouts.partials.sidebar-item icon="dashboard" label="Dashboard" :href="route('anggota.dashboard')"
                    :active="request()->routeIs('anggota.dashboard')" />
            @else
                <x-layouts.partials.sidebar-item icon="dashboard" label="Dashboard" :href="route('admin.dashboard')"
                    :active="request()->routeIs('admin.dashboard')" />
            @endif
            <div class="px-8 mt-8 mb-3 flex items-center gap-2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-coffee/50 font-serif">I. Katalog
                    Buku</span>
                <div class="h-px flex-1 bg-ink/5"></div>
            </div>

            <x-layouts.partials.sidebar-item icon="book" label="Daftar Buku" :href="route('books.index')" :active="request()->routeIs('books.*')" />

            @if (auth()->user()->isAdmin() || auth()->user()->isPetugas())
                <div class="px-8 mt-8 mb-3 flex items-center gap-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-red-600/50 font-serif">
                        <x-lucide-lock class="w-3.5 h-3.5 inline mr-0.5" /> II. Manajemen Sistem
                    </span>
                    <div class="h-px flex-1 bg-ink/5"></div>
                </div>

                <x-layouts.partials.sidebar-item icon="users" label="Data Pengguna" :href="route('admin.users.index')"
                    :active="request()->routeIs('admin.users.*')" />

                <x-layouts.partials.sidebar-item icon="receipt" label="Data Transaksi" :href="route('admin.transaksi.index')"
                    :active="request()->routeIs('admin.transaksi.*')" />
            @endif

            @if (auth()->user()->isAnggota())
                <div class="px-8 mt-8 mb-3 flex items-center gap-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-blue-600/50 font-serif">
                        <x-lucide-book-marked class="w-3.5 h-3.5 inline mr-0.5" /> II. Peminjaman Buku
                    </span>
                    <div class="h-px flex-1 bg-ink/5"></div>
                </div>

                <x-layouts.partials.sidebar-item icon="history" label="Riwayat Peminjaman" :href="route('anggota.transaksi')"
                    :active="request()->routeIs('anggota.transaksi')" />
            @endif
        @else
            <div class="px-8 mt-8 mb-3 flex items-center gap-2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-coffee/50 font-serif">Akses
                    Publik</span>
                <div class="h-px flex-1 bg-ink/5"></div>
            </div>

            <x-layouts.partials.sidebar-item icon="book" label="Katalog Buku" :href="route('books.index')"
                :active="request()->routeIs('books.*')" />

            <div class="px-8 mt-6 mb-3 flex items-center gap-2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-coffee/50 font-serif">Informasi</span>
                <div class="h-px flex-1 bg-ink/5"></div>
            </div>

            <x-layouts.partials.sidebar-item icon="info" label="Tentang Kami" :href="route('about')"
                :active="request()->routeIs('about')" />
            <x-layouts.partials.sidebar-item icon="file-text" label="Peraturan" :href="route('rules')" :active="request()->routeIs('rules')" />
        @endif
    </nav>

    <div class="flex-shrink-0 space-y-2 border-t border-ink bg-[#f9f7f1] p-4 sm:p-6">
        @if (auth()->check())
            <x-ui.button href="{{ route('profile.edit') }}" variant="ghost"
                class="w-full justify-start px-4 py-2.5 text-sm text-coffee" icon="circle-user-round">
                Profil Pengguna
            </x-ui.button>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <x-ui.button type="submit" variant="ghost"
                    class="mt-2 w-full justify-start border-t border-ink/5 pt-4 px-4 py-2.5 text-sm text-red-600/70 hover:bg-red-50 hover:text-red-700"
                    icon="log-out">
                    Tutup Sesi
                </x-ui.button>
            </form>
        @else
            <x-ui.button href="{{ route('login') }}" variant="ghost"
                class="w-full justify-start px-4 py-2.5 text-sm text-green-600 hover:bg-green-50 hover:text-green-700"
                icon="log-in">
                Masuk
            </x-ui.button>

            <x-ui.button href="{{ route('register') }}" variant="ghost"
                class="w-full justify-start px-4 py-2.5 text-sm text-blue-600 hover:bg-blue-50 hover:text-blue-700"
                icon="user-plus">
                Daftar
            </x-ui.button>
        @endif
    </div>
</aside>
