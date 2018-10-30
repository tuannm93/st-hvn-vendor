<!-- Search details -->
@if (isset($results))
    <div class="table-result-search">
        <div class="d-flex flex-column">
            <span class="font-weight-bold fs-15">{{ __('report_corp_commission.trader_agency_list') }}</span>
            <span class="count-total">{{ __('common.total') }} {{ $results->total() }} {{ __('common.item') }}</span>
        </div>
        <div class="custom-scroll-x">
            <table class="table table-bordered table-list add-pseudo-scroll-bar" id="searchData">
                <thead>
                <tr class="text-center">
                    <th class="p-1 align-middle"><a> {{ __('report_corp_commission.number_of_viewers') }} </a></th>
                    <th class="p-1 align-middle fix-w-85">
                        <a class="order-sort text--underline" id="label_follow_date" data-val="follow_date">
                            {{ __('report_corp_commission.follow_date') }}
                            <span class="type_order" id="direction_label_follow_date"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle">
                        <a class="order-sort text--underline" id="label_detect_contact_desired_time" data-val="detect_contact_desired_time">
                            {{ __('report_corp_commission.detect_contact_desired_time') }}
                            <span class="type_order" id="direction_label_detect_contact_desired_time"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle fix-w-85">
                        <a class="order-sort text--underline" id="label_visit_time" data-val="visit_time">
                            {{ __('report_corp_commission.visit_time') }}
                            <span class="type_order" id="direction_label_visit_time"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle fix-w-25">
                        <a class="order-sort text--underline" id="label_commission_rank" data-val="commission_rank">
                            {{ __('report_corp_commission.commission_rank') }}
                            <span class="type_order" id="direction_label_commission_rank"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle fix-w-80">
                        <a class="order-sort text--underline" id="label_site_id" data-val="site_id">
                            {{ __('report_corp_commission.site_name') }}
                            <span class="type_order" id="direction_label_site_id"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle">
                        <a class="order-sort text--underline" id="label_address1" data-val="address1">
                            {{ __('report_corp_commission.address') }}
                            <span class="type_order" id="direction_label_address1"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle">
                        <a class="order-sort text--underline" id="label_customer_name" data-val="customer_name">
                            {{ __('report_corp_commission.customer_name') }}
                            <span class="type_order" id="direction_label_customer_name"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle">
                        <a class="order-sort text--underline" id="label_item_id" data-val="item_id">
                            {{ __('report_corp_commission.item_id') }}
                            <span class="type_order" id="direction_label_item_id"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle fix-w-93">
                        <a class="order-sort text--underline" id="label_corp_name" data-val="corp_name">
                            {{ __('report_corp_commission.corp_name') }}
                            <span class="type_order" id="direction_label_corp_name"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle">
                        <a class="order-sort text--underline" id="label_commission_dial" data-val="commission_dial">
                            {{ __('report_corp_commission.commission_dial') }}
                            <span class="type_order" id="direction_label_commission_dial"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle">
                        <a>{{ __('report_corp_commission.contactable') }}</a>
                    </th>
                    <th class="p-1 align-middle fix-w-34">
                        <a>{{ __('report_corp_commission.holiday_label') }}</a>
                    </th>
                    <th class="p-1 align-middle">
                        <a class="order-sort text--underline" id="label_first_commission" data-val="first_commission">
                            {{ __('report_corp_commission.first_commission') }}
                            <span class="type_order" id="direction_label_first_commission"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle fix-w-53">
                        <a class="order-sort text--underline" id="label_user_name" data-val="user_name">
                            {{ __('report_corp_commission.user_name') }}
                            <span class="type_order" id="direction_label_user_name"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle">
                        <a class="order-sort text--underline" id="label_modified" data-val="modified">
                            {{ __('report_corp_commission.modified') }}
                            <span class="type_order" id="direction_label_modified"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle">
                        <a class="order-sort text--underline" id="label_auction" data-val="auction">
                            {{ __('report_corp_commission.auction') }}
                            <span class="type_order" id="direction_label_auction"></span>
                        </a>
                    </th>
                    <th class="p-1 align-middle">
                        <a class="order-sort text--underline" id="label_cross_sell_implement" data-val="cross_sell_implement">
                            {{ __('report_corp_commission.cross_sell_implement') }}
                            <span class="type_order" id="direction_label_cross_sell_implement"></span>
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($results))
                    @php
                        $listData = '';
                    @endphp
                    @foreach($results as $key => $data)
                        @php
                            $listData .= $data->id;
                            $listData .= ",";
                        @endphp
                        <tr class="{{ outputClassOfDateLimit($data) }}">
                            <td class="p-1 align-middle text-center" id="td_{{ $data->id }}">0</td>
                            <td class="p-1 align-middle text-center">{!! dateTimeFormat($data->follow_date) !!}</td>
                            <td class="p-1 align-middle text-center">{!! getContactDesiredTime($data) !!}</td>
                            <td class="p-1 align-middle text-center">{!! getVisitTime($data) !!}</td>
                            <td class="p-1 align-middle text-center">{{ $data->commission_rank }}</td>
                            <td class="p-1 align-middle">{{ $data->site_name }}</td>
                            <td class="p-1 align-middle text-center">{{ getDivTextJP('prefecture_div', $data->address1) }}</td>
                            <td class="p-1 align-middle">{{ $data->customer_name }}</td>
                            <td class="p-1 align-middle text-center"><a class="highlight-link" href='{{ route('demand.detail', $data->id) }}'>{{ $data->id }}</a></td>
                            <td class="p-1 align-middle"><a class="highlight-link" href="{{ route('affiliation.detail.edit', $data->m_corps_id) }}">{{ $data->corp_name }}</a></td>
                            <td class="p-1 align-middle"><a class="highlight-link" href="{{ checkDevice().$data->commission_dial }}">{{ $data->commission_dial }}</a></td>
                            <td class="p-1 align-middle text-center">{{ $data->contactable }}</td>
                            <td class="p-1 align-middle text-center">{{ $data->holiday }}</td>
                            <td class="p-1 align-middle text-center">@if($data->first_commission == 1) ☑ @else □ @endif</td>
                            <td class="p-1 align-middle">{{ $data->user_name }}</td>
                            <td class="p-1 align-middle text-center">{{ dateTimeFormat($data->modified2) }}</td>
                            <td class="p-1 align-middle text-center">@if($data->auction == 1) ☑ @else □ @endif</td>
                            <td class="p-1 align-middle text-center">@if($data->cross_sell_implement == 1) ☑ @else □ @endif</td>
                        </tr>
                    @endforeach
                    <input type="hidden" value="{{ $listData }}" id="list_data_id">
                @endif
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            @if(!empty($results))
                {{ $results->links('common.pagination') }}
            @endif
        </div>

    </div>
@endif
