@extends('layouts.app')
@section('style')
@endsection
@section('content')
<section class="app-content container commission commission-detail my-4">
    {{Form::open(['enctype' => 'multipart/form-data', 'type'=>'post', 'route'=>['commission.regist', $id] , 'accept-charset'=>"UTF-8", 'id' => 'detail-commision'])}}
    <input name="data[DemandInfo][demand_status]" id="demand_status" value="{{$results['DemandInfo__demand_status']}}" type="hidden">
    <input name="data[DemandInfo][id]" value="{{$results['DemandInfo__id']}}" id="DemandInfoId" type="hidden">
    <input name="data[CommissionInfo][id]" id="commissioninfo_id" value="{{$results['CommissionInfo__id']}}" type="hidden">
    <input name="data[CommissionInfo][corp_id]" id="commissioninfo_id" value="{{$results['CommissionInfo__corp_id']}}" type="hidden">
    <input name="data[CommissionCorrespond][commission_id]" id="commissioninfo_id" value="{{$results['CommissionInfo__id']}}" type="hidden">
    <input name="data[visit_desired_time]" value="{{$results['CommissionInfo__visit_desired_time']}}" id="visit_desired_time" type="hidden">
    <input name="data[contact_desired_time]" value="{{$results['DemandInfo__contact_desired_time']}}" id="contact_desired_time" type="hidden">
    <input name="" value="{{$auth}}" id="user_auth" type="hidden">

    <div id="message">
        @if (Session::has('lock_status_invalid'))
            <p class="alert alert-danger">{{ Session::get('lock_status_invalid') }}</p>
        @endif
        @if (Session::has('success'))
            <p class="alert alert-success">{{ Session::get('success') }}</p>
        @endif
        @if (Session::has('error'))
            <p class="alert alert-danger">{{ Session::get('error') }}</p>
        @endif
        @if (Session::has('error_file'))
            <p class="alert alert-danger">{{ Session::get('error_file') }}</p>
        @endif
    </div>

    <div class="row">
        @if ($auth == 'affiliation')
            <div class="col-12 col-sm-3 col-md-2">

                <a class="btn btn--gradient-orange w-100 mb-2 animate" href="{{ route('affiliation.category', ['id' => $affiliation_id]) }}" target="_blank">
                    {{ trans('commission_detail.btn_regist_info') }}
                </a>

            </div>
            <div class="col-12 col-sm-9 col-md-10">
                <p class="box__mess text--orange font-weight-normal">{!! trans('commission_detail.indicate_block') !!}</p>
            </div>

        @endif
    </div>

    <div class="app-content container commission my-4">
        <div class="row header-field my-4">
            <div class="col-sm-6 col-md-4">
                <strong>{{ trans('commission_detail.deal_detail') }}</strong>
            </div>
        </div>

        {{--Category 1--}}

        <div class="form-category mb-4">
            <a class="collapse-trigger d-block d-md-none p-3" data-toggle="collapse" href="#commission-detail_1" role="button" aria-expanded="false" aria-controls="collapseExample">
                <strong>
                    {{ trans('commission_detail.cus_info') }}　{{ trans('commission_detail.case_status') }}：
                @php
                    $commissionStatusList = $drop_list['commission_status'];

                    if (!empty($results['CommissionInfo__commission_status'])) {
                        echo $commissionStatusList[$results['CommissionInfo__commission_status']];
                    }
                @endphp
                </strong>
                <span class="fa fa-caret-down float-right"></span>
            </a>
            <div class="collapse d-sm-block show" id="commission-detail_1">
            @component('commission.component.detail_cus_info', [
                'results' => $results,
                'auth' => $auth,
                'contact_desired_time_hope' => $contact_desired_time_hope,
                'visit_time_of_hope' => $visit_time_of_hope,
                'site_list' => $site_list,
                'div_value' => $div_value,
                'drop_list' => $drop_list,
                'visit_time' => $visit_time,
            ])
            @endcomponent

            @component('commission.component.detail_file_info', [
                'results' => $results,
                'demand_attached_files' => $demand_attached_files,
                'address_disclosure' => $address_disclosure,
                'tel_disclosure' => $tel_disclosure,
                'div_value' => $div_value,
            ])
            @endcomponent
            </div>

            <a class="collapse-trigger d-block d-md-none p-3 mt-4" data-toggle="collapse" href="#commission-detail_2" role="button" aria-expanded="false" aria-controls="collapseExample">
                <strong>{!! trans('commission_detail.support') !!}</strong>
                <span class="fa fa-caret-down float-right"></span>
            </a>
            <div class="collapse d-sm-block" id="commission-detail_2">
            @component('commission.component.detail_progress_info', [
                'results' => $results,
                'auth' => $auth,
                'contact_desired_time' => $contact_desired_time,
                'm_commission_alert_settings_tel' => $m_commission_alert_settings_tel,
                'm_commission_alert_settings_visit' => $m_commission_alert_settings_visit,
                'm_commission_alert_settings_order' => $m_commission_alert_settings_order,
                'visit_time_of_hope' => $visit_time_of_hope,
                'visit_time_display' => $visit_time_display,
                'div_value' => $div_value,
                'drop_list' => $drop_list,
                'visit_time' => $visit_time,
            ])
            @endcomponent
            </div>

            @component('commission.component.detail_application', [
                'commission_id' => $results['CommissionInfo__id'],
                'corp_id' => $results['CommissionInfo__corp_id'],
                'demand_id' => $results['CommissionInfo__demand_id'],
                'drop_list' => $drop_list,
            ])
            @endcomponent

        </div>

        <a class="collapse-trigger d-block d-md-none p-3 mt-4" data-toggle="collapse" href="#commission-detail_3" role="button" aria-expanded="false" aria-controls="collapseExample">
            <strong>{!! trans('commission_detail.agency_infor') !!}</strong>
            <span class="fa fa-caret-down float-right"></span>
        </a>
        <div class="collapse d-sm-block" id="commission-detail_3">
        @component('commission.component.detail_info', [
            'results' => $results,
            'auth' => $auth,
            'user_list' => $user_list,
            'applications' => $applications,
            'tax_rate' => $tax_rate,
            'user_id' => $user_id,
            'category_default_fee' => $category_default_fee,
            'div_value' => $div_value,
            'drop_list' => $drop_list,
        ])
        @endcomponent
        </div>

        <a class="collapse-trigger d-block d-md-none p-3 mt-4" data-toggle="collapse" href="#commission-detail_4" role="button" aria-expanded="false" aria-controls="collapseExample">
            <strong>{!! trans('commission_detail.jpr_info') !!}</strong>
            <span class="fa fa-caret-down float-right"></span>
        </a>
        <div class="collapse d-sm-block" id="commission-detail_4">
        @component('commission.component.detail_jpr_info', [
            'results' => $results,
            'estimate_file_url' => $file_url['estimate_file_url'],
            'receipt_file_url' => $file_url['receipt_file_url'],
            'drop_list' => $drop_list,
        ])
        @endcomponent
        </div>

        <a class="collapse-trigger d-block d-md-none p-3 mt-4" data-toggle="collapse" href="#commission-detail_5" role="button" aria-expanded="false" aria-controls="collapseExample">
            <strong>{!! trans('commission_detail.corresponding_his_info') !!}</strong>
            <span class="fa fa-caret-down float-right"></span>
        </a>
        <div class="collapse d-sm-block" id="commission-detail_5">
        @component('commission.component.detail_his_info', [
            'results' => $results,
            'auth' => $auth,
            'user_list' => $user_list,
            'user' => $user
        ])
        @endcomponent
        </div>

        {{Form::input('hidden', 'commt_flg', 1, ['id' => 'commt_flg'])}}

        <div class="text-center my-3">
            <a id="btn_back" class="btn btn--gradient-default col-3 col-lg-1" href="{{ route('commission.index', ['affiliationId' => 'none']) }}">{{ trans('commission_detail.btn_return') }}</a>
            @if (($results['CommissionInfo__lock_status'] != 1) or ($auth != 'affiliation'))
                <button id="regist" class="btn btn--gradient-green col-3 col-lg-1" type="submit">{{ trans('commission_detail.btn_register') }}</button>
            @endif
        </div>
        <div class="row justify-content-center">
            <div class="table-responsive">
                <div class="table-responsive-sm">
                    <table class="table table-list table-bordered">
                        <thead class="bg-primary-lighter">
                            <tr>
                                <th align="center" style="width:10%;">{{ trans('commission_detail.corressponding_no') }}</th>
                                <th align="center">{{ trans('commission_detail.corressponding_person') }}</th>
                                <th align="center">{{ trans('commission_detail.corressponding_datetime') }}</th>
                            </tr>
                            <tr>
                                <th align="center" colspan="3">{{ trans('commission_detail.corressponding_content') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history_list as $key => $val)
                                @php
                                    $his = (array) $val;
                                @endphp
                                <tr>
                                    <td align="center">
                                        @if ($auth != 'affiliation' && !isset($his['CommissionCorrespond__rits_responders']))
                                            {{ count($history_list) - $key }}
                                        @else
                                            <a href="javascript:void(0)" class="history-input text--orange" history_id="{{ $his['CommissionCorrespond__id'] }}">{{ count($history_list) - $key}}</a>
                                        @endif
                                    </td>
                                    <td align="center">@php echo isset($his['MUser__user_name']) ? $his['MUser__user_name'] : $his['CommissionCorrespond__responders']; @endphp</td>
                                    <td align="center">{{ dateTimeFormat($his['CommissionCorrespond__correspond_datetime']) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">@php echo nl2br($his['CommissionCorrespond__corresponding_contens']) @endphp</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{Form::close()}}

    {{Form::open(['type'=>'post', 'route' => ['commission.approval'] , 'accept-charset' => 'UTF-8', 'id' => 'approval_form', 'name' => 'approval_form' ])}}
        <input name="approval_id" id="id" type="hidden">
        <input name="action_name" id="action" type="hidden">
    {{Form::close()}}

</section>
@component('commission.component.detail_modal', [
    'results' => $results,
    'auth' => $auth,
    'site_list' => $site_list,
    'div_value' => $div_value,
])
@endcomponent

@if($results['CommissionInfo__commission_status'] != $div_value['introduction'])
<input type="hidden" id="commission_status_flg" value="1">
@endif
@if($results['MGenre__insurant_flg'] == 1 && $results['AffiliationInfo__liability_insurance'] == 2)
<input type="hidden" id="liability_insurance" value="1">
@endif
<input type="hidden" id="construction_status_val" value="{{ $div_value['order_fail'] }}">

@endsection

@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/pages/commission/commission_common.detail.js') }}"></script>
    <script src="{{ mix('js/pages/commission/commission.detail.js') }}"></script>
    <script src="{{ mix('js/pages/commission/commission_onchange.detail.js') }}"></script>
    <script src="{{ mix('js/pages/commission/commission.app.js') }}"></script>
    <script>
        Datetime.initForDatepicker();
        Datetime.initForDatepickerLimit();
        Datetime.initForDateTimepicker();
        FormUtil.validate('#detail-commision');
    </script>
@endsection
