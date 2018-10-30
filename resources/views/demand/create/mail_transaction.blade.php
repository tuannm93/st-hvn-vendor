<div class="form-category mb-4">
{{--    @include('demand.create.anchor_top')--}}
    <label class="form-category__label">@lang('demand_detail.one_touch_order_registration')</label>
    <div class="form-category__body clearfix">
        <div class="form-inline mb-4">
            {!! Form::select('demandInfo[quick_order_fail_reason]', $quickOrderFailReasonDropDownList, '',
            [
                'class' => 'form-control mr-lg-2 mb-2 mb-lg-0',
                'id' => 'quick-order-fail-reason'
            ]) !!}
            <input type="hidden" name="quick_order_fail" value="" id="hidQuickOrderFail" />
            <button class="btn btn--gradient-red btn--w-normal" id="quick_order_fail">
            @lang('demand_detail.one_touch_order_registration')</button>
            {!! Form::hidden('demandInfo[display_auto_commission_message]', 0) !!}
        </div>
    </div>
</div>
