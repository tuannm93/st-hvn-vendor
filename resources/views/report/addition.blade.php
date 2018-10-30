@extends('layouts.app')

@section('content')
<div class="report-addition">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
            <div class="box__mess box--{{ $msg }}">
                {{ Session::get('alert-' . $msg) }}
            </div>
        @endif
    @endforeach
    {!! Form::open(['url' => route('report.additionUpdate'),'accept-charset' => 'UTF-8', 'id' => 'actionForm', 'class' => 'fieldset-custom']) !!}
    <label class="form-category__label mt-2">@lang('report_addition.page_title')</label>
    <fieldset class="form-group">
        <legend class="col-form-label fs-13">@lang('report_addition.search_title')</legend>
        <div class="box--bg-gray box--border-gray p-2">
            <div class="d-flex align-items-center mb-2">
                <label class="mb-0 mr-2" for="demand_flg_1">@lang('report_addition.lbl_check_demand_flg')</label>
                {{Form::checkbox('demand_flg', 'true',(isset($checkDemandFlg) && $checkDemandFlg = true) ? true : false, ['id'=>'demand_flg_1', 'class' => ''])}}
            </div>
            <div>
                {{Form::button(trans('report_addition.search_btn'),['id' => 'searchBtn', 'class'=>'btn btn--gradient-orange remove-effect-btn col-sm-3 col-xl-1 mb-2 mb-sm-0'])}}
                @if(count($additionItems)>0)
                    {{Form::button(trans('report_addition.csv_btn'),['id' => 'csvExportBtn', 'class'=>'btn btn--gradient-orange remove-effect-btn col-sm-3 col-xl-1'])}}
                @endif
            </div>
        </div>
    </fieldset>
    @if(count($additionItems)>0)
        @php
            $sort = $dataSort['sort'];
            $order = $dataSort['order'];
        @endphp
        <p class="mb-2">@lang('report_addition.total_item')  {{$additionItems->total()}}@lang('report_addition.total_item_unit')</p>
        <div class="custom-scroll-x">
            <table class="table custom-border add-pseudo-scroll-bar">
                <thead class="text-center bg-yellow-light">
                    <tr>
                        <th class="p-1 align-middle fix-w-100">@lang('report_addition.demand_id')</th>
                        <th class="p-1 align-middle fix-w-150">
                            @lang('report_addition.official_corp_name')
                            <span class="sortInner">
                                <a href="{{URL::current().( isset($checkDemandFlg) ? '?check_demand_flg=true&' : '?').'sort=official_corp_name&order=asc'}}" class="triangle-up"></a>
                                <a href="{{URL::current().( isset($checkDemandFlg) ? '?check_demand_flg=true&' : '?').'sort=official_corp_name&order=desc'}}" class="triangle-down"></a>
                            </span>
                        </th>
                        <th class="p-1 align-middle fix-w-100">@lang('report_addition.customer_name')</th>
                        <th class="p-1 align-middle fix-w-100">@lang('report_addition.genre_name')</th>
                        <th class="p-1 align-middle fix-w-100">@lang('report_addition.demand_type_update')</th>
                        <th class="p-1 align-middle fix-w-150">@lang('report_addition.construction_price_tax_exclude')</th>
                        <th rowspan="2" class="p-1 align-middle fix-w-150">@lang('report_addition.memo')</th>
                        <th rowspan="2" class="p-1 align-middle fix-w-50">@lang('report_addition.demand_flg')</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="p-1 align-middle">@lang('report_addition.note')</th>
                        <th class="p-1 align-middle fix-w-50">@lang('report_addition.complete_date')</th>
                    </tr>
                </thead>
                @foreach($additionItems as $item)
                    <tr>
                        <td class="p-1 align-middle text-wrap fix-w-100">{{ $item->demand_id }}</td>
                        <td class="p-1 align-middle text-wrap fix-w-150">
                            <a href="{{route('affiliation.detail.edit',['id' => $item->corp_id])}}" class="highlight-link">{{$item->official_corp_name}}</a>
                        </td>
                        <td class="p-1 align-middle text-wrap fix-w-100">{{$item->customer_name}}</td>
                        <td class="p-1 align-middle text-wrap fix-w-100">{{$item->genre_name}}</td>
                        <td class="p-1 align-middle text-wrap fix-w-100">{{Config::get('datacustom.demand_type_update.'.$item->demand_type_update)}}</td>
                        <td class="p-1 align-middle text-wrap fix-w-150 text-right">{{yenFormat2($item->construction_price_tax_exclude)}}</td>
                        <td rowspan="2" class="p-1 align-middle text-wrap fix-w-150">
                            {{ Form::textarea('item['.$item->addition_id.'][memo]', $item->memo, ['class' => 'p-1 form-control', 'rows' => 2]) }}
                        </td>
                        <td rowspan="2" class="p-1 align-middle text-wrap text-center fix-w-50">
                            {{ Form::hidden('item['.$item->addition_id.'][id]', $item->addition_id) }}
                            {{ Form::hidden('item['.$item->addition_id.'][demand_flg]', 0) }}
                            {{Form::checkbox('item['.$item->addition_id.'][demand_flg]', 1,($item->demand_flg == 1) ? true : false)}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="p-1 align-middle text-wrap">
                            {{$item->note}} &nbsp;
                        </td>
                        <td class="p-1 align-middle text-wrap text-center fix-w-50">
                            {{$item->complete_date}}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="pseudo-scroll-bar" data-display="false">
            <div class="scroll-bar"></div>
        </div>
        <div>
            {{$additionItems->appends($paginationAppends)->links('vendor.pagination.simple-default')}}
        </div>
        <div class="text-center mt-2">
            {{ Form::submit(trans('report_addition.update_btn'), ['class' => 'btn btn--gradient-green col-sm-3 col-lg-2', 'id' => 'btnUpdate']) }}
        </div>
    @else
        <div>
            @lang('report_addition.no_data')
        </div>
    @endif

    {!! Form::close() !!}
</div>
@endsection
@section('script')
    <script>
        const CURRENT_URL = '{{URL::current()}}';
        const EXPORT_URL = '{{route('report.additionExportCSV')}}';
    </script>
    <script src="{{ mix('js/pages/report-addition.js') }}"></script>
    <script>
        $(document).ready(function () {
            ReportAddition.init();
        });
    </script>
@endsection
