@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'autocomplete' => null,
    'autofocus' => false,
    'help' => null,
    'id' => null,
    'errorBag' => null,
    'step' => null,
    'min' => null,
    'max' => null,
    'accept' => null,
])

@php
    $fieldId = $id ?: $name;
    $currentValue = old($name, $value);
    $errorMessage = $errorBag ? $errors->getBag($errorBag)->first($name) : $errors->first($name);
    $hasError = filled($errorMessage);
    $inputClasses = 'w-full px-4 py-2.5 bg-background border text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all';
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

    <input
        id="{{ $fieldId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($type !== 'file') value="{{ $currentValue }}" @endif
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($step !== null) step="{{ $step }}" @endif
        @if ($min !== null) min="{{ $min }}" @endif
        @if ($max !== null) max="{{ $max }}" @endif
        @if ($accept) accept="{{ $accept }}" @endif
        @if ($autofocus) autofocus @endif
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        {{ $attributes->class($inputClasses . ' ' . ($hasError ? 'border-red-700' : 'border-ink')) }}
    />

    @if ($hasError)
        <p class="mt-1 font-mono text-[10px] text-red-700">{{ $errorMessage }}</p>
    @elseif ($help)
        <p class="mt-1 font-mono text-[10px] text-muted">{{ $help }}</p>
    @endif
</div>
