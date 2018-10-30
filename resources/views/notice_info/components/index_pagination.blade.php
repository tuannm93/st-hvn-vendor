@if ($paginator->hasPages())
    <div class="dataTables_paginate">
        @if ($paginator->onFirstPage())
            <a class="paginate_button disabled" rel="prev">&lt; @lang('notice_info.prev')</a>
        @else
            <a class="paginate_button previous active" rel="prev">&lt; @lang('notice_info.prev')</a>
        @endif
        <span class="pl-3 pr-3"></span>
        @if ($paginator->hasMorePages())
            <a class="paginate_button next active" rel="next">@lang('notice_info.next') &gt;</a>
        @else
            <a class="paginate_button disabled">@lang('notice_info.next') &gt;</a>
        @endif
    </div>
@endif
