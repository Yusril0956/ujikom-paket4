@props([
    'name',
    'label',
    'value' => 1,
    'checked' => false,
    'id' => null,
    'help' => null,
    'errorBag' => null,
])

@php
    $fieldId = $id ?: $name;
    $errorMessage = $errorBag ? $errors->getBag($errorBag)->first($name) : $errors->first($name);
    $hasError = filled($errorMessage);
@endphp

<div>
    <label for="{{ $fieldId }}" class="flex cursor-pointer items-center gap-2">
        <input
            id="{{ $fieldId }}"
            name="{{ $name }}"
            type="checkbox"
            value="{{ $value }}"
            @checked(old($name, $checked))
            {{ $attributes->class('h-4 w-4 rounded border-ink text-ink focus:ring-ink') }}
        />
        <span class="font-serif text-ink">{{ $label }}</span>
    </label>

    @if ($hasError)
        <p class="mt-1 font-mono text-[10px] text-red-700">{{ $errorMessage }}</p>
    @elseif ($help)
        <p class="mt-1 font-mono text-[10px] text-muted">{{ $help }}</p>
    @endif
</div>
