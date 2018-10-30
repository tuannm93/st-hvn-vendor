<div class="form-table">
    <div class="row mx-0">
        <div class="col-12 py-2 form-table-cell">
            <div class="form-row align-items-center">
                <div class="custom-control custom-checkbox mr-sm-4">
                    {!! Form::checkbox('demandInfo[nighttime_takeover]', 1, false, ['class' => 'custom-control-input', 'id' => 'demandInfo[nighttime_takeover]']) !!}
                    <label class="custom-control-label" for='demandInfo[nighttime_takeover]'>@lang('demand_detail.night_work')</label>

                    @if ($errors->has('demandInfo.nighttime_takeover'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.nighttime_takeover')}}</label>
                    @endif
                </div>
                <div class="custom-control custom-checkbox mr-sm-4">
                    {!! Form::checkbox('demandInfo[mail_demand]', 1, false, ['class' => 'custom-control-input', 'id' => 'demandInfo[mail_demand]']) !!}
                    <label class="custom-control-label" for='demandInfo[mail_demand]'>@lang('demand_detail.mail_demand')</label>

                    @if ($errors->has('demandInfo.mail_demand'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.mail_demand')}}</label>
                    @endif
                </div>
                <div class="custom-control custom-checkbox mr-sm-4">
                    {!! Form::checkbox('demandInfo[cross_sell_implement]', 1, false, ['class' => 'custom-control-input', 'id' => 'demandInfo[cross_sell_implement]']) !!}
                    <label class="custom-control-label" for='demandInfo[cross_sell_implement]'>@lang('demand_detail.cross_cell_acquisition')</label>
                </div>
                <div class="custom-control custom-checkbox mr-sm-4">
                    {!! Form::checkbox('demandInfo[cross_sell_call]', 1, false, ['class' => 'custom-control-input', 'id' => 'demandInfo[cross_sell_call]']) !!}
                    <label class="custom-control-label" for='demandInfo[cross_sell_call]'>@lang('demand_detail.cross_sell_call')</label>
                </div>
                <div class="custom-control custom-checkbox mr-sm-4">

                    {!! Form::checkbox('demandInfo[riro_kureka]', 1, false, ['class' => 'custom-control-input', 'disabled' => true, 'id' => 'demandInfo[riro_kureka]']) !!}
                    <label class="custom-control-label"
                            for='demandInfo[riro_kureka]'>@lang('demand_detail.cleka_case')</label>
                </div>
                <div class="custom-control custom-checkbox mr-sm-4">
                    {!! Form::checkbox('demandInfo[remand]', 1, false, ['class' => 'custom-control-input', 'id' => 'demandInfo[remand]']) !!}
                    <label class="custom-control-label" for='demandInfo[remand]'>@lang('demand_detail.reversed_case')</label>

                    @if ($errors->has('demandInfo.remand'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.remand')}}</label>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-12 py-2 form-table-cell">
            <div class="form-row align-items-center">
                <div class="custom-control custom-checkbox mr-sm-4">
                    {!! Form::checkbox('demandInfo[auction]', 1, false, ['class' => 'custom-control-input', 'disabled' => true]) !!}
                    <label class="custom-control-label" for='demandInfo["auction"]'>@lang('demand_detail.bidding_flow_case')</label>
                </div>
                {{--<div class="custom-control custom-checkbox mr-sm-4">--}}
                    {{--{!! Form::checkbox('demandInfo["do_auction"]', 2, false, ['disabled' => true, 'class' => 'custom-control-input']) !!}--}}
                    {{--<label class="custom-control-label"--}}
                            {{--for="customControlAutosizing">再入札</label>--}}
                {{--</div>--}}


                <div class="custom-control custom-checkbox mr-sm-4">
                    {!! Form::checkbox('demandInfo[low_accuracy]', 1, false, ['class'=> 'custom-control-input',  'id' => 'demandInfo[low_accuracy]']) !!}
                    <label class="custom-control-label" for="demandInfo[low_accuracy]">@lang('demand_detail.low_accuracy_projects')</label>

                    @if ($errors->has('demandInfo.low_accuracy'))
                        <label class="invalid-feedback d-block">{{$errors->first('demandInfo.low_accuracy')}}</label>
                    @endif
                </div>
                <div class="custom-control custom-checkbox mr-sm-4">
                    {!! Form::checkbox('demandInfo[corp_change]', 1, false, ['class' => 'custom-control-input',  'id' => 'demandInfo[corp_change]']) !!}
                    <label class="custom-control-label" for="demandInfo[corp_change]">@lang('demand_detail.change_of_merchant_store_request')</label>

                    @if ($errors->has('demandInfo.corp_change'))
                        <label class="invalid-feedback d-block">{{$errors->first('demandInfo.corp_change')}}</label>
                    @endif
                </div>
                <div class="custom-control custom-checkbox mr-sm-4">
                    {!! Form::checkbox('demandInfo[sms_reorder]', 1, false, ['class' => 'custom-control-input',  'id' => 'demandInfo[sms_reorder]']) !!}
                    <label class="custom-control-label" for="demandInfo[sms_reorder]">@lang('demand_detail.re_order_sms')</label>

                    @if ($errors->has('demandInfo.sms_reorder'))
                        <label class="invalid-feedback d-block">{{$errors->first('demandInfo.sms_reorder')}}</label>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-0 ">
        <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.special_measures')</label>
        <div class="col-lg-3 py-2 form-table-cell">
            {!! Form::select('demandInfo[special_measures]', $specialMeasureDropDownList, '', ['class' => 'form-control']) !!}
        </div>
        <div class="d-none d-lg-flex col-lg-6 py-2 form-table-cell"></div>
    </div>
    <div class="row mx-0 ">
        <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.guide_pet_tombstones')</label>
        <div class="col-lg-9 align-items-center form-table-cell group-radio-pet-tomb py-2">
            <div class="row ml-lg-0">
                <div class="custom-control custom-radio custom-control-inline my-auto">
                    {!! Form::radio('demandInfo[pet_tombstone_demand]', 1, false, ['class' => 'custom-control-input', 'id' => 'demandInfo[pet_tombstone_demand_1]']) !!}
                    <label class="custom-control-label"
                            for="demandInfo[pet_tombstone_demand_1]">@lang('demand_detail.guided')</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline my-auto">
                    {!! Form::radio('demandInfo[pet_tombstone_demand]', 2, false, ['class' => 'custom-control-input', 'id' => 'demandInfo[pet_tombstone_demand_2]']) !!}

                    <label class="custom-control-label"
                            for="demandInfo[pet_tombstone_demand_2]">@lang('demand_detail.not_covered')</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline my-auto">
                    {!! Form::radio('demandInfo[pet_tombstone_demand]', 3, false, ['class' => 'custom-control-input', 'id' => 'demandInfo[pet_tombstone_demand_3]']) !!}
                    <label class="custom-control-label"
                            for="demandInfo[pet_tombstone_demand_3]">@lang('demand_detail.forget_the_guide')</label>
                </div>
                <button class="btn btn--gradient-default btn--w-normal mb-2 mb-lg-0" id="reset-radio">@lang('demand_detail.release')</button>
                @if (!Session::has('demand_errors.pet_tombstone_demand'))
                    <div class="my-auto ml-lg-2">
                        <p class="text-muted mb-lg-0">@lang('demand_detail.essential_items')</p>
                    </div>
                @endif
            </div>
            @if (Session::has('demand_errors.pet_tombstone_demand'))
                <div class="row ml-lg-0">
                    <label class="invalid-feedback d-block">{{Session::get('demand_errors.pet_tombstone_demand')}}</label>
                </div>
                <div class="row ml-lg-0">
                    <p class="text-muted mb-2 mb-lg-0">@lang('demand_detail.essential_items')</p>
                </div>
            @endif

        </div>
    </div>
    <div class="row mx-0 ">
        <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.order_number')</label>
        <div class="col-lg-3 py-2 form-table-cell">
            {!! Form::text('demandInfo[order_no_marriage]', '', ['class' => 'form-control is-required', 'data-rules' => 'valid-number']) !!}

            @if (Session::has('demand_errors.check_order_no_marriage_not_empty'))
            <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_order_no_marriage_not_empty')}}</label>
            @endif
        </div>
        <div class="d-none d-lg-flex col-lg-6 py-2 form-table-cell"></div>
    </div>
    <div class="row mx-0 ">
        <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.st_claim')</label>
        <div class="col-lg-3 py-2 form-table-cell">
            {!! Form::select('demandInfo[st_claim]', $stClaimDropDownList, '', ['class' => 'form-control']) !!}
        </div>
        <div class="d-none d-lg-flex col-lg-6 py-2 form-table-cell"></div>
    </div>
</div>
