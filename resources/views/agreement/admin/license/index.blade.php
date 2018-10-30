@php

@endphp

@extends('layouts.app')
@section('style')
    <link href="{{ mix('css/lib/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ mix('css/lib/buttons.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <section class="content agreement-customize">
        <div class="row">
            <div class="col-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('agreement_admin.license_management')
                    </div>

                    <div id="success-message-alert" class="alert alert-success d-none" role="alert"></div>
                    <div id="error-message-alert" class="alert alert-warning d-none" role="alert">
                        <p>@lang('common.please_contact_management')</p>
                        <p>@lang('common.a_system_error_has_occurred')</p>
                    </div>

                    <div class="panel-body">
                        <table id="datalist" class="table table-hover table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center w-15">
                                        @lang('agreement_admin.license_id')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.license_name')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.certificate')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('agreement.admin.license.add')
        @include('agreement.admin.license.update')
        @include('agreement.admin.license.detail')

    </section>
@endsection

@section('script')

    <script>

        const LICENSE_DATA = '{{route('agreement.admin.license.get-data')}}';
        const ADD_URL = '{{route('agreement.admin.license.add')}}';
        const UPDATE_URL = '{{route('agreement.admin.license.update')}}';

        const REGISTRATION = '{{trans('agreement_admin.registration')}}';
        const REFERENCE = '{{trans('agreement_admin.reference')}}';
        const UPDATED = '{{trans('agreement_admin.updated')}}';
        const DELETE = '{{trans('agreement_admin.delete')}}';

        const SET_LICENSE = '{{trans('agreement_admin.set_license')}}';
        const EXPORT_CSV = '{{trans('agreement_admin_dashboard.exportCsv')}}';
        const EXPORT_EXCEL = '{{trans('agreement_admin_dashboard.exportExcel')}}';
        const EXPRESS = '{{trans('agreement_admin_dashboard.express')}}';
        const PAGE = '{{trans('agreement_admin_dashboard.page')}}';
        const KEY_WORD_SEARCH_FROM_THE_WHOLE = '{{trans('agreement_admin_dashboard.keywordSearchFromTheWhole')}}';
        const ZERO_RECORDS = '{{trans('agreement_admin_dashboard.zeroRecords')}}';
        const PROCESSING = '{{trans('agreement_admin_dashboard.processing')}}';

        const DISPLAY_BY_ITEM = '{{trans('agreement_admin.display_by_item')}}';

        const CONFIRM_DELETE_CONTENT = '{{trans('agreement_admin.content_confirm_delete')}}';
        const ARE_YOU_SURE_YOU_WANT_TO_REGISTER = '{{trans('agreement_admin.are_you_sure_you_want_to_register')}}';

        const YES = '{{trans('agreement_admin.btn_yes')}}';
        const NO = '{{trans('agreement_admin.btn_no')}}';

        const HAVE_TO = '{{ \App\Models\License::HAVE_TO }}';

    </script>

    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ mix('js/lib/jszip.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.buttons.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.select.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.html5.min.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/agreement.admin.license.js') }}"></script>

@endsection
