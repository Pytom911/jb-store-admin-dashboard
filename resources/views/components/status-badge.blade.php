@props(['status'])

@php
    // Solid fills rather than alpha tints: a tinted chip loses its edge against
    // the lilac canvas. Each pair here clears WCAG AA for white text.
    $badgeClasses = match ($status->value) {
        'available' => 'bg-pop-lime text-white',
        'reserved' => 'bg-pop-tangerine text-white',
        default => 'bg-pop-slate text-white',
    };

    // A reserved account is the one state where the shopper may lose the sale,
    // so it gets a pulsing dot the other two states do not need.
    $showDot = $status->value === 'reserved';
@endphp

<span
    {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold whitespace-nowrap', $badgeClasses]) }}
>
    @if ($showDot)
        <span class="relative flex h-1.5 w-1.5" aria-hidden="true">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75 motion-reduce:hidden"></span>
            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-white"></span>
        </span>
    @endif

    {{ $status->label() }}
</span>