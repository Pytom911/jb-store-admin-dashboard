@extends('layouts.admin')

@section('title', 'Games')
@section('header', 'Games')

@section('content')
    <div class="space-y-4">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div class="w-full sm:w-64">
                    <x-form.input
                        name="search"
                        label="Cari"
                        :value="$search"
                        placeholder="Nama atau slug game"
                    />
                </div>

                <div class="w-full sm:w-44">
                    <x-form.select
                        name="status"
                        label="Status"
                        :options="\App\Enums\GameStatus::options()"
                        :value="$status"
                        placeholder="Semua status"
                    />
                </div>

                <x-button type="submit" variant="secondary">Filter</x-button>

                @if ($search || $status)
                    <a
                        href="{{ route('admin.games.index') }}"
                        class="pb-2 text-sm font-medium text-slate-500 transition hover:text-slate-800"
                    >
                        Reset
                    </a>
                @endif
            </form>

            <x-button :href="route('admin.games.create')">Tambah Game</x-button>
        </div>

        <section class="overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
            @if ($games->isEmpty())
                <p class="px-5 py-12 text-center text-sm text-slate-500">
                    @if ($search || $status)
                        Tidak ada game yang cocok dengan filter ini.
                    @else
                        Belum ada game.
                        <a href="{{ route('admin.games.create') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Tambahkan game pertama
                        </a>.
                    @endif
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3">Game</th>
                                <th scope="col" class="px-5 py-3">Slug</th>
                                <th scope="col" class="px-5 py-3 text-center">Akun</th>
                                <th scope="col" class="px-5 py-3 text-center">Tersedia</th>
                                <th scope="col" class="px-5 py-3">Status</th>
                                <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @foreach ($games as $game)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            @if ($game->image_url)
                                                <img
                                                    src="{{ $game->image_url }}"
                                                    alt=""
                                                    class="h-10 w-10 shrink-0 rounded-lg object-cover ring-1 ring-slate-200"
                                                />
                                            @else
                                                <span
                                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400 ring-1 ring-slate-200"
                                                >
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 18h-.008v-.008h.008v.008ZM9.75 18h-.008v-.008h.008v.008ZM7.5 15h.008v.008H7.5V15Zm0-3h.008v.008H7.5V12Zm3 0h.008v.008h-.008V12Zm0-3h.008v.008h-.008V9Zm3 0h.008v.008h-.008V9Zm3 3h.008v.008h-.008V12Zm0 3h.008v.008h-.008V15Zm1.125-6.75H4.875a1.125 1.125 0 0 0-1.125 1.125v7.5c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125v-4.875c0-.621-.504-1.125-1.125-1.125H18.75M16.5 6.75h.008v.008h-.008V6.75Zm0 0H7.5c0-2.208 1.792-4 4-4h1a4 4 0 0 1 4 4v6a1.125 1.125 0 0 1-1.125 1.125H6.75A1.125 1.125 0 0 1 5.625 12.75v-1.5c0-1.036.84-1.875 1.875-1.875H7.5m8.25 3 1.5-1.5m0 0-1.5-1.5m1.5 1.5-1.5 1.5" />
                                                    </svg>
                                                </span>
                                            @endif

                                            <div class="min-w-0">
                                                <p class="font-medium text-slate-900">{{ $game->name }}</p>
                                                @if ($game->description)
                                                    <p class="truncate text-xs text-slate-500">{{ $game->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $game->slug }}</td>
                                    <td class="px-5 py-3 text-center text-slate-600">{{ $game->accounts_count }}</td>
                                    <td class="px-5 py-3 text-center font-medium text-slate-900">
                                        {{ $game->available_accounts_count }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <x-status-badge :status="$game->status" />
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center justify-end gap-1">
                                            <x-button
                                                :href="route('admin.games.edit', $game)"
                                                variant="ghost"
                                                class="px-2.5 py-1.5"
                                            >
                                                Edit
                                            </x-button>

                                            <x-button
                                                type="button"
                                                variant="ghost"
                                                class="px-2.5 py-1.5 text-red-600 hover:bg-red-50 hover:text-red-700"
                                                data-confirm-url="{{ route('admin.games.destroy', $game) }}"
                                                data-confirm-message="Hapus game &quot;{{ $game->name }}&quot;? Tindakan ini tidak bisa dibatalkan."
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

        {{ $games->links() }}
    </div>
@endsection
