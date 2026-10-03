@extends('layouts.app')

@section('title', 'Game')
@section('description', 'Daftar game yang tersedia di toko kami beserta jumlah stok akunnya.')

@section('content')
    <section class="pop-wash-cool border-b border-rule">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <p class="font-display text-xs font-bold tracking-[0.14em] text-pop-cyan uppercase">Etalase</p>

            <div class="mt-1 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div class="max-w-2xl">
                    <h1 class="font-display text-3xl font-bold tracking-tight text-balance text-ink sm:text-4xl">
                        Semua Game
                    </h1>

                    <p class="mt-3 text-base leading-relaxed text-pretty text-ink-soft">
                        Pilih game untuk melihat akun yang tersedia. Saat ini ada
                        <span class="font-semibold text-wa tabular-nums">{{ $totalAvailable }}</span> akun siap beli.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        @if ($games->isEmpty())
            <x-empty-state
                title="Belum ada game"
                description="Game yang diaktifkan admin akan tampil di sini."
            />
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($games as $game)
                    @include('public.partials.game-card', ['game' => $game])
                @endforeach
            </div>

            <div class="mt-12">
                {{ $games->links() }}
            </div>
        @endif
    </div>
@endsection