<div class="row justify-content-center">
    <div class="col-12 col-lg-3">
    @if(isset($demand->commissionInfos) && count($demand->commissionInfos) > 0)
        <button class="btn btn--gradient-green w-100" id="send_commission_info_btn">@lang('demand_detail.sending_the_agency_information')</button>
    @endif
    </div>
    <div class="col-12 col-lg-3">
        <button id="bottom_regist" class="btn btn--gradient-green w-100">@lang('demand_detail.registration')</button>
        <div class="custom-control mx-auto custom-checkbox my-1 ">
            <input type="checkbox" class="custom-control-input" id="customControlInline" name="not-send" value="1">
            <label class="custom-control-label" for="customControlInline">@lang('demand_detail.no_mail')@lang('demand_detail.transmission_unnecessary')</label>
        </div>
    </div>
</div>
@if(session('error_commission_flg_count'))
    <div class="row justify-content-center header-field my-4 border-2 border border-warning" style="margin-bottom: 0">
        <div class="col-4 col-md-offset-4 text-center">
            <strong class="text-center text-danger" style="color: #f27b07"> {{ session('error_commission_flg_count') }} </strong>
        </div>
    </div>
@endif
