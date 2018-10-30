@extends('layouts.app')

@section('content')
    <div class="report-corp-agreement-category">
        <label class="font-weight-bold my-3 fs-15">{{ trans('report_corp_agreement_category.merchant_contract_category_history') }}</label>
        {{ Form::open(array('enctype' => 'multipart/form-data', 'type'=>'post','class'=>'fieldset-custom', 'route'=>array('report.export.csv.corp.agreement.category') , 'accept-charset'=>"UTF-8" )) }}
            <fieldset class="form-group">
                <legend class="col-form-label fs-13">{{ trans('report_corp_agreement_category.search_condition') }}</legend>
                <div class="box--bg-gray box--border-gray p-2">
                    <a class="btn btn--gradient-orange col-6 col-sm-3 col-xl-1 mb-2 mb-sm-0" href="{{ route('report.corp.agreement.category') }}">{{ trans('report_corp_agreement_category.list_display') }}</a>
                    {{ Form::input('submit', 'csv_out', trans('report_corp_agreement_category.csv_output'), array('id'=>'csv_out' ,'class'=>'btn btn--gradient-orange col-6 col-sm-3 col-xl-1')) }}
                </div>
            </fieldset>
        {{ Form::close() }}
        <div class="custom-scroll-x ajax-table" data-url="{{ route('report.corp.agreement.category.ajax') }}">
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/corp_agreement_category.js') }}"></script>\
    <script>
        $(document).ready(function () {
            CorpAgreementCategory.init();
        });
    </script>
@endsection