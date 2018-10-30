@extends('layouts.app')

@section('content')
    @php
        $isError = $errors->any()
    @endphp
    <section class="agreement-system">
        @include('agreement.system.progress')
        <form method="post" action="{{route('agreementSystem.postStep2')}}" id="agreementSystemStep2">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12">
                    @if ($errors->any())
                        <div class="box__mess box--error">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    <input name="mCorp[id]" value="{{$mCorp->id}}" type="hidden">
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.corporate/individual')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom group-companyKind">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="companyKind_1" name="mCorp[corp_kind]" required value="Corp"
                                   @if($mCorp->corp_kind == \App\Models\MCorp::CORP) {{'checked'}} @endif class="custom-control-input">
                            <label class="custom-control-label" for="companyKind_1">
                                @lang('agreement_system.corp_kind_corp')
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="companyKind_2" name="mCorp[corp_kind]" required value="Person"
                                   @if($mCorp->corp_kind == \App\Models\MCorp::PERSON) {{'checked'}} @endif class="custom-control-input">
                            <label class="custom-control-label" for="companyKind_2">
                                @lang('agreement_system.corp_kind_person')
                            </label>
                        </div>
                        <div class="text-danger font-weight-bold err-group-radioButton" hidden>{{ __('common.required_field') }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.listing')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom group-listedFlag">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="listedFlag_1" name="affiliationInfo[listed_kind]" value="listed" required
                                   @if($affiliationInfo['listed_kind'] == \App\Models\AffiliationInfo::LISTED) {{'checked'}} @endif class="custom-control-input">
                            <label class="custom-control-label" for="listedFlag_1">
                                @lang('agreement_system.listed')
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="listedFlag_2" name="affiliationInfo[listed_kind]" value="unlisted" required
                                   @if($affiliationInfo['listed_kind'] == \App\Models\AffiliationInfo::UNLISTED) {{'checked'}} @endif class="custom-control-input">
                            <label class="custom-control-label" for="listedFlag_2">
                                @lang('agreement_system.unlisted')
                            </label>
                        </div>
                        <div class="text-danger font-weight-bold err-group-radioButton" hidden>{{ __('common.required_field') }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.tax')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom group-taxPayment">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="taxPayment_1" name="affiliationInfo[default_tax]" value="false" required
                                   @if($affiliationInfo['default_tax'] == false) {{'checked'}} @endif class="custom-control-input">
                            <label class="custom-control-label" for="taxPayment_1">
                                @lang('agreement_system.no_delinquency')
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="taxPayment_2" name="affiliationInfo[default_tax]" value="true" required
                                   @if($affiliationInfo['default_tax'] == true) {{'checked'}} @endif class="custom-control-input">
                            <label class="custom-control-label" for="taxPayment_2">
                                @lang('agreement_system.delinquent')
                            </label>
                        </div>
                        <div class="text-danger font-weight-bold err-group-radioButton" hidden>{{ __('common.required_field') }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.capital_stock')</strong>
                        </label>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row">
                            <div class="col-auto">
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" id="affiliationInfoCapitalStock"
                                           class="form-control form-control-sm"
                                           name="affiliationInfo[capital_stock]" data-rule-maxlength="30" maxlength="30"
                                           value="@if(isset($affiliationInfo['capital_stock'])){{$affiliationInfo['capital_stock']}}@endif"
                                           data-rule-required="#companyKind_1:checked"
                                           data-rule-number="true">
                                </div>
                                <label class="m-0 d-inline-flex">@lang('agreement_system.yen')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.staff_amount')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row">
                            <div class="col-auto">
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" id="staffAmount"
                                           class="form-control form-control-sm"
                                           name="affiliationInfo[employees]"
                                           data-rule-maxlength="10" maxlength="10"
                                           value="{{$affiliationInfo['employees']}}"
                                           data-rule-required="true" data-rule-digits="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.to_whom_it_may_concern')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row mb-3">
                            <div class="col-auto">
                                <label for="mCorpResponsibility_sei" class="d-inline-flex m-0">
                                    @lang('agreement_system.representative')　@lang('agreement_system.surname')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" class="form-control form-control-sm"
                                           name="mCorp[responsibility_sei]"
                                           id="mCorpResponsibility_sei"
                                           data-rule-maxlength="10" maxlength="10"
                                           value="{{$data['responsibilitySei']}}"
                                           data-rule-required="true">
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="mCorpResponsibility_mei" class="d-inline-flex m-0">
                                    @lang('agreement_system.name')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" class="form-control form-control-sm"
                                           name="mCorp[responsibility_mei]"
                                           id="mCorpResponsibility_mei"
                                           data-rule-maxlength="10" maxlength="10"
                                           value="{{$data['responsibilityMei']}}"
                                           data-rule-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-auto">
                                <label for="mCorpCorp_person" class="d-inline-flex m-0">
                                    @lang('agreement_system.person_in_charge')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" class="form-control form-control-sm"
                                           name="mCorp[corp_person]"
                                           id="mCorpCorp_person"
                                           data-rule-maxlength="20" maxlength="20"
                                           value="{{$mCorp->corp_person}}"
                                           data-rule-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p class="text-primary m-0">
                                    @lang('agreement_system.register_correctly_please')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.office_location/document_destination')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row mb-3 align-items-center">
                            <div class="col-auto">
                                <label for="postcode" class="d-inline-flex m-0">
                                    @lang('agreement_system.symbol_postal_code')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" class="form-control form-control-sm"
                                           name="mCorp[postcode]"
                                           id="postcode"
                                           data-rule-maxlength="7" maxlength="7"
                                           value="{{$mCorp->postcode}}"
                                           data-rule-digits="true">
                                </div>
                                <button id="search_address" type="button"
                                        class="btn btn--gradient-default btn-sm remove-effect-btn">
                                    @lang('agreement_system.search_address')
                                </button>
                            </div>
                            <div class="col-auto">
                                <p class="text-muted m-0">
                                    @lang('agreement_system.zip_code_hint')
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-auto">
                                <select id="address1" name="mCorp[address1]"
                                        class="custom-select custom-select-sm"
                                        data-rule-required="true">
                                    @foreach($data['prefectureDiv'] as $key => $value)
                                        <option
                                            value="{{$key}}" @if($key == $mCorp->address1) {{'selected'}} @endif>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="address2" class="d-inline-flex m-0">
                                    @lang('agreement_system.city')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input id="address2"
                                           class="form-control form-control-sm"
                                           value="{{$mCorp->address2}}"
                                           type="text"
                                           data-rule-maxlength="20" maxlength="20"
                                           name="mCorp[address2]"
                                           data-rule-required="true">
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="address3" class="d-inline-flex m-0">
                                    @lang('agreement_system.another_address')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input id="address3"
                                           class="form-control form-control-sm"
                                           value="{{$mCorp->address3}}"
                                           type="text"
                                           data-rule-maxlength="100" maxlength="100"
                                           name="mCorp[address3]"
                                           data-rule-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p class="text-primary m-0">
                                    @lang('agreement_system.register_correctly_please')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.head_office_address/representative_address')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row mb-3 align-items-center">
                            <div class="col-auto">
                                <label for="representative_postcode" class="d-inline-flex m-0">
                                    @lang('agreement_system.symbol_postal_code')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" class="form-control form-control-sm"
                                           name="mCorp[representative_postcode]"
                                           id="representative_postcode"
                                           data-rule-maxlength="7" maxlength="7"
                                           value="{{$mCorp->representative_postcode}}"
                                           data-rule-digits="true">
                                </div>
                                <button id="search_representative_address" type="button"
                                        class="btn btn--gradient-default btn-sm remove-effect-btn">
                                    @lang('agreement_system.search_address')
                                </button>
                            </div>
                            <div class="col-auto">
                                <p class="text-muted m-0">
                                    @lang('agreement_system.zip_code_hint')
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-auto">
                                <select id="representative_address1" name="mCorp[representative_address1]"
                                        class="custom-select custom-select-sm"
                                        data-rule-required="true">
                                    @foreach($data['prefectureDiv'] as $key => $value)
                                        <option
                                            value="{{$key}}" @if($key == $mCorp->representative_address1) {{'selected'}} @endif>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="representative_address2" class="d-inline-flex m-0">
                                    @lang('agreement_system.city')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input id="representative_address2"
                                           class="form-control form-control-sm"
                                           value="{{$mCorp->representative_address2}}"
                                           type="text"
                                           data-rule-maxlength="20" maxlength="20"
                                           name="mCorp[representative_address2]"
                                           data-rule-required="true">
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="representative_address3" class="d-inline-flex m-0">
                                    @lang('agreement_system.another_address')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input id="representative_address3"
                                           class="form-control form-control-sm"
                                           value="{{$mCorp->representative_address3}}"
                                           type="text"
                                           data-rule-maxlength="100" maxlength="100"
                                           name="mCorp[representative_address3]"
                                           data-rule-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p class="text-primary m-0">
                                    @lang('agreement_system.register_correctly_please')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.phone_number')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <input type="text" class="form-control form-control-sm"
                                       name="mCorp[tel1]"
                                       data-rule-maxlength="11" maxlength="11"
                                       value="{{$mCorp->tel1}}"
                                       data-rule-required="true" data-rule-digits="true">
                            </div>
                            <div class="col-auto">
                                <p class="text-muted m-0">
                                    @lang('agreement_system.phone_number_hint')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.pc_mail')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row">
                            <div class="col-auto">
                                <input type="text" class="form-control form-control-sm multiple-email-validation"
                                       name="mCorp[mailaddress_pc]"
                                       value="{{$mCorp->mailaddress_pc}}"
                                       data-rule-required="true">
                            </div>
                            <div class="col-auto">
                                <p class="text-muted m-0">
                                    @lang('agreement_system.pc_mail_hint1')<br>
                                    {!! __('agreement_system.pc_mail_hint2') !!}<br>
                                    {!! __('agreement_system.pc_mail_hint3') !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.mobile_mail')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="form-row">
                            <div class="col-auto">
                                <div class="custom-control custom-control-inline custom-checkbox mobile-mail-none">
                                    <input type="checkbox" class="custom-control-input ignore"
                                           id="mobileMailNone" name="mCorp[mobile_mail_none]"
                                           value="1"
                                    @if($mCorp->mobile_mail_none == 1) {{'checked'}} @endif >
                                    <label class="custom-control-label" for="mobileMailNone">@lang('agreement_system.do_not_have')</label>
                                </div>
                                <label>@lang('agreement_system.check')</label>
                            </div>
                            <div class="col-auto">
                                <select id="mobileTelType" name="mCorp[mobile_tel_type]"
                                        class="custom-select custom-select-sm">
                                    <option value="" @if("" == $mCorp->mobile_tel_type) {{'selected'}} @endif>
                                        @lang('agreement_system.none')
                                    </option>
                                    <option value="1" @if("1" == $mCorp->mobile_tel_type) {{'selected'}} @endif>
                                        @lang('agreement_system.smart_phone')
                                    </option>
                                    <option value="2" @if("2" == $mCorp->mobile_tel_type) {{'selected'}} @endif>
                                        @lang('agreement_system.ordinary_mobile')
                                    </option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <input id="mailaddressMobile" type="text"
                                       class="form-control form-control-sm multiple-email-validation"
                                       name="mCorp[mailaddress_mobile]"
                                       value="{{$mCorp->mailaddress_mobile}}"
                                       data-rule-required="true">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.agency_telephone_number')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row">
                            <div class="col-auto">
                                <input value="{{$mCorp->corp_commission_type}}" type="hidden">
                                <input type="text" class="form-control form-control-sm"
                                       name="mCorp[commission_dial]"
                                       value="{{$mCorp->commission_dial}}"
                                       data-rule-required="true" data-rule-digits="true"
                                       data-rule-maxlength="11" maxlength="11">
                            </div>
                            <div class="col-auto">
                                <p class="text-muted m-0">
                                    @lang('agreement_system.agency_telephone_number_hint1')<br>
                                    @lang('agreement_system.agency_telephone_number_hint2')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.coordination_method')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row">
                            <div class="col-auto">
                                <select id="coordinationMethod" name="mCorp[coordination_method]"
                                        class="custom-select custom-select-sm"
                                        data-rule-required="true">
                                    @foreach($data['coordinationMethodList'] as $key => $value)
                                        <option
                                            value="{{$key}}" @if($key == $mCorp->coordination_method) {{'selected'}} @endif>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto" id="coordinationMethodNote">
                                <p class="text--orange-light border border-thick border-note p-2 bg-note m-0">
                                    @lang('agreement_system.coordination_method_hint1')
                                    <br/>@lang('agreement_system.coordination_method_hint2')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.fax_number')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right" id="label-required-mCorpFax">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row">
                            <div class="col-auto">
                                <input type="text" name="mCorp[fax]" id="mCorpFax"
                                       value="{{$mCorp->fax}}"
                                       data-rule-maxlength="11" maxlength="11"
                                       class="form-control form-control-sm"
                                       data-rule-required="true" data-rule-digits="true">
                            </div>
                            <div class="col-auto">
                                <p class="text-muted m-0">
                                    @lang('agreement_system.fax_number_hint1')
                                    <br>@lang('agreement_system.fax_number_hint2')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.24hour_correspondence')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="form-row">
                            <div class="col-auto">
                                @php
                                    $isSupport24HourChecked = $isAvailableTimeOtherChecked = false;
                                    if ($isError) {
                                        $isSupport24HourChecked = old('mCorp.support24hour') == 1 ? true : $isSupport24HourChecked;
                                        $isAvailableTimeOtherChecked = old('mCorp.available_time_other') == 1 ? true : $isAvailableTimeOtherChecked;
                                    } else {
                                        $isSupport24HourChecked = $mCorp->support24hour == 1 ? true : $isSupport24HourChecked;
                                        $isAvailableTimeOtherChecked = $mCorp->available_time_other == 1 ? true : $isAvailableTimeOtherChecked;
                                    }
                                @endphp
                                <div data-group-required=true>
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="mCorp[support24hour]" value="1"
                                               class="custom-control-input"
                                               id="support24hour" {{$isSupport24HourChecked ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="support24hour">
                                            @lang('agreement_system.24h_correspondence')
                                        </label>
                                    </div>
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="mCorp[available_time_other]" value="1"
                                               class="custom-control-input" id="supportOther"
                                            {{ $isAvailableTimeOtherChecked ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="supportOther">
                                            @lang('agreement_system.other')
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-inline-flex flex-column">
                                    <input type="text" name="mCorp[available_time_from]"
                                           id="availableTimeFrom"
                                           value="{{$isError ? old('mCorp.available_time_from') : $mCorp->available_time_from }}"
                                           class="timepicker form-control form-control-sm d-inline-block w-auto"
                                           data-rule-lessThanTime="#availableTimeTo">
                                </div>
                                <label>{{ trans('common.wavy_seal') }}</label>
                                <div class="d-inline-flex flex-column">
                                    <input type="text" name="mCorp[available_time_to]"
                                           id="availableTimeTo"
                                           value="{{$isError ? old('mCorp.available_time_to') : $mCorp->available_time_to}}"
                                           class="timepicker form-control form-control-sm d-inline-block w-auto">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.available_time')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="form-row">
                            <div class="col-auto">
                                @php
                                    $isContactableSupport24HourChecked = $isContactableTimeOtherChecked = false;
                                    if ($isError) {
                                        $isContactableSupport24HourChecked = old('mCorp.contactable_support24hour') == 1 ? true : $isContactableSupport24HourChecked;
                                        $isContactableTimeOtherChecked = old('mCorp.contactable_time_other') == 1 ? true : $isContactableTimeOtherChecked;
                                    } else {
                                        $isContactableSupport24HourChecked = $mCorp->contactable_support24hour == 1 ? true : $isContactableSupport24HourChecked;
                                        $isContactableTimeOtherChecked = $mCorp->contactable_time_other == 1 ? true : $isContactableTimeOtherChecked;
                                    }
                                @endphp
                                <div data-group-required=true>
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="mCorp[contactable_support24hour]"
                                               class="custom-control-input" id="contactableSupport24hour"
                                               value="1"
                                            {{ $isContactableSupport24HourChecked ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="contactableSupport24hour">
                                            @lang('agreement_system.24h_correspondence')
                                        </label>
                                    </div>
                                    <div class="custom-control custom-control-inline custom-checkbox">
                                        <input type="checkbox" name="mCorp[contactable_time_other]"
                                               class="custom-control-input" id="contactableSupportOther"
                                               value="1"
                                            {{ $isContactableTimeOtherChecked ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="contactableSupportOther">
                                            @lang('agreement_system.other')
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-inline-flex flex-column">
                                    <input type="text" name="mCorp[contactable_time_from]"
                                           id="contactableTimeFrom"
                                           value="{{$isError ? old('mCorp.contactable_time_from') : $mCorp->contactable_time_from}}"
                                           class="timepicker form-control form-control-sm d-inline-block w-auto"
                                           data-rule-lessThanTime="#contactableTimeTo">
                                </div>
                                <label>{{ trans('common.wavy_seal') }}</label>
                                <div class="d-inline-flex flex-column">
                                    <input type="text" name="mCorp[contactable_time_to]"
                                           id="contactableTimeTo"
                                           value="{{$isError ? old('mCorp.contactable_time_to') : $mCorp->contactable_time_to}}"
                                           class="timepicker form-control form-control-sm d-inline-block w-auto">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.holiday')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="form-row">
                            <div class="col-auto" data-group-required="true">
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="holidays[holidayNo]" type="checkbox" id="holidayNo"
                                           class="custom-control-input holidayNo"
                                           value="1" @if(in_array(1, $corpHolidays)) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="holidayNo">
                                        @lang('agreement_system.no_rest')
                                    </label>
                                </div>
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="holidays[holidayMon]" type="checkbox" id="holidayMon"
                                           class="custom-control-input holiday"
                                           value="2" @if(in_array(2, $corpHolidays)) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="holidayMon">
                                        @lang('agreement_system.mon')
                                    </label>
                                </div>
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="holidays[holidayTue]" type="checkbox" id="holidayTue"
                                           class="custom-control-input holiday"
                                           value="3" @if(in_array(3, $corpHolidays)) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="holidayTue">
                                        @lang('agreement_system.tue')
                                    </label>
                                </div>
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="holidays[holidayWed]" type="checkbox" id="holidayWed"
                                           class="custom-control-input holiday"
                                           value="4" @if(in_array(4, $corpHolidays)) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="holidayWed">
                                        @lang('agreement_system.wed')
                                    </label>
                                </div>
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="holidays[holidayThu]" type="checkbox" id="holidayThu"
                                           class="custom-control-input holiday"
                                           value="5" @if(in_array(5, $corpHolidays)) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="holidayThu">
                                        @lang('agreement_system.thu')
                                    </label>
                                </div>
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="holidays[holidayFri]" type="checkbox" id="holidayFri"
                                           class="custom-control-input holiday"
                                           value="6" @if(in_array(6, $corpHolidays)) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="holidayFri">
                                        @lang('agreement_system.fri')
                                    </label>
                                </div>
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="holidays[holidaySat]" type="checkbox" id="holidaySat"
                                           class="custom-control-input holiday"
                                           value="7" @if(in_array(7, $corpHolidays)) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="holidaySat">
                                        @lang('agreement_system.sat')
                                    </label>
                                </div>
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="holidays[holidaySun]" type="checkbox" id="holidaySun"
                                           class="custom-control-input holiday"
                                           value="8" @if(in_array(8, $corpHolidays)) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="holidaySun">
                                        @lang('agreement_system.sun')
                                    </label>
                                </div>
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input name="holidays[holidayHol]" type="checkbox" id="holidayHol"
                                           class="custom-control-input holiday"
                                           value="9" @if(in_array(9, $corpHolidays)) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="holidayHol">
                                        @lang('agreement_system.public_holiday')
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.refund_account')</strong>
                        </label>
                        <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="row mb-3">
                            <div class="col-auto">
                                <label for="refundBankName" class="d-inline-flex">
                                    @lang('agreement_system.bank_name')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" name="mCorp[refund_bank_name]"
                                           id="refundBankName"
                                           data-rule-maxlength="50" maxlength="50"
                                           value="{{$mCorp->refund_bank_name}}"
                                           class="form-control form-control-sm"
                                           data-rule-required="true">
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="refundBranchName" class="d-inline-flex">
                                    @lang('agreement_system.branch_name')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" name="mCorp[refund_branch_name]"
                                           id="refundBranchName"
                                           data-rule-maxlength="50" maxlength="50"
                                           value="{{$mCorp->refund_branch_name}}"
                                           class="form-control form-control-sm"
                                           data-rule-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <label for="refundAccountType" class="d-inline-flex">
                                    @lang('agreement_system.deposit_type')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" name="mCorp[refund_account_type]"
                                           id="refundAccountType"
                                           data-rule-maxlength="50" maxlength="50"
                                           value="{{$mCorp->refund_account_type}}"
                                           class="form-control form-control-sm"
                                           data-rule-required="true">
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="refundAccount" class="d-inline-flex">
                                    @lang('agreement_system.account_number')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" name="mCorp[refund_account]"
                                           id="refundAccount"
                                           data-rule-maxlength="11" maxlength="11"
                                           value="{{$mCorp->refund_account}}"
                                           class="form-control form-control-sm"
                                           data-rule-digits="true"
                                           data-rule-required="true" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 pr-lg-0">
                    <div class="form__label--white-light p-3 h-100 border-bottom">
                        <label class="m-0">
                            <strong>@lang('agreement_system.supported_language')</strong>
                        </label>
                    </div>
                </div>
                <div class="col-12 col-lg-9 pl-lg-0">
                    <div class="p-3 border-bottom">
                        <div class="form-row">
                            <div class="col-auto">
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input type="checkbox" id="supportLanguageEn"
                                           name="mCorp[support_language_en]"
                                           class="custom-control-input"
                                           value="1"
                                    @if($mCorp->support_language_en == 1) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="supportLanguageEn">
                                        @lang('agreement_system.english')
                                    </label>
                                </div>
                                <div class="custom-control custom-control-inline custom-checkbox">
                                    <input type="checkbox" id="supportLanguageZh"
                                           name="mCorp[support_language_zh]"
                                           class="custom-control-input"
                                           value="1"
                                    @if($mCorp->support_language_zh == 1) {{'checked'}} @endif>
                                    <label class="custom-control-label" for="supportLanguageZh">
                                        @lang('agreement_system.chinese')
                                    </label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="supportLanguageEmployees" class="d-inline-flex">
                                    @lang('agreement_system.available_employees')
                                </label>
                                <div class="d-inline-flex flex-column w-auto">
                                    <input type="text" name="mCorp[support_language_employees]"
                                           id="supportLanguageEmployees"
                                           value="{{$mCorp->support_language_employees}}"
                                           class="form-control form-control-sm"
                                           data-rule-digits="true"
                                           data-rule-maxlength="10" maxlength="10">
                                </div>
                                <label>@lang('agreement_system.man')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="agreement-system text-center">
                <button class="btn btn--gradient-default btn-lg" type="button" id="back_button">
                    @lang('agreement_system.btn.return')
                </button>
                <button class="btn btn--gradient-green btn-lg" type="submit">
                    @lang('agreement_system.btn.next')
                </button>
            </div>
        </form>
        <div id="page-data"
             data-get-address-by-postcode-url="{{ action('Ajax\AjaxController@searchAddressByZip') }}">
        </div>
    </section>
@endsection
@section('script')
    <script>
        var urlBackStep2 = '{{route('agreementSystem.getStep1')}}';
    </script>
    <script type="text/javascript" src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/helpers/address.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/step2_agreement_system.js')}}"></script>
    <script>
        jQuery(document).ready(function () {
            Step2AgreementSystem.init();
        });
    </script>
@endsection
