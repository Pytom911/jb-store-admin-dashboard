@php
    $onAccountsIndex = request()->routeIs('admin.accounts.index');
    $currentStatus = request()->query('status');

    $groups = [
        [
            'label' => null,
            'items' => [
                ['label' => 'Overview', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
            ],
        ],
        [
            'label' => 'Games',
            'items' => [
                ['label' => 'Semua Game', 'href' => route('admin.games.index'), 'active' => request()->routeIs('admin.games.index')],
                ['label' => 'Tambah Game', 'href' => route('admin.games.create'), 'active' => request()->routeIs('admin.games.create')],
            ],
        ],
        [
            'label' => 'Accounts',
            'items' => [
                ['label' => 'Semua Akun', 'href' => route('admin.accounts.index'), 'active' => $onAccountsIndex && ! $currentStatus],
                ['label' => 'Akun Tersedia', 'href' => route('admin.accounts.index', ['status' => 'available']), 'active' => $onAccountsIndex && $currentStatus === 'available'],
                ['label' => 'Akun Terjual', 'href' => route('admin.accounts.index', ['status' => 'sold']), 'active' => $onAccountsIndex && $currentStatus === 'sold'],
                ['label' => 'Tambah Akun', 'href' => route('admin.accounts.create'), 'active' => request()->routeIs('admin.accounts.create')],
            ],
        ],
        [
            'label' => null,
            'items' => [
                ['label' => 'Settings', 'href' => route('admin.settings.edit'), 'active' => request()->routeIs('admin.settings.*')],
            ],
        ],
    ];
@endphp

<nav class="flex flex-1 flex-col gap-6 overflow-y-auto px-4 py-5">
    <div class="flex items-center gap-2.5 px-2">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-500 text-sm font-bold text-white">
            {{ strtoupper(mb_substr(config('app.name'), 0, 1)) }}
        </span>

        <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-white">{{ config('app.name') }}</p>
            <p class="truncate text-xs text-slate-400">Panel Admin</p>
        </div>

        {{-- Only ever reachable inside the mobile drawer: the desktop aside is lg:flex, so this stays hidden there. --}}
        <button
            data-drawer-toggle
            type="button"
            aria-label="Tutup menu navigasi"
            class="ml-auto -mr-1 rounded-lg p-2 text-slate-400 transition hover:bg-slate-800 hover:text-white lg:hidden"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    @foreach ($groups as $group)
        <div>
            @if ($group['label'])
                <p class="px-3 pb-2 text-xs font-semibold tracking-wider text-slate-500 uppercase">{{ $group['label'] }}</p>
            @endif

            <ul class="space-y-1">
                @foreach ($group['items'] as $item)
                    <li>
                        <a
                            href="{{ $item['href'] }}"
                            @class([
                                'block rounded-lg px-3 py-2 text-sm transition',
                                'bg-indigo-500/15 text-white' => $item['active'],
                                'text-slate-300 hover:bg-slate-800 hover:text-white' => ! $item['active'],
                            ])
                            @if ($item['active'])
                                aria-current="page"
                            @endif
                        >
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</nav>
