<p class="my-2 mt-sm-0 ">{{__('affiliation_resign.title_categories_base')}}</p>
@if(empty($data['basic']))
    <div class="box__mess box--success font-weight-light mb-3">{{__('affiliation_resign.message_no_categories')}}</div>
@else
    @component('affiliation.components.resign.resign_table', [
        'data' => $data['basic'],
        'isConclusionBase' => $isConclusionBase,
        'listFeeUnit' => $listFeeUnit,
        'listCorpCommisionType' => $listCorpCommisionType,
        'idTable' => 'table_basic_' . $listType
    ])
    @endcomponent
@endif
<p class="my-2 mt-sm-0">{{__('affiliation_resign.title_categories_individual')}}</p>
@if(empty($data['individual']))
    <div class="box__mess box--success font-weight-light mb-3">{{__('affiliation_resign.message_no_categories')}}</div>
@else
    @component('affiliation.components.resign.resign_table', [
        'data' => $data['individual'],
        'isConclusionBase' => $isConclusionBase,
        'listFeeUnit' => $listFeeUnit,
        'listCorpCommisionType' => $listCorpCommisionType,
        'idTable' => 'table_individual_' . $listType
    ])
    @endcomponent
@endif