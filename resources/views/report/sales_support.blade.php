@extends('layouts.app')

@section('content')

<div class="sales-support report-sales-support form-horizontal fieldset-custom my-4">
    @if (Session::has('success'))
        <div id="message">
            <p class="alert alert-success">{{ Session::get('success') }}</p>
        </div>
    @endif
    <h3 class="title-head">{!! __('report_sales_support.report_sales_support') !!}</h3>
    <fieldset>
        <legend>{!! __('report_sales_support.search_form') !!}</legend>
        {{Form::open(['method'=>'GET', 'route'=>['report.sales.support'] , 'accept-charset'=>"UTF-8", 'id' => 'sale_support_search_form' ])}}
        <div class="form-search">
            <div class="row">
                <div class="col-lg-6 my-auto pt-md-3">
                    <div class="form-group form-inline flex-column flex-sm-row">
                        <label class="col-sm-3 col-lg-4"
                               for="corp_name">{!! __('report_sales_support.genre_name') !!}</label>
                        <div class="col-sm-9 col-lg-8 p-md-0">
                            {{Form::select('genre_id[]', $m_genre_list, '',['id'=>'genre_id', 'multiple'=>'multiple', 'class'=>'multiple_check_filter'])}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 my-auto pt-md-3">
                    <div class="form-group form-inline">
                        <label class="col-sm-3 col-lg-3"
                               for="">{!! __('report_sales_support.support_kind') !!}</label>
                        <div class="col-sm-9 col-lg-9 step d-flex">
                            <div class="custom-control custom-radio custom-control-inline">
                                {{ Form::radio('support_kind', 'none', null, [($support_kind == 'none') ? 'checked' : '', 'id' => 'support_kind', 'class' => 'custom-control-input']) }}
                                <label class="custom-control-label" for="support_kind">{!! __('report_sales_support.all') !!}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                {{ Form::radio('support_kind', 'tel', null, [($support_kind == 'tel') ? 'checked' : '','id' => 'support_kindTel', 'class' => 'custom-control-input']) }}
                                <label class="custom-control-label"
                                        for="support_kindTel">{!! __('report_sales_support.tel_support') !!}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                {{ Form::radio('support_kind', 'visit', null, [($support_kind == 'visit') ? 'checked' : '', 'id' => 'support_kindVisit', 'class' => 'custom-control-input']) }}
                                <label class="custom-control-label"
                                        for="support_kindVisit">{!! __('report_sales_support.visit_support') !!}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                            {{ Form::radio('support_kind', 'order', null, [($support_kind == 'order') ? 'checked' : '', 'id' => 'support_kindOrder', 'class' => 'custom-control-input']) }}
                                <label class="custom-control-label"
                                        for="support_kindOrder">{!! __('report_sales_support.order_support') !!}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group form-inline">
                        <label class="col-sm-3 col-lg-4"
                               for="corp_name">{!! __('report_sales_support.correspond_status') !!}</label>
                        <div class="col-sm-9 col-lg-8 p-md-0">
                            @if ($flg)
                            <input name="last_step_status" value="" id="last_step_status_" type="hidden">
                            @endif
                            {{Form::select('last_step_status[]', getLastStepStatusList(), '',['id'=>'last_step_status', 'multiple'=>'multiple', 'class'=>'multiple_check_filter' , 'style' => 'display:none'])}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group col-lg-2 offset-lg-9">
                        <input type="submit" value="{!! __('report_sales_support.btn_search') !!}" class="btn btn--gradient-orange pl-4 pr-4" id="report_btn_search">
                    </div>
                </div>
            </div>
        </div>
        {{Form::close()}}
    </fieldset>
    {{Form::open(['method'=>'POST', 'route'=>['report.update.sales.support'] , 'accept-charset'=>"UTF-8", 'class' => 'form-horizontal fieldset-custom my-4' ])}}
    <div class="form-group d-flex justify-content-end">
        <input type="submit" value="{!! __('report_sales_support.btn_update') !!}" class="btn btn--gradient-green">
    </div>
    <div class="content custom-scroll-x">
        <table class="table table-bordered table-list" id="table-salesSupportList" data-url="">
            <thead>
                <tr>
                    <th class="p-1 align-middle">{!! __('report_sales_support.demand_id') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="DemandInfo.id" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="DemandInfo.id" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.customer_name') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="DemandInfo.customer_name" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="DemandInfo.customer_name" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.tel1') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="DemandInfo.tel1" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="DemandInfo.tel1" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.official_corp_name') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="MCorp.official_corp_name" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="MCorp.official_corp_name" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.commission_dial') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="MCorp.commission_dial" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="MCorp.commission_dial" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.genre_name') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="MGenre.genre_name" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="MGenre.genre_name" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.commission_status') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="CommissionInfo.commission_status" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="CommissionInfo.commission_status" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.support_kind') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="CommissionSupport.support_kind" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="CommissionSupport.support_kind" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.correspond_status') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="CommissionSupport.correspond_status" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="CommissionSupport.correspond_status" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.order_fail_reason') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="CommissionSupport.order_fail_reason" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="CommissionSupport.order_fail_reason" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.correspond_datetime') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="CommissionSupport.correspond_datetime" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="CommissionSupport.correspond_datetime" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle">{!! __('report_sales_support.modified') !!}
                        <span class="sortInner">
                            <span class="up sort" data-sort="CommissionSupport.modified" data-direction="asc">{{ trans('common.asc') }}</span>
                            <span class="down sort" data-sort="CommissionSupport.modified" data-direction="desc">{{ trans('common.desc') }}</span>
                        </span>
                    </th>
                    <th class="p-1 align-middle fix-w-85">
                        <span>{!! __('report_sales_support.status_update') !!}</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                <tr>
                    <td class="p-1 align-middle text-center">{{ $result['DemandInfo__id'] }}</td>
                    <td class="p-1 align-middle"><a class="highlight-link" href="{{ route('commission.detail', ['id' => $result['CommissionInfo__id']]) }}" target="_blank">{{ $result['DemandInfo__customer_name'] }}</a></td>
                    <td class="p-1 align-middle text-center"><a class="highlight-link" href="{{ checkDevice().$result['DemandInfo__tel1'] }}">{{ $result['DemandInfo__tel1'] }}</a></td>
                    <td class="p-1 align-middle">{{ $result['MCorp__official_corp_name'] }}</td>
                    <td class="p-1 align-middle text-center"><a class="highlight-link" href="{{ checkDevice().$result['MCorp__commission_dial'] }}">{{ $result['MCorp__commission_dial'] }}</a></td>
                    <td class="p-1 align-middle">{{ $result['MGenre__genre_name'] }}</td>
                    <td class="p-1 align-middle">{{ $result['MItem__item_name'] }}</td>
                    <td class="p-1 align-middle">{{ $support_kind_label[$result['CommissionSupport__support_kind']] }}{!! __('report_sales_support.correspon') !!}</td>
                    <td class="p-1 align-middle">
                        @if ($result['CommissionSupport__support_kind'] == 'tel')
                            {{ $items[__('report_sales_support.tel_correspon')][$result['CommissionSupport__correspond_status']] }}
                        @elseif ($result['CommissionSupport__support_kind'] == 'visit')
                            {{ $items[__('report_sales_support.visit_correspon')][$result['CommissionSupport__correspond_status']] }}
                        @elseif ($result['CommissionSupport__support_kind'] == 'order')
                            {{ $items[__('report_sales_support.order_correspon')][$result['CommissionSupport__correspond_status']] }}
                        @endif
                    </td>
                    <td class="p-1 align-middle">
                        @if ($result['CommissionSupport__support_kind'] == 'tel' && isset($items[__('report_sales_support.tel_reason')][$result['CommissionSupport__order_fail_reason']]))
                            {{ $items[__('report_sales_support.tel_reason')][$result['CommissionSupport__order_fail_reason']] }}
                        @elseif ($result['CommissionSupport__support_kind'] == 'visit' && isset($items[__('report_sales_support.visit_reason')][$result['CommissionSupport__order_fail_reason']]))
                            {{ $items[__('report_sales_support.visit_reason')][$result['CommissionSupport__order_fail_reason']] }}
                        @elseif ($result['CommissionSupport__support_kind'] == 'order' && isset($items[__('report_sales_support.order_reason')][$result['CommissionSupport__order_fail_reason']]))
                            {{ $items[__('report_sales_support.order_reason')][$result['CommissionSupport__order_fail_reason']] }}
                        @endif
                    </td>
                    <td class="p-1 align-middle text-center">{{ $result['CommissionSupport__correspond_datetime'] }}</td>
                    <td class="p-1 align-middle text-center">{{ $result['CommissionSupport__modified'] }}</td>
                    <td class="p-1 align-middle text-center">
                        {{ Form::select(
                            'exclusion_status[' . $result['CommissionInfo__id'] . ']',
                            [0 => '', 1 => __('report_sales_support.success'), 2 => __('report_sales_support.failure')],
                            '',
                            [
                                'class' => 'form-control'
                            ]
                        ) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{Form::close()}}
</div>
@endsection

@section('script')
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/lib/custom.js') }}"></script>
    <script src="{{ mix('js/pages/report.sales_support.js') }}"></script>
@endsection
