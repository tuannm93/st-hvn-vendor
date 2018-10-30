@if ($paginator->hasPages() && $paginator->currentPage() > 0 && $paginator->currentPage() <= $paginator->lastPage())
    <nav aria-label="..." class="dataTables_paginate">
        <ul class="pagination justify-content-end mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <a class="paginate_button disabled mr-2" rel="prev">< @lang('pagination.previous')</a>
            @else
                <a  href="{{ $paginator->previousPageUrl() }}" class="paginate_button mr-2 prev active" rel="prev">< @lang('pagination.previous')</a>
            @endif
            @if($paginator->lastPage() > 5)
                @if($paginator->currentPage() < 4)
                    @for($i = 1; $i <=5; $i ++ )
                        @if($i == $paginator->currentPage())
                            <li class="">
                                <span aria-hidden="true">{{ $paginator->currentPage() }}</span>
                            </li>
                        @else
                            <li class="">
                                <a  href="{{ $paginator->url($i) }}" class="high-light">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor
                @elseif($paginator->currentPage() + 1 < $paginator->lastPage())
                    <li>
                        <a href="{{ $paginator->url($paginator->currentPage() - 2) }}" class="high-light">{{ $paginator->currentPage() - 2 }} </a>
                    </li>
                    <li>
                        <a href="{{ $paginator->url($paginator->currentPage() - 1) }}" class="high-light">{{ $paginator->currentPage() - 1 }} </a>
                    </li>
                    <li>
                        <span>{{ $paginator->currentPage() }} </span>
                    </li>
                    <li>
                        <a href="{{ $paginator->url($paginator->currentPage() + 1) }}" class="high-light">{{ $paginator->currentPage() + 1 }} </a>
                    </li>
                    <li>
                        <a href="{{ $paginator->url($paginator->currentPage() + 2) }}" class="high-light">{{ $paginator->currentPage() + 2 }} </a>
                    </li>
                @elseif($paginator->currentPage() + 1 == $paginator->lastPage())
                    <li>
                        <a href="{{ $paginator->url($paginator->currentPage() - 3) }}" class="high-light">{{ $paginator->currentPage() - 3 }} </a>
                    </li>
                    <li>
                        <a href="{{ $paginator->url($paginator->currentPage() - 2) }}" class="high-light">{{ $paginator->currentPage() - 2 }} </a>
                    </li>
                    <li>
                        <a href="{{ $paginator->url($paginator->currentPage() - 1) }}" class="high-light">{{ $paginator->currentPage() - 1 }} </a>
                    </li>
                    <li>
                        <span>{{ $paginator->currentPage() }} </span>
                    </li>
                    <li>
                        <a href="{{ $paginator->lastPage() }}" class="high-light">{{ $paginator->lastPage() }} </a>
                    </li>
                @else
                    {{ dump($paginator->currentPage()) }}
                     <li>
                        <a href="{{ $paginator->url($paginator->lastPage() - 4) }}" class="high-light">{{ $paginator->lastPage() - 4 }} </a>
                    </li>
                    <li>
                        <a href="{{ $paginator->url($paginator->lastPage() - 3) }}" class="high-light">{{ $paginator->lastPage() - 3 }} </a>
                    </li>
                    <li>
                        <a href="{{ $paginator->url($paginator->lastPage() - 2) }}" class="high-light">{{ $paginator->lastPage() - 2 }} </a>
                    </li>
                    <li>
                        <a href="{{ $paginator->url($paginator->lastPage() - 1) }}" class="high-light">{{ $paginator->lastPage() - 1 }} </a>
                    </li>
                    <li>
                        <span>{{ $paginator->lastPage() }} </span>
                    </li>
                @endif
            @else
                @for($i = 1; $i <= $paginator->lastPage(); $i ++)
                    <li>
                        @if($paginator->currentPage() == $i)
                            <span>{{ $i }} </span>
                        @else
                            <a href="{{ $paginator->url($i) }}" class="high-light">{{ $i }} </a>
                        @endif
                    </li>
                @endfor
            @endif
            @if ($paginator->hasMorePages())
                <a  href="{{ $paginator->nextPageUrl() }}" class="paginate_button ml-2 next active" rel="next">@lang('pagination.next') ></a>
            @else
                <a class="paginate_button ml-2 disabled" rel="next">@lang('pagination.next') ></a>
            @endif
        </ul>
    </nav>
@endif