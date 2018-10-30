@if ($paginator->hasPages())
    <div class="dataTables_paginate">
        @if ($paginator->onFirstPage())
            <a rel="prev" aria-controls="tbBillSearch" id="tbBillSearch_previous">&lt; @lang('mcorp_list.prev')</a>
        @else
            <a class="paginate_button previous active" rel="prev"
               aria-controls="tbBillSearch" id="tbBillSearch_previous" href="javascript:void(0)">&lt; @lang('mcorp_list.prev')</a>
        @endif
        <span class="pl-3 pr-3"></span>
        @if ($paginator->hasMorePages())
            <a class="paginate_button next active" rel="next"
               data-cur-page="1"
               aria-controls="tbBillSearch" id="tbBillSearch_next" href="javascript:void(0)">@lang('mcorp_list.next') &gt;</a>
        @else
            <a rel="next"
               aria-controls="tbBillSearch" id="tbBillSearch_next">@lang('mcorp_list.next') &gt;</a>
        @endif
    </div>
@endif
