@extends('layouts.app')

@section('title', $game->name)
@section('description', $game->description ?: "Daftar akun {$game->name} yang tersedia di toko kami.")

@section('content')
    @php
        $sortOptions = ['price_asc' => 'Harga Termurah', 'price_desc' => 'Harga Termahal'];
        $currentStatus = $status ?: \App\Enums\AccountStatus::Available->value;
    @endphp

    <section class="pop-wash-cool border-b border-rule">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <nav aria-label="Breadcrumb" class="text-sm text-ink-soft">
                <a href="{{ route('games.index') }}" class="rounded transition-colors hover:text-accent">Semua game</a>
                <span class="mx-1.5 text-ink/30" aria-hidden="true">/</span>
                <span class="font-medium text-ink">{{ $game->name }}</span>
            </nav>

            <div class="mt-7 flex flex-col gap-7 sm:flex-row sm:items-start">
                <div class="aspect-square w-32 shrink-0 overflow-hidden rounded-2xl bg-ink/5 ring-1 ring-inset ring-ink/5 sm:w-44">
                    @if ($game->image_url)
                        <img
                            src="{{ $game->image_url }}"
                            alt="{{ $game->name }}"
                            width="352"
                            height="352"
                            fetchpriority="high"
                            class="h-full w-full object-cover"
                        />
                    @else
                        <span
                            class="flex h-full w-full items-center justify-center bg-gradient-to-br from-accent-soft to-surface font-display text-5xl font-bold text-accent/45"
                            aria-hidden="true"
                        >
                            {{ strtoupper(mb_substr($game->name, 0, 1)) }}
                        </span>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <h1 class="font-display text-3xl leading-tight font-bold tracking-tight text-balance text-ink sm:text-4xl">
                        {{ $game->name }}
                    </h1>

                    @if ($game->description)
                        <p class="mt-3 max-w-2xl text-base leading-relaxed text-pretty text-ink-soft">{{ $game->description }}</p>
                    @endif

                    <dl class="mt-6 flex flex-wrap items-stretch gap-3">
                        <div class="rounded-2xl bg-wa px-4 py-3">
                            <dt class="text-xs font-semibold text-white/85">Tersedia</dt>
                            <dd class="font-display text-2xl font-bold text-white tabular-nums">
                                {{ $game->available_accounts_count }}
                            </dd>
                        </div>

                        <div class="rounded-2xl bg-surface px-4 py-3 ring-1 ring-inset ring-rule">
                            <dt class="text-xs font-semibold text-ink-soft">Total akun</dt>
                            <dd class="font-display text-2xl font-bold text-ink tabular-nums">
                                {{ $game->accounts_count }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            {{-- Status stays a link list so each tab is deep-linkable and the
                 current filter is visible in the URL. --}}
            <nav aria-label="Saring status akun" class="-mx-1 flex gap-2 overflow-x-auto overscroll-x-contain px-1 pb-1">
                @foreach (\App\Enums\AccountStatus::options() as $statusValue => $statusLabel)
                    <a
                        href="{{ route('games.show', array_merge(['game' => $game, 'status' => $statusValue], $sort ? ['sort' => $sort] : [])) }}"
                        @if ($currentStatus === $statusValue) aria-current="true" @endif
                        @class([
                            'shrink-0 rounded-full px-4 py-2 text-sm font-semibold whitespace-nowrap transition-colors',
                            'bg-ink text-white' => $currentStatus === $statusValue,
                            'bg-surface text-ink-soft ring-1 ring-inset ring-rule hover:bg-accent-soft hover:text-accent' => $currentStatus !== $statusValue,
                        ])
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

                <button
                    type="submit"
                    class="inline-flex h-[42px] items-center justify-center rounded-xl bg-ink px-5 text-sm font-semibold text-white transition-[filter] hover:brightness-125 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                >
                    Urutkan
                </button>
            </form>
        </div>

        @if ($currentStatus === \App\Enums\AccountStatus::Reserved->value)
            <div class="mt-6">
                <x-alert tone="warning" title="Status sedang dipesan">
                    Akun berstatus ini sedang dipegang pembeli lain. Kalau masih muncul di daftar, hubungi admin
                    untuk memastikan kebenarannya sebelum transfer.
                </x-alert>
            </div>
        @endif

        @if ($accounts->isEmpty())
            <x-empty-state
                class="mt-10"
                title="Tidak ada akun"
                :description="'Belum ada akun '.$game->name.' dengan status ini. Coba lihat status lain atau tanya stok lewat WhatsApp.'"
            />
        @else
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($accounts as $account)
                    <x-account-card :account="$account" :game="$game" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $accounts->links() }}
            </div>
        @endif
    </div>
@endsection