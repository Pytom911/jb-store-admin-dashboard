@props([
    'name',
    'id' => null,
    'label' => null,
    'accept' => 'image/*',
    'help' => null,
    'multiple' => false,
])

@php
    // Array inputs are posted as "images[]", which is neither a usable DOM id nor
    // the key Laravel puts in the error bag, so both are derived from the bare name.
    $key = str_ends_with($name, '[]') ? substr($name, 0, -2) : $name;
    $id = $id ?? $key;
    $invalid = $errors->has($key);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-slate-700">{{ $label }}</label>
    @endif

    <input
        id="{{ $id }}"
        type="file"
        name="{{ $name }}"
        accept="{{ $accept }}"
        @if ($multiple) multiple @endif
        {{ $attributes->class([
            'block w-full rounded-lg border text-sm text-slate-500 transition focus:outline-none focus:ring-2',
            'file:mr-3 file:rounded-r-lg file:border-0 file:bg-slate-50 file:px-3.5 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-100',
            'border-red-400 focus:border-red-500 focus:ring-red-500/20' => $invalid,
            'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' => ! $invalid,
        ]) }}
    />

    @if ($help && ! $invalid)
        <p class="mt-1.5 text-xs text-slate-500">{{ $help }}</p>
    @endif

    @error($key)
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
