
<label class="form-category__label d-none d-md-block">{{ trans('commission_detail.cus_info') }}　{{ trans('commission_detail.case_status') }}：
@php
    $commissionStatusList = $drop_list['commission_status'];

    if (!empty($results['CommissionInfo__commission_status'])) {
        echo $commissionStatusList[$results['CommissionInfo__commission_status']];
    }
@endphp
</label>
<div class="form-category__body clearfix">
    <h3 class="form-note mt-0 font-weight-bold">
        {{ trans('commission_detail.case_type') }}：{{ getDivTextJP('selection_type', $results['DemandInfo__selection_system']) ? trans('commission_detail.' . getDivTextJP('selection_type', $results['DemandInfo__selection_system'])) : '' }} <br>
        {{ trans('commission_detail.request_datetime') }}:{{ $contact_desired_time_hope }}<br>
        {{ trans('commission_detail.desired_visit_date') }}:{{ $visit_time_of_hope }}
    </h3>

    <div class="bg-scroll-white">
        <table class="table table-bordered customer-table">
            <tbody>
            <tr>
                <th>{{ trans('commission_detail.proposal_no') }}</th>
                <td data-label="{{ trans('commission_detail.proposal_no') }}">
                    @if ($auth != 'affiliation')
                        <a href="{{route('demand.detail', ['id' => $results['CommissionInfo__demand_id']]) }}" class="link-primary text--underline" target="_blank">
                            {{ $results['CommissionInfo__demand_id'] }}
                        </a>
                    @else
                        {{$results['CommissionInfo__demand_id']}}
                    @endif
                    <input name="data[CommissionInfo][demand_id]" id="demand_id" value="{{ $results['CommissionInfo__demand_id'] }}" type="hidden">
                </td>
                <th>{{ trans('commission_detail.reception_datetime') }}</th>
                <td data-label="{{ trans('commission_detail.reception_datetime') }}">{{ dateTimeFormat($results['DemandInfo__receive_datetime']) }}</td>
                <th>{{ trans('commission_detail.receipt_datetime') }}</th>
                <td data-label="{{ trans('commission_detail.receipt_datetime') }}">{{ dateTimeFormat($results['CommissionInfo__commission_note_send_datetime']) }}</td>

            </tr>
            <tr>
                <th class="odd">{{ trans('commission_detail.cus_name') }}</th>
                <td data-label="{{ trans('commission_detail.cus_name') }}">{{$results['DemandInfo__customer_name']}}</td>
                <th class="odd">{{ trans('commission_detail.contact_1') }}</th>
                <td data-label="{{ trans('commission_detail.contact_1') }}">
                @if ($auth != 'affiliation' ||
                        ($results['DemandInfo__selection_system'] != $div_value['auction_selection'] && $results['DemandInfo__selection_system'] != $div_value['automatic_auction_selection']) ||
                        ($results['DemandInfo__contact_desired_time'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) ||
                        ($results['DemandInfo__contact_desired_time_from'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) )
                    <a href="{{ checkDevice().$results['DemandInfo__tel1'] }}" class="link-primary text--underline">{{ $results['DemandInfo__tel1'] }}</a>
                @else
                    @php
                        $hour = 0;
                        $minute = 0;
                        if (isset($tel_disclosure[$results['DemandInfo__priority']]['item_hour_date'])) {
                            $hour = $tel_disclosure[$results['DemandInfo__priority']]['item_hour_date'];
                        }
                        if (isset($tel_disclosure[$results['DemandInfo__priority']]['item_minute_date'])) {
                            $minute = $tel_disclosure[$results['DemandInfo__priority']]['item_minute_date'];
                        }
                        if (!empty($hour)) {
                            $hour = $hour * 60;
                        }
                        $num = $hour + $minute;

                        if (isset($visit_time['visit_time'])) {
                            $visitTime = $visit_time['visit_time'];
                        }
                        if (isset($visit_time['visit_time_from'])){
                            $visitTime = $visit_time['visit_time_from'];
                        }
                        if (empty($visitTime)) {
                            if (isset($results['DemandInfo__contact_desired_time'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time'];
                            }
                            if (isset($results['DemandInfo__contact_desired_time_from'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time_from'];
                            }
                        }

                        $target_date = date("Y-m-d H:i:s", strtotime($visitTime . '-' . $num . " minute"));
                    @endphp

                    @if (strtotime($target_date) <= strtotime(date("Y-m-d H:i:s")) ||
                        $results['MCorp__auction_masking'] != $div_value['without'] ||
                        isset($visit_time['visit_adjust_time']) )
                        <a href="{{ checkDevice().$results['DemandInfo__tel1'] }}" class="link-primary text--underline">{{ $results['DemandInfo__tel1'] }}</a>
                    @else
                        {{ !empty($results['DemandInfo__tel1']) ? substr_replace($results['DemandInfo__tel1'], "******", -6, 6) : '' }}
                    @endif
                @endif
                </td>
                <th class="odd">{{ trans('commission_detail.contact_2') }}</th>
                <td data-label="{{ trans('commission_detail.contact_2') }}">
                @if ($auth != 'affiliation' ||
                        ($results['DemandInfo__selection_system'] != $div_value['auction_selection'] && $results['DemandInfo__selection_system'] != $div_value['automatic_auction_selection']) ||
                        ($results['DemandInfo__contact_desired_time'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) ||
                        ($results['DemandInfo__contact_desired_time_from'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) )
                    <a href="{{ checkDevice().$results['DemandInfo__tel2'] }}" class="link-primary text--underline">{{ $results['DemandInfo__tel2'] }}</a>
                @else
                    @php
                        $hour = 0;
                        $minute = 0;
                        if (isset($tel_disclosure[$results['DemandInfo__priority']]['item_hour_date'])) {
                            $hour = $tel_disclosure[$results['DemandInfo__priority']]['item_hour_date'];
                        }
                        if (isset($tel_disclosure[$results['DemandInfo__priority']]['item_minute_date'])) {
                            $minute = $tel_disclosure[$results['DemandInfo__priority']]['item_minute_date'];
                        }
                        if (!empty($hour)) {
                            $hour = $hour * 60;
                        }
                        $num = $hour + $minute;
                        if (isset($visit_time['visit_time'])) {
                            $visitTime = $visit_time['visit_time'];
                        }
                        if (isset($visit_time['visit_time_from'])){
                            $visitTime = $visit_time['visit_time_from'];
                        }
                        if (empty($visitTime)) {
                            if (isset($results['DemandInfo__contact_desired_time'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time'];
                            }
                            if (isset($results['DemandInfo__contact_desired_time_from'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time_from'];
                            }
                        }
                        $target_date = date("Y-m-d H:i:s", strtotime($visitTime . '-' . $num . " minute"));
                    @endphp

                    @if (strtotime($target_date) <= strtotime(date("Y-m-d H:i:s")) ||
                        $results['MCorp__auction_masking'] != $div_value['without'] ||
                        isset($visit_time['VisitTime']['visit_adjust_time']) )
                        <a href="{{ checkDevice().$results['DemandInfo__tel2'] }}" class="link-primary text--underline">{{ $results['DemandInfo__tel2'] }}</a>
                    @else
                        @php
                            echo !empty($results['DemandInfo__tel2']) ? substr_replace($results['DemandInfo__tel2'], "******", -6, 6) : '';
                        @endphp
                    @endif
                @endif
            </td>
        </tr>
        <tr>
            <th>{{ trans('commission_detail.prefecture') }}</th>
            <td data-label="{{ trans('commission_detail.prefecture') }}">{{ getDivTextJP('prefecture_div', $results['DemandInfo__address1']) }}</td>
            <th>{{ trans('commission_detail.municipality') }}</th>
            <td data-label="{{ trans('commission_detail.municipality') }}">{{ $results['DemandInfo__address2'] }}</td>
            <th>{!! trans('commission_detail.later_address') !!}</th>
            <td data-label="{!! trans('commission_detail.later_address') !!}" class="d-none d-md-table-cell">
                @if ($auth != 'affiliation' ||
                        ($results['DemandInfo__selection_system'] != $div_value['auction_selection'] && $results['DemandInfo__selection_system'] != $div_value['automatic_auction_selection']) ||
                        ($results['DemandInfo__contact_desired_time'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) ||
                        ($results['DemandInfo__contact_desired_time_from'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) )
                    {{ $results['DemandInfo__address3'] }}
                @else
                    @php
                        $hour = 0;
                        $minute = 0;
                        if (isset($address_disclosure[$results['DemandInfo__priority']]['item_hour_date'])) {
                            $hour = $address_disclosure[$results['DemandInfo__priority']]['item_hour_date'];
                        }
                        if (isset($address_disclosure[$results['DemandInfo__priority']]['item_minute_date'])) {
                            $minute = $address_disclosure[$results['DemandInfo__priority']]['item_minute_date'];
                        }
                        if (!empty($hour)) {
                            $hour = $hour * 60;
                        }
                        $num = $hour + $minute;
                        if (isset($visit_time['visit_time'])) {
                            $visitTime = $visit_time['visit_time'];
                        }
                        if (isset($visit_time['visit_time_from'])){
                            $visitTime = $visit_time['visit_time_from'];
                        }
                        if (empty($visitTime)) {
                            if (isset($results['DemandInfo__contact_desired_time'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time'];
                            }
                            if (isset($results['DemandInfo__contact_desired_time_from'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time_from'];
                            }
                        }

                        $target_date = date("Y-m-d H:i:s", strtotime($visitTime . '-' . $num . " minute"));
                    @endphp

                    @if (strtotime($target_date) <= strtotime(date("Y-m-d H:i:s")) ||
                        $results['MCorp__auction_masking'] == $div_value['all_exclusion'] ||
                        isset($visit_time['visit_adjust_time']) )
                        {{ $results['DemandInfo__address3'] }}
                    @else
                        {{ maskingAddress3($results['DemandInfo__address3']) }}
                    @endif
                @endif
                <br>
                @php
                    echo getDropText('建物種別',$results['DemandInfo__construction_class']);
                @endphp
            </td>
            <td data-label="{{ trans('commission_detail.later_address_1') }}" class="d-md-none">
                @if ($auth != 'affiliation' ||
                                        ($results['DemandInfo__selection_system'] != $div_value['auction_selection'] && $results['DemandInfo__selection_system'] != $div_value['automatic_auction_selection']) ||
                                        ($results['DemandInfo__contact_desired_time'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) ||
                                        ($results['DemandInfo__contact_desired_time_from'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) )
                    {{ $results['DemandInfo__address3'] }}
                @else
                    @php
                        $hour = 0;
                        $minute = 0;
                        if (isset($address_disclosure[$results['DemandInfo__priority']]['item_hour_date'])) {
                            $hour = $address_disclosure[$results['DemandInfo__priority']]['item_hour_date'];
                        }
                        if (isset($address_disclosure[$results['DemandInfo__priority']]['item_minute_date'])) {
                            $minute = $address_disclosure[$results['DemandInfo__priority']]['item_minute_date'];
                        }
                        if (!empty($hour)) {
                            $hour = $hour * 60;
                        }
                        $num = $hour + $minute;
                        if (isset($visit_time['visit_time'])) {
                            $visitTime = $visit_time['visit_time'];
                        }
                        if (isset($visit_time['visit_time_from'])){
                            $visitTime = $visit_time['visit_time_from'];
                        }
                        if (empty($visitTime)) {
                            if (isset($results['DemandInfo__contact_desired_time'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time'];
                            }
                            if (isset($results['DemandInfo__contact_desired_time_from'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time_from'];
                            }
                        }

                        $target_date = date("Y-m-d H:i:s", strtotime($visitTime . '-' . $num . " minute"));
                    @endphp

                    @if (strtotime($target_date) <= strtotime(date("Y-m-d H:i:s")) ||
                        $results['MCorp__auction_masking'] == $div_value['all_exclusion'] ||
                        isset($visit_time['visit_adjust_time']) )
                        {{ $results['DemandInfo__address3'] }}
                    @else
                        @php
                            echo maskingAddress3($results['DemandInfo__address3']);
                        @endphp
                    @endif
                @endif
            </td>
            <td data-label="{{ trans('commission_detail.later_address_2') }}" class="d-md-none">
                @php
                    echo getDropText('建物種別',$results['DemandInfo__construction_class']);
                @endphp
            </td>
        </tr>
        <tr>
            <th class="odd">{{ trans('commission_detail.mail_address') }}</th>
            <td data-label="{{ trans('commission_detail.mail_address') }}">
                @if ($auth != 'affiliation' ||
                    ($results['DemandInfo__selection_system'] != $div_value['auction_selection'] && $results['DemandInfo__selection_system'] != $div_value['automatic_auction_selection']) ||
                    ($results['DemandInfo__contact_desired_time'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) ||
                    ($results['DemandInfo__contact_desired_time_from'] != '' && $results['MCorp__auction_masking'] == $div_value['without']) )
                    <a href="mailto:{{ $results['DemandInfo__customer_mailaddress'] }}" class="link-primary text--underline">{{ $results['DemandInfo__customer_mailaddress'] }}</a>
                @else
                    @php
                        $hour = 0;
                        $minute = 0;
                        if (isset($tel_disclosure[$results['DemandInfo__priority']]['item_hour_date'])) {
                            $hour = $tel_disclosure[$results['DemandInfo__priority']]['item_hour_date'];
                        }
                        if (isset($tel_disclosure[$results['DemandInfo__priority']]['item_minute_date'])) {
                            $minute = $tel_disclosure[$results['DemandInfo__priority']]['item_minute_date'];
                        }
                        if (!empty($hour)) {
                            $hour = $hour * 60;
                        }
                        $num = $hour + $minute;
                        if (isset($visit_time['visit_time'])) {
                            $visitTime = $visit_time['visit_time'];
                        }
                        if (isset($visit_time['visit_time_from'])){
                            $visitTime = $visit_time['visit_time_from'];
                        }
                        if (empty($visitTime)) {
                            if (isset($results['DemandInfo__contact_desired_time'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time'];
                            }
                            if (isset($results['DemandInfo__contact_desired_time_from'])) {
                                $visitTime = $results['DemandInfo__contact_desired_time_from'];
                            }
                        }

                        $target_date = date("Y-m-d H:i:s", strtotime($visitTime . '-' . $num . " minute"));
                    @endphp

                    @if (strtotime($target_date) <= strtotime(date("Y-m-d H:i:s")) ||
                        $results['MCorp__auction_masking'] != $div_value['without'] ||
                        isset($visit_time['visit_adjust_time']) )
                        <a href="mailto:{{ $results['DemandInfo__customer_mailaddress'] }}" class="link-primary text--underline">{{ $results['DemandInfo__customer_mailaddress'] }}</a>
                    @else
                        @php
                            echo !empty($results['DemandInfo__customer_mailaddress']) ? substr_replace($results['DemandInfo__customer_mailaddress'], "******", 0, 6) : '';
                        @endphp
                    @endif
                @endif
            </td>
            <th class="odd">{{ trans('commission_detail.jpr_receipt_no') }}</th>
            <td data-label="{{ trans('commission_detail.jpr_receipt_no') }}">{{ $results['DemandInfo__jbr_order_no'] }}</td>
            <th class="odd">{{ trans('commission_detail.corporate_name') }}</th>
            <td data-label="{{ trans('commission_detail.corporate_name') }}">{{ $results['DemandInfo__customer_corp_name'] }}</td>
        </tr>
        <tr>
            <th>{{ trans('commission_detail.genre') }}</th>
            <td data-label="{{ trans('commission_detail.genre') }}">{{ $results['MGenre__genre_name'] }}</td>
            <th>{{ trans('commission_detail.site_name') }}</th>
            <td colspan="3" data-label="{{ trans('commission_detail.site_name') }}">
                @if (!empty($site_list['site_url']) && $site_list['site_url'] != '-')
                    <a href="http://{{ $site_list['site_url'] }}" target="_blank" class="link-primary text--underline">{{ $site_list['site_name'] }}</a>
                @else
                    {{ $site_list['site_name'] }}
                @endif
                <button type="button" class="btn btn-outline-primary float-right site_launch_details_open d-none d-md-inline-block" id="site_launch_details_open">サイト打ち出し詳細 ≫</button>
            </td>
        </tr>
        <tr>
            <th class="odd">{{ trans('commission_detail.business_trip') }}</th>
            <td data-label="{{ trans('commission_detail.business_trip') }}">{{ $results['DemandInfo__business_trip_amount'] }}
                @php
                    echo isset($results['DemandInfo__business_trip_amount']) ? trans('common.yen') : '';
                @endphp
            </td>
            <th class="odd">{{ trans('commission_detail.cost_from') }}</th>
            <td colspan="3" data-label="{{ trans('commission_detail.cost_from') }}">{{ $results['DemandInfo__cost_from'] }}
            @if (isset($results['DemandInfo__cost_from']))
                 {!! trans('common.yen') !!}　{{trans('common.wavy_seal')}}　'
            @endif
            {{ $results['DemandInfo__cost_to'] }}
            @if (isset($results['DemandInfo__cost_to']))
            {!! trans('common.yen') !!}
            @endif
            </td>
        </tr>
        <tr>
            <th colspan="6" class="contactCont">{{ trans('commission_detail.content_of_consulation') }}</th>
        </tr>
        <tr>
            <td colspan="6" class="last" data-label="{{ trans('commission_detail.content_of_consulation') }}">
                <div>
                    @php
                        echo nl2br($results['DemandInfo__contents']);
                    @endphp
                </div>
            </td>
        </tr>
    </tbody>
</table>
</div>

</div>
