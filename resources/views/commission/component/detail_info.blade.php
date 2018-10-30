@php
use App\Services\Auction\AuctionService;
@endphp
<div class="form-category mb-4">
    {{Form::input('hidden', 'data[CommissionInfo][introduction_free]', isset($results['CommissionInfo__introduction_free']) ? $results['CommissionInfo__introduction_free'] : '0', ['id' => 'introduction_free'])}}
    {{Form::input('hidden', 'data[CommissionInfo][first_commission]', isset($results['CommissionInfo__first_commission']) ? $results['CommissionInfo__first_commission'] : '0', ['id' => 'first_commission'])}}
    <label class="form-category__label d-none d-md-block">{!! trans('commission_detail.agency_infor') !!}</label>
    <div class="ml-lg-4">
        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold">
                    <span class="border-left-orange-mobi pl-2 pl-md-0">{!! trans('commission_detail.demand_id') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-5 d-flex align-items-center">
                @if ($auth != 'affiliation')
                    <a href="{{route('demand.detail', ['id' => $results['CommissionInfo__demand_id']])}}" target="_blank" class="link-primary text--underline">
                        {{$results['CommissionInfo__demand_id']}}
                    </a>
                @else
                    {{$results['CommissionInfo__demand_id']}}
                @endif
                {{Form::input('hidden', 'data[CommissionInfo][demand_id]', $results['CommissionInfo__demand_id'], ['id' => 'demand_id'])}}
            </div>
        </div>
        @if ($auth != 'affiliation')
        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold">
                    <span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.official_corp_name') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3 d-flex align-items-center">
            <a href="{{route('affiliation.detail.edit', ['id' => $results['MCorp__id']])}}" class="link-primary text--underline">{{$results['MCorp__official_corp_name']}}</a>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold">
                    <span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.commission_dial') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3 d-flex align-items-center">
                <a href="{{checkDevice().$results['MCorp__commission_dial']}}" class="link-primary text--underline">
                    {{$results['MCorp__commission_dial']}}
                </a>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.progress_check_tel') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3 d-flex align-items-center">
                <a href="{{checkDevice().$results['MCorp__progress_check_tel']}}" class="link-primary text--underline">
                    {{$results['MCorp__progress_check_tel']}}
                </a>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.appointers') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                {{ Form::select(
                    'data[CommissionInfo][appointers]',
                    ['' => trans('common.none')] + $user_list,
                    isset($results['CommissionInfo__appointers']) ? $results['CommissionInfo__appointers'] : '',
                    [
                        'id' => 'appointers',
                        'class' => 'form-control'
                    ]
                ) }}
                @if ($errors->has('appointers'))
                <label class="invalid-feedback d-block">{{$errors->first('appointers')}}</label>
                @endif
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.first_commission') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                <div class="custom-control custom-checkbox mr-sm-2">
                    {{ Form::checkbox('data[CommissionInfo][first_commission]', 1, isset($results['CommissionInfo__first_commission']) ? $results['CommissionInfo__first_commission'] : '', ['class' => 'custom-control-input', 'id' => 'first_commission']) }}
                    <label class="custom-control-label" for="first_commission"></label>
                    @if ($errors->has('first_commission'))
                    <label class="invalid-feedback d-block">{{$errors->first('first_commission')}}</label>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.attention') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8">
                {{ Form::textarea('data[CommissionInfo][attention]', $results['CommissionInfo__attention'], ['class' => 'form-control', 'id' => 'attention', 'rows' => 5]) }}
                @if ($errors->has('attention'))
                <label class="invalid-feedback d-block">{{$errors->first('attention')}}</label>
                @endif
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.commission_dial') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                {{Form::input('text', 'data[CommissionInfo][commission_dial]', $results['CommissionInfo__commission_dial'], ['id' => 'commission_dial', 'class' => 'form-control', 'maxlength' => 40, 'data-rule-numberHalfSize'=>'true'])}}
                @if ($errors->has('commission_dial'))
                <label class="invalid-feedback d-block">{{$errors->first('commission_dial')}}</label>
                @endif
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.tel_commission_datetime') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                {{Form::input('text', 'data[CommissionInfo][tel_commission_datetime]', dateTimeFormat($results['CommissionInfo__tel_commission_datetime']), ['id' => 'tel_commission_datetime', 'class' => 'form-control datetimepicker', 'maxlength' => 40])}}
                @if ($errors->has('tel_commission_datetime'))
                <label class="invalid-feedback d-block">{{$errors->first('tel_commission_datetime')}}</label>
                @endif
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.tel_commission_person') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                {{ Form::select(
                    'data[CommissionInfo][tel_commission_person]',
                    ['' => trans('common.none')] + $user_list,
                    isset($results['CommissionInfo__tel_commission_person']) ? $results['CommissionInfo__tel_commission_person'] : '',
                    [
                        'id' => 'tel_commission_person',
                        'class' => 'form-control'
                    ]
                ) }}
                @if ($errors->has('tel_commission_person'))
                <label class="invalid-feedback d-block">{{$errors->first('tel_commission_person')}}</label>
                @endif
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.commission_note_send_datetime') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                <!-- not format date yet -->
                {{Form::input('text', 'data[CommissionInfo][commission_note_send_datetime]', dateTimeFormat($results['CommissionInfo__commission_note_send_datetime']), ['id' => 'commission_note_send_datetime', 'class' => 'form-control datetimepicker'])}}
                @if ($errors->has('commission_note_send_datetime'))
                <label class="invalid-feedback d-block">{{$errors->first('commission_note_send_datetime')}}</label>
                @endif
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.commission_note_sender') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                {{ Form::select(
                    'data[CommissionInfo][commission_note_sender]',
                    ['' => trans('common.none')] + $user_list,
                    isset($results['CommissionInfo__commission_note_sender']) ? $results['CommissionInfo__commission_note_sender'] : '',
                    [
                        'id' => 'commission_note_sender',
                        'class' => 'form-control'
                    ]
                ) }}
                @if ($errors->has('commission_note_sender'))
                <label class="invalid-feedback d-block">{{$errors->first('commission_note_sender')}}</label>
                @endif
            </div>
        </div>
        @endif

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.commission_status') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                {{ Form::select(
                    'data[CommissionInfo][commission_status]',
                    $drop_list['commission_status'],
                    isset($results['CommissionInfo__commission_status']) ? $results['CommissionInfo__commission_status'] : '',
                    [
                        'id' => 'commission_status',
                        'class' => 'form-control value-change',
                        'disabled' => ($results['CommissionInfo__commission_type'] == $div_value['package_estimate'] || ($auth == 'affiliation' && $results['CommissionInfo__commission_status'] == $div_value['construction'])) ? true : false
                    ]
                ) }}

                <input type="hidden" id="commission_status_before" value="{{ isset($results['CommissionInfo__commission_status']) ? $results['CommissionInfo__commission_status'] : '' }}">
                <input type="hidden" id="order_fail_date_before" value="{{ isset($results['CommissionInfo__order_fail_date']) ? $results['CommissionInfo__order_fail_date'] : date('Y/m/d') }}">
                <input type="hidden" id="commission_order_fail_reason_before" value="{{ $results['CommissionInfo__commission_order_fail_reason'] }}">
                <input type="hidden" id="progress_report_datetime_before" value="{{ $results['CommissionInfo__progress_report_datetime'] }}">
                <input type="hidden" id="commission_type_check" value="{{ $results['CommissionInfo__commission_type'] == $div_value['normal_commission'] ? 1 : 0 }}">

                <input type="hidden" id="construction_div_value" value="{{ $div_value['construction'] }}">
                <input type="hidden" id="order_fail_div_value" value="{{ $div_value['order_fail'] }}">

                <input type="hidden" id="tel_correspond_status_div_value" value="{{ $div_value['tel_correspond_status'] }}">
                <input type="hidden" id="visit_correspond_status_div_value" value="{{ $div_value['visit_correspond_status'] }}">
                <input type="hidden" id="order_correspond_status_div_value" value="{{ $div_value['order_correspond_status'] }}">
                <input type="hidden" id="progression_div_value" value="{{ $div_value['progression'] }}">

                @if ($results['CommissionInfo__commission_type'] == $div_value['package_estimate'] || ($auth == 'affiliation' && $results['CommissionInfo__commission_status'] == $div_value['construction']))
                    {{Form::input('hidden', 'data[CommissionInfo][commission_status]', isset($results['CommissionInfo__commission_status']) ? $results['CommissionInfo__commission_status'] : '')}}
                @endif

                {{Form::input('hidden', 'data[CommissionInfo][commission_type]', $results['CommissionInfo__commission_type'], ['id' => 'commission_type'])}}
                {{Form::input('hidden', 'data[hidden_last_updated]', '', ['id' => 'hidden_last_updated'])}}
                {{Form::input('hidden', 'data[CommissionInfo][commission_status_last_updated]', $results['CommissionInfo__commission_status_last_updated'], ['id' => 'commission_status_last_updated'])}}
                {{Form::input('hidden', 'data[CommissionInfo][select_commission_unit_price_rank]', $results['CommissionInfo__select_commission_unit_price_rank'], ['id' => 'select_commission_unit_price_rank'])}}
                {{Form::input('hidden', 'data[CommissionInfo][select_commission_unit_price]', $results['CommissionInfo__select_commission_unit_price'], ['id' => 'select_commission_unit_price'])}}

                @if (strlen($results['CommissionInfo__commission_status_last_updated']) > 0)
                    {!! trans('commission_detail.last_modify_datetime') !!}<label>{{$results['CommissionInfo__commission_status_last_updated']}}</label>
                @endif
                @if ($errors->has('commission_status'))
                    <label class="invalid-feedback d-block">{{$errors->first('commission_status')}}</label>
                @endif
                @if (session('commission_errors.commission_status'))
                    <label class="invalid-feedback d-block">{{session('commission_errors.commission_status')}}</label>
                @endif
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.order_date') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                {{Form::input('text', 'data[DemandInfo][order_date]', $results['DemandInfo__order_date'], ['id' => 'order_date', 'class' => 'form-control datepicker'])}}
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.complete_date') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                @php
                    $complete_date_class = ($auth == 'accounting_admin' || $auth == 'accounting' || $auth == 'system') ? 'datepicker' : 'datepicker_limit';
                    $complete_date_disabled =  ($results['CommissionInfo__commission_type'] == $div_value['package_estimate'] || ($auth == 'affiliation' && $results['CommissionInfo__commission_status'] == $div_value['construction'])) ? true : false;
                @endphp

                {!! Form::input('text', 'data[CommissionInfo][complete_date]', $results['CommissionInfo__complete_date'], ['id' => 'complete_date', 'class' => $complete_date_class . ' form-control', 'disabled' => $complete_date_disabled]) !!}

                @if ($complete_date_disabled)
                    {{Form::input('hidden', 'data[CommissionInfo][complete_date]', $results['CommissionInfo__complete_date'], ['id' => 'complete_date'])}}
                @endif

                @if ($errors->has('complete_date'))
                <label class="invalid-feedback d-block">{{$errors->first('complete_date')}}</label>
                @endif
                @if (session('commission_errors.complete_date'))
                <label class="invalid-feedback d-block">{{session('commission_errors.complete_date')}}</label>
                @endif
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.order_fail_date') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                {{Form::input('text', 'data[CommissionInfo][order_fail_date]', $results['CommissionInfo__order_fail_date'], ['id' => 'order_fail_date', 'class' => 'form-control datepicker_limit'])}}
                @if ($errors->has('order_fail_date'))
                <label class="invalid-feedback d-block">{{$errors->first('order_fail_date')}}</label>
                @endif
                @if (session('commission_errors.order_fail_date'))
                <label class="invalid-feedback d-block">{{session('commission_errors.order_fail_date')}}</label>
                @endif
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.commission_order_fail_reason') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                {{ Form::select(
                    'data[CommissionInfo][commission_order_fail_reason]',
                    ['' => trans('common.none')] + $drop_list['commission_order_fail_reason'],
                    isset($results['CommissionInfo__commission_order_fail_reason']) ? $results['CommissionInfo__commission_order_fail_reason'] : '',
                    [
                        'id' => 'commission_order_fail_reason',
                        'disabled' => 'true',
                        'class' => 'form-control'
                    ]
                ) }}
                @if ($errors->has('commission_order_fail_reason'))
                <label class="invalid-feedback d-block">{{$errors->first('commission_order_fail_reason')}}</label>
                @endif
                @if (session('commission_errors.commission_order_fail_reason'))
                <label class="invalid-feedback d-block">{{session('commission_errors.commission_order_fail_reason')}}</label>
                @endif
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.estimate_price_tax_exclude') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3 d-flex pr-0">
                <div class="mb-2 w-100">
                    {{Form::input('text', 'data[CommissionInfo][estimate_price_tax_exclude]', $results['CommissionInfo__estimate_price_tax_exclude'], ['id' => 'estimate_price_tax_exclude', 'class' => 'form-control value-change', 'maxlength' => 40, 'data-rule-number' => 'true'])}}

                    @if ($errors->has('estimate_price_tax_exclude'))
                    <label class="invalid-feedback d-block">{{$errors->first('estimate_price_tax_exclude')}}</label>
                    @endif
                </div>
                <label class="ml-1 mt-2" for="estimate_price_tax_exclude">{!! trans('common.yen') !!}</label>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.construction_price_tax_exclude') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3">
                <div class="mb-2">
                    @if ($results['BillInfo__bill_status'] == $div_value['payment'])
                        @if (($auth == 'admin') || ($auth == 'system'))
                            {{Form::input('hidden', 'data[CommissionInfo][construction_price_tax_exclude]', '')}}
                            {{Form::input('text', 'data[CommissionInfo][construction_price_tax_exclude]', $results['CommissionInfo__construction_price_tax_exclude'], ['id' => 'construction_price_tax_exclude', 'class' => 'form-control value-change', 'maxlength' => 40, 'data-rule-number' => 'true'])}}
                            <label class="form-group__sub-label" for="construction_price_tax_exclude">{!! trans('common.yen') !!}</label>
                        @else
                            {{AuctionService::yenFormat2($results['CommissionInfo__construction_price_tax_exclude'])}}
                            {{Form::input('hidden', 'data[CommissionInfo][construction_price_tax_exclude]', $results['CommissionInfo__construction_price_tax_exclude'], ['id' => 'construction_price_tax_exclude', 'data-rule-number' => 'true'])}}
                        @endif
                    @else
                        @if ($auth == 'affiliation' && $results['CommissionInfo__commission_status'] == $div_value['construction'])
                            {{AuctionService::yenFormat2($results['CommissionInfo__construction_price_tax_exclude'])}}
                            {{Form::input('hidden', 'data[CommissionInfo][construction_price_tax_exclude]', $results['CommissionInfo__construction_price_tax_exclude'], ['id' => 'construction_price_tax_exclude', 'data-rule-number' => 'true'])}}
                        @else
                            {{Form::input('hidden', 'data[CommissionInfo][construction_price_tax_exclude]', '')}}
                            {{Form::input('text', 'data[CommissionInfo][construction_price_tax_exclude]', $results['CommissionInfo__construction_price_tax_exclude'], ['id' => 'construction_price_tax_exclude', 'class' => 'form-control value-change', 'maxlength' => 40, 'data-rule-number' => 'true'])}}
                            <label class="form-group__sub-label" for="construction_price_tax_exclude">{!! trans('common.yen') !!}</label>
                        @endif
                    @endif

                    @if ($errors->has('construction_price_tax_exclude'))
                    <label class="invalid-feedback d-block">{{$errors->first('construction_price_tax_exclude')}}</label>
                    @endif
                    @if (session('commission_errors.construction_price_tax_exclude'))
                    <label class="invalid-feedback d-block">{{session('commission_errors.construction_price_tax_exclude')}}</label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-5 col-sm-4 col-lg-2 pr-0">
                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.construction_price_tax_include') !!}</span>
                </div>
            </div>
            <div class="col-7 col-sm-8 col-lg-3 pr-0">
                <div class="mb-2">
                    @if ($auth == 'affiliation')
                        <span id="construction_price_tax_include_display">{{AuctionService::yenFormat2($results['CommissionInfo__construction_price_tax_include'])}}</span>
                        {{Form::input('hidden', 'data[CommissionInfo][construction_price_tax_include]', $results['CommissionInfo__construction_price_tax_include'], ['id' => 'construction_price_tax_include', 'data-rule-number' => "true"])}}
                    @else
                        <div class="d-flex">
                            <div class="w-100">
                                {{Form::input('text', 'data[CommissionInfo][construction_price_tax_include]', $results['CommissionInfo__construction_price_tax_include'], ['id' => 'construction_price_tax_include', 'class' => 'form-control', 'maxlength' => 40, 'data-rule-number' => "true"])}}
                            </div>
                            <label class="ml-1 mt-2" for="construction_price_tax_include">{!! trans('common.yen') !!}</label>
                        </div>
                    @endif

                    @if ($errors->has('construction_price_tax_include'))
                    <label class="invalid-feedback d-block">{{$errors->first('construction_price_tax_include')}}</label>
                    @endif
                    @if (session('commission_errors.construction_price_tax_include'))
                    <label class="invalid-feedback d-block">{{session('commission_errors.construction_price_tax_include')}}</label>
                    @endif
                </div>
            </div>
        </div>

        {{Form::input('hidden', 'data[CommissionInfo][business_trip_amount]', $results['CommissionInfo__business_trip_amount'] ? $results['CommissionInfo__business_trip_amount'] : '', ['id' => 'business_trip_amount', 'class' => 'value-change'])}}

        @if ($auth != 'affiliation')
            <div class="row mb-2">
                <div class="col-5 col-sm-4 col-lg-2 pr-0">
                    <div class="col-form-label font-weight-bold">
                        <span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.deduction_tax_include') !!}</span>
                    </div>
                </div>
                <p class="py-2 px-3 m-0 letter-spacing">
                    {{AuctionService::yenFormat2($results['CommissionInfo__deduction_tax_include'])}}
                    {{Form::input('hidden', 'data[CommissionInfo][deduction_tax_include]', $results['CommissionInfo__deduction_tax_include'], ['id' => 'deduction_tax_include'])}}
                </p>
            </div>
            <div class="row mb-2">
                <div class="col-5 col-sm-4 col-lg-2 pr-0">
                    <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.deduction_tax_exclude') !!}</span>
                </div>
                </div>
                <p class="py-2 px-3 m-0 letter-spacing">
                    {{AuctionService::yenFormat2($results['CommissionInfo__deduction_tax_exclude'])}}
                    {{Form::input('hidden', 'data[CommissionInfo][deduction_tax_exclude]', $results['CommissionInfo__deduction_tax_exclude'], ['id' => 'deduction_tax_exclude'])}}
                </p>
            </div>
        @else
            {{Form::input('hidden', 'data[CommissionInfo][deduction_tax_include]', $results['CommissionInfo__deduction_tax_include'], ['id' => 'deduction_tax_include'])}}
            {{Form::input('hidden', 'data[CommissionInfo][deduction_tax_exclude]', $results['CommissionInfo__deduction_tax_exclude'], ['id' => 'deduction_tax_exclude'])}}
        @endif

        @php
            $feeUnit = $results['CommissionInfo__order_fee_unit'];

            if(is_null($feeUnit) == true && is_null($results['MCorpCategory__order_fee_unit']) == false) {
                $feeUnit = $results['MCorpCategory__order_fee_unit'];
            }else if(is_null($feeUnit) == true) {
                $feeUnit = $category_default_fee['category_default_fee_unit'];
            }
        @endphp
        <div class="row mb-2">

            <div class="col-12 col-md-7">
                @if ($feeUnit !== 0 && $results['CommissionInfo__commission_status'] != $div_value['introduction'])
                    <div class="row mb-2">
                        <div class="col-5 col-sm-4 pr-0">
                            <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.commission_fee_rate') !!}</span>
                            </div>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                            @php
                                echo !empty($results['CommissionInfo__commission_fee_rate']) ? $results['CommissionInfo__commission_fee_rate'] . ' ％' : '';
                            @endphp
                            {{Form::input('hidden', 'data[CommissionInfo][commission_fee_rate]', $results['CommissionInfo__commission_fee_rate'], ['id' => 'commission_fee_rate'])}}
                            @if (session('commission_errors.commission_fee_rate'))
                            <label class="invalid-feedback d-block">{{session('commission_errors.commission_fee_rate')}}</label>
                            @endif
                        </div>
                    </div>
                    @if (($auth != 'affiliation') || ($auth == 'affiliation' && (!empty($results['CommissionInfo__irregular_fee_rate']) || $results['CommissionInfo__irregular_fee_rate'] > 0)))
                        <div class="row mb-2">
                            <div class="col-5 col-sm-4 pr-0">
                                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.irregular_fee_rate') !!}</span>
                                </div>
                            </div>
                            <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                                @php
                                    echo !empty($results['CommissionInfo__irregular_fee_rate'])? $results['CommissionInfo__irregular_fee_rate'].' ％' : '';
                                @endphp
                                {{Form::input('hidden', 'data[CommissionInfo][irregular_fee_rate]', $results['CommissionInfo__irregular_fee_rate'], ['id' => 'irregular_fee_rate'])}}
                            </div>
                        </div>
                    @else
                        {{Form::input('hidden', 'data[CommissionInfo][irregular_fee_rate]', $results['CommissionInfo__irregular_fee_rate'], ['id' => 'irregular_fee_rate'])}}
                    @endif

                    {{Form::input('hidden', 'data[BillInfo][id]', $results['BillInfo__id'])}}

                    @if (($auth != 'affiliation') || ($auth == 'affiliation' && (!empty($results['CommissionInfo__irregular_fee']) || $results['CommissionInfo__irregular_fee'] > 0)) )
                        <div class="row mb-2">
                            <div class="col-5 col-sm-4 pr-0">
                                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.irregular_fee') !!}</span>
                                </div>
                            </div>
                            <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                                @php
                                    echo !empty($results['CommissionInfo__irregular_fee']) ? AuctionService::yenFormat2($results['CommissionInfo__irregular_fee']) : '';
                                @endphp
                                {{Form::input('hidden', 'data[CommissionInfo][irregular_fee]', $results['CommissionInfo__irregular_fee'], ['id' => 'irregular_fee'])}}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 col-sm-4 pr-0">
                                <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.irregular_reason') !!}</span>
                                </div>
                            </div>
                            <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                                @php
                                    $ir = $drop_list['irregular_reason'];
                                    echo !empty($results['CommissionInfo__irregular_reason']) ? $ir[$results['CommissionInfo__irregular_reason']]:'';
                                @endphp
                            </div>
                        </div>
                    @else
                        {{Form::input('hidden', 'data[CommissionInfo][irregular_fee]', $results['CommissionInfo__irregular_fee'], ['id' => 'irregular_fee'])}}
                    @endif

                    <div class="row mb-2">
                        <div class="col-5 col-sm-4 pr-0">
                            <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.confirmd_fee_rate') !!}</span>
                            </div>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                            <span id ="confirmd_fee_rate_display">
                                {{ !empty($results['CommissionInfo__confirmd_fee_rate'])? $results['CommissionInfo__confirmd_fee_rate'].' ％' : '' }}
                            </span>
                            {{Form::input('hidden', 'data[CommissionInfo][confirmd_fee_rate]', $results['CommissionInfo__confirmd_fee_rate'], ['id' => 'confirmd_fee_rate'])}}
                            {{Form::input('hidden', 'data[CommissionInfo][corp_fee]', $results['CommissionInfo__corp_fee'], ['id' => 'corp_fee'])}}
                        </div>
                    </div>
                <!-- else feeUnit -->
                @else

                    @if (($auth != 'affiliation') || ($auth == 'affiliation' && (!empty($results['CommissionInfo__irregular_fee']) || $results['CommissionInfo__irregular_fee'] > 0)) )
                    <div class="row mb-2">
                        <div class="col-5 col-sm-4 pr-0">
                            <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.irregular_fee') !!}</span>
                            </div>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-4">
                            <div class="mb-2">
                                {{ !empty($results['CommissionInfo__irregular_fee']) ? AuctionService::yenFormat2($results['CommissionInfo__irregular_fee']) : '' }}
                                {{Form::input('hidden', 'data[CommissionInfo][irregular_fee]', $results['CommissionInfo__irregular_fee'], ['id' => 'irregular_fee'])}}
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 col-sm-4 pr-0">
                            <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.irregular_reason') !!}</span>
                            </div>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-4">
                            <div class="mb-2">
                                @php
                                    $ir = $drop_list['irregular_reason'];
                                @endphp
                                {{ !empty($results['CommissionInfo__irregular_reason']) ? $ir[$results['CommissionInfo__irregular_reason']] : '' }}
                            </div>
                        </div>
                    </div>
                    @else
                        {{Form::input('hidden', 'data[CommissionInfo][irregular_fee]', $results['CommissionInfo__irregular_fee'], ['id' => 'irregular_fee'])}}
                    @endif

                    <div class="row mb-2">
                        <div class="col-5 col-sm-4 pr-0">
                            <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.corp_fee') !!}</span>
                            </div>
                        </div>
                        <div class="col-7 col-sm-8 col-lg-4">
                            <div class="mb-2">
                                {{ !empty($results['CommissionInfo__corp_fee'])? AuctionService::yenFormat2($results['CommissionInfo__corp_fee']) : '' }}
                                {{Form::input('hidden', 'data[CommissionInfo][corp_fee]', $results['CommissionInfo__corp_fee'], ['id' => 'corp_fee'])}}
                                @if (session('commission_errors.corp_fee'))
                                <label class="invalid-feedback d-block">{{session('commission_errors.corp_fee')}}</label>
                                @endif
                            </div>
                        </div>
                    </div>

                <!-- end feeUnit -->
                @endif

                @if ($auth != 'affiliation')
                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.unit_price_calc_exclude') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[CommissionInfo][unit_price_calc_exclude]', 1, isset($results['CommissionInfo__unit_price_calc_exclude']) ? $results['CommissionInfo__unit_price_calc_exclude'] : '', ['class' => 'custom-control-input', 'id' => 'unit_price_calc_exclude']) }}
                            <label class="custom-control-label" for="unit_price_calc_exclude"></label>
                            @if ($errors->has('unit_price_calc_exclude'))
                            <label class="invalid-feedback d-block">{{$errors->first('unit_price_calc_exclude')}}</label>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.fee_target_price') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <span id ="fee_target_price_display" class="letter-spacing">
                            {{ !empty($results['BillInfo__fee_target_price']) ? AuctionService::yenFormat2($results['BillInfo__fee_target_price']) : '0' . trans('common.yen') }}
                        </span>
                        {{Form::input('hidden', 'data[BillInfo][fee_target_price]', $results['BillInfo__fee_target_price'], ['id' => 'fee_target_price'])}}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.fee_tax_exclude') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <span id="fee_tax_exclude_display" class="letter-spacing">
                            {{ !empty($results['BillInfo__fee_tax_exclude']) ? AuctionService::yenFormat2($results['BillInfo__fee_tax_exclude']) : '0' . trans('common.yen') }}
                        </span>
                        {{Form::input('hidden', 'data[BillInfo][fee_tax_exclude]', $results['BillInfo__fee_tax_exclude'], ['id' => 'fee_tax_exclude'])}}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.tax_rate') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <div class="form-group m-0" id="tax_rate_list">
                            {{ !empty($tax_rate)? $tax_rate.' %' : '' }}
                            {{Form::input('hidden', 'data[MTaxRate][tax_rate]', $tax_rate, ['id' => 'tax_rate'])}}
                            <br/>
                            <span>{{ !empty($results['BillInfo__tax'])? AuctionService::yenFormat2($results['BillInfo__tax']) : '' }} </span>&nbsp;
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.insurance_price') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <span id="insurance_price_display" class="letter-spacing">
                            {{ !empty($results['BillInfo__insurance_price'])? AuctionService::yenFormat2($results['BillInfo__insurance_price']) : '0' . trans('common.yen') }}
                        </span>
                        {{Form::input('hidden', 'data[BillInfo][insurance_price]', $results['BillInfo__insurance_price'], ['id' => 'insurance_price'])}}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.total_bill_price') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <span id="total_bill_price_display" class="letter-spacing">
                            {{ !empty($results['BillInfo__total_bill_price'])? AuctionService::yenFormat2($results['BillInfo__total_bill_price']) : '0' . trans('common.yen') }}
                        </span>
                        {{Form::input('hidden', 'data[BillInfo][total_bill_price]', $results['BillInfo__total_bill_price'], ['id' => 'total_bill_price'])}}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.ac_commission_exclusion_flg') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <span id="auction_total_bill_price_display" class="letter-spacing">
                            {{Form::input('hidden', 'data[CommissionInfo][ac_commission_exclusion_flg]', $results['CommissionInfo__ac_commission_exclusion_flg'], ['id' => 'ac_commission_exclusion_flg']) }}
                            @if ($results['CommissionInfo__ac_commission_exclusion_flg'])
                                   除外
                            @else
                                {{ !empty($results['AuctionBillInfo__total_bill_price'])? AuctionService::yenFormat2($results['AuctionBillInfo__total_bill_price']) : '0' . trans('common.yen') }}
                            @endif
                        </span>
                    </div>
                </div>

                @if ($auth != 'affiliation')
                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.introduction_free') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::input('hidden', 'data[CommissionInfo][introduction_free]', 0, ['id' => 'introduction_free_', 'disabled' => true]) }}
                            {{ Form::checkbox('data[CommissionInfo][introduction_free]', 1, isset($results['CommissionInfo__introduction_free']) ? $results['CommissionInfo__introduction_free'] : '', ['class' => 'custom-control-input', 'id' => 'introduction_free', 'disabled' => true]) }}
                            <label class="custom-control-label" for="introduction_free"></label>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.riro_kureka') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            @if($auth != 'affiliation')
                                {{ Form::input('hidden', 'data[DemandInfo][riro_kureka]', 0, ['id' => 'riro_kureka_']) }}
                                {{ Form::checkbox('data[DemandInfo][riro_kureka]', 1, isset($results['DemandInfo__riro_kureka']) ? $results['DemandInfo__riro_kureka'] : '', ['class' => 'custom-control-input', 'id' => 'riro_kureka']) }}
                            @else
                                {{ Form::input('hidden', 'data[DemandInfo][riro_kureka]', isset($results['DemandInfo__riro_kureka']) ? $results['DemandInfo__riro_kureka'] : '', ['id' => 'riro_kureka_']) }}
                                {{ Form::checkbox('data[DemandInfo][riro_kureka]', 1, isset($results['DemandInfo__riro_kureka']) ? $results['DemandInfo__riro_kureka'] : '', ['class' => 'custom-control-input', 'id' => 'riro_kureka', 'disabled' => true]) }}
                            @endif
                            <label class="custom-control-label" for="riro_kureka"></label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.fee_billing_date') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        {{ $results['BillInfo__fee_billing_date'] }}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.report_note') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8">
                        {{ Form::textarea('data[CommissionInfo][report_note]', $results['CommissionInfo__report_note'], ['class' => 'form-control', 'id' => 'report_note', 'rows' => 5]) }}
                        @if ($errors->has('report_note'))
                        <label class="invalid-feedback d-block">{{$errors->first('report_note')}}</label>
                        @endif
                    </div>
                </div>

                @if($auth != 'affiliation')
                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.progress_reported') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4 d-flex align-items-center">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::input('hidden', 'data[CommissionInfo][progress_reported]', 0, ['id' => 'progress_reported_'])}}
                            {{ Form::checkbox('data[CommissionInfo][progress_reported]', 1, isset($results['CommissionInfo__progress_reported']) ? $results['CommissionInfo__progress_reported'] : '', ['class' => 'custom-control-input', 'id' => 'progress_reported']) }}
                            <label class="custom-control-label" for="progress_reported"></label>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold">
                            <span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.progress_report_datetime') !!}</span>
                        </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4">
                    {{Form::input('text', 'data[CommissionInfo][progress_report_datetime]', dateTimeFormat($results['CommissionInfo__progress_report_datetime']), ['id' => 'progress_report_datetime', 'class' => 'form-control datetimepicker'])}}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.re_commission_exclusion_status') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4">
                        {{ Form::select(
                                'data[CommissionInfo][re_commission_exclusion_status]',
                                [0 => '', 1 => '成功', 2 => '失敗'],
                                isset($results['CommissionInfo__re_commission_exclusion_status']) ? $results['CommissionInfo__re_commission_exclusion_status'] : '',
                                [
                                    'id' => 're_commission_exclusion_status',
                                    'class' => 'form-control'
                                ]
                            ) }}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.re_commission_exclusion_user_id') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4">
                        {{Form::input('text', 'data[CommissionInfo][re_commission_exclusion_user_id]', $results['CommissionInfo__re_commission_exclusion_user_id'], ['id' => 're_commission_exclusion_user_id', 'class' => 'form-control'])}}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.re_commission_exclusion_datetime') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4">
                        {{Form::input('text', 'data[CommissionInfo][re_commission_exclusion_datetime]', dateTimeFormat($results['CommissionInfo__re_commission_exclusion_datetime']), ['id' => 're_commission_exclusion_datetime', 'class' => 'form-control datetimepicker'])}}
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-5 col-sm-4 pr-0">
                        <div class="col-form-label font-weight-bold"><span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.reform_upsell_ic') !!}</span>
                            </div>
                    </div>
                    <div class="col-7 col-sm-8 col-lg-4">
                            {{ Form::select(
                                'data[CommissionInfo][reform_upsell_ic]',
                                ['' => trans('common.none')] + $drop_list['reform_upsell_ic'],
                                isset($results['CommissionInfo__reform_upsell_ic']) ? $results['CommissionInfo__reform_upsell_ic'] : '',
                                [
                                    'id' => 'reform_upsell_ic',
                                    'class' => 'form-control'
                                ]
                            ) }}
                    </div>
                </div>
                @else
                    {{Form::input('hidden', 'data[CommissionInfo][progress_reported]', $results['CommissionInfo__progress_reported'], ['id' => 'progress_reported'])}}
                    {{Form::input('hidden', 'data[CommissionInfo][progress_report_datetime]', $results['CommissionInfo__progress_report_datetime'], ['id' => 'progress_report_datetime'])}}
                @endif
            </div>

            <div class="col-12 col-md-5">
                <div class="col-form-label font-weight-bold">
                    <span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.commission_info') !!}</span>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <td class="p-1 align-middle bg-yellow w-30">{!! trans('commission_detail.order_fee_unit') !!}</td>
                        <td class="p-1 align-middle">
                            @if ($results['MCorpCategory__corp_commission_type'] != 2 )
                                {{$results['MCorpCategory__order_fee']}}
                                {{getDivTextJP('fee_div' ,$results['MCorpCategory__order_fee_unit'])}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1 align-middle bg-yellow">{!! trans('commission_detail.introduce_fee') !!}</td>
                        <td class="p-1 align-middle">
                            @if ($results['MCorpCategory__corp_commission_type'] == 2 )
                                {{AuctionService::yenFormat2($results['MCorpCategory__introduce_fee'])}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1 align-middle bg-yellow">{!! trans('commission_detail.note') !!}</td>
                        <td class="p-1 align-middle">{{nl2br($results['MCorpCategory__note'])}}</td>
                    </tr>
                </table>
                <a id="app"></a>
                @if ($auth != 'affiliation')
                    <button type="button" class="btn btn--gradient-green remove-effect-btn my-1" id="approval_app_btn">{!! trans('commission_detail.approval_app_btn') !!}</button>
                    @if(session('error_message'))
                        <p class='alert alert-danger'>
                            {{ session('error_message') }}
                        </p>
                    @endif
                    @if(session('application_message'))
                        <p class="alert alert-success">
                            {{ session('application_message') }}
                        </p>
                    @endif
                    @if(session('success_message'))
                        <p class="alert alert-success">
                            {{ session('success_message') }}
                        </p>
                    @endif
                    <div class="col-form-label font-weight-bold">
                        <span class="pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.application_his') !!}</span>
                    </div>
                    <div class="approval-data">
                        @foreach($applications as $application)
                            <table class="table table-bordered list_table mb-2">
                                <tr>
                                    <td>{{ $application['Approval__id'] }}</td>
                                    <td>{{ $application['Approval__application_datetime'] }}</td>
                                    <td>{{ $application['Approval__application_user_id'] }}</td>
                                    <td bgcolor="
                                                @php
                                        if($application['Approval__status'] == -1 || $application['Approval__status'] == -2)echo '#ffffc0';
                                        elseif($application['Approval__status'] == 1)echo '#81F79F';
                                        elseif($application['Approval__status'] == 2)echo '#F5A9A9';
                                        else echo '#fff';
                                    @endphp">{{ $application['Approval__status_disp'] }}</td>
                                </tr>
                                <tr>
                                    <td>{!! trans('commission_detail.target_item') !!}</td>
                                    <td colspan=3 align="center">
                                        @php
                                            if($application['CommissionApplication__chg_deduction_tax_include']){
                                                echo trans('commission_detail.tax_include');
                                                if(!empty($application['CommissionApplication__deduction_tax_include']))
                                                    echo $application['CommissionApplication__deduction_tax_include'] . trans('common.yen') . '<br/>';
                                                else
                                                    echo trans('commission_detail.empty_string') . '<br/>';
                                            }

                                            if($application['CommissionApplication__chg_irregular_fee_rate']){
                                                echo trans('commission_detail.irregular_fee_rate');
                                                if(!empty($application['CommissionApplication__irregular_fee_rate']))
                                                    echo $application['CommissionApplication__irregular_fee_rate'].'%<br/>';
                                                else
                                                    echo trans('commission_detail.empty_string') . '<br/>';
                                            }

                                            if($application['CommissionApplication__chg_irregular_fee']){
                                                echo trans('commission_detail.chg_irregular_fee');
                                                if(!empty($application['CommissionApplication__irregular_fee']))
                                                    echo $application['CommissionApplication__irregular_fee'] . trans('common.yen') . '<br/>';
                                                else
                                                    echo trans('commission_detail.empty_string') . '<br/>';
                                            }

                                            if($application['CommissionApplication__chg_irregular_fee_rate'] || $application['CommissionApplication__chg_irregular_fee']){
                                                echo trans('commission_detail.reason');
                                                $ir =  $drop_list['irregular_reason'];
                                                echo !empty($application['CommissionApplication__irregular_reason']) ? $ir[$application['CommissionApplication__irregular_reason']]:'';
                                                echo '<br/>';
                                            }

                                            if($application['CommissionApplication__chg_introduction_free']){
                                                if($application['CommissionApplication__introduction_free'] == 0)
                                                    echo trans('commission_detail.free_invalid') . '<br/>';
                                                elseif($application['CommissionApplication__introduction_free'] == 1)
                                                    echo trans('commission_detail.free_enfective') . '<br/>';
                                            }

                                            if($application['CommissionApplication__chg_ac_commission_exclusion_flg']){
                                                if($application['CommissionApplication__ac_commission_exclusion_flg'] == 0)
                                                    echo trans('commission_detail.fee_not_exclude') . '<br/>';
                                                elseif($application['CommissionApplication__ac_commission_exclusion_flg'] == 1)
                                                    echo trans('commission_detail.fee_exclude') . '<br/>';
                                            }

                                            if($application['CommissionApplication__chg_introduction_not']){
                                                if($application['CommissionApplication__introduction_not'] == 0)
                                                    echo trans('commission_detail.impossible') . '<br/>';
                                                elseif($application['CommissionApplication__introduction_not'] == 1)
                                                    echo trans('commission_detail.introduce_impossible') . '<br/>';
                                            }
                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <td>{!! trans('commission_detail.application_reason') !!}</td>
                                    <td colspan=3 align="center">
                                        {{ $application['Approval__application_reason'] }}
                                    </td>
                                </tr>
                            </table>
                            @if (($application['Approval__status'] == -1 || $application['Approval__status_disp'] == -2) && ($auth == 'system' || $auth == 'admin'))
                                @if ($application['Approval__application_user_id'] == $user_id)
                                    <div class="text-danger">
                                        <p>
                                            <strong>{!! trans('commission_detail.approve_app_text') !!}</strong>
                                        </p>
                                    </div>
                                @else
                                    <table class="table">
                                        <tr>
                                            <td class="border-0">
                                                <button class="btn btn-sm btn--gradient-green btnOver app_sm w-100" name="approval" id="{{$application['Approval__id']}}">{!! trans('commission_detail.approve_btn') !!}</button>
                                            </td>
                                            <td class="border-0">
                                                <button class="btn btn-sm btn--gradient-default btnOver app_sm w-100" name="rejected" id="{{$application['Approval__id']}}">{!! trans('commission_detail.reject_btn') !!}</button>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{Form::input('hidden', 'data[modified]', $results['CommissionInfo__modified'], ['id' => 'modified'])}}

    </div>
</div>
