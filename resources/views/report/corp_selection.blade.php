@extends('layouts.app')
@section('style')
@endsection
@section('content')
<div class="corp_selection">
    <div class="container">
        @if (isset($results))
            <div class="row table-result-search">
                <h5>{{ __('report_corp_selection.vendor_selection') }}</h5>
                <div class="col-lg-12 row">
                    <span class="count-total">{{ __('report_corp_selection.total') }} {{ $results->total() }}{{ __('report_corp_selection.item') }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered list_tbl_ext table-list" id="">
                        <thead>
                        <tr>
                            <th class="text-center">
                                @if(Request::get('sort') == 'demand_follow_date' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=demand_follow_date&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.demand_follow_date') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'demand_follow_date' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=demand_follow_date&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.demand_follow_date') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=demand_follow_date&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.demand_follow_date') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'id' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=id&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.demand_detail_id') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'id' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=id&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.demand_detail_id') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=id&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.demand_detail_id') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'commission_rank' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=commission_rank&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.commission_rank') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'commission_rank' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=commission_rank&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.commission_rank') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=commission_rank&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.commission_rank') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'nighttime_takeover' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=nighttime_takeover&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.nighttime_takeover') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'nighttime_takeover' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=nighttime_takeover&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.nighttime_takeover') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=nighttime_takeover&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.nighttime_takeover') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'receive_datetime' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=receive_datetime&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.receive_datetime') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'receive_datetime' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=receive_datetime&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.receive_datetime') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=receive_datetime&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.receive_datetime') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'customer_name' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=customer_name&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.customer_name') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'customer_name' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=customer_name&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.customer_name') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=customer_name&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.customer_name') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'detect_contact_desired_time' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=detect_contact_desired_time&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.detect_contact_desired_time') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'detect_contact_desired_time' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=detect_contact_desired_time&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.detect_contact_desired_time') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=detect_contact_desired_time&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.detect_contact_desired_time') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'visit_time' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=visit_time&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.visit_time') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'visit_time' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=visit_time&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.visit_time') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=visit_time&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.visit_time') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'site_id' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=site_id&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.site_name') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'site_id' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=site_id&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.site_name') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=site_id&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.site_name') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'category_id' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=category_id&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.category_name') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'category_id' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=category_id&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.category_name') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=category_id&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.category_name') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'address1' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=address1&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.prefecture') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'address1' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=address1&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.prefecture') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=address1&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.prefecture') }}
                                    </a>
                                @endif
                            </th>
                            <th class="text-center">
                                @if(Request::get('sort') == 'auction' && Request::get('direction') == 'desc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=auction&direction=asc{{ $pageLink }}">
                                        {{ __('report_corp_selection.auction') }} {{ trans('common.desc') }}
                                    </a>
                                @elseif(Request::get('sort') == 'auction' && Request::get('direction') == 'asc')
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=auction&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.auction') }} {{ trans('common.asc') }}
                                    </a>
                                @else
                                    <a href="{{ URL::route('report.get.corp.selection') }}?sort=auction&direction=desc{{ $pageLink }}">
                                        {{ __('report_corp_selection.auction') }}
                                    </a>
                                @endif
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($results))
                            @foreach($results as $result)
                                <tr class="{{ outputClassOfCorpSelection($result) }}">
                                    <td class="text-center">{{ dateTimeFormat($result['follow_date']) }}</td>
                                    <td class="text-center">
                                        <a href="{{route('demand.detail',$result['id'])}}">{{ $result['id'] }}</a>
                                    </td>
                                    <td class="text-center">{{ $result['commission_rank'] }}</td>
                                    <td class="text-center">@if($result['nighttime_takeover'] == 1) ☑ @else □ @endif</td>
                                    <td class="text-center">{{ dateTimeFormat($result['receive_datetime']) }}</td>
                                    <td class="text-left">{{ $result['customer_name'] }}</td>
                                    <td class="text-center">{{ dateTimeFormat($result['detect_contact_desired_time']) }}</td>
                                    <td class="text-center">{{ dateTimeFormat($result['visit_time']) }}</td>
                                    <td class="text-left">{{ $result['site_name'] }}</td>
                                    <td class="text-left">{{ $result['category_name'] }}</td>
                                    <td class="text-center">{{ getDivTextJP('prefecture_div', $result['address1']) }}</td>
                                    <td class="text-center">@if($result['auction'] == 1) ☑ @else □ @endif</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(!empty($results))
                        {{ $results->appends([
                            'sort' => Request::get('sort'),
                            'direction' => Request::get('direction')]
                        )->links('report.components.pagination') }}
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@section('script')
@endsection
