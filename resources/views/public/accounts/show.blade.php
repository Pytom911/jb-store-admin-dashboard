@extends('layouts.app')

@section('title', $account->title)
@section('description', "Detail akun {$account->account_code}: {$account->formattedPrice()}.")

@section('content')
    @php
        $whatsappUrl = 'https://wa.me/'.config('marketplace.whatsapp_number');
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-soft">
            <a href="{{ route('accounts.index') }}" class="rounded transition-colors hover:text-accent">Katalog akun</a>
            <span class="mx-1.5 text-ink/30" aria-hidden="true">/</span>
            <span class="font-medium text-ink">{{ $account->account_code }}</span>
        </nav>

        <div class="mt-7 grid gap-10 lg:grid-cols-12 lg:gap-12">
            <div class="lg:col-span-7 xl:col-span-8">
                <div class="flex flex-wrap items-center gap-3">
                    <a
                        href="{{ route('games.show', $account->game) }}"
                        class="rounded-full bg-accent-soft px-3.5 py-1.5 text-xs font-semibold text-accent transition-colors hover:bg-accent hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    >
                        {{ $account->game->name }}
                    </a>

                    <x-status-badge :status="$account->status" />
                </div>

                <h1 class="mt-5 font-display text-3xl leading-tight font-bold tracking-tight text-balance text-ink sm:text-4xl">
                    {{ $account->title }}
                </h1>

                <p class="mt-2 font-mono text-sm text-ink-soft">{{ $account->account_code }}</p>

                <p class="mt-6 font-display text-3xl font-bold tracking-tight tabular-nums text-ink sm:text-4xl">
                    {{ $account->formattedPrice() }}
                </p>

                @if ($account->images->isNotEmpty())
                    <section class="mt-10 border-t border-rule pt-8">
                        <h2 class="font-display text-lg font-bold tracking-tight text-ink">
                            Detail Akun

                            <span class="ml-1 text-sm font-semibold text-ink-soft tabular-nums">
                                {{ $account->images->count() }} foto
                            </span>
                        </h2>

                        <ul class="mt-5 grid gap-4 sm:grid-cols-2">
                            @foreach ($account->images as $image)
                                <li>
                                    <img
                                        src="{{ $image->url }}"
                                        alt="Detail akun {{ $account->account_code }} &mdash; foto {{ $loop->iteration }}"
                                        width="640"
                                        height="480"
                                        class="aspect-4/3 w-full rounded-2xl object-cover ring-1 ring-inset ring-ink/5"
                                        loading="lazy"
                                        decoding="async"
                                    />
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($account->description)
                    <section class="mt-10 border-t border-rule pt-8">
                        <h2 class="font-display text-lg font-bold tracking-tight text-ink">Keterangan</h2>
                        <p class="mt-3 text-base leading-relaxed break-words whitespace-pre-line text-ink-soft">
                            {{ $account->description }}
                        </p>
                    </section>
                @endif
            </div>

            {{-- top-28 clears the sticky header on lg, and matches the
                 scroll-padding-top of 7.5rem set in app.css. --}}
            <div class="lg:col-span-5 xl:col-span-4">
                <div class="lg:sticky lg:top-28">
                    @if ($account->isAvailable())
                        <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-accent to-pop-magenta p-6 text-white">
                            <h2 class="font-display text-lg font-bold tracking-tight">Beli Akun Ini</h2>

                            <p class="mt-3 text-sm leading-relaxed text-white/85">
                                Sampaikan kode akun <span class="font-mono font-semibold">{{ $account->account_code }}</span>
                                agar prosesnya cepat. Data login dikirim setelah pembayaran, dengan garansi 7 hari.
                            </p>

                            <a
                                href="{{ $account->whatsappUrl() }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-6 flex items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-bold text-accent transition-colors hover:bg-white/90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                            >
                                Pesan via WhatsApp
                            </a>

                            <p class="mt-4 border-t border-white/25 pt-4 text-xs text-white/80">
                                Balasan biasanya kurang dari 10 menit, 09.00 &ndash; 21.00 WIB.
                            </p>
                        </div>
                    @else
                        <div class="rounded-2xl bg-surface p-6 ring-1 ring-inset ring-rule">
                            <x-status-badge :status="$account->status" />

                            <h2 class="mt-4 font-display text-lg font-bold tracking-tight text-balance text-ink">
                                Akun {{ strtolower($account->status->label()) }}
                            </h2>

                            <p class="mt-3 text-sm leading-relaxed text-ink-soft">
                                Akun ini sudah {{ strtolower($account->status->label()) }}. Cari akun lain untuk game yang
                                sama, atau tanya stok terbaru lewat WhatsApp.
                            </p>

                            <a
                                href="{{ $whatsappUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-6 flex items-center justify-center gap-2 rounded-full bg-ink px-5 py-3 text-sm font-semibold text-white transition-[filter] hover:brightness-125 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                            >
                                Tanya stok lain
                            </a>
                        </div>
                    @endif

                    <a
                        href="{{ route('games.show', $account->game) }}"
                        class="mt-4 flex items-center justify-between gap-3 rounded-2xl bg-surface px-5 py-4 text-sm font-semibold text-ink ring-1 ring-inset ring-rule transition-shadow hover:shadow-md hover:shadow-accent/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    >
                        <span class="min-w-0 truncate">Semua akun {{ $account->game->name }}</span>

                        <svg class="h-4 w-4 shrink-0 text-ink-soft" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection