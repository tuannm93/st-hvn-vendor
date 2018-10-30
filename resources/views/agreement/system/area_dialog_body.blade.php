<div>
    <label class="form-category__label mb-4 d-none d-sm-block">
        @lang('agreement_system.area_configuration_base')
    </label>
    <p>@lang('agreement_system.area_content_notice')</p>
    <div id="area_info">
        <div class="area_line_group d-block d-md-table">
            @foreach($addressAreaList as $index => $addressArea)
                <div class="pref_block d-block d-md-table-cell">
                    <div class="pref_sub d-table">
                        <div class="pref_sub1 @if($addressArea['status'] == 1) pref_sub1__none @elseif($addressArea['status'] == 2) pref_sub1__part @endif d-table-cell">
                            @if($addressArea['status'] == 1)
                                {!! __('agreement_system.correspondence_impossible') !!}
                            @elseif($addressArea['status'] == 2)
                                {!! __('agreement_system.partial_area_support_possible') !!}
                            @else
                                {!! __('agreement_system.all_regions_available') !!}
                            @endif
                        </div>
                        <div class="pref_sub2 d-table-cell">
                            {{ $addressArea['address1'] }}
                        </div>
                        <div class="pref_sub3 d-table-cell">
                            <a class="selectArea" style="cursor:pointer;" target="_blank"
                               data-city="{{$addressArea['address1']}}" data-address="{{$addressArea['address1_cd']}}">
                                {{__('agreement_system.btn_area_configuration')}}<span
                                    class="d-none d-sm-inline">≫</span>
                            </a>
                        </div>
                    </div>
                </div>
                @if(($index + 1) % 4 == 0)
        </div>
        <div class="area_line_group d-block d-md-table">
            @endif
            @endforeach
        </div>
    </div>
</div>
