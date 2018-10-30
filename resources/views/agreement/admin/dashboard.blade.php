@extends('layouts.app')
@section('style')
    <link href="{{ mix('css/lib/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ mix('css/lib/buttons.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <section class="content agreement-dashboard">
        <div class="row">
            <div class="col-12">

                <a id="download-file" target="_blank" href="#" class="d-none"></a>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('agreement_admin_dashboard.contractStatusList')
                    </div>
                    <div class="panel-body">
                        <table id="datalist" class="table responsive table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.companyId')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.rider')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.companyType')<b/>
                                        <select class="custom-select filter-control">
                                            @if($corpKindLabel)
                                                @foreach($corpKindLabel as $key => $value)
                                                    <option value="{{$value}}">{{$value}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.contractStatus')
                                        <select class="custom-select filter-control">
                                            @if($agreementStatusItemLabel)
                                                @foreach($agreementStatusItemLabel as $key => $value)
                                                    <option value="{{$value}}">{{$value}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.officialCompanyName')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.companyInfo')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.listing')
                                        <select class="custom-select filter-control">
                                            @if($listedKindLabel)
                                                @foreach($listedKindLabel as $key => $value)
                                                    <option value="{{$value}}">{{$value}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.capital')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.antiCompanyCheck')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.specialCommercialLawCheck')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin_dashboard.detail')
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')

    <script>
        var EXPORT_EXCEL_URL = '{{route('dashboard.exportExcel')}}';
        var EXPORT_CSV_URL = '{{route('dashboard.exportCsv')}}';
        var DATA_PROCESSING_URL = '{{route('dashboard.dataProcessing')}}';

        var CREATE_RIDER = '{{trans('agreement_admin_dashboard.createRider')}}';
        var EXPORT_CSV = '{{trans('agreement_admin_dashboard.exportCsv')}}';
        var EXPORT_EXCEL = '{{trans('agreement_admin_dashboard.exportExcel')}}';
        var EXPRESS = '{{trans('agreement_admin_dashboard.express')}}';
        var PAGE = '{{trans('agreement_admin_dashboard.page')}}';
        var KEY_WORD_SEARCH_FROM_THE_WHOLE = '{{trans('agreement_admin_dashboard.keywordSearchFromTheWhole')}}';
        var ZERO_RECORDS = '{{trans('agreement_admin_dashboard.zeroRecords')}}';
        var PROCESSING = '{{trans('agreement_admin_dashboard.processing')}}';
        var DISPLAY_BY_ITEM = '{{trans('agreement_admin.display_by_item')}}';

        var NONE = '{{ \App\Models\CorpAgreement::NONE }}';
        var NONE_STATUS = '{{ \App\Models\CorpAgreement::HANSHA_CHECK_STATUS[\App\Models\CorpAgreement::NONE] }}';
        var OK = '{{ \App\Models\CorpAgreement::OK }}';
        var OK_STATUS = '{{ \App\Models\CorpAgreement::HANSHA_CHECK_STATUS[\App\Models\CorpAgreement::OK] }}';
        var NG = '{{ \App\Models\CorpAgreement::NG }}';
        var NG_STATUS = '{{ \App\Models\CorpAgreement::HANSHA_CHECK_STATUS[\App\Models\CorpAgreement::NG] }}';
        var INADEQUATE = '{{ \App\Models\CorpAgreement::INADEQUATE }}';
        var INADEQUATE_STATUS = '{{ \App\Models\CorpAgreement::HANSHA_CHECK_STATUS[\App\Models\CorpAgreement::INADEQUATE] }}';
    </script>

    <script src="{{ mix('js/lib/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ mix('js/lib/jszip.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.buttons.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.select.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.html5.min.js') }}"></script>
    <script src="{{ mix('js/pages/helpers/currency.js') }}"></script>
    <script src="{{ mix('js/pages/agreement.admin.dashboard.js') }}"></script>

@endsection