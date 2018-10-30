@if(isset($regist) && $regist == true)
@else
    <div class="text-center">
        {{ Form::input('button', 'all_regist', trans('corp_target_area_select.select_all_areas'), ['data-corp-id' => $corpId, 'data-url' => route('corp_target_area_select.update_all_data'), 'class' => 'btn btn--gradient-orange col-5 col-sm-4 col-md-2', 'id'=>'all_regist']) }}
        {{ Form::input('button', 'all_remove', trans('corp_target_area_select.delete_all_areas'), ['data-corp-id' => $corpId, 'data-url' => route('corp_target_area_select.delete_all_data'), 'class' => 'btn btn--gradient-orange col-5 col-sm-4 col-md-2 mx-2', 'id'=>'all_remove']) }}
    </div>
    <div class="row mt-3 mb-2 pl-3 pr-3 pref-title">
        @for($i=1; $i<=6; $i++)
            <div class="col-1 p-0">{{ trans('corp_target_area_select.response_region') }}</div>
            <div class="col-1 p-0">{{ trans('corp_target_area_select.advanced_settings') }}</div>
        @endfor
    </div>
    <div class="row pl-3 pr-3 mb-3 pref-list">
        @foreach($prefList as $key => $item)
            <div class="col-md-2">
                <div class="row mb-1">
                    <div class="col-6 p-0 form-check">
                        <div class="custom-control custom-checkbox">
                            @if($item['rank'] == 2)
                                {{ Form::input('hidden', 'data[nocheckaddress1]['.$item['id'].']', null, ['class' => 'check_group address1_'.$item['id']]) }}
                            @endif
                            {{ Form::input('checkbox', 'data[address1]['.$item['id'].']', $item['name'], ['class' => 'check_group custom-control-input address1_'.$item['id'], 'id' => 'address1_'.$item['id'], ($item['rank'] == 2)? 'checked' : '']) }}
                            <label for="address1_{{ $item['id'] }}" class="custom-control-label">{{ $item['name'] }}</label>
                        </div>
                    </div>
                    <div class="col-6 p-0">
                        {{ Form::input('button', null, trans('corp_target_area_select.detailed'), ['data-num' => $item['id'], 'data-txt' => $item['name'], 'data-url' => route('ajax.searchCorpTargetArea', [$corpId, $item['name']]), 'class' => 'btn btn--gradient-default detail']) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif