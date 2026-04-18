@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'help' => null,
    'id' => null,
    'errorBag' => null,
])

@php
    $fieldId = $id ?: $name;
    $currentValue = old($name, $value);
    $errorMessage = $errorBag ? $errors->getBag($errorBag)->first($name) : $errors->first($name);
    $hasError = filled($errorMessage);
    $selectClasses = 'w-full px-4 py-2.5 bg-background border text-sm font-serif text-ink focus:outline-none focus:ring-1 focus:ring-ink transition-all';
@endphp

<div>
    @if ($label)
        <label for="{{ $fieldId }}" class="mb-2 block font-mono text-xs uppercase tracking-wider text-coffee">
            {{ $label }}
            @if ($required)
                <span class="text-red-700">*</span>
            @endif
        </label>
    @endif

    <select
        id="{{ $fieldId }}"
        name="{{ $name }}"
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        {{ $attributes->class($selectClasses . ' ' . ($hasError ? 'border-red-700' : 'border-ink')) }}
    >
        {{ $slot }}
    </select>

    @if ($hasError)
        <p class="mt-1 font-mono text-[10px] text-red-700">{{ $errorMessage }}</p>
    @elseif ($help)
        <p class="mt-1 font-mono text-[10px] text-muted">{{ $help }}</p>
    @endif
</div>
