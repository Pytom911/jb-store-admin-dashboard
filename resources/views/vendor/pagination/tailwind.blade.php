@if ($paginator->hasPages())
    @php
        $linkClasses = 'inline-flex h-10 min-w-10 items-center justify-center rounded-xl px-3 text-sm font-semibold tabular-nums transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2';
    @endphp

    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col items-center justify-between gap-4 sm:flex-row">
        @if ($paginator->total() > 0)
            <p class="text-sm text-fg-soft">
                Menampilkan
                <span class="font-semibold text-fg tabular-nums">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-semibold text-fg tabular-nums">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-semibold text-fg tabular-nums">{{ $paginator->total() }}</span>
                hasil
            </p>
        @endif

        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="{{ $linkClasses }} cursor-not-allowed text-fg-soft/50 ring-1 ring-inset ring-line" aria-disabled="true">
                    Sebelumnya
                    <span class="sr-only">(tidak ada halaman sebelumnya)</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $linkClasses }} text-fg ring-1 ring-inset ring-line hover:bg-brand-soft hover:text-brand">
                    Sebelumnya
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-10 min-w-10 items-center justify-center px-2 text-sm text-fg-soft/60">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="{{ $linkClasses }} bg-brand text-white">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="{{ $linkClasses }} text-fg ring-1 ring-inset ring-line hover:bg-brand-soft hover:text-brand">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $linkClasses }} text-fg ring-1 ring-inset ring-line hover:bg-brand-soft hover:text-brand">
                    Berikutnya
                </a>
            @else
                <span class="{{ $linkClasses }} cursor-not-allowed text-fg-soft/50 ring-1 ring-inset ring-line" aria-disabled="true">
                    Berikutnya
                    <span class="sr-only">(halaman terakhir)</span>
                </span>
            @endif
        </div>
    </nav>
@endif