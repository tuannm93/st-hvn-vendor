<div class="form-category mb-4" id="jbrdemandinfo">
    @include('demand.create.anchor_top')
    <label class="form-category__label">@lang('demand_detail.proposal_information')</label>
    <div class="form-category__body clearfix">
        <div class="form-table mb-4">
            <div class="row mx-0">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.reception_no')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::text('demandInfo[jbr_order_no]', $demand->jbr_order_no, ['class' => 'form-control w-100']) !!}
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.work_content_jbr')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::select('demandInfo[jbr_work_contents]', $jbrWorkContentDropDownList, $demand->jbr_work_contents, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="row mx-0">
                <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.content_of_the_email')</label>
                <div class="col-lg-6 py-2 form-table-cell">
                    {!! Form::textarea('demandInfo[mail]', $demand->mail, ['class' => 'form-control', 'rows' => 5]) !!}
                </div>
                <div class="d-none d-lg-flex col-lg-3 form-table-cell"></div>
            </div>
        </div>
    </div>
</div>
