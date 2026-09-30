@extends('layouts.app')

@section('title', 'Akun Game Original Siap Pakai')
@section('description', 'JajanAkun: akun game original dengan garansi 7 hari. Pilih akun, konfirmasi lewat WhatsApp, data login dikirim setelah pembayaran.')

@section('content')
    @php
        $whatsappUrl = 'https://wa.me/'.config('marketplace.whatsapp_number');
        $heroCovers = $games->filter(fn ($game) => $game->image_url)->take(4)->values();
    @endphp

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <h1 class="text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">
                        Akun game original, siap pakai.
                    </h1>

                    <p class="mt-5 max-w-lg text-base leading-relaxed text-slate-600">
                        <span class="font-semibold text-slate-900 tabular-nums">{{ $stats['available'] }} akun</span>
                        siap beli dari
                        <span class="font-semibold text-slate-900 tabular-nums">{{ $stats['games'] }} game</span>,
                        garansi 7 hari. Data login dikirim setelah pembayaran.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a
                            href="{{ route('accounts.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2"
                        >
                            Lihat katalog
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>

                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-sm font-medium text-slate-500 underline decoration-slate-300 underline-offset-4 transition hover:text-slate-900 hover:decoration-slate-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600"
                        >
                            Tanya stok lewat WhatsApp
                        </a>
                    </div>
                </div>

                @if ($heroCovers->isNotEmpty())
                    <div>
                        @if ($heroCovers->count() === 1)
                            <img
                                src="{{ $heroCovers->first()->image_url }}"
                                alt="{{ $heroCovers->first()->name }}"
                                class="aspect-16/10 w-full rounded-2xl object-cover shadow-sm ring-1 ring-slate-200"
                            />
                        @else
                            <div class="grid grid-cols-2 gap-4">
                                @foreach ($heroCovers as $cover)
                                    <a
                                        href="{{ route('games.show', $cover) }}"
                                        class="group relative block overflow-hidden rounded-2xl bg-slate-900 ring-1 ring-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600"
                                    >
                                        <img
                                            src="{{ $cover->image_url }}"
                                            alt="{{ $cover->name }}"
                                            class="aspect-square w-full object-cover opacity-90 transition duration-300 group-hover:scale-105 group-hover:opacity-100"
                                            loading="lazy"
                                        />

                                        <span class="absolute inset-x-0 bottom-0 bg-slate-900/85 px-3 py-2 text-xs font-semibold text-white">
                                            {{ $cover->name }}
                                        </span>

                                        @if (($cover->available_accounts_count ?? 0) > 0)
                                            <span class="absolute top-3 left-3 rounded-md bg-white px-2 py-0.5 text-xs font-semibold text-slate-900 tabular-nums">
                                                {{ $cover->available_accounts_count }} tersedia
                                            </span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="border-y border-slate-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <dl class="grid gap-y-6 py-7 sm:grid-cols-3 sm:gap-x-8">
                @foreach ([
                    ['title' => 'Garansi 7 hari', 'body' => 'Ada yang tidak sesuai? Dikembalikan dalam seminggu.'],
                    ['title' => 'Bayar setelah konfirmasi', 'body' => 'Cek dulu akunnya di sini, baru transfer.'],
                    ['title' => 'Dikirim setelah transfer', 'body' => 'Data login masuk paling lama 10 menit.'],
                ] as $point)
                    <div>
                        <dt class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                            <svg class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            {{ $point['title'] }}
                        </dt>
                        <dd class="mt-1.5 pl-6 text-sm leading-relaxed text-slate-500">{{ $point['body'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    @if ($games->isNotEmpty())
        <section class="bg-white pt-14 sm:pt-16">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between gap-4">
                    <h2 class="text-xl font-semibold tracking-tight text-slate-900">Pilih game</h2>

                    <a
                        href="{{ route('games.index') }}"
                        class="shrink-0 text-sm font-medium text-slate-500 transition hover:text-slate-900"
                    >
                        Semua game
                    </a>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto pb-2">
                <div class="mx-auto flex max-w-6xl snap-x snap-mandatory gap-4 px-4 sm:px-6 lg:px-8">
                    @foreach ($games as $game)
                        @include('public.partials.game-tile', ['game' => $game])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight text-slate-900">Stok terbaru</h2>
                <p class="mt-1.5 text-sm text-slate-500">Akun yang baru masuk etalase.</p>
            </div>

            <a
                href="{{ route('accounts.index') }}"
                class="shrink-0 text-sm font-medium text-slate-500 transition hover:text-slate-900"
            >
                Lihat semua
            </a>
        </div>

        @if ($latestAccounts->isEmpty())
            <x-empty-state
                class="mt-6"
                title="Belum ada akun"
                description="Akun yang ditambahkan admin akan muncul di bagian ini."
            />
        @else
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($latestAccounts as $account)
                    <x-account-card :account="$account" />
                @endforeach
            </div>
        @endif
    </section>

    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold tracking-tight text-slate-900">Harga termurah</h2>
                <p class="mt-1.5 text-sm text-slate-500">Akun termurah yang masih tersedia, diurutkan dari yang paling murah.</p>
            </div>

            <a
                href="{{ route('accounts.index', ['sort' => 'price_asc']) }}"
                class="shrink-0 text-sm font-medium text-slate-500 transition hover:text-slate-900"
            >
                Lihat semua
            </a>
        </div>

        @if ($availableAccounts->isEmpty())
            <x-empty-state
                class="mt-6"
                title="Stok sedang kosong"
                description="Belum ada akun yang tersedia. Tanya admin lewat WhatsApp untuk cek stok terbaru."
            />
        @else
            <div class="mt-6 divide-y divide-slate-200 rounded-xl bg-white ring-1 ring-slate-200">
                @foreach ($availableAccounts as $account)
                    @include('public.partials.account-row', ['account' => $account])
                @endforeach
            </div>
        @endif
    </section>

    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold tracking-tight text-slate-900">Cara beli</h2>
        <p class="mt-1.5 text-sm text-slate-500">Tiga langkah, tanpa registrasi akun.</p>

        <ol class="mt-7 flex flex-col gap-6 sm:flex-row sm:gap-4">
            @foreach ([
                ['title' => 'Pilih akun', 'body' => 'Cek status dan harganya di katalog.'],
                ['title' => 'Konfirmasi', 'body' => 'Tekan Pesan, kode akun ikut terkirim.'],
                ['title' => 'Transfer', 'body' => 'Data login menyusul, masuk dalam 10 menit.'],
            ] as $index => $step)
                <li class="flex flex-1 gap-3">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-semibold text-white tabular-nums">
                        {{ $index + 1 }}
                    </span>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $step['body'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6 lg:px-8">
        <div class="flex flex-col items-start gap-6 rounded-2xl bg-slate-900 px-6 py-10 sm:flex-row sm:items-center sm:justify-between sm:px-10">
            <div>
                <h2 class="text-lg font-semibold tracking-tight text-white">Stoknya belum ada di etalase?</h2>
                <p class="mt-2 text-sm text-slate-400">
                    Tanya ke +{{ config('marketplace.whatsapp_number') }}, biasanya dibalas di bawah 10 menit.
                </p>
            </div>

            <a
                href="{{ $whatsappUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900"
            >
                Tanya stok
            </a>
        </div>
    </section>
@endsection
