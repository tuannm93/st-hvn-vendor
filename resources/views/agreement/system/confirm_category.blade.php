<div class="row agreement-system">
    <div class="col-12">
        <label class="sub-label mt-3 border_left_orange">
            <strong>{{__('agreement_system.applicable_genre_list')}}</strong>
        </label>
    </div>

    {{-- Category A --}}
    <div class="col-12">
        <label class="sub-label border_left_orange">
            <strong>{{__('agreement_system.conclusion_base_business_category_A')}}</strong>
        </label>
    </div>
    <div class="col-12">
        <label class="sub-label border_left_orange">
            {{__('agreement_system.genre_reflects_basic_correspondence_area')}}
        </label>
        @component('agreement.system.component.genre_table', [
            'corpCategoryList' => $corpCategoryList,
            'category' => 'A'
        ])
        @endcomponent
    </div>

    {{-- Category B --}}
    <div class="col-12">
        <label class="sub-label border_left_orange">
            <strong>{{__('agreement_system.conclusion_base_business_category_B')}}</strong>
        </label>
    </div>
    <div class="col-12">
        <label class="sub-label border_left_orange">
            {{__('agreement_system.genre_reflects_basic_correspondence_area')}}
        </label>
        @component('agreement.system.component.genre_table', [
            'corpCategoryList' => $corpCategoryList,
            'category' => 'B'
        ])
        @endcomponent
    </div>
</div>

