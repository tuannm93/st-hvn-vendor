@if ($paginator->hasPages())
    <div class="dataTables_paginate">
        @if ($paginator->onFirstPage())
            <a class="paginate_button" rel="prev">&lt; @lang('mcorp_list.prev')</a>
        @else
            <a class="paginate_button previous active" href="{{ $paginator->url($paginator->currentPage()-1) }}" rel="prev">&lt; {{ __('report_corp_selection.prev') }}</a>
        @endif
        <span class="pl-3 pr-3"></span>
        @if ($paginator->hasMorePages())
            <a class="paginate_button next active" href="{{ $paginator->url($paginator->currentPage()+1) }}" rel="next">{{ __('report_corp_selection.next') }} &gt;</a>
        @else
            <a class="paginate_button" rel="next">@lang('mcorp_list.next') &gt;</a>
        @endif
    </div>
@endif