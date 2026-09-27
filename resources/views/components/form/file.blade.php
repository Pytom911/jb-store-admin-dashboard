@props([
    'name',
    'id' => null,
    'label' => null,
    'accept' => 'image/*',
    'help' => null,
])

@php
    $id = $id ?? $name;
    $invalid = $errors->has($name);
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

    @error($name)
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
