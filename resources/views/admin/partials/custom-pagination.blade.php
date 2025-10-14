@if ($paginator->hasPages())
    <div class="custom-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-btn pagination-arrow prev disabled">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn pagination-arrow prev">Previous</a>
        @endif

        {{-- Page Numbers --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $showPages = 5; // Show 5 pages around current
            $halfShow = floor($showPages / 2);

            // Calculate start and end page
            $start = max(1, $currentPage - $halfShow);
            $end = min($lastPage, $currentPage + $halfShow);

            // Adjust if we're near the beginning or end
            if ($end - $start + 1 < $showPages) {
                if ($start == 1) {
                    $end = min($lastPage, $start + $showPages - 1);
                } else {
                    $start = max(1, $end - $showPages + 1);
                }
            }
        @endphp

        {{-- First page if not visible --}}
        @if ($start > 1)
            <a href="{{ $paginator->url(1) }}" class="pagination-btn">1</a>
            @if ($start > 2)
                <span class="pagination-ellipsis">...</span>
            @endif
        @endif

        {{-- Page number links --}}
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $currentPage)
                <span class="pagination-btn active">{{ $i }}</span>
            @else
                <a href="{{ $paginator->url($i) }}" class="pagination-btn">{{ $i }}</a>
            @endif
        @endfor

        {{-- Last page if not visible --}}
        @if ($end < $lastPage)
            @if ($end < $lastPage - 1)
                <span class="pagination-ellipsis">...</span>
            @endif
            <a href="{{ $paginator->url($lastPage) }}" class="pagination-btn">{{ $lastPage }}</a>
        @endif

        {{-- Pagination Info --}}
        <div class="pagination-info">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn pagination-arrow next">Next</a>
        @else
            <span class="pagination-btn pagination-arrow next disabled">Next</span>
        @endif
    </div>
@endif
