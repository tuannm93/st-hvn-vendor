<table border="1" class="table table-bordered table-list" id='table-report-auction_fall'>
    <thead class="text-center">
    <tr>
        <th class="min-width-115">{{ trans('report_auction_fall.proposal_id') }}
            <span class="sortInner">
							        <span class="up sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.id', 'direction' => 'asc']) }}" data-sort="demand_infos.id" data-direction="asc">{{ trans('common.asc') }}</span>
                                    <span class="down sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.id', 'direction' => 'desc']) }}" data-sort="demand_infos.id" data-direction="desc">{{ trans('common.desc') }}</span>
                                </span>
        </th>
        <th class="min-width-115">{{ trans('report_auction_fall.overnight_takeover') }}
            <span class="sortInner">
							        <span class="up sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.nighttime_takeover', 'direction' => 'asc']) }}" data-sort="demand_infos.nighttime_takeover" data-direction="asc">{{ trans('common.asc') }}</span>
                                    <span class="down sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.nighttime_takeover', 'direction' => 'desc']) }}" data-sort="demand_infos.nighttime_takeover" data-direction="desc">{{ trans('common.desc') }}</span>
                                </span>
        </th>
        <th class="min-width-115">{{ trans('report_auction_fall.received_date_and_time') }}
            <span class="sortInner">
							        <span class="up sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.receive_datetime', 'direction' => 'asc']) }}" data-sort="demand_infos.receive_datetime" data-direction="asc">{{ trans('common.asc') }}</span>
                                    <span class="down sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.receive_datetime', 'direction' => 'desc']) }}" data-sort="demand_infos.receive_datetime" data-direction="desc">{{ trans('common.desc') }}</span>
                                </span>
        </th>
        <th class="min-width-115">{{ trans('report_auction_fall.customer_name') }}
            <span class="sortInner">
							        <span class="up sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.customer_name', 'direction' => 'asc']) }}" data-sort="demand_infos.customer_name" data-direction="asc">{{ trans('common.asc') }}</span>
                                    <span class="down sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.customer_name', 'direction' => 'desc']) }}" data-sort="demand_infos.customer_name" data-direction="desc">{{ trans('common.desc') }}</span>
                                </span>
        </th>
        <th class="min-width-115">{{ trans('report_auction_fall.contact_due_date') }}
            <span class="sortInner">
							        <span class="up sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.contact_desired_time', 'direction' => 'asc']) }}" data-sort="demand_infos.contact_desired_time" data-direction="asc">{{ trans('common.asc') }}</span>
                                    <span class="down sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.contact_desired_time', 'direction' => 'desc']) }}" data-sort="demand_infos.contact_desired_time" data-direction="desc">{{ trans('common.desc') }}</span>
                                </span>
        </th>
        <th class="min-width-115">{{ trans('report_auction_fall.site_name') }}
            <span class="sortInner">
							        <span class="up sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.site_id', 'direction' => 'asc']) }}" data-sort="demand_infos.site_id" data-direction="asc">{{ trans('common.asc') }}</span>
                                    <span class="down sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.site_id', 'direction' => 'desc']) }}" data-sort="demand_infos.site_id" data-direction="desc">{{ trans('common.desc') }}</span>
                                </span>
        </th>
        <th class="min-width-115">{{ trans('report_auction_fall.category') }}
            <span class="sortInner">
							        <span class="up sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.category_id', 'direction' => 'asc']) }}" data-sort="demand_infos.category_id" data-direction="asc">{{ trans('common.asc') }}</span>
                                    <span class="down sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.category_id', 'direction' => 'desc']) }}" data-sort="demand_infos.category_id" data-direction="desc">{{ trans('common.desc') }}</span>
                                </span>
        </th>
        <th class="min-width-115">{{ trans('report_auction_fall.prefecture') }}
            <span class="sortInner">
							        <span class="up sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.address1', 'direction' => 'asc']) }}" data-sort="demand_infos.address1" data-direction="desc">{{ trans('common.asc') }}</span>
                                    <span class="down sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.address1', 'direction' => 'desc']) }}" data-sort="demand_infos.address1" data-direction="desc">{{ trans('common.desc') }}</span>
                                </span>
        </th>
        <th class="min-width-115">{{ trans('report_auction_fall.after_chase') }}
            <span class="sortInner">
							        <span class="up sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.follow_date', 'direction' => 'asc']) }}" data-sort="demand_infos.follow_date" data-direction="asc">{{ trans('common.asc') }}</span>
                                    <span class="down sort"
                                          data-url="{{ route('report.auctionfalltable', ['sort' => 'demand_infos.follow_date', 'direction' => 'desc']) }}" data-sort="demand_infos.follow_date" data-direction="desc">{{ trans('common.desc') }}</span>
                                </span>
        </th>
    </tr>
    </thead>
    <tbody id="data-table-auction-fall">
    @if(isset($results) && count($results) > 0)
        @foreach($results as $result)
            <tr>
                <td>
                    <a class="text--orange" href="{{ route('demand.detail', ['id' => $result->id]) }}">{{ $result->id }}</a>
                </td>
                <td>
                    {{ ($result->nighttime_takeover == 1) ? '○' : '' }}
                </td>
                <td>
                    {{ dateTimeFormat($result->receive_datetime) }}
                </td>
                <td>
                    {{ $result->customer_name }}
                </td>
                <td>
                    {{ dateTimeFormat($result->contact_desired_time) }}
                </td>
                <td>
                    {{ $result->site_name }}
                </td>
                <td>
                    {{ $result->category_name }}
                </td>
                <td>
                    {{ getDivTextJP('prefecture_div', $result->address1) }}
                </td>
                <td>
                    {{ dateTimeFormat($result->follow_date) }}
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
@if(!empty($results))
    {{ $results->links('report.report_auction_fall_pagination') }}
@endif
