@php
    $stock = $game->available_accounts_count ?? $game->availableAccounts()->count();
@endphp

<a
    href="{{ route('games.show', $game) }}"
    class="group flex h-full flex-col overflow-hidden rounded-2xl bg-surface ring-1 ring-inset ring-rule transition-shadow hover:shadow-lg hover:shadow-accent/10 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
>
    <div class="relative aspect-4/3 overflow-hidden bg-ink/5">
        @if ($game->image_url)
            <img
                src="{{ $game->image_url }}"
                alt="{{ $game->name }}"
                width="640"
                height="480"
                loading="lazy"
                decoding="async"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105 motion-reduce:transition-none motion-reduce:group-hover:scale-100"
            />
        @else
            <span
                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-accent-soft via-surface to-pop-cyan/10 font-display text-5xl font-bold text-accent/45"
                aria-hidden="true"
            >
                {{ strtoupper(mb_substr($game->name, 0, 1)) }}
            </span>
        @endif

        @if ($stock > 0)
            <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-pop-lime px-2.5 py-1 text-xs font-semibold text-white tabular-nums shadow-sm">
                <span class="h-1.5 w-1.5 rounded-full bg-white" aria-hidden="true"></span>
                {{ $stock }} tersedia
            </span>
        @else
            <span class="absolute top-3 left-3 rounded-full bg-pop-slate px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                Stok habis
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        <h3 class="font-display text-lg font-semibold tracking-tight text-ink transition-colors group-hover:text-accent">
            {{ $game->name }}
        </h3>

        @if ($game->description)
            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-ink-soft">
                {{ $game->description }}
            </p>
        @endif

        <p @class([
            'mt-auto pt-5 text-sm font-semibold',
            'text-wa' => $stock > 0,
            'text-ink-soft' => $stock === 0,
        ])>
            @if ($stock > 0)
                {{ $stock }} akun siap beli
            @else
                Tanya admin soal stok
            @endif
        </p>
    </div>
</a>