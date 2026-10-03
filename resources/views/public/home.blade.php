@extends('layouts.app')

@section('title', 'Akun Game Original Siap Pakai')
@section('description', 'JajanAkun: akun game original dengan garansi 7 hari. Pilih akun, konfirmasi lewat WhatsApp, data login dikirim setelah pembayaran.')

@section('content')
    @php
        $whatsappUrl = 'https://wa.me/'.config('marketplace.whatsapp_number');
        $whatsappNumber = '+'.config('marketplace.whatsapp_number');
    @endphp

    {{-- ============================ HERO ============================ --}}
    <section class="hero-wash border-b border-rule">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 sm:py-16 xl:grid-cols-12 xl:gap-14 xl:px-8 xl:py-20">
            <div class="xl:col-span-5">
                <p
                    class="inline-flex items-center gap-2 rounded-full bg-surface px-3.5 py-1.5 text-xs font-semibold text-ink-soft ring-1 ring-inset ring-accent/25"
                >
                    <span class="relative flex h-2 w-2" aria-hidden="true">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-wa opacity-75 motion-reduce:hidden"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-wa"></span>
                    </span>

                    <span class="tabular-nums">{{ $stats['available'] }}</span> akun siap beli dari
                    <span class="tabular-nums">{{ $stats['games'] }}</span> game
                </p>

                <h1 class="mt-6 font-display text-[clamp(2.5rem,7vw,4.5rem)] leading-[0.95] font-bold tracking-[-0.03em] text-balance text-ink">
                    Akun game original, siap pakai hari ini.
                </h1>

                <p class="mt-6 max-w-xl text-lg leading-relaxed text-pretty text-ink-soft">
                    Cek etalasenya lebih dulu, konfirmasi lewat WhatsApp, baru transfer. Data login menyusul paling
                    lama 10 menit setelah pembayaran, dengan garansi 7 hari.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a
                        href="{{ route('accounts.index') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-accent-deep via-accent to-accent-bright px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/25 transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    >
                        Lihat katalog akun

                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>

                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-full bg-wa px-6 py-3.5 text-sm font-semibold text-white transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-wa"
                    >
                        Tanya stok
                    </a>
                </div>
            </div>

            <div class="xl:col-span-7">
                @if ($games->isNotEmpty())
                    {{--
                        One set only. The driver in app.js repeats it until two
                        sets span the frame, then scrolls by exactly one set
                        width, so the wrap point is a handover rather than a
                        reset. Duplicating the loop here instead would ship two
                        copies of every game to assistive tech.
                    --}}
                    <div class="relative">
                        <div class="game-marquee py-6" data-game-marquee>
                            <div class="game-marquee__track" data-game-marquee-track>
                                <div class="game-marquee__set" data-game-marquee-set>
                                    @foreach ($games as $index => $game)
                                        @include('public.partials.game-tile', [
                                            'game' => $game,
                                            'lazy' => $index > 1,
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{--
                            WCAG 2.2.2 wants a user-controlled pause on motion
                            that runs past five seconds. It never engages on its
                            own: nothing stops the marquee for hover, scroll or
                            reaching the end of the loop.
                        --}}
                        <button
                            type="button"
                            data-game-marquee-toggle
                            aria-pressed="false"
                            class="absolute top-0 right-0 z-10 inline-flex h-9 w-9 items-center justify-center rounded-full bg-surface/90 text-ink-soft ring-1 ring-inset ring-rule backdrop-blur-sm transition-colors hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                        >
                            <span class="sr-only" data-marquee-toggle-label>Jedaikan kartu game</span>

                            <svg
                                class="h-4 w-4"
                                data-marquee-icon="pause"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path d="M8 5.25A.75.75 0 0 1 8.75 4.5h1.5a.75.75 0 0 1 .75.75v13.5a.75.75 0 0 1-.75.75h-1.5A.75.75 0 0 1 8 18.75V5.25Zm6 0A.75.75 0 0 1 14.75 4.5h1.5a.75.75 0 0 1 .75.75v13.5a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1-.75-.75V5.25Z" />
                            </svg>

                            <svg
                                class="hidden h-4 w-4"
                                data-marquee-icon="play"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" />
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="flex aspect-16/9 flex-col justify-between rounded-2xl bg-gradient-to-br from-accent-deep via-accent to-accent-bright p-6 text-white">
                        <span class="font-display text-3xl font-bold">{{ config('app.name') }}</span>
                        <p class="text-sm text-white/85">
                            Belum ada game di etalase. Akun yang tersedia bisa langsung dicek di katalog.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>


    {{-- ====================== JAMINAN / 3 KARTU ====================== --}}
    <section class="border-b border-rule bg-surface">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <dl class="grid gap-5 sm:grid-cols-3">
                @foreach ([
                    ['tone' => 'from-accent-bright to-accent', 'title' => 'Garansi 7 Hari', 'body' => 'Ada yang tidak sesuai? Dikembalikan dalam seminggu.'],
                    ['tone' => 'from-accent to-accent-deep', 'title' => 'Bayar Setelah Konfirmasi', 'body' => 'Cek dulu akunnya di sini, baru transfer.'],
                    ['tone' => 'from-accent-deep to-accent-deepest', 'title' => 'Dikirim Setelah Transfer', 'body' => 'Data login masuk paling lama 10 menit.'],
                ] as $point)
                    <div class="rounded-2xl bg-surface-2 p-6 ring-1 ring-inset ring-rule">
                        <span class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br text-white {{ $point['tone'] }}">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>

                        <dt class="font-display text-base font-bold tracking-tight text-balance text-ink">{{ $point['title'] }}</dt>
                        <dd class="mt-1.5 text-sm leading-relaxed text-ink-soft">{{ $point['body'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- ========================== PERINGATAN ========================== --}}
    @if ($stats['available'] > 0 && $stats['available'] <= 3)
        <div class="border-b border-rule bg-paper">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <x-alert tone="warning" title="Stok tinggal {{ $stats['available'] }} akun">
                    Struktur di bawah hampir habis. Kalau yang kamu cari tidak ada, tanya admin lewat WhatsApp
                    supaya dicek ke gudang.
                </x-alert>
            </div>
        </div>
    @endif

    {{-- ========================= PILIH GAME ========================= --}}
    @if ($games->isNotEmpty())
        <section class="border-b border-rule py-14 sm:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @include('public.partials.section-head', [
                    'eyebrow' => 'Etalase',
                    'accent' => 'mid',
                    'title' => 'Pilih Game',
                    'deck' => $games->count() >= 6
                        ? 'Enam game paling ramai etalasenya. Gulir ke samping untuk lihat yang lain.'
                        : 'Game yang tersedia di etalase kami.',
                    'linkLabel' => 'Semua game',
                    'linkHref' => route('games.index'),
                ])
            </div>

            {{-- Keyboard-scrollable: the region needs a tab stop and a name before
                 arrow keys reach it. Padding leaves the hover shadow room
                 inside the scroll container instead of letting it clip. --}}
            <div
                class="hide-scrollbar mt-6 overflow-x-auto overscroll-x-contain"
                tabindex="0"
                role="region"
                aria-label="Daftar game, gulir ke samping"
            >
                <div class="mx-auto flex max-w-7xl snap-x snap-mandatory gap-4 px-4 py-5 sm:gap-5 sm:px-6 lg:px-8">
                    @foreach ($games as $game)
                        @include('public.partials.game-tile', ['game' => $game])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ======================== STOK TERBARU ======================== --}}
    <section class="border-b border-rule py-14 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('public.partials.section-head', [
                'eyebrow' => 'Baru masuk',
                'accent' => 'accent',
                'title' => 'Stok Terbaru',
                'deck' => 'Akun yang baru masuk etalase, lengkap dengan harga dan statusnya.',
                'linkLabel' => 'Lihat katalog',
                'linkHref' => route('accounts.index'),
            ])

            @if ($latestAccounts->isEmpty())
                <x-empty-state
                    class="mt-8"
                    tone="wa"
                    title="Belum ada akun"
                    description="Akun yang ditambahkan admin akan muncul di bagian ini."
                    action-label="Cek lewat WhatsApp"
                    :action-url="$whatsappUrl"
                />
            @else
                <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($latestAccounts as $account)
                        <x-account-card :account="$account" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ======================= HARGA TERMURAH ======================= --}}
    <section class="pop-wash-alt border-b border-rule py-14 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('public.partials.section-head', [
                'eyebrow' => 'Paluran',
                'accent' => 'deep-mid',
                'title' => 'Harga Termurah',
                'deck' => 'Akun yang masih tersedia, diurutkan dari yang paling murah.',
                'linkLabel' => 'Urutkan semua',
                'linkHref' => route('accounts.index', ['sort' => 'price_asc']),
            ])

            @if ($availableAccounts->isEmpty())
                <x-empty-state
                    class="mt-8"
                    tone="wa"
                    title="Stok sedang kosong"
                    description="Belum ada akun yang tersedia. Tanya admin lewat WhatsApp untuk cek stok terbaru."
                    action-label="Tanya stok"
                    :action-url="$whatsappUrl"
                />
            @else
                <ul class="mt-4">
                    @foreach ($availableAccounts as $account)
                        <li class="flex items-stretch gap-4 sm:gap-6">
                            {{-- Rank chip replaces the old faint numeral: it was
                                 1.7:1 against the canvas and unreadable. --}}
                            <span
                                class="hidden w-10 shrink-0 self-center rounded-xl bg-gradient-to-br from-accent-bright to-accent-deep py-1.5 text-center font-display text-sm font-bold text-white tabular-nums sm:block"
                                aria-hidden="true"
                            >
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <div class="min-w-0 flex-1">
                                @include('public.partials.account-row', ['account' => $account])
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    {{-- ========================== CARA BELI ========================== --}}
    <section class="border-b border-rule py-14 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('public.partials.section-head', [
                'eyebrow' => 'Alur',
                'accent' => 'deep',
                'title' => 'Cara Beli',
                'deck' => 'Tiga langkah, tanpa daftar akun dan tanpa form yang panjang.',
            ])

            <ol class="mt-10 grid gap-6 sm:grid-cols-3 lg:gap-8">
                @foreach ([
                    ['tone' => 'from-accent-bright to-accent', 'title' => 'Pilih akun', 'body' => 'Cek status, harga, dan fotonya langsung di katalog.'],
                    ['tone' => 'from-accent to-accent-deep', 'title' => 'Konfirmasi', 'body' => 'Tekan Pesan, kode akun ikut terkirim ke admin.'],
                    ['tone' => 'from-accent-deep to-accent-deepest', 'title' => 'Transfer', 'body' => 'Data login menyusul, masuk dalam 10 menit.'],
                ] as $step)
                    <li class="relative rounded-2xl bg-surface p-6 ring-1 ring-inset ring-rule">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br font-display text-lg font-bold text-white {{ $step['tone'] }}">
                            {{ $loop->iteration }}
                        </span>

                        <h3 class="mt-5 font-display text-lg font-bold tracking-tight text-ink">{{ $step['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-ink-soft">{{ $step['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ======================= PERTANYAAN SINGKAT ======================= --}}
    @php
        $faqs = [
            [
                'q' => 'Apakah akunnya asli?',
                'a' => 'Semua akun dibuat khusus untuk pembeli, bukan hasil curian. Satu akun untuk satu pembeli.',
            ],
            [
                'q' => 'Kalau ternyata tidak cocok?',
                'a' => 'Ada garansi 7 hari. Bilang saja lewat WhatsApp, diganti atau dikembalikan.',
            ],
            [
                'q' => 'Berapa lama prosesnya?',
                'a' => 'Cek etalase, konfirmasi, transfer. Data login menyusul paling lama 10 menit setelah pembayaran.',
            ],
        ];
    @endphp

    <section class="border-b border-rule py-14 sm:py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            @include('public.partials.section-head', [
                'accent' => 'mid',
                'title' => 'Pertanyaan Singkat',
                'deck' => 'Tiga hal yang paling sering ditanyain sebelum transfer.',
            ])

            <div class="mt-8 divide-y divide-rule border-y border-rule">
                @foreach ($faqs as $faq)
                    <details class="group">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 text-left font-display text-base font-semibold text-ink transition-colors hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                        >
                            {{ $faq['q'] }}

                            <svg
                                class="h-5 w-5 shrink-0 text-ink-soft transition-transform group-open:rotate-45 motion-reduce:transition-none"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </summary>

                        <p class="pb-5 text-sm leading-relaxed text-pretty text-ink-soft">
                            {{ $faq['a'] }}
                        </p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =========================== CTA BAND =========================== --}}
    <section class="bg-ink text-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-8 px-4 py-14 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="max-w-xl">
                <h2 class="font-display text-2xl font-bold tracking-tight text-balance sm:text-3xl">
                    Stok yang dicari belum ada di etalase?
                </h2>

                <p class="mt-3 text-base leading-relaxed text-white/75">
                    Tanya ke {{ $whatsappNumber }}. Admin biasanya membalas di bawah 10 menit pada jam operasional,
                    09.00 &ndash; 21.00 WIB.
                </p>
            </div>

            <a
                href="{{ $whatsappUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex shrink-0 items-center gap-2 rounded-full bg-wa px-6 py-3.5 text-sm font-semibold text-white transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
            >
                Tanya stok lewat WhatsApp
            </a>
        </div>
    </section>
@endsection
