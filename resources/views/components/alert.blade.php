@props([
    'tone' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    /*
     * Page-level notice. Tone drives colour only; the ARIA role is derived so a
     * warning that appears after render is not swallowed by a polite region.
     */
    $palette = [
        'info' => ['wrap' => 'bg-accent-soft text-accent ring-accent/25', 'icon' => 'text-accent', 'path' => 'M12 16.5v-3m0-3h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'role' => 'status'],
        'success' => ['wrap' => 'bg-pop-lime/10 text-pop-lime ring-pop-lime/30', 'icon' => 'text-pop-lime', 'path' => 'm4.5 12.75 6 6 9-13.5', 'role' => 'status'],
        'warning' => ['wrap' => 'bg-pop-tangerine/10 text-pop-tangerine ring-pop-tangerine/30', 'icon' => 'text-pop-tangerine', 'path' => 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z', 'role' => 'status'],
        'danger' => ['wrap' => 'bg-red-50 text-red-700 ring-red-600/25', 'icon' => 'text-red-600', 'path' => 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z', 'role' => 'alert'],
    ];

    $colors = $palette[$tone] ?? $palette['info'];
@endphp

<div
    {{ $attributes->class(['flex items-start gap-3 rounded-xl px-4 py-3.5 text-sm ring-1 ring-inset', $colors['wrap']]) }}
    role="{{ $colors['role'] }}"
>
    <svg
        class="mt-px h-5 w-5 shrink-0 {{ $colors['icon'] }}"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        aria-hidden="true"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $colors['path'] }}" />
    </svg>

    <div class="min-w-0 flex-1">
        @if ($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif

        <div class="{{ $title ? 'mt-0.5 opacity-90' : '' }} leading-relaxed break-words">
            {{ $slot }}
        </div>
    </div>

    @if ($dismissible)
        <button
            type="button"
            data-alert-dismiss
            aria-label="Tutup pemberitahuan"
            class="-mr-1 shrink-0 rounded-lg p-1 transition-colors hover:bg-black/5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>