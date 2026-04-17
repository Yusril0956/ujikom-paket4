@props([
'date' => now()->format('l, d F Y'),
'userName' => auth()->check() ? auth()->user()->name : 'Guest',
'userRole' => auth()->check() ? auth()->user()->role : 'guest',
'avatar' => 'https://i.pinimg.com/1200x/9d/a6/85/9da685aef502c3f249bd434a78bc8028.jpg',
])

<header class="min-h-[72px] border-b border-ink bg-surface px-4 py-3 sm:px-6">
    <div class="flex items-center justify-between gap-3 sm:gap-4">
        <div class="flex min-w-0 items-start gap-3 sm:gap-4">
            <button type="button"
                class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-md border border-ink bg-background text-coffee lg:hidden"
                data-sidebar-toggle aria-label="Buka menu">
                <x-lucide-menu class="h-4 w-4" />
            </button>

            <div class="hidden sm:flex items-center gap-2 rounded-md border border-ink bg-background px-3 py-1.5">
                <x-lucide-calendar class="w-3.5 h-3.5 text-coffee" />
                <time class="text-[10px] font-mono uppercase tracking-wider text-muted" datetime="{{ now()->format('Y-m-d') }}">
                    {{ $date }}
                </time>
            </div>

            <div class="hidden min-w-0 flex-1 flex-col justify-center sm:flex">
                <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-muted">
                    {{ now()->format('d M Y') }}
                </p>
            </div>
        </div>

        <div class="flex shrink-0 items-center justify-end gap-3 sm:gap-4">

            @if(auth()->check())
            @php
            $badgeConfig = match(auth()->user()->role) {
            'admin' => ['icon' => 'shield-check', 'label' => 'Administrator', 'text' => 'text-ink', 'bg' => 'bg-ink/5'],
            'petugas' => ['icon' => 'briefcase', 'label' => 'Petugas', 'text' => 'text-coffee', 'bg' => 'bg-surface'],
            default => ['icon' => 'user', 'label' => 'Anggota', 'text' => 'text-muted', 'bg' => 'bg-surface'],
            };
            @endphp
            <span class="hidden md:flex items-center gap-1.5 rounded border border-ink px-3 py-1.5 text-[10px] font-mono uppercase tracking-wider {{ $badgeConfig['text'] }} {{ $badgeConfig['bg'] }}">
                <x-dynamic-component :component="'lucide-' . $badgeConfig['icon']" class="w-3 h-3" />
                {{ $badgeConfig['label'] }}
            </span>
            @else
            <span class="hidden md:flex items-center gap-1.5 rounded border border-ink bg-surface px-3 py-1.5 text-[10px] font-mono uppercase tracking-wider text-muted">
                <x-lucide-globe class="w-3 h-3" />
                Guest
            </span>
            @endif

            @if(auth()->check())
            <details class="relative">
                <summary class="flex cursor-pointer list-none items-center gap-2 rounded-md focus:outline-none focus-visible:ring-1 focus-visible:ring-ink" title="Menu Profil">
                    <div class="h-10 w-10 overflow-hidden rounded-full border border-ink bg-background transition-colors hover:border-coffee">
                        @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                            alt="{{ $userName }}"
                            class="h-full w-full object-cover">
                        @else
                        <x-lucide-circle-user-round class="mx-auto mt-2 h-6 w-6 text-muted" />
                        @endif
                    </div>
                    <div class="hidden min-w-0 text-left sm:block">
                        <p class="truncate font-serif text-sm text-ink">{{ $userName }}</p>
                        <p class="truncate font-mono text-[10px] uppercase tracking-[0.18em] text-muted">{{ ucfirst($userRole) }}</p>
                    </div>
                    <x-lucide-chevron-down class="hidden h-4 w-4 text-muted sm:block" />
                </summary>

                <div class="absolute right-0 z-50 mt-2 w-[min(14rem,calc(100vw-2rem))] rounded-md border border-ink bg-surface shadow-[var(--elevation-2)]">
                    <div class="border-b border-ink p-3">
                        <p class="truncate font-serif text-sm text-ink">{{ $userName }}</p>
                        <p class="truncate font-mono text-[10px] text-muted">{{ auth()->user()->email ?? 'user@scriptoria.id' }}</p>
                    </div>
                    <nav class="p-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded px-3 py-2 text-sm font-serif text-coffee transition-colors hover:bg-ink/5 hover:text-ink">
                            <x-lucide-user class="w-4 h-4" /> Profil
                        </a>
                        <a href="{{ route('rules') }}" class="flex items-center gap-2 rounded px-3 py-2 text-sm font-serif text-coffee transition-colors hover:bg-ink/5 hover:text-ink">
                            <x-lucide-scale class="w-4 h-4" /> Tata Tertib
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 rounded px-3 py-2 text-sm font-serif text-red-700 transition-colors hover:bg-red-50 hover:text-red-800">
                                <x-lucide-log-out class="w-4 h-4" /> Keluar
                            </button>
                        </form>
                    </nav>
                </div>
            </details>
            @else
            <div class="flex h-10 w-10 items-center justify-center rounded-full border border-ink bg-background">
                <x-lucide-user-check class="w-5 h-5 text-muted" />
            </div>
            @endif
        </div>
    </div>
</header>
