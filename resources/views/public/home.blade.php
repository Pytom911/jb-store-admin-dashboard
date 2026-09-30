@extends('layouts.app')

@section('title', 'Jual Beli Akun Game Original')
@section('description', 'Beli akun game original dengan garansi 7 hari. Stok diperbarui setiap hari, dikirim lewat WhatsApp.')

@section('content')
    @php
        $whatsappUrl = 'https://wa.me/'.config('marketplace.whatsapp_number');
        $heroCovers = $games->filter(fn ($game) => $game->image_url)->take(4)->values();
    @endphp

    <section class="relative overflow-hidden bg-slate-950">
        <div
            class="absolute inset-0"
            style="background-image:radial-gradient(75% 65% at 50% 0%, #4338ca 0%, #0f172a 62%)"
        ></div>

        <div
            class="absolute inset-0 opacity-[0.07]"
            style="background-image:linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px);background-size:56px 56px"
        ></div>

        <div class="relative mx-auto max-w-6xl px-4 pt-16 pb-32 sm:px-6 sm:pt-20 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-indigo-100 ring-1 ring-white/15">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        {{ $stats['available'] }} akun siap dibeli
                    </p>

                    <h1 class="mt-6 text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                        Akun game original, langsung bisa dimainkan.
                    </h1>

                    <p class="mt-5 max-w-lg text-base leading-relaxed text-slate-300">
                        Pilih akun dari katalog, konfirmasi lewat WhatsApp, dan data login dikirim
                        setelah pembayaran. Ada garansi 7 hari.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a
                            href="{{ route('accounts.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950"
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
                            class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-5 py-3 text-sm font-semibold text-white ring-1 ring-white/20 transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950"
                        >
                            Tanya stok via WhatsApp
                        </a>
                    </div>
                </div>

                @if ($heroCovers->isNotEmpty())
                    <div class="hidden lg:block">
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($heroCovers as $cover)
                                <img
                                    src="{{ $cover->image_url }}"
                                    alt="{{ $cover->name }}"
                                    class="aspect-square w-full rounded-2xl object-cover shadow-2xl ring-1 ring-white/10 {{ $cover->id % 2 ? 'rotate-1' : '-rotate-1' }}"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="mx-auto -mt-20 max-w-6xl px-4 sm:px-6 lg:px-8">
        <dl class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([
                ['label' => 'Game tersedia', 'value' => $stats['games'], 'tone' => 'text-indigo-600'],
                ['label' => 'Total akun', 'value' => $stats['accounts'], 'tone' => 'text-slate-900'],
                ['label' => 'Siap dibeli', 'value' => $stats['available'], 'tone' => 'text-emerald-600'],
                ['label' => 'Sudah terjual', 'value' => $stats['sold'], 'tone' => 'text-amber-600'],
            ] as $stat)
                <div class="rounded-xl bg-white p-5 shadow-lg shadow-slate-900/5 ring-1 ring-slate-200">
                    <dt class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</dt>
                    <dd class="mt-2 text-3xl font-semibold tracking-tight {{ $stat['tone'] }}">{{ $stat['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    </section>

    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Pilih game favoritmu</h2>
                <p class="mt-2 text-sm text-slate-500">Cek stok per game sebelum memilih akun.</p>
            </div>

            <a
                href="{{ route('games.index') }}"
                class="shrink-0 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500"
            >
                Semua game
            </a>
        </div>

        @if ($games->isEmpty())
            <x-empty-state
                class="mt-6"
                title="Belum ada game"
                description="Game yang diaktifkan admin akan tampil di sini."
            />
        @else
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($games as $game)
                    @include('public.partials.game-card', ['game' => $game])
                @endforeach
            </div>
        @endif
    </section>

    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Akun terbaru</h2>
                <p class="mt-2 text-sm text-slate-500">Stok yang baru diinput ke etalase.</p>
            </div>

            <a
                href="{{ route('accounts.index') }}"
                class="shrink-0 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500"
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
                    @include('public.partials.account-card', ['account' => $account])
                @endforeach
            </div>
        @endif
    </section>

    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Harga termurah</h2>
                <p class="mt-2 text-sm text-slate-500">Akun termurah yang masih tersedia hari ini.</p>
            </div>

            <a
                href="{{ route('accounts.index', ['sort' => 'price_asc']) }}"
                class="shrink-0 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500"
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
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($availableAccounts as $account)
                    @include('public.partials.account-card', ['account' => $account])
                @endforeach
            </div>
        @endif
    </section>

    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Cara beli</h2>
        <p class="mt-2 text-sm text-slate-500">Tiga langkah, tanpa registrasi akun.</p>

        <ol class="mt-6 grid gap-4 sm:grid-cols-3">
            @foreach ([
                ['title' => 'Pilih akun', 'body' => 'Telusuri katalog atau kategori game. Status dan harga tertera di setiap kartu.'],
                ['title' => 'Konfirmasi via WhatsApp', 'body' => 'Tekan tombol pesan di kartu akun. Kode akun ikut terkirim supaya tidak salah.'],
                ['title' => 'Transfer dan masuk', 'body' => 'Setelah pembayaran, data login dikirim. Ada garansi 7 hari.'],
            ] as $index => $step)
                <li class="rounded-xl bg-white p-6 ring-1 ring-slate-200">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-sm font-semibold text-indigo-600">
                        {{ $index + 1 }}
                    </span>

                    <h3 class="mt-4 font-semibold text-slate-900">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ $step['body'] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6 lg:px-8">
        <div class="flex flex-col items-start gap-6 rounded-2xl bg-slate-900 px-6 py-10 sm:flex-row sm:items-center sm:justify-between sm:px-10">
            <div>
                <h2 class="text-xl font-semibold tracking-tight text-white">Mau cek stok yang belum di etalase?</h2>
                <p class="mt-2 text-sm text-slate-400">
                    Kirim pesan ke +{{ config('marketplace.whatsapp_number') }}, biasanya dibalas di bawah 10 menit.
                </p>
            </div>

            <a
                href="{{ $whatsappUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900"
            >
                Chat WhatsApp
            </a>
        </div>
    </section>
@endsection
