@extends('layouts.app')

@php
    $isError = $errors->any();
@endphp

@section('content')
<div class="auction-index">
    @component('auction_setting.components.tabs')
    @endcomponent
    @if (Session::has('success'))
        <div class="alert alert-success mt-3">{{ Session::get('success') }}</div>
    @endif
    @if ($isError)
        <div class="alert alert-danger mt-3">{{ __('mtime.message_failure') }}</div>
    @endif

    <form action="{{action('Auction\AuctionSettingController@index')}}" method="post" novalidate id="formAuctionIndex">
        <input type="hidden" value="{{csrf_token()}}" id="_token" name="_token">
        @php $isDisabled = Auth::user()->auth == 'accounting_admin' ? true : false @endphp
        <div class="form-category mt-3 pb-3">
            <label class="form-category__label">{{trans('mtime.address_disclosure_time_setting') }}</label>
            <div class="container-fluid pl-5">
                <div class="form-inline mt-1">
                    <div class="case-title my-auto">{{trans('mtime.usual_case') }}</div>
                    <div class="case-content my-auto">
                        <div class="form-inline">
                            <input type="hidden" value="{{ $data[0]->id }}" id="id{{ $data[0]->id }}" name="id{{ $data[0]->id }}">
                            <input type="hidden" value="address_disclosure" id="item_category{{ $data[0]->id }}" name="item_category{{ $data[0]->id }}">
                            <input type="hidden" value="normal" id="item_detail{{ $data[0]->id }}" name="item_detail{{ $data[0]->id }}">
                            <input type="hidden" value="0" id="item_type{{ $data[0]->id }}" name="item_type{{ $data[0]->id }}">
                            <input type="hidden" value="1" id="item_id{{ $data[0]->id }}" name="item_id{{ $data[0]->id }}">
                            <label class="pr-3">{{trans('mtime.interview_time') }}</label>
                            <select data-error-container="#MTime0ItemHourDate-feedback" data-rule-required="true" class="form-control p-0" size="1" name="item_hour_date{{ $data[0]->id }}" id="MTime0ItemHourDate" {{$isDisabled ? 'disabled' : ''}} >
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($hourList as $key =>$item )
                                    <option {{$errors->has('item_hour_date1') ? '' : (isset($data[0]->item_hour_date) && $data[0]->item_hour_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.time') }}</label>
                        <select data-error-container="#MTime0ItemMinuteDate-feedback" data-msg-pattern="{{ trans('mtime.msg_error_required') }}" data-rule-pattern="[^0\s][0-9]*" class="form-control p-0" size="1" name="item_minute_date{{ $data[0]->id }}" id="MTime0ItemMinuteDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($minuteList as $key =>$item )
                                    <option {{$errors->has('item_minute_date1') ? '' : (isset($data[0]->item_minute_date) && $data[0]->item_minute_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.before') }}</label>
                        </div>
                        <div id="MTime0ItemHourDate-feedback"></div>
                        <div id="MTime0ItemMinuteDate-feedback"></div>
                    </div>
                </div>
                <div class="form-inline mt-1">
                    <div class="case-title my-auto">{{trans('mtime.urgent_case') }}</div>
                    <div class="case-content my-auto">
                        <div class="form-inline">
                            <input type="hidden" value="{{ $data[1]->id }}" id="id{{ $data[1]->id }}" name="id{{ $data[1]->id }}">
                            <input type="hidden" value="address_disclosure" id="item_category{{ $data[1]->id }}" name="item_category{{ $data[1]->id }}">
                            <input type="hidden" value="immediately" id="item_detail{{ $data[1]->id }}" name="item_detail{{ $data[1]->id }}">
                            <input type="hidden" value="0" id="item_type{{ $data[1]->id }}" name="item_type{{ $data[1]->id }}">
                            <input type="hidden" value="2" id="item_id{{ $data[1]->id }}" name="item_id{{ $data[1]->id }}">
                            <label class="pr-3">{{trans('mtime.interview_time') }}</label>
                            <select data-error-container="#MTime1ItemHourDate-feedback" data-rule-required="true" class="form-control p-0" size="1" name="item_hour_date{{ $data[1]->id }}" id="MTime1ItemHourDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($hourList as $key =>$item )
                                    <option {{$errors->has('item_hour_date2') ? '' : (isset($data[1]->item_hour_date) && $data[1]->item_hour_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.time') }}</label>
                            <select data-error-container="#MTime1ItemMinuteDate-feedback" data-msg-pattern="{{ trans('mtime.msg_error_required') }}" data-rule-pattern="[^0\s][0-9]*" class="form-control p-0" size="1" name="item_minute_date{{ $data[1]->id }}" id="MTime1ItemMinuteDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($minuteList as $key =>$item )
                                    <option {{$errors->has('item_minute_date2') ? '' : (isset($data[1]->item_minute_date) && $data[1]->item_minute_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.before') }}</label>
                        </div>
                        <div id="MTime1ItemHourDate-feedback"></div>
                        <div id="MTime1ItemMinuteDate-feedback"></div>
                    </div>
                </div>
                <div class="form-inline mt-1">
                    <div class="case-title my-auto">{{trans('mtime.big_urgent_case') }}</div>
                    <div class="case-content my-auto">
                        <div class="form-inline">
                            <input type="hidden" value="{{ $data[2]->id }}" id="id{{ $data[2]->id }}" name="id{{ $data[2]->id }}">
                            <input type="hidden" value="address_disclosure" id="item_category{{ $data[2]->id }}" name="item_category{{ $data[2]->id }}">
                            <input type="hidden" value="asap" id="item_detail{{ $data[2]->id }}" name="item_detail{{ $data[2]->id }}">
                            <input type="hidden" value="0" id="item_type{{ $data[2]->id }}" name="item_type{{ $data[2]->id }}">
                            <input type="hidden" value="3" id="item_id{{ $data[2]->id }}" name="item_id{{ $data[2]->id }}">
                            <label class="pr-3">{{trans('mtime.interview_time') }}</label>
                            <select data-error-container="#MTime2ItemHourDate-feedback" data-rule-required="true" class="form-control p-0" size="1" name="item_hour_date{{ $data[2]->id }}" id="MTime2ItemHourDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($hourList as $key =>$item )
                                    <option {{$errors->has('item_hour_date3') ? '' : (isset($data[2]->item_hour_date) && $data[2]->item_hour_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.time') }}</label>
                            <select data-error-container="#MTime2ItemMinuteDate-feedback" data-msg-pattern="{{ trans('mtime.msg_error_required') }}" data-rule-pattern="[^0\s][0-9]*" class="form-control p-0" size="1" name="item_minute_date{{ $data[2]->id }}" id="MTime2ItemMinuteDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($minuteList as $key =>$item )
                                    <option {{$errors->has('item_minute_date3') ? '' : (isset($data[2]->item_minute_date) && $data[2]->item_minute_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.before') }}</label>
                        </div>
                        <div id="MTime2ItemHourDate-feedback"></div>
                        <div id="MTime2ItemMinuteDate-feedback"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-category mt-3 pb-3">
            <label class="form-category__label">{{trans('mtime.telephone_number_disclosure_time_setting') }}</label>
            <div class="container-fluid pl-5">
                <div class="form-inline mt-1">
                    <div class="case-title my-auto">{{trans('mtime.usual_case') }}</div>
                    <div class="case-content my-auto">
                        <div class="form-inline">
                            <input type="hidden" value="{{ $data[3]->id }}" id="id{{ $data[3]->id }}" name="id{{ $data[3]->id }}">
                            <input type="hidden" value="tel_disclosure" id="item_category{{ $data[3]->id }}" name="item_category{{ $data[3]->id }}">
                            <input type="hidden" value="normal" id="item_detail{{ $data[3]->id }}" name="item_detail{{ $data[3]->id }}">
                            <input type="hidden" value="0" id="item_type{{ $data[3]->id }}" name="item_type{{ $data[3]->id }}">
                            <input type="hidden" value="1" id="item_id{{ $data[3]->id }}" name="item_id{{ $data[3]->id }}">
                            <label class="pr-3">{{trans('mtime.interview_time') }}</label>
                            <select data-error-container="#MTime3ItemHourDate-feedback" data-rule-required="true" class="form-control p-0" size="1" name="item_hour_date{{ $data[3]->id }}" id="MTime3ItemHourDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($hourList as $key =>$item )
                                    <option {{$errors->has('item_hour_date4') ? '' : (isset($data[3]->item_hour_date) && $data[3]->item_hour_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.time') }}</label>
                            <select data-error-container="#MTime3ItemMinuteDate-feedback" data-msg-pattern="{{ trans('mtime.msg_error_required') }}" data-rule-pattern="[^0\s][0-9]*" class="form-control p-0" size="1" name="item_minute_date{{ $data[3]->id }}" id="MTime3ItemMinuteDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($minuteList as $key =>$item )
                                    <option {{$errors->has('item_minute_date4') ? '' : (isset($data[3]->item_minute_date) && $data[3]->item_minute_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.before') }}</label>
                        </div>
                        <div id="MTime3ItemHourDate-feedback"></div>
                        <div id="MTime3ItemMinuteDate-feedback"></div>
                    </div>
                </div>
                <div class="form-inline mt-1">
                    <div class="case-title my-auto">{{trans('mtime.urgent_case') }}</div>
                    <div class="case-content my-auto">
                        <div class="form-inline">
                            <input type="hidden" value="{{ $data[4]->id }}" id="id{{ $data[4]->id }}"  name="id{{ $data[4]->id }}">
                            <input type="hidden" value="tel_disclosure" id="item_category{{ $data[4]->id }}" name="item_category{{ $data[4]->id }}">
                            <input type="hidden" value="immediately" id="item_detail{{ $data[4]->id }}" name="item_detail{{ $data[4]->id }}">
                            <input type="hidden" value="0" id="item_type{{ $data[4]->id }}" name="item_type{{ $data[4]->id }}">
                            <input type="hidden" value="2" id="item_id{{ $data[4]->id }}" name="item_id{{ $data[4]->id }}">
                            <label class="pr-3">{{trans('mtime.interview_time') }}</label>
                            <select data-error-container="#MTime4ItemHourDate-feedback" data-rule-required="true" class="form-control p-0" size="1" name="item_hour_date{{ $data[4]->id }}" id="MTime4ItemHourDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($hourList as $key =>$item )
                                    <option {{$errors->has('item_hour_date5') ? '' : (isset($data[4]->item_hour_date) && $data[4]->item_hour_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.time') }}</label>
                            <select data-error-container="#MTime4ItemMinuteDate-feedback" data-msg-pattern="{{ trans('mtime.msg_error_required') }}" data-rule-pattern="[^0\s][0-9]*" class="form-control p-0" size="1" name="item_minute_date{{ $data[4]->id }}" id="MTime4ItemMinuteDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($minuteList as $key =>$item )
                                    <option {{$errors->has('item_minute_date5') ? '' : (isset($data[4]->item_minute_date) && $data[4]->item_minute_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.before') }}</label>
                        </div>
                        <div id="MTime4ItemHourDate-feedback"></div>
                        <div id="MTime4ItemMinuteDate-feedback"></div>
                    </div>
                </div>
                <div class="form-inline mt-1">
                    <div class="case-title my-auto">{{trans('mtime.big_urgent_case') }}</div>
                    <div class="case-content my-auto">
                        <div class="form-inline">
                            <input type="hidden" value="{{ $data[5]->id }}" id="id{{ $data[5]->id }}" name="id{{ $data[5]->id }}">
                            <input type="hidden" value="tel_disclosure" id="item_category{{ $data[5]->id }}" name="item_category{{ $data[5]->id }}">
                            <input type="hidden" value="asap" id="item_detail{{ $data[5]->id }}" name="item_detail{{ $data[5]->id }}">
                            <input type="hidden" value="0" id="item_type{{ $data[5]->id }}" name="item_type{{ $data[5]->id }}">
                            <input type="hidden" value="3" id="item_id{{ $data[5]->id }}" name="item_id{{ $data[5]->id }}">
                            <label class="pr-3">{{trans('mtime.interview_time') }}</label>
                            <select data-error-container="#MTime5ItemHourDate-feedback" data-rule-required="true" class="form-control p-0" size="1" name="item_hour_date{{ $data[5]->id }}" id="MTime5ItemHourDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($hourList as $key =>$item )
                                    <option {{$errors->has('item_hour_date6') ? '' : (isset($data[5]->item_hour_date) && $data[5]->item_hour_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.time') }}</label>
                            <select data-error-container="#MTime5ItemMinuteDate-feedback" data-msg-pattern="{{ trans('mtime.msg_error_required') }}" data-rule-pattern="[^0\s][0-9]*" class="form-control p-0" size="1" name="item_minute_date{{ $data[5]->id }}" id="MTime5ItemMinuteDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($minuteList as $key =>$item )
                                    <option {{$errors->has('item_minute_date6') ? '' : (isset($data[5]->item_minute_date) && $data[5]->item_minute_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.before') }}</label>
                        </div>
                        <div id="MTime5ItemHourDate-feedback"></div>
                        <div id="MTime5ItemMinuteDate-feedback"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-category mt-3 pb-3">
            <label class="form-category__label">{{trans('mtime.setting_of_date_and_time') }}</label>
            <div class="container-fluid pl-5">
                <div class="form-inline mt-1">
                    <div class="case-title my-auto">{{trans('mtime.usual_case') }}</div>
                    <div class="case-content my-auto">
                        <div class="form-inline">
                            <input type="hidden" value="{{ $data[6]->id }}" id="id{{ $data[6]->id }}" name="id{{ $data[6]->id }}">
                            <input type="hidden" value="send_mail" id="item_category{{ $data[6]->id }}" name="item_category{{ $data[6]->id }}">
                            <input type="hidden" value="normal" id="item_detail{{ $data[6]->id }}" name="item_detail{{ $data[6]->id }}">
                            <input type="hidden" value="0" id="item_type{{ $data[6]->id }}" name="item_type{{ $data[6]->id }}">
                            <input type="hidden" value="1" id="item_id{{ $data[6]->id }}" name="item_id{{ $data[6]->id }}">
                            <label class="pr-3">{{trans('mtime.interview_time') }}</label>
                            <select class="form-control p-0" size="1" name="item_hour_date{{ $data[6]->id }}" id="MTime6ItemHourDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($hourList as $key =>$item )
                                    <option {{(isset($data[6]->item_hour_date) && $data[6]->item_hour_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.time') }}</label>
                            <select class="form-control p-0" size="1" name="item_minute_date{{ $data[6]->id }}" id="MTime6ItemMinuteDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($minuteList as $key =>$item )
                                    <option {{(isset($data[6]->item_minute_date) && $data[6]->item_minute_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.before') }}</label>
                        </div>
                    </div>
                </div>
                <div class="form-inline mt-1">
                    <div class="case-title my-auto">{{trans('mtime.urgent_case') }}</div>
                    <div class="case-content my-auto">
                        <div class="form-inline">
                            <input type="hidden" value="{{ $data[7]->id }}" id="id{{ $data[7]->id }}" name="id{{ $data[7]->id }}">
                            <input type="hidden" value="send_mail" id="item_category{{ $data[7]->id }}" name="item_category{{ $data[7]->id }}">
                            <input type="hidden" value="immediately" id="item_detail{{ $data[7]->id }}" name="item_detail{{ $data[7]->id }}">
                            <input type="hidden" value="0" id="item_type{{ $data[7]->id }}" name="item_type{{ $data[7]->id }}">
                            <input type="hidden" value="2" id="item_id{{ $data[7]->id }}" name="item_id{{ $data[7]->id }}">
                            <label class="pr-3">{{trans('mtime.interview_time') }}</label>
                            <select class="form-control p-0" size="1" name="item_hour_date{{ $data[7]->id }}" id="MTime7ItemHourDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($hourList as $key =>$item )
                                    <option {{(isset($data[7]->item_hour_date) && $data[7]->item_hour_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.time') }}</label>
                            <select class="form-control p-0" size="1" name="item_minute_date{{ $data[7]->id }}" id="MTime7ItemMinuteDate" {{$isDisabled ? 'disabled' : ''}}>
                                <option value="">--{{trans('mtime.none') }}--</option>
                                @foreach($minuteList as $key =>$item )
                                    <option {{(isset($data[7]->item_minute_date) && $data[7]->item_minute_date == $item) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            </select>
                            <label class="pl-2 pr-2">{{trans('mtime.before') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-category mt-3 pb-3">
            <label class="form-category__label">{{trans('mtime.follow_TEL_setting') }}</label>
            <div class="container-fluid pl-5">
                <div class="form-inline mt-1">
                    <input type="hidden" value="{{ $data[8]->id }}" id="id{{ $data[8]->id }}" name="id{{ $data[8]->id }}">
                    <label class="pr-2">{{trans('mtime.date_of_visit_from') }}</label>
                    <input data-error-container="#date-of-visit-feedback" data-rule-required="true" data-msg-pattern="{{ trans('mtime.msg_error_numberic') }}" data-rule-pattern="\d+" class="input-small form-control" name="item_hour_date{{ $data[8]->id }}" maxlength="10" type="text" value="{{$isError ? old('item_hour_date9') : $data[8]->item_hour_date}}" {{$isDisabled ? 'disabled' : ''}}>
                    <label class="pl-2">{{trans('mtime.end_date_of_visit_from') }}</label>
                </div>
                <div id="date-of-visit-feedback"></div>
                <div class="form-inline mt-1">
                    <label class="pr-2">{{trans('mtime.time_of_visit') }}</label>
                    <input type="hidden" value="{{ $data[9]->id }}" id="id{{ $data[9]->id }}" name="id{{ $data[9]->id }}">
                    <input data-error-container="#time-of-visit-feedback" data-rule-required="true" data-msg-pattern="{{ trans('mtime.msg_error_numberic') }}" data-rule-pattern="\d+" class="input-small form-control" name="item_hour_date{{ $data[9]->id }}" maxlength="10" type="text" value="{{$isError ? old('item_hour_date10') : $data[9]->item_hour_date}}" {{$isDisabled ? 'disabled' : ''}}>
                    <label class="pl-2">{{trans('mtime.end_time_of_visit') }}</label>
                </div>
                <div id="time-of-visit-feedback"></div>
                <div class="form-inline mt-1">
                    <label class="pr-2">{{trans('mtime.follow_up_time_is') }}</label>
                    <input type="hidden" value="{{ $data[10]->id }}" id="id{{ $data[10]->id }}" name="id{{ $data[10]->id }}">
                    <input data-error-container="#follow-up-time-1-feedback" data-rule-required="true" data-msg-pattern="{{ trans('mtime.msg_error_numberic') }}" data-rule-pattern="\d+" class="input-small form-control" name="item_hour_date{{ $data[10]->id }}" maxlength="10" type="text" value="{{$isError ? old('item_hour_date11') : $data[10]->item_hour_date}}" {{$isDisabled ? 'disabled' : ''}}>
                    <label class="pl-2 pr-2">{{trans('mtime.to') }}</label>
                    <input type="hidden" value="{{ $data[11]->id }}" id="id{{ $data[11]->id }}" name="id{{ $data[11]->id }}">
                    <input data-error-container="#follow-up-time-2-feedback" data-rule-required="true" data-msg-pattern="{{ trans('mtime.msg_error_numberic') }}" data-rule-pattern="\d+" class="input-small form-control" name="item_hour_date{{ $data[11]->id }}" maxlength="10" type="text" value="{{$isError ? old('item_hour_date12') : $data[11]->item_hour_date}}" {{$isDisabled ? 'disabled' : ''}}>
                    <label class="pl-2">{{trans('mtime.end_follow_up_time') }}</label>
                </div>
                <div id="follow-up-time-1-feedback"></div>
                <div id="follow-up-time-2-feedback"></div>
                <div class="form-inline mt-1">
                    <label class="pr-2">{{trans('mtime.start_time') }}</label>
                    <input type="hidden" value="{{ $data[12]->id }}" id="id{{ $data[12]->id }}" name="id{{ $data[12]->id }}">
                    <input data-error-container="#start-time-feedback" data-rule-required="true" data-msg-pattern="{{ trans('mtime.msg_error_numberic') }}" data-rule-pattern="\d+" class="input-small form-control" name="item_hour_date{{ $data[12]->id }}" maxlength="10" type="text" value="{{$isError ? old('item_hour_date13') : $data[12]->item_hour_date}}" {{$isDisabled ? 'disabled' : ''}}>
                    <label class="pl-2">{{trans('mtime.end_start_time') }}</label>
                </div>
                <div id="start-time-feedback"></div>
                <div class="form-inline mt-1">
                    <label class="pr-2">{{trans('mtime.end_time') }}</label>
                    <input type="hidden" value="{{ $data[13]->id }}" id="id{{ $data[13]->id }}" name="id{{ $data[13]->id }}">
                    <input data-error-container="#end-time-feedback" data-rule-required="true" data-msg-pattern="{{ trans('mtime.msg_error_numberic') }}" data-rule-pattern="\d+" class="input-small form-control" name="item_hour_date{{ $data[13]->id }}" maxlength="10" type="text" value="{{$isError ? old('item_hour_date14') : $data[13]->item_hour_date}}" {{$isDisabled ? 'disabled' : ''}}>
                    <label class="pl-2">{{trans('mtime.end_start_time') }}</label>
                </div>
                <div id="end-time-feedback"></div>
            </div>
        </div>
        <?php
            $item_detail = [1 => 'spare_time', 2 => 'before_visit', 3 => 'follow_from', 4 => 'follow_to', 5 => 'before_day_first_half', 6 => 'before_day'];
            $num = 9;
        foreach ($item_detail as $key => $val){ ?>
                <input type="hidden" value="follow_tel" id="item_category{{$num}}" name="item_category{{$num}}">
                <input type="hidden" value="{{$val}}" id="item_detail{{$num}}" name="item_detail{{$num}}">
                <input type="hidden" value="1" id="item_type{{$num}}" name="item_type{{$num}}">
                <input type="hidden" value="{{$key}}" id="item_id{{$num}}" name="item_id{{$num}}">
            <?php
            $num++;
        }
        ?>

        <div class="form-category mt-3 pb-3">
            <label class="form-category__label">{{trans('mtime.other_company_has_responded_time_setting_message_disappearing') }}</label>
            <div class="container-fluid pl-5">
                <div class="form-inline mt-1">
                    <input type="hidden" value="{{ $data[14]->id }}" id="id{{ $data[14]->id }}" name="id{{ $data[14]->id }}">
                    <input type="hidden" value="support_message" id="item_category{{ $data[14]->id }}" name="item_category{{ $data[14]->id }}">
                    <input type="hidden" value="after_push_time" id="item_detail{{ $data[14]->id }}" name="item_detail{{ $data[14]->id }}">
                    <input type="hidden" value="1" id="item_type{{ $data[14]->id }}" name="item_type{{ $data[14]->id }}">
                    <input type="hidden" value="1" id="item_id{{ $data[14]->id }}" name="item_id{{ $data[14]->id }}">
                    <label class="pr-2">{{trans('mtime.after_another_company_presses') }}</label>
                    <input data-error-container="#after-another-company-presses-feedback" data-rule-required="true" data-msg-pattern="{{ trans('mtime.msg_error_numberic') }}" data-rule-pattern="\d+" class="input-small form-control" name="item_hour_date{{ $data[14]->id }}" maxlength="10" type="text" value="{{$isError ? old('item_hour_date15') : $data[14]->item_hour_date}}" {{$isDisabled ? 'disabled' : ''}}>
                    <label class="pl-2">{{trans('mtime.after_hours') }}</label>
                </div>
                <div id="after-another-company-presses-feedback"></div>
            </div>
        </div>

        <div class="form-group text-center pt-2 pb-4">
            <button name="regist" id="regist" class="btn btn--gradient-green" type="submit" {{$isDisabled ? 'disabled' : ''}}>{{trans('mtime.register') }}</button>
        </div>
    </form>
</div>
@endsection

@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/auction_setting.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            FormUtil.validate('#formAuctionIndex');
            var arrayValidate = [];
            for (var i = 0; i < 6; i++) {
                var item = {
                    item1: {
                        inputId: $('#MTime' + i + 'ItemHourDate'),
                        feedbackId: $('#MTime' + i + 'ItemHourDate-feedback')
                    },
                    item2: {
                        inputId: $('#MTime' + i + 'ItemMinuteDate'),
                        feedbackId: $('#MTime' + i + 'ItemMinuteDate-feedback')
                    }
                };
                arrayValidate.push(item);
            }
            checkGet.validateSelect(arrayValidate);
        });
    </script>
@endsection


