<div class="col-12 row m-0 p-0">
        <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">{{__('demand_detail.estimated_time')}}</label>
        <div class="col-lg-9 py-2 estimated-time form-table-cell" countAffiliation="{{route('ajax.affiliation.count')}}">
            <div class="form-row align-items-center date-time-group">
                <div class="col-5 col-lg-3 date-time-item">
                    {!! Form::text('demandInfo[contact_estimated_time_from]', $demandExtenInfoData['est_start_work'] ?? '',
                    ['class' => 'form-control datetimepicker count txt_range_time is-required',
                    'data-rules' => 'valid-date',
                    'id' => 'contact_estimated_time_from']) !!}

                    @if (Session::has('demand_errors.contact_estimated_time_from'))
                        <label
                                class="invalid-feedback d-block">{{Session::get('demand_errors.contact_estimated_time_from')}}</label>
                    @elseif(Session::has('demand_errors.check_contact_estimated_time1'))
                        <label
                                class="invalid-feedback d-block invalid-time">{{Session::get('demand_errors.check_contact_estimated_time1')}}</label>

                    @elseif (Session::has('demand_errors.check_contact_estimated_time_from'))
                        <label
                                class="invalid-feedback d-block">{{Session::get('demand_errors.check_contact_estimated_time_from')}}</label>
                    @elseif ($errors->has('demandInfo.check_estimated_require_to'))
                        <label
                                class="invalid-feedback d-block">{{$errors->first('demandInfo.check_estimated_require_from')}}</label>
                    @endif
                </div>
                <span class="col-auto text-center">〜</span>
                <div class="col-5 col-lg-3">
                    {!! Form::text('demandInfo[contact_estimated_time_to]', $demandExtenInfoData['est_end_work'] ?? '',
                    ['class' => 'form-control datetimepicker count txt_range_time is-required', 'data-rules' => 'valid-date',
                    'disabled' => old('demandInfo')['selection_system'] == 0 || $demand->selection_system == 0 ? false : true,
                    'id' => 'contact_estimated_time_to']) !!}

                    @if (Session::has('demand_errors.contact_estimated_time_from'))
                        <label
                                class="invalid-feedback d-block">{{Session::get('demand_errors.contact_estimated_time_to')}}</label>
                    @elseif (Session::has('demand_errors.check_contact_estimated_time_to'))
                        <label
                                class="invalid-feedback d-block">{{Session::get('demand_errors.check_contact_estimated_time_to')}}</label>
                    @elseif ($errors->has('demandInfo.check_estimated_require_to'))
                        <label
                                class="invalid-feedback d-block">{{$errors->first('demandInfo.check_estimated_require_to')}}</label>
                    @endif
                </div>
            </div>
        </div>
        <div id="msg-required-visit"></div>
    </div>
<input type="hidden" class="cyzen_commission_corp" value>
