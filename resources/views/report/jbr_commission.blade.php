@extends('layouts.app')

@section('content')
    <div class="report-jbr-commission pt-2 mt-2">
        @include('report.jbr_commission.search_form')
        <div class="custom-scroll-x">
            <h5 class="font-weight-bold fs-15">{{ __('report_commission.title') }}</h5>
            <div>{{ __('report_corp_commission.total_number') }} {{ $totalRecord }}{{ __('report_corp_commission.item') }}</div>
            <table class="table custom-border add-pseudo-scroll-bar">
                <thead>
                    <tr class="text-center bg-yellow-light fs-11">
                        <th class="p-1 align-middle fix-w-50">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('follow_date')) }}" class="text-dark font-weight-bold text--underline">
                                {{ __('report_commission.follow_day') }}
                            </a>
                            {{ getSortIcon('follow_date') }}
                        </th>
                        <th class="p-1 align-middle fix-w-50">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('detect_contact_desired_time')) }}" class="text-dark font-weight-bold text--underline">
                                {!! __('report_commission.required_day') !!}</a>
                            {{ getSortIcon('detect_contact_desired_time') }}
                        </th>
                        <th class="p-1 align-middle fix-w-50">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('visit_time')) }}" class="text-dark font-weight-bold text--underline">{{ __('report_commission.access_day') }}</a>
                            {{ getSortIcon('visit_time') }}
                        </th>
                        <th class="p-1 align-middle fix-w-50">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('commission_rank')) }}" class="text-dark font-weight-bold text--underline">
                                {{ __('report_commission.commission_rank') }}</a>
                            {{ getSortIcon('commission_rank') }}
                        </th>
                        <th class="p-1 align-middle fix-w-50">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('site_id')) }}" class="text-dark font-weight-bold text--underline">
                                {{ __('report_commission.website_name') }}</a>
                            {{ getSortIcon('site_id') }}
                        </th>
                        <th class="p-1 align-middle fix-w-50">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('customer_name')) }}" class="text-dark font-weight-bold text--underline">
                                {{ __('report_commission.customer_name') }}</a>
                            {{ getSortIcon('customer_name') }}
                        </th>
                        <th class="p-1 align-middle fix-w-100">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('demand_id')) }}" class="text-dark font-weight-bold text--underline">
                                {{ __('report_commission.proposal_number') }}</a>
                            {{ getSortIcon('demand_id') }}
                        </th>
                        <th class="p-1 align-middle fix-w-100">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('corp_name')) }}" class="text-dark font-weight-bold text--underline">
                                {{ __('report_commission.partner_1') }}</a>
                            {{ getSortIcon('corp_name') }}
                        </th>
                        <th class="p-1 align-middle fix-w-100">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('commission_dial')) }}" class="text-dark font-weight-bold text--underline">
                                {{ __('report_commission.procedure_dial') }}</a>
                            {{ getSortIcon('commission_dial') }}
                        </th>
                        <th class="p-1 align-middle fix-w-50">{{ __('report_commission.available_time') }}</th>
                        <th class="p-1 align-middle fix-w-50">{{ __('report_commission.holiday') }}</th>
                        <th class="p-1 align-middle fix-w-50"><a href="{{ route('report.jbr_commission', getQueryOrder('first_commission')) }}" class="text-dark font-weight-bold text--underline">
                                {!! __('report_commission.initial_check') !!}</a>
                        </th>
                        <th class="p-1 align-middle fix-w-50">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('user_name')) }}" class="text-dark font-weight-bold text--underline">
                                {!!  __('report_commission.last_history_update') !!}</a>
                            {{ getSortIcon('user_name') }}
                        </th>
                        <th class="p-1 align-middle fix-w-50">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('modified2')) }}" class="text-dark font-weight-bold text--underline">
                                {{ __('report_commission.history_update_time') }}</a>
                            {{ getSortIcon('modified2') }}
                        </th>
                        <th class="p-1 align-middle fix-w-50">
                            <a href="{{ route('report.jbr_commission', getQueryOrder('auction')) }}" class="text-dark font-weight-bold text--underline">
                                {{ __('report_commission.bid_drop') }}</a>
                            {{ getSortIcon('auction') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $report)
                        <tr class="{{ outputClassOfCorpSelection2($report) }}">
                            <td class="p-1 align-middle text-wrap text-center">{!! dateTimeFormat($report->follow_date) !!}</td>
                            <td class="p-1 align-middle text-wrap text-center">{!! getContactDesiredTime($report, '<br>〜<br>', 'Y/m/d<\b\r>H:i') !!}</td>
                            <td class="p-1 align-middle text-wrap text-center">{!! getVisitTime($report, 'Y/m/d<\b\r>H:i') !!}</td>
                            <td class="p-1 align-middle text-wrap fix-w-50 text-center">{!! $report->commission_rank ? $report->commission_rank : '-' !!}</td>
                            <td class="p-1 align-middle text-wrap">{!! $report->site_name !!}</td>
                            <td class="p-1 align-middle text-wrap">{!! $report->customer_name !!}</td>
                            <td class="p-1 align-middle text-wrap fix-w-50 text-center"><a href="{{ route('demand.detail', $report->id) }}" class="highlight-link text--underline">{{ $report->id }}</a></td>
                            <td class="p-1 align-middle text-wrap"><a href="{{ route('affiliation.detail.edit', $report->m_corp_id) }}" class="highlight-link text--underline">{{ $report->corp_name }}</a></td>
                            <td class="p-1 align-middle text-wrap text-center">{!! ctype_digit($report->commission_dial) ? '<a href="'.checkDevice().$report->commission_dial.'" class="highlight-link text--underline">'.$report->commission_dial.'</a>' : '' !!}</td>
                            <td class="p-1 align-middle text-wrap fix-w-50 text-center">@if($report->demand_info_contactable && $report->demand_info_contactable != '~') {!! $report->demand_info_contactable !!} @endif</td>
                            <td class="p-1 align-middle text-wrap fix-w-50">{!! $report->demand_infos_holiday !!}</td>
                            <td class="p-1 align-middle text-wrap fix-w-50 text-center">@if($report->first_commission == 1) <i class="fa fa-check-square-o" aria-hidden="true"></i> @else <i class="fa fa-square-o" aria-hidden="true"></i> @endif </td>
                            <td class="p-1 align-middle text-wrap fix-w-50">@if($report->modified_user_id == 'AutomaticAuction') {{ __('report_commission.automatic_selection') }} @else {{ $report->m_user_name }} @endif</td>
                            <td class="p-1 align-middle text-wrap text-center">{!! dateTimeFormat($report->commission_info_modified, 'Y/m/d<\b\r>H:i') !!}</td>
                            <td class="p-1 align-middle text-wrap fix-w-50 text-center">@if($report->auction == 1) <i class="fa fa-check-square-o" aria-hidden="true"></i> @else <i class="fa fa-square-o" aria-hidden="true"></i> @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pseudo-scroll-bar" data-display="false">
            <div class="scroll-bar"></div>
        </div>
        <div class="pagination mt-3">
            {!! $reportData !!}
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ mix('js/pages/jbr_commission.js') }}"></script>
    <script>
        let checkOrderBy = false;
        @if(\Request::has('order_by') && !is_array(\Request::get('order_by')))
            checkOrderBy = true;
        @endif
        ReportJBRCommission.init();
    </script>
@endsection
