@php
    // Games loaded through GameController@show already know their game, which keeps
    // this card from lazy-loading the relation once per account on that page.
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
    @endif

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-start justify-between gap-3">
            <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                {{ $accountGame->name }}
            </span>

            <x-status-badge :status="$account->status" />
        </div>

        <h3 class="mt-4 text-base font-semibold text-slate-900">
            <a href="{{ $detailUrl }}" class="transition hover:text-indigo-600">
                <span class="absolute inset-0" aria-hidden="true"></span>
                {{ $account->title }}
            </a>
        </h3>

        <p class="mt-1 font-mono text-xs text-slate-400">{{ $account->account_code }}</p>

        <p class="mt-4 text-lg font-semibold tracking-tight text-slate-900">{{ $account->formattedPrice() }}</p>

        <div class="mt-auto pt-5">
            @if ($account->isAvailable())
                <a
                    href="{{ $account->whatsappUrl() }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="relative flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.875 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25a8.25 8.25 0 0 1-12.28 7.16L3 21l2.59-5.72A8.25 8.25 0 1 1 21 11.25Z" />
                    </svg>

                    Pesan via WhatsApp
                </a>
            @else
                <span class="flex items-center justify-center rounded-lg bg-slate-100 px-3.5 py-2 text-sm font-medium text-slate-400">
                    {{ $account->status->label() }}
                </span>
            @endif
        </div>
    </div>
</article>
