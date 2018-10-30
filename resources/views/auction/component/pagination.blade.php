@if ($paginator->hasPages())
    <div class="dataTables_paginate">
        @if ($paginator->onFirstPage())
            <a class="paginate_button previous disabled" rel="prev"
               aria-controls="tbBillSearch" id="tbBillSearch_previous">&lt; @lang('auction.prev')</a>
        @else
            <a class="paginate_button previous active" href="{{ $paginator->previousPageUrl() }}" rel="prev"
               aria-controls="tbBillSearch" id="tbBillSearch_previous">&lt; @lang('auction.prev')</a>
        @endif
        <span class="pl-3 pr-3"></span>
        @if ($paginator->hasMorePages())
            <a class="paginate_button next active" href="{{ $paginator->nextPageUrl() }}" rel="next"
               aria-controls="tbBillSearch" id="tbBillSearch_next">@lang('auction.next') &gt;</a>
        @else
            <a class="paginate_button next disabled" rel="next"
               aria-controls="tbBillSearch" id="tbBillSearch_next">@lang('auction.next') &gt;</a>
        @endif
    </div>
@endif