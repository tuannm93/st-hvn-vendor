<?php
$invalidFlowDate = false;
$orderFailDate = old('demandInfo')['order_fail_date'];
$orderFailDateInvalid = false;
$visitTime = [];
$timeRank1 = 'checked';
$timeRank2 = '';
$desertFrom = $demand->contact_desired_time_from_format;
$desertTo = $demand->contact_desired_time_to_format;
$disabled = false;
if ($demand->is_contact_time_range_flg == 0) {
    $disabled = true;
}

if ($demand->is_contact_time_range_flg == 1) {
    $desertTime = '';
    $timeRank2 = 'checked';
    $timeRank1 = '';
}
if (old('demandInfo')['order_fail_date'] && strtotime(old('demandInfo')['order_fail_date']) === false) {
    $orderFailDateInvalid = true;
    $orderFailDate = '';
}
if (old('demandInfo')['follow_date'] && strtotime(old('demandInfo')['follow_date']) === false) {
    $invalidFlowDate = true;
}

$desertTime = dateTimeFormat($demand->contact_desired_time);
if (old('demandInfo')) {
    $desertTime = old('demandInfo.contact_desired_time');
}

if (old('demandInfo')) {
    $desertFrom = old('demandInfo.contact_desired_time_from');
}
if (old('demandInfo')) {
    $desertTo = old('demandInfo.contact_desired_time_to');
}

if (old('demandInfo.is_contact_time_range_flg') != null) {
    if (old('demandInfo.is_contact_time_range_flg') == 1) {
        $timeRank2 = 'checked';
        $timeRank1 = '';
        $desertTime = '';
        $disabled = false;
    }
    else {
        $disabled = true;
        $timeRank2 = '';
        $timeRank1 = 'checked';
    }
}
?>
<div class="form-table">
    <div class="row mx-0">
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.customer_name')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text("demandInfo[customer_name]", $demand->customer_name, ['class' => 'form-control is-required', 'data-rules' => 'not-empty', 'maxlength' => 50]) !!}
                @if ($errors->has('demandInfo.customer_name'))
                    <label class="invalid-feedback d-block">{{ $errors->first('demandInfo.customer_name') }}</label>
                @endif
            </div>
        </div>
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.corporate_name')</label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text("demandInfo[customer_corp_name]", $demand->customer_corp_name, ['class' => 'form-control', 'id' => 'customer_corp_name']) !!}
                @if ($errors->has('demandInfo.customer_corp_name'))
                    <label class="invalid-feedback d-block">{{ $errors->first('demandInfo.customer_corp_name') }}</label>
                @endif
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.customer_tel')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-3 py-2 form-table-cell">
                @php
                    $tel = '';
                    if(!empty(old('demandInfo')['customer_tel'])) {
                        $tel = old('demandInfo')['customer_tel'];
                    }
                @endphp
                {!! Form::text("demandInfo[customer_tel]", $demand->customer_tel, ['class' => 'd-inline-block form-control w-50 is-required', 'data-rules'=> "not-empty,valid-customer", 'maxlength' => '11']) !!}
                @if(!empty(old('demandInfo')['customer_tel']))
                    <a href="{{ checkDevice().$tel }}" class="text--orange ml-2 w-50">{{ $tel }}</a>
                @else
                    <a href="{{ checkDevice().$demand->customer_tel }}"
                        class="text--orange ml-2 w-50">{{ $demand->customer_tel }}</a>
                @endif

                @if (Session::has('demand_errors.check_customer_tel'))
                    <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_customer_tel')}}</label>
                @elseif ($errors->has('demandInfo.customer_tel'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.customer_tel')}}</label>
                @elseif ($errors->has('check_customer_tel'))
                    <label class="invalid-feedback d-block">
                        {{$errors->first('check_customer_tel')}}
                    </label>
                @endif
                <div id="err-demandInfo_customer_tel"></div>
            </div>
            <div class="d-none d-lg-flex col-lg-6 py-2 form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.contact_first')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text("demandInfo[tel1]", $demand->tel1, ['class' => 'd-inline-block form-control w-50 is-required', 'data-rules' => 'not-empty,valid-number', 'maxlength' => 11]) !!}
                <a href="{{checkDevice().(old('demandInfo')['tel1'] ?? $demand->tel1) }}"
                       class="text--orange ml-2 w-50">{{ old('demandInfo')['tel1'] ?? $demand->tel1 }}</a>
                @if (Session::has('demand_errors.check_tel1'))
                    <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_tel1')}}</label>
                @elseif ($errors->has('demandInfo.tel1'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.tel1')}}</label>
                @endif
                <div id="err-demandInfo_tel"></div>
            </div>
        </div>
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.contact_second')</label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text("demandInfo[tel2]", $demand->tel2, ['class' => 'd-inline-block form-control w-50 is-required', 'data-rules' => 'valid-number', 'maxlength' => 11]) !!}
                @if(!empty(old('demandInfo')['tel2']))
                    <a href="{{checkDevice(). old('demandInfo')['tel2'] }}"
                        class="text--orange ml-2 mr-2">{{ old('demandInfo')['tel2'] }}</a>
                @else
                    <a href="{{ checkDevice().$demand->tel2 }}" class="text--orange w-50">{{ $demand->tel2 }}</a>
                @endif
                @if ($errors->has('demandInfo.tel2'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.tel2')}}</label>
                @endif

            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.customer_email_address')</label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! Form::text("demandInfo[customer_mailaddress]", $demand->customer_mailaddress, ['class' => 'form-control is-required', 'data-rules' => 'valid-email']) !!}
                @if ($errors->has('demandInfo.customer_mailaddress'))
                    <label
                        class="invalid-feedback d-block">{{$errors->first('demandInfo.customer_mailaddress')}}</label>
                @endif
            </div>
            <div class="d-none d-lg-flex col-lg-6 py-2 form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.postal_code')</label>
            <div class="col-12 col-lg-9 py-2 form-table-cell">
                <div class="form-group mb-lg-0">
                    {!! Form::text("demandInfo[postcode]", $demand->postcode, ['class' => 'form-control d-inline w-40 w-sm-15', 'id' => 'postcode', 'size' => 7, 'maxlength' => 7]) !!}
                    <button class="btn btn--gradient-default d-inline ml-lg-2"
                            id="search-address-by-zip">@lang('demand_detail.address_search')</button>
                    <p class="d-block d-xl-inline ml-lg-2 text-muted mt-2 mb-0 mt-xl-0">@lang('demand_detail.entering_postal_codes')@lang('demand_detail.hyphen')</p>
                </div>
                <div id="err-postcode">
                    @if ($errors->has('demandInfo.postcode'))
                        <label class="invalid-feedback d-block">{{$errors->first('demandInfo.postcode')}}</label>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.prefectures')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::select('demandInfo[address1]', $prefectureDiv, $demand->address1, ['class' => 'form-control is-required', 'id' => 'address1', 'data-rules' => 'not-empty', 'maxlength' => 10]) !!}
                {{--                    {!! Form::select('demandInfo[address1]', $prefectureDiv, '', ['class' => 'form-control', 'id' => 'address1']) !!}--}}
                @if ($errors->has('demandInfo.address1'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.address1')}}</label>
                @endif
            </div>
        </div>
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.municipality')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text('demandInfo[address2]', $demand->address2, ['class' => 'form-control is-required', 'id' => 'address2', 'data-rules' => 'not-empty', 'maxlength' => 20]) !!}
                @if ($errors->has('demandInfo.address2'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.address2')}}</label>
                @endif
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.later_address')</label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text('demandInfo[address3]', $demand->address3, ['class' => 'form-control', 'id' => 'address3']) !!}
                @if ($errors->has('demandInfo.address3'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.address3')}}</label>
                @endif
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.building_type')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::select('demandInfo[construction_class]', $mItemsDropDownList, $demand->construction_class, ['class' => 'form-control is-required', 'data-rules' => 'not-empty']) !!}
                @if ($errors->has('demandInfo.construction_class'))
                    <label
                        class="invalid-feedback d-block">{{$errors->first('demandInfo.construction_class')}}</label>
                @endif
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.selection_method')</label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! Form::select('demandInfo[selection_system]', $selectionSystemList, $demand->getOriginal('selection_system'), ['class' => 'form-control', 'id' => 'selection_system']) !!}
                {!! Form::hidden('demandInfo[selection_system_before]', isset($copy) || isset($cross) ? '' : $demand->selection_system, ['id' => 'selection_system_before']) !!}
                @if (Session::has('demand_errors.check_selection_system'))
                    <label
                        class="invalid-feedback d-block">{{Session::get('demand_errors.check_selection_system')}}</label>
                @elseif (Session::has('demand_errors.check_auction_setting_genre'))
                    <label
                        class="invalid-feedback d-block">{{Session::get('demand_errors.check_auction_setting_genre')}}</label>
                @elseif ($errors->has('demandInfo.selection_system'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.selection_system')}}</label>
                @endif
            </div>
            <div class="col-lg-6 d-none d-lg-flex form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex d-lg-block align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.content_of_consultation')</label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::textarea('demandInfo[contents]', !isset($cross) ? $demand->getOriginal('contents') : '', ['class' => 'form-control text-justify', 'rows' => 10, 'id' => 'demand-content']) !!}
                @if (Session::has('demand_errors.check_contents_string'))
                    <label
                        class="invalid-feedback d-block">{{Session::get('demand_errors.check_contents_string')}}</label>
                @elseif ($errors->has('demandInfo.contents'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.contents')}}</label>
                @endif
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex d-lg-block align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.sharing')<br>
                            @lang('demand_detail.technology_side_notes')</label>
            <div class="col-lg-6 py-2 form-table-cell">
                <div id="attention" class="p-2 text-justify"></div>
            </div>
        </div>
    </div>
    <div data-group-fill-required="true" data-error-container="#msg-required-visit">
        <div class="row mx-0">
            <div class="col-12 row m-0 p-0">
                <div class="col-lg-3 px-0 form-table-cell">
                    <div class="form__label form__label--white-light p-3 h-100">
                        <label class="m-0">
                            <strong> @lang('demand_detail.contact_deadline')</strong>
                        </label>
                        <button class="btn btn--gradient-default d-block"
                                id="plus-15-minus"> @lang('demand_detail.15_minutes')</button>
                    </div>
                </div>
                <div class="col-lg-9 py-2 form-table-cell visitTimeDiv">
                    <div class="form-row align-items-center">
                        <div class="custom-control custom-radio mr-2">
                            <input {{ $timeRank1 }} type="radio" name='demandInfo[is_contact_time_range_flg]'
                                class="custom-control-input range-time-0 absolute_time" value="0"
                                id="is_contact_time_range_flg_0" data-Chk="{{ $timeRank1 }}"/>
                            <label class="custom-control-label"
                                   for="demandInfo[is_contact_time_range_flg_0]">@lang('demand_detail.specify_time')</label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input {{ $timeRank2 }} class="custom-control-input range-time-1 range_time"
                                id="is_contact_time_range_flg_1"
                                name="demandInfo[is_contact_time_range_flg]" type="radio" value="1"
                                aria-invalid="false" data-Chk="{{ $timeRank2 }}">

                            <label class="custom-control-label"
                                   for="demandInfo[is_contact_time_range_flg_1]"> @lang('demand_detail.time_adjustment_required')</label>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-2">

                        <div class="col-lg-3 mb-2">
                            {!! Form::text('demandInfo[contact_desired_time]', $desertTime,
                            ['class' => 'form-control datetimepicker count txt_absolute_time is-required', 'id' => 'contact_desired_time', 'data-rules' => 'valid-date',
                                'disabled' => !$disabled
                            ]) !!}
                            {!! Form::hidden('demandInfo[contact_desired_time_before]', '') !!}
                            @if (Session::has('demand_errors.check_contact_desired_time2'))
                                <label
                                    class="invalid-feedback d-block">{{Session::get('demand_errors.check_contact_desired_time2')}}</label>
                            @elseif (Session::has('demand_errors.check_contact_desired_time3'))
                                <label
                                    class="invalid-feedback d-block">{{Session::get('demand_errors.check_contact_desired_time3')}}</label>
                            @elseif ($errors->has('demandInfo.contact_desired_time'))
                                <label
                                    class="invalid-feedback d-block">{{$errors->first('demandInfo.contact_desired_time')}}</label>
                            @endif

                        </div>
                        <div class="col-lg-4 mb-2">
                            <div class="number-kameiten font-weight-bold"
                                 data-apikey="{{env('GOOGLE_API_KEY')}}"></div>
                        </div>
                    </div>
                    <div class="form-row align-items-center date-time-group">
                        <div class="col-5 col-lg-3 mb-2 date-time-item">
                            {!! Form::text('demandInfo[contact_desired_time_from]', ($disabled ? '' : $desertFrom),
                                [
                                    'class' => 'form-control count datetimepicker txt_range_time',
                                    'disabled' => $disabled,
                                    'id' => 'DemandInfoContactDesiredTimeFrom'
                                ])
                            !!}
                            @if (Session::has('demand_errors.check_contact_desired_time4'))
                                <label
                                    class="invalid-feedback invalid-time d-block">{{Session::get('demand_errors.check_contact_desired_time4')}}</label>
                            @elseif ($errors->has('demandInfo.contact_desired_time_from'))
                                <label
                                    class="invalid-feedback invalid-time d-block">{{$errors->first('demandInfo.contact_desired_time_from')}}</label>
                            @elseif($errors->has('contact_desired_time_from'))
                                <label
                                    class="invalid-feedback invalid-time d-block">{{$errors->first('contact_desired_time_from')}}</label>
                            @endif
                        </div>
                        <div class="col-auto mb-2 text-center date-time-sup">〜</div>
                        <div class="col-5 col-lg-3 mb-2 date-time-item">
                            {!! Form::text('demandInfo[contact_desired_time_to]', ($disabled ? '' : $desertTo), [
                                    'class' => 'form-control count datetimepicker txt_range_time',
                                    'disabled' => $disabled,
                                    'id' => 'DemandInfoContactDesiredTimeTo'
                                ])
                            !!}
                            @if (Session::has('demand_errors.check_contact_desired_time5'))
                                <label
                                    class="invalid-feedback invalid-time d-block">{{Session::get('demand_errors.check_contact_desired_time5')}}</label>
                            @elseif (Session::has('demand_errors.check_require_to'))
                                <label
                                    class="invalid-feedback invalid-time d-block">{{Session::get('demand_errors.check_require_to')}}</label>
                            @elseif (Session::has('demand_errors.check_contact_desired_time6'))
                                <label
                                    class="invalid-feedback invalid-time d-block">{{Session::get('demand_errors.check_contact_desired_time6')}}</label>
                            @elseif ($errors->has('demandInfo.contact_desired_time_to'))
                                <label
                                    class="invalid-feedback invalid-time d-block">{{$errors->first('demandInfo.contact_desired_time_to')}}</label>
                            @endif
                        </div>
                    </div>
                    <p class="text--info mb-0">@lang('demand_detail.visit_time_text')</p>
                </div>
            </div>
        </div>
        @include('demand.cyzen.partials_estimated_time')
    </div>
    @php
        $label = [1 => '①', 2 => '②', 3 => '③'];
    @endphp
    @for($i = 1; $i <= 3; $i++)
        @php $j = $i - 1 @endphp
        {!! Form::hidden('visitTime[' . $i . '][visit_time_before]', isset($demand->visitTimes[$j]) ? $demand->visitTimes[$j]->visit_time_before : '') !!}
        {!! Form::hidden('visitTime[' . $i . '][commit_flg]', isset($demand->visitTimes[$j]) ? $demand->visitTimes[$j]->commit_flg : '') !!}
        {!! Form::hidden('visitTime[' . $i . '][id]', isset($demand->visitTimes[$j]) ? $demand->visitTimes[$j]->id : '') !!}
        {!! Form::hidden('visitTime[' . $i . '][visit_time_from]', isset($demand->visitTimes[$j]) ? $demand->visitTimes[$j]->visit_time_from : '') !!}
        {!! Form::hidden('visitTime[' . $i . '][visit_time_to]', isset($demand->visitTimes[$j]) ? $demand->visitTimes[$j]->visit_time_to : '') !!}
        {!! Form::hidden('visitTime[' . $i . '][visit_adjust_time]', isset($demand->visitTimes[$j]) ? $demand->visitTimes[$j]->visit_adjust_time : '') !!}
        <div class="row mx-0 visit-time-div normal_info"
             style="display: @if($demand->visitTimes) block @else none @endif;">
            <div class="col-12 row m-0 p-0">
                <label class="col-lg-3 d-flex d-lg-block align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.desired_visit_date'){{ $label[$i] }}</label>
                <div class="col-lg-9 py-2 visitTimeDiv form-table-cell">
                    <div class="form-row align-items-center">
                        <div class="custom-control custom-radio mr-2">
                            <input @if((isset(old('visitTime')[$i]) && old('visitTime')[$i]['is_visit_time_range_flg'] == 0)
                            || (!isset($demand->visitTimes[$j])
                            || $demand->visitTimes[$j]->is_visit_time_range_flg == 0)) checked @endif
                            name='visitTime[{{ $i }}][is_visit_time_range_flg]' type="radio" value="0"
                                   id="visitTime[{{ $i }}][is_visit_time_range_flg_0]"
                                   class="custom-control-input absolute_time"/>


                            <label class="custom-control-label"
                                   for="{{'visitTime['. $i .'][is_visit_time_range_flg_0]'}}">@lang('demand_detail.specify_time')</label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input
                                @if((isset(old('visitTime')[$i]) && old('visitTime')[$i]['is_visit_time_range_flg'] == 1) || (isset($demand->visitTimes[$j])) && $demand->visitTimes[$j]->is_visit_time_range_flg == 1))
                                checked
                                @endif name='visitTime[{{ $i }}][is_visit_time_range_flg]' type="radio" value="1"
                                id="visitTime[{{ $i }}][is_visit_time_range_flg_1]"
                                class="custom-control-input range_time"/>
                            <label class="custom-control-label"
                                   for="{{ 'visitTime[' . $i . '][is_visit_time_range_flg_1]' }}">@lang('demand_detail.time_adjustment_required')</label>
                        </div>
                    </div>
                    @php
                        $disabledVisitTime = false;
                        $disabledRangeTime = false;
                        $vsTime = isset($demand->visitTimes[$j]) ? $demand->visitTimes[$j]->visit_time_format : '';
                        if(old('visitTime')[$i] && old('visitTime')[$i]['is_visit_time_range_flg'] == 1){
                            $disabledVisitTime = true;
                            $vsTime = null;
                            $disabledRangeTime = false;
                        }elseif(!isset(old('visitTime')[$i]) && isset($demand->visitTimes[$j]) && $demand->visitTimes[$j]->is_visit_time_range_flg == 1){
                            $disabledVisitTime = true;
                            $vsTime = null;
                            $disabledRangeTime = false;
                        }elseif(!isset(old('visitTime')[$i]) && !isset($demand->visitTimes[$j])){
                            $disabledVisitTime = false;
                            $disabledRangeTime = true;
                        }else{
                            $disabledVisitTime = false;
                            $disabledRangeTime = true;
                        }
                    @endphp
                    <div class="form-row align-items-center">
                        <div class="col-12 col-lg-3 mb-2">
                            {!! Form::hidden('visitTime[' . $i . '][visit_time]', '') !!}
                            <input class="form-control datetimepicker txt_absolute_time"
                                   name="visitTime[{{ $i }}][visit_time]"
                                   type="text"
                                   @if($disabledVisitTime) disabled @endif

                                   value="{{ $vsTime }}"
                                   aria-invalid="true"/>
                            @if($errors->get('visit_time') && isset($errors->get('visit_time')[$i]))
                                <label class="invalid-feedback d-block text-center">{{ $errors->get('visit_time')[$i] }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="form-row mb-2 date-time-group">
                        <div class="col-5 col-lg-3 mb-2 date-time-item">
                            <input class="form-control datetimepicker txt_range_time"
                                   name="visitTime[{{ $i }}][visit_time_from]"
                                   type="text"
                                   @if($disabledRangeTime) disabled @endif
                                   value="{{ $disabledRangeTime ? '' : $demand->buildVisittime(old('visitTime'), $j, 'visit_time_from') }}"
                                   aria-invalid="true"/>
                            @if($errors->get('visit_time_from') && isset($errors->get('visit_time_from')[$i]))
                                <label class="invalid-feedback invalid-time d-block text-center">{{ $errors->get('visit_time_from')[$i] }}</label>
                            @endif
                        </div>

                        <span class="col-auto my-2 text-center date-time-sup">〜</span>
                        <div class="col-5 col-lg-3 mb-2 date-time-item">
                            <input class="form-control datetimepicker txt_range_time"
                                   name="visitTime[{{ $i }}][visit_time_to]"
                                   type="text"
                                   @if($disabledRangeTime) disabled @endif
                                   value="{{ $disabledRangeTime ? '' : $demand->buildVisittime(old('visitTime'), $j, 'visit_time_to') }}"
                                   aria-invalid="true"/>
                            @if($errors->get('visit_time_to') && isset($errors->get('visit_time_to')[$i]))
                                <label class="invalid-feedback invalid-time d-block text-center">{{ $errors->get('visit_time_to')[$i] }}</label>
                            @endif

                        </div>
                        <span class="col-auto my-2 date-time-sup-d">@lang('demand_detail.visit_adjustment') </span>
                        <div class="col-lg-3 mb-2 date-time-item">
                            <input class="form-control datetimepicker txt_range_time"
                                   name="visitTime[{{ $i }}][visit_adjust_time]"
                                   type="text"
                                   @if($disabledRangeTime) disabled @endif
                                   value="{{ $disabledRangeTime ? '' : $demand->buildVisittime(old('visitTime'), $j, 'visit_adjust_time') }}"
                                   aria-invalid="true"/>
                            @if($errors->get('adjust_time') && isset($errors->get('adjust_time')[$i]))
                                <label class="invalid-feedback invalid-time-from-to d-block">{{ $errors->get('adjust_time')[$i] }}</label>
                            @endif
                        </div>
                    </div>

                    @if($i != 0) <p class="text--info mb-0">@lang('demand_detail.visit_possible_time')@lang('demand_detail.induce_to_complete')</p>@endif
                </div>
            </div>
        </div>
    @endfor


    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.business_trip_cost')</label>
            <div class="col-12 col-lg-9 py-2 visitTimeDiv form-table-cell">
                <div class="form-row align-items-center">
                    <div class="col-5 col-lg-3">
                        {!! Form::text('demandInfo[business_trip_amount]', $demand->business_trip_amount,
                        ['class' => 'form-control is-required', 'id' => 'business-trip-mount', 'data-rules' => 'valid-number']) !!}
                    </div>
                    <span class="col-auto text-center"> @lang('demand_detail.circle') </span>
                    @if ($errors->has('demandInfo.business_trip_amount'))
                        <label
                            class="invalid-feedback d-block">{{$errors->first('demandInfo.business_trip_amount')}}</label>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.service_price_offer')</label>
            <div class="col-lg-9 py-2 form-table-cell">
                <div class="form-row align-items-center">
                    <div class="col-4 col-lg-3">
                        {!! Form::text('demandInfo[cost_from]', $demand->cost_from, ['class' => 'form-control is-required', 'data-rules' => 'valid-number']) !!}
                        @if ($errors->has('demandInfo.cost_from'))
                            <label class="invalid-feedback d-block">{{$errors->first('demandInfo.cost_from')}}</label>
                        @endif
                    </div>
                    <span class="col-auto text-center"> @lang('demand_detail.circle') </span>
                    <span class="col-auto text-center"> 〜</span>
                    <div class="col-4 col-lg-3">
                        {!! Form::text('demandInfo[cost_to]', $demand->cost_to, ['class' => 'form-control is-required', 'data-rules' => 'valid-number']) !!}
                        @if ($errors->has('demandInfo.cost_to'))
                            <label class="invalid-feedback d-block">{{$errors->first('demandInfo.cost_to')}}</label>
                        @endif
                    </div>
                    <span class="col-auto text-center"> @lang('demand_detail.circle')</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.follow_up_date')</label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! Form::text('demandInfo[follow_date]', $demand->follow_date, ['class' => 'form-control datetimepicker']) !!}
                @if (Session::has('demand_errors.follow_date'))
                    <label class="invalid-feedback d-block">{{Session::get('demand_errors.follow_date')}}</label>
                @elseif($invalidFlowDate == true)
                    <label class="invalid-feedback d-block">{{ trans('demand.validation_error.date_error') }}</label>
                @endif
            </div>
            <div class="col-lg-6 d-none d-xl-flex form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.reception_date_time')</label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! Form::text('demandInfo[receive_datetime]', isset($demand->receive_datetime_format) ? $demand->receive_datetime_format : '', ['class' => 'form-control datetimepicker']) !!}
                @if ($errors->has('demandInfo.receive_datetime'))
                    <label
                        class="invalid-feedback d-block">{{$errors->first('demandInfo.receive_datetime')}}</label>
                @endif
            </div>
            <div class="col-lg-6 d-none d-xl-flex form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.priority')</label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! Form::select('demandInfo[priority]', $priorityDropDownList, $demand->priority, ['class' => 'form-control', 'disabled' => Auth::user()->priority == 'system']) !!}
                {!! Form::hidden('demandInfo[priority_before]', $demand->priority_before ?? '') !!}
            </div>
            <div class="col-lg-6 d-none d-xl-flex form-table-cell"></div>
        </div>
    </div>


    @if(!isset($copy) && !isset($cross))
        <div class="row mx-0 normal_info" style="display: none">
            <div class="col-12 row m-0 p-0">
                <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.bid_deadline')</label>
                <div class="col-lg-3 d-flex align-items-center py-2 form-table-cell">
                    @if (isset($demand->auction_deadline_time_format))
                        <span> {{ $demand->auction_deadline_time_format }}</span>
                    @endif
                    {!! Form::hidden('demandInfo[auction_deadline_time]', isset($demand->auction_deadline_time_format) ? $demand->auction_deadline_time_format : '', ['class' => 'form-control-plaintext ignore', 'readonly']) !!}
                </div>
                <div class="col-lg-6 d-none d-xl-flex form-table-cell"></div>
            </div>
        </div>
    @endif

    <div class="row mx-0 normal_info" style="display: none">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.follow_up_date_time')</label>
            <div class="col-lg-3 d-flex align-items-center py-2 form-table-cell">
                @if (isset($demand->follow_tel_date_format))
                    <span>{{$demand->follow_tel_date_format}}</span>
                @endif
                {!! Form::hidden('demandInfo[follow_tel_date_format]', isset($demand->follow_tel_date_format) ? $demand->follow_tel_date_format : '', ['class' => 'form-control-plaintext ignore', 'readonly']) !!}
            </div>
            <div class="col-lg-6 d-none d-xl-flex form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0 normal_info" style="display: none">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell" for="demandInfo[follow]">@lang('demand_detail.followed')</label>
            <div class="col-lg-3 d-flex align-items-center custom-control custom-checkbox p-3 form-table-cell">
                {!! Form::checkbox('demandInfo[follow]', 1, $demand->follow == 1, ['id' => 'demandInfo[follow]', 'class' => 'custom-control-input']) !!}
                <label class="custom-control-label custome-label" for="demandInfo[follow]"></label>
            </div>
            <div class="col-lg-6 d-none d-xl-flex form-table-cell"></div>
        </div>
    </div>
    @if(isset($demand->auctionInfo) && count($demand->auctionInfo) > 0 && !isset($copy) && !isset($cross))
        <div class="row mx-0">
            <div class="col-12 p-20 text-right" style="padding: 20px">
                <button data-url_data="{{ route('demand.auction_detail', $demand->id) }}" class="btn btn--gradient-red"
                        type="button" data-toggle="modal"
                        id="get-auction-detail">@lang('demand_detail.bidding_situation')</button>
            </div>
        </div>
    @endif
</div>
