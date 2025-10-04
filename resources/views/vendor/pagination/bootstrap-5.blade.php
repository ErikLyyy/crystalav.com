@if ($paginator->hasPages())
    <nav>
        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div>
                <p class="small text-muted">
                    {!! __('Showing') !!}
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>
            <ul class="pagination justify-content-center">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">&laquo;</a></li>
                @endif

                @php
                    $dotBeforeShown = false;
                    $dotAfterShown = false;
                @endphp

                @for ($page = 1; $page <= $paginator->lastPage(); $page++)
                    @if ($page == 1 || $page == $paginator->lastPage() || abs($page - $paginator->currentPage()) <= 2)
                        <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                        </li>
                    @elseif ($page < $paginator->currentPage() && !$dotBeforeShown)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                        @php $dotBeforeShown = true; @endphp
                    @elseif ($page > $paginator->currentPage() && !$dotAfterShown)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                        @php $dotAfterShown = true; @endphp
                    @endif
                @endfor

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">&raquo;</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif

            </ul>
        </div>
    </nav>
@endif
