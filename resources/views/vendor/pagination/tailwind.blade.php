@if ($paginator->hasPages())
    @php
        $linkClasses = 'inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-2.5 text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500';
    @endphp

    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-wrap items-center justify-between gap-3">
        @if ($paginator->total() > 0)
            <p class="text-sm text-slate-500">
                Menampilkan
                <span class="font-medium text-slate-700">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-medium text-slate-700">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-medium text-slate-700">{{ $paginator->total() }}</span>
                hasil
            </p>
        @endif

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="{{ $linkClasses }} cursor-not-allowed text-slate-400 ring-1 ring-inset ring-slate-200">
                    Sebelumnya
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    class="{{ $linkClasses }} text-slate-700 ring-1 ring-inset ring-slate-300 hover:bg-slate-50"
                >
                    Sebelumnya
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-9 min-w-9 items-center justify-center px-2 text-sm text-slate-400">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="{{ $linkClasses }} bg-indigo-600 text-white">
                                {{ $page }}
                            </span>
                        @else
                            <a
                                href="{{ $url }}"
                                class="{{ $linkClasses }} text-slate-700 ring-1 ring-inset ring-slate-300 hover:bg-slate-50"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    class="{{ $linkClasses }} text-slate-700 ring-1 ring-inset ring-slate-300 hover:bg-slate-50"
                >
                    Berikutnya
                </a>
            @else
                <span class="{{ $linkClasses }} cursor-not-allowed text-slate-400 ring-1 ring-inset ring-slate-200">
                    Berikutnya
                </span>
            @endif
        </div>
    </nav>
@endif
