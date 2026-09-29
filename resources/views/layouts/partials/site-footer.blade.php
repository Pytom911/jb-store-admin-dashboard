@php
    $whatsappUrl = 'https://wa.me/'.config('marketplace.whatsapp_number');
@endphp

<footer class="mt-16 border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-base font-bold text-white">
                        {{ strtoupper(mb_substr(config('app.name'), 0, 1)) }}
                    </span>

                    <span class="text-base font-semibold tracking-tight text-slate-900">{{ config('app.name') }}</span>
                </a>

                <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-500">
                    Toko akun game original dengan garansi penuh. Pilih akun, konfirmasi lewat WhatsApp, dan akun
                    langsung dikirim setelah pembayaran.
                </p>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">Halaman</h2>

                <ul class="mt-4 space-y-2.5 text-sm">
                    @foreach ([
                        ['label' => 'Beranda', 'route' => 'home'],
                        ['label' => 'Game', 'route' => 'games.index'],
                        ['label' => 'Katalog Akun', 'route' => 'accounts.index'],
                    ] as $item)
                        <li>
                            <a
                                href="{{ route($item['route']) }}"
                                class="text-slate-500 transition hover:text-indigo-600"
                            >
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-slate-900">Kontak</h2>

                <ul class="mt-4 space-y-2.5 text-sm text-slate-500">
                    <li>
                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="transition hover:text-emerald-600"
                        >
                            +{{ config('marketplace.whatsapp_number') }}
                        </a>
                    </li>
                    <li>Setiap hari, 09.00 &ndash; 21.00 WIB</li>
                    <li>
                        <a href="{{ route('login') }}" class="transition hover:text-indigo-600">Masuk ke Admin</a>
                    </li>
                </ul>
            </div>
        </div>

        <p class="mt-10 border-t border-slate-200 pt-6 text-xs text-slate-400">
            &copy; {{ now()->year }} {{ config('app.name') }}. Seluruh merek game milik pemiliknya masing-masing.
        </p>
    </div>
</footer>
