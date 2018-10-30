@if ($paginator->hasPages())
    <div class="dataTables_paginate">
        @if ($paginator->onFirstPage())
            <a class="paginate_button disabled"  rel="prev"
               aria-controls="tbBillSearch" id="tbBillSearch_previous">&lt; @lang('mcorp_list.prev')</a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="paginate_button previous active" rel="prev"
               aria-controls="tbBillSearch" id="tbBillSearch_previous">&lt; @lang('mcorp_list.prev')</a>
        @endif
        <span class="pl-3 pr-3"></span>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="paginate_button next active" rel="next"
               aria-controls="tbBillSearch" id="tbBillSearch_next">@lang('mcorp_list.next') &gt;</a>
        @else
            <a class="paginate_button disabled"  rel="next"
               aria-controls="tbBillSearch" id="tbBillSearch_next">@lang('mcorp_list.next') &gt;</a>
        @endif
    </div>
@endif