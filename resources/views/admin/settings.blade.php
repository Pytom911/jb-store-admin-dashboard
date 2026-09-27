@extends('layouts.admin')

@section('title', 'Settings')
@section('header', 'Settings')

@section('content')
    <div class="grid gap-5 lg:grid-cols-3">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5 lg:col-span-2">
            @csrf
            @method('PUT')

            <section class="rounded-xl bg-white p-5 ring-1 ring-slate-200 sm:p-6">
                <h2 class="mb-5 text-sm font-semibold text-slate-900">Profil Admin</h2>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.input
                        name="name"
                        label="Nama"
                        :value="$user->name"
                        required
                    />

                    <x-form.input
                        name="email"
                        label="Email"
                        type="email"
                        :value="$user->email"
                        required
                    />
                </div>
            </section>

            <section class="rounded-xl bg-white p-5 ring-1 ring-slate-200 sm:p-6">
                <h2 class="mb-1 text-sm font-semibold text-slate-900">Password Login</h2>
                <p class="mb-5 text-xs text-slate-500">Kosongkan kedua kolom ini kalau tidak ingin mengganti password.</p>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.password name="password" label="Password Baru" autocomplete="new-password" />
                    <x-form.password name="password_confirmation" label="Ulangi Password" autocomplete="new-password" />
                </div>
            </section>

            <div class="flex flex-wrap items-center gap-3">
                <x-button type="submit">Simpan Perubahan</x-button>
            </div>
        </form>

        <aside class="space-y-5">
            <section class="rounded-xl bg-white p-5 ring-1 ring-slate-200">
                <h2 class="mb-4 text-sm font-semibold text-slate-900">Informasi Sistem</h2>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs text-slate-500">Nomor WhatsApp Admin</dt>
                        <dd class="mt-0.5 font-mono text-slate-900">{{ $whatsappNumber }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-500">Environment</dt>
                        <dd class="mt-0.5 text-slate-900">{{ $environment }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-500">Role</dt>
                        <dd class="mt-0.5 text-slate-900">{{ ucfirst($user->role) }}</dd>
                    </div>
                </dl>

                <p class="mt-4 border-t border-slate-200 pt-4 text-xs text-slate-500">
                    Nomor WhatsApp diatur lewat variabel <code class="font-mono">WHATSAPP_ADMIN_NUMBER</code> di file
                    <code class="font-mono">.env</code>. Ubah di sana, lalu jalankan
                    <code class="font-mono">php artisan config:clear</code>.
                </p>
            </section>

            <section class="rounded-xl bg-white p-5 ring-1 ring-slate-200">
                <h2 class="mb-4 text-sm font-semibold text-slate-900">Tautan</h2>

                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    rel="noopener"
                    class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-50"
                >
                    Lihat Website Publik
                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full rounded-lg px-3 py-2 text-left text-sm text-red-600 transition hover:bg-red-50"
                    >
                        Keluar dari Akun
                    </button>
                </form>
            </section>
        </aside>
    </div>
@endsection
