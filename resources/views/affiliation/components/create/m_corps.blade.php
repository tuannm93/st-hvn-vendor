{{-- Mcorp view --}}
<div class="form-category mb-4">
    <label class="form-category__label">{{ trans('affiliation_detail.corporate_intelligence') }}</label>
    <div class="form-category__body clearfix">
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.business_id') }}</label>
            <div class="col-12 col-sm-9 col-lg-6"></div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.follow_date') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control datetimepickerCustom" data-rule-pattern="\d{4}\/\d{2}\/\d{2}\s\d{2}:\d{2}" data-msg-pattern="{{ trans('affiliation_detail.validate_date_time_false') }}" name="data[m_corps][follow_date]" value="{{ old('data.m_corps.follow_date') }}" size='40' maxlength='40'>
                @include('element.error_line', ['attribute' => 'data.m_corps.follow_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.follow_person') }} </label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][follow_person]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($userList))
                        @foreach($userList as $key => $value)
                            <option @if($oldFollowPerson == $key) selected @elseif(Auth::getUser()->id == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.follow_person.'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.sharing_technology_personnel') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][rits_person]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($userList))
                        @foreach($userList as $key => $value)
                            <option @if($oldRitsPerson == $key) selected @elseif(Auth::getUser()->id == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.rits_person'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.document_send_request_date') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control datepicker monthdatepicker" name="data[m_corps][document_send_request_date]" value="{{ old('data.m_corps.document_send_request_date') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.document_send_request_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.contract_status') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][corp_commission_status]" @if($userRole == config('datacustom.auth_list.admin')) disabled="disabled" @endif>
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($contractStatus))
                        @foreach($contractStatus as $key => $value)
                            <option @if(old('data.m_corps.corp_commission_status') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.corp_commission_status'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.development_situation') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][corp_status]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($developmentSituation))
                        @foreach($developmentSituation as $key => $value)
                            <option @if(old('data.m_corps.corp_status') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.corp_status'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.agency_NG_conversion_date') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control datepicker" name="data[m_corps][commission_ng_date]" value="{{ old('data.m_corps.commission_ng_date') }}" size="10" maxlength="10">
                @include('element.error_line', ['attribute' => 'data.m_corps.commission_ng_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reason_for_loss_of_development') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][order_fail_reason]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($reasonLossDevelopment))
                        @foreach($reasonLossDevelopment as $key => $value)
                            <option @if(old('data.m_corps.order_fail_reason') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.order_fail_reason'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.affiliation_status') }}<span class="text-danger">*</span></label>
            <div class="col-12 col-sm-9 col-lg-6">
                <div data-group-required="true">
                @foreach($affiliationStatus as $key => $value)
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customRadioInline{{ $key }}" name="data[m_corps][affiliation_status]" class="custom-control-input" value="{{ $key }}" @if(old('data.m_corps.affiliation_status') !== null && old('data.m_corps.affiliation_status') == $key) checked @endif>
                        <label class="custom-control-label" for="customRadioInline{{ $key }}">{{ $value }}</label>
                    </div>
                @endforeach
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.affiliation_status'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.acquired_date') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" name="data[m_corps][contract_date]" class="form-control datepicker" value="{{ old('data.m_corps.contract_date') }}" size="10" maxlength="10">
                @include('element.error_line', ['attribute' => 'data.m_corps.contract_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.corp_name') }}<span class="text-danger">*</span></label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][corp_name]" value="{{ old('data.m_corps.corp_name') }}" data-rule-required="true" maxlength="100">
                @include('element.error_line', ['attribute' => 'data.m_corps.corp_name'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.corp_name_kana') }}<span class="text-danger">*</span>
            </label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][corp_name_kana]" value="{{ old('data.m_corps.corp_name_kana') }}" data-rule-required="true" maxlength="200">
                @include('element.error_line', ['attribute' => 'data.m_corps.corp_name_kana'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.official_corp_name') }}<span class="text-danger">*</span></label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][official_corp_name]" value="{{ old('data.m_corps.official_corp_name') }}" data-rule-required="true" maxlength="200">
                @include('element.error_line', ['attribute' => 'data.m_corps.official_corp_name'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.representative') }}<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <div class="form-inline row check-require-group">
                    <div class="col-sm-6 col-lg-4 mb-2 mb-sm-0 row">
                        <label class="align-items-center col-2 d-flex mb-0">{{ trans('affiliation_detail.surname') }}</label>
                        <input type="text" id="data_m_corps_responsibility_sei" class="form-control col-10" name="data[m_corps][responsibility_sei]" data-rule-required="true" data-error-container="#data_m_corps_responsibility_sei-err" maxlength="10" value="{{ old('data.m_corps.responsibility_sei') }}">
                        @include('element.error_line', ['attribute' => 'data.m_corps.responsibility_sei'])
                        <div id="data_m_corps_responsibility_sei-err" class="error-holder offset-2 col-10 px-0"></div>
                    </div>
                    <div class="col-sm-6 col-lg-4 row">
                        <label class="align-items-center col-2 d-flex mb-0">{{ trans('affiliation_detail.name') }}</label>
                        <input type="text" class="form-control col-10" name="data[m_corps][responsibility_mei]" size="10" maxlength="10" data-error-container="#data_m_corps_responsibility_mei-err" value="{{ old('data.m_corps.responsibility_mei') }}">
                        @include('element.error_line', ['attribute' => 'data.m_corps.responsibility_mei'])
                        <label id="pseudo-el-data_m_corps_responsibility_mei" class="invalid-feedback d-none opacity-0">@lang('common.required_field')</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.responsible_person') }}<span class="text-danger">*</span></label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][corp_person]" value="{{ old('data.m_corps.corp_person') }}" data-rule-required="true" maxlength="20">
                @include('element.error_line', ['attribute' => 'data.m_corps.corp_person'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.office_location_or_document_destination') }}</label>
            <div class="col-sm-9">
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.postal_code') }}</label>
                    <div class="col-sm-9 col-lg-6 row mx-0">
                        <div class="col-auto px-0">
                            <input type="text" class="form-control mr-sm-2" id="postcode" name="data[m_corps][postcode]" data-rule-number="true" size="7" maxlength="7" value="{{ old('data.m_corps.postcode') }}">
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn--gradient-orange d-inline-flex" id="postcode_fill">{{ trans('affiliation_detail.address_search') }}</button>
                        </div>
                        @include('element.error_line', ['attribute' => 'data.m_corps.postcode'])
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.prefectures') }}<span class="text-danger">*</span></label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <select class="custom-select my-1 mr-sm-2" id="address1" name="data[m_corps][address1]" data-rule-required="true">
                            <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                            @if(!empty($prefectureList))
                                @foreach($prefectureList as $key => $value)
                                    <option value="{{ $key }}" @if(old('data.m_corps.address1') == $key) selected @endif>{{ $value }}</option>
                                @endforeach
                            @endif
                        </select>
                        @include('element.error_line', ['attribute' => 'data.m_corps.address1'])
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.municipality') }}<span class="text-danger">*</span></label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <input type="text" class="form-control" id="address2" name="data[m_corps][address2]" value="{{ old('data.m_corps.address2') }}" data-rule-required="true" maxlength="20">
                        @include('element.error_line', ['attribute' => 'data.m_corps.address2'])
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.town_area') }}</label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <input type="text" class="form-control" id="address3" name="data[m_corps][address3]" data-rule-required="true" size="100" maxlength="100" value="{{ old('data.m_corps.address3') }}">
                        @include('element.error_line', ['attribute' => 'data.m_corps.address3'])
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.head_office_or_representative_residence') }}</label>
            <div class="col-sm-9">
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.postal_code') }}</label>
                    <div class="col-sm-9 col-lg-6 row mx-0">
                        <div class="col-auto px-0">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="representative_postcode" name="data[m_corps][representative_postcode]" data-rule-number="true" size="7" maxlength="7" value="{{ old('data.m_corps.representative_postcode') }}">
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn--gradient-orange mb-2" id="representative_postcode_fill">{{ trans('affiliation_detail.address_search') }}</button>
                        </div>
                        @include('element.error_line', ['attribute' => 'data.m_corps.representative_postcode'])
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.prefectures') }}</label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <select class="custom-select my-1 mr-sm-2" id="representative_address1" name="data[m_corps][representative_address1]">
                            <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                            @if(!empty($prefectureList))
                                @foreach($prefectureList as $key => $value)
                                    <option value="{{ $key }}" @if(old('data.m_corps.representative_address1') == $key) selected @endif >{{ $value }}</option>
                                @endforeach
                            @endif
                        </select>
                        @include('element.error_line', ['attribute' => 'data.m_corps.representative_address1'])
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.municipality') }}</label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <input type="text" class="form-control" id="representative_address2" name="data[m_corps][representative_address2]" size="20" maxlength="20" value="{{ old('data.m_corps.representative_address2') }}">
                        @include('element.error_line', ['attribute' => 'data.m_corps.representative_address2'])
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.town_area') }}</label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <input type="text" class="form-control" id="representative_address3" name="data[m_corps][representative_address3]" size="100" maxlength="100" value="{{ old('data.m_corps.representative_address3') }}">
                        @include('element.error_line', ['attribute' => 'data.m_corps.representative_address3'])
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.trade_name1') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][trade_name1]" value="{{ old('data.m_corps.trade_name1') }}" size="100" maxlength="100">
                @include('element.error_line', ['attribute' => 'data.m_corps.trade_name1'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.trade_name2') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][trade_name2]" value="{{ old('data.m_corps.trade_name2') }}" size="100" maxlength="100">
                @include('element.error_line', ['attribute' => 'data.m_corps.trade_name2'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.commission_dial') }}<span class="text-danger">*</span></label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" size="11" maxlength="11" name="data[m_corps][commission_dial]" data-rule-number="true" data-rule-required="true" value="{{ old('data.m_corps.commission_dial') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.commission_dial'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.tel1') }}<span class="text-danger">*</span></label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][tel1]" data-rule-number="true" data-rule-required="true" maxlength="11" value="{{ old('data.m_corps.tel1') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.tel1'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.tel2') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][tel2]" data-rule-number="true" size="11" maxlength="11" value="{{ old('data.m_corps.tel2') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.tel2'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.mobile_tel') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][mobile_tel]" data-rule-number="true" size="11" maxlength="11" value="{{ old('data.m_corps.mobile_tel') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.mobile_tel'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.fax_number') }} </label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][fax]" id="mcorps_fax" data-rule-number="true" data-rule-required="true" size="11" maxlength="11" value="{{ old('data.m_corps.fax') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.fax'])
            </div>
        </div>
        <div data-group-fill-required="true" data-msg-group-fill-required="{{ trans('affiliation_detail.required_validate') }}" id="mcorp-mail-toggle" data-error-container="#mcorp-mail-toggle-error-container">
            <div class="form-group row mb-2">
                <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.pc_mail') }}<span class="text-danger">*</span></label>
                <div class="col-12 col-sm-9 col-lg-6">
                    <input type="text" class="form-control multiple-email-validation" name="data[m_corps][mailaddress_pc]" id="mailaddress_pc" size="255" maxlength="255" value="{{ old('data.m_corps.mailaddress_pc') }}">
                    @include('element.error_line', ['attribute' => 'data.m_corps.mailaddress_pc'])
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.mobile_mail') }}<span class="text-danger">*</span></label>
                <div class="col-12 col-sm-9 col-lg-6">
                    <input type="text" class="form-control multiple-email-validation" name="data[m_corps][mailaddress_mobile]" id="mailaddress_mobile" size="255" maxlength="255" value="{{ old('data.m_corps.mailaddress_mobile') }}">
                    @include('element.error_line', ['attribute' => 'data.m_corps.mailaddress_mobile'])
                    <p id="passwordHelpInline" class="text-muted mt-2 mb-0">
                        {{ trans('affiliation_detail.confirm_input_mail') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12 col-sm-9 offset-sm-3">
                <div id="mcorp-mail-toggle-error-container"></div>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.url') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][url]" data-rule-url="true" maxlength="2048" value="{{ old('data.m_corps.url') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.url'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.progress_check_tel') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][progress_check_tel]" data-rule-number="true" size="11" maxlength="11" value="{{ old('data.m_corps.progress_check_tel') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.progress_check_tel'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.progress_confirmation_person') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][progress_check_person]" size="200" maxlength="200" value="{{ old('data.m_corps.progress_check_person') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.progress_check_person'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.corresponding_area') }} </label>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.target_range') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][target_range]" value="{{ old('data.m_corps.target_range') }}" size="20" maxlength="20">
                @include('element.error_line', ['attribute' => 'data.m_corps.target_range'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">
                {{ trans('affiliation_detail.business_hours') }}<span class="text-danger">*</span>
            </label>
            <div class="col-12 col-sm-9 col-lg-6 form-inline">
                <div data-group-required="true">
                    <div class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="support24hour" {{ $support24hourChecked }} name="data[m_corps][support24hour]" value="1">
                        <label class="custom-control-label" for="support24hour">
                            {{ trans('affiliation_detail.support24hour') }}
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="available_time_other" {{ $availableTimeOtherChecked }} name="data[m_corps][available_time_other]" value="1">
                        <label class="custom-control-label" for="available_time_other">
                            {{ trans('affiliation_detail.available_time_other') }}
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <input type="text" class="form-control timepicker timeAffPicker mr-2 w-100" name="data[m_corps][available_time_from]" id="available_time_from" size="50" maxlength="50" value="{{ old('data.m_corps.available_time_from') }}">
                    </div>
                    <label class="col-2">
                        {{ trans('common.wavy_seal') }}
                    </label>
                    <div class="col-5">
                        <input type="text" class="form-control timepicker timeAffPicker w-100" name="data[m_corps][available_time_to]" id="available_time_to" size="50" maxlength="50" value="{{ old('data.m_corps.available_time_to') }}">
                    </div>
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.support24hour'])
                @include('element.error_line', ['attribute' => 'data.m_corps.available_time_other'])
                @include('element.error_line', ['attribute' => 'data.m_corps.available_time_from'])
                @include('element.error_line', ['attribute' => 'data.m_corps.available_time_to'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">
                {{ trans('affiliation_detail.contactable_support24hour') }}<span class="text-danger">*</span>
            </label>
            <div class="col-12 col-sm-9 col-lg-6 form-inline">
                <div data-group-required="true">
                    <div class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="contactable_support24hour" {{ $contactSupport24hourChecked }} name="data[m_corps][contactable_support24hour]" value="1">
                        <label class="custom-control-label" for="contactable_support24hour">
                            {{ trans('affiliation_detail.support24hour') }}
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-inline mb-1">
                        <input class="custom-control-input" type="checkbox" id="contactable_time_other" name="data[m_corps][contactable_time_other]" {{$contactTimeOtherChecked}}  value="1">
                        <label class="custom-control-label" for="contactable_time_other">
                            {{ trans('affiliation_detail.available_time_other') }}
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <input type="text" class="form-control timepicker timeAffPicker mb-2 mr-2 w-100" name="data[m_corps][contactable_time_from]" id="contactable_time_from" size="50" maxlength="50" value="{{ old('data.m_corps.contactable_time_from') }}">
                    </div>
                    <label class="col-2">
                        {{ trans('common.wavy_seal') }}
                    </label>
                    <div class="col-5">
                        <input type="text" class="form-control timepicker timeAffPicker mb-2 w-100" name="data[m_corps][contactable_time_to]" id="contactable_time_to" size="50" maxlength="50" value="{{ old('data.m_corps.contactable_time_to') }}">
                    </div>
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.contactable_support24hour'])
                @include('element.error_line', ['attribute' => 'data.m_corps.contactable_time_other'])
                @include('element.error_line', ['attribute' => 'data.m_corps.contactable_time_from'])
                @include('element.error_line', ['attribute' => 'data.m_corps.contactable_time_to'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">
                {{ trans('affiliation_detail.holiday') }}<span class="text-danger">*</span>
            </label>
            <div class="col-12 col-sm-9 ">
                <div class="form-inline" data-group-required="true">
                    @foreach($holiday as $key => $value)
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input class="custom-control-input @if($key === 1) category-holiday-no-rest @else category-holiday @endif" type="checkbox" @if(in_array($key, $holidayChecked)) checked="checked" @endif id="check{{ $key }}" name="data[m_corp_subs][holiday][]" value="{{ $key }}">
                            <label class="custom-control-label" for="check{{ $key }}">{{ $value }}</label>
                        </div>
                    @endforeach
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corp_subs.holiday'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.long_term_holidays') }}</label>
            <div class="col-sm-9">
                <div class="form-row">
                    @foreach($vacation as $key => $value)
                        <input type="hidden" value="{{ $value }}" name="data[m_corp_new_years][label_{{ sprintf('%02d', $key) }}]">
                        <div class="form-group col-sm-4 col-md-2">
                            <label for="" class="mb-0">{{ $value }}</label>
                            <select class="custom-select my-1 mr-sm-2" name="data[m_corp_new_years][status_{{ sprintf('%02d', $key) }}]">
                                <option value=""></option>
                                <option value="{{ trans('affiliation_detail.operation') }}" @if(old("data.m_corp_new_years.status_".sprintf('%02d', $key)) == trans('affiliation_detail.operation')) selected @endif>{{ trans('affiliation_detail.operation') }}</option>
                                <option value="{{ trans('affiliation_detail.tel_only') }}" @if(old("data.m_corp_new_years.status_".sprintf('%02d', $key)) == trans('affiliation_detail.tel_only')) selected @endif>{{ trans('affiliation_detail.tel_only') }}</option>
                                <option value="{{ trans('affiliation_detail.vacation') }}" @if(old("data.m_corp_new_years.status_".sprintf('%02d', $key)) == trans('affiliation_detail.vacation')) selected @endif>{{ trans('affiliation_detail.vacation') }}</option>
                            </select>
                        </div>
                    @endforeach
                </div>
                @if(count($vacation) > 0)
                    <div class="form-group mb-2">
                        <label>{{ trans('affiliation_detail.remarks') }}</label>
                        <textarea class="form-control" rows="5" name="data[m_corp_new_years][note]">{{ old('data.m_corp_new_years.note') }}</textarea>
                    </div>
                @endif
                @include('element.error_line', ['attribute' => 'data.m_corp_new_years.note'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.free_estimate') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][free_estimate]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($freeEstimate))
                        @foreach($freeEstimate as $key => $value)
                            <option value="{{ $key }}" @if(old('data.m_corps.free_estimate') == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.free_estimate'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.portalsite') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][portalsite]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($portalSite))
                        @foreach($portalSite as $key => $value)
                            <option @if(old('data.m_corps.portalsite') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.portalsite'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reg_send_date') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control datepicker" name="data[m_corps][reg_send_date]" size="10" maxlength="10" value="{{ old('data.m_corps.reg_send_date') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.reg_send_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reg_send_method') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][reg_send_method]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($regSendMethod))
                        @foreach($regSendMethod as $key => $value)
                            <option @if(old('data.m_corps.reg_send_method') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.reg_send_method'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reg_collect_date') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control datepicker" name="data[m_corps][reg_collect_date]" size="10" maxlength="10" value="{{ old('data.m_corps.reg_collect_date') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.reg_collect_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.ps_app_send_date') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control datepicker" name="data[m_corps][ps_app_send_date]" size="10" maxlength="10" value="{{ old('data.m_corps.ps_app_send_date') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.ps_app_send_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.ps_app_collect_date') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control datepicker" name="data[m_corps][ps_app_collect_date]" size="10" maxlength="10" value="{{ old('data.m_corps.ps_app_collect_date') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.ps_app_collect_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.coordination_method') }}<span class="text-danger">*</span></label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][coordination_method]" data-rule-required="true" id="coordination_method">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($coordinationMethod))
                        @foreach($coordinationMethod as $key => $value)
                            <option value="{{ $key }}" @if(old('data.m_corps.coordination_method') == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.coordination_method'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.listed_media') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control" name="data[m_corps][listed_media]" maxlength="255" value="{{ old('data.m_corps.listed_media') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.listed_media'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.corp_commission_type') }} </label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][corp_commission_type]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($corpCommissionType))
                        @foreach($corpCommissionType as $key => $value)
                            <option @if(old('data.m_corps.corp_commission_type') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.corp_commission_type'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.jbr_available_status') }} </label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][jbr_available_status]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($jbrAvailableStatus))
                        @foreach($jbrAvailableStatus as $key => $value)
                            <option @if(old('data.m_corps.jbr_available_status') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.jbr_available_status'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.mailaddress_auction') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control multiple-email-validation" name="data[m_corps][mailaddress_auction]" size="10" maxlength="255" value="{{ old('data.m_corps.mailaddress_auction') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.mailaddress_auction'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.auction_status') }} </label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][auction_status]">
                    @if(!empty($auctionStatus))
                        @foreach($auctionStatus as $key => $value)
                            <option @if(old('data.m_corps.auction_status') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.auction_status'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.auction_masking') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][auction_masking]">
                    @if(!empty($auctionMasking))
                        @foreach($auctionMasking as $key => $value)
                            <option @if(old('data.m_corps.auction_masking') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.auction_masking'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.refund_account') }}</label>
            <div class="col-sm-9">
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.refund_bank_name') }}</label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <input type="text" class="form-control" name="data[m_corps][refund_bank_name]" size="50" maxlength="50" value="{{ old('data.m_corps.refund_bank_name') }}">
                        @include('element.error_line', ['attribute' => 'data.m_corps.refund_bank_name'])
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.refund_branch_name') }}</label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <input type="text" class="form-control" name="data[m_corps][refund_branch_name]" size="50" maxlength="50" value="{{ old('data.m_corps.refund_branch_name') }}">
                        @include('element.error_line', ['attribute' => 'data.m_corps.refund_branch_name'])
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.refund_account_type') }}</label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <input type="text" class="form-control" name="data[m_corps][refund_account_type]" size="20" maxlength="20" value="{{ old('data.m_corps.refund_account_type') }}">
                        @include('element.error_line', ['attribute' => 'data.m_corps.refund_account_type'])
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-lg-2 col-form-label">{{ trans('affiliation_detail.refund_account_number') }}</label>
                    <div class="col-12 col-sm-9 col-lg-6">
                        <input data-rule-numberHalfSize="true" type="text" class="form-control" name="data[m_corps][refund_account]" size="14" maxlength="14" value="{{ old('data.m_corps.refund_account') }}">
                        @include('element.error_line', ['attribute' => 'data.m_corps.refund_account'])
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.support_language') }} </label>
            <div class="col-12 col-sm-9 form-inline">
                <div class="custom-control custom-checkbox custom-control-inline">
                    <input class="custom-control-input" type="checkbox" id="support_language_en" name="data[m_corps][support_language_en]" {{$supportLanguageEnChecked}} value="1">
                    <label class="custom-control-label" for="support_language_en">
                        {{ trans('affiliation_detail.support_language_en') }}
                    </label>
                </div>
                <div class="custom-control custom-checkbox custom-control-inline">
                    <input class="custom-control-input" type="checkbox" id="support_language_zh" name="data[m_corps][support_language_zh]" {{$supportLanguageZhChecked}} value="1">
                    <label class="custom-control-label" for="support_language_zh">
                        {{ trans('affiliation_detail.support_language_zh') }}
                    </label>
                </div>
                <div class="form-inline ">
                    <label for="check2" class="mr-2 ">
                        {{ trans('affiliation_detail.support_language_employees') }}
                    </label>
                    <div class="d-inline-flex flex-column">
                        <input type="text" class="form-control" name="data[m_corps][support_language_employees]" data-rule-number="true" size="10" maxlength="10" value="{{ old('data.m_corps.support_language_employees') }}">
                    </div>
                    <label for="check2" class="mr-2 ">
                        {{ trans('affiliation_detail.people') }}
                    </label>
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.support_language_zh'])
                @include('element.error_line', ['attribute' => 'data.m_corps.support_language_en'])
                @include('element.error_line', ['attribute' => 'data.m_corps.support_language_employees'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.auto_call_flag_label') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <select class="custom-select my-1 mr-sm-2" name="data[m_corps][auto_call_flag]">
                    @if(!empty($autoCallFlag))
                        @foreach($autoCallFlag as $key => $value)
                            <option @if(old('data.m_corps.auto_call_flag') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.auto_call_flag'])
            </div>
        </div>
    </div>
</div>
