@props([
'date' => now()->format('l, d F Y'),
'userName' => auth()->check() ? auth()->user()->name : 'Guest',
'userRole' => auth()->check() ? auth()->user()->role : 'guest',
'avatar' => 'https://i.pinimg.com/1200x/9d/a6/85/9da685aef502c3f249bd434a78bc8028.jpg',
])

<header class="h-auto min-h-[72px] bg-surface border-b border-ink px-6 py-3 flex items-center justify-between">

    <div class="flex items-start gap-4">
        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-background border border-ink rounded-md">
            <x-lucide-calendar class="w-3.5 h-3.5 text-coffee" />
            <time class="text-[10px] font-mono uppercase tracking-wider text-muted" datetime="{{ now()->format('Y-m-d') }}">
                {{ $date }}
            </time>
        </div>

        <div class="flex flex-col justify-center">
            <h2 class="font-serif text-lg font-bold text-ink leading-tight">
                @if(auth()->check())
                @if(auth()->user()->role === 'admin')
                <span class="text-coffee">Selamat Bertugas,</span> {{ $userName }}
                @elseif(auth()->user()->role === 'petugas')
                <span class="text-coffee">Selamat Bekerja,</span> {{ $userName }}
                @else
                <span class="text-coffee">Selamat Datang,</span> {{ $userName }}
                @endif
                @else
                Selamat Datang di Scriptoria
                @endif
            </h2>
        </div>
    </div>

    <div class="flex items-center gap-4">

        @if(auth()->check())
        @php
        $badgeConfig = match(auth()->user()->role) {
        'admin' => ['icon' => 'shield-check', 'label' => 'Administrator', 'text' => 'text-ink', 'bg' => 'bg-ink/5'],
        'petugas' => ['icon' => 'briefcase', 'label' => 'Petugas', 'text' => 'text-coffee', 'bg' => 'bg-surface'],
        default => ['icon' => 'user', 'label' => 'Anggota', 'text' => 'text-muted', 'bg' => 'bg-surface'],
        };
        @endphp
        <span class="hidden md:flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-mono uppercase tracking-wider border border-ink rounded {{ $badgeConfig['text'] }} {{ $badgeConfig['bg'] }}">
            <x-dynamic-component :component="'lucide-' . $badgeConfig['icon']" class="w-3 h-3" />
            {{ $badgeConfig['label'] }}
        </span>
        @else
        <span class="hidden md:flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-mono uppercase tracking-wider border border-ink rounded text-muted bg-surface">
            <x-lucide-globe class="w-3 h-3" />
            Guest
        </span>
        @endif

        @if(auth()->check())
        <div class="relative group">
            <button class="flex items-center gap-2 focus:outline-none" title="Menu Profil">
                <div class="w-10 h-10 rounded-full border border-ink overflow-hidden bg-background hover:border-coffee transition-colors">
                    @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                        alt="{{ $userName }}"
                        class="w-full h-full object-cover">
                    @else
                    <x-lucide-circle-user-round class="w-6 h-6 text-muted mx-auto mt-2" />
                    @endif
                </div>
                <x-lucide-chevron-down class="w-4 h-4 text-muted group-hover:text-ink transition-colors hidden sm:block" />
            </button>

            <div class="absolute right-0 mt-2 w-48 bg-surface border border-ink rounded-md shadow-[var(--elevation-2)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="p-2 border-b border-ink">
                    <p class="font-serif text-sm text-ink truncate">{{ $userName }}</p>
                    <p class="font-mono text-[10px] text-muted truncate">{{ auth()->user()->email ?? 'user@scriptoria.id' }}</p>
                </div>
                <nav class="p-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 rounded transition-colors">
                        <x-lucide-user class="w-4 h-4" /> Profil
                    </a>
                    <a href="{{ route('rules') }}" class="flex items-center gap-2 px-3 py-2 text-sm font-serif text-coffee hover:text-ink hover:bg-ink/5 rounded transition-colors">
                        <x-lucide-scale class="w-4 h-4" /> Tata Tertib
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-serif text-red-700 hover:bg-red-50 hover:text-red-800 rounded transition-colors text-left">
                            <x-lucide-log-out class="w-4 h-4" /> Keluar
                        </button>
                    </form>
                </nav>
            </div>
        </div>
        @else
        <div class="w-10 h-10 rounded-full border border-ink bg-background flex items-center justify-center">
            <x-lucide-user-check class="w-5 h-5 text-muted" />
        </div>
        @endif
    </div>
</header>