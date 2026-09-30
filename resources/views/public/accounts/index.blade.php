@extends('layouts.app')

@section('title', 'Katalog Akun')
@section('description', 'Katalog akun game yang tersedia. Saring per game, status, dan harga.')

@section('content')
    @php
        $sortOptions = ['price_asc' => 'Harga Termurah', 'price_desc' => 'Harga Termahal'];
        $currentStatus = $status ?: \App\Enums\AccountStatus::Available->value;
        $hasFilter = filled($search) || filled($gameSlug) || filled($sort);
    @endphp

    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Katalog akun</h1>
            <p class="mt-3 text-base text-slate-600">
                Semua akun yang bisa dibeli beserta harga dan statusnya. Tekan tombol pesan untuk lanjut ke WhatsApp.
            </p>
        </div>

        <form
            method="GET"
            action="{{ route('accounts.index') }}"
            class="mt-8 flex flex-wrap items-end gap-3 rounded-xl bg-white p-4 ring-1 ring-slate-200"
        >
            <div class="w-full sm:w-56">
                <x-form.input
                    name="search"
                    label="Cari"
                    :value="$search"
                    placeholder="Kode atau judul akun"
                />
            </div>

            <div class="w-full sm:w-52">
                <x-form.select
                    name="game"
                    label="Game"
                    :options="$games->pluck('name', 'slug')->all()"
                    :value="$gameSlug"
                    placeholder="Semua game"
                />
            </div>

            <div class="w-full sm:w-44">
                <x-form.select
                    name="status"
                    label="Status"
                    :options="\App\Enums\AccountStatus::options()"
                    :value="$currentStatus"
                />
            </div>

            <div class="w-full sm:w-44">
                <x-form.select
                    name="sort"
                    label="Urutkan"
                    :options="$sortOptions"
                    :value="$sort"
                    placeholder="Terbaru"
                />
            </div>

            <x-button type="submit" variant="secondary">Filter</x-button>

            @if ($hasFilter)
                <a
                    href="{{ route('accounts.index', ['status' => $currentStatus]) }}"
                    class="pb-2 text-sm font-medium text-slate-500 transition hover:text-slate-800"
                >
                    Reset
                </a>
            @endif
        </form>

        @if ($accounts->isEmpty())
            <x-empty-state
                class="mt-6"
                title="Tidak ada akun"
                :description="$hasFilter
                    ? 'Tidak ada akun yang cocok dengan filter ini. Coba longgarkan kata kunci atau ganti filter.'
                    : 'Belum ada akun yang tersedia. Tanya admin lewat WhatsApp untuk cek stok terbaru.'"
            />
        @else
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($accounts as $account)
                    <x-account-card :account="$account" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $accounts->links() }}
            </div>
        @endif
    </div>
@endsection
