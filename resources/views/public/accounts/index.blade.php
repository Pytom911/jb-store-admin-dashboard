@extends('layouts.app')

@section('title', 'Katalog Akun')
@section('description', 'Katalog akun game yang tersedia. Saring per game, status, dan harga.')

@section('content')
    @php
        $sortOptions = ['price_asc' => 'Harga Termurah', 'price_desc' => 'Harga Termahal'];
        $currentStatus = $status ?: \App\Enums\AccountStatus::Available->value;
        $hasFilter = filled($search) || filled($gameSlug) || filled($sort);
    @endphp

    <section class="pop-wash-cool border-b border-rule">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <p class="font-display text-xs font-bold tracking-[0.14em] text-accent uppercase">Katalog</p>

            <div class="mt-1 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div class="max-w-2xl">
                    <h1 class="font-display text-3xl font-bold tracking-tight text-balance text-ink sm:text-4xl">
                        Katalog Akun
                    </h1>

                    <p class="mt-3 text-base leading-relaxed text-pretty text-ink-soft">
                        Semua akun yang bisa dibeli beserta harga dan statusnya. Tekan tombol pesan untuk lanjut ke
                        WhatsApp.
                    </p>
                </div>

                <p
                    class="shrink-0 rounded-full bg-surface px-4 py-2 text-sm font-semibold text-ink-soft tabular-nums ring-1 ring-inset ring-accent/25"
                    role="status"
                >
                    <span class="text-ink tabular-nums">{{ $accounts->total() }}</span> akun ditemukan
                </p>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <form
            method="GET"
            action="{{ route('accounts.index') }}"
            class="grid gap-4 rounded-2xl bg-surface p-5 ring-1 ring-inset ring-rule sm:grid-cols-2 lg:grid-cols-5 lg:items-end"
        >
            <x-form.input
                name="search"
                label="Cari"
                :value="$search"
                placeholder="Kode atau judul akun, misalnya AKL-1042…"
                autocomplete="off"
                :spellcheck="false"
            />

            <x-form.select
                name="game"
                label="Game"
                :options="$games->pluck('name', 'slug')->all()"
                :value="$gameSlug"
                placeholder="Semua game"
            />

            <x-form.select
                name="status"
                label="Status"
                :options="\App\Enums\AccountStatus::options()"
                :value="$currentStatus"
            />

            <x-form.select
                name="sort"
                label="Urutkan"
                :options="$sortOptions"
                :value="$sort"
                placeholder="Terbaru"
            />

            <div class="flex items-center gap-3 sm:col-span-2 lg:col-span-1">
                <button
                    type="submit"
                    class="inline-flex h-[42px] items-center justify-center rounded-xl bg-accent px-5 text-sm font-semibold text-white transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                >
                    Terapkan
                </button>

                @if ($hasFilter)
                    <a
                        href="{{ route('accounts.index', ['status' => $currentStatus]) }}"
                        class="rounded-full px-3 py-2 text-sm font-semibold text-ink-soft transition-colors hover:bg-accent-soft hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>

        @if ($hasFilter)
            <div class="mt-6">
                <x-alert tone="info">
                    Filter aktif, jadi yang tampil hanya akun yang cocok. Reset dulu kalau mau melihat seluruh katalog.
                </x-alert>
            </div>
        @endif

        @if ($accounts->isEmpty())
            <x-empty-state
                class="mt-10"
                title="Tidak ada akun"
                :description="$hasFilter
                    ? 'Tidak ada akun yang cocok dengan filter ini. Coba longgarkan kata kunci atau ganti filter.'
                    : 'Belum ada akun yang tersedia. Tanya admin lewat WhatsApp untuk cek stok terbaru.'"
            />
        @else
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($accounts as $account)
                    <x-account-card :account="$account" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $accounts->links() }}
            </div>
        @endif
    </div>
@endsection