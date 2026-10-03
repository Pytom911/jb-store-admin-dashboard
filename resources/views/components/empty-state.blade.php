@props([
    'title',
    'description' => null,
    'actionLabel' => null,
    'actionUrl' => null,
    // The action is a WhatsApp hand-off on the storefront, so the caller opts
    // into green there. Left as accent, any page whose action is an in-app link
    // stays on the blue identity.
    'tone' => 'accent',
])

@php
    $actionTone = $tone === 'wa'
        ? 'bg-wa focus-visible:outline-wa'
        : 'bg-accent focus-visible:outline-accent';
@endphp

<div {{ $attributes->class('rounded-2xl border border-dashed border-rule bg-surface-2 py-16 text-center') }}>
    <span
        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-accent-soft text-accent"
        aria-hidden="true"
    >
        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
        </svg>
    </span>

    <p class="mt-5 font-display text-lg font-bold tracking-tight text-balance text-ink">{{ $title }}</p>

    @if ($description)
        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-pretty text-ink-soft">{{ $description }}</p>
    @endif

    @if ($actionUrl && $actionLabel)
        <a
            href="{{ $actionUrl }}"
            class="mt-7 inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold text-white transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 {{ $actionTone }}"
        >
            {{ $actionLabel }}
        </a>
    @endif
</div>