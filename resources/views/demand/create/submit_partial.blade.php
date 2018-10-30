<div class="row justify-content-center">
    <div class="col-12 col-lg-3">
        <button id="bottom_regist" class="btn btn--gradient-green w-100">@lang('demand_detail.registration')</button>
        <div class="custom-control mx-auto custom-checkbox my-1 ">
            {!! Form::checkbox('not-send', 1, false, ['class' => 'custom-control-input', 'id' => 'not-send']) !!}
            <label class="custom-control-label" for="not-send">@lang('demand_detail.no_mail')@lang('demand_detail.transmission_unnecessary')</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <h5 class="m-0 text-right text-dark"><strong>@lang('demand_detail.view_count')：0件</strong></h5>
    </div>
</div>
