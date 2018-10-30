@extends('layouts.app')
@section('style')
    <link href="{{ mix('css/lib/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ mix('css/lib/buttons.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row agreement-customize index">
        <div class="col-md-12 col-md-offset-1">

            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading mb-3">
                        @lang('agreement_admin_customize.specialManagement')
                    </div>

                    <div class="alert alert-success d-none" id="success-alert" role="alert"></div>

                    <div class="table-responsive">
                        <table id="datalist" class="table responsive table-striped table-bordered table-hover"
                               cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        @lang('agreement_admin_customize.enterprise')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.table_kind')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_customize.processingDivision')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_customize.content')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_customize.express')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_customize.edit')
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_customize.delete')
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('agreement_customize.update_agreement_customize')
        @include('agreement_customize.delete_agreement_customize')
    </div>
@endsection
@section('script')

    <script>
        const GET_DATA_URL = '{{route('agreement.customize.data')}}';

        const KEY_WORD_SEARCH_FROM_THE_WHOLE = '{{trans('agreement_admin_dashboard.keywordSearchFromTheWhole')}}';
        const ZERO_RECORDS = '{{trans('agreement_admin_dashboard.zeroRecords')}}';
        const PROCESSING = '{{trans('agreement_admin_dashboard.processing')}}';
        const EXPRESS = '{{trans('agreement_admin_dashboard.express')}}';
        const PAGE = '{{trans('agreement_admin_dashboard.page')}}';
        const DISPLAY_BY_ITEM = '{{trans('agreement_admin.display_by_item')}}';

        const CONFIRM_DELETE_CONTENT = '{{trans('agreement_admin.content_confirm_delete')}}';
        const CONFIRM_UPDATE_CONTENT = '{{trans('agreement_admin.content_confirm_update')}}';

        const YES = '{{trans('agreement_admin.btn_yes')}}';
        const NO = '{{trans('agreement_admin.btn_no')}}';
    </script>

    <script src="{{ mix('js/lib/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ mix('js/lib/jszip.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.buttons.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.select.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.html5.min.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/agreement.admin.customize.js') }}"></script>

@endsection
