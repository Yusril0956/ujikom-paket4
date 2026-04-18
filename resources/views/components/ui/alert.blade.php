@props([
    'type' => 'info',
    'title' => null,
    'icon' => null,
])

@php
    $config = [
        'success' => [
            'wrap' => 'bg-green-50 border-green-300 text-green-800',
            'icon' => 'check-circle',
            'iconClass' => 'text-green-600',
            'titleClass' => 'text-green-900',
        ],
        'danger' => [
            'wrap' => 'bg-red-50 border-red-300 text-red-800',
            'icon' => 'x-circle',
            'iconClass' => 'text-red-600',
            'titleClass' => 'text-red-900',
        ],
        'warning' => [
            'wrap' => 'bg-amber-50 border-amber-300 text-amber-900',
            'icon' => 'triangle-alert',
            'iconClass' => 'text-amber-600',
            'titleClass' => 'text-amber-900',
        ],
        'info' => [
            'wrap' => 'bg-surface border-ink text-muted',
            'icon' => 'info',
            'iconClass' => 'text-coffee',
            'titleClass' => 'text-ink',
        ],
    ];

    $theme = $config[$type] ?? $config['info'];
    $iconName = $icon ?? $theme['icon'];
@endphp

@php
    $classes = 'border p-4 ' . $theme['wrap'];
@endphp

<div {{ $attributes->class($classes) }}>
    <div class="flex items-start gap-3">
        @if ($iconName)
            <x-dynamic-component :component="'lucide-' . $iconName"
                class="mt-0.5 h-5 w-5 flex-shrink-0 {{ $theme['iconClass'] }}" />
        @endif

        <div class="flex-1 font-serif text-sm">
            @if ($title)
                <p class="mb-2 font-semibold {{ $theme['titleClass'] }}">
                    {{ $title }}
                </p>
            @endif
            {{ $slot }}
        </div>
    </div>
</div>
