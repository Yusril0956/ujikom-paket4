@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex flex-col gap-4 border border-ink bg-surface p-4 sm:flex-row sm:items-center sm:justify-between"
        aria-label="Pagination">
        <span class="text-center text-xs font-mono text-muted sm:text-left">
            Menampilkan {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} dari
            {{ $paginator->total() }} data
        </span>
        <div class="flex w-full flex-wrap items-center justify-center gap-2 sm:w-auto sm:justify-end">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span
                    class="px-3 py-1.5 border border-ink text-xs font-mono text-muted rounded opacity-50 cursor-not-allowed"
                    aria-disabled="true">← Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1.5 border border-ink text-xs font-mono text-coffee hover:bg-ink/5 hover:text-ink transition-colors rounded">←
                    Prev</a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="min-w-9 px-3 py-1.5 border border-ink bg-ink text-center text-surface text-xs font-mono rounded"
                        aria-current="page">{{ $page }}</span>
                @elseif ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2)
                    <a href="{{ $url }}"
                        class="min-w-9 px-3 py-1.5 border border-ink text-center text-xs font-mono text-coffee hover:bg-ink/5 hover:text-ink transition-colors rounded">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1.5 border border-ink text-xs font-mono text-coffee hover:bg-ink/5 hover:text-ink transition-colors rounded">Next
                    →</a>
            @else
                <span
                    class="px-3 py-1.5 border border-ink text-xs font-mono text-muted rounded opacity-50 cursor-not-allowed"
                    aria-disabled="true">Next →</span>
            @endif
        </div>
    </nav>
@endif
