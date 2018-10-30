<div class="form-category mb-4" id="correspondsinfo">
    @include('demand.create.anchor_top')
    <label class="form-category__label">@lang('demand_detail.corresponding_history_information')</label>
    <div class="form-category__body clearfix">
        <div class="form-table mb-4">
            <div class="row mx-0">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.corresponding_person')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::select('demandCorrespond[responders]', $userDropDownList, Auth::user()->id, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.corresponding_date_time')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::text('demandCorrespond[correspond_datetime]', dateTimeNowFormat(), ['class' => 'form-control datetimepicker']) !!}
                    </div>
                </div>
            </div>
            <div class="row mx-0">
                <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.correspondence_contents')</label>
                <div class="col-lg-6 py-2 form-table-cell">
                    {!! Form::textarea('demandCorrespond[corresponding_contens]', !$ctiDemand ? '初回登録' : '', ['class' => 'form-control', 'id' => 'demandCorrespondContent']) !!}
                    @if ($errors->has('demandCorrespond.corresponding_contens'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandCorrespond.corresponding_contens')}}</label>
                    @endif
                </div>
                <div class="d-none d-lg-flex col-lg-3 form-table-cell"></div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-list table-bordered" >
                <thead>
                    <tr>
                        <th class="p-1 align-center fix-w-50">No</th>
                        <th class="p-1 align-center fix-w-100">@lang('demand_detail.person_in_charge')</th>
                        <th class="p-1 align-center fix-w-150">@lang('demand_detail.corresponding_date_time')</th>
                        <th class="p-1 align-center">@lang('demand_detail.correspondence_contents')</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
