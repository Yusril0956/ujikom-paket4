@props(['icon', 'label', 'href', 'active' => false])

<a href="{{ $href }}" @class([
    'relative flex items-center gap-4 border-l-4 px-8 py-2.5 text-sm font-serif transition-colors duration-200 group',
    'bg-ink/5 text-ink font-bold border-ink' => $active,
    'border-transparent text-coffee/80 hover:bg-ink/5 hover:text-ink' => !$active,
])>
    <span @class([
        'transition-colors duration-200',
        'text-ink' => $active,
        'text-coffee/40 group-hover:text-ink/60' => !$active,
    ])>
        @if ($icon === 'dashboard')
            <x-lucide-layout-dashboard class="w-5 h-5 stroke-[1.5]" />
        @elseif($icon === 'book' || $icon === 'book-open')
            <x-lucide-book-open class="w-5 h-5 stroke-[1.5]" />
        @elseif($icon === 'users')
            <x-lucide-users-round class="w-5 h-5 stroke-[1.5]" />
        @elseif($icon === 'receipt')
            <x-lucide-receipt-text class="w-5 h-5 stroke-[1.5]" />
        @elseif($icon === 'transaction')
            <x-lucide-repeat class="w-5 h-5 stroke-[1.5]" />
        @elseif($icon === 'history')
            <x-lucide-history class="w-5 h-5 stroke-[1.5]" />
        @elseif($icon === 'book-marked')
            <x-lucide-bookmark class="w-5 h-5 stroke-[1.5]" />
        @else
            <x-lucide-circle-slash class="w-5 h-5 stroke-[1.5]" />
        @endif
    </span>

    <span class="flex-1 tracking-wide">{{ $label }}</span>

    @if ($active)
        <span class="relative -top-[1px] font-serif text-lg leading-none text-ink opacity-50">›</span>
    @endif
</a>
