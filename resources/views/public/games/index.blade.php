@extends('layouts.app')

@section('title', 'Game')
@section('description', 'Daftar game yang tersedia di toko kami beserta jumlah stok akunnya.')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Game</h1>
            <p class="mt-3 text-base text-slate-600">
                Pilih game untuk melihat akun yang tersedia. Saat ini ada
                <span class="font-semibold text-slate-900">{{ $totalAvailable }}</span> akun siap beli.
            </p>
        </div>

        @if ($games->isEmpty())
            <x-empty-state
                class="mt-8"
                title="Belum ada game"
                description="Game yang diaktifkan admin akan tampil di sini."
            />
        @else
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($games as $game)
                    @include('public.partials.game-card', ['game' => $game])
                @endforeach
            </div>

            <div class="mt-10">
                {{ $games->links() }}
            </div>
        @endif
    </div>
@endsection
