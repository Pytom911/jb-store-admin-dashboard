@props([
    'label',
    'value',
    'href',
    'tone' => 'slate',
    'icon' => 'stack',
])

@php
    $toneClasses = match ($tone) {
        'indigo' => 'bg-indigo-500/10 text-indigo-600',
        'emerald' => 'bg-emerald-500/10 text-emerald-600',
        'amber' => 'bg-amber-500/10 text-amber-700',
        default => 'bg-slate-500/10 text-slate-600',
    };

    $icons = [
        'gamepad' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.25 18h-.008v-.008h.008v.008ZM9.75 18h-.008v-.008h.008v.008ZM7.5 15h.008v.008H7.5V15Zm0-3h.008v.008H7.5V12Zm3 0h.008v.008h-.008V12Zm0-3h.008v.008h-.008V9Zm3 0h.008v.008h-.008V9Zm3 3h.008v.008h-.008V12Zm0 3h.008v.008h-.008V15Zm1.125-6.75H4.875a1.125 1.125 0 0 0-1.125 1.125v7.5c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125v-4.875c0-.621-.504-1.125-1.125-1.125H18.75M16.5 6.75h.008v.008h-.008V6.75Zm0 0H7.5c0-2.208 1.792-4 4-4h1a4 4 0 0 1 4 4v6a1.125 1.125 0 0 1-1.125 1.125H6.75A1.125 1.125 0 0 1 5.625 12.75v-1.5c0-1.036.84-1.875 1.875-1.875H7.5m8.25 3 1.5-1.5m0 0-1.5-1.5m1.5 1.5-1.5 1.5" />',
        'stack' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 0 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />',
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
        'cart' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12A1.125 1.125 0 0 1 19.75 22H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />',
    ];
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->class('group block rounded-xl bg-white p-5 ring-1 ring-slate-200 transition hover:ring-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500') }}
>
    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>

    <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $value }}</p>

    <span class="mt-4 inline-flex h-9 w-9 items-center justify-center rounded-lg {{ $toneClasses }}">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            {!! $icons[$icon] ?? $icons['stack'] !!}
        </svg>
    </span>
</a>
