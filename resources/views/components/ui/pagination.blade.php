@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-4" aria-label="Pagination">

        {{-- Info --}}
        <p class="text-xs text-[var(--muted-foreground)]">
            Menampilkan
            <span class="font-medium text-[var(--foreground)]">{{ $paginator->firstItem() }}</span>
            &ndash;
            <span class="font-medium text-[var(--foreground)]">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-medium text-[var(--foreground)]">{{ $paginator->total() }}</span>
            data
        </p>

        {{-- Tombol navigasi --}}
        <div class="flex items-center gap-1">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md
                             border border-[var(--border)] text-[var(--muted-foreground)]/40
                             cursor-not-allowed select-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="inline-flex h-8 w-8 items-center justify-center rounded-md
                          border border-[var(--border)] text-[var(--muted-foreground)]
                          hover:bg-[var(--muted)] hover:text-[var(--foreground)]
                          transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-8 w-8 items-center justify-center
                                 text-xs text-[var(--muted-foreground)]">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-md
                                         bg-[var(--primary)] text-xs font-medium
                                         text-[var(--primary-foreground)]">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="inline-flex h-8 w-8 items-center justify-center rounded-md
                                      border border-[var(--border)] text-xs text-[var(--muted-foreground)]
                                      hover:bg-[var(--muted)] hover:text-[var(--foreground)]
                                      transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="inline-flex h-8 w-8 items-center justify-center rounded-md
                          border border-[var(--border)] text-[var(--muted-foreground)]
                          hover:bg-[var(--muted)] hover:text-[var(--foreground)]
                          transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            @else
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md
                             border border-[var(--border)] text-[var(--muted-foreground)]/40
                             cursor-not-allowed select-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </span>
            @endif

        </div>
    </nav>
@endif
