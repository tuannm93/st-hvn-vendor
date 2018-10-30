@extends('layouts.app')

@section('style')
    @if($flag)
        <link href="{{ mix('css/lib/dataTables.bootstrap4.css') }}" rel="stylesheet">
    @endif
@endsection

@section('content')
    <div class="report-development-search">
        <label class="form-category__label mt-2">{{trans("report_development.title")}}</label>
        @if($flag)
            <div class="pb-3">
                <a href="{{route("report.development")}}"
                   class="btn btn--gradient-gray col-3 col-md-2">{{trans("report_development_search.back")}}</a>
            </div>
            <table id="datalist" class="table">
                <thead>
                    <tr class="text-center bg-yellow-light">
                        <th class="hidden">
                        </th>
                        <th class="fix-w-100align-middle">
                            @lang('report_development_search.col_title_1')
                        </th>
                        <th class="fix-w-150 align-middle">
                            @lang('report_development_search.col_title_2')<i class="triangle-up mx-2 sort" aria-hidden="true"></i><i class="triangle-down sort" aria-hidden="true"></i>
                        </th>
                        <th class="fix-w-100 align-middle">
                            @lang('report_development_search.col_title_3')<i class="triangle-up mx-2 sort" aria-hidden="true"></i><i class="triangle-down sort" aria-hidden="true"></i>
                        </th>
                        <th class="fix-w-150 align-middle">
                            @lang('report_development_search.col_title_4')<i class="triangle-up mx-2 sort" aria-hidden="true"></i><i class="triangle-down sort" aria-hidden="true"></i>
                        </th>
                    </tr>
                </thead>
            </table>
        @else
            @component("report/components/development_search/table", ["genres" => $genres,
                    "prefecture" => $prefecture,
                    "noAttackList" => $noAttackList,
                    "advanceList" => $advanceList,
                    "genreId" => $genreId])
            @endcomponent
        @endif
    </div>
@endsection

@section('script')
    @if($flag)
        <script>
            const GET_DATA_URL = '{{route('report.development.data')}}';
            @if(isset($status) && $status != null && isset($address))
            const DATA_URL = {"status": "{{$status}}", "address": "{{$address}}"};
            @else
            const DATA_URL = {};
            @endif
            const ZERO_RECORDS = '{{trans('report_development_search.zeroRecords')}}';
            const PROCESSING = '{{trans('report_development_search.processing')}}';
            const TABLE_INFO_TOTAL = '{{trans("report_development_search.total")}}'
            const TABLE_INFO_RECORD = '{{trans("report_development_search.record")}}'
            const TABLE_INFO_NO_ATTACK = '{{trans("report_development_search.total_no_attack", ["count" => isset($noAttackList[$address]) ? $noAttackList[$address] : 0])}}'
            const TABLE_INFO_ADVANCE = '{{trans("report_development_search.total_advance", ["count" =>  isset($advanceList[$address]) ? $advanceList[$address] : 0])}}'
            const PREV = '{{trans('report_development_search.prev')}}';
            const NEXT = '{{trans('report_development_search.next')}}';
        </script>
        <script src="{{ mix('js/lib/jquery.dataTables.min.js') }}"></script>
        <script src="{{ mix('js/lib/dataTables.bootstrap4.js') }}"></script>
        <script type="text/javascript" src="{{ mix('js/pages/report.development.search.js') }}"></script>
    @endif
@endsection