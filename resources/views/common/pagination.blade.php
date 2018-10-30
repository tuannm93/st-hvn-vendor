@if ($paginator->hasPages())
    <div class="dataTables_paginate">
        @if ($paginator->onFirstPage())
            <a class="paginate_button" onclick="return false;" rel="prev" aria-controls="searchData" id="search_previous">&lt; @lang('common.prev_page')</a>
        @else
            <a class="paginate_button previous active" rel="prev" aria-controls="searchData" id="search_previous">&lt; @lang('common.prev_page')</a>
        @endif
        <span class="pl-3 pr-3"></span>
        @if ($paginator->hasMorePages())
            <a class="paginate_button next active" rel="next" aria-controls="searchData" id="search_next" data-cur-page="1">@lang('common.next_page') &gt;</a>
        @else
            <a class="paginate_button"  onclick="return false;" rel="next" aria-controls="searchData" id="search_next">@lang('common.next_page') &gt;</a>
        @endif
    </div>
@endif
