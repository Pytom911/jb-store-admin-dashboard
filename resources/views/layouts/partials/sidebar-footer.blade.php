<div class="border-t border-slate-800 p-4">
    <p class="truncate px-2 text-sm font-medium text-white">{{ auth()->user()->name }}</p>
    <p class="truncate px-2 text-xs text-slate-400">{{ auth()->user()->email }}</p>

    <a
        href="{{ route('home') }}"
        target="_blank"
        rel="noopener"
        class="mt-4 flex items-center justify-between rounded-lg px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white"
    >
        Lihat Website
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
        </svg>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="mt-1">
        @csrf
        <button
            type="submit"
            class="flex w-full items-center rounded-lg px-3 py-2 text-sm text-slate-400 transition hover:bg-slate-800 hover:text-white"
        >
            Keluar
        </button>
    </form>
</div>
