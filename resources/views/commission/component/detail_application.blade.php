<div class="modal" tabindex="-1" role="dialog" id="application_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header p-1">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3 px-lg-5" id="display_modal_area">
                <h4 class="modal-title">{!! trans('commission_app.commission_application') !!}</h4>
                {{Form::input('hidden', 'data[commission_id]', $commission_id, ['id' => 'commission_id'])}}
                {{Form::input('hidden', 'data[demand_id]', $demand_id, ['id' => 'demand_id'])}}
                {{Form::input('hidden', 'data[corp_id]', $corp_id, ['id' => 'corp_id'])}}
                <div class="form-group row mb-2">
                    <div class="col-sm-3 col-lg-2">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[chk1]', 1, false, ['class' => 'custom-control-input', 'id' => 'chk1']) }}
                            <label class="custom-control-label" for="chk1"></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        {!! trans('commission_app.deduction_tax_include') !!}
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        <div class="form-group mb-2">
                            {{Form::input('text', 'data[deduction_tax_include]', '', ['id' => 'deduction_tax_include', 'class' => 'form-control', 'disabled' => 'disabled'])}}
                            <label class="form-group__sub-label" for="construction_price_tax_include">{!! trans('common.yen') !!}</label>
                            @if ($errors->has('deduction_tax_include'))
                            <label class="invalid-feedback d-block">{{$errors->first('deduction_tax_include')}}</label>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <div class="col-sm-3 col-lg-2">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[chk2]', 1, false, ['class' => 'custom-control-input irc', 'id' => 'chk2']) }}
                            <label class="custom-control-label" for="chk2"></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        {!! trans('commission_app.irregular_fee_rate') !!}
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        <div class="form-group mb-2">
                            {{Form::input('text', 'data[irregular_fee_rate]', '', ['id' => 'irregular_fee_rate', 'class' => 'form-control', 'disabled' => 'disabled'])}}
                            <label class="form-group__sub-label" for="construction_price_tax_include">%</label>
                            @if ($errors->has('irregular_fee_rate'))
                            <label class="invalid-feedback d-block">{{$errors->first('irregular_fee_rate')}}</label>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <div class="col-sm-3 col-lg-2">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[chk3]', 1, false, ['class' => 'custom-control-input irc', 'id' => 'chk3']) }}
                            <label class="custom-control-label" for="chk3"></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        {!! trans('commission_app.irregular_fee') !!}
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        <div class="form-group mb-2">
                            {{Form::input('text', 'data[irregular_fee]', '', ['id' => 'irregular_fee', 'class' => 'form-control', 'disabled' => 'disabled'])}}
                            <label class="form-group__sub-label" for="construction_price_tax_include">{!! trans('common.yen') !!}</label>
                            @if ($errors->has('irregular_fee'))
                            <label class="invalid-feedback d-block">{{$errors->first('irregular_fee')}}</label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <div class="col-sm-3 col-lg-2">
                        <div class="custom-control custom-checkbox mr-sm-2"></div>
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        {!! trans('commission_app.irregular_reason') !!}
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        <div class="form-group mb-2">
                            {{ Form::select(
                                'data[irregular_reason]',
                                $drop_list['irregular_reason'],
                                '',
                                [
                                    'id' => 'ir',
                                    'class' => 'form-control',
                                    'disabled' => 'disabled'
                                ]
                            ) }}
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <div class="col-sm-3 col-lg-2">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[chk4]', 1, false, ['class' => 'custom-control-input', 'id' => 'chk4']) }}
                            <label class="custom-control-label" for="chk4"></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        {!! trans('commission_app.introduction_free') !!}
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[introduction_free]', 1, false, ['class' => 'custom-control-input', 'id' => 'introduction_free', 'disabled' => 'disabled']) }}
                            <label class="custom-control-label" for="introduction_free"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <div class="col-sm-3 col-lg-2">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[chk5]', 1, false, ['class' => 'custom-control-input', 'id' => 'chk5']) }}
                            <label class="custom-control-label" for="chk5"></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        {!! trans('commission_app.ac_commission_exclusion_flg') !!}
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[ac_commission_exclusion_flg]', 1, false, ['class' => 'custom-control-input', 'id' => 'ac_commission_exclusion_flg', 'disabled' => 'disabled']) }}
                            <label class="custom-control-label" for="ac_commission_exclusion_flg"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <div class="col-sm-3 col-lg-2">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[chk6]', 1, false, ['class' => 'custom-control-input', 'id' => 'chk6']) }}
                            <label class="custom-control-label" for="chk6"></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        {!! trans('commission_app.introduction_not') !!}
                    </div>
                    <div class="col-12 col-sm-9 col-lg-5">
                        <div class="custom-control custom-checkbox mr-sm-2">
                            {{ Form::checkbox('data[introduction_not]', 1, false, ['class' => 'custom-control-input', 'id' => 'introduction_not', 'disabled' => 'disabled']) }}
                            <label class="custom-control-label" for="introduction_not"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <div class="col-sm-3 col-lg-2">
                        {!! trans('commission_app.application_reason') !!}
                    </div>
                    <div class="col-12 col-sm-9 col-lg-10">
                        {{ Form::textarea('data[application_reason]', null, ['class' => 'form-control', 'id' => 'application_reason', 'required' => 'required', 'rows' => 5]) }}
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn--gradient-green fix-w-200 my-3" id="application_submit">{!! trans('commission_app.application_submit') !!}</button>
                <button type="button" class="btn btn--gradient-default fix-w-200 my-3" id="application_close">{!! trans('commission_app.application_close') !!}</button>
            </div>
        </div>
    </div>
</div>