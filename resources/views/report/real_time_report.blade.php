@extends('layouts.app')

@section('content')
    <div class="report-real-time-report">
        <label class="form-category__label">@lang('report_real_time.real_time_report')</label>
        <form class="fieldset-custom">
            <fieldset class="form-group">
                <legend class="col-form-label">@lang('report_real_time.search_condition')</legend>
                <div class="box--bg-gray box--border-gray p-2">
                    <button type="button" id="reloadButton" class="btn btn--gradient-orange col-auto">@lang('report_real_time.search_button')</button>
                </div>
            </fieldset>
            @if(isset($results) && count($results) > 0)
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item bg-yellow-light">@lang('report_real_time.today_commission_num')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-left">{{ $results['today_commission_num'] }} @lang('report_real_time.item')</div>
                </div>
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top bg-yellow-light">@lang('report_real_time.today_demand_num')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top border-left">{{ $results['today_demand_num'] }} @lang('report_real_time.item')</div>
                </div>
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top bg-yellow-light">@lang('report_real_time.uncounted')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top border-left">{{ $results['uncounted'] }} @lang('report_real_time.item')</div>
                </div>
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top bg-yellow-light">@lang('report_real_time.bidding')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top border-left">{{ $results['bidding'] }} @lang('report_real_time.item')</div>
                </div>
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top bg-yellow-light">@lang('report_real_time.missing_mail')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top border-left">{{ $results['missing_mail'] }} @lang('report_real_time.item')</div>
                </div>
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top bg-yellow-light">@lang('report_real_time.follow_date')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top border-left">{{ $results['follow_date'] }} @lang('report_real_time.item')</div>
                </div>
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top bg-yellow-light">@lang('report_real_time.selection_waiting')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top border-left">{{ $results['selection_waiting'] }} @lang('report_real_time.item')</div>
                </div>
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top bg-yellow-light">@lang('report_real_time.possible_call')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top border-left">{{ $results['possible_call'] }} @lang('report_real_time.item')</div>
                </div>
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top bg-yellow-light">@lang('report_real_time.call_hear_num')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top border-left">{{ $results['call_hear_num'] }} @lang('report_real_time.item')</div>
                </div>
                <div class="row mx-0 custom-border">
                    <div class="col-7 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top bg-yellow-light">@lang('report_real_time.call_loss_num')</div>
                    <div class="col-5 col-sm-3 col-md-2 py-1 d-flex align-items-center item border-top border-left">{{ $results['call_loss_num'] }} @lang('report_real_time.item')</div>
                </div>
            @endif
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ mix('js/pages/report.real_time.js') }}"></script>
@endsection
