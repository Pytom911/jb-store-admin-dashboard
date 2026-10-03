@php
    $whatsappUrl = 'https://wa.me/'.config('marketplace.whatsapp_number');
    $whatsappNumber = '+'.config('marketplace.whatsapp_number');

    $steps = [
        ['tone' => 'bg-gradient-to-br from-accent-bright to-accent', 'title' => 'Pilih akun', 'body' => 'Pilih di katalog, lalu tekan Pesan.'],
        ['tone' => 'bg-gradient-to-br from-accent to-accent-deep', 'title' => 'Konfirmasi', 'body' => 'Kode akun terkirim ke admin lewat WhatsApp.'],
        ['tone' => 'bg-gradient-to-br from-accent-deep to-accent-deepest', 'title' => 'Transfer', 'body' => 'Data login masuk paling lama 10 menit.'],
    ];
@endphp

<footer class="border-t border-rule bg-ink text-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-3 rounded-xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white"
                >
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-accent-deep via-accent to-accent-bright font-display text-lg font-bold text-white"
                    >
                        {{ strtoupper(mb_substr(config('app.name'), 0, 1)) }}
                    </span>

                    <span class="font-display text-xl font-bold tracking-tight">{{ config('app.name') }}</span>
                </a>

                <p class="mt-5 max-w-sm text-base leading-relaxed text-white/75">
                    Toko akun game original dengan garansi 7 hari. Cek dulu etalasenya, konfirmasi lewat WhatsApp,
                    data login menyusul setelah pembayaran.
                </p>

                <a
                    href="{{ $whatsappUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-6 inline-flex items-center gap-2 rounded-full bg-wa px-5 py-2.5 text-sm font-semibold text-white transition-[filter] hover:brightness-110 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                >
                    {{ $whatsappNumber }}
                </a>
            </div>

            <nav aria-label="Halaman" class="lg:col-span-3">
                <h2 class="font-display text-sm font-bold tracking-tight">Halaman</h2>

                <ul class="mt-4 space-y-1">
                    @foreach ([
                        ['label' => 'Beranda', 'route' => 'home'],
                        ['label' => 'Semua game', 'route' => 'games.index'],
                        ['label' => 'Katalog akun', 'route' => 'accounts.index'],
                        ['label' => 'Harga termurah', 'route' => 'accounts.index', 'parameters' => ['sort' => 'price_asc']],
                    ] as $item)
                        <li>
                            <a
                                href="{{ route($item['route'], $item['parameters'] ?? []) }}"
                                @class([
                                    'inline-block rounded py-1.5 text-sm transition-colors',
                                    'text-white/75 hover:text-white' => ! request()->routeIs($item['route']),
                                    'font-bold text-white' => request()->routeIs($item['route']),
                                ])
                            >
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="lg:col-span-4">
                <h2 class="font-display text-sm font-bold tracking-tight">Cara Pesan</h2>

                <ol class="mt-4 space-y-3">
                    @foreach ($steps as $index => $step)
                        <li class="flex gap-3.5">
                            <span
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg font-display text-xs font-bold text-white {{ $step['tone'] }}"
                            >
                                {{ $index + 1 }}
                            </span>

                            <span class="min-w-0">
                                <span class="block text-sm font-semibold">{{ $step['title'] }}</span>
                                <span class="block text-sm text-white/70">{{ $step['body'] }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>

                <p class="mt-6 border-t border-white/15 pt-4 text-sm text-white/70">
                    Dilayani setiap hari, 09.00 &ndash; 21.00 WIB.
                </p>

                <a href="{{ route('login') }}" class="mt-2 inline-block rounded text-sm font-semibold text-white/75 transition-colors hover:text-white">
                    Masuk ke panel admin
                </a>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-3 border-t border-white/15 pt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-white/60">
                &copy; {{ now()->year }} {{ config('app.name') }}. Seluruh merek game milik pemiliknya masing-masing.
            </p>

            <p class="text-xs text-white/60">Garansi 7 hari &middot; Pembayaran setelah konfirmasi</p>
        </div>
    </div>
</footer>