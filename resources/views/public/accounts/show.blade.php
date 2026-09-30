@extends('layouts.app')

@section('title', $account->title)
@section('description', "Detail akun {$account->account_code}: {$account->formattedPrice()}.")

@section('content')
    @php
        $whatsappUrl = 'https://wa.me/'.config('marketplace.whatsapp_number');
    @endphp

    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="text-sm">
            <a href="{{ route('accounts.index') }}" class="text-slate-500 transition hover:text-slate-900">Katalog akun</a>
            <span class="mx-1.5 text-slate-300">/</span>
            <span class="font-medium text-slate-900">{{ $account->account_code }}</span>
        </nav>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="rounded-xl bg-white p-6 ring-1 ring-slate-200 sm:p-8">
                    <div class="flex flex-wrap items-center gap-3">
                        <a
                            href="{{ route('games.show', $account->game) }}"
                            class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800"
                        >
                            {{ $account->game->name }}
                        </a>

                        <x-status-badge :status="$account->status" />
                    </div>

                    <h1 class="mt-4 text-2xl font-semibold tracking-tight text-slate-900">{{ $account->title }}</h1>

                    <p class="mt-2 font-mono text-sm text-slate-400">{{ $account->account_code }}</p>

                    <p class="mt-6 text-3xl font-semibold tracking-tight text-slate-900">
                        {{ $account->formattedPrice() }}
                    </p>

                    @if ($account->images->isNotEmpty())
                        <div class="mt-6 border-t border-slate-200 pt-6">
                            <h2 class="text-sm font-semibold text-slate-900">
                                Detail Akun
                                <span class="ml-1 font-normal text-slate-400">{{ $account->images->count() }} foto</span>
                            </h2>

                            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                                @foreach ($account->images as $image)
                                    <li>
                                        <img
                                            src="{{ $image->url }}"
                                            alt="Detail akun {{ $account->account_code }} — foto {{ $loop->iteration }}"
                                            class="w-full rounded-lg object-cover ring-1 ring-slate-200"
                                            loading="lazy"
                                        />
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($account->description)
                        <div class="mt-6 border-t border-slate-200 pt-6">
                            <h2 class="text-sm font-semibold text-slate-900">Keterangan</h2>
                            <p class="mt-2 text-sm leading-relaxed whitespace-pre-line text-slate-600">{{ $account->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24">
                    @if ($account->isAvailable())
                        <div class="rounded-xl bg-white p-6 ring-1 ring-slate-200">
                            <h2 class="text-base font-semibold text-slate-900">Beli akun ini</h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                                Sampaikan kode akun <span class="font-mono text-slate-700">{{ $account->account_code }}</span>
                                agar prosesnya cepat. Data login dikirim setelah pembayaran, dengan garansi 7 hari.
                            </p>

                            <a
                                href="{{ $account->whatsappUrl() }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-5 flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
                            >
                                Pesan via WhatsApp
                            </a>

                            <p class="mt-3 text-xs text-slate-400">
                                Balasan biasanya kurang dari 10 menit pada jam operasional.
                            </p>
                        </div>
                    @else
                        <div class="rounded-xl bg-white p-6 ring-1 ring-slate-200">
                            <h2 class="text-base font-semibold text-slate-900">Akun {{ strtolower($account->status->label()) }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                                Akun ini sudah {{ strtolower($account->status->label()) }}. Cari akun lain untuk game yang
                                sama, atau tanya stok terbaru lewat WhatsApp.
                            </p>

                            <a
                                href="{{ $whatsappUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-5 flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2"
                            >
                                Tanya stok lain
                            </a>
                        </div>
                    @endif

                    <a
                        href="{{ route('games.show', $account->game) }}"
                        class="mt-4 flex items-center justify-between rounded-xl bg-white p-4 text-sm font-medium text-slate-700 ring-1 ring-slate-200 transition hover:ring-slate-300"
                    >
                        Semua akun {{ $account->game->name }}

                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
