<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

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
    @endphp

    <body class="flex min-h-full flex-col bg-slate-50 font-sans text-slate-900 antialiased">
        <a
            href="#konten"
            class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:ring-2 focus:ring-emerald-600"
        >
            Lewati ke konten
        </a>

        <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center gap-3 px-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900 text-base font-bold text-white">
                        {{ strtoupper(mb_substr(config('app.name'), 0, 1)) }}
                    </span>

                    <span class="text-base font-semibold tracking-tight text-slate-900">{{ config('app.name') }}</span>
                </a>

                <nav aria-label="Navigasi utama" class="ml-4 hidden items-center gap-1 lg:flex">
                    @foreach ($navigation as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @if (request()->routeIs($item['active'])) aria-current="page" @endif
                                class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs($item['active']) ? 'bg-slate-900 text-white hover:bg-slate-900' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="ml-auto flex items-center gap-2">
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="hidden items-center gap-2 rounded-lg bg-emerald-600 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2 sm:inline-flex"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.875 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25a8.25 8.25 0 0 1-12.28 7.16L3 21l2.59-5.72A8.25 8.25 0 1 1 21 11.25Z" />
                        </svg>

                        WhatsApp
                    </a>

                    <a
                        href="{{ route('login') }}"
                        class="hidden text-sm font-semibold text-slate-600 transition hover:text-slate-900 sm:block"
                    >
                        Masuk
                    </a>

                    <button
                        data-drawer-toggle
                        type="button"
                        aria-label="Buka menu navigasi"
                        aria-expanded="false"
                        aria-controls="site-drawer"
                        class="-mr-1 rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 lg:hidden"
                    >
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <div id="site-drawer" class="fixed inset-0 z-50 lg:hidden">
            <div
                data-drawer-overlay
                class="pointer-events-none absolute inset-0 bg-slate-900/60 opacity-0 transition-opacity duration-300"
            ></div>

            <div
                data-drawer-panel
                data-drawer-closed-class="-translate-y-full"
                class="absolute inset-x-0 top-0 -translate-y-full p-4 transition-transform duration-300"
            >
                <div class="rounded-xl bg-white p-4 shadow-xl ring-1 ring-slate-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-900">Menu</span>

                        <button
                            data-drawer-toggle
                            type="button"
                            aria-label="Tutup menu navigasi"
                            class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <nav aria-label="Navigasi seluler" class="mt-3 grid gap-1">
                        @foreach ($navigation as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                @if (request()->routeIs($item['active'])) aria-current="page" @endif
                                class="rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($item['active']) ? 'bg-slate-900 text-white hover:bg-slate-900' : 'text-slate-700 hover:bg-slate-100' }}"
                            >
                                {{ $item['label'] }}
                            </a>
                        @endforeach

                        <a
                            href="{{ route('login') }}"
                            class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                        >
                            Masuk ke Admin
                        </a>
                    </nav>

                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-3 flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-3.5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-500"
                    >
                        Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <main id="konten" class="flex-1">
            @yield('content')
        </main>

        @include('layouts.partials.site-footer')

        @stack('scripts')
    </body>
</html>
