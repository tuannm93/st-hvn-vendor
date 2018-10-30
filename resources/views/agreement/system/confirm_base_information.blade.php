<h2>@lang('agreement_system.base_information')</h2>

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
        <div class="p-3 border-bottom h-100">
            @if($mCorp->corp_kind == \App\Models\MCorp::CORP)
                @lang('agreement_system.corp_kind_corp')
            @elseif($mCorp->corp_kind == \App\Models\MCorp::PERSON)
                @lang('agreement_system.corp_kind_person')
            @endif
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
        <div class="p-3 border-bottom h-100">
            @if($affiliationInfo['listed_kind'] == \App\Models\MCorp::LISTED)
                @lang('agreement_system.listed')
            @elseif($affiliationInfo['listed_kind'] == \App\Models\MCorp::UNLISTED)
                @lang('agreement_system.unlisted')
            @endif
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
        <div class="p-3 border-bottom h-100">
            @if($affiliationInfo['default_tax'] == false)
                @lang('agreement_system.no_delinquency')
            @elseif($affiliationInfo['default_tax'] == true)
                @lang('agreement_system.delinquent')
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-lg-3 pr-lg-0">
        <div class="form__label--white-light p-3 h-100 border-bottom">
            <label class="m-0">
                <strong>@lang('agreement_system.capital_stock')</strong>
            </label>
            <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
        </div>
    </div>
    <div class="col-12 col-lg-9 pl-lg-0">
        <div class="p-3 border-bottom h-100">
            @if($affiliationInfo['capital_stock'])
                {{$affiliationInfo['capital_stock']}} @lang('agreement_system.yen')
            @endif
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
        <div class="p-3 border-bottom h-100">
                {{$affiliationInfo['employees']}} @lang('agreement_system.man')
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
        <div class="p-3 border-bottom h-100">
            <p class="m-0">
                @lang('agreement_system.representative'): {{$data['responsibilitySei']}} {{$data['responsibilityMei']}}
            </p>
            <p class="m-0">
                @lang('agreement_system.person_in_charge'): {{$mCorp->corp_person}}
            </p>
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
        <div class="p-3 border-bottom h-100">
            @lang('agreement_system.symbol_postal_code') {{$mCorp->postcode}}
            <br>
            {{$data['prefectureDiv'][$mCorp->address1]}} {{$mCorp->address2}} {{$mCorp->address3}}
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
        <div class="p-3 border-bottom h-100">
            @lang('agreement_system.symbol_postal_code') {{$mCorp->representative_postcode}}
            <br>
            {{$data['prefectureDiv'][$mCorp->representative_address1]}} {{$mCorp->representative_address2}} {{$mCorp->representative_address3}}
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
        <div class="p-3 border-bottom h-100">
            {{$mCorp->tel1}}
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
        <div class="p-3 border-bottom h-100">
            {{$mCorp->mailaddress_pc}}
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
        <div class="p-3 border-bottom h-100">
            @if($mCorp->mobile_mail_none == 1)
                @lang('agreement_system.do_not_have')
            @else
                @if("1" == $mCorp->mobile_tel_type)
                    @lang('agreement_system.smart_phone') {{$mCorp->mailaddress_mobile}}
                @elseif("2" == $mCorp->mobile_tel_type)
                    @lang('agreement_system.ordinary_mobile') {{$mCorp->mailaddress_mobile}}
                @endif()
            @endif
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
        <div class="p-3 border-bottom h-100">
            {{$mCorp->commission_dial}}
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
        <div class="p-3 border-bottom h-100">
            {{$data['coordinationMethodList'][$mCorp->coordination_method]}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-lg-3 pr-lg-0">
        <div class="form__label--white-light p-3 h-100 border-bottom">
            <label class="m-0">
                <strong>@lang('agreement_system.fax_number')</strong>
            </label>
            <span class="badge badge-warning float-lg-right">{{ __('common.have_to') }}</span>
        </div>
    </div>
    <div class="col-12 col-lg-9 pl-lg-0">
        <div class="p-3 border-bottom h-100">
            {{$mCorp->fax}}
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
        <div class="p-3 border-bottom h-100">
            @if($mCorp->support24hour == 1)
                @lang('agreement_system.24h_correspondence')
            @else
                @lang('agreement_system.other') {{$mCorp->available_time_from }}
                {{trans('common.wavy_seal')}}{{$mCorp->available_time_to}}
            @endif
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
        <div class="p-3 border-bottom h-100">
            @if($mCorp->contactable_support24hour == 1)
                @lang('agreement_system.24h_correspondence')
            @else
                @lang('agreement_system.other') {{$mCorp->contactable_time_from }}
                {{trans('common.wavy_seal')}}{{$mCorp->contactable_time_to}}
            @endif
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
        <div class="p-3 border-bottom h-100">
            @if(in_array(1, $corpHolidays))
                @lang('agreement_system.no_rest')
            @elseif(in_array(9, $corpHolidays))
                @lang('agreement_system.public_holiday')
            @else
                [
                @if(in_array(2, $corpHolidays))
                    @lang('agreement_system.mon')
                @endif
                @if(in_array(3, $corpHolidays))
                    @lang('agreement_system.tue')
                @endif
                @if(in_array(4, $corpHolidays))
                    @lang('agreement_system.wed')
                @endif
                @if(in_array(5, $corpHolidays))
                    @lang('agreement_system.thu')
                @endif
                @if(in_array(6, $corpHolidays))
                    @lang('agreement_system.fri')
                @endif
                @if(in_array(7, $corpHolidays))
                    @lang('agreement_system.sat')
                @endif
                @if(in_array(8, $corpHolidays))
                    @lang('agreement_system.sun')
                @endif
                ]
            @endif
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
        <div class="p-3 border-bottom h-100">
            {{$mCorp->refund_bank_name}} {{$mCorp->refund_branch_name}}
            <br>
            {{$mCorp->refund_account_type}} {{$mCorp->refund_account}}
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
        <div class="p-3 border-bottom h-100">
            @if($mCorp->support_language_en == 1)
                @lang('agreement_system.english')
            @endif
            @if($mCorp->support_language_zh == 1)
                @lang('agreement_system.chinese')
            @endif
            @if($mCorp->support_language_en == 1 || $mCorp->support_language_zh == 1)
                <br>
            @endif
            @lang('agreement_system.available_employees') {{$mCorp->support_language_employees}}
        </div>
    </div>
</div>

