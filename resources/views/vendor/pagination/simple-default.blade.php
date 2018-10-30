@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled mr-4"><span>@lang('pagination.previous')</span></li>
        @else
            <li class="mr-4"><a class="highlight-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="mf-4"><a class="highlight-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a></li>
        @else
            <li class="disabled mf-4"><span>@lang('pagination.next')</span></li>
        @endif
    </ul>
@endif
