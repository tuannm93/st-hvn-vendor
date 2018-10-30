@extends('layouts.app')

@section('content')
    <div class="auction-support text-center pt-2">
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
            <button name="close" id="close" class="btn btn--gradient-orange" type="button">OK</button>
        </div>
        <div class="custom-control custom-checkbox mt-1">
            <input type="checkbox" name="data[popup_stop_flg]" class="custom-control-input" id="popup_stop_flg" value="1">
            <label class="custom-control-label" for="popup_stop_flg">@lang('support.doNotDisplayAgain')</label>
        </div>
    </div>
@endsection