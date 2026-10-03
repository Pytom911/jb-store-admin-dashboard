@props([
    'account',
    'game' => null,
])

@php
    // Games loaded through GameController@show already know their game, which keeps
    // this card from lazy-loading the relation once per account on that page. The
    // prop is declared rather than read from the ambient scope, so a page-level
    // $game left behind by another loop can never be mistaken for the account's.
    $accountGame = $game ?? $account->game;
    $detailUrl = route('accounts.show', $account->account_code);
@endphp

<article
    @class([
        'relative flex h-full flex-col overflow-hidden rounded-2xl bg-surface ring-1 ring-inset transition-shadow',
        'ring-rule hover:shadow-lg hover:shadow-accent/10' => $account->isAvailable(),
        'ring-rule opacity-80' => ! $account->isAvailable(),
    ])
>
    @if ($account->coverImage)
        <img
            src="{{ $account->coverImage->url }}"
            alt="{{ $account->title }}"
            width="640"
            height="360"
            class="aspect-video w-full shrink-0 object-cover"
            loading="lazy"
            decoding="async"
        />
    @else
        <div
            class="flex aspect-video w-full shrink-0 items-center justify-center bg-gradient-to-br from-accent-soft via-surface to-accent-bright/15 font-display text-3xl font-bold text-accent/45"
            aria-hidden="true"
        >
            {{ strtoupper(mb_substr($accountGame->name, 0, 1)) }}
        </div>
    @endif

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-start justify-between gap-2">
            <span class="min-w-0 truncate text-xs font-semibold text-ink-soft">{{ $accountGame->name }}</span>

            <x-status-badge :status="$account->status" class="shrink-0" />
        </div>

        <h3 class="mt-2.5 font-display text-base leading-snug font-semibold tracking-tight text-ink">
            <a href="{{ $detailUrl }}" class="transition-colors hover:text-accent">
                <span class="absolute inset-0" aria-hidden="true"></span>
                {{ $account->title }}
            </a>
        </h3>

        <p class="mt-1 font-mono text-xs text-ink-soft">{{ $account->account_code }}</p>

        <p class="mt-5 text-xl font-bold tracking-tight tabular-nums text-ink">
            {{ $account->formattedPrice() }}
        </p>

        <div class="mt-auto pt-5">
            @if ($account->isAvailable())
                <a
                    href="{{ $account->whatsappUrl() }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="relative flex items-center justify-center rounded-full bg-wa px-4 py-2.5 text-sm font-semibold text-white transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                >
                    Pesan lewat WhatsApp
                </a>
            @else
                {{-- Not a disabled button: a greyed-out control reads as broken. The
                     status label plus a route to ask is the useful next step. --}}
                <a
                    href="{{ route('games.show', $accountGame) }}"
                    class="flex items-center justify-center gap-2 rounded-full bg-ink/5 px-4 py-2.5 text-sm font-semibold text-ink-soft transition-colors hover:bg-ink/10 hover:text-ink focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                >
                    {{ $account->status->label() }} &mdash; cari akun lain
                </a>
            @endif
        </div>
    </div>
</article>