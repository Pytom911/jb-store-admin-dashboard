@extends('layouts.app')

@section('title', 'Akun Game Original Siap Pakai')
@section('description', 'JajanAkun: akun game original dengan garansi 7 hari. Pilih akun, konfirmasi lewat WhatsApp, data login dikirim setelah pembayaran.')

@section('content')
    @php
        $whatsappUrl = 'https://wa.me/'.config('marketplace.whatsapp_number');
        $whatsappNumber = '+'.config('marketplace.whatsapp_number');
        $heroCovers = $games->filter(fn ($game) => $game->image_url)->take(4)->values();

        /*
         * The collage has to look deliberate for 1, 2, 3 and 4 covers. A single
         * spanning tile plus square tiles only tiles cleanly at 3 and 4, so the
         * thin cases get their own column count instead of leaving a hole.
         */
        $heroLayout = match ($heroCovers->count()) {
            0 => null,
            1 => ['cols' => 'grid-cols-1', 'heroSpan' => 'col-span-1 aspect-16/9', 'restSpan' => 'aspect-4/3'],
            2 => ['cols' => 'grid-cols-2', 'heroSpan' => 'col-span-1 aspect-4/3', 'restSpan' => 'aspect-4/3'],
            default => ['cols' => 'grid-cols-2', 'heroSpan' => 'col-span-2 aspect-16/9', 'restSpan' => 'aspect-square'],
        };
    @endphp

    {{-- ============================ HERO ============================ --}}
    <section class="pop-wash-cool border-b border-rule">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 sm:py-16 lg:grid-cols-12 lg:gap-14 lg:px-8 lg:py-20">
            <div class="lg:col-span-6 xl:col-span-7">
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
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-accent to-pop-magenta px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/25 transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
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
                        class="inline-flex items-center gap-2 rounded-full bg-surface px-6 py-3.5 text-sm font-semibold text-ink ring-1 ring-inset ring-accent/30 transition-colors hover:bg-accent-soft hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    >
                        Tanya stok
                    </a>
                </div>
            </div>

            {{-- Sampul game. Tetap collage, sekarang dengan stok sebagai pill warna. --}}
            <div class="lg:col-span-6 xl:col-span-5">
                @if ($heroLayout)
                    <div class="grid {{ $heroLayout['cols'] }} gap-3 sm:gap-4">
                        @foreach ($heroCovers as $index => $cover)
                            <a
                                href="{{ route('games.show', $cover) }}"
                                class="group relative block overflow-hidden rounded-2xl bg-ink/5 ring-1 ring-inset ring-ink/5 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent {{ $index === 0 ? $heroLayout['heroSpan'] : $heroLayout['restSpan'] }}"
                            >
                                <img
                                    src="{{ $cover->image_url }}"
                                    alt="{{ $cover->name }}"
                                    width="640"
                                    height="480"
                                    @if ($index === 0)
                                        fetchpriority="high"
                                    @else
                                        loading="lazy"
                                        decoding="async"
                                    @endif
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105 motion-reduce:transition-none motion-reduce:group-hover:scale-100"
                                />

                                <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink/90 via-ink/40 to-transparent px-3 pt-10 pb-3 text-left font-display text-sm font-semibold tracking-tight text-white">
                                    {{ $cover->name }}
                                </span>

                                @if (($cover->available_accounts_count ?? 0) > 0)
                                    <span class="absolute top-3 right-3 rounded-full bg-pop-lime px-2.5 py-1 text-xs font-semibold text-white tabular-nums shadow-sm">
                                        {{ $cover->available_accounts_count }} tersedia
                                    </span>
                                @else
                                    <span class="absolute top-3 right-3 rounded-full bg-pop-slate px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                                        Stok habis
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="flex aspect-16/9 flex-col justify-between rounded-2xl bg-gradient-to-br from-accent to-pop-magenta p-6 text-white">
                        <span class="font-display text-3xl font-bold">{{ config('app.name') }}</span>
                        <p class="text-sm text-white/85">
                            Cover game belum diunggah. Akun yang tersedia bisa langsung dicek di katalog.
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
                    ['accent' => 'pop-lime', 'title' => 'Garansi 7 Hari', 'body' => 'Ada yang tidak sesuai? Dikembalikan dalam seminggu.'],
                    ['accent' => 'pop-cyan', 'title' => 'Bayar Setelah Konfirmasi', 'body' => 'Cek dulu akunnya di sini, baru transfer.'],
                    ['accent' => 'pop-magenta', 'title' => 'Dikirim Setelah Transfer', 'body' => 'Data login masuk paling lama 10 menit.'],
                ] as $point)
                    <div class="rounded-2xl bg-surface-2 p-6 ring-1 ring-inset ring-rule">
                        <span @class([
                            'mb-4 flex h-11 w-11 items-center justify-center rounded-xl text-white',
                            'bg-pop-lime' => $point['accent'] === 'pop-lime',
                            'bg-pop-cyan' => $point['accent'] === 'pop-cyan',
                            'bg-pop-magenta' => $point['accent'] === 'pop-magenta',
                        ])>
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
                    'accent' => 'cyan',
                    'title' => 'Pilih Game',
                    'deck' => $games->count() >= 6
                        ? 'Enam game paling ramai etalasenya. Gulir ke samping untuk lihat yang lain.'
                        : 'Game yang tersedia di etalase kami.',
                    'linkLabel' => 'Semua game',
                    'linkHref' => route('games.index'),
                ])
            </div>

            {{-- Keyboard-scrollable: the region needs a tab stop and a name before
                 arrow keys reach it. --}}
            <div
                class="mt-8 overflow-x-auto overscroll-x-contain"
                tabindex="0"
                role="region"
                aria-label="Daftar game, gulir ke samping"
            >
                <div class="mx-auto flex max-w-7xl snap-x snap-mandatory gap-4 px-4 pb-2 sm:gap-5 sm:px-6 lg:px-8">
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
                'accent' => 'magenta',
                'title' => 'Stok Terbaru',
                'deck' => 'Akun yang baru masuk etalase, lengkap dengan harga dan statusnya.',
                'linkLabel' => 'Lihat katalog',
                'linkHref' => route('accounts.index'),
            ])

            @if ($latestAccounts->isEmpty())
                <x-empty-state
                    class="mt-8"
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
    <section class="pop-wash-warm border-b border-rule py-14 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('public.partials.section-head', [
                'eyebrow' => 'Paluran',
                'accent' => 'tangerine',
                'title' => 'Harga Termurah',
                'deck' => 'Akun yang masih tersedia, diurutkan dari yang paling murah.',
                'linkLabel' => 'Urutkan semua',
                'linkHref' => route('accounts.index', ['sort' => 'price_asc']),
            ])

            @if ($availableAccounts->isEmpty())
                <x-empty-state
                    class="mt-8"
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
                                class="hidden w-10 shrink-0 self-center rounded-xl bg-gradient-to-br from-accent to-pop-magenta py-1.5 text-center font-display text-sm font-bold text-white tabular-nums sm:block"
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
                'accent' => 'lime',
                'title' => 'Cara Beli',
                'deck' => 'Tiga langkah, tanpa daftar akun dan tanpa form yang panjang.',
            ])

            <ol class="mt-10 grid gap-6 sm:grid-cols-3 lg:gap-8">
                @foreach ([
                    ['title' => 'Pilih akun', 'body' => 'Cek status, harga, dan fotonya langsung di katalog.'],
                    ['title' => 'Konfirmasi', 'body' => 'Tekan Pesan, kode akun ikut terkirim ke admin.'],
                    ['title' => 'Transfer', 'body' => 'Data login menyusul, masuk dalam 10 menit.'],
                ] as $index => $step)
                    <li class="relative rounded-2xl bg-surface p-6 ring-1 ring-inset ring-rule">
                        <span @class([
                            'flex h-12 w-12 items-center justify-center rounded-2xl font-display text-lg font-bold text-white',
                            'bg-accent' => $index === 0,
                            'bg-pop-cyan' => $index === 1,
                            'bg-pop-lime' => $index === 2,
                        ])>
                            {{ $index + 1 }}
                        </span>

                        <h3 class="mt-5 font-display text-lg font-bold tracking-tight text-ink">{{ $step['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-ink-soft">{{ $step['body'] }}</p>
                    </li>
                @endforeach
            </ol>
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