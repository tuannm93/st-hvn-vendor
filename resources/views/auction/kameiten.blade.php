@inject('service', 'App\Services\Auction\AuctionService')

@php
$auctionMaskingAll = $service->getDivValue("auction_masking" , "all_exclusion");
$auctionMaskingWithout = $service->getDivValue("auction_masking" , "without");
@endphp

{!! Form::open(['url' => route('auction.delete'), 'id' => 'auction-post-search-form']) !!}
@php
    $sort = $dataSortForKameiten['sort'];
    $order = $dataSortForKameiten['order'];
@endphp
<label class="collapse-label-mobi font-weight-bold p-2 w-100 text--white d-block d-xl-none" data-toggle="collapse" data-target="#table-group-kameiten-mobi" aria-expanded="false" aria-controls="table-group-kameiten-mobi">@lang('auction.latest_bid_deals_list')<i class="fa fa-chevron-down float-right" aria-hidden="true"></i></label>
<div id="table-group-kameiten-mobi" class="collapse show">
    <div class="form-category d-none d-xl-block">
        <label
            class="form-category__label font-weight-light">@lang('auction.latest_bid_deals_list')</label>
    </div>
    <label class="bg-gray-light fs-11 d-block d-xl-none font-weight-bold p-1 pl-1 w-100">@lang('auction.attachment_file')</label>
    <div class="file-upload mb-2 fs-8 d-block d-xl-none">
        <span><i class="fa fa-file-o fa-lg" aria-hidden="true"
                title=""></i>: @lang('auction.with_attached_file')</span>
    </div>
    <label class="bg-gray-light fs-11 d-block d-xl-none font-weight-bold p-1 pl-1 w-100">@lang('auction.sort')</label>
    <div class="clearfix sort-key-box fs-11 mt-4 d-inline d-xl-flex flex-xl-row">
        <div class="py-1 px-2 d-none d-xl-block"><i class="fa fa-square" aria-hidden="true"></i> @lang('auction.sort')</div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'demand_id');
            @endphp
            <a href="" class="text-dark sort-item-for-kameiten" data-sort="demand_id-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.by_proposal_number')
            </a>
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'visit_time');
            @endphp
            <a href="" class="text-dark sort-item-for-kameiten" data-sort="visit_time-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.by_visit_date_and_time')
            </a>
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'contact_desired_time');
            @endphp
            <a href="" class="text-dark sort-item-for-kameiten" data-sort="contact_desired_time-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.by_phone_call_date_and_time_order')
            </a>
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link d-none d-xl-block">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'genre_name');
            @endphp
            <a href="" class="text-dark sort-item-for-kameiten" data-sort="genre_name-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.genre_order')
            </a>
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link d-none d-xl-block">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'customer_name');
            @endphp
            <a href="" class="text-dark sort-item-for-kameiten" data-sort="customer_name-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.by_customer_name')
            </a>
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link d-none d-xl-block">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'tel1');
            @endphp
            <a href="" class="text-dark sort-item-for-kameiten" data-sort="tel1-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.contact_sort')
            </a>
        </div>
        <div class="py-1 px-2 float-left mb-1 mb-xl-0 sort-link d-none d-xl-block">
            @php
                $sortInfor = $service->getInforOrderSort($sort, $order, 'address');
            @endphp
            <a href="" class="text-dark sort-item-for-kameiten" data-sort="address-{{ $sortInfor['order_display'] }}">
                {{ $sortInfor['icon'] }}@lang('auction.sort_by_address')
            </a>
        </div>
    </div>
    <div class="file-upload py-3 d-none d-xl-block">
        <span><i class="fa fa-file-o fa-lg" aria-hidden="true"
                title=""></i>: @lang('auction.with_attached_file')</span>
    </div>
    <div class="table-responsive d-none d-xl-block">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="align-middle">@lang('auction.Delete')</th>
                    <th class="align-middle">@lang('auction.proposal_number')</th>
                    <th class="align-middle">@lang('auction.date_and_time_of_visit')</th>
                    <th class="align-middle">@lang('auction.telephone_date_time_desired')</th>
                    <th class="align-middle">@lang('auction.genre')</th>
                    <th class="align-middle">@lang('auction.customer_name')</th>
                    <th class="align-middle">@lang('auction.contact')</th>
                    <th class="align-middle">@lang('auction.address')</th>
                    <th class="align-middle">@lang('auction.building_type')</th>
                    <th class="align-middle">@lang('auction.detail')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auctionAlreadyData as $key => $val)
                    @php
                        $targetTime = '';
                    @endphp
                    <tr>
                        <td>
                            {!! Form::checkbox("id[]", $val->id, false) !!}
                        </td>
                        <td>{{ $val->demand_id }}</td>
                        <td>
                            @if ($val->is_visit_time_range_flg == 0)
                                {!! dateTimeWeek($val->visit_time, '%Y/%m/%d(%a)<br>%H:%M') !!}
                            @endif
                            @if ($val->is_visit_time_range_flg == 1 && strlen($val->visit_time_from) > 0)
                                {!! dateTimeWeek($val->visit_time_from, '%Y/%m/%d(%a)<br>%H:%M') !!}
                                <br>
                                <center>@lang('common.wavy_seal')</center>
                                {!! dateTimeWeek($val->visit_time_to, '%Y/%m/%d(%a)<br>%H:%M') !!}
                            @endif
                            @php
                                if (empty($targetTime)) {
                                    $targetTime = ($val->is_visit_time_range_flg == 0) ? $val->visit_time : $val->visit_time_from;
                                }
                            @endphp
                        </td>
                        <td>
                            @if ($val->is_contact_time_range_flg == 0)
                                {!! dateTimeWeek($val->contact_desired_time, '%Y/%m/%d(%a)<br>%H:%M') !!}
                            @endif
                            @if ($val->is_contact_time_range_flg == 1 && strlen($val->contact_desired_time_from) > 0)
                                {!! dateTimeWeek($val->contact_desired_time_from, '%Y/%m/%d(%a)<br>%H:%M') !!}
                                <br>
                                <center>@lang('common.wavy_seal')</center>
                                {!! dateTimeWeek($val->contact_desired_time_to, '%Y/%m/%d(%a)<br>%H:%M') !!}
                            @endif
                            @php
                                if (empty($targetTime)) {
                                    $targetTime = ($val->is_contact_time_range_flg == 0) ? $val->contact_desired_time : $val->contact_desired_time_from;
                                }
                            @endphp
                        </td>
                        <td>{{ $val->genre_name }}</td>
                        <td>{{ $val->customer_name }}</td>
                        <td>
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
                                $isDisplayTelFull = false;
                                if(strtotime($targetDate) <= strtotime(date("Y-m-d H:i:s")) ||
                                    $val->auction_masking != $auctionMaskingWithout ||
                                    ($val->contact_desired_time != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                    ($val->contact_desired_time_from != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                    isset($val->visit_adjust_time)
                                ) {
                                    $isDisplayTelFull = true;
                                }
                            @endphp
                            @if ($isDisplayTelFull)
                                <a href="{{ checkDevice().$val->tel1 }}">{{ $val->tel1 }}</a><br>
                            @else
                                {{ !empty($val->tel1) ? substr_replace($val->tel1, "******", -6,6) : '' }}
                            @endif
                        </td>
                        <td>
                            @php
                                $hour = 0;
                                $minute = 0;
                                if (isset($addressDisclosure[$val->priority]['item_hour_date'])) {
                                    $hour = $addressDisclosure[$val->priority]['item_hour_date'];
                                }
                                if (isset($addressDisclosure[$val->priority]['item_minute_date'])) {
                                    $minute = $addressDisclosure[$val->priority]['item_minute_date'];
                                }
                                if (!empty($hour)) {
                                    $hour = $hour * 60;
                                }
                                $num = $hour + $minute;
                                $targetDate = date("Y-m-d H:i:s", strtotime($targetTime . "-" . $num . " minute"));
                                $isDisplayAddressFull = false;
                                $auctionMaskingAll = $service->getDivValue("auction_masking" , "all_exclusion");
                                $auctionMaskingWithout = $service->getDivValue("auction_masking" , "without");
                                if(strtotime($targetDate) <= strtotime(date("Y-m-d H:i:s")) ||
                                    $val->auction_masking == $auctionMaskingAll ||
                                    ($val->contact_desired_time != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                    ($val->contact_desired_time_from != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                    isset($val->visit_adjust_time)
                                ) {
                                    $isDisplayAddressFull = true;
                                }
                            @endphp
                            {{ $service->getDivTextJP('prefecture_div', $val->address1) }}
                            {{ $val->address2 }}
                            @if ($isDisplayAddressFull)
                                {{ $val->address3 }}
                            @else
                                {{ $service->maskingAddress3($val->address3) }}
                            @endif
                        </td>
                        <td>{{ $service->getDropText($buildingType, $val->construction_class) }}</td>
                        <td>
                            <button class="btn btn--gradient-default font-weight-bold border--btn-gray">
                                <a href="{{ route('commission.detail', ['id' => $val->commission_infos_id]) }}" target="_blank">
                                    @lang('auction.detailed_confirmation_report')
                                </a>
                            </button>
                            @if (!empty($val->demand_attached_files_demand_id))
                                <img src="/img/file_ico.jpg" alt="@lang('auction.with_attached_file')" title="@lang('auction.with_attached_file')"
                                    style="width: 15px">
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (count($auctionAlreadyData) > 0)
            {{ $auctionAlreadyData->links('auction.component.pagination') }}
        @endif
    </div>
    <div class="table-mobi d-block d-xl-none">
        @foreach($auctionAlreadyData as $key => $val)
            @php
                $targetTime = '';
            @endphp
            <div class="content-table">
                <div class="commission_info_title mt-4">
                    <span>@lang('auction.date_and_time_of_visit') －</span>
                    {!! Form::checkbox("id[]", $val->id, false, ['id' => 'delete-' . $key, 'class' => 'checkbox-delete float-right']) !!}
                    {{Form::label('delete-'.$key, __('auction.Delete'), ['class' => 'mb-0 mr-2 float-right'])}}
                </div>
                <div class="detail_box fs-11 m-2">
                    <div class="clearfix detail_row border-bottom mb-2">
                        <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.proposal_number')</div>
                        <div class="detail_item_value">{{ $val->demand_id }}</div>
                    </div>
                    <div class="clearfix detail_row border-bottom mb-2">
                        <p class="mb-0 text-danger font-weight-bold">時間指定</p>
                        <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.telephone_date_time_desired')</div>
                        <div class="detail_item_value">
                            @if ($val->is_contact_time_range_flg == 0)
                                {!! dateTimeWeek($val->contact_desired_time, '%Y/%m/%d(%a) &nbsp %H:%M') !!}
                            @endif
                            @if ($val->is_contact_time_range_flg == 1 && strlen($val->contact_desired_time_from) > 0)
                                {!! dateTimeWeek($val->contact_desired_time_from, '%Y/%m/%d(%a) &nbsp %H:%M') !!}
                                &nbsp
                                <center>@lang('common.wavy_seal')</center>
                                {!! dateTimeWeek($val->contact_desired_time_to, '%Y/%m/%d(%a)&nbsp %H:%M') !!}
                            @endif
                            @php
                                if (empty($targetTime)) {
                                    $targetTime = ($val->is_contact_time_range_flg == 0) ? $val->contact_desired_time : $val->contact_desired_time_from;
                                }
                            @endphp
                        </div>
                    </div>
                    <div class="clearfix detail_row border-bottom mb-2">
                        <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.genre')</div>
                        <div class="detail_item_value">{{ $val->genre_name }}</div>
                    </div>
                    <div class="clearfix detail_row border-bottom mb-2">
                        <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.customer_name')</div>
                        <div class="detail_item_value">{{ $val->customer_name }}</div>
                    </div>
                    <div class="clearfix detail_row border-bottom mb-2">
                        <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.contact')</div>
                        <div class="detail_item_value">
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
                                $isDisplayTelFull = false;
                                if(strtotime($targetDate) <= strtotime(date("Y-m-d H:i:s")) ||
                                    $val->auction_masking != $auctionMaskingWithout ||
                                    ($val->contact_desired_time != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                    ($val->contact_desired_time_from != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                    isset($val->visit_adjust_time)
                                ) {
                                    $isDisplayTelFull = true;
                                }
                            @endphp
                            @if ($isDisplayTelFull)
                                <a href="{{ checkDevice().$val->tel1 }}">{{ $val->tel1 }}</a><br>
                            @else
                                {{ !empty($val->tel1) ? substr_replace($val->tel1, "******", -6,6) : '' }}
                            @endif
                        </div>
                    </div>
                    <div class="clearfix detail_row border-bottom mb-2">
                        <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.address')</div>
                        <div class="detail_item_value">
                            @php
                                $hour = 0;
                                $minute = 0;
                                if (isset($addressDisclosure[$val->priority]['item_hour_date'])) {
                                    $hour = $addressDisclosure[$val->priority]['item_hour_date'];
                                }
                                if (isset($addressDisclosure[$val->priority]['item_minute_date'])) {
                                    $minute = $addressDisclosure[$val->priority]['item_minute_date'];
                                }
                                if (!empty($hour)) {
                                    $hour = $hour * 60;
                                }
                                $num = $hour + $minute;
                                $targetDate = date("Y-m-d H:i:s", strtotime($targetTime . "-" . $num . " minute"));
                                $isDisplayAddressFull = false;
                                $auctionMaskingAll = $service->getDivValue("auction_masking" , "all_exclusion");
                                $auctionMaskingWithout = $service->getDivValue("auction_masking" , "without");
                                if(strtotime($targetDate) <= strtotime(date("Y-m-d H:i:s")) ||
                                    $val->auction_masking == $auctionMaskingAll ||
                                    ($val->contact_desired_time != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                    ($val->contact_desired_time_from != '' && $val->auction_masking == $auctionMaskingWithout) ||
                                    isset($val->visit_adjust_time)
                                ) {
                                    $isDisplayAddressFull = true;
                                }
                            @endphp
                            {{ $service->getDivTextJP('prefecture_div', $val->address1) }}
                            {{ $val->address2 }}
                            @if ($isDisplayAddressFull)
                                {{ $val->address3 }}
                            @else
                                {{ $service->maskingAddress3($val->address3) }}
                            @endif
                        </div>
                    </div>
                    <div class="clearfix detail_row mb-2">
                        <div class="detail_item_title font-weight-bold border-left-orange pl-1">@lang('auction.building_type')</div>
                        <div class="detail_item_value">{{ $service->getDropText($buildingType, $val->construction_class) }}</div>
                    </div>
                    <div class="button_block text-center py-3">
                        <a href="{{ route('commission.detail', ['id' => $val->commission_infos_id]) }}" target="_blank" class="btn btn--gradient-orange text-white">
                            @lang('auction.detailed_confirmation_report')
                        </a>
                        @if (!empty($val->demand_attached_files_demand_id))
                            <img src="/img/file_ico.jpg" alt="@lang('auction.with_attached_file')" title="@lang('auction.with_attached_file')"
                                style="width: 15px">
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center py-5">
        {!! Form::submit(__('auction.delete_checked_items'),['id' => 'delete-auction', 'class' => 'btn btn--gradient-default auction-search-border font-weight-bold col-md-3', 'name' => 'delete']); !!}
    </div>
</div>
{{ Form::close() }}
