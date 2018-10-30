@php

    $timeTxt[1] = old('visitTime')[1]['visit_time'];
    $timeTxt[2] = old('visitTime')[2]['visit_time'];
    $timeTxt[3] = old('visitTime')[3]['visit_time'];
    $timeTxt[4] = '';
    $orderFailDate = old('demandInfo')['order_fail_date'];
    $orderFailDateInvalid = false;
    $invalidFlowDate = false;
    $invalidDemandDesertTime = '';
    if (old('demandInfo')['order_fail_date'] && strtotime(old('demandInfo')['order_fail_date']) === false ) {
        $orderFailDateInvalid = true;
        $orderFailDate = '';
    }
    if (old('demandInfo')['follow_date'] && strtotime(old('demandInfo')['follow_date']) === false ) {
        $invalidFlowDate = true;
    }
    if (isset(old('demandInfo')['contact_estimated_time_from']) && !empty(old('demandInfo')['contact_estimated_time_from'])) {
        $invalidDemandEstimatedFrom = old('demandInfo')['contact_estimated_time_from'];
    }
    if (isset(old('demandInfo')['contact_estimated_time_to']) && !empty(old('demandInfo')['contact_estimated_time_to'])) {
        $invalidDemandEstimatedTo = old('demandInfo')['contact_estimated_time_to'];
    }
@endphp
<div class="form-table">
    <div class="row mx-0">
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.customer_name')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text("demandInfo[customer_name]", '', ['class' => 'form-control is-required', 'data-rules' => 'not-empty', 'maxlength' => 50]) !!}

                @if ($errors->has('demandInfo.customer_name'))
                    <label class="invalid-feedback d-block">{{ $errors->first('demandInfo.customer_name') }}</label>
                @endif
            </div>
        </div>
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.corporate_name')</label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text("demandInfo[customer_corp_name]", '', ['class' => 'form-control', 'id' => 'customer_corp_name']) !!}

                @if ($errors->has('demandInfo.customer_corp_name'))
                    <label
                            class="invalid-feedback d-block">{{ $errors->first('demandInfo.customer_corp_name') }}</label>
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
                        $tel = ($ctiDemand) ? $ctiDemand['customer_tel'] : '';
                        if(!empty(old('demandInfo')['customer_tel'])) {
                            $tel = old('demandInfo')['customer_tel'];
                            if (!is_numeric($tel)) {
                                $tel = '9999999999';
                            }
                        }
                    @endphp
                    <input class="d-inline-block form-control w-50 is-required" data-rules="not-empty,valid-customer" maxlength="11"
                           name="demandInfo[customer_tel]" type="text" value="{{ $tel }}">

                    @if(!empty(old('demandInfo')['customer_tel']) || $ctiDemand)
                        <a href="{{ checkDevice().$tel }}" class="text--orange ml-2 w-50">{{ $tel }}</a>
                    @endif
                    @if (Session::has('demand_errors.check_customer_tel'))
                        <label
                                class="invalid-feedback d-block">{{Session::get('demand_errors.check_customer_tel')}}</label>
                    @elseif ($errors->has('demandInfo.customer_tel'))
                        <label class="invalid-feedback d-block">{{$errors->first('demandInfo.customer_tel')}}</label>
                    @endif
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
                {!! Form::text("demandInfo[tel1]", '', ['class' => 'd-inline-block form-control w-50 is-required', 'data-rules' => 'not-empty,valid-number', 'maxlength' => 11]) !!}
                @if(!empty(old('demandInfo')['tel1']))
                    @if(!is_numeric(old('demandInfo')['tel1']))
                        <label
                                class="invalid-feedback d-block">{{ trans('demand.validation_error.numeric_error') }}</label>
                    @endif
                    <a href="{{ checkDevice().old('demandInfo')['tel1'] }}"
                        class="text--orange ml-2 w-50">{{ old('demandInfo')['tel1'] }}</a>
                @endif
                @if (Session::has('demand_errors.check_tel1'))
                    <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_tel1')}}</label>
                @elseif ($errors->has('demandInfo.tel1'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.tel1')}}</label>
                @endif
            </div>
        </div>
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.contact_second')</label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text("demandInfo[tel2]", !empty(old('demandInfo')['tel2']) ? old('demandInfo')['tel2'] : '' , ['class' => 'd-inline-block form-control w-50 is-required', 'data-rules' => 'valid-number', 'maxlength' => 11]) !!}

                @if ($errors->has('demandInfo.tel2'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.tel2')}}</label>
                @endif
                <a href="{{ checkDevice().old('demandInfo')['tel2'] }}" class="text--orange ml-2 w-50">
                    @if(!empty(old('demandInfo')['tel2']))
                        {{ old('demandInfo')['tel2'] }}
                    @endif
                </a>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.customer_email_address')</label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! Form::text("demandInfo[customer_mailaddress]", '', ['class' => 'form-control is-required', 'data-rules' => 'valid-email' ]) !!}

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
            <div class="col-lg-9 py-2 form-table-cell">
                <div class="form-group mb-lg-0">
                    {!! Form::text("demandInfo[postcode]", '', ['class' => 'form-control d-inline w-40 w-sm-15 is-required', 'id' => 'postcode', 'data-rules' => 'valid-number', 'data-error-container' => '#err-postcode', 'size' => 7, 'maxlength' => 7]) !!}
                    <button class="btn btn--gradient-default d-inline ml-lg-2"
                            id="search-address-by-zip">@lang('demand_detail.address_search')</button>
                    <p class="d-block d-xl-inline ml-lg-2 text-muted mt-2 mb-0 mt-xl-0">@lang('demand_detail.zip_code_msg')</p>
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
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.prefectures')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::select('demandInfo[address1]', $prefectureDiv, '', ['class' => 'form-control is-required', 'id' => 'address1', 'data-rules' => 'not-empty', 'maxlength' => 10]) !!}

                @if ($errors->has('demandInfo.address1'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.address1')}}</label>
                @endif
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.municipality')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::text('demandInfo[address2]', '', ['class' => 'form-control is-required', 'id' => 'address2', 'data-rules' => 'not-empty', 'maxlength' => 20]) !!}

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
                {!! Form::text('demandInfo[address3]', '', ['class' => 'form-control', 'id' => 'address3', 'maxlength' => 100]) !!}

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
                {!! Form::select('demandInfo[construction_class]', $mItemsDropDownList, '', ['class' => 'form-control is-required', 'data-rules' => 'not-empty']) !!}

                @if ($errors->has('demandInfo.construction_class'))
                    <label
                            class="invalid-feedback d-block">{{$errors->first('demandInfo.construction_class')}}</label>
                @endif
            </div>
        </div>
    </div>
    <div class="row mx-0 normal_info" id="selection-system-div"
         style="display: @if(!old('visitTime')) none @else block @endif;">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.selection_method')
                <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
            </label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! Form::select('demandInfo[selection_system]', $selectionSystemList, '', ['class' => 'form-control', 'id' => 'selection_system']) !!}
                {!! Form::hidden('demandInfo[selection_system_before]', '', ['id' => 'selection_system_before']) !!}
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
            <div class="col-lg-6 d-none d-lg-inline py-2 form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex d-lg-block align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.content_of_consultation')</label>
            <div class="col-lg-6 py-2 form-table-cell">
                {!! Form::textarea('demandInfo[contents]', '', ['class' => 'form-control text-justify', 'rows' => 10, 'id' => 'demand-content']) !!}

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
                            <strong>@lang('demand_detail.contact_deadline')</strong>
                        </label>
                        <button class="btn btn--gradient-default d-block"
                                id="plus-15-minus">@lang('demand_detail.15_minutes')</button>
                    </div>
                </div>
                <div class="col-lg-9 py-2 visitTimeDiv count-affiliation form-table-cell"
                     countAffiliation="{{route('ajax.affiliation.count')}}">
                    <div class="form-row align-items-center mb-2" data-group-required="true">
                        <div class="custom-control custom-radio mr-2">

                            <input value=0 name='demandInfo[is_contact_time_range_flg]' type='radio'
                                   class='custom-control-input range-time-0 absolute_time'
                                   id='demandInfo[is_contact_time_range_flg_0]'
                                   @if(old('demandInfo')['is_contact_time_range_flg'] == 0) checked="true" @endif />
                            <label class="custom-control-label"
                                   for="demandInfo[is_contact_time_range_flg_0]">@lang('demand_detail.specify_time')</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input value=1 name='demandInfo[is_contact_time_range_flg]' type='radio'
                                   class='custom-control-input range-time-1 range_time'
                                   id='demandInfo[is_contact_time_range_flg_1]'
                                   @if(old('demandInfo')['is_contact_time_range_flg'] == 1) checked="true" @endif />

                            <label class="custom-control-label"
                                   for="demandInfo[is_contact_time_range_flg_1]">@lang('demand_detail.time_adjustment_required')</label>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-2">
                        <div class="col-10 col-lg-3 mb-2">
                            <input class="form-control datetimepicker txt_absolute_time count is-required"
                                   id="contact_desired_time" data-rules="valid-date"
                                   name="demandInfo[contact_desired_time]" type="text"
                                   value="@if(isset(old('demandInfo')['contact_desired_time']) && old('demandInfo')['contact_desired_time']) {{ old('demandInfo')['contact_desired_time'] }} @endif"
                                   @if(!empty(old('demandInfo')['is_contact_time_range_flg']) || old('demandInfo')['is_contact_time_range_flg'] == 1) disabled="true" @endif />

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
                        <div class="col-5 col-lg-4 mb-2">
                            <div class="number-kameiten"
                                 data-apikey="{{env('GOOGLE_API_KEY')}}"></div>
                        </div>
                    </div>
                    <div class="form-row align-items-center mb-2 date-time-group">
                        <div class="col-5 col-lg-3 mb-2 date-time-item">

                            <input type='text' name='demandInfo[contact_desired_time_from]'
                                   class='form-control datetimepicker count txt_range_time is-required'
                                   data-rules='valid-date'
                                   @if(empty(old('demandInfo')['is_contact_time_range_flg']) || old('demandInfo')['is_contact_time_range_flg'] == 0) disabled="true"
                                   @endif
                                   value="@if(!empty(old('demandInfo')['is_contact_time_range_flg']) || old('demandInfo')['is_contact_time_range_flg'] == 1){{ old('demandInfo')['contact_desired_time_from'] }}@endif"
                            />

                            @if (Session::has('demand_errors.check_contact_desired_time4'))
                                <label
                                        class="invalid-feedback invalid-time d-block">{{Session::get ('demand_errors.check_contact_desired_time4')}}</label>
                            @elseif (Session::has('demand_errors.check_require_to'))
                                <label
                                        class="invalid-feedback invalid-time d-block">{{Session::get('demand_errors.check_require_to')}}</label>
                            @elseif ($errors->has('demandInfo.contact_desired_time_from'))
                                <label
                                        class="invalid-feedback invalid-time d-block">{{$errors->first('demandInfo.contact_desired_time_from')}}</label>
                            @endif
                        </div>
                        <div class="col-auto mb-2 text-center date-time-sup">〜</div>
                        <div class="col-5 col-lg-3 mb-2 date-time-item">

                            <input type='text' name='demandInfo[contact_desired_time_to]'
                                   class='form-control count datetimepicker txt_range_time is-required'
                                   data-rules='valid-date'
                                   @if(empty(old('demandInfo')['is_contact_time_range_flg']) || old('demandInfo')['is_contact_time_range_flg'] == 0) disabled="true"
                                   @endif

                                   value="@if(!empty(old('demandInfo')['is_contact_time_range_flg']) || old('demandInfo')['is_contact_time_range_flg'] == 1){{ old('demandInfo')['contact_desired_time_to'] }}@endif"
                            />

                            @if (Session::has('demand_errors.check_contact_desired_time5'))
                                <label
                                        class="invalid-feedback invalid-time d-block">{{Session::get('demand_errors.check_contact_desired_time5')}}</label>
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
                @include('demand.cyzen.estimated_time')
            </div>
        </div>
    </div>
    @php $label = [1 => '①', 2 => '②', 3 => '③'] @endphp
    @for($i = 1; $i < 4; $i++)
        {!! Form::hidden('visitTime[' . $i . '][visit_time_before]', '') !!}
        {!! Form::hidden('visitTime[' . $i . '][commit_flg]', '') !!}
        {!! Form::hidden('visitTime[' . $i . '][id]', '') !!}
        {!! Form::hidden('visitTime[' . $i . '][visit_time_before]', '') !!}
        <div class="row mx-0 normal_info" style="display: @if(!old('visitTime')) none @else block @endif;">
            <div class="col-12 row m-0 p-0 ">
                <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.desired_visit_date'){{ $label[$i] }}
                {{--<span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>--}}
                </label>
                <div class="col-lg-9 py-2 visitTimeDiv form-table-cell">
                    <div class="form-row align-items-center mb-2">
                        <div class="custom-control custom-radio mr-2">
                            <input type="radio" name='visitTime[{{ $i }}][is_visit_time_range_flg]' value=0
                                   class='custom-control-input absolute_time'
                                   id='visitTime[{{ $i }}][is_visit_time_range_flg_0]'
                                   @if(!isset(old('visitTime')[$i]['is_visit_time_range_flg']) || old('visitTime')[$i]['is_visit_time_range_flg'] == 0) checked="true" @endif
                            />

                            <label class="custom-control-label"
                                   for="{{'visitTime['. $i .'][is_visit_time_range_flg_0]'}}">@lang('demand_detail.specify_time')</label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input type="radio" name='visitTime[{{ $i }}][is_visit_time_range_flg]' value=1
                                   class='custom-control-input range_time'
                                   id='visitTime[{{ $i }}][is_visit_time_range_flg_1]'
                                   @if(isset(old('visitTime')[$i]['is_visit_time_range_flg']) && old('visitTime')[$i]['is_visit_time_range_flg'] == 1) checked="true" @endif
                            />


                            <label class="custom-control-label"
                                   for="{{ 'visitTime[' . $i . '][is_visit_time_range_flg_1]' }}">@lang('demand_detail.time_adjustment_required')</label>
                        </div>
                    </div>
                    <div class="form-row align-items-center">
                        <div class="col-12 col-lg-3 mb-2">
                            {!! Form::hidden('visitTime[' . $i . '][visit_time]', '') !!}

                            <input class="form-control datetimepicker txt_absolute_time is-required"
                                   name="visitTime[{{ $i }}][visit_time]" type="text"
                                   @if(isset(old('visitTime')[$i]['is_visit_time_range_flg']) && old('visitTime')[$i]['is_visit_time_range_flg'] == 1) disabled='true'
                                   @endif data-rules='valid-date'
                                   value="{{ $timeTxt[$i] }}"/>
                            @if($errors->get('visit_time') && isset($errors->get('visit_time')[$i]))
                                <label class="invalid-feedback d-block text-center">{{ $errors->get('visit_time')[$i] }}</label>
                            @endif
                        </div>
                    </div>
                    {{-- time range --}}
                    <div class="form-row mb-2 date-time-group">

                        <div class="col-5 col-lg-3 mb-2 date-time-item">
                            {!! Form::hidden('visitTime[' . $i . '][visit_time_from]', '') !!}

                            <input type="text" name='visitTime[{{ $i }}][visit_time_from]'
                                   @if(!isset(old('visitTime')[$i]['is_visit_time_range_flg']) || old('visitTime')[$i]['is_visit_time_range_flg'] == 0) disabled="true"
                                   @endif
                                   value="@if(isset(old('visitTime')[$i]['visit_time_from'])) {{ old('visitTime')[$i]['visit_time_from'] }} @endif"
                                   class='form-control datetimepicker txt_range_time is-required'
                                   data-rules='valid-date'/>
                            @if($errors->get('visit_time_from') && isset($errors->get('visit_time_from')[$i]))
                                <label class="invalid-feedback invalid-time d-block text-center">{{ $errors->get('visit_time_from')[$i] }}</label>
                            @endif
                        </div>

                        <div class="col-auto my-2 text-center date-time-sup">〜</div>

                        <div class="col-5 col-lg-3 mb-2 date-time-item">
                            {!! Form::hidden('visitTime[' . $i . '][visit_time_to]', '') !!}

                            <input type="text" name='visitTime[{{ $i }}][visit_time_to]'
                                   @if(!isset(old('visitTime')[$i]['is_visit_time_range_flg']) || old('visitTime')[$i]['is_visit_time_range_flg'] == 0) disabled="true"
                                   @endif
                                   value='@if(isset(old('visitTime')[$i]['visit_time_to'])) {{ old('visitTime')[$i]['visit_time_to'] }} @endif'
                                   class='form-control datetimepicker txt_range_time is-required'
                                   data-rules='valid-date'/>

                            @if($errors->get('visit_time_to') && isset($errors->get('visit_time_to')[$i]))
                                <label class="invalid-feedback invalid-time d-block text-center">{{ $errors->get('visit_time_to')[$i] }}</label>
                            @endif

                        </div>
                        <div class="col-auto my-2 text-center date-time-sup-d">@lang('demand_detail.visit_adjustment')</div>

                        <div class="col-lg-3 mb-2 date-time-item">
                            {!! Form::hidden('visitTime[' . $i . '][visit_adjust_time]', '') !!}

                            <input type="text" name='visitTime[{{ $i }}][visit_adjust_time]'
                                   @if(!isset(old('visitTime')[$i]['is_visit_time_range_flg']) || old('visitTime')[$i]['is_visit_time_range_flg'] == 0) disabled="true"
                                   @endif
                                   value='@if(isset(old('visitTime')[$i]['visit_adjust_time'])) {{ old('visitTime')[$i]['visit_adjust_time'] }} @endif'
                                   class='form-control datetimepicker txt_range_time is-required'
                                   data-rules='valid-date'/>
                            @if($errors->get('adjust_time') && isset($errors->get('adjust_time')[$i]))
                                <label class="invalid-feedback invalid-time-from-to d-block">{{ $errors->get('adjust_time')[$i] }}</label>
                            @endif
                        </div>
                    </div>
                    @if($i != 1) <p class="text--info mb-0">@lang('demand_detail.visit_time_complete')</p>@endif
                </div>
            </div>
        </div>
    @endfor

    <div class="row mx-0 normal_info"
         style="{{ old('demandInfo')['category_id'] ? '' : 'display: none;' }}">
        <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">
            @lang('demand_detail.business_trip_cost')
        </label>
        <div class="col-12 col-lg-9 py-2 form-table-cell">
            <div class="form-row align-items-center">
                <div class="col-5 col-lg-3">
                    {!! Form::text('demandInfo[business_trip_amount]', '', ['class' => 'form-control', 'id' => 'business-trip-mount']) !!}
                </div>
                <span class="col-auto text-center"> @lang('demand_detail.circle') </span>

                @if ($errors->has('demandInfo.business_trip_amount'))
                    <label
                            class="invalid-feedback d-block">{{$errors->first('demandInfo.business_trip_amount')}}</label>
                @endif
            </div>
        </div>
    </div>
    <div class="row mx-0 normal_info"
         style="{{ old('demandInfo')['category_id'] ? '' : 'display: none;' }}">
        <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">
            @lang('demand_detail.service_price_offer')
        </label>
        <div class="col-12 col-lg-9 py-2 form-table-cell">
            <div class="form-row align-items-center">
                <div class="col-4 col-lg-3">
                    {!! Form::text('demandInfo[cost_from]', '', ['class' => 'form-control']) !!}

                    @if ($errors->has('demandInfo.cost_from'))
                        <label class="invalid-feedback d-block">{{$errors->first('demandInfo.cost_from')}}</label>
                    @endif
                </div>
                <span class="col-auto text-center"> @lang('demand_detail.circle') </span>
                <span class="col-auto text-center"> 〜</span>
                <div class="col-4 col-lg-3">
                    {!! Form::text('demandInfo[cost_to]', '', ['class' => 'form-control']) !!}

                    @if ($errors->has('demandInfo.cost_to'))
                        <label class="invalid-feedback d-block">{{$errors->first('demandInfo.cost_to')}}</label>
                    @endif
                </div>
                <span class="col-auto text-center"> @lang('demand_detail.circle')</span>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.follow_up_date')</label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! Form::text('demandInfo[follow_date]', '', ['class' => 'form-control datetimepicker is-required', 'data-rules' => 'valid-date']) !!}
                @if (Session::has('demand_errors.follow_date'))
                    <label class="invalid-feedback d-block">{{Session::get('demand_errors.follow_date')}}</label>
                @elseif($invalidFlowDate == true)
                    <label
                            class="invalid-feedback d-block">{{ trans('demand.validation_error.date_error') }}</label>
                @endif
            </div>
            <div class="col-lg-6 d-none d-xl-flex form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.reception_date_time')</label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! Form::text('demandInfo[receive_datetime]', dateTimeNowFormat(), ['class' => 'form-control datetimepicker is-required', 'data-rules' => 'valid-date']) !!}

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
                {!! Form::select('demandInfo[priority]', $priorityDropDownList, '', ['class' => 'form-control', 'disabled' => Auth::user()->auth != 'system']) !!}
                {!! Form::hidden('demandInfo[priority_before]', '') !!}
            </div>
            <div class="d-none d-lg-flex col-lg-6 py-2 form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0 normal_info" style="display: none;">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.follow_up_date_time')</label>
            <div class="col-lg-3 py-2 form-table-cell">
                {!! isset($demand) && isset($demand->follow_tel_date_format) ? $demand->follow_tel_date_format : '' !!}
                {!! Form::hidden('demandInfo[follow_tel_date]', '') !!}
            </div>
            <div class="d-none d-lg-flex col-lg-6 py-2 form-table-cell"></div>
        </div>
    </div>
    <div class="row mx-0 normal_info" style="display: none">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell" for="demandInfo[follow]">@lang('demand_detail.followed')</label>
            <div class="col-lg-3 d-flex align-items-center custom-control custom-checkbox p-3 form-table-cell">
                {!! Form::checkbox('demandInfo[follow]', 1, false, ['id' => 'demandInfo[follow]', 'class' => 'custom-control-input']) !!}
                <label class="custom-control-label custome-label" for="demandInfo[follow]"></label>
            </div>
            <div class="d-none d-lg-flex col-lg-6 py-2 form-table-cell"></div>
        </div>
    </div>
    {!! Form::hidden('demandInfo[lat]', '', ['class' => 'form-control', 'id' => 'latitude', 'data-rules' => 'not-empty', 'maxlength' => 20]) !!}
    {!! Form::hidden('demandInfo[lng]', '', ['class' => 'form-control', 'id' => 'longitude', 'data-rules' => 'not-empty', 'maxlength' => 20]) !!}
    <div id="page-data"
         data-date-picker-on-select="CountAff.onSelect">
    </div>
    <div id="count-data"
         data-count-aff="CountAff.ajaxCountAff">
    </div>
</div>
