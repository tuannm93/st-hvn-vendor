@php
    use App\Services\Auction\AuctionService;

@endphp
<input type="hidden" id="screen_support" value="{{ $screen }}">
<div class="auction-support text-center">
    @if($screen == config('constant.refusal.deal_already'))
        <p class="mb-0 text-danger">@lang('support.couldNotBuy')</p>
        <p class="mb-0">{{ dateTimeFormat($results['created'], 'Y年m月d日 H時i分')}}@lang('support.into')</p>
        <p class="mb-0">@lang('support.otherCompanyBid')</p>
        <div class="pt-3">
            <button type="button" class="btn btn--gradient-gray closeBtn">@lang('support.closeBtn')</button>
        </div>
    @elseif($screen == config('constant.refusal.support_limit'))
        <p class="mb-0">@lang('support.memberStore')</p>
        <p class="mb-0">@lang('support.beenDecide')</p>
        <p class="mb-0">@lang('support.contactDirectly')</p>
        <div class="pt-3">
            <button type="button" class="btn btn--gradient-gray closeBtn">@lang('support.closeBtn')</button>
        </div>
    @elseif($screen == config('constant.refusal.update_fail'))
        <p class="mb-0 text-danger">@lang('support.updateFail')</p>

        <div class="pt-3">
            <button type="button" class="btn btn--gradient-gray closeBtn">@lang('support.closeBtn')</button>
        </div>
    @elseif($screen == config('constant.refusal.support_past_time'))
        <p class="mb-0 text-danger">@lang('support.support_past_time_1')</p>
        <p class="mb-0">@lang('support.support_past_time_2')</p>
        <div class="pt-3">
            <button type="button" class="btn btn--gradient-gray closeBtn">@lang('support.closeBtn')</button>
        </div>
    @elseif($screen == config('constant.refusal.support_already'))
        <p class="mb-0">
            @lang('support.regardingTheDisclosure')
            @if(isset($address_disclosure[$demand_data['priority']]['item_hour_date']))
                {{ $address_disclosure[$demand_data['priority']]['item_hour_date'] }}@lang('support.time')
            @endif

            @if(isset($address_disclosure[$demand_data['priority']]['item_minute_date']))
                {{ $address_disclosure[$demand_data['priority']]['item_minute_date'] }}@lang('support.minute')
            @endif
            @lang('support.before')
        </p>
        <p class="mb-0">
            @lang('support.regardingTheDisclosurePhoneNumber')
            @if(isset($tel_disclosure[$demand_data['priority']]['item_hour_date']))
                {{ $tel_disclosure[$demand_data['priority']]['item_hour_date'] }}@lang('support.time')
            @endif

            @if(isset($tel_disclosure[$demand_data['priority']]['item_minute_date']))
                {{ $tel_disclosure[$demand_data['priority']]['item_minute_date'] }}@lang('support.minute')
            @endif
            @lang('support.before')
        </p>
        <p>@lang('support.willBeDisplayedIn')</p>
        <p class="mb-0"><small>@lang('support.beforehand')</small></p>
        <p><small>@lang('support.contactNew')</small></p>
        <div class="form-group">
            <button class="btn btn--gradient-orange closeBtn" type="button" id="btnDone">OK</button>
        </div>
        <div class="custom-control custom-checkbox mt-1">
            <input type="checkbox" name="data[popup_stop_flg]" class="custom-control-input" id="popup_stop_flg" value="1">
            <label class="custom-control-label" for="popup_stop_flg">@lang('support.doNotDisplayAgain')</label>
        </div>
    @else
        <form action="{{ route('auction.handle.support') }}" accept-charset="utf-8" id="supportForm" method="post" novalidate>
            {{ csrf_field() }}
            @if($data['demand_status'] == 6 || $data['demand_status'] == 9)
                <div id="lostOrder">
                    <p class="mb-0">@lang('support.lostOrder')</p>
                    <div class="pt-3">
                        <button id="closeBtn" class="btn btn--gradient-gray" type="button" data-dismiss="modal">@lang('support.closeBtn')</button>
                    </div>
                </div>
            @elseif(isset($data['site_id']) && $data['site_id'] == 585 && $data['jbr_available_status'] == 1)
                <div id="confirmation" class="pb-2">
                    <div class="pt-2 row justify-content-center mr-0 ml-0">
                        <input id="updateMcorpId" type="hidden" value="{{ $data['corp_id']}}">
                        <input id="understandBtn" class="btn btn--gradient-green" type="button" value="@lang('support.understandBtn')">
                    </div>
                    <div class="pt-2 row justify-content-center mr-0 ml-0">
                        <input id="notUnderstandBtn" class="btn btn--gradient-green" type="button" value="@lang('support.notUnderstandBtn')">
                    </div>
                </div>
            @endif
            <div id="kihon_info">
                @if(!empty($auction_fee))
                    <div class="font-weight-bold text-left pb-1">
                        <span>@lang('support.biddingFee'):</span>
                        <span class="text-danger">{{ yenFormat2($auction_fee)}}</span>
                    </div>
                    <div class="text-left">@lang('support.biddingFeeAgreementForm')</div>
                    <div class="auction_fee pt-2 pb-4 pr-1 mt-2 text-left rounded border border-dark">{!! $auction_provision !!}</div>
                    <div class="custom-control custom-checkbox mt-2">
                        <input type="checkbox" data-rule-required="true" data-msg-required="@lang('support.mustAgreement')" data-error-container="#agreement_check_feedback" class="custom-control-input" name="data[agreement_check]" id="agreement_check" value="1">
                        <label class="custom-control-label" for="agreement_check">@lang('support.proposalNumber'){{ $data['demand_id']}}@lang('support.agreeBid')</label>
                    </div>
                    <div id="agreement_check_feedback" ></div>
                @endif

                <div class="pt-1 pb-1 text--orange font-weight-bold">@lang('support.nameOfPersonInCharge')</div>
                <div class="row justify-content-center">
                    <input name="data[responders]" data-error-container="#responders_feedback" data-rule-required="true" id="responders" class="form-control col col-8 col-sm-6 col-md-6" maxlength="40" type="text">
                </div>
                <div id="responders_feedback" class="pl-2"></div>
                <div class="text-left pt-3">
                    @if(count($visit_list) > 1)
                        <div>@lang('support.timeOfVisit')　　：</div>
                        <div data-group-required="true" class="pl-2">
                            @foreach($visit_list as $key => $val)
                                <div class="custom-control custom-radio">
                                    @if(isset($val['visit_time']))
                                        <input class="custom-control-input" name="data[visit_time_id]" type="radio" id="exampleRadios{{ $val['id'] }}" value="{{ $val['id'] }}">
                                        <label class="custom-control-label" for="exampleRadios{{ $val['id'] }}">{{ dateTimeWeek($val['visit_time'], '%Y年%m月%d日(%a)%R') }}</label>
                                    @else
                                        <input class="custom-control-input" id="exampleRadios{{ $val['id'] }}" type="radio" name="data[visit_time_id]" value="{{ $val['id'] }}">
                                        <label class="custom-control-label" for="exampleRadios{{ $val['id'] }}">
                                            {{ dateTimeWeek($val['visit_time_from'], '%Y年%m月%d日(%a)%R') }} {{trans('common.wavy_seal')}} {{ dateTimeWeek($val['visit_time_to'], '%Y年%m月%d日(%a)%R') }}
                                            <br>
                                            @lang('support.telephoneDate')： {{ dateTimeWeek($val['visit_adjust_time'], '%Y年%m月%d日(%a)%R') }}
                                        </label>
                                    @endif
                                </div>
                                <br>
                            @endforeach
                        </div>
                        @if(!isset($visit_list[$key]['visit_time']))
                            <div class="text-danger pl-2">@lang('support.mesFail1')<br>　@lang('support.mesFail2')</div>
                        @endif
                    @elseif(count($visit_list) == 1)
                        <input type="hidden" value="{{ $visit_list[0]['id'] }}" name="data[visit_time_id]">
                        @if(isset($visit_list[0]['visit_time']))
                            @lang('support.timeOfVisit')　　：{!! dateTimeWeek($visit_list[0]['visit_time'], '%Y年%m月%d日(%a)%R') !!}
                        @else
                            @lang('support.timeOfVisit')　　：{!! dateTimeWeek($visit_list[0]['visit_time_from'], '%Y年%m月%d日(%a)%R') !!} {{trans('common.wavy_seal')}} {!! dateTimeWeek($visit_list[0]['visit_time_to'], '%Y年%m月%d日(%a)%R') !!}
                            <br>
                            @lang('support.telephoneDate')：{!! dateTimeWeek($visit_list[0]['visit_adjust_time'], '%Y年%m月%d日(%a)%R') !!}
                            <div class="text-danger">@lang('support.mesFail1')<br>　@lang('support.mesFail2')</div>
                        @endif
                    @else
                        <input type="hidden" value="{{ $data['contact_desired_time'] }}" name="data[contact_desired_time]">
                        @if(isset($data['contact_desired_time']))
                            @lang('support.telephoneDate')：{!! dateTimeWeek($data['contact_desired_time'], '%Y年%m月%d日(%a)%R') !!}
                        @else
                            @lang('support.telephoneDate')：{!! dateTimeWeek($data['contact_desired_time_from'], '%Y年%m月%d日(%a)%R') !!} {{trans('common.wavy_seal')}} {!! dateTimeWeek($data['contact_desired_time_to'], '%Y年%m月%d日(%a)%R') !!}
                        @endif
                    @endif
                </div>
                <div class="text-left pt-2">
                    <input type="hidden" value="{{ $data['cost_from'] }}" name="data[cost_from]">
                    <input type="hidden" value="{{ $data['cost_to'] }}" name="data[cost_to]">

                    @if(empty($data['cost_from']) && empty($data['cost_to']))
                        @lang('support.priceOffer')　　：@lang('support.none')
                    @elseif(!empty($data['cost_from']) && !empty($data['cost_to']))
                        @lang('support.priceOffer')　　：{{ yenFormat2($data['cost_from']) }} {{trans('common.wavy_seal')}} {{ yenFormat2($data['cost_to']) }}
                    @else
                        @if(!empty($data['cost_from']))
                            @lang('support.priceOffer')　　：{{ yenFormat2($data['cost_from']) }}
                        @else
                            @lang('support.priceOffer')　　：{{ yenFormat2($data['cost_to']) }}
                        @endif
                    @endif

                </div>
                <div class="form-group mt-3">
                    <input type="hidden" value="{{ $data['demand_id'] }}" name="data[demand_id]">
                    <input type="hidden" value="{{ $data['id'] }}" name="data[id]">
                    <input type="hidden" value="{{ $data['corp_id'] }}" name="data[corp_id]">
                    <input type="hidden" value="{{ $data['push_time'] }}" name="data[push_time]">
                    <input type="hidden" value="{{ $data['business_trip_amount'] }}" name="data[business_trip_amount]">
                    <input type="hidden" value="{{ $data['auction_deadline_time'] }}" name="data[auction_deadline_time]">
                    <input type="hidden" value="{{ $data['demand_status'] }}" name="data[demand_status]">
                    <input type="hidden" value="{{ $data['site_id'] }}" name="data[site_id]">
                    <input type="hidden" value="{{ $data['jbr_available_status'] }}" name="data[jbr_available_status]">

                    <button name="completion" id="completion" data-url="{{ route('auction.support', ['id' => $auctionId]) }}" class="btn btn--gradient-green" type="button">@lang('support.completion')</button>
                </div>
            </div>
            <div id="contact">
                <p class="mb-0">@lang('support.contact')</p>
                <div class="pt-3">
                    <button class="btn btn--gradient-gray closeBtn" type="button">@lang('support.closeBtn')</button>
                </div>
            </div>
        </form>
    @endif
</div>
