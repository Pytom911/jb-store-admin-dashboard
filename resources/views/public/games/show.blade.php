@extends('layouts.app')

@section('title', $game->name)
@section('description', $game->description ?: "Daftar akun {$game->name} yang tersedia di toko kami.")

@section('content')
    @php
        $sortOptions = ['price_asc' => 'Harga Termurah', 'price_desc' => 'Harga Termahal'];
        $currentStatus = $status ?: \App\Enums\AccountStatus::Available->value;
    @endphp

    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="text-sm">
            <a href="{{ route('games.index') }}" class="text-slate-500 transition hover:text-slate-900">Game</a>
            <span class="mx-1.5 text-slate-300">/</span>
            <span class="font-medium text-slate-900">{{ $game->name }}</span>
        </nav>

        <div class="mt-6 flex flex-col gap-6 sm:flex-row sm:items-start">
            <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-slate-900 ring-1 ring-slate-200">
                @if ($game->image_url)
                    <img src="{{ $game->image_url }}" alt="{{ $game->name }}" class="h-full w-full object-cover" />
                @else
                    <span class="text-3xl font-bold text-white/40">{{ strtoupper(mb_substr($game->name, 0, 1)) }}</span>
                @endif
            </div>

            <div class="min-w-0">
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">{{ $game->name }}</h1>

                @if ($game->description)
                    <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600">{{ $game->description }}</p>
                @endif

                <p class="mt-4 text-sm text-slate-500">
                    <span class="font-semibold text-emerald-600">{{ $game->available_accounts_count }}</span> tersedia
                    dari total
                    <span class="font-semibold text-slate-700">{{ $game->accounts_count }}</span> akun
                </p>
            </div>
        </div>

        <div class="mt-10 flex flex-col gap-4 border-b border-slate-200 pb-4 lg:flex-row lg:items-center lg:justify-between">
            <nav aria-label="Saring status akun" class="-mx-1 flex gap-1 overflow-x-auto px-1">
                @foreach (\App\Enums\AccountStatus::options() as $statusValue => $statusLabel)
                    <a
                        href="{{ route('games.show', array_merge(['game' => $game, 'status' => $statusValue], $sort ? ['sort' => $sort] : [])) }}"
                        @if ($currentStatus === $statusValue) aria-current="true" @endif
                        class="rounded-lg px-3.5 py-2 text-sm font-medium whitespace-nowrap transition {{ $currentStatus === $statusValue ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}"
                    >
                        {{ $statusLabel }}
                    </a>
                @endforeach
            </nav>

            <form method="GET" class="flex items-end gap-2">
                <input type="hidden" name="status" value="{{ $currentStatus }}" />

                <x-form.select
                    name="sort"
                    label="Urutkan"
                    :options="$sortOptions"
                    :value="$sort"
                    placeholder="Terbaru"
                />

                <x-button type="submit" variant="secondary">Urutkan</x-button>
            </form>
        </div>

        @if ($accounts->isEmpty())
            <x-empty-state
                class="mt-8"
                title="Tidak ada akun"
                :description="'Belum ada akun '.$game->name.' dengan status ini. Coba lihat status lain atau tanya stok lewat WhatsApp.'"
            />
        @else
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($accounts as $account)
                    <x-account-card :account="$account" :game="$game" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $accounts->links() }}
            </div>
        @endif
    </div>
@endsection
