<div class="custom-scroll-x">
    <table class="table custom-table add-pseudo-scroll-bar">
        <thead class="text-center bg-yellow-light">
            <tr>
                <th rowspan="2" class="p-1 align-middle border-bottom-bold fix-w-85">{{ __('demand_detail.received_date') }}</th>
                <th rowspan="2" class="p-1 align-middle border-bottom-bold fix-w-50">{{ __('demand_detail.proposal_number') }}<br>{{ __('demand_detail.customer_name') }}</th>
                <th rowspan="2" class="p-1 align-middle border-bottom-bold fix-w-50">{{ __('demand_detail.content') }}</th>
                <th rowspan="2" class="p-1 align-middle border-bottom-bold fix-w-91">{{ __('demand_detail.commission_rate') }}<br/>{{ __('demand_detail.commission_fee') }}</th>
                <th colspan="3" class="p-1 align-middle border-bottom">{{ __('demand_detail.company_management_status') }}</th>
                <th class="p-1 align-middle border-bottom">{{ __('demand_detail.required_item') }}</th>
                <th rowspan="2" class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.status_after_change') }}</th>
                <th rowspan="2" class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.completed_date') }}<br/>{{ __('demand_detail.lost_date') }}</th>
                <th rowspan="2" class="p-1 align-middle border-bottom-bold fix-w-103">{{ __('demand_detail.tax_excluded') }}<br>{{ __('demand_detail.tax_included') }}</th>
                <th rowspan="2" class="p-1 align-middle border-bottom-bold fix-w-150">{{ __('demand_detail.reason_for_losing') }}</th>
                <th rowspan="2" class="p-1 align-middle border-bottom-bold fix-w-120">{{ __('demand_detail.remarks') }}</th>
            </tr>
            <tr>
                <th class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.completed_date') }}<br>{{ __('demand_detail.lost_date') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-103">{{ __('demand_detail.tax_excluded') }}<br>{{ __('demand_detail.tax_included') }}<br>{{ __('demand_detail.commission_amount') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-50">{{ __('demand_detail.our_company') }}<br/>{{ __('demand_detail.management_status1') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-150">{{ __('demand_detail.management_status2') }}<br>{{ __('demand_detail.changes_question') }}</th>
            </tr>
        </thead>
            <tbody class="fs-13">
            @foreach($data['ProgDemandInfo'] as $k => $demandInfo)
                <tr class="formRow check-require">
                    <td class="p-1 align-middle border-bottom fix-w-85 text-center">
                        <input name="ProgDemandInfo[{{ $k }}][id]" type="hidden" value="{{ $demandInfo['id']}}">
                        <input name="ProgDemandInfo[{{ $k }}][demand_id]" type="hidden" value="{{ $demandInfo['demand_id']}}">
                        <input name="ProgDemandInfo[{{ $k }}][commission_id]" type="hidden" value="{{ $demandInfo['commission_id']}}">
                        <input name="ProgDemandInfo[{{ $k }}][receive_datetime]" type="hidden" value="{{ $demandInfo['receive_datetime']}}">
                        <input name="ProgDemandInfo[{{ $k }}][customer_name]" type="hidden" value="{{ $demandInfo['customer_name']}}">
                        <input name="ProgDemandInfo[{{ $k }}][category_name]" type="hidden" value="{{ $demandInfo['category_name']}}">
                        <input name="ProgDemandInfo[{{ $k }}][complete_date]" type="hidden" value="{{ $demandInfo['complete_date']}}">
                        <input name="ProgDemandInfo[{{ $k }}][construction_price_tax_exclude]" type="hidden" value="{{ $demandInfo['construction_price_tax_exclude']}}">
                        <input name="ProgDemandInfo[{{ $k }}][construction_price_tax_include]" type="hidden" value="{{ $demandInfo['construction_price_tax_include']}}">
                        <input name="ProgDemandInfo[{{ $k }}][fee_target_price]" type="hidden" value="{{ $demandInfo['fee_target_price']}}">
                        <input id="orgStatus" name="ProgDemandInfo[{{ $k }}][commission_status]" type="hidden" value="{{ $demandInfo['commission_status']}}">
                        <input name="ProgDemandInfo[{{ $k }}][fee]" type="hidden" value="{{ $demandInfo['fee']}}">
                        <input name="ProgDemandInfo[{{ $k }}][fee_rate]" type="hidden" value="{{ $demandInfo['fee_rate']}}">

                        {{ date('Y/m/d', strtotime($demandInfo['receive_datetime'])) }}<br>
                        {{ date('h:i', strtotime($demandInfo['receive_datetime'])) }}
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-50 text-wrap">
                        {{ $demandInfo['demand_id'] }} <br>
                        {{ $demandInfo['customer_name'] }}
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-50">
                        {{ $demandInfo['category_name'] }}
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-91 text-right">
                        @if(!empty($demandInfo['fee']))
                            {{ formatMoney($demandInfo['fee']) }} 円
                        @elseif(!empty($demandInfo['fee_rate']))
                            {{ $demandInfo['fee_rate'] }}%
                        @endif
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-100 text-center">
                        @if(!empty($demandInfo['complete_date']))
                            {{ date('Y/m/d', strtotime($demandInfo['complete_date'])) }}
                        @endif
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-103 text-right">
                        @if(!empty($demandInfo['construction_price_tax_exclude']))
                            {{ formatMoney($demandInfo['construction_price_tax_exclude']) }}
                        @else
                            -
                        @endif
                        円<br>

                        @if(!empty($demandInfo['construction_price_tax_include']))
                            {{ formatMoney($demandInfo['construction_price_tax_include']) }}
                        @else
                            -
                        @endif
                        円<br>

                        @if(!empty($demandInfo['fee_target_price']))
                            {{ formatMoney($demandInfo['fee_target_price']) }}
                        @else
                            -
                        @endif
                        円
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-50 text-left">
                        {{ $commissionStatus[$demandInfo['commission_status']] ?? '' }}
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-150">
                        <select name="ProgDemandInfo[{{ $k }}][diff_flg]" class="diff form-control p-1 fix-height-select">
                            @foreach($diffFlags as $key => $valueOption)
                                <option value="{{ $key }}"
                                @if($key == $demandInfo['diff_flg']) selected @endif
                                >{{$valueOption}}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" hidden>@lang('demand_detail.required_mess')</div>
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-100">
                        <select name="ProgDemandInfo[{{ $k }}][commission_status_update]" class="csu update status form-control p-1 fix-height-select">
                            @foreach($commissionStatus as $key => $valueOption)
                                <option value="{{ $key }}"
                                        @if($key == $demandInfo['commission_status_update']) selected @endif
                                >{{$valueOption}}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" hidden>@lang('demand_detail.required_mess')</div>
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-100">
                        <input type="text" name="ProgDemandInfo[{{ $k }}][complete_date_update]" value="{{$demandInfo['complete_date_update']}}" class="datepicker_limit update completeDate form-control p-1">
                        <div class="text-danger" hidden>@lang('demand_detail.required_mess')</div>
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-103">
                        <div class="align-items-center d-flex mb-1 text-right">
                            <input type="text" name="ProgDemandInfo[{{ $k }}][construction_price_tax_exclude_update]" value="{{$demandInfo['construction_price_tax_exclude_update']}}" class="totalCost update form-control p-1">
                            <strong class="ml-1">円</strong>
                        </div>
                        <div class="align-items-center d-flex mb-1 text-right">
                            <input type="text" name="ProgDemandInfo[{{ $k }}][construction_price_tax_include_update]" value="{{$demandInfo['construction_price_tax_include_update']}}" class="totalCostTaxInclude form-control p-1" disabled>
                            <strong class="ml-1">円</strong>
                        </div>
                        <div class="text-danger" hidden>@lang('demand_detail.required_mess')</div>
                        <div class="err-decimal" hidden>@lang('demand_detail.err_decimal')</div>
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-150">
                        <select class="update failReason form-control p-1 fix-height-select" name="ProgDemandInfo[{{ $k }}][commission_order_fail_reason_update]">
                            @foreach($commissionOrderFailReasonList as $key => $commissionOrder)
                                <option @if($key != 0)
                                            value="{{$key}}"
                                            @if($key == $demandInfo['commission_order_fail_reason_update'])
                                                selected
                                            @endif
                                        @else
                                            value=""
                                        @endif
                                >{{$commissionOrder}}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" hidden>@lang('demand_detail.required_mess')</div>
                    </td>
                    <td class="p-1 align-middle border-bottom fix-w-120">
                        <textarea type="text" name="ProgDemandInfo[{{ $k }}][comment_update]" class="comment form-control p-1">{{$demandInfo['comment_update']}}</textarea>
                        <div class="text-danger" hidden>@lang('demand_detail.required_mess')</div>
                    </td>
                </tr>
                @php
                    $pDate1 = $demandInfo['receive_datetime'];
                    $pDate2 = date('Y-m-d H:i:s');
                    $TimeStamp1 = strtotime($pDate1);
                    $TimeStamp2 = strtotime($pDate2);
                    $SecondDiff = abs($TimeStamp2 - $TimeStamp1);
                    $DayDiff = round($SecondDiff / (60 * 60 * 24));
                @endphp
                @if($DayDiff >= 90 && $demandInfo['commission_status'] == 3)
                    <td colspan="13" class="font-weight-bold bg-yellow-light">
                        {{ __('demand_detail.describe_current_situation') }}
                        <input type="hidden" name="ProgDemandInfo[{{$k}}][long_date]" value="1">
                    </td>
                @endif
            @endforeach
            </tbody>
    </table>
</div>
<div class="pseudo-scroll-bar" data-display="false">
    <div class="scroll-bar"></div>
</div>
<div>
    <input type="hidden" id="consTax" value="{{ $tax }}" />
</div>
