<div class="form-category mb-2" id="jbrdemandinfo">
    @include('demand.create.anchor_top')
    <label class="form-category__label">@lang('demand_detail.proposal_information')</label>
    <div class="form-category__body clearfix">
        <div class="form-table mb-4">
            <div class="row mx-0">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.reception_no')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::text('demandInfo[jbr_order_no]', '', ['class' => 'form-control w-100 is-required']) !!}

                        @if (Session::has('demand_errors.check_jbr_order_no'))
                        <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_jbr_order_no')}}</label>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.work_content_jbr')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::select('demandInfo[jbr_work_contents]', $jbrWorkContentDropDownList, '', ['class' => 'form-control']) !!}

                        @if (Session::has('demand_errors.check_jbr_work_contents'))
                        <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_jbr_work_contents')}}</label>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mx-0">
                <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.content_of_the_email')</label>
                <div class="col-lg-6 py-2 form-table-cell">
                    {!! Form::textarea('demandInfo[mail]', '', ['class' => 'form-control', 'rows' => 5]) !!}

                    @if ($errors->has('demandInfo.mail'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.mail')}}</label>
                    @endif
                </div>
                <div class="d-none d-lg-flex col-lg-3 form-table-cell"></div>
            </div>
        </div>
    </div>
</div>
