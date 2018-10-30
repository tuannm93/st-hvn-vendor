@if ($paginator->hasPages())
    <div class="dataTables_paginate mt-3">
        @if ($paginator->onFirstPage())
            <a class="paginate_button disabled" rel="prev">@lang('report_jbr.previousPage')</a>
        @else
            <a class="paginate_button previous active" rel="prev">@lang('report_jbr.previousPage')</a>
        @endif
        <span class="pl-3 pr-3"></span>
        @if ($paginator->hasMorePages())
            <a class="paginate_button next active" rel="next">@lang('report_jbr.nextPage')</a>
        @else
            <a class="paginate_button disabled">@lang('report_jbr.nextPage')</a>
        @endif
    </div>
@endif
