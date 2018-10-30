@extends('layouts.app')

@section('content')

    <div class="report-jbr-receipt-follow">
        {{Form::open(['enctype' => 'multipart/form-data', 'type'=>'post', 'route'=>['report.getCsvListReceiptFollow'] , 'accept-charset'=>"UTF-8", 'class' => 'fieldset-custom', 'id' => 'search-form' ])}}
        @if(session()->has('error'))
            <div class="box__mess box--error">
                {!! session('error') !!}
            </div>
        @endif
        <label class="font-weight-bold fs-15 mt-2">@lang('report_jbr.label1')</label>
        <fieldset class="form-group">
            <legend class="col-form-label fs-13">@lang('report_jbr.label2')</legend>
            <div class="row mx-0 box--bg-gray box--border-gray p-2">
                <div class="col-md-4 col-xl-2 mb-1">
                    <label class="col-form-label">@lang('report_jbr.label3')</label>
                </div>
                <div class="col-md-8 mb-1 d-flex flex-column flex-sm-row">
                    <div class="col-xl-3 px-0">
                        <input class="datepicker form-control" data-rule-date="true" data-rule-pattern="\d{4}/\d{1,2}/\d{1,2}" data-msg-pattern="@lang('validation.invalid_date')" data-error-container="#invalid-date-from" type="text" id="from_date" name="from_date">
                        <div id="invalid-date-from"></div>
                    </div>
                    <span class="px-2 d-none d-sm-block fs-20">&#8275</span>
                    <div class="col-xl-3 px-0">
                        <input class="datepicker form-control" data-rule-date="true" data-rule-pattern="\d{4}/\d{1,2}/\d{1,2}" data-msg-pattern="@lang('validation.invalid_date')" data-error-container="#invalid-date-to" type="text" id="to_date" name="to_date">
                        <div id="invalid-date-to"></div>
                    </div>
                </div>
                <div class="col-12">
                    <button id="search" class="btn btn--gradient-orange remove-effect-btn col-sm-3 col-xl-1 mb-1">@lang('report_jbr.search')</button>
                    <button id="csv_out" class="btn btn--gradient-orange remove-effect-btn col-sm-3 col-xl-1 mb-1" name="csv_out" type="submit">@lang('report_jbr.csv_out')</button>
                </div>
            </div>
        </fieldset>
        {{Form::close()}}
        <div class="content-ajax">
            @include('report.components.component_jbr_receipt')
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/pages/jbr_receipt_follow.js') }}"></script>
    <script type="text/javascript">
        var urlGetJbrList = '{{route('report.getListReceiptFollow')}}';
        FormUtil.validate('#search-form');
    </script>
@endsection()
