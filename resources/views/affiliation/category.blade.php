@extends('layouts.app')

@php
    $oldData = old('data');
    $oldAddress1 = old('data.m_corps.address1');
    $oldRepreAddress1 = old('data.m_corps.representative_address1');
    $oldCoordinationMethod = old('data.m_corps.coordination_method');
    $oldMobileMailNone = old('data.m_corps.mobile_mail_none');
    $oldSupportLanguageEn = old('data.m_corps.support_language_en');
    $oldSupportLanguageZh = old('data.m_corps.support_language_zh');
    $oldContactableSupport24hour = old('data.m_corps.contactable_support24hour');
    $oldSupport24hour = old('data.m_corps.support24hour');
    $oldContactableTimeOther = old('data.m_corps.contactable_time_other');
    $oldAvailableTimeOther = old('data.m_corps.available_time_other');

    $mCorpsResponsibilitySei = isset($corpData->m_corps_responsibility_sei) ? $corpData->m_corps_responsibility_sei : '';
    $mCorpsResponsibilityMei = isset($corpData->m_corps_responsibility_mei) ? $corpData->m_corps_responsibility_mei : '';
    $mCorpCorpPerson = isset($corpData->m_corps_corp_person) ? $corpData->m_corps_corp_person : '';
    $mCorpsPostcode = isset($corpData->m_corps_postcode) ? $corpData->m_corps_postcode : '';
    $mCorpsAddress2 = isset($corpData->m_corps_address2) ? $corpData->m_corps_address2 : '';
    $mCorpsAddress3 = isset($corpData->m_corps_address3) ? $corpData->m_corps_address3 : '';
    $mCorpsReprePostcode = isset($corpData->m_corps_representative_postcode) ? $corpData->m_corps_representative_postcode : '';
    $mCorpsRepreAddress2 = isset($corpData->m_corps_representative_address2) ? $corpData->m_corps_representative_address2 : '';
    $mCorpsRepreAddress3 = isset($corpData->m_corps_representative_address3) ? $corpData->m_corps_representative_address3 : '';
    $mCorpsTel1 = isset($corpData->m_corps_tel1) ? $corpData->m_corps_tel1 : '';
    $mCorpsMailAddressPc = isset($corpData->m_corps_mailaddress_pc) ? $corpData->m_corps_mailaddress_pc : '';
    $mCorpsMailAddressMobile = isset($corpData->m_corps_mailaddress_mobile) ? $corpData->m_corps_mailaddress_mobile : '';
    $mCorpsCommissionDial = isset($corpData->m_corps_commission_dial) ? $corpData->m_corps_commission_dial : '';
    $mCorpsMailaddressAuction = isset($corpData->m_corps_mailaddress_auction) ? $corpData->m_corps_mailaddress_auction : '';
    $mCorpsFax = isset($corpData->m_corps_fax) ? $corpData->m_corps_fax : '';
    $mCorpsBankName = isset($corpData->m_corps_refund_bank_name) ? $corpData->m_corps_refund_bank_name : '';
    $mCorpsBranchName = isset($corpData->m_corps_refund_branch_name) ? $corpData->m_corps_refund_branch_name : '';
    $mCorpsAccountType = isset($corpData->m_corps_refund_account_type) ? $corpData->m_corps_refund_account_type : '';
    $mCorpsRefundAccount = isset($corpData->m_corps_refund_account) ? $corpData->m_corps_refund_account : '';
    $mCorpsLanguageSupport = isset($corpData->m_corps_support_language_employees) ? $corpData->m_corps_support_language_employees : '';

    if(isset($oldData)) {
        $mCorpsResponsibilitySei = old('data.m_corps.responsibility_sei');
        $mCorpsResponsibilityMei = old('data.m_corps.responsibility_mei');
        $mCorpCorpPerson = old('data.m_corps.corp_person');
        $mCorpsPostcode = old('data.m_corps.postcode');
        $mCorpsAddress2 = old('data.m_corps.address2');
        $mCorpsAddress3 = old('data.m_corps.address3');
        $mCorpsReprePostcode = old('data.m_corps.representative_postcode');
        $mCorpsRepreAddress2 = old('data.m_corps.representative_address2');
        $mCorpsRepreAddress3 = old('data.m_corps.representative_address3');
        $mCorpsTel1 = old('data.m_corps.tel1');
        $mCorpsMailAddressPc = old('data.m_corps.mailaddress_pc');
        $mCorpsMailAddressMobile = old('data.m_corps.mailaddress_mobile');
        $mCorpsCommissionDial = old('data.m_corps.commission_dial');
        $mCorpsMailaddressAuction = old('data.m_corps.mailaddress_auction');
        $mCorpsFax = old('data.m_corps.fax');
        $mCorpsBankName = old('data.m_corps.refund_bank_name');
        $mCorpsBranchName = old('data.m_corps.refund_branch_name');
        $mCorpsAccountType = old('data.m_corps.refund_account_type');
        $mCorpsRefundAccount = old('data.m_corps.refund_account');
        $mCorpsLanguageSupport = old('data.m_corps.support_language_employees');
    }
@endphp

@section('content')
    <section class="content affiliation-category">

        <div class="row header-field my-4">
            <div class="col-sm-6 col-md-4">
                <strong>{{ __('affiliation.business_number') }}:</strong>
                <a target="_blank" class="text--orange"
                   href="{{ route('affiliation.detail.edit', $id) }}">
                    <u>{{ $corpData->m_corps_official_corp_name or '' }}</u>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <strong>
                    {{ __('affiliation.business_id') }}: {{ $corpData->m_corps_id or '' }}
                </strong>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-category">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>

                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ __('affiliation.not_empty_affiliation_base_item') }}
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-category__body">
                        <form action="{{ route('affiliation.updateCorp', ['id' => $id]) }}" method="post" class="form--border" id="updateCorp">
                            {{ csrf_field() }}
                            <a class="collapse-trigger d-block d-sm-none p-3" data-toggle="collapse" href="#affiliation-category1" role="button" aria-expanded="false" aria-controls="collapseExample">
                                {{ __('affiliation.company_information') }}
                                <span class="fa fa-caret-down float-right"></span>
                            </a>
                            <div class="collapse d-sm-block show" id="affiliation-category1">
                                <label class="form-category__label mb-0 d-none d-sm-block">{{ __('affiliation.company_information') }}</label>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <strong>{{ __('affiliation.credit_limit') }}</strong>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">

                                        @if(isset($affiliationInfoCredit))
                                            <input type="text" readonly class="form-control-plaintext p-0"
                                                   value="{{ $affiliationInfoCredit }}{{ __('affiliation.yen') }}({{ __('affiliation.credit_limit_amount_outstanding') }}： {{ $affiliationInfoCreditRemaining }}{{ __('affiliation.yen') }})">
                                        @else
                                            <input type="text" readonly class="form-control-plaintext p-0" value="{{ __('affiliation.not_set') }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <strong>{{ __('affiliation.with_the_vibration_vibration_mouth_seat') }}</strong>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        @if(!empty($affiliationInfo['virtual_account']))
                                            <input type="text" readonly class="form-control-plaintext p-0" value="{{ __('affiliation.bank_name') }}： 三菱UFJ銀行">
                                            <input type="text" readonly class="form-control-plaintext p-0" value="{{ __('affiliation.branch_name') }}： 名古屋中央支店">
                                            <input type="text" readonly class="form-control-plaintext p-0" value="{{ __('affiliation.deposit_type') }}： 普通預金">
                                            <input type="text" readonly class="form-control-plaintext p-0" value="{{ __('affiliation.account_number') }}： {{$affiliationInfo['virtual_account']}}">
                                        @endif

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.to_whom_it_may_concern')  }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-6 col-sm-4 pr-1 pr-sm-3 pl-sm-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.representative') }} {{ __('affiliation.sei') }}
                                                </label>
                                                <div class="d-sm-inline-flex flex-column">
                                                    <input type="text" name="data[m_corps][responsibility_sei]" id="responsibility_sei" class="form-control form-control-sm" data-rule-required="true" maxlength="10" value="{{ $mCorpsResponsibilitySei }}">
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4 pl-1 pl-sm-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.mei') }}
                                                </label>
                                                <div class="d-sm-inline-flex flex-column">
                                                    <input type="text" name="data[m_corps][responsibility_mei]" id="responsibility_mei" class="form-control form-control-sm" data-rule-required="true" maxlength="10" value="{{ $mCorpsResponsibilityMei }}">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.person_in_charge') }}
                                                </label>
                                                <div class="d-sm-inline-flex flex-column">
                                                    <input type="text" name="data[m_corps][corp_person]" id="corp_person" class="form-control form-control-sm" data-rule-required="true" maxlength="20" value="{{ $mCorpCorpPerson }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-primary m-0">
                                                {{ __('affiliation.register_correctly_please') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.the_office_location_books_are_paid_first') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col pl-3">
                                                <label class="d-inline-flex col-form-label mr-2">
                                                    〒
                                                </label>
                                                <div class="d-inline-flex flex-column mb-2">
                                                    <input type="text" name="data[m_corps][postcode]" id="postcode" class="form-control form-control-sm" data-rule-minlength="0" data-rule-maxlength="7" data-rule-number="true" maxlength="7" value="{{ $mCorpsPostcode }}">
                                                </div>
                                                <div class="d-inline-flex">
                                                    <button type="button" id="address-search" class="btn btn--gradient-default btn-sm border"><strong>{{ __('affiliation.address_search') }}</strong></button>
                                                </div>
                                                <div class="d-sm-inline-flex">
                                                    <p class="text-muted m-0">
                                                        {{ __('affiliation.please_input_zip_code_without_hyphen') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 col-md-3 pl-3">
                                                <label class="d-block d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.prefectures') }}
                                                </label>
                                                <div class="d-inline-flex flex-column">
                                                    <select name="data[m_corps][address1]" id="address1" class="custom-select custom-select-sm" data-rule-required="true" data-rule-maxlength="10" maxlength="10">
                                                        <option value="">{{ __('affiliation.none_option') }}</option>
                                                        @php
                                                            $notOldValue = true;
                                                        @endphp
                                                        @foreach ($prefectureDivList as $prefectureKey => $prefectureValue)
                                                            @php
                                                                $select = ''
                                                            @endphp
                                                            @if(isset($oldAddress1) && $oldAddress1 == $prefectureKey)
                                                                @php
                                                                    $select = 'selected';
                                                                    $notOldValue = false;
                                                                @endphp
                                                            @elseif(isset($corpData->m_corps_address1) && $corpData->m_corps_address1 == $prefectureKey && $notOldValue)
                                                                @php
                                                                    $select = 'selected="selected"'
                                                                @endphp
                                                            @endif
                                                            <option {{ $select }} value="{{ $prefectureKey }}">{{ $prefectureValue }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 pl-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.municipality') }}
                                                </label>
                                                <div class="d-sm-inline-flex flex-column">
                                                    <input type="text" name="data[m_corps][address2]" id="address2" class="form-control form-control-sm" data-rule-required="true" data-rule-maxlength="20" maxlength="20" value="{{ $mCorpsAddress2 }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-lg-5 pl-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.later_address') }}
                                                </label>
                                                <div class="d-sm-inline-flex flex-column">
                                                    <input type="text" name="data[m_corps][address3]" id="address3" class="form-control form-control-sm" data-rule-required="true" data-rule-maxlength="100" maxlength="100" value="{{ $mCorpsAddress3 }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-primary m-0">
                                                {{ __('affiliation.register_correctly_please') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.head_office_representative_residence') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col pl-3">
                                                <label class="d-inline-flex col-form-label mr-2">
                                                    〒
                                                </label>
                                                <div class="d-inline-flex flex-column mb-2">
                                                    <input type="text" name="data[m_corps][representative_postcode]" id="representative_postcode" class="form-control form-control-sm" data-rule-number="true" data-rule-minlength="0" data-rule-maxlength="7" maxlength="7" value="{{ $mCorpsReprePostcode }}">
                                                </div>
                                                <div class="d-inline-flex">
                                                    <button type="button" id="representative-address-search" class="btn btn--gradient-default btn-sm border"><strong>{{ __('affiliation.address_search') }}</strong></button>
                                                </div>
                                                <div class="d-sm-inline-flex">
                                                    <p class="text-muted m-0">
                                                        {{ __('affiliation.please_input_zip_code_without_hyphen') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 col-md-3 pl-3">
                                                <label class="d-block d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.prefectures') }}
                                                </label>
                                                <div class="d-inline-flex flex-column">
                                                    <select name="data[m_corps][representative_address1]" id="representative_address1" data-rule-required="true" class="custom-select custom-select-sm">
                                                        <option value="">{{ __('affiliation.none_option') }}</option>
                                                        @php
                                                            $notOldValue = true;
                                                        @endphp
                                                        @foreach ($prefectureDivList as $prefectureKey => $prefectureValue)
                                                            @php
                                                                $select = '';

                                                            @endphp
                                                            @if(isset($oldRepreAddress1) && $oldRepreAddress1 == $prefectureKey)
                                                                @php
                                                                    $select = 'selected="selected"';
                                                                $notOldValue = false;
                                                                @endphp
                                                            @elseif(isset($corpData->m_corps_representative_address1) && $corpData->m_corps_representative_address1 == $prefectureKey && $notOldValue)
                                                                @php
                                                                    $select = 'selected="selected"'
                                                                @endphp
                                                            @endif
                                                            <option {{  $select }} value="{{ $prefectureKey }}">{{ $prefectureValue }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 pl-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.municipality') }}
                                                </label>
                                                <div class="d-sm-inline-flex flex-column">
                                                    <input type="text" name="data[m_corps][representative_address2]" id="representative_address2" class="form-control form-control-sm" data-rule-required="true" data-rule-maxlength="20" maxlength="20" value="{{ $mCorpsRepreAddress2 }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-lg-5 pl-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.later_address') }}
                                                </label>
                                                <div class="d-sm-inline-flex flex-column">
                                                    <input type="text" name="data[m_corps][representative_address3]" id="representative_address3" class="form-control form-control-sm" data-rule-required="true" data-rule-maxlength="100" maxlength="100" value="{{ $mCorpsRepreAddress3 }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-primary m-0">
                                                {{ __('affiliation.register_correctly_please') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.phone_number') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-sm-4 col-md-3 pl-3">
                                                <input type="text" name="data[m_corps][tel1]" class="form-control form-control-sm mb-2" data-rule-required="true" data-rule-number="true" data-rule-minlength="0" data-rule-maxlength="11" maxlength="11" value="{{ $mCorpsTel1 }}">
                                            </div>
                                            <div class="col-sm-8 col-md-9 pl-3">
                                                <p class="text-muted m-0">
                                                    {{ __('affiliation.please_enter_the_phone_number_without_hyphen') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.pc_mail') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-sm-4 col-md-3 pl-3">
                                                <input type="text" name="data[m_corps][mailaddress_pc]" class="form-control form-control-sm mb-2 multiple-email-validation" data-rule-required="true" maxlength="255" size="255" value="{{ $mCorpsMailAddressPc }}">
                                            </div>
                                            <div class="col-sm-8 col-md-9 pl-3">
                                                <p class="text-muted m-0">
                                                    {!! trans('affiliation.mail_info_message') !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @if ( Auth::user()->auth != 'affiliation' || $corpData->m_corps_corp_commission_type != 2 )  <!-- Only for referral bases except for correspondence Hidden -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">

                                            <strong>{{ __('affiliation.mobile_mail') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-sm-4 col-md-3 col-xl-2 pl-3 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    @php
                                                        $mobileMailNoneCheck = '';
                                                        $mobileTelTypeDisable = '';
                                                        $mailaddressMobileDisable = '';
                                                        $mobileTelTypeHiddenDisable = 'disabled="disabled"';
                                                        $mailaddressMobileHiddenDisable = 'disabled="disabled"';
                                                        if (!empty($oldMobileMailNone) || (!empty($corpData->m_corps_mobile_mail_none))) {
                                                            $mobileMailNoneCheck = 'checked="checked"';
                                                            $mobileTelTypeDisable = 'disabled="disabled"';
                                                            $mobileTelTypeHiddenDisable = '';
                                                            $mailaddressMobileHiddenDisable = '';
                                                            $mailaddressMobileDisable = 'disabled="disabled"';
                                                        }
                                                    @endphp
                                                    <input id="mobile_mail_none" type="checkbox" value="1" class="custom-control-input" {{ $mobileMailNoneCheck }} name="data[m_corps][mobile_mail_none]">
                                                    <label class="custom-control-label" for="mobile_mail_none">{{ __('affiliation.do_not_have') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 pl-3 mb-2">
                                                <div class="d-sm-inline-flex flex-column">
                                                    <select id="mobile_tel_type" {{ $mobileTelTypeDisable }} name="data[m_corps][mobile_tel_type]" data-rule-required ="true" class="custom-select custom-select-sm">
                                                        <option value="">{{ __('affiliation.none_option') }}</option>
                                                        @foreach ($mobilePhoneTypeList as $mobilePhoneTypeValue)
                                                            @php
                                                                $select = ''
                                                            @endphp
                                                            @if(isset($corpData->m_corps_mobile_tel_type) && $corpData->m_corps_mobile_tel_type == $mobilePhoneTypeValue['id'])
                                                                @php
                                                                    $select = 'selected="selected"'
                                                                @endphp
                                                            @endif
                                                            <option {{  $select }} value="{{ $mobilePhoneTypeValue['id'] }}">{{ $mobilePhoneTypeValue['category_name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 pl-3">
                                                <div class="d-sm-inline-flex flex-column">
                                                    <input id="mailaddress_mobile" {{ $mailaddressMobileDisable }} type="text" data-rule-required ="true" name="data[m_corps][mailaddress_mobile]" class="form-control form-control-sm multiple-email-validation" size="255" maxlength="255" value="{{ $mCorpsMailAddressMobile }}">
                                                </div>
                                            </div>
                                            <input type="hidden" id="mobile_tel_type_hidden" {{ $mobileTelTypeHiddenDisable }} name="data[m_corps][mobile_tel_type]" value="0">
                                            <input type="hidden" id="mailaddress_mobile_hidden" {{ $mailaddressMobileHiddenDisable }} name="data[m_corps][mailaddress_mobile]" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.agency_telephone_number') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-sm-4 col-md-3 pl-3">
                                                <input type="text" name="data[m_corps][commission_dial]" class="form-control form-control-sm mb-2" data-rule-required="true" data-rule-number="true" data-rule-minlength="0" data-rule-maxlength="11" maxlength="11" value="{{ $mCorpsCommissionDial }}">
                                            </div>
                                            <div class="col-sm-8 col-md-9 pl-3">
                                                <p class="text-muted m-1">
                                                    {!!  __('affiliation.telephone_number_info_message') !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <strong>{{ __('affiliation.bidding_ceremony_delivery_destination_address') }}</strong>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-sm-4 col-md-3 pl-3">
                                                <input type="text" name="data[m_corps][mailaddress_auction]" class="form-control form-control-sm mb-2 multiple-email-validation" size="255" maxlength="255" value="{{ $mCorpsMailaddressAuction }}">
                                            </div>
                                            <div class="col-sm-8 col-md-9 pl-3">
                                                <p class="text-muted m-0">
                                                    {!! trans('affiliation.mail_info_message') !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.intermediary_method') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-sm-4 col-md-3 pl-3">
                                                @php
                                                    $coordinationMethodSelected = '';
                                                @endphp
                                                <select id="coordination_method" name="data[m_corps][coordination_method]" class="custom-select custom-select-sm" data-rule-required="true" data-rule-number="true">
                                                    <option value="">{{ __('affiliation.none_option') }}</option>
                                                    @php
                                                        $notOldValue = true;
                                                    @endphp
                                                    @foreach ($customerInfoContactList as $customerInfoContact)
                                                        @php
                                                            $select = '';
                                                        @endphp
                                                        @if(isset($oldCoordinationMethod) && $oldCoordinationMethod == $customerInfoContact['id'])
                                                            @php
                                                                $select = 'selected="selected"';
                                                                $coordinationMethodSelected = $customerInfoContact['category_name'];
                                                                $notOldValue = false;
                                                            @endphp
                                                        @elseif(isset($corpData->m_corps_coordination_method) && $corpData->m_corps_coordination_method == $customerInfoContact['id'] && $notOldValue)
                                                            @php
                                                                $select = 'selected="selected"';
                                                                $coordinationMethodSelected = $customerInfoContact['category_name'];
                                                            @endphp
                                                        @endif
                                                        <option {{  $select }} value="{{ $customerInfoContact['id'] }}">{{ $customerInfoContact['category_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @php
                                                if($corpData->m_corps_coordination_method != 6) {
                                                    $isHiddenCoordinationMethodMessageInfo = '';
                                                } else {
                                                    $isHiddenCoordinationMethodMessageInfo = 'd-none';
                                                }
                                            @endphp
                                            <div class="col-sm-8 col-md-9 pl-3 {{ $isHiddenCoordinationMethodMessageInfo }}" id="coordination_method_message_info">                                                <p class="text--orange-light border border-thick border-note p-2 bg-note m-0">
                                                    {!! trans('affiliation.intermediary_method_message_info') !!}
                                                </p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.fax_number') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            @php
                                                if(isset($coordinationMethodSelected) && stripos( $coordinationMethodSelected, __('common.fax') )) {
                                                    $isRequiredFax = 'true';
                                                } else {
                                                    $isRequiredFax = 'false';
                                                }
                                            @endphp
                                            <div class="col-sm-4 col-md-3 pl-3">
                                                <input id="fax_input" type="text" name="data[m_corps][fax]" class="form-control form-control-sm mb-2" data-rule-required="{{$isRequiredFax}}" data-rule-number="true" data-rule-minlength="0" data-rule-maxlength="11" maxlength="11" value="{{ $mCorpsFax }}">
                                            </div>
                                            <div class="col-sm-8 col-md-9 pl-3">
                                                <p class="text-muted mb-1">
                                                    {!! trans('affiliation.fax_number_message_info') !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.contact_possible_time') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-12 col-md-4 col-lg-4 col-xl-3 pl-3" data-group-required="true">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    @php
                                                        $contactableSupport24HourCheck = '';
                                                        if (!empty($oldContactableSupport24hour) || !empty($corpData->m_corps_contactable_support24hour)) {
                                                            $contactableSupport24HourCheck = 'checked="checked"';
                                                        }
                                                    @endphp
                                                    <input value="1" type="checkbox" {{ $contactableSupport24HourCheck }} class="custom-control-input" id="contactable_support24hour" name="data[m_corps][contactable_support24hour]">
                                                    <label class="custom-control-label" for="contactable_support24hour">
                                                        {{ __('affiliation.24h_compatible') }}
                                                    </label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    @php
                                                        $contactableTimeOtherCheck = '';
                                                        if (empty($oldContactableSupport24hour) && (!empty($oldContactableTimeOther) || !empty($corpData->m_corps_contactable_time_other))) {
                                                            $contactableTimeOtherCheck = 'checked="checked"';
                                                        }
                                                    @endphp
                                                    <input value="1" type="checkbox" class="custom-control-input" {{ $contactableTimeOtherCheck }} id="contactable_time_other" name="data[m_corps][contactable_time_other]">
                                                    <label class="custom-control-label" for="contactable_time_other">
                                                        {{ __('affiliation.other') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6 mt-2 mt-lg-0">
                                                <div class="d-inline-flex flex-column w-45">
                                                    <input type="text" name="data[m_corps][contactable_time_from]" id="contactable_time_from" class="form-control form-control-sm mb-2 timepicker" maxlength="50" value="{{ $corpData->m_corps_contactable_time_from or '' }}">
                                                </div>
                                                <span class="w-10">{{trans('common.wavy_seal')}}</span>
                                                <div class="d-inline-flex flex-column w-45">
                                                    <input type="text" name="data[m_corps][contactable_time_to]" id="contactable_time_to" class="form-control form-control-sm mb-2 timepicker" maxlength="50" value="{{ $corpData->m_corps_contactable_time_to or '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.business_hours') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-12 col-md-4 col-lg-4 col-xl-3 pl-3" data-group-required="true">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    @php
                                                        $support24HourCheck = '';
                                                        if (!empty($oldSupport24hour) || !empty($corpData->m_corps_support24hour)) {
                                                            $support24HourCheck = 'checked="checked"';
                                                        }
                                                    @endphp
                                                    <input value="1" type="checkbox" {{ $support24HourCheck }} class="custom-control-input" id="support24hour" name="data[m_corps][support24hour]">
                                                    <label class="custom-control-label" for="support24hour">
                                                        {{ __('affiliation.24h_compatible') }}
                                                    </label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    @php
                                                        $timeOtherCheck = '';
                                                        if (empty($oldSupport24hour) &&(!empty($oldAvailableTimeOther) || !empty($corpData->m_corps_available_time_other))) {
                                                            $timeOtherCheck = 'checked="checked"';
                                                        }
                                                    @endphp
                                                    <input value="1" type="checkbox" {{ $timeOtherCheck }} class="custom-control-input" id="available_time_other" name="data[m_corps][available_time_other]">
                                                    <label class="custom-control-label" for="available_time_other">
                                                        {{ __('affiliation.other') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6 mt-2 mt-lg-0">
                                                <div class="d-inline-flex flex-column w-45">
                                                    <input name="data[m_corps][available_time_from]" id="available_time_from" name="available_time_from" type="text" class="form-control form-control-sm mb-2 timepicker" maxlength="50" value="{{ $corpData->m_corps_available_time_from or '' }}">
                                                </div>
                                                <span class="w-10">{{trans('common.wavy_seal')}}</span>
                                                <div class="d-inline-flex flex-column w-45">
                                                    <input name="data[m_corps][available_time_to]" id="available_time_to" name="available_time_to" type="text" class="form-control form-control-sm mb-2 timepicker" maxlength="50" value="{{ $corpData->m_corps_available_time_to or '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.business_holiday') }}</strong><span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col pl-3" data-group-required="true">
                                                @foreach($businessHolidayList as $holiday)
                                                    @php
                                                        if($holiday['id'] == 1) {
                                                            $holidayCheckBoxClass = 'category-holiday-no-rest';
                                                        } else {
                                                            $holidayCheckBoxClass = 'category-holiday';
                                                        }
                                                    @endphp
                                                    @if(in_array($holiday['id'], $corpHolidays))
                                                        <div class="custom-control custom-checkbox custom-control-inline">
                                                            <input name="data[holiday][{{ $holiday['id'] }}]" type="checkbox" value="1" checked="checked" class="{{ $holidayCheckBoxClass }} custom-control-input ignore" id="holiday_{{ $holiday['id'] }}">
                                                            <label class="custom-control-label" for="holiday_{{ $holiday['id'] }}">{{ $holiday['category_name'] }}</label>
                                                        </div>
                                                    @else
                                                        <div class="custom-control custom-checkbox custom-control-inline">
                                                            <input name="data[holiday][{{ $holiday['id'] }}]" type="checkbox" value="1" class="{{ $holidayCheckBoxClass }} custom-control-input ignore" id="holiday_{{ $holiday['id'] }}">
                                                            <label class="custom-control-label" for="holiday_{{ $holiday['id'] }}">{{ $holiday['category_name'] }}</label>
                                                        </div>
                                                    @endif


                                                @endforeach
                                            </div>
                                            <div class="col-12 custom-holiday-message invalid-feedback">投稿数を入力してください</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.long_term_holidays_situation') }}</strong>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <input type="hidden" name="data[m_corp_new_years][id]" value="{{ $corpData->m_corp_new_years_id }}">
                                        <div class="row mb-2">
                                            <div class="col-12">
                                                @foreach($vacationList as $vacation)
                                                    <div class="d-inline-flex flex-column mb-2">
                                                        <label class="m-0">{{ $vacation['category_name'] }}</label>
                                                        <input type="hidden" name="data[m_corp_new_years][label_{{ sprintf('%02d', $vacation['id']) }}]" value="{{ $vacation['category_name'] or '' }}">
                                                        @php
                                                            $selected = '';
                                                            if($vacation['category_name'] == $corpData->{'m_corp_new_years_label_'.sprintf('%02d', $vacation['id'])}){
                                                                $selected = $corpData->{'m_corp_new_years_status_'.sprintf('%02d', $vacation['id'])};
                                                            }
                                                        @endphp
                                                        <select name="data[m_corp_new_years][status_{{ sprintf('%02d', $vacation['id']) }}]" class="custom-select custom-select-sm">
                                                            <option value=""></option>
                                                            @foreach($newYearStatusOptions as $key => $newYearStatusOption)
                                                                @if(!empty($selected) && $selected == $key)
                                                                    <option selected="selected" value="{{ $key }}">{{ $newYearStatusOption }}</option>
                                                                @else
                                                                    <option value="{{ $key }}">{{ $newYearStatusOption }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{ __('affiliation.refund_account') }}</strong>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-sm-5 col-md-4 pl-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.bank_name') }}
                                                </label>
                                                <div class="d-sm-inline-flex">
                                                    <input type="text" name="data[m_corps][refund_bank_name]" class="form-control form-control-sm" maxlength="50" data-rule-maxlength="50" value="{{ $mCorpsBankName }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-5 col-md-4 pl-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.branch_name') }}
                                                </label>
                                                <div class="d-sm-inline-flex">
                                                    <input type="text" name="data[m_corps][refund_branch_name]" class="form-control form-control-sm" maxlength="50" data-rule-maxlength="50" value="{{ $mCorpsBranchName }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-5 col-md-4 pl-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.deposit_type') }}
                                                </label>
                                                <div class="d-sm-inline-flex">
                                                    <input type="text" name="data[m_corps][refund_account_type]" class="form-control form-control-sm" maxlength="50" data-rule-maxlength="50" value="{{ $mCorpsAccountType }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-5 col-md-4 pl-3">
                                                <label class="d-sm-inline-flex col-form-label mr-2">
                                                    {{ __('affiliation.account_number') }}
                                                </label>
                                                <div class="d-sm-inline-flex flex-column">
                                                    <input type="text" name="data[m_corps][refund_account]" class="form-control form-control-sm" maxlength="14" data-rule-maxlength="14" data-rule-number="true" value="{{ $mCorpsRefundAccount }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <span class="m-0">
                                            <strong>{{__('affiliation.supported_language')}}</strong>
                                        </span>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <div class="row mb-2">
                                            <div class="col-12 col-md-4 col-lg-3">
                                                @php
                                                    $englishSupportChecked = '';
                                                    $chineseSupportChecked = '';
                                                @endphp
                                                @if(!empty($oldSupportLanguageEn) || !empty($corpData->m_corps_support_language_en))
                                                    @php
                                                        $englishSupportChecked = 'checked = "checked"'
                                                    @endphp
                                                @endif
                                                @if(!empty($oldSupportLanguageZh) || !empty($corpData->m_corps_support_language_zh))
                                                    @php
                                                        $chineseSupportChecked = 'checked = "checked"'
                                                    @endphp
                                                @endif
                                                <div class="custom-control custom-checkbox custom-control-inline">

                                                    <input value="1" name="data[m_corps][support_language_en]" type="checkbox" {{ $englishSupportChecked }} class="custom-control-input" id="MCorp.support_language_en">
                                                    <label class="custom-control-label" for="MCorp.support_language_en">
                                                        {{__('affiliation.english')}}
                                                    </label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input value="1" name="data[m_corps][support_language_zh]" type="checkbox" {{ $chineseSupportChecked }} class="custom-control-input" id="MCorp.support_language_zh">
                                                    <label class="custom-control-label" for="MCorp.support_language_zh">
                                                        {{__('affiliation.chinese')}}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col mt-2 mt-md-0">
                                                <div class="d-inline-flex">
                                                    <label>{{ __('affiliation.available_employees') }}</label>
                                                </div>
                                                <div class="d-inline-flex flex-column">
                                                    <input name="data[m_corps][support_language_employees]" type="text" class="form-control form-control-sm" data-rule-number="true" maxlength="50" data-rule-maxlength="50" value="{{ $mCorpsLanguageSupport }}">
                                                </div>
                                                <span>{{ __('affiliation.man') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-lg-3 col-form-label form__label--white-light form__label--border p-sm-3">
                                        <strong>{{ __('affiliation.last_updated') }}</strong>
                                    </label>
                                    <div class="col-sm-10 col-lg-9 py-sm-3">
                                        <input name="data[m_corps][modified]" type="text" readonly class="form-control-plaintext p-0" value="{{ $corpData->m_corps_modified or '' }}">
                                    </div>
                                </div>
                                <div class="row mt-4 border-0">
                                    <div class="col-12 col-sm-6 offset-sm-3 col-lg-4 offset-lg-4">
                                        <button type="submit" class="btn btn-lg btn--gradient-green w-100 text-white submit-update-corp">
                                            <strong>{{ __('common.register') }}</strong>
                                        </button>
                                    </div>
                                </div>

                            </div>
                            @if ( Auth::user()->auth != 'affiliation' || $corpData->m_corps_corp_commission_type != 2 )  <!-- Only for referral bases except for correspondence Hidden -->
                            <a class="collapse-trigger d-block d-sm-none p-3 mt-4" data-toggle="collapse" href="#affiliation-category2" role="button" aria-expanded="false" aria-controls="collapseExample">
                                {{ __('affiliation.seikatsu110_name') }}
                                <span class="fa fa-caret-down float-right"></span>
                            </a>
                            <div class="collapse d-sm-block mt-sm-5" id="affiliation-category2">
                                <div class="row mb-4">
                                    <div class="col-12 mb-3 mt-3 mt-sm-0">
                                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSdpazwzisQNbjyZ7HfuZ32d3Zi-Ow3ddqaTxLjLDfYJ2JVGZg/viewform" target="_blank" class="btn btn-lg btn--gradient-orange text-white px-5">
                                            <strong>{{ __('affiliation.seikatsu110_register') }}</strong>&nbsp;<span class="fa fa-clone"></span>
                                        </a>
                                    </div>
                                    <div class="col-12 mb-3">
                                        {!!  __('affiliation.seikatsu110_info')  !!}
                                    </div>
                                    <div class="col-12">
                                        <p class="d-block d-sm-inline-flex m-sm-0">
                                            <a href="http://www.seikatsu110.jp/mypage/login.html" target="_blank" class="btn btn--gradient-default border px-4">
                                                <strong>{{ __('affiliation.seikatsu110_management') }}</strong>&nbsp;<span class="fa fa-clone"></span>
                                            </a>
                                        </p>
                                        <p class="d-block d-sm-inline-flex m-sm-0">
                                            <a href="http://www.seikatsu110.jp/" target="_blank" class="btn btn--gradient-default border px-4">
                                                <strong>{{ __('affiliation.seikatsu110_homepage') }}</strong>&nbsp;<span class="fa fa-clone"></span>
                                            </a>
                                        </p>

                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>

                                </div>
                            </div>
                            @endif
                        </form>
                    </div>

                    <a class="collapse-trigger d-block d-sm-none p-3 mt-4" data-toggle="collapse" href="#affiliation-category3" role="button" aria-expanded="false" aria-controls="collapseExample">
                        {{ __('affiliation.genre_registration_title') }}
                        <span class="fa fa-caret-down float-right"></span>
                    </a>
                    <div class="collapse d-sm-block mt-sm-5" id="affiliation-category3">
                        <div class="row mb-sm-5">
                            <div class="col-12">
                                <label class="form-category__label mb-1 d-none d-sm-block">{{ __('affiliation.genre_registration') }}</label>
                                <label class="form__label--border d-block d-sm-none mt-3">
                                    <strong>{{ __('affiliation.genre_registration') }}</strong>
                                </label>
                            </div>
                            <div class="col-8 col-sm-12 col-md-4 col-lg-3 pr-md-0">
                                @php
                                    if (Auth::user()->auth != 'affiliation') {
                                        $genreRegistrationChangeUrl = route('affiliation.resign.index', ['id' => $id]);
                                    } else {
                                        $genreRegistrationChangeUrl = action('Agreement\AgreementSystemController@getStep0');
                                    }
                                @endphp
                                <a href="{{ $genreRegistrationChangeUrl }}" target="_blank" class="btn btn-lg btn--gradient-orange text-white w-100">
                                    <strong>{{ __('affiliation.genre_registration_change') }}</strong>
                                </a>
                            </div>
                            <div class="col-12 col-sm-12 col-md-8 col-lg-9 pl-md-0">
                                <div class="border border-thick border-note bg-note mt-1 mt-sm-0 box-mess ml-md-2">
                                    <p class="m-0 text--orange-light">{{ __('affiliation.genre_registration_change_info') }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-category__label mb-1 d-none d-sm-block mt-3">{{ __('affiliation.corresponding_area') }}</label>
                                <label class="form__label--border d-block d-sm-none mt-3">
                                    <strong>{{ __('affiliation.corresponding_area') }}</strong>
                                </label>
                            </div>
                            <div class="col-8 col-sm-12 col-md-3 pr-md-0">
                                <a href="{{ route('affiliation.corptargetarea', $id) }}" target="_blank" class="btn btn-lg btn--gradient-orange text-white w-100">
                                    <strong>{{ __('affiliation.register_basic_basic_area') }}</strong>
                                </a>
                            </div>
                            <div class="col-12 col-sm-12 col-md-9 pl-md-0">
                                <div class="border border-thick border-note bg-note mt-1 mt-sm-0 box-mess ml-md-2">
                                    <p class="m-0 text--orange-light">
                                        {{ __('affiliation.register_basic_basic_area_info') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-12">
                                <p class="mt-3 p-3 bg-light">

                                </p>
                            </div>
                        </div>
                    </div>

                    <a class="collapse-trigger d-block d-sm-none p-3 mt-4" data-toggle="collapse" href="#affiliation-category4" role="button" aria-expanded="false" aria-controls="collapseExample">
                        {{ __('affiliation.setting_contents_list') }}
                        <span class="fa fa-caret-down float-right"></span>
                    </a>
                    <div class="collapse d-sm-block mt-sm-5" id="affiliation-category4">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-category__label mb-1 d-none d-sm-block">{{ __('affiliation.setting_contents_list') }}</label>
                                <label class="sub-label mt-3">
                                    <strong>{{ __('affiliation.basic_compatible_area_list') }}</strong>
                                </label>
                            </div>
                            <div class="col-12">
                                @if(count($prefList) >= 1)
                                    <div class="select_area_contents mt-md-3">
                                        <div class="area_line_group d-block d-md-table">
                                            @foreach($prefList as $pref)
                                                <div class="pref_block d-block d-md-table-cell">
                                                    <div class="pref_sub d-table">
                                                        <div class="pref_sub1 d-table-cell" id="taiou_{{ $pref['id'] }}">
                                                            @if($pref['rank'] == 2)
                                                                {!! trans('affiliation.all_regions_available') !!}
                                                            @elseif($pref['rank'] == 1)
                                                                {!! trans('affiliation.partial_area_support_possible') !!}
                                                            @else
                                                                {!! trans('affiliation.correspondence_impossible') !!}
                                                            @endif
                                                        </div>
                                                        <div class="pref_sub2 d-table-cell">
                                                            {{ $pref['name'] }}
                                                        </div>
                                                        <div class="pref_sub3 d-table-cell">
                                                            <a href="{{ route('affiliation.corptargetarea', ['id' => $id, 'initPref' => $pref['name']]) }}" target="_blank">{{ __('affiliation.set') }}<span class="d-none d-md-inline">≫</span></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($loop->iteration % 3 == 0)
                                        </div>
                                        <div class="area_line_group d-block d-md-table">
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="sub-label mt-3">
                                <strong>{{ __('affiliation.applicable_genre_category_list') }}</strong>
                            </label>
                            <p>
                                {{ __('affiliation.applicable_genre_category_list_info') }}
                            </p>
                        </div>
                        {{-- Category A --}}
                        <div class="col-12">
                            <label class="sub-label">
                                <strong>{{ __('affiliation.conclusion_base_business_category_a') }}</strong>
                            </label>
                            <p>
                                {{ __('affiliation.genre_category_reflecting_basic_correspondence_area') }}
                            </p>
                            @component('affiliation.component.genre_table', [
                                'genreAreaList' =>  $genreNormalAreaListA,
                                'category' => 'A',
                                'userAuth' => Auth::user()->auth,
                                'mCorpsCorpCommissionType' => $corpData->m_corps_corp_commission_type,
                                'auctionDeliveryStatusList' => $auctionDeliveryStatusList,
                                'corpId' => $id
                            ])

                            @endcomponent
                        </div>

                        <div class="col-12">
                            <p class="m-0">
                                {{ __('affiliation.correspondence_areas_individually_changed_genre_category') }}
                            </p>
                            @component('affiliation.component.genre_table', [
                                'genreAreaList' =>  $genreCustomAreaListA,
                                'category' => 'A',
                                'userAuth' => Auth::user()->auth,
                                'mCorpsCorpCommissionType' => $corpData->m_corps_corp_commission_type,
                                'auctionDeliveryStatusList' => $auctionDeliveryStatusList,
                                'corpId' => $id
                            ])

                            @endcomponent
                        </div>


                        {{-- Category B --}}
                        <div class="col-12">
                            <label class="sub-label">
                                <strong>{{ __('affiliation.conclusion_base_business_category_b') }}</strong>
                            </label>
                            <p>
                                {{ __('affiliation.genre_category_reflecting_basic_correspondence_area') }}
                            </p>
                            @component('affiliation.component.genre_table', [
                                'genreAreaList' =>  $genreNormalAreaListB,
                                'category' => 'B',
                                'userAuth' => Auth::user()->auth,
                                'mCorpsCorpCommissionType' => $corpData->m_corps_corp_commission_type,
                                'auctionDeliveryStatusList' => $auctionDeliveryStatusList,
                                'corpId' => $id
                            ])

                            @endcomponent
                        </div>
                        <div class="col-12">
                            <p class="m-0">
                                {{ __('affiliation.correspondence_areas_individually_changed_genre_category') }}
                            </p>
                            @component('affiliation.component.genre_table', [
                                'genreAreaList' =>  $genreCustomAreaListB,
                                'category' => 'B',
                                'userAuth' => Auth::user()->auth,
                                'mCorpsCorpCommissionType' => $corpData->m_corps_corp_commission_type,
                                'auctionDeliveryStatusList' => $auctionDeliveryStatusList,
                                'corpId' => $id
                            ])

                            @endcomponent
                        </div>
                        @if (!empty($lastItemGenre))
                            <div class="col-12">
                                <p class="m-0">
                                    {{ __('affiliation.last_updated') }}: <span class="text-primary">{{ $lastItemGenre['modified'] }}</span>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div id="page-data"
             data-get-address-by-postcode-url="{{ action('Ajax\AjaxController@searchAddressByZip') }}" >
        </div>
        <hr class="text-dark">
    </section>
@endsection

@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/helpers/address.js') }}"></script>
    <script src="{{ mix('js/pages/affiliation.category.js') }}"></script>
@endsection
