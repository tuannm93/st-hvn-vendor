@extends('layouts.app')

@section('content')
<div class="report-unsent-list">
    <label class="form-category__label mt-2">@lang('report_unsent_list.page_title')</label>
    <div>
        <span>@lang('report_unsent_list.before_total_item') <span>{{$unsentList->total()}}</span>@lang('report_unsent_list.after_total_item')</span>
    </div>
    <div class="custom-scroll-x">
        <table class="table custom-border add-pseudo-scroll-bar">
            <thead>
                <tr class="text-center bg-yellow-light">
                    <th class="p-1 align-middle fix-w-100">
                        @lang('report_unsent_list.demand_id')
                        <a href="{{URL::current().'?sort=demand_id&order=asc'}}" class="triangle-up"></a>
                        <a href="{{URL::current().'?sort=demand_id&order=desc'}}" class="triangle-down"></a>
                    </th>
                    <th class="p-1 align-middle fix-w-150">
                        @lang('report_unsent_list.corp_name')
                        <a href="{{URL::current().'?sort=corp_name&order=asc'}}" class="triangle-up"></a>
                        <a href="{{URL::current().'?sort=corp_name&order=desc'}}" class="triangle-down"></a>
                    </th>
                    <th class="p-1 align-middle fix-w-150">
                        @lang('report_unsent_list.receive_datetime')
                        <a href="{{URL::current().'?sort=receive_datetime&order=asc'}}" class="triangle-up"></a>
                        <a href="{{URL::current().'?sort=receive_datetime&order=desc'}}" class="triangle-down"></a>
                    </th>
                    <th class="p-1 align-middle fix-w-150">
                        @lang('report_unsent_list.detect_contact_desired_time')
                        <a href="{{URL::current().'?sort=detect_contact_desired_time&order=asc'}}" class="triangle-up"></a>
                        <a href="{{URL::current().'?sort=detect_contact_desired_time&order=desc'}}" class="triangle-down"></a>
                    </th>
                    <th class="p-1 align-middle fix-w-150">
                        @lang('report_unsent_list.site_id')
                        <a href="{{URL::current().'?sort=site_id&order=asc'}}" class="triangle-up"></a>
                        <a href="{{URL::current().'?sort=site_id&order=desc'}}" class="triangle-down"></a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($unsentList as $item)
                <tr>
                    <td class="p-1 align-middle fix-w-100 text-center">
                        <a href="{{route('demand.detail',['id' => $item->demand_id])}}" class="highlight-link"> {{$item->demand_id}} </a>
                    </td>
                    <td class="p-1 align-middle fix-w-150">
                        <a href="{{route('affiliation.detail.edit',['id' => $item->m_corp_id])}}" class="highlight-link"> {{$item->corp_name}} </a>
                    </td>
                    <td class="p-1 align-middle fix-w-150 text-center">{{dateTimeFormat($item->receive_datetime)}}</td>
                    <td class="p-1 align-middle fix-w-150 text-center">
                        {{dateTimeFormat($item->detect_contact_desired_time)}}
                        @if($item->contact_desired_time_to != null && $item->contact_desired_time_to != '')
                            〜 {{dateTimeFormat($item->contact_desired_time_to)}}
                        @endif
                    </td>
                    <td class="p-1 align-middle fix-w-150">{{$item->site_name}}</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-1 align-middle text-center">@lang('report_unsent_list.no_data')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pseudo-scroll-bar" data-display="false">
        <div class="scroll-bar"></div>
    </div>
    @if($unsentList->total() > config('rits.report_list_limit'))
        {{$unsentList->links('vendor.pagination.simple-default')}}
    @endif
</div>
@endsection
@section('script')
    <script src="{{ mix('js/pages/report.unsent_list.js') }}"></script>
    <script>
        ReportUnSentList.init();
    </script>
@endsection
