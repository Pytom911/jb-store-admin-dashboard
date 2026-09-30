@php
    $accountGame = $account->game;
@endphp

<article
    @class([
        'relative flex items-center gap-4 px-4 py-3 transition',
        'hover:bg-slate-50' => $account->isAvailable(),
        'opacity-70' => ! $account->isAvailable(),
    ])
>
    @if ($account->coverImage)
        <img
            src="{{ $account->coverImage->url }}"
            alt=""
            class="h-14 w-24 shrink-0 rounded-lg object-cover ring-1 ring-slate-200"
            loading="lazy"
        />
    @else
        <span
            class="flex h-14 w-24 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-lg font-semibold text-slate-300 ring-1 ring-slate-200"
            aria-hidden="true"
        >
            {{ strtoupper(mb_substr($accountGame->name, 0, 1)) }}
        </span>
    @endif

    <div class="min-w-0 flex-1">
        <h3 class="truncate text-sm font-semibold text-slate-900">
            <a href="{{ route('accounts.show', $account->account_code) }}" class="transition hover:text-emerald-700">
                <span class="absolute inset-0" aria-hidden="true"></span>
                {{ $account->title }}
            </a>
        </h3>

        <p class="mt-0.5 flex items-baseline gap-2 truncate text-xs text-slate-500">
            <span class="truncate">{{ $accountGame->name }}</span>
            <span class="font-mono text-slate-400">{{ $account->account_code }}</span>
        </p>
    </div>

    <p class="shrink-0 text-base font-semibold tracking-tight tabular-nums text-slate-900">
        {{ $account->formattedPrice() }}
    </p>

    <div class="shrink-0">
        @if ($account->isAvailable())
            <a
                href="{{ $account->whatsappUrl() }}"
                target="_blank"
                rel="noopener noreferrer"
                class="relative inline-flex items-center justify-center rounded-lg bg-emerald-600 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
            >
                Pesan
            </a>
        @else
            <span class="inline-flex items-center rounded-lg bg-slate-100 px-3.5 py-2 text-sm font-medium text-slate-500">
                {{ $account->status->label() }}
            </span>
        @endif
    </div>
</article>
