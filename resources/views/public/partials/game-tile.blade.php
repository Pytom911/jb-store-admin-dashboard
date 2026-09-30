@php
    $stock = $game->available_accounts_count ?? $game->availableAccounts()->count();
@endphp

<a
    href="{{ route('games.show', $game) }}"
    class="group flex w-56 shrink-0 snap-start flex-col overflow-hidden rounded-xl bg-white ring-1 ring-slate-200 transition hover:ring-slate-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 sm:w-60"
>
    <div class="flex aspect-16/9 items-center justify-center overflow-hidden bg-slate-100">
        @if ($game->image_url)
            <img
                src="{{ $game->image_url }}"
                alt="{{ $game->name }}"
                loading="lazy"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            />
        @else
            <span class="text-3xl font-bold text-slate-300" aria-hidden="true">
                {{ strtoupper(mb_substr($game->name, 0, 1)) }}
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-3.5">
        <h3 class="truncate text-sm font-semibold text-slate-900 transition group-hover:text-emerald-700">
            {{ $game->name }}
        </h3>

        <p class="mt-auto pt-3 text-xs font-medium {{ $stock > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
            @if ($stock > 0)
                {{ $stock }} akun tersedia
            @else
                Stok habis
            @endif
        </p>
    </div>
</a>
