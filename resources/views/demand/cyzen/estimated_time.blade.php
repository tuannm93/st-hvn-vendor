<div class="col-12 row m-0 p-0">
    <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">{{__('demand_detail.estimated_time')}}</label>
    <div class="col-lg-9 py-2 estimated-time form-table-cell"
         countAffiliation="{{route('ajax.affiliation.count')}}">
        <div class="form-row align-items-center date-time-group">
            <div class="col-5 col-lg-3 date-time-item">
                <input type="text"
                       name="demandInfo[contact_estimated_time_from]"
                       class="form-control datetimepicker txt_range_time is-required count"
                       date-rules="valid-date"
                       disabled="@if(old('demandInfo') || (!old('demandInfo') && old('demandInfo')['selection_system'] == 0)) false @else true @endif"
                       id="contact_estimated_time_from"
                       value="{{$invalidDemandEstimatedFrom ?? ''}}"
                >
                @if (Session::has('demandInfo.contact_estimated_time_from'))
                    <label
                            class="invalid-feedback d-block invalid-time">{{Session::get('demandInfo.contact_estimated_time_from')}}</label>
                @elseif(Session::has('demand_errors.check_contact_estimated_time1'))
                    <label
                            class="invalid-feedback d-block invalid-time">{{Session::get('demand_errors.check_contact_estimated_time1')}}</label>
                @elseif (Session::has('demand_errors.check_contact_estimated_time_from'))
                    <label
                            class="invalid-feedback d-block invalid-time">{{Session::get('demand_errors.check_contact_estimated_time_from')}}</label>
                @elseif ($errors->has('demandInfo.check_estimated_require_to'))
                    <label
                            class="invalid-feedback d-block invalid-time">{{$errors->first('demandInfo.check_estimated_require_from')}}</label>
                @endif
            </div>
            <span class="col-auto text-center date-time-sup">〜</span>
            <div class="col-5 col-lg-3 date-time-item">
                <input type="text"
                       name="demandInfo[contact_estimated_time_to]"
                       class="form-control datetimepicker txt_range_time is-required count"
                       date-rules="valid-date"
                       disabled="@if(old('demandInfo') || (!old('demandInfo') && old('demandInfo')['selection_system'] == 0)) false @else true @endif"
                       id="contact_estimated_time_to"
                       value="{{$invalidDemandEstimatedTo ?? ''}}"
                >
                @if (Session::has('demandInfo.contact_estimated_time_from'))
                    <label
                            class="invalid-feedback d-block invalid-time">{{Session::get('demandInfo.contact_estimated_time_from')}}</label>
                @elseif (Session::has('demand_errors.check_contact_estimated_time_to'))
                    <label
                            class="invalid-feedback d-block invalid-time">{{Session::get('demand_errors.check_contact_estimated_time_to')}}</label>
                @elseif ($errors->has('demandInfo.check_estimated_require_to'))
                    <label
                            class="invalid-feedback d-block invalid-time">{{$errors->first('demandInfo.check_estimated_require_to')}}</label>
                @endif
                <div id="msg-required-visit"></div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="cyzen_commission_corp" value>
