@php
    $stock = $game->available_accounts_count ?? $game->availableAccounts()->count();
    $isLazy = $lazy ?? true;
@endphp

{{--
    Same card language as game-card and account-card: white surface, rounded-2xl,
    hairline ring, soft shadow that lifts on hover. The frame keeps a fixed
    aspect ratio and object-cover, so covers of any shape render at the same
    size without distorting. Shared by the hero marquee and the "Pilih Game"
    strip, so both read as one component.
--}}
<a
    href="{{ route('games.show', $game) }}"
    class="group block w-52 shrink-0 snap-start overflow-hidden rounded-2xl bg-surface shadow-sm ring-1 ring-inset ring-rule transition-[transform,box-shadow] duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:shadow-accent/20 focus-visible:-translate-y-1 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent motion-reduce:transform-none motion-reduce:transition-none sm:w-64"
>
    <div class="relative aspect-4/5 overflow-hidden bg-accent-soft/40">
        @if ($game->image_url)
            <img
                src="{{ $game->image_url }}"
                alt="{{ $game->name }}"
                width="512"
                height="640"
                @if ($isLazy)
                    loading="lazy"
                    decoding="async"
                @else
                    fetchpriority="high"
                    decoding="async"
                @endif
                class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105 motion-reduce:transition-none motion-reduce:group-hover:scale-100"
            />
        @else
            <span
                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-accent-deep via-accent to-accent-bright font-display text-4xl font-bold text-white/90"
                aria-hidden="true"
            >
                {{ strtoupper(mb_substr($game->name, 0, 1)) }}
            </span>
        @endif

        {{-- Scrim: the stock chip sits on artwork the store owner uploaded, so
             it cannot rely on the image being dark enough behind it. --}}
        <span
            class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-ink/65 via-ink/20 to-transparent"
            aria-hidden="true"
        ></span>

        @if ($stock > 0)
            <span
                class="absolute bottom-2 left-2 inline-flex items-center gap-1.5 rounded-full bg-accent px-2.5 py-1 text-xs font-semibold text-white tabular-nums shadow-sm ring-1 ring-inset ring-white/25"
            >
                <span class="h-1.5 w-1.5 rounded-full bg-white" aria-hidden="true"></span>
                {{ $stock }} tersedia
            </span>
        @else
            <span class="absolute bottom-2 left-2 rounded-full bg-pop-slate px-2.5 py-1 text-xs font-semibold text-white shadow-sm ring-1 ring-inset ring-white/25">
                Stok habis
            </span>
        @endif
    </div>

    <div class="p-4">
        <h3 class="truncate font-display text-base font-semibold tracking-tight text-ink transition-colors group-hover:text-accent">
            {{ $game->name }}
        </h3>

        <p @class([
            'mt-1 text-xs font-semibold',
            'text-accent' => $stock > 0,
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