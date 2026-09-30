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

<article @class([
    'relative flex h-full flex-col overflow-hidden rounded-xl bg-white ring-1 transition',
    'ring-slate-200 hover:ring-slate-300' => $account->isAvailable(),
    'ring-slate-200 opacity-70' => ! $account->isAvailable(),
])>
    @if ($account->coverImage)
        <img
            src="{{ $account->coverImage->url }}"
            alt="{{ $account->title }}"
            class="aspect-video w-full shrink-0 object-cover"
            loading="lazy"
        />
    @else
        <div
            class="flex aspect-video w-full shrink-0 items-center justify-center bg-slate-100 text-2xl font-semibold text-slate-300"
            aria-hidden="true"
        >
            {{ strtoupper(mb_substr($accountGame->name, 0, 1)) }}
        </div>
    @endif

    <div class="flex flex-1 flex-col p-4">
        <div class="flex items-center justify-between gap-2">
            <span class="truncate text-xs font-medium text-slate-500">{{ $accountGame->name }}</span>

            <x-status-badge :status="$account->status" />
        </div>

        <h3 class="mt-2 text-sm font-semibold text-slate-900">
            <a href="{{ $detailUrl }}" class="transition hover:text-emerald-700">
                <span class="absolute inset-0" aria-hidden="true"></span>
                {{ $account->title }}
            </a>
        </h3>

        <p class="mt-1 font-mono text-xs text-slate-400">{{ $account->account_code }}</p>

        <p class="mt-4 text-xl font-semibold tracking-tight tabular-nums text-slate-900">
            {{ $account->formattedPrice() }}
        </p>

        <div class="mt-auto pt-4">
            @if ($account->isAvailable())
                <a
                    href="{{ $account->whatsappUrl() }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="relative flex items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                >
                    Pesan
                </a>
            @else
                <span class="flex items-center justify-center rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium text-slate-400">
                    {{ $account->status->label() }}
                </span>
            @endif
        </div>
    </div>
</article>
