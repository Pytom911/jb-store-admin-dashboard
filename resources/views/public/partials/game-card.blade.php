@php
    $stock = $game->available_accounts_count ?? $game->availableAccounts()->count();
@endphp

<a
    href="{{ route('games.show', $game) }}"
    class="group relative flex h-full flex-col overflow-hidden rounded-xl bg-white ring-1 ring-slate-200 transition hover:ring-indigo-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
>
    <div class="flex aspect-16/10 items-center justify-center overflow-hidden bg-slate-900">
        @if ($game->image_url)
            <img
                src="{{ $game->image_url }}"
                alt="{{ $game->name }}"
                loading="lazy"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            />
        @else
            <span class="text-3xl font-bold text-white/40">{{ strtoupper(mb_substr($game->name, 0, 1)) }}</span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-4">
        <h3 class="font-semibold text-slate-900 transition group-hover:text-indigo-600">{{ $game->name }}</h3>

        <p class="mt-auto pt-3 text-sm text-slate-500">
            @if ($stock > 0)
                <span class="font-medium text-emerald-600">{{ $stock }} akun tersedia</span>
            @else
                <span class="text-slate-400">Stok habis</span>
            @endif
        </p>
    </div>
</a>
