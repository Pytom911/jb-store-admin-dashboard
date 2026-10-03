@php
    $stock = $game->available_accounts_count ?? $game->availableAccounts()->count();
@endphp

<a
    href="{{ route('games.show', $game) }}"
    class="group block w-52 shrink-0 snap-start rounded-2xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent sm:w-64"
>
    <div class="relative aspect-4/5 overflow-hidden rounded-2xl bg-ink/5 ring-1 ring-inset ring-ink/5">
        @if ($game->image_url)
            <img
                src="{{ $game->image_url }}"
                alt="{{ $game->name }}"
                width="512"
                height="640"
                loading="lazy"
                decoding="async"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105 motion-reduce:transition-none motion-reduce:group-hover:scale-100"
            />
        @else
            <span
                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-accent-soft to-surface font-display text-4xl font-bold text-accent/45"
                aria-hidden="true"
            >
                {{ strtoupper(mb_substr($game->name, 0, 1)) }}
            </span>
        @endif

        @if ($stock > 0)
            <span
                class="absolute bottom-2 left-2 inline-flex items-center gap-1.5 rounded-full bg-pop-lime px-2.5 py-1 text-xs font-semibold text-white tabular-nums shadow-sm"
            >
                <span class="h-1.5 w-1.5 rounded-full bg-white" aria-hidden="true"></span>
                {{ $stock }} tersedia
            </span>
        @else
            <span class="absolute bottom-2 left-2 rounded-full bg-pop-slate px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                Stok habis
            </span>
        @endif
    </div>

    <h3 class="mt-3 truncate font-display text-base font-semibold tracking-tight text-ink transition-colors group-hover:text-accent">
        {{ $game->name }}
    </h3>

    <p @class([
        'mt-1 text-xs font-semibold',
        'text-wa' => $stock > 0,
        'text-ink-soft' => $stock === 0,
    ])>
        @if ($stock > 0)
            {{ $stock }} akun siap beli
        @else
            Tanya admin soal stok
        @endif
    </p>
</a>