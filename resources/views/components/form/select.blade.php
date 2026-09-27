@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'help' => null,
])

@php
    $id = $id ?? $name;
    $invalid = $errors->has($name);
    $current = (string) old($name, $value);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-slate-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-600">*</span>
            @endif
        </label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @if ($required)
            required
        @endif
        {{ $attributes->class([
            'block w-full rounded-lg border px-3 py-2 text-sm text-slate-900 transition focus:outline-none focus:ring-2',
            'border-red-400 focus:border-red-500 focus:ring-red-500/20' => $invalid,
            'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' => ! $invalid,
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
        <p class="mt-1.5 text-xs text-slate-500">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
