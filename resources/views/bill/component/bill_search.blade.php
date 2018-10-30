@if (count($billList))
    <div class="row table-result-search">
        <div class="col">
            <div class="text-danger font-weight-bold" id="valid-checkbox-table"></div>
            <div class="custom-scroll-x">
                <table class="table table-bordered table-list" id="tbBillSearch">
                    <thead>
                    <tr>
                        <th class="align-middle">@lang('bill_list.proposal_number')</th>
                        <th class="align-middle">@lang('bill_list.customer_name')</th>
                        <th class="align-middle">@lang('bill_list.content')</th>
                        <th class="align-middle">@lang('bill_list.complete_date')</th>
                        <th class="align-middle">@lang('bill_list.claim')</th>
                        @if($billSession[0]['bill_status'] != getDivValue('bill_status','not_issue'))
                            <th class="align-middle fix-w-250">@lang('bill_list.amount')
                                @if($billSession[0]['bill_status'] != getDivValue('bill_status', 'payment'))
                                    <br>
                                    <input type="checkbox" value="" name="fee_all_check" id="fee_all_check">
                                @endif
                            </th>
                            <th class="align-middle fix-w-100">@lang('bill_list.balance')</th>
                        @endif
                        @if($billSession[0]['bill_status'] != getDivValue('bill_status', 'payment'))
                            <th class="align-middle">@lang('bill_list.object')<br>
                                <input type="checkbox" name="all_check" id="all_check" class="out_line">
                            </th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($billList as $key => $list)
                        @php
                            $feePaymentPriceDis = (!empty($list['fee_payment_price'])) ? $list['fee_payment_price'] : 0;
                            $feePaymentBalanceDis = $list['total_bill_price'] - $feePaymentPriceDis;
                        @endphp
                        <tr>
                            <td class="p-1 align-middle text-center">
                                <a class="highlight-link" href="{{route('bill.bill_detail',$list['id'])}}">{{ $list['demand_id'] }}</a>
                                <input name="id[]" type="hidden" value="{{$list['id']}}" id="id{{$key}}">
                            </td>
                            <td class="p-1 align-middle">
                                {{ $list['customer_name'] }}
                            </td>
                            <td class="p-1 align-middle">
                                @if(empty($list['auction_id']))
                                    @if(array_key_exists($list['category_id'], $categoryList))
                                        {{$categoryList[$list['category_id']] }}
                                    @endif
                                @else
                                    {{trans('bill_list.bidding_fee')}}
                                @endif
                            </td>
                            <td class="p-1 align-middle text-center">
                                {{ $list['complete_date'] }}
                            </td>
                            <td class="p-1 align-middle text-center">
                                {{ yenFormat2($list['total_bill_price']) }}
                                <input type="hidden" value="{{ $list['total_bill_price'] }}"
                                       name="total_bill_price[]" id="total_bill_price{{$key}}">
                            </td>
                            @if($billSession[0]['bill_status'] != getDivValue('bill_status', 'not_issue'))
                                <td class="p-1">
                                    <div class="d-flex justify-content-center">
                                    
                                        @if($billSession[0]['bill_status'] == getDivValue('bill_status', 'issue'))
                                        <div>
                                            <input type="text" name="fee_payment_price[]"
                                                data-rule-number='true'  data-rule-required="true"
                                                value="{{ $feePaymentPriceDis }}" id="fee_payment_price{{$key}}"
                                                data-fee={{ $key }} class="fee_target_input form-control"
                                                maxlength="10">
                                        </div>
                                        <div class="ml-1 mt-2">
                                            @lang('bill_list.yen')
                                        </div>                                            
                                        <input type="checkbox" value="{{$key}}" name="checkbox[]"
                                                class="fee_target_checkbox ml-1 mt-2" id="checkbox{{$key}}">
                                        @else
                                            {{ yenFormat2($feePaymentPriceDis) }}
                                            <input type="hidden" name="fee_payment_price[]"
                                                value="{{ $feePaymentPriceDis }}" id="fee_payment_price{{$key}}">
                                        @endif
                                    </div>
                                </td>
                                <td class="p-1 align-middle text-center">
                                <span id='fee_payment_balance_display{{$key}}'>
                                    {{ yenFormat2($feePaymentBalanceDis) }}
                                </span>
                                    <input type="hidden" value="{{ $feePaymentBalanceDis }}"
                                           name="fee_payment_balance[]" id="fee_payment_balance{{$key}}">
                                </td>
                            @endif
                            @if($billSession[0]['bill_status'] != getDivValue('bill_status', 'payment'))
                                <td class="p-1 align-middle text-center">
                                    <div id="checkAll">
                                        @if (strtotime(date('Y/m/1')) > strtotime($list['complete_date']))
                                            <input type="checkbox" id="target{{$key}}" name="target[]" value="{{$key}}" class="target_checkbox">
                                        @endif
                                    </div>
                                </td>
                            @endif
                            <input type="hidden" value="{{$list['modified']}}" name="modified[]"
                                   id="modified{{$key}}">
                        </tr>
                        @php
                            $totalBillPrice[] = $list['total_bill_price'];
                            $feePaymentPrice[] = $feePaymentPriceDis;
                            $feePaymentBalance[] = $feePaymentBalanceDis;
                            $count = $key;
                        @endphp
                    @endforeach
                    </tbody>
                    <thead>
                    @if($billSession[0]['bill_status'] != getDivValue( 'bill_status', 'payment' ))
                        <tr align="center">
                            <td colspan="4" class="bg-warning">@lang('bill_list.total')</td>
                            <td>{{yenFormat2(array_sum($totalBillPrice))}}</td>
                            @if($billSession[0]['bill_status'] != 1)
                                <td>
                                    <span id='all_fee_payment_price_display'>{{yenFormat2(array_sum($feePaymentPrice))}}</span>
                                </td>
                                <td>
                                    <span id='all_fee_payment_balance_display'>{{yenFormat2(array_sum($feePaymentBalance))}}</span>
                                </td>
                            @endif
                            <td></td>
                        </tr>
                    @endif
                    </thead>
                </table>
            </div>
            
            <input type="hidden" value="{{ $count }}" id="count" name="count">
            @php $billSessiontoPaginate = $billSession[0]['bill_status'] @endphp
            @if($billSession[0]['bill_status'] == getDivValue('bill_status', 'payment' ))
                {{ $billList->links('bill.component.bill_list_pagination') }}
            @endif
            <p class="text-right">
                @if($billSession[0]['bill_status'] != getDivValue('bill_status', 'not_issue'))
                    <input class="btn btn--gradient-default mt-1" id="history" name="history" type="button"
                           value="@lang('bill_list.history')">
                @endif
                @if ($billSession[0]['bill_status'] == getDivValue('bill_status', 'issue'))
                    <input type="submit" id="bill_save" value="@lang('bill_list.save')" name="save" id="save" class="btn btn--gradient-green mt-1">
                @endif
                @if ($billSession[0]['bill_status'] != getDivValue('bill_status', 'payment'))
                    <input type="button" name="bill_download" value="@lang('bill_list.bill_download')"
                           id="bill_download" class="btn btn--gradient-default mt-1">
                @endif
            </p>
        </div>
    </div>
@else
    <div class="row table-result-search">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-bordered table-list">
                    <thead>
                    <tr>
                        <th class="p-1 align-middle fix-w-100">@lang('bill_list.proposal_number')</th>
                        <th class="p-1 align-middle fix-w-100">@lang('bill_list.customer_name')</th>
                        <th class="p-1 align-middle fix-w-100">@lang('bill_list.content')</th>
                        <th class="p-1 align-middle fix-w-100">@lang('bill_list.complete_date')</th>
                        <th class="p-1 align-middle fix-w-100">@lang('bill_list.claim')</th>
                        @if($billSession[0]['bill_status'] != getDivValue('bill_status','not_issue'))
                            <th class="p-1 align-middle fix-w-100">@lang('bill_list.amount')
                                @if($billSession[0]['bill_status'] != getDivValue('bill_status', 'payment'))
                                    <br>
                                    <input type="checkbox" value="" name="fee_all_check" id="fee_all_check">
                                @endif
                            </th>
                            <th class="p-1 align-middle fix-w-100">@lang('bill_list.balance')</th>
                        @endif
                        @if($billSession[0]['bill_status'] != getDivValue('bill_status', 'payment'))
                            <th>@lang('bill_list.object')<br>
                                <input type="checkbox" name="all_check" id="all_check">
                            </th>
                        @endif
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endif
