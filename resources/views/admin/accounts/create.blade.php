@extends('layouts.admin')

@section('title', 'Tambah Akun')
@section('header', 'Tambah Akun')

@section('content')
    <form method="POST" action="{{ route('admin.accounts.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <section class="rounded-xl bg-white p-5 ring-1 ring-slate-200 sm:p-6">
            <h2 class="mb-5 text-sm font-semibold text-slate-900">Informasi Akun</h2>

            @include('admin.accounts._form')
        </section>

        <div class="flex flex-wrap items-center gap-3">
            <x-button type="submit">Simpan Akun</x-button>
            <x-button :href="route('admin.accounts.index')" variant="secondary">Batal</x-button>
        </div>
    </form>
@endsection
