<div class="collapse d-sm-block agreement-system" id="setting">
    <div class="row">
        <div class="col-12">
            <label class="sub-label mt-3 border_left_orange">
                <strong>{{__('agreement_system.basic_compatible_area_list')}}</strong>
            </label>
        </div>
        <div class="col-12">
            <div class="select_area_contents mt-md-3">
                <div class="area_line_group d-block d-md-table">
                    @foreach($prefList as $pref)
                        <div class="pref_block d-block d-md-table-cell">
                            <div class="pref_sub d-table">
                                <div
                                    class="pref_sub1 @if($pref->status == 1) pref_sub1__none @elseif($pref->status == 2) pref_sub1__part @endif d-table-cell">
                                    @if($pref->status == 1)
                                        {!! trans('agreement_system.correspondence_impossible') !!}
                                    @elseif($pref->status == 2)
                                        {!! trans('agreement_system.partial_area_support_possible')!!}
                                    @else
                                        {!! trans('agreement_system.all_regions_available')!!}
                                    @endif
                                </div>
                                <div class="pref_sub2 d-table-cell">
                                    {{ $pref->address1 }}
                                </div>
                                <div class="pref_sub3 d-table-cell">
                                    <a class="viewSelectArea" style="cursor:pointer;" target="_blank"
                                       data-city="{{$pref->address1}}"
                                       data-address="{{$pref->address1_cd}}">
                                        {{__('agreement_system.area')}}<span
                                            class="d-none d-sm-inline">≫</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if($loop->iteration % 3 == 0)
                </div>
                <div class="area_line_group d-block d-md-table">
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
