<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>@yield('title', 'Dashboard') &middot; {{ config('app.name') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="h-full bg-slate-50 font-sans text-slate-900 antialiased">
        <div class="min-h-full lg:pl-64">
            <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col bg-slate-900 lg:flex">
                @include('layouts.partials.nav')
                @include('layouts.partials.sidebar-footer')
            </aside>

            <div id="admin-drawer" class="fixed inset-0 z-50 lg:hidden">
                <div
                    data-drawer-overlay
                    class="pointer-events-none absolute inset-0 bg-slate-900/60 opacity-0 transition-opacity duration-300"
                ></div>

                <aside
                    data-drawer-panel
                    class="absolute inset-y-0 left-0 flex w-72 -translate-x-full flex-col bg-slate-900 transition-transform duration-300"
                >
                    @include('layouts.partials.nav')
                    @include('layouts.partials.sidebar-footer')
                </aside>
            </div>

            <header
                class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8"
            >
                <button
                    data-drawer-toggle
                    type="button"
                    aria-label="Buka menu navigasi"
                    aria-expanded="false"
                    aria-controls="admin-drawer"
                    class="-ml-1 rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 lg:hidden"
                >
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <h1 class="truncate text-base font-semibold text-slate-900">@yield('header', 'Overview')</h1>
            </header>

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                <x-flash />

                @yield('content')
            </main>
        </div>

        <x-confirm-modal />

        @stack('scripts')
    </body>
</html>
