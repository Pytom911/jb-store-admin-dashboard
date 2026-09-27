<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>@yield('title', 'Masuk') &middot; {{ config('app.name') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="flex h-full items-center justify-center bg-slate-50 px-4 py-12 font-sans text-slate-900 antialiased">
        <main class="w-full max-w-sm">
            <div class="mb-8 text-center">
                <span
                    class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-lg font-bold text-white"
                >
                    {{ strtoupper(mb_substr(config('app.name'), 0, 1)) }}
                </span>

                <h1 class="mt-4 text-xl font-semibold text-slate-900">{{ config('app.name') }}</h1>
                <p class="mt-1 text-sm text-slate-500">Masuk ke panel admin</p>
            </div>

            <div class="rounded-xl bg-white p-6 ring-1 ring-slate-200">
                <x-flash />

                @yield('content')
            </div>
        </main>
    </body>
</html>
