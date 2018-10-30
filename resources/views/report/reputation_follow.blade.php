@extends('layouts.app')
@php
    $bHasMessage = Session::has(__('report_reputation_follow.KEY_REPORT_REPUTATION_FOLLOW_MESSAGE_UPDATE'));
    $content = Session::get(__('report_reputation_follow.KEY_REPORT_REPUTATION_FOLLOW_MESSAGE_UPDATE'));
    $message = $content[0];
    $classError = "box--success";
    if($content[1]){
        $classError = 'box--error';
    }
    $bEnableCsv = ($data['total'] == 0) ? false : true;
@endphp
@section('content')
    <input type="hidden" value="{{csrf_token()}}" id="csrf-token" name="token">
    <div class="report-reputation-follow">
        <label class="form-category__label mt-2">{{__('report_reputation_follow.report_after_check_rumor')}}</label>
        @if($bHasMessage)
            <div class="row justify-content-center box__mess {{$classError}}">
                {{$message}}
            </div>
        @endif
        <form class="fieldset-custom" method="get" action="{{route('report.reputation.follow.download')}}">
            <fieldset class="form-group">
                <legend class="col-form-label">{{__('report_reputation_follow.search_condition')}}</legend>
                <div class="box--bg-gray box--border-gray p-2">
                    <input type="submit" class="btn btn--gradient-orange col-sm-3 col-xl-1"
                           {{$bEnableCsv ? '': 'disabled'}}
                           value="{{__('report_reputation_follow.export_csv')}}"/>
                </div>
            </fieldset>
        </form>
        <div class="row mx-0">
            <div class="col-lg-6 px-0 d-flex align-items-center">
                <span>
                    {{__('report_reputation_follow.total') . ' ' . $data['total']. __('report_reputation_follow.row')}}
                </span>
            </div>
            @if($data['bAllowShowUpdate'])
                <div class="col-lg-6 px-0 text-lg-right">
                    <button class="btn btn--gradient-gray" id="btnSelectAll">
                        {{__('report_reputation_follow.check_all')}}
                    </button>
                    <button class="btn btn--gradient-gray" id="btnUpdateDateTime" disabled="disabled">
                        {{__('report_reputation_follow.update_date_time')}}
                    </button>
                </div>
            @endif
        </div>

        <div id="viewResult">
            @include('report.reputation_follow.reputation_follow_table')
        </div>
        <div id="page-data"
             data-text-selectall="{{__('report_reputation_follow.check_all')}}"
             data-text-unselectall="{{__('report_reputation_follow.uncheck_all')}}"
             data-url-search="{{route('report.reputation.follow.search')}}"
             data-url-exportcsv="{{route('report.reputation.follow.download')}}"
             data-url-update="{{route('report.reputation.follow.update')}}">
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/report.reputation.follow.js') }}"></script>
@endsection
