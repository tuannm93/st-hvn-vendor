@extends('layouts.app')
@section('style')
@endsection
@section('content')
    <h5 class="font-weight-bold mt-3">{{ __('report_corp_cate_group_app_answer.corp_category_application_answer') }}({{ __('report_corp_cate_group_app_answer.answer') }})</h5>
    @include('report.corp_category_group_application_answer.search')
    <div class="searchResult">
        @include('report.corp_category_group_application_answer.show_report')
    </div>
@endsection

@section('script')
    <script>
        var url_report_search = '{{ route('report.search.corp.category.group.application.answer') }}';
        var controlEl = {
            isInitSearch: true,
            searchEl: '#searchButton',
            sort: [],
            formId: '#searchForm',
            resultArea: '.searchResult',
            nextPage: '.next',
            prevPage: '.previous'
        };
    </script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script>
        Datetime.initForDatepicker();
        FormUtil.validate('#searchForm');
        ajaxCommon.search(url_report_search, controlEl);
        $(document).ready(

        );

        $('#searchButton').click(function () {
            $('#corp_id_hid').val($('#corp_id').val());
            $('#corp_name_hid').val($('#corp_name').val());
            $('#group_id_hid').val($('#group_id').val());
            $('#application_date_from_hid').val($('#application_date_from').val());
            $('#application_date_to_hid').val($('#application_date_to').val());
        });
    </script>
@endsection
