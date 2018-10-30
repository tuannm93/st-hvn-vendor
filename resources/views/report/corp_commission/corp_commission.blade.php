@extends('layouts.app')
@section('style')
@endsection
@section('content')
    <div class="report-corp-commission">
        {{-- Form select order --}}
        @include('report.corp_commission.form_select_order')
        <br>
        {{-- Form select filter --}}
        @include('report.corp_commission.form_select_filter')
        <br>
        {{-- Table show data --}}
        <div class="searchResult">
            @include('report.corp_commission.show_report')
        </div>
        <div class="show-report-commission pseudo-scroll-bar" data-display="false">
            <div class="scroll-bar"></div>
        </div>
        @component('partials.confirm', [
            'text' =>  __('report_corp_commission.confirm_report_delete'),
            'formIdSubmit' => ''
        ]) @endcomponent
        <input type="hidden" value="{{csrf_token()}}" id="csrf-token" name="token">
        <div id="page-data"
             data-url-search="{{ route('report.search.corp.commission') }}"
             data-url-delete-report="{{ route('report.delete.corp.commission') }}"
             data-url-register-report="{{ route('report.register.corp.commission') }}"
             data-url-count-browser="{{ route('ajax.demand.count_browse_list') }}">
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/utilities/scroll.bar.js') }}"></script>
    <script src="{{ mix('js/pages/report.corp_commission.ajax.js') }}"></script>
    <script>
        var un_select = '@lang('report_corp_commission.select_anker')';
        var check_all = '@lang('report_corp_commission.check_all')';
        var un_check_all = '@lang('report_corp_commission.un_check_all')';
    </script>
    <script src="{{ mix('js/pages/report.corp_commission.js') }}"></script>
@endsection

