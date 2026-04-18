@props([
    'subtitle' => 'Digital Archive System',
    'showSubtitle' => true,
    'title' => 'Scriptoria',
])

<div {{ $attributes->class('text-center') }}>
    <x-lucide-book-open class="mx-auto mb-3 h-10 w-10 stroke-[1.5] text-ink" />

    <h1 class="font-serif text-2xl font-black uppercase tracking-[0.2em] text-ink">
        {{ $title }}
    </h1>

    @if ($showSubtitle && filled($subtitle))
        <p class="mt-2 font-mono text-[10px] uppercase tracking-[0.3em] text-coffee">
            {{ $subtitle }}
        </p>
    @endif
</div>
