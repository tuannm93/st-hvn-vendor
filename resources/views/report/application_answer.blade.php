@extends('layouts.app')

@section('content')
    <div class="report-application-answer pt-2">
        <h5 class="font-weight-bold pb-1">{{ trans('application_answer.application_report') }}</h5>
        <div class="row">
            {{ Form::open(['class'=>'form-horizontal fieldset-custom container', 'id'=>'form-app-answer', 'enctype' => 'multipart/form-data', 'type'=>'post', 'route'=>['report.application_answer'], 'accept-charset'=>"UTF-8" ]) }}
            <fieldset>
                <legend>{{ trans('application_answer.search_condition') }}</legend>
                <div class="form-search">
                    <div class="form-group form-inline mb-0">
                        <a class="btn btn--gradient-orange mr-3 btn--decoration-none remove-effect-btn" href="{{ route('report.application_answer') }}">{{ trans('application_answer.list_view') }}</a>
                        {{ Form::input('submit', 'csv', trans('application_answer.csv_output'), ['class' => 'btn btn--gradient-orange remove-effect-btn']) }}
                    </div>
                </div>
            </fieldset>
            {{ Form::close() }}
        </div>
        <div>
            <div class="table-responsive" data-url="{{ route('report.application_answer_ajax') }}">
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/application_answer.js') }}"></script>\
    <script>
        $(document).ready(function () {
            ApplicationAnswer.init();
        });
    </script>
@endsection
