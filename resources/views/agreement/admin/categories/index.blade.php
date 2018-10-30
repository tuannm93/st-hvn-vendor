@php

@endphp

@extends('layouts.app')
@section('style')
    <link href="{{ mix('css/lib/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ mix('css/lib/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ mix('css/lib/select.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <section class="content agreement-customize">
        <div class="row">
            <div class="col-12">

                <a id="download-file" target="_blank" href="#" class="d-none"></a>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('agreement_admin.category_management')
                    </div>

                    <div id="success-message-alert" class="alert alert-success d-none" role="alert"></div>

                    <div class="panel-body">
                        <table id="datalist" class="table responsive table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        @lang('agreement_admin.category_id')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.genre_name')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.category_name')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.license_check_condition')
                                        <select class="custom-select filter-control">
                                            <option value=""></option>
                                            @foreach(\App\Models\MCategory::LICENSE_CONDITION_TYPE as $type)
                                                <option value="{{$type}}">{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.license')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.configuration')
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('agreement.admin.categories.detail')
        @include('agreement.admin.categories.update')
    </section>
@endsection

@section('script')

    <script>
        const EXPORT_EXCEL_URL = '{{route('agreement.admin.categories.export-excel')}}';
        const EXPORT_CSV_URL = '{{route('agreement.admin.categories.export-csv')}}';
        const DATA_PROCESSING_URL = '{{route('agreement.admin.categories.data')}}';
        const LICENSE_DATA = '{{route('agreement.admin.license.get-data')}}';

        const REFERENCE = '{{trans('agreement_admin.reference')}}';
        const SET_LICENSE = '{{trans('agreement_admin.set_license')}}';
        const EXPORT_CSV = '{{trans('agreement_admin_dashboard.exportCsv')}}';
        const EXPORT_EXCEL = '{{trans('agreement_admin_dashboard.exportExcel')}}';
        const EXPRESS = '{{trans('agreement_admin_dashboard.express')}}';
        const PAGE = '{{trans('agreement_admin_dashboard.page')}}';
        const KEY_WORD_SEARCH_FROM_THE_WHOLE = '{{trans('agreement_admin_dashboard.keywordSearchFromTheWhole')}}';
        const ZERO_RECORDS = '{{trans('agreement_admin_dashboard.zeroRecords')}}';
        const PROCESSING = '{{trans('agreement_admin_dashboard.processing')}}';
        const RIDER = '{{trans('agreement_admin_dashboard.rider')}}';
        const DISPLAY_BY_ITEM = '{{trans('agreement_admin.display_by_item')}}';
        const YES = '{{trans('agreement_admin.btn_yes')}}';
        const NO = '{{trans('agreement_admin.btn_no')}}';
        const CONFIRM_UPDATE_CONTENT = '{{trans('agreement_admin.content_confirm_update')}}';
    </script>

    <script src="{{ mix('js/lib/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ mix('js/lib/jszip.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.buttons.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.select.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.html5.min.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/pages/agreement.admin.categories.js') }}"></script>

@endsection
