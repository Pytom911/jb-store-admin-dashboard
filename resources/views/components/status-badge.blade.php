@props(['status'])

@php
    $badgeClasses = match ($status->value) {
        'active', 'available' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'reserved' => 'bg-amber-50 text-amber-800 ring-amber-600/20',
        default => 'bg-slate-100 text-slate-600 ring-slate-500/20',
    };
@endphp

<span
    {{ $attributes->class('inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium whitespace-nowrap ring-1 ring-inset', $badgeClasses) }}
>
    {{ $status->label() }}
</span>
