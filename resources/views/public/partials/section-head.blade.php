@props([
    'title',
    'deck' => null,
    'linkLabel' => null,
    'linkHref' => null,
    'eyebrow' => null,
    'accent' => 'accent',
])

@php
    /*
     * Each section carries one accent so the long single-column page reads as a
     * sequence of distinct bands. The classes are spelled out in full because
     * Tailwind scans source text for literal class names and would never emit a
     * utility assembled from an interpolated string.
     */
    $accents = [
        'accent' => ['text' => 'text-accent', 'fill' => 'bg-accent'],
        'cyan' => ['text' => 'text-pop-cyan', 'fill' => 'bg-pop-cyan'],
        'lime' => ['text' => 'text-pop-lime', 'fill' => 'bg-pop-lime'],
        'magenta' => ['text' => 'text-pop-magenta', 'fill' => 'bg-pop-magenta'],
        'tangerine' => ['text' => 'text-pop-tangerine', 'fill' => 'bg-pop-tangerine'],
        'wa' => ['text' => 'text-wa', 'fill' => 'bg-wa'],
    ];

    $palette = $accents[$accent] ?? $accents['accent'];
@endphp

<div class="border-b border-ink/10 pb-5">
    @if ($eyebrow)
        <p class="font-display text-xs font-bold tracking-[0.14em] uppercase {{ $palette['text'] }}">{{ $eyebrow }}</p>
    @endif

    <div class="mt-1 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="max-w-2xl">
            <h2 class="font-display text-2xl font-bold tracking-tight text-balance text-ink sm:text-3xl">{{ $title }}</h2>

            @if ($deck)
                <p class="mt-2 text-sm leading-relaxed text-pretty text-ink-soft sm:text-base">{{ $deck }}</p>
            @endif
        </div>

        @if ($linkLabel && $linkHref)
            <a
                href="{{ $linkHref }}"
                class="inline-flex shrink-0 items-center gap-2 self-start rounded-full bg-surface px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-current transition-colors {{ $palette['text'] }} hover:bg-current hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent sm:self-auto"
            >
                {{ $linkLabel }}

                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        @endif
    </div>

    <div class="mt-5 h-1.5 w-20 rounded-full {{ $palette['fill'] }}" aria-hidden="true"></div>
</div>