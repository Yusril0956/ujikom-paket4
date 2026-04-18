@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'left',
    'iconClass' => 'h-4 w-4',
    'disabled' => false,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-md border text-sm font-serif font-semibold transition-all focus:outline-none focus-visible:ring-1 focus-visible:ring-ink';

    $variants = [
        'primary' => 'border-ink bg-ink text-surface hover:bg-ink/90',
        'secondary' => 'border-ink bg-surface text-coffee hover:bg-ink/5 hover:text-ink',
        'danger' => 'border-red-700 bg-red-700 text-surface hover:bg-red-800 hover:border-red-800',
        'danger-outline' => 'border-red-700 bg-red-50 text-red-700 hover:bg-red-100',
        'ghost' => 'border-transparent bg-transparent text-coffee hover:bg-ink/5 hover:text-ink',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes = trim($base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($disabled ? 'cursor-not-allowed opacity-60' : ''));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon && $iconPosition === 'left')
            <x-dynamic-component :component="'lucide-' . $icon" class="{{ $iconClass }}" />
        @endif
        <span>{{ $slot }}</span>
        @if ($icon && $iconPosition === 'right')
            <x-dynamic-component :component="'lucide-' . $icon" class="{{ $iconClass }}" />
        @endif
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->class($classes) }}>
        @if ($icon && $iconPosition === 'left')
            <x-dynamic-component :component="'lucide-' . $icon" class="{{ $iconClass }}" />
        @endif
        <span>{{ $slot }}</span>
        @if ($icon && $iconPosition === 'right')
            <x-dynamic-component :component="'lucide-' . $icon" class="{{ $iconClass }}" />
        @endif
    </button>
@endif
