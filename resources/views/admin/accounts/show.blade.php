@extends('layouts.admin')

@section('title', $account->account_code)
@section('header', $account->account_code)

@section('content')
    <div class="space-y-5">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-lg font-semibold text-slate-900">{{ $account->title }}</h2>
                    <x-status-badge :status="$account->status" />
                </div>

                <p class="mt-1 text-sm text-slate-500">{{ $account->game->name }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <x-button :href="route('admin.accounts.edit', $account)">Edit</x-button>
                <x-button :href="route('admin.accounts.index')" variant="secondary">Kembali</x-button>
            </div>
        </div>

        <section class="rounded-xl bg-white ring-1 ring-slate-200">
            <header class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">
                    Galeri
                    <span class="ml-1 font-normal text-slate-400">{{ $account->images->count() }} gambar</span>
                </h3>

                <x-button :href="route('admin.accounts.edit', $account)" variant="secondary" class="px-3 py-1.5">
                    Tambah Gambar
                </x-button>
            </header>

            @if ($account->images->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-slate-500">
                    Belum ada gambar. Tambahkan screenshot detail akun supaya pembeli bisa melihat apa yang mereka dapat.
                </p>
            @else
                <ul class="grid gap-4 p-5 sm:grid-cols-3">
                    @foreach ($account->images as $image)
                        <li class="overflow-hidden rounded-lg ring-1 ring-slate-200">
                            <div class="relative bg-slate-50">
                                <img
                                    src="{{ $image->url }}"
                                    alt="Gambar detail {{ $account->account_code }}"
                                    class="aspect-video w-full object-cover"
                                />

                                @if ($image->is_cover)
                                    <span class="absolute top-2 left-2 rounded bg-indigo-600 px-2 py-0.5 text-xs font-semibold text-white">
                                        Sampul
                                    </span>
                                @endif
                            </div>

                            {{-- Each control owns its own form because HTML forbids nesting forms. --}}
                            <div class="flex items-center justify-between gap-1 border-t border-slate-200 p-2">
                                @if ($image->is_cover)
                                    <span class="px-2.5 py-1.5 text-xs text-slate-400">Dipakai di katalog</span>
                                @else
                                    <form method="POST" action="{{ route('admin.accounts.images.update', [$account, $image]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-button type="submit" variant="ghost" class="px-2.5 py-1.5">
                                            Jadikan Sampul
                                        </x-button>
                                    </form>
                                @endif

                                <x-button
                                    type="button"
                                    variant="ghost"
                                    class="px-2.5 py-1.5 text-red-600 hover:bg-red-50 hover:text-red-700"
                                    data-confirm-url="{{ route('admin.accounts.images.destroy', [$account, $image]) }}"
                                    data-confirm-message="Hapus gambar ini dari akun {{ $account->account_code }}?"
                                >
                                    Hapus
                                </x-button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section class="rounded-xl bg-white ring-1 ring-slate-200">
            <header class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Informasi</h3>
            </header>

            <dl class="grid gap-5 p-5 sm:grid-cols-3">
                <div>
                    <dt class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Kode Akun</dt>
                    <dd class="mt-1 font-medium text-sm text-slate-900">{{ $account->account_code }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Harga</dt>
                    <dd class="mt-1 font-medium text-sm text-slate-900">{{ $account->formattedPrice() }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Game</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $account->game->name }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Dibuat</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $account->created_at->format('d M Y H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Diperbarui</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ $account->updated_at->format('d M Y H:i') }}</dd>
                </div>
            </dl>

            @if ($account->description)
                <div class="border-t border-slate-200 px-5 py-4">
                    <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Deskripsi</p>
                    <p class="mt-1.5 text-sm whitespace-pre-line text-slate-700">{{ $account->description }}</p>
                </div>
            @endif
        </section>
    </div>
@endsection
