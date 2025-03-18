@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="pagination-style-3">
        <div class="d-inline-block">
            <p class="mt-2">
                Showing 
                {{ $paginator->firstItem() }} 
                to 
                {{ $paginator->lastItem() }} 
                of 
                {{ $paginator->total() }} 
                entries
            </p>
        </div>
        <div class="d-inline-block">
            <select class="form-select form-select-sm w-auto ms-2" onchange="window.location.href=this.value">
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}" {{ request()->query('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ request()->query('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request()->query('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ request()->query('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>

        <ul class="pagination mb-0 flex-wrap float-end">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                        Prev
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                        Prev
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                $start = max(1, $paginator->currentPage() - 2);
                $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
            @endphp

            {{-- First Page --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                
                @if ($start > 2)
                    <li class="page-item disabled">
                        <a class="page-link" href="javascript:void(0);">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>
                    </li>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active">
                        <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <li class="page-item disabled">
                        <a class="page-link" href="javascript:void(0);">
                            <i data-feather="more-horizontal"></i>
                        </a>
                    </li>
                @endif
                
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">
                        {{ $paginator->lastPage() }}
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link text-primary" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                        Next
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="javascript:void(0);" aria-label="Next">
                        Next
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif