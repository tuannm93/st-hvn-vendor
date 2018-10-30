@inject('service', 'App\Services\Auction\AuctionService')

@php
    $auctionMaskingAll = $service->getDivValue("auction_masking" , "all_exclusion");
    $auctionMaskingWithout = $service->getDivValue("auction_masking" , "without");
@endphp

<div id="table-group-mobi" class="collapse show">
    <label class="bg-gray-light fs-11 d-block d-xl-none font-weight-bold p-1 pl-1 w-100">@lang('auction.sort')</label>
    <div class="auction-link clearfix sort-key-box fs-11 mt-4 d-inline d-xl-flex flex-xl-row">
        @php
            $sort = $dataSort['sort'];
            $order = $dataSort['order'];
        @endphp
        <div class="py-1 px-2 d-none d-xl-block">
            <i class="fa fa-square" aria-hidden="true"></i> @lang('auction.sort')
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'demand_infos.auction_deadline_time');
            @endphp
            <a href="" class="text-dark sort-item" data-sort="demand_infos.auction_deadline_time-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.bid_button_order_by_deadline')
            </a>
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'demand_infos.visit_time_min');
            @endphp
            <a href="" class="text-dark sort-item" data-sort="demand_infos.visit_time_min-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.by_visit_date_and_time')
            </a>
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'demand_infos.contact_desired_time');
            @endphp
            <a href="" class="text-dark sort-item" data-sort="demand_infos.contact_desired_time-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.by_phone_call_date_and_time_order')
            </a>
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link d-none d-xl-block">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'demand_infos.receive_datetime');
            @endphp
            <a href="" class="text-dark sort-item" data-sort="demand_infos.receive_datetime-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.by_date_of_reception')
            </a>
        </div>
        <div class="py-1 px-2 d-none d-xl-block">
            <span class="text--orange">{{trans('common.asterisk')}} {{ $sortInfor['icon'] }}@lang('auction.default_bid_button_order_by_deadline')</span>
        </div>
    </div>
    <div class="fs-11 mt-3 text--info d-none d-xl-block">
        <span>@lang('common.asterisk') @lang('auction.text_contact')</span>
    </div>
    @foreach($results as $key => $val)
        {!! Form::hidden('AuctionInfo.[push_time]', isset($val->push_time) ? $val->push_time : ""); !!}
        @php
            $status = $service->detectAuctionBtn($val);
            $latComission = $service->findLastCommission($val, 'created');
        @endphp
        @if (!(($status == 1 || $status == 2) && (strtotime($latComission . '+' . $supportMessageTime . ' hour') < strtotime(date('Y-m-d H:i:s')))))
            <div id="index_{{ $val->id }}" class="mt-xl-3 table-block clearfix position-relative {{$status == 2 ? 'disable-action' : ''}}">
                @if ($status == 2)
                    <div class="d-xl-none position-absolute fade-table-mobi"></div>
                @endif
                <div class="row p-2 mx-0">
                    <div class="col-xl-6 px-0">
                        <div class="row">
                            <div class="col-sm-2 py-2">
                                @if($service->getDivValue('priority', 'normal') != $val->priority)
                                    @if($service->getDivTextJP('priority', $val->priority, true))
                                        <span class="label-1 p-1 border-orange">
                                    {{ $service->getDivTextJP('priority', $val->priority, true) }}
                                </span>
                                    @endif
                                @else
                                    @if(!empty(trim($service->getDivTextJP('priority', $val->priority, true))))
                                        <span class="label-1 p-1">
                                    {{ $service->getDivTextJP('priority', $val->priority, true) }}
                                </span>
                                    @endif
                                @endif
                            </div>
                            <div class="col-sm-4 py-2 d-none d-xl-block">
                            <span class="label-2"><i class="fa fa-square" aria-hidden="true"></i>
                                @lang('auction.proposal_number') {{ $val->id }}
                            </span>
                            </div>
                            <div class="col-sm-6 py-2 d-none d-xl-block">
                                <span class="label-3"><i class="fa fa-square"
                                                        aria-hidden="true"></i> @lang('auction.site_name'): <a
                                        href="http://{{ $val->site_url }}" target="_blank">{{ $val->site_name }}</a></span>
                            </div>
                            @if(!empty($val->auction_fee))
                                <div class="col-md-6 py-2 font-weight-bold">
                                    <span class="p-1">@lang('auction.bidding_fee'): </span>
                                    <span
                                        class="text-danger">{{ $service->yenFormat2($val->auction_fee) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <div class="col-md-6 px-0 py-2 d-none d-xl-block">
                                <span class="date-lable p-1">@lang('auction.agency_(receipt)_date_and_time')</span>
                                <span class="date">{{ dateTimeWeek($val->receive_datetime) }}</span>
                            </div>
                            <div class="col-md-6 px-0 py-2">
                                <span class="date-lable p-1">@lang('auction.bid_button_deadline')</span>

                                <span
                                    class="date">{{ dateTimeWeek($val->auction_deadline_time) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive list d-none d-xl-block">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th class="align-middle">@lang('auction.date_and_time_of_visit')</th>
                                <th class="align-middle">@lang('auction.telephone_date_time_desired')</th>
                                <th class="align-middle">@lang('auction.price_offer')</th>
                                <th class="align-middle">@lang('auction.travel_expenses')</th>
                                <th class="align-middle">@lang('auction.customer_name')</th>
                                <th class="align-middle">@lang('auction.contact')</th>
                                <th class="align-middle">@lang('auction.address')</th>
                                <th class="align-middle">@lang('auction.building_type')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>
                                    @php
                                        $isDisplay = false;
                                        $visitTimes = explode('|', $val->prop);
                                        if(count($visitTimes) == 4) {
                                            $isDisplay = true;
                                            $targetTime = $visitTimes[0];
                                        }
                                    @endphp
                                    @if($isDisplay)
                                        <p class="font-weight-bold text-danger mb-0">{{ ($visitTimes[1] == 0) ? __('auction.specify_time') : __('auction.time_to_adjust') }}</p>
                                        <p>{{ dateTimeWeek($visitTimes[0]) }}</p>
                                        @if($visitTimes[1] == 1)
                                            @lang('common.wavy_seal')<br>
                                            {{ dateTimeWeek($visitTimes[2]) }}
                                            <br>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $isDisplayContactDesiredTime = false;
                                        if ($val->is_contact_time_range_flg == 0 && strlen($val->contact_desired_time) > 0) {
                                            $isDisplayContactDesiredTime = true;
                                            $targetTime = $val->contact_desired_time;
                                        }
                                    @endphp
                                    @if ($isDisplayContactDesiredTime)
                                        <p class="font-weight-bold mb-0 text-danger">@lang('auction.time_specification')</p>
                                        {{ dateTimeWeek($val->contact_desired_time) }}
                                    @endif
                                    @php
                                        $isDisplayContactDesiredTimeTo = false;
                                        if ($val->is_contact_time_range_flg == 1 && strlen($val->contact_desired_time_from) > 0) {
                                            $isDisplayContactDesiredTimeTo = true;
                                            $targetTime = $val->contact_desired_time_from;
                                        }
                                    @endphp
                                    @if ($isDisplayContactDesiredTimeTo)
                                        <p class="font-weight-bold mb-0 text-danger">@lang('auction.time_to_adjust')</p>
                                        {{ dateTimeWeek($val->contact_desired_time_from) }}
                                        <br>@lang('common.wavy_seal')<br>
                                        {{ dateTimeWeek($val->contact_desired_time_to) }}
                                    @endif
                                    @if (strlen($val->contact_desired_time) == 0 && strlen($val->contact_desired_time_from) == 0 && count($visitTimes) == 4)
                                        <p class="mb-0">{{ dateTimeWeek($visitTimes[3]) }}</p>
                                        @if (strlen($visitTimes[3]) > 0)
                                            <p class="mb-0 font-weight-bold text-danger">
                                                @lang('auction.after_bidding_confirm_the_contents')
                                                , @lang('auction.upon_your_request_promptly')
                                                , @lang('auction.call_and_decide_the_visit_time')
                                                , @lang('auction.please')
                                            </p>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <p class="mb-0">
                                        @if(empty($val->cost_from) && empty($val->cost_to))
                                            @lang('auction.none')
                                        @elseif(!empty($val->cost_from) && !empty($val->cost_to))
                                            {{ $service->yenFormat2($val->cost_from) }}
                                            {{ trans('common.wavy_seal') }} {{ $service->yenFormat2($val->cost_to) }}
                                        @else
                                            {{ !empty($val->cost_from)? $service->yenFormat2($val->cost_from) : $service->yenFormat2($val->cost_to) }}
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <p class="mb-0">
                                        @if (empty($val->business_trip_amount))
                                            @lang('auction.none')
                                        @else
                                            {{ $service->yenFormat2($val->business_trip_amount) }}
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    {{ $val->customer_name }}
                                </td>
                                <td>
                                    @if(!$isRoleAffiliation)
                                        <a href="{{ checkDevice().$val->tel1 }}">{{ $val->tel1 }}</a><br>
                                    @else
                                        @php
                                            $hour = 0;
                                            $minute = 0;
                                            if (isset($telDisclosure[$val->priority]['item_hour_date'])) {
                                                $hour = $telDisclosure[$val->priority]['item_hour_date'];
                                            }
                                            if (isset($telDisclosure[$val->priority]['item_minute_date'])) {
                                                $minute = $telDisclosure[$val->priority]['item_minute_date'];
                                            }
                                            if (!empty($hour)) {
                                                $hour = $hour * 60;
                                            }
                                            $num = $hour + $minute;
                                            $targetDate = date("Y-m-d H:i:s", strtotime($targetTime . "-" . $num . " minute"));
                                            $isCallToPhone = false;
                                            if ((strtotime($targetDate) <= strtotime(date("Y-m-d H:i:s")) ||
                                                $val->auction_masking != $auctionMaskingWithout ||
                                                ($val->contact_desired_time != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                                ($val->contact_desired_time_from != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                                !empty($visitTimes[3])
                                            ) && $status == 1 ) {
                                                $isCallToPhone = true;
                                            }
                                        @endphp
                                        @if ($isCallToPhone)
                                            <a href="{{ checkDevice().$val->tel1 }}">{{ $val->tel1 }}</a><br>
                                        @else
                                            {{ !empty($val->tel1) ? substr_replace($val->tel1, "******", -6,6) : '' }}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <p class="mb-0">
                                        @if (!$isRoleAffiliation)
                                            {{ $service->getDivTextJP('prefecture_div', $val->address1) }}{{ $val->address2 }}{{ $val->address3 }}
                                            <br>
                                        @else
                                            @php
                                                $hour = 0;
                                                $minute = 0;
                                                if (isset($addressDisclosure[$val->priority]['item_hour_date'])) {
                                                    $hour = $addressDisclosure[$val->priority]['item_hour_date'];
                                                }
                                                if (isset($addressDisclosure[$val->priority]['item_minute_date'])) {
                                                    $minute = $addressDisclosure[$val->priority]['item_minute_date'];}
                                                if (!empty($hour)) {
                                                    $hour = $hour * 60;
                                                }
                                                $num = $hour + $minute;
                                                $targetDate = date("Y-m-d H:i:s", strtotime($targetTime . "-" . $num . " minute"));
                                                $isDisplayAddressFull = false;
                                                if( (strtotime($targetDate) <= strtotime(date("Y-m-d H:i:s")) ||
                                                    $val->auction_masking == $auctionMaskingAll ||
                                                    ($val->contact_desired_time != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                                    ($val->contact_desired_time_from != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                                    !empty($visitTimes[3])
                                                ) && $status == 1 ) {
                                                    $isDisplayAddressFull = true;
                                                }
                                            @endphp
                                            @if ($isDisplayAddressFull)
                                                {{ $service->getDivTextJP('prefecture_div', $val->address1) }}{{ $val->address2 }}{{ $val->address3 }}
                                                <br>
                                            @else
                                                {{ $service->getDivTextJP('prefecture_div', $val->address1) }}{{ $val->address2 }}{{ $service->maskingAddress3($val->address3) }}
                                                <br>
                                            @endif
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <p class="mb-0">{{ $service->getDropText($buildingType, $val->construction_class) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" class="bg--gray-light ankenDetail ankenDetail-{{ $val->id }}"
                                    style="display:none" id="ankenDetail-{{ $val->id }}">
                                    <span>@lang('auction.case_detail')</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="display:none" id="ankenDetailText-{{ $val->id }}"
                                    class="ankenDetailText ankenDetailText-{{ $val->id }}"></td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div id="btnAnkenDetail-{{ $val->id }}" class="btnAnkenDetail"
                                        style="display:none"></div>
                                    @if ($status == 1)
                                        <div>
                                            @lang('auction.it_is_own_bidding')
                                        </div>
                                    @elseif ($status == 2)
                                        <div>
                                            <span
                                                style="color:#FF0000">@lang('auction.bidding_could_not_be_done')</span>
                                            @if (strtotime($latComission . '+' . $supportMessageTime . ' hour') >= strtotime(date('Y-m-d H:i:s')))
                                                <div>
                                                    {{ dateTimeFormat($latComission, 'Y年m月d日 H時i分') }}@lang('auction.other_companies_have_bid_on')
                                                </div>
                                            @endif
                                        </div>
                                    @elseif ($status == 3)
                                        <div>
                                            @lang('auction.text_infor_expiration')<br>
                                            @lang('auction.if_correspondence_is_possible_please_contact_directly')
                                        </div>
                                    @elseif ($status == 4)
                                        <div>
                                            @lang('auction.it_is_a_bid_flow_project')
                                        </div>
                                    @elseif ($status == 5)
                                        <button id="support-button-{{$val->auction_info_id}}" type="button"
                                                class="btn btn--gradient-orange supportButton" data-id="{{ $val->auction_info_id }}"
                                                data-url="{{ route('auction.support', ['id' => $val->auction_info_id]) }}">@lang('auction.to_bid')
                                        </button>
                                        <button type="button" data-id="{{ $val->auction_info_id }}"
                                                class="btn btn--gradient-green refusalButton"
                                                id="page-data-{{ $val->auction_info_id }}"
                                                data-url-refusal="{{ URL::route('auction.get.refusal', $val->auction_info_id) }}"
                                                data-url-post-refusal="{{ URL::route('auction.post.refusal', $val->auction_info_id) }}">@lang('auction.to_withdraw')</button>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-mobi d-block d-xl-none">
                    <div class="content-table">
                        <div class="detail_box fs-11 m-2">
                            @if ($status == 2)
                                <div class="popup-not-pay position-absolute">
                                    <div class="popup-title text-center p-1 font-weight-bold">
                                        @lang('auction.bidding_could_not_be_done')
                                    </div>
                                    @if (strtotime($latComission . '+' . $supportMessageTime . ' hour') >= strtotime(date('Y-m-d H:i:s')))
                                        <div class="popup-content text-center p-2">
                                            <span class="font-weight-bold">{{ dateTimeFormat($latComission, 'Y') }}</span>年<span class="font-weight-bold">{{ dateTimeFormat($latComission, 'm') }}</span>月<span class="font-weight-bold">{{ dateTimeFormat($latComission, 'd') }}</span>日 <span class="font-weight-bold">{{ dateTimeFormat($latComission, 'H') }}</span>時<span class="font-weight-bold">{{ dateTimeFormat($latComission, 'i') }}</span>分@lang('auction.ni')<br>
                                            @lang('auction.companies_have_bid_on')
                                        </div>
                                    @endif
                                </div>
                            @endif
                            <div class="clearfix detail_row border-bottom mb-2">
                                <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.proposal_number')</div>
                                <div class="detail_item_value">{{ $val->id }}</div>
                            </div>
                            <div class="clearfix detail_row border-bottom mb-2">
                                <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.site_name')</div>
                                <div class="detail_item_value">
                                    <a href="http://{{ $val->site_url }}" target="_blank" class="text-success">{{ $val->site_name }}</a>
                                </div>
                            </div>
                            <div class="clearfix detail_row border-bottom mb-2">
                                <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.agency_(receipt)_date_and_time')</div>
                                <div class="detail_item_value">{{ dateTimeWeek($val->receive_datetime) }}</div>
                            </div>
                            <div class="clearfix detail_row border-bottom mb-2">
                                <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.date_and_time_of_visit')</div>
                                <div class="detail_item_value">
                                    @php
                                        $isDisplay = false;
                                        $visitTimes = explode('|', $val->prop);
                                        if(count($visitTimes) == 4) {
                                            $isDisplay = true;
                                            $targetTime = $visitTimes[0];
                                        }
                                    @endphp
                                    @if($isDisplay)
                                        <p class="font-weight-bold text-danger mb-0">{{ ($visitTimes[1] == 0) ? __('auction.specify_time') : __('auction.time_to_adjust') }}</p>
                                        <p>{{ dateTimeWeek($visitTimes[0]) }}</p>
                                        @if($visitTimes[1] == 1)
                                            @lang('common.wavy_seal')<br>
                                            {{ dateTimeWeek($visitTimes[2]) }}
                                            <br>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix detail_row border-bottom mb-2">
                                <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.telephone_date_time_desired')</div>
                                <div class="detail_item_value">
                                    @php
                                        $isDisplayContactDesiredTime = false;
                                        if ($val->is_contact_time_range_flg == 0 && strlen($val->contact_desired_time) > 0) {
                                            $isDisplayContactDesiredTime = true;
                                            $targetTime = $val->contact_desired_time;
                                        }
                                    @endphp
                                    @if ($isDisplayContactDesiredTime)
                                        <p class="font-weight-bold mb-0 text-danger">@lang('auction.time_specification')</p>
                                        {{ dateTimeWeek($val->contact_desired_time) }}
                                    @endif
                                    @php
                                        $isDisplayContactDesiredTimeTo = false;
                                        if ($val->is_contact_time_range_flg == 1 && strlen($val->contact_desired_time_from) > 0) {
                                            $isDisplayContactDesiredTimeTo = true;
                                            $targetTime = $val->contact_desired_time_from;
                                        }
                                    @endphp
                                    @if ($isDisplayContactDesiredTimeTo)
                                        <p class="font-weight-bold mb-0 text-danger">@lang('auction.time_to_adjust')</p>
                                        {{ dateTimeWeek($val->contact_desired_time_from) }}
                                        <br>@lang('common.wavy_seal')<br>
                                        {{ dateTimeWeek($val->contact_desired_time_to) }}
                                    @endif
                                    @if (strlen($val->contact_desired_time) == 0 && strlen($val->contact_desired_time_from) == 0 && count($visitTimes) == 4)
                                        <p class="mb-0">{{ dateTimeWeek($visitTimes[3]) }}</p>
                                        @if (strlen($visitTimes[3]) > 0)
                                            <p class="mb-0 font-weight-bold text-danger">
                                                @lang('auction.after_bidding_confirm_the_contents')
                                                , @lang('auction.upon_your_request_promptly')
                                                , @lang('auction.call_and_decide_the_visit_time')
                                                , @lang('auction.please')
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix detail_row border-bottom mb-2">
                                <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.customer_name')</div>
                                <div class="detail_item_value">{{ $val->customer_name }}</div>
                            </div>
                            <div class="clearfix detail_row border-bottom mb-2">
                                <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.contact')</div>
                                <div class="detail_item_value">
                                    @if(!$isRoleAffiliation)
                                        <a href="{{ checkDevice().$val->tel1 }}">{{ $val->tel1 }}</a><br>
                                    @else
                                        @php
                                            $hour = 0;
                                            $minute = 0;
                                            if (isset($telDisclosure[$val->priority]['item_hour_date'])) {
                                                $hour = $telDisclosure[$val->priority]['item_hour_date'];
                                            }
                                            if (isset($telDisclosure[$val->priority]['item_minute_date'])) {
                                                $minute = $telDisclosure[$val->priority]['item_minute_date'];
                                            }
                                            if (!empty($hour)) {
                                                $hour = $hour * 60;
                                            }
                                            $num = $hour + $minute;
                                            $targetDate = date("Y-m-d H:i:s", strtotime($targetTime . "-" . $num . " minute"));
                                            $isCallToPhone = false;
                                            if ((strtotime($targetDate) <= strtotime(date("Y-m-d H:i:s")) ||
                                                $val->auction_masking != $auctionMaskingWithout ||
                                                ($val->contact_desired_time != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                                ($val->contact_desired_time_from != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                                !empty($visitTimes[3])
                                            ) && $status == 1 ) {
                                                $isCallToPhone = true;
                                            }
                                        @endphp
                                        @if ($isCallToPhone)
                                            <a href="{{ checkDevice().$val->tel1 }}">{{ $val->tel1 }}</a><br>
                                        @else
                                            {{ !empty($val->tel1) ? substr_replace($val->tel1, "******", -6,6) : '' }}
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix detail_row border-bottom mb-2">
                                <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.address')</div>
                                <div class="detail_item_value">
                                    <p class="mb-0">
                                        @if (!$isRoleAffiliation)
                                            {{ $service->getDivTextJP('prefecture_div', $val->address1) }}{{ $val->address2 }}{{ $val->address3 }}
                                            <br>
                                        @else
                                            @php
                                                $hour = 0;
                                                $minute = 0;
                                                if (isset($addressDisclosure[$val->priority]['item_hour_date'])) {
                                                    $hour = $addressDisclosure[$val->priority]['item_hour_date'];
                                                }
                                                if (isset($addressDisclosure[$val->priority]['item_minute_date'])) {
                                                    $minute = $addressDisclosure[$val->priority]['item_minute_date'];}
                                                if (!empty($hour)) {
                                                    $hour = $hour * 60;
                                                }
                                                $num = $hour + $minute;
                                                $targetDate = date("Y-m-d H:i:s", strtotime($targetTime . "-" . $num . " minute"));
                                                $isDisplayAddressFull = false;
                                                if( (strtotime($targetDate) <= strtotime(date("Y-m-d H:i:s")) ||
                                                    $val->auction_masking == $auctionMaskingAll ||
                                                    ($val->contact_desired_time != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                                    ($val->contact_desired_time_from != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                                    !empty($visitTimes[3])
                                                ) && $status == 1 ) {
                                                    $isDisplayAddressFull = true;
                                                }
                                            @endphp
                                            @if ($isDisplayAddressFull)
                                                {{ $service->getDivTextJP('prefecture_div', $val->address1) }}{{ $val->address2 }}{{ $val->address3 }}
                                                <br>
                                            @else
                                                {{ $service->getDivTextJP('prefecture_div', $val->address1) }}{{ $val->address2 }}{{ $service->maskingAddress3($val->address3) }}
                                                <br>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="clearfix detail_row mb-2">
                                <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.building_type')</div>
                                <div class="detail_item_value">
                                    <p class="mb-0">{{ $service->getDropText($buildingType, $val->construction_class) }}</p>
                                </div>
                            </div>

                            <div class="bg--gray-light px-4 py-2 btn-fix-margin">
                                <div id="btnAnkenDetail-{{ $val->id }}" class="btnAnkenDetail" style="display:none"></div>
                                @if ($status == 1)
                                <div class="bg--gray-light">
                                    <div class="text-center">
                                        <button type="button" class="btn btn--gradient-default col-12 confirm-deal-details-btn" data-url="{{ route('auction.proposal.json', ['demandId' => $val->id]) }}">@lang('auction.confirm_deal_details')
                                        </button>
                                    </div>
                                    <div class="bg-white font-weight-bold text-center p-1">
                                        @lang('auction.it_is_own_bidding')
                                    </div>
                                </div>
                                @elseif ($status == 2)
                                <div class="bg--gray-light">
                                    <div class="text-center mb-1">
                                        <button type="button" class="btn btn--gradient-default col-12 confirm-deal-details-btn" data-url="{{ route('auction.proposal.json', ['demandId' => $val->id]) }}">@lang('auction.confirm_deal_details')
                                        </button>
                                    </div>
                                </div>
                                @elseif ($status == 3)
                                <div class="bg--gray-light">
                                    <div class="text-center mb-1">
                                        <button type="button" class="btn btn--gradient-default col-12 confirm-deal-details-btn" data-url="{{ route('auction.proposal.json', ['demandId' => $val->id]) }}">@lang('auction.confirm_deal_details')
                                        </button>
                                    </div>
                                    <div class="bg-white font-weight-bold text-center p-1">
                                        @lang('auction.text_infor_expiration')<br>
                                        @lang('auction.if_correspondence_is_possible_please_contact_directly')
                                    </div>
                                </div>
                                @elseif ($status == 4)
                                <div class="bg--gray-light">
                                    <div class="text-center mb-1">
                                        <button type="button" class="btn btn--gradient-default col-12 confirm-deal-details-btn" data-url="{{ route('auction.proposal.json', ['demandId' => $val->id]) }}">@lang('auction.confirm_deal_details')
                                        </button>
                                    </div>
                                    <div class="bg-white font-weight-bold text-center p-1">
                                        @lang('auction.it_is_a_bid_flow_project')
                                    </div>
                                </div>
                                @elseif ($status == 5)
                                <div class="bg--gray-light">
                                    <button type="button" class="btn btn--gradient-default confirm-deal-details-btn" data-url="{{ route('auction.proposal.json', ['demandId' => $val->id]) }}">@lang('auction.confirm_deal_details')
                                    </button>
                                    <button id="support-button-{{$val->auction_info_id}}" type="button"
                                            class="btn btn--gradient-orange supportButton" data-id="{{ $val->auction_info_id }}"
                                            data-url="{{ route('auction.support', ['id' => $val->auction_info_id]) }}">@lang('auction.to_bid')
                                    </button>
                                    <button type="button" data-id="{{ $val->auction_info_id }}"
                                            class="btn btn--gradient-green refusalButton"
                                            id="page-data-{{ $val->auction_info_id }}"
                                            data-url-refusal="{{ URL::route('auction.get.refusal', $val->auction_info_id) }}"
                                            data-url-post-refusal="{{ URL::route('auction.post.refusal', $val->auction_info_id) }}">@lang('auction.to_withdraw')</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
