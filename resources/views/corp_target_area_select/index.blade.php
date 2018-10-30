<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger" style="display: none"></div>
        <div class="alert alert-success" style="display: none"></div>
        <div class="contents">
            {{ Form::open(['id' => 'corp-target-area-select', 'enctype' => 'multipart/form-data', 'type'=>'post', 'accept-charset'=>"UTF-8" ]) }}
            <h3 class="form-category__label mb-3">{{ trans('corp_target_area_select.set_basic_compatible_area') }}</h3>
            <div id='display_area'>
                <div class="text-center">
                    {{ Form::input('button', 'all_regist', trans('corp_target_area_select.select_all_areas'), ['data-corp-id' => $corpId, 'data-url' => route('corp_target_area_select.update_all_data'), 'class' => 'btn btn--gradient-orange col-12 col-sm-4 col-md-2 mx-2 mr-sm-2 mb-2 mb-sm-0', 'id'=>'all_regist']) }}
                    {{ Form::input('button', 'all_remove', trans('corp_target_area_select.delete_all_areas'), ['data-corp-id' => $corpId, 'data-url' => route('corp_target_area_select.delete_all_data'), 'class' => 'btn btn--gradient-orange col-12 col-sm-4 col-md-2', 'id'=>'all_remove']) }}
                </div>
            @if(isset($regist) && $regist == true)
            @else
                <div class="row mt-3 mb-2 pl-3 pr-3 pref-title">
                @for($i=1; $i<=6; $i++)
                    <div class="col-1 p-0">{{ trans('corp_target_area_select.response_region') }}</div>
                    <div class="col-1 p-0">{{ trans('corp_target_area_select.advanced_settings') }}</div>
                @endfor
                </div>
                <div class="row mb-3 pl-3 pr-3 pref-list">
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
            </div>
            {{ Form::input('hidden', 'data[address1_text]', null, ['id' => 'address1_text']) }}
            {{ Form::input('hidden', 'corp-id', $corpId ) }}
            <input id="commt_flg" name="commt_flg" type="hidden" value="">
            <div class="text-center">
                {{ Form::input('button', null, trans('corp_target_area_select.cancel'), ['class' => 'btn btn--gradient-default col-5 col-sm-4 col-md-2', 'id' => 'close_modal']) }}
                {{ Form::input('button', 'regist', trans('corp_target_area_select.register'), ['data-corp-id' => $corpId, 'data-url' => route('corp_target_area_select.regist_address_data'), 'class' => 'btn btn--gradient-green col-5 col-sm-4 col-md-2 mx-2', 'id' => 'regist']) }}
                {{ Form::input('button', 'registjsc', trans('corp_target_area_select.register'), ['data-corp-id' => $corpId, 'data-url' => route('corp_target_area_select.regist_data'), 'class' => 'btn btn--gradient-green col-5 col-sm-4 col-md-2 mx-2', 'id' => 'registjsc', 'hidden' => 'true']) }}
                {{ Form::input('button', 'back', trans('corp_target_area_select.back'), ['class' => 'btn btn--gradient-default col-5 col-sm-4 col-md-2 show-corp-target-area-select', 'data-url' => route('corp_target_area_select.index', $corpId), 'id' => 'back_modal', 'hidden' => 'true']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
