@php
    $accountGame = $account->game;
@endphp

<article
    @class([
        'relative flex gap-3 border-b border-rule py-4 transition-colors sm:gap-5',
        'opacity-80' => ! $account->isAvailable(),
    ])
>
    @if ($account->coverImage)
        <img
            src="{{ $account->coverImage->url }}"
            alt=""
            width="256"
            height="160"
            class="h-14 w-16 shrink-0 rounded-lg object-cover sm:h-20 sm:w-32"
            loading="lazy"
            decoding="async"
        />
    @else
        <span
            class="flex h-14 w-16 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-accent-soft to-surface font-display text-xl font-bold text-accent/45 sm:h-20 sm:w-32 sm:text-2xl"
            aria-hidden="true"
        >
            {{ strtoupper(mb_substr($accountGame->name, 0, 1)) }}
        </span>
    @endif

    <div class="min-w-0 flex-1">
        <h3 class="truncate font-semibold text-ink">
            <a href="{{ route('accounts.show', $account->account_code) }}" class="transition-colors hover:text-accent">
                <span class="absolute inset-0" aria-hidden="true"></span>
                {{ $account->title }}
            </a>
        </h3>

        <p class="mt-1 flex flex-wrap items-baseline gap-x-2 text-xs text-ink-soft">
            <span class="truncate">{{ $accountGame->name }}</span>
            <span class="font-mono text-ink-soft">{{ $account->account_code }}</span>
        </p>

        @unless ($account->isAvailable())
            <p class="mt-1.5">
                <x-status-badge :status="$account->status" />
            </p>
        @endunless
    </div>

    <div class="flex shrink-0 flex-col items-end justify-between gap-3 sm:gap-2">
        <p class="text-sm font-bold tracking-tight tabular-nums text-ink sm:text-xl">
            {{ $account->formattedPrice() }}
        </p>

        @if ($account->isAvailable())
            <a
                href="{{ $account->whatsappUrl() }}"
                target="_blank"
                rel="noopener noreferrer"
                class="relative inline-flex items-center gap-1.5 rounded-full bg-wa px-4 py-2 text-xs font-semibold text-white transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent sm:px-5 sm:py-2.5 sm:text-sm"
            >
                Pesan
            </a>
        @endif
    </div>
</article>