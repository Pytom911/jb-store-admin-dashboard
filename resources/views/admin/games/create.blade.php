@extends('layouts.admin')

@section('title', 'Tambah Game')
@section('header', 'Tambah Game')

@section('content')
    <form
        method="POST"
        action="{{ route('admin.games.store') }}"
        enctype="multipart/form-data"
        class="space-y-5"
    >
        @csrf

        <section class="rounded-xl bg-white p-5 ring-1 ring-slate-200 sm:p-6">
            <h2 class="mb-5 text-sm font-semibold text-slate-900">Detail Game</h2>

            @include('admin.games._form')
        </section>

        <div class="flex flex-wrap items-center gap-3">
            <x-button type="submit">Simpan Game</x-button>
            <x-button :href="route('admin.games.index')" variant="secondary">Batal</x-button>
        </div>
    </form>
@endsection
