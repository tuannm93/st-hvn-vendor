@php
    $disabledNext = $paginator['pageNumber'] == $paginator['curPage'] ? true : false;
    $disabledPrevious = $paginator['curPage'] == 1 ? true : false;
@endphp
<div class="pagination">
    <button id="btnPreviousListAffiliation" class="btn-pagination mr-2 {{ !$disabledPrevious ? "item-pagination active": ""}}"
            {{$disabledPrevious ? "disabled": ""}}>{{__('affiliation.previous')}}
    </button>
    <button id="btnNextListAffiliation" class="btn-pagination {{!$disabledNext ? "item-pagination active": ""}}"
            {{$disabledNext ? "disabled": ""}}>{{__('affiliation.next')}}
    </button>
</div>