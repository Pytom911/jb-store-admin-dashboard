@extends('layouts.admin')

@section('title', 'Accounts')
@section('header', 'Accounts')

@section('content')
    <div class="space-y-4">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div class="w-full sm:w-56">
                    <x-form.input
                        name="search"
                        label="Cari"
                        :value="$search"
                        placeholder="Kode atau judul akun"
                    />
                </div>

                <div class="w-full sm:w-48">
                    <x-form.select
                        name="game_id"
                        label="Game"
                        :options="$games->pluck('name', 'id')->all()"
                        :value="$gameId"
                        placeholder="Semua game"
                    />
                </div>

                <div class="w-full sm:w-40">
                    <x-form.select
                        name="status"
                        label="Status"
                        :options="\App\Enums\AccountStatus::options()"
                        :value="$status"
                        placeholder="Semua status"
                    />
                </div>

                <div class="w-full sm:w-44">
                    <x-form.select
                        name="sort"
                        label="Urutkan"
                        :options="['price_asc' => 'Harga Termurah', 'price_desc' => 'Harga Termahal']"
                        :value="$sort"
                        placeholder="Terbaru"
                    />
                </div>

                <x-button type="submit" variant="secondary">Filter</x-button>

                @if ($search || $status || $gameId || $sort)
                    <a
                        href="{{ route('admin.accounts.index') }}"
                        class="pb-2 text-sm font-medium text-slate-500 transition hover:text-slate-800"
                    >
                        Reset
                    </a>
                @endif
            </form>

            <x-button :href="route('admin.accounts.create')">Tambah Akun</x-button>
        </div>

        <section class="overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
            @if ($accounts->isEmpty())
                <p class="px-5 py-12 text-center text-sm text-slate-500">
                    @if ($search || $status || $gameId || $sort)
                        Tidak ada akun yang cocok dengan filter ini.
                    @else
                        Belum ada akun.
                        <a href="{{ route('admin.accounts.create') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Tambahkan akun pertama
                        </a>.
                    @endif
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3">Kode</th>
                                <th scope="col" class="px-5 py-3">Gambar</th>
                                <th scope="col" class="px-5 py-3">Judul</th>
                                <th scope="col" class="px-5 py-3">Game</th>
                                <th scope="col" class="px-5 py-3 text-right">Harga</th>
                                <th scope="col" class="px-5 py-3">Status</th>
                                <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach ($accounts as $account)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-5 py-3 font-medium whitespace-nowrap text-slate-900">
                                        <a
                                            href="{{ route('admin.accounts.show', $account) }}"
                                            class="transition hover:text-indigo-600"
                                        >
                                            {{ $account->account_code }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($account->coverImage)
                                            <div class="relative w-fit">
                                                <img
                                                    src="{{ $account->coverImage->url }}"
                                                    alt="Sampul akun {{ $account->account_code }}"
                                                    class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200"
                                                />

                                                @if ($account->images_count > 1)
                                                    <span class="absolute -right-1.5 -bottom-1.5 rounded-full bg-slate-900 px-1.5 py-0.5 text-[10px] leading-none font-semibold text-white ring-2 ring-white">
                                                        +{{ $account->images_count - 1 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-400 ring-1 ring-slate-200">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Zm10.5-11.25h.008v.008h-.008V9.75Z"
                                                    />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-slate-600">{{ $account->title }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $account->game->name }}</td>
                                    <td class="px-5 py-3 text-right font-medium whitespace-nowrap text-slate-900">
                                        {{ $account->formattedPrice() }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <x-status-badge :status="$account->status" />
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center justify-end gap-1">
                                            <x-button
                                                :href="route('admin.accounts.edit', $account)"
                                                variant="ghost"
                                                class="px-2.5 py-1.5"
                                            >
                                                Edit
                                            </x-button>

                                            <x-button
                                                type="button"
                                                variant="ghost"
                                                class="px-2.5 py-1.5 text-red-600 hover:bg-red-50 hover:text-red-700"
                                                data-confirm-url="{{ route('admin.accounts.destroy', $account) }}"
                                                data-confirm-message="Hapus akun {{ $account->account_code }}? Data akun dan gambar-gambarnya ikut terhapus dan tidak bisa dikembalikan."
                                            >
                                                Hapus
                                            </x-button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{ $accounts->links() }}
    </div>
@endsection
