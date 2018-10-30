@extends('layouts.app')
@php
    // Get some old check value
    $dataSupport24hour = (old('data.m_corps.support24hour') !== null) ? old('data.m_corps.support24hour') : $mCorp->support24hour;
    $dataAvailableTimeOther = (old('data.m_corps.available_time_other') !== null) ? old('data.m_corps.available_time_other') : $mCorp->available_time_other;
    $dataContactSupport24hour = (old('data.m_corps.contactable_support24hour') !== null) ? old('data.m_corps.contactable_support24hour') : $mCorp->contactable_support24hour;
    $dataContactTimeOther = (old('data.m_corps.contactable_time_other') !== null) ? old('data.m_corps.contactable_time_other') : $mCorp->contactable_time_other;
    $dataSupportLanguageEn = (old('data.m_corps.support_language_en') !== null) ? old('data.m_corps.support_language_en') : $mCorp->support_language_en;
    $dataSupportLanguageZh = (old('data.m_corps.support_language_zh') !== null) ? old('data.m_corps.support_language_zh') : $mCorp->support_language_zh;

    $support24hourChecked = ($dataSupport24hour == 1) ? 'checked' : '';
    $disableAvailableTime = ($dataSupport24hour == 1) ? 'disabled' : '';
    $disableContactTime = ($dataContactSupport24hour == 1) ? 'disabled' : '';
    $availableTimeOtherChecked = ($dataAvailableTimeOther == 1) ? 'checked' : '';
    $contactSupport24hourChecked = ($dataContactSupport24hour == 1) ? 'checked' : '';
    $contactTimeOtherChecked = ($dataContactTimeOther == 1) ? 'checked' : '';
    $supportLanguageEnChecked = ($dataSupportLanguageEn == 1) ? 'checked' : '';
    $supportLanguageZhChecked = ($dataSupportLanguageZh == 1) ? 'checked' : '';
    if ($corpAgreement) {
       $checkCorpAgreement = ($corpAgreement->accept_check == 1) ? 'checked' : '';
    } else {
        $checkCorpAgreement = '';
    }

    $followDate = (old('data.m_corps.follow_date') !== null) ? old('data.m_corps.follow_date') : $mCorp->follow_date;
    $followPerson = (old('data.m_corps.follow_person') !== null) ? old('data.m_corps.follow_person') : $mCorp->follow_person;
    $ritsPerson = (old('data.m_corps.rits_person') !== null) ? old('data.m_corps.rits_person') : $mCorp->rits_person;
    $documentSendRequestDate = (old('data.m_corps.document_send_request_date') !== null) ? old('data.m_corps.document_send_request_date') : $mCorp->document_send_request_date;
    $corpCommissionStatus = (old('data.m_corps.corp_commission_status') !== null) ? old('data.m_corps.corp_commission_status') : $mCorp->corp_commission_status;
    $corpStatus = (old('data.m_corps.corp_status') !== null) ? old('data.m_corps.corp_status') : $mCorp->corp_status;
    $commissionNgDate = (old('data.m_corps.commission_ng_date') !== null) ? old('data.m_corps.commission_ng_date') : $mCorp->commission_ng_date;
    $orderFailReason = (old('data.m_corps.order_fail_reason') !== null) ? old('data.m_corps.order_fail_reason') : $mCorp->order_fail_reason;
    $affiliationStatusValue = (old('data.m_corps.affiliation_status') !== null) ? old('data.m_corps.affiliation_status') : $mCorp->affiliation_status;
    $contractDate = (old('data.m_corps.contract_date') !== null) ? old('data.m_corps.contract_date') : $mCorp->contract_date;
    $corpName = (old('data.m_corps.corp_name') !== null) ? old('data.m_corps.corp_name') : $mCorp->corp_name;
    $corpNameKana = (old('data.m_corps.corp_name_kana') !== null) ? old('data.m_corps.corp_name_kana') : $mCorp->corp_name_kana;
    $officialCorpName = (old('data.m_corps.official_corp_name') !== null) ? old('data.m_corps.official_corp_name') : $mCorp->official_corp_name;
    $responsibilitySei = (old('data.m_corps.responsibility_sei') !== null) ? old('data.m_corps.responsibility_sei') : $mCorp->responsibility_sei;
    $responsibilityMei = (old('data.m_corps.responsibility_mei') !== null) ? old('data.m_corps.responsibility_mei') : $mCorp->responsibility_mei;
    $corpPerson = (old('data.m_corps.corp_person') !== null) ? old('data.m_corps.corp_person') : $mCorp->corp_person;
    $postcode = (old('data.m_corps.postcode') !== null) ? old('data.m_corps.postcode') : $mCorp->postcode;
    $address1 = (old('data.m_corps.address1') !== null) ? old('data.m_corps.address1') : $mCorp->address1;
    $address2 = (old('data.m_corps.address2') !== null) ? old('data.m_corps.address2') : $mCorp->address2;
    $address3 = (old('data.m_corps.address3') !== null) ? old('data.m_corps.address3') : $mCorp->address3;
    $representativePostcode = (old('data.m_corps.representative_postcode') !== null) ? old('data.m_corps.representative_postcode') : $mCorp->representative_postcode;
    $representativeAddress1 = (old('data.m_corps.representative_address1') !== null) ? old('data.m_corps.representative_address1') : $mCorp->representative_address1;
    $representativeAddress2 = (old('data.m_corps.representative_address2') !== null) ? old('data.m_corps.representative_address2') : $mCorp->representative_address2;
    $representativeAddress3 = (old('data.m_corps.representative_address3') !== null) ? old('data.m_corps.representative_address3') : $mCorp->representative_address3;
    $tradeName1 = (old('data.m_corps.trade_name1') !== null) ? old('data.m_corps.trade_name1') : $mCorp->trade_name1;
    $tradeName2 = (old('data.m_corps.trade_name2') !== null) ? old('data.m_corps.trade_name2') : $mCorp->trade_name2;
    $commissionDial = (old('data.m_corps.commission_dial') !== null) ? old('data.m_corps.commission_dial') : $mCorp->commission_dial;
    $tel1 = (old('data.m_corps.tel1') !== null) ? old('data.m_corps.tel1') : $mCorp->tel1;
    $tel2 = (old('data.m_corps.tel2') !== null) ? old('data.m_corps.tel2') : $mCorp->tel2;
    $mobileTel = (old('data.m_corps.mobile_tel') !== null) ? old('data.m_corps.mobile_tel') : $mCorp->mobile_tel;
    $fax = (old('data.m_corps.fax') !== null) ? old('data.m_corps.fax') : $mCorp->fax;
    $mailaddressPc = (old('data.m_corps.mailaddress_pc') !== null) ? old('data.m_corps.mailaddress_pc') : $mCorp->mailaddress_pc;
    $mailaddressMobile = (old('data.m_corps.mailaddress_mobile') !== null) ? old('data.m_corps.mailaddress_mobile') : $mCorp->mailaddress_mobile;
    $url = (old('data.m_corps.url') !== null) ? old('data.m_corps.url') : $mCorp->url;
    $progressCheckTel = (old('data.m_corps.progress_check_tel') !== null) ? old('data.m_corps.progress_check_tel') : $mCorp->progress_check_tel;
    $progressCheckPerson = (old('data.m_corps.progress_check_person') !== null) ? old('data.m_corps.progress_check_person') : $mCorp->progress_check_person;
    $targetRange = (old('data.m_corps.target_range') !== null) ? old('data.m_corps.target_range') : $mCorp->target_range;
    $availableTimeFrom = (old('data.m_corps.available_time_from') !== null) ? old('data.m_corps.available_time_from') : $mCorp->available_time_from;
    $availableTimeTo = (old('data.m_corps.available_time_to') !== null) ? old('data.m_corps.available_time_to') : $mCorp->available_time_to;
    $contactableTimeFrom = (old('data.m_corps.contactable_time_from') !== null) ? old('data.m_corps.contactable_time_from') : $mCorp->contactable_time_from;
    $contactableTimeTo = (old('data.m_corps.contactable_time_to') !== null) ? old('data.m_corps.contactable_time_to') : $mCorp->contactable_time_to;
    $newYearNote = (old('data.m_corp_new_years.new_year_note') !== null) ? old('data.m_corp_new_years.new_year_note') : $mCorp->new_year_note;
    $freeEstimateValue = (old('data.m_corps.free_estimate') !== null) ? old('data.m_corps.free_estimate') : $mCorp->free_estimate;
    $portalSiteValue = (old('data.m_corps.portalsite') !== null) ? old('data.m_corps.portalsite') : $mCorp->portalsite;
    $regSendDate = (old('data.m_corps.reg_send_date') !== null) ? old('data.m_corps.reg_send_date') : $mCorp->reg_send_date;
    $regSendMethodValue = (old('data.m_corps.reg_send_method') !== null) ? old('data.m_corps.reg_send_method') : $mCorp->reg_send_method;
    $regCollectDate = (old('data.m_corps.reg_collect_date') !== null) ? old('data.m_corps.reg_collect_date') : $mCorp->reg_collect_date;
    $psAppSendDate = (old('data.m_corps.ps_app_send_date') !== null) ? old('data.m_corps.ps_app_send_date') : $mCorp->ps_app_send_date;
    $psAppCollectDate = (old('data.m_corps.ps_app_collect_date') !== null) ? old('data.m_corps.ps_app_collect_date') : $mCorp->ps_app_collect_date;
    $coordinationMethodValue = (old('data.m_corps.coordination_method') !== null) ? old('data.m_corps.coordination_method') : $mCorp->coordination_method;
    $listedMedia = (old('data.m_corps.listed_media') !== null) ? old('data.m_corps.listed_media') : $mCorp->listed_media;
    $corpCommissionTypeValue = (old('data.m_corps.corp_commission_type') !== null) ? old('data.m_corps.corp_commission_type') : $mCorp->corp_commission_type;
    $jbrAvailableStatusValue = (old('data.m_corps.jbr_available_status') !== null) ? old('data.m_corps.jbr_available_status') : $mCorp->jbr_available_status;
    $mailAddressAuction = (old('data.m_corps.mailaddress_auction') !== null) ? old('data.m_corps.mailaddress_auction') : $mCorp->mailaddress_auction;
    $auctionStatusValue = (old('data.m_corps.auction_status') !== null) ? old('data.m_corps.auction_status') : $mCorp->auction_status;
    $auctionMaskingValue = (old('data.m_corps.auction_masking') !== null) ? old('data.m_corps.auction_masking') : $mCorp->auction_masking;
    $refundBankName = (old('data.m_corps.refund_bank_name') !== null) ? old('data.m_corps.refund_bank_name') : $mCorp->refund_bank_name;
    $refundBranchName = (old('data.m_corps.refund_branch_name') !== null) ? old('data.m_corps.refund_branch_name') : $mCorp->refund_branch_name;
    $refundAccountType = (old('data.m_corps.refund_account_type') !== null) ? old('data.m_corps.refund_account_type') : $mCorp->refund_account_type;
    $refundAccount = (old('data.m_corps.refund_account') !== null) ? old('data.m_corps.refund_account') : $mCorp->refund_account;
    $supportLanguageEmployees = (old('data.m_corps.support_language_employees') !== null) ? old('data.m_corps.support_language_employees') : $mCorp->support_language_employees;
    $autoCallFlagValue = (old('data.m_corps.auto_call_flag') !== null) ? old('data.m_corps.auto_call_flag') : $mCorp->auto_call_flag;
    $corpKind = (old('data.m_corps.corp_kind') !== null) ? old('data.m_corps.corp_kind') : $mCorp->corp_kind;
    $capitalStock = (old('data.affiliation_infos.capital_stock') !== null) ? old('data.affiliation_infos.capital_stock') : $mCorp->capital_stock;
    $employees = (old('data.affiliation_infos.employees') !== null) ? old('data.affiliation_infos.employees') : $mCorp->employees;
    $listedKind = (old('data.affiliation_infos.listed_kind') !== null) ? old('data.affiliation_infos.listed_kind') : $mCorp->listed_kind;
    $defaultTax = (old('data.affiliation_infos.default_tax') !== null) ? old('data.affiliation_infos.default_tax') : $mCorp->default_tax;
    $maxCommission = (old('data.affiliation_infos.max_commission') !== null) ? old('data.affiliation_infos.max_commission') : $mCorp->max_commission;
    $collectionMethodValue = (old('data.affiliation_infos.collection_method') !== null) ? old('data.affiliation_infos.collection_method') : $mCorp->collection_method;
    $collectionMethodOthers = (old('data.affiliation_infos.collection_method_others') !== null) ? old('data.affiliation_infos.collection_method_others') : $mCorp->collection_method_others;
    $creditLimit = (old('data.affiliation_infos.credit_limit') !== null) ? old('data.affiliation_infos.credit_limit') : $mCorp->credit_limit;
    $addMonthCredit = (old('data.affiliation_infos.add_month_credit') !== null) ? old('data.affiliation_infos.add_month_credit') : $mCorp->add_month_credit;
    $allowCreditMailSend = (old('data.affiliation_infos.allow_credit_mail_send') !== null) ? old('data.affiliation_infos.allow_credit_mail_send') : $mCorp->allow_credit_mail_send;
    $virtualAccount = (old('data.affiliation_infos.virtual_account') !== null) ? old('data.affiliation_infos.virtual_account') : $mCorp->virtual_account;
    $liabilityInsuranceValue = (old('data.affiliation_infos.liability_insurance') !== null) ? old('data.affiliation_infos.liability_insurance') : $mCorp->liability_insurance;
    $regFollowDate1 = (old('data.affiliation_infos.reg_follow_date1') !== null) ? old('data.affiliation_infos.reg_follow_date1') : $mCorp->reg_follow_date1;
    $regFollowDate2 = (old('data.affiliation_infos.reg_follow_date2') !== null) ? old('data.affiliation_infos.reg_follow_date2') : $mCorp->reg_follow_date2;
    $regFollowDate3 = (old('data.affiliation_infos.reg_follow_date3') !== null) ? old('data.affiliation_infos.reg_follow_date3') : $mCorp->reg_follow_date3;
    $wasteCollectOathValue = (old('data.affiliation_infos.waste_collect_oath') !== null) ? old('data.affiliation_infos.waste_collect_oath') : $mCorp->waste_collect_oath;
    $transferName = (old('data.affiliation_infos.transfer_name') !== null) ? old('data.affiliation_infos.transfer_name') : $mCorp->transfer_name;
    $claimCountValue = (old('data.affiliation_infos.claim_count') !== null) ? old('data.affiliation_infos.claim_count') : $mCorp->claim_count;
    $claimHistory = (old('data.affiliation_infos.claim_history') !== null) ? old('data.affiliation_infos.claim_history') : $mCorp->claim_history;
    $progSendMethodValue = (old('data.m_corps.prog_send_method') !== null) ? old('data.m_corps.prog_send_method') : $mCorp->prog_send_method;
    $progSendFax = (old('data.m_corps.prog_send_fax') !== null) ? old('data.m_corps.prog_send_fax') : $mCorp->prog_send_fax;
    $progSendMailAddress = (old('data.m_corps.prog_send_mail_address') !== null) ? old('data.m_corps.prog_send_mail_address') : $mCorp->prog_send_mail_address;
    $progIrregular = (old('data.m_corps.prog_irregular') !== null) ? old('data.m_corps.prog_irregular') : $mCorp->prog_irregular;
    $specialAgreementCheck = (old('data.m_corps.special_agreement_check') !== null) ? old('data.m_corps.special_agreement_check') : $mCorp->special_agreement_check;
    $billSendMethodValue = (old('data.m_corps.bill_send_method') !== null) ? old('data.m_corps.bill_send_method') : $mCorp->bill_send_method;
    $billSendAddress = (old('data.m_corps.bill_send_address') !== null) ? old('data.m_corps.bill_send_address') : $mCorp->bill_send_address;
    $billIrregular = (old('data.m_corps.bill_irregular') !== null) ? old('data.m_corps.bill_irregular') : $mCorp->bill_irregular;
    $specialAgreement = (old('data.m_corps.special_agreement') !== null) ? old('data.m_corps.special_agreement') : $mCorp->special_agreement;
    $orderFailDate = (old('data.m_corps.order_fail_date') !== null) ? old('data.m_corps.order_fail_date') : $mCorp->order_fail_date;
    $geocodeLat = (old('data.m_corps.geocode_lat') !== null) ? old('data.m_corps.geocode_lat') : $mCorp->geocode_lat;
    $geocodeLong = (old('data.m_corps.geocode_long') !== null) ? old('data.m_corps.geocode_long') : $mCorp->geocode_long;
    $note = (old('data.m_corps.note') !== null) ? old('data.m_corps.note') : $mCorp->note;
    $advertisingStatusValue = (old('data.m_corps.advertising_status') !== null) ? old('data.m_corps.advertising_status') : $mCorp->advertising_status;
    $advertisingSendDate = (old('data.m_corps.advertising_send_date') !== null) ? old('data.m_corps.advertising_send_date') : $mCorp->advertising_send_date;
    $paymentSiteValue = (old('data.m_corps.payment_site') !== null) ? old('data.m_corps.payment_site') : $mCorp->payment_site;
    $regInfo = (old('data.affiliation_infos.reg_info') !== null) ? old('data.affiliation_infos.reg_info') : $mCorp->reg_info;
    $attention = (old('data.affiliation_infos.attention') !== null) ? old('data.affiliation_infos.attention') : $mCorp->attention;
    $lastAntisocialCheck = (old('data.m_corps.last_antisocial_check') !== null) ? old('data.m_corps.last_antisocial_check') : $mCorp->last_antisocial_check;
    $antisocialCheckMonth = (old('data.m_corps.antisocial_check_month') !== null) ? old('data.m_corps.antisocial_check_month') : $mCorp->antisocial_check_month;
@endphp
@section('content')
    <section class="app-content container affiliation my-4">

        {{-- Button link view --}}
        @include('affiliation.components.detail.button_link')

        {{-- Error placement--}}
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(session($msg))
                <p class="alert alert-{{ $msg }}">
                    {{ session($msg) }}
                </p>
            @endif
        @endforeach
        @if ($errors->any())
            <p class="alert alert-danger">@lang('affiliation.input_invalid')</p>
        @endif

        <form method="post" id="formAffiliation" action="{{ route('affiliation.detail.update', $mCorp->id) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" value="{{ $mCorp->modified }}" name="data[m_corps][modified]">
            <input type="hidden" value="{{ $mCorp->affiliation_info_id }}" name="data[affiliation_infos][affiliation_id]">

            {{-- Mcorp view --}}
            @include('affiliation.components.detail.m_corps')

            {{-- Affiliation correspond view --}}
            @include('affiliation.components.detail.affiliation_correspond')

            {{-- Affiliation info view --}}
            @include('affiliation.components.detail.affiliation_infos')
        </form>
    </section>
    <div id="page-data" data-url-search-address="{{ route('ajax.searchAddressByZip') }}"></div>
    @include('affiliation.components.detail.history_modal')
    @include('affiliation.components.detail.target_area_modal')
    @include('affiliation.components.detail.corp_target_area_select')
@endsection

@section('script')
    <script>
        var msg = "@lang('affiliation_detail.delete_affiliation_mess')";
        var confirm = "@lang('support.confirm')";
        var cancel = "@lang('support.cancel')";
    </script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/affiliation.detail.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/pages/corp_target_area_select.js') }}"></script>
    <script>
        $(document).ready(function () {
            FormUtil.validate('#formAffiliation');
            AffiliationDetail.init();
            Datetime.initForDatepicker();
            Datetime.initForDateTimepicker();
            Datetime.initForTimepicker();
        });
    </script>
@endsection
