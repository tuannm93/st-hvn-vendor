@if ($paginator->hasPages())
    <ul class="pagination d-flex justify-content-center">
        @if($paginator->currentPage() == $paginator->lastPage() && $paginator->lastPage() > 3)
            <li class="page-item disabled">
                <a href="{{$paginator->url($paginator->lastPage()-2)}}"
                   class="page-link">{{$paginator->lastPage()-2}}</a>
            </li>
        @endif
        @if($paginator->currentPage() == 1)
            <li class="page-item active"><span class="page-link">1</span></li>
        @else
            @if($paginator->currentPage()>1)
                <li class="page-item">
                    <a href="{{$paginator->url($paginator->currentPage()-1)}}"
                       class="page-link">{{ $paginator->currentPage()-1 }}</a>
                </li>
            @endif
            <li class="page-item active"><span class="page-link">{{$paginator->currentPage()}}</span></li>
        @endif

        @if($paginator->currentPage()<$paginator->lastPage())
            <li class="page-item">
                <a href="{{$paginator->url($paginator->currentPage()+1)}}"
                   class="page-link">{{ $paginator->currentPage()+1 }}</a>
            </li>
        @endif
        @if($paginator->currentPage() == 1 && $paginator->lastPage() > 3)
            <a href="{{$paginator->url($paginator->currentPage() + 2)}}"
               class="page-link">{{$paginator->currentPage() + 2}}</a>
        @endif
    </ul>
@endif
