@if ($paginator->hasPages())
    <div class="dataTables_paginate">
        @if ($paginator->onFirstPage())
            <a class="paginate_button disabled" rel="prev">&lt; @lang('report_jbr.prev')</a>
        @else
            <a class="paginate_button previous active" rel="prev">&lt; @lang('report_jbr.prev')</a>
        @endif
        <span class="pl-3 pr-3"></span>
        @if ($paginator->hasMorePages())
            <a class="paginate_button next active" rel="next">@lang('report_jbr.next') &gt;</a>
        @else
            <a class="paginate_button disabled">@lang('report_jbr.next') &gt;</a>
        @endif
    </div>
@endif