@if ($paginator->hasPages())
    <div class="dataTables_paginate">
        @if ($paginator->onFirstPage())
            <a class="paginate_button pr-3 disabled"  rel="prev">
                &lt; @lang('mcorp_list.prev')
            </a>
        @else
            <a data-cur-page="{{ $paginator->currentPage() }}" class="paginate_button pr-3 previous active" rel="prev" href="#">
                &lt; @lang('mcorp_list.prev')
            </a>
        @endif
        @if ($paginator->hasMorePages())
            <a data-cur-page="{{ $paginator->currentPage() }}" class="paginate_button pl-3 next active" rel="next" href="#">
                @lang('mcorp_list.next') &gt;
            </a>
        @else
            <a class="paginate_button pl-3 disabled"  rel="next">
                @lang('mcorp_list.next') &gt;
            </a>
        @endif
    </div>
@else
    <div class="dataTables_paginate">
        <a class="paginate_button pr-3 disabled"  rel="prev">
            &lt; @lang('mcorp_list.prev')
        </a>
        <a class="paginate_button pl-3 disabled"  rel="next">
            @lang('mcorp_list.next') &gt;
        </a>
    </div>
@endif
