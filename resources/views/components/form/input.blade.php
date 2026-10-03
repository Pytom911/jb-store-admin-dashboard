@props([
    'name',
    'id' => null,
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'help' => null,
    'prefix' => null,
    'autocomplete' => null,
    'spellcheck' => null,
])

@php
    $id = $id ?? $name;
    $invalid = $errors->has($name);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-fg">
            {{ $label }}
            @if ($required)
                <span class="text-danger" aria-hidden="true">*</span>
                <span class="sr-only">(wajib diisi)</span>
            @endif
        </label>
    @endif

    <div @class(['relative' => $prefix])>
        @if ($prefix)
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-fg-soft">
                {{ $prefix }}
            </span>
        @endif

        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            @if ($required)
                required
            @endif
            @if ($autocomplete)
                autocomplete="{{ $autocomplete }}"
            @endif
            @if ($spellcheck !== null)
                spellcheck="{{ $spellcheck ? 'true' : 'false' }}"
            @endif
            @if ($placeholder)
                placeholder="{{ $placeholder }}"
            @endif
            {{ $attributes->class([
                'block w-full rounded-xl border bg-field px-3 py-2.5 text-sm text-fg transition-colors placeholder:text-field-placeholder',
                'focus-visible:outline-none focus-visible:ring-2',
                'pl-9' => (bool) $prefix,
                'border-danger focus-visible:border-danger focus-visible:ring-danger/25' => $invalid,
                'border-field-line focus-visible:border-brand focus-visible:ring-brand/30' => ! $invalid,
            ]) }}
        />
    </div>

    @if ($help && ! $invalid)
        <p class="mt-1.5 text-xs text-fg-soft">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs font-medium text-danger">{{ $message }}</p>
    @enderror
</div>