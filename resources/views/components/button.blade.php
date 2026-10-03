@props(['variant' => 'primary', 'href' => null, 'type' => 'submit'])

@php
    $variants = [
        'primary' => 'bg-brand text-white shadow-sm hover:brightness-110 focus-visible:ring-brand',
        'secondary' => 'bg-field text-fg ring-1 ring-inset ring-field-line hover:bg-brand-soft hover:text-brand focus-visible:ring-brand',
        'danger' => 'bg-danger text-white shadow-sm hover:brightness-110 focus-visible:ring-danger',
        'ghost' => 'text-fg-soft hover:bg-field hover:text-fg focus-visible:ring-brand',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-[color,background-color,filter] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$classes, $variants[$variant]]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$classes, $variants[$variant]]) }}>{{ $slot }}</button>
@endif