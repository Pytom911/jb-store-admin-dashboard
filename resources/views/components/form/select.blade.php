@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'help' => null,
    'autocomplete' => null,
])

@php
    $id = $id ?? $name;
    $invalid = $errors->has($name);
    $current = (string) old($name, $value);
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

    {{-- bg-field/color are also set globally in app.css; repeating them here keeps
         the control readable when Windows dark mode repaints the native widget. --}}
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @if ($required)
            required
        @endif
        @if ($autocomplete)
            autocomplete="{{ $autocomplete }}"
        @endif
        {{ $attributes->class([
            'block w-full rounded-xl border bg-field px-3 py-2.5 text-sm text-fg transition-colors',
            'focus-visible:outline-none focus-visible:ring-2',
            'border-danger focus-visible:border-danger focus-visible:ring-danger/25' => $invalid,
            'border-field-line focus-visible:border-brand focus-visible:ring-brand/30' => ! $invalid,
        ]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($current === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>

    @if ($help && ! $invalid)
        <p class="mt-1.5 text-xs text-fg-soft">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs font-medium text-danger">{{ $message }}</p>
    @enderror
</div>