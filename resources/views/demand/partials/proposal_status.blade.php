<div class="form-category mb-4" id="demandstatus">
    @include('demand.create.anchor_top')
    <label class="form-category__label">@lang('demand_detail.proposal_status')</label>
    <div class="form-category__body clearfix">
        <div class="form-table mb-4">
            <div class="row mx-0">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.proposal_status')
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::select('demandInfo[demand_status]', $demandStatusDropDownList, $demand->demand_status, ['class' => 'form-control is-required', 'id' => 'demand_status', 'data-rules' => 'not-empty']) !!}
                        @if (Session::has('demand_errors.check_demand_status'))
                            <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_demand_status')}}</label>
                        @elseif (Session::has('demand_errors.check_demand_status_advance'))
                            <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_demand_status_advance')}}</label>
                        @elseif (Session::has('demand_errors.check_demand_status_introduce'))
                            <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_demand_status_introduce')}}</label>
                        @elseif (Session::has('demand_errors.check_demand_status_introduce_email'))
                            <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_demand_status_introduce_email')}}</label>
                        @elseif (Session::has('demand_errors.check_demand_status_selection_type'))
                            <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_demand_status_selection_type')}}</label>
                        @elseif (Session::has('demand_errors.check_demand_status_confirm'))
                            <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_demand_status_confirm')}}</label>
                        @elseif (session('demand_errors.check_demand_status_introduce_email2'))
                            <label class="invalid-feedback d-block">{{session('demand_errors.check_demand_status_introduce_email2')}}</label>
                        @elseif ($errors->has('demandInfo.demand_status'))
                            <label class="invalid-feedback d-block">{{$errors->first('demandInfo.demand_status')}}</label>
                        @elseif ($errors->has('check_demand_status_confirm'))
                            <label class="invalid-feedback d-block">{{$errors->first('check_demand_status_confirm')}}</label>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.lost_date')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::text('demandInfo[order_fail_date]', $demand->order_fail_date, ['class' => 'form-control datepicker order-fail-date']) !!}
                    </div>
                </div>
            </div>
            <div class="row mx-0">
                <div class="col-12 row m-0 p-0">
                    <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.reason_for_losing')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::select('demandInfo[order_fail_reason]', $orderFailReasonDropDownList, $demand->order_fail_reason,
                        ['class' => 'form-control', 'disabled' => old('demandInfo')['demand_status'] && old('demandInfo')['demand_status'] == 6 ? false : true, 'id' => 'order-fail-reason']) !!}
                        @if(session('demand_errors.check_order_fail_reason'))
                            <label class="invalid-feedback d-block">{{ session('demand_errors.check_order_fail_reason') }}</label>
                        @endif
                    </div>
                    <div class="d-none d-lg-flex col-lg-3 form-table-cell"></div>
                </div>
            </div>
            <div class="row mx-0">
                <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.reception_status')
                    <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                </label>
                <div class="col-9 col-lg-3 py-2 form-table-cell border-right-0">
                    {!! Form::select('demandInfo[acceptance_status]', $acceptanceStatusDropDownList, $demand->acceptance_status, ['class' => 'form-control is-required', 'data-rules' => 'not-empty']) !!}
                </div>
                <div class="col-auto col-lg-6 custom-checkbox custom-control d-flex py-2 align-items-center border-left-0 border-right-0 form-table-cell">
                    {!! Form::checkbox('demandInfo[nitoryu_flg]', 1, $demand->nitoryu_flg == 1, ['id' => 'demandInfo[nitoryu_flg]', 'class' => 'custom-control-input']) !!}
                    <label class="custom-control-label" for='demandInfo[nitoryu_flg]'>@lang('demand_detail.double_sword')</label>
                </div>
            </div>
        </div>
    </div>
</div>
