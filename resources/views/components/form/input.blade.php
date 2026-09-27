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
])

@php
    $id = $id ?? $name;
    $invalid = $errors->has($name);
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

    <div @class(['relative' => $prefix])>
        @if ($prefix)
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">
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
            @if ($placeholder)
                placeholder="{{ $placeholder }}"
            @endif
            {{ $attributes->class([
                'block w-full rounded-lg border px-3 py-2 text-sm text-slate-900 transition placeholder:text-slate-400 focus:outline-none focus:ring-2',
                'pl-9' => (bool) $prefix,
                'border-red-400 focus:border-red-500 focus:ring-red-500/20' => $invalid,
                'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' => ! $invalid,
            ]) }}
        />
    </div>

    @if ($help && ! $invalid)
        <p class="mt-1.5 text-xs text-slate-500">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
