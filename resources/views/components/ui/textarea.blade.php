@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'help' => null,
    'id' => null,
    'rows' => 3,
    'errorBag' => null,
])

@php
    $fieldId = $id ?: $name;
    $currentValue = old($name, $value);
    $errorMessage = $errorBag ? $errors->getBag($errorBag)->first($name) : $errors->first($name);
    $hasError = filled($errorMessage);
    $textareaClasses = 'w-full px-4 py-2.5 bg-background border text-sm font-serif text-ink placeholder:text-muted/50 focus:outline-none focus:ring-1 focus:ring-ink transition-all resize-y';
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

    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        {{ $attributes->class($textareaClasses . ' ' . ($hasError ? 'border-red-700' : 'border-ink')) }}
    >{{ $currentValue }}</textarea>

    @if ($hasError)
        <p class="mt-1 font-mono text-[10px] text-red-700">{{ $errorMessage }}</p>
    @elseif ($help)
        <p class="mt-1 font-mono text-[10px] text-muted">{{ $help }}</p>
    @endif
</div>
