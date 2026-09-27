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
            <header class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Kredensial</h3>
            </header>

            <dl class="grid gap-5 p-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Username</dt>
                    <dd class="mt-1 font-mono text-sm text-slate-900">{{ $account->username }}</dd>
                </div>

                <div>
                    <dt class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Password</dt>
                    <dd class="mt-1 flex items-center gap-2">
                        <input
                            id="account-password"
                            type="password"
                            readonly
                            value="{{ $account->password }}"
                            class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 font-mono text-sm text-slate-900"
                        />

                        <button
                            type="button"
                            data-reveal="account-password"
                            aria-label="Lihat password"
                            class="shrink-0 rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus-visible:text-slate-700"
                        >
                            <svg data-eye class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg data-eye-slash class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243" />
                            </svg>
                        </button>
                    </dd>
                </div>
            </dl>

            <p class="border-t border-slate-200 bg-amber-50 px-5 py-3 text-xs text-amber-800">
                Password tersimpan terenkripsi. Pastikan tidak dibagikan di channel publik.
            </p>
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
