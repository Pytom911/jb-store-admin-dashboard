@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Overview')

@section('content')
    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card
                label="Total Game"
                :value="$totalGames"
                :href="route('admin.games.index')"
                tone="indigo"
                icon="gamepad"
            />

            <x-stat-card
                label="Total Akun"
                :value="$totalAccounts"
                :href="route('admin.accounts.index')"
                tone="slate"
                icon="stack"
            />

            <x-stat-card
                label="Akun Tersedia"
                :value="$availableAccounts"
                :href="route('admin.accounts.index', ['status' => 'available'])"
                tone="emerald"
                icon="check"
            />

            <x-stat-card
                label="Akun Terjual"
                :value="$soldAccounts"
                :href="route('admin.accounts.index', ['status' => 'sold'])"
                tone="amber"
                icon="cart"
            />
        </div>

        <section class="overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
            <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">Akun Terbaru</h2>

                <a
                    href="{{ route('admin.accounts.index') }}"
                    class="text-sm font-medium text-indigo-600 transition hover:text-indigo-500"
                >
                    Lihat semua
                </a>
            </header>

            @if ($recentAccounts->isEmpty())
                <p class="px-5 py-12 text-center text-sm text-slate-500">
                    Belum ada akun. Mulai dengan
                    <a href="{{ route('admin.accounts.create') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        menambahkan akun pertama
                    </a>.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3">Kode</th>
                                <th scope="col" class="px-5 py-3">Judul</th>
                                <th scope="col" class="px-5 py-3">Game</th>
                                <th scope="col" class="px-5 py-3 text-right">Harga</th>
                                <th scope="col" class="px-5 py-3">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentAccounts as $account)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-5 py-3 font-medium whitespace-nowrap text-slate-900">
                                        <a
                                            href="{{ route('admin.accounts.show', $account) }}"
                                            class="transition hover:text-indigo-600"
                                        >
                                            {{ $account->account_code }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3 text-slate-600">{{ $account->title }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $account->game->name }}</td>
                                    <td class="px-5 py-3 text-right font-medium whitespace-nowrap text-slate-900">
                                        {{ $account->formattedPrice() }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <x-status-badge :status="$account->status" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
