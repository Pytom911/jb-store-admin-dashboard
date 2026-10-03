<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="theme-color" content="#f4f3ff" />

        <title>@yield('title', config('app.name')) &middot; {{ config('app.name') }}</title>
        <meta
            name="description"
            content="@yield('description', 'Jual beli akun game original, garansi penuh, dan pengiriman cepat lewat WhatsApp.')"
        />

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    @php
        $navigation = [
            ['label' => 'Beranda', 'route' => 'home', 'active' => 'home'],
            ['label' => 'Game', 'route' => 'games.index', 'active' => 'games*'],
            ['label' => 'Katalog Akun', 'route' => 'accounts.index', 'active' => 'accounts*'],
        ];

        $whatsappUrl = 'https://wa.me/'.config('marketplace.whatsapp_number');
        $whatsappNumber = '+'.config('marketplace.whatsapp_number');
    @endphp

    <body class="flex min-h-full flex-col font-sans text-ink antialiased">
        <a
            href="#konten"
            class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-50 focus:rounded-xl focus:bg-accent focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white"
        >
            Lewati ke konten
        </a>

        <header class="sticky top-0 z-40 border-b border-rule bg-paper/85 backdrop-blur-md">
            {{-- Gradient hairline: the one saturated edge on the chrome, so the
                 header reads as part of the palette rather than a grey bar. --}}
            <div class="h-1 bg-gradient-to-r from-accent-deepest via-accent to-accent-bright" aria-hidden="true"></div>

            <div class="mx-auto flex h-16 max-w-7xl items-center gap-4 px-4 pt-[env(safe-area-inset-top)] sm:px-6 lg:h-20 lg:px-8">
                <a
                    href="{{ route('home') }}"
                    class="-ml-1 flex shrink-0 items-center gap-2.5 rounded-xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-accent"
                >
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-accent-deep via-accent to-accent-bright font-display text-base font-bold text-white"
                    >
                        {{ strtoupper(mb_substr(config('app.name'), 0, 1)) }}
                    </span>

                    <span class="font-display text-base font-bold tracking-tight text-ink">
                        {{ config('app.name') }}
                    </span>
                </a>

                <nav aria-label="Navigasi utama" class="ml-4 hidden items-center gap-1.5 lg:flex">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @if (request()->routeIs($item['active'])) aria-current="page" @endif
                            @class([
                                'rounded-full px-3.5 py-2 text-sm font-semibold transition-colors',
                                'bg-accent text-white' => request()->routeIs($item['active']),
                                'text-ink-soft hover:bg-accent-soft hover:text-accent' => ! request()->routeIs($item['active']),
                            ])
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="ml-auto flex items-center gap-2 sm:gap-3">
                    <a
                        href="{{ route('login') }}
                        class="hidden rounded-full px-3 py-2 text-sm font-semibold text-ink-soft transition-colors hover:bg-accent-soft hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent sm:block"
                    >
                        Masuk
                    </a>

                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Chat {{ $whatsappNumber }} lewat WhatsApp"
                        class="inline-flex items-center gap-2 rounded-full bg-wa px-3.5 py-2 text-sm font-semibold text-white transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    >
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.875 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25a8.25 8.25 0 0 1-12.28 7.16L3 21l2.59-5.72A8.25 8.25 0 1 1 21 11.25Z" />
                        </svg>

                        <span class="hidden sm:inline">Chat WhatsApp</span>
                    </a>

                    <button
                        data-drawer-toggle
                        type="button"
                        aria-label="Buka menu navigasi"
                        aria-expanded="false"
                        aria-controls="site-drawer"
                        class="-mr-2 rounded-xl p-2 text-ink transition-colors hover:bg-accent-soft hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent lg:hidden"
                    >
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        {{--
            The root spans the whole viewport, so while it is closed it must stop
            hit testing entirely, otherwise the invisible wrapper swallows every tap
            on the page underneath on small screens. `inert` carries that state (and
            keeps the closed panel out of the tab order) while pointer-events-none
            stays as the no-JS/inertless fallback. The panel slides behind the right
            edge instead of being hidden, so the close animation still plays.
        --}}
        <div
            id="site-drawer"
            data-drawer-root
            inert
            class="pointer-events-none fixed inset-0 z-50 lg:hidden data-[open=true]:pointer-events-auto"
        >
            <div
                data-drawer-overlay
                class="absolute inset-0 bg-ink/60 opacity-0 backdrop-blur-sm transition-opacity duration-300"
            ></div>

            <div
                data-drawer-panel
                data-drawer-closed-class="translate-x-full"
                tabindex="-1"
                class="absolute inset-y-0 right-0 flex w-[19rem] max-w-[85vw] translate-x-full flex-col overscroll-contain overflow-y-auto bg-paper shadow-2xl transition-transform duration-300 ease-out data-[open=true]:translate-x-0"
            >
                <div class="flex items-center justify-between border-b border-rule px-5 py-4 pt-[calc(env(safe-area-inset-top)+1rem)]">
                    <span class="font-display text-sm font-bold tracking-tight text-ink">Menu</span>

                    <button
                        data-drawer-toggle
                        type="button"
                        aria-label="Tutup menu navigasi"
                        class="-mr-2 rounded-xl p-2 text-ink-soft transition-colors hover:bg-accent-soft hover:text-accent focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav aria-label="Navigasi seluler" class="px-5 py-2">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @if (request()->routeIs($item['active'])) aria-current="page" @endif
                            @class([
                                '-mx-2 flex items-center justify-between rounded-xl px-3 py-3 font-display text-lg font-semibold tracking-tight transition-colors',
                                'bg-accent-soft text-accent' => request()->routeIs($item['active']),
                                'text-ink-soft hover:bg-accent-soft hover:text-accent' => ! request()->routeIs($item['active']),
                            ])
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="mt-2 border-t border-rule px-5 py-5">
                    <p class="text-sm leading-relaxed text-ink-soft">
                        Stok yang tidak ada di etalase bisa dicek langsung. Balasan biasanya di bawah 10 menit.
                    </p>

                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-4 flex items-center justify-center gap-2.5 rounded-xl bg-wa px-4 py-3 text-sm font-semibold text-white transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    >
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.875 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25a8.25 8.25 0 0 1-12.28 7.16L3 21l2.59-5.72A8.25 8.25 0 1 1 21 11.25Z" />
                        </svg>

                        Tanya stok
                    </a>

                    <dl class="mt-5 space-y-2 border-t border-rule pt-4 text-sm">
                        <div class="flex items-baseline justify-between gap-3">
                            <dt class="text-ink-soft">Nomor</dt>
                            <dd>
                                <a class="font-medium text-ink transition-colors hover:text-accent" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">{{ $whatsappNumber }}</a>
                            </dd>
                        </div>

                        <div class="flex items-baseline justify-between gap-3">
                            <dt class="text-ink-soft">Jam layanan</dt>
                            <dd class="text-right font-medium text-ink">09.00 &ndash; 21.00 WIB</dd>
                        </div>
                    </dl>
                </div>

                <a
                    href="{{ route('login') }}"
                    class="mt-auto border-t border-rule px-5 py-4 pb-[calc(env(safe-area-inset-bottom)+1rem)] text-sm font-semibold text-ink-soft transition-colors hover:bg-accent-soft hover:text-accent"
                >
                    Masuk ke panel admin
                </a>
            </div>
        </div>

        {{-- tabindex lets the alert dismiss handler hand focus back here after it
             removes a notice, instead of dropping the user at the document top. --}}
        <main id="konten" tabindex="-1" class="flex-1 focus:outline-none">
            @yield('content')
        </main>

        @include('layouts.partials.site-footer')

        @stack('scripts')
    </body>
</html>