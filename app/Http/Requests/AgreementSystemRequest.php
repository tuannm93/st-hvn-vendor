<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Factory;
use Illuminate\Support\Facades\Input;

class AgreementSystemRequest extends FormRequest
{
    /**
     * AgreementSystemRequest constructor.
     *
     * @param Factory $validationFactory
     */
    public function __construct(Factory $validationFactory)
    {
        $validationFactory->extend(
            'corp',
            function ($value) {
                return 'corp' === $value;
            },
            '法人, 資本金'
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['mCorp.corp_kind'] = 'required';
        $rules['affiliationInfo.listed_kind'] = 'required';
        $rules['affiliationInfo.default_tax'] = 'required';
        if (!checkIsNullOrEmptyStr(Input::get('affiliationInfo.capital_stock'))) {
            $rules['affiliationInfo.capital_stock'] = 'numeric';
        }

        $rules['affiliationInfo.employees'] = 'required|numeric';
        $rules['mCorp.responsibility_sei'] = 'required';
        $rules['mCorp.responsibility_mei'] = 'required';
        $rules['mCorp.corp_person'] = 'required';

        $rules = $this->ruleForAddress($rules);

        $rules['mCorp.tel1'] = 'required|numeric';
        $rules['mCorp.mailaddress_pc'] = 'required|affiliation_add_validate_mail_format';
        if (!Input::has('mCorp.mobile_mail_none')) {
            $rules['mCorp.mobile_tel_type'] = 'required';
            $rules['mCorp.mailaddress_mobile'] = 'required|affiliation_add_validate_mail_format';
        }
        $rules['mCorp.commission_dial'] = 'required|numeric';
        $rules['mCorp.coordination_method'] = 'required';

        $rules = $this->ruleForCoordinationMethod($rules);

        $rules = $this->ruleForAvailableTime($rules);

        $rules = $this->ruleForHoliday($rules);

        $rules['mCorp.refund_bank_name'] = 'required';
        $rules['mCorp.refund_branch_name'] = 'required';
        $rules['mCorp.refund_account_type'] = 'required';
        $rules['mCorp.refund_account'] = 'required';
        if (!checkIsNullOrEmptyStr(Input::get('mCorp.support_language_employees'))) {
            $rules['mCorp.support_language_employees'] = 'numeric';
        }
        return $rules;
    }

    /**
     * Rule for address
     *
     * @param $rules
     * @return mixed
     */
    private function ruleForAddress($rules)
    {
        $rules['mCorp.postcode'] = 'required|numeric';
        $rules['mCorp.address1'] = 'required';
        $rules['mCorp.address2'] = 'required';
        $rules['mCorp.address3'] = 'required';
        $rules['mCorp.representative_postcode'] = 'required|numeric';
        $rules['mCorp.representative_address1'] = 'required';
        $rules['mCorp.representative_address2'] = 'required';
        $rules['mCorp.representative_address3'] = 'required';
        return $rules;
    }

    /**
     * @param $rules
     * @return mixed
     */
    private function ruleForCoordinationMethod($rules)
    {
        if (Input::has('mCorp.coordination_method')
            && (Input::get('mCorp.coordination_method') == 1
                || Input::get('mCorp.coordination_method') == 3
                || Input::get('mCorp.coordination_method') == 7)
        ) {
            $rules['mCorp.fax'] = 'required|numeric';
        }
        return $rules;
    }

    /**
     * @param $rules
     * @return mixed
     */
    private function ruleForAvailableTime($rules)
    {
        if (!Input::has('mCorp.support24hour') && !Input::has('mCorp.available_time_other')) {
            $rules['mCorp.support24hour'] = 'required';
        } elseif (Input::has('mCorp.available_time_other')) {
            $rules['mCorp.available_time_from'] = 'required|date_format:H:i';
            if (Input::get('mCorp.available_time_to') != null) {
                $rules['mCorp.available_time_to'] = 'date_format:H:i|after:mCorp.available_time_from';
            }
        }
        if (!Input::has('mCorp.contactable_support24hour') && !Input::has('mCorp.contactable_time_other')) {
            $rules['mCorp.contactable_support24hour'] = 'required';
        } elseif (Input::has('mCorp.contactable_time_other')) {
            $rules['mCorp.contactable_time_from'] = 'required|date_format:H:i';
            if (Input::get('mCorp.contactable_time_to') != null) {
                $rules['mCorp.contactable_time_to'] = 'date_format:H:i|after:mCorp.contactable_time_from';
            }
        }
        return $rules;
    }

    /**
     * @param $rules
     * @return mixed
     */
    private function ruleForHoliday($rules)
    {
        if (!Input::has('holidays')) {
            $rules['holidays.holidayNo'] = 'required';
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'mCorp.corp_kind.required' => trans('agreement_system.required_corp_kind'),
            'affiliationInfo.listed_kind.required' => trans('agreement_system.required_listed_kind'),
            'affiliationInfo.default_tax.required' => trans('agreement_system.required_default_tax'),
            'affiliationInfo.capital_stock.numeric' => trans('agreement_system.numeric_capital_stock'),
            'affiliationInfo.employees.required' => trans('agreement_system.required_employees'),
            'affiliationInfo.employees.numeric' => trans('agreement_system.numeric_employees'),
            'mCorp.responsibility_sei.required' => trans('agreement_system.required_responsibility_sei'),
            'mCorp.responsibility_mei.required' => trans('agreement_system.required_responsibility_mei'),
            'mCorp.corp_person.required' => trans('agreement_system.required_corp_person'),
            'mCorp.postcode.required' => trans('agreement_system.required_postcode'),
            'mCorp.postcode.numeric' => trans('agreement_system.numeric_postcode'),
            'mCorp.address1.required' => trans('agreement_system.required_address1'),
            'mCorp.address2.required' => trans('agreement_system.required_address2'),
            'mCorp.address3.required' => trans('agreement_system.required_address3'),
            'mCorp.representative_postcode.required' => trans('agreement_system.required_representative_postcode'),
            'mCorp.representative_postcode.numeric' => trans('agreement_system.numeric_representative_postcode'),
            'mCorp.representative_address1.required' => trans('agreement_system.required_representative_address1'),
            'mCorp.representative_address2.required' => trans('agreement_system.required_representative_address2'),
            'mCorp.representative_address3.required' => trans('agreement_system.required_representative_address3'),
            'mCorp.tel1.required' => trans('agreement_system.required_tel1'),
            'mCorp.tel1.numeric' => trans('agreement_system.numeric_tel1'),
            'mCorp.mailaddress_pc.required' => trans('agreement_system.required_mailaddress_pc'),
            'mCorp.mailaddress_pc.emails' => trans('agreement_system.emails_mailaddress_pc'),
            'mCorp.mobile_tel_type.required' => trans('agreement_system.required_mobile_tel_type'),
            'mCorp.mailaddress_mobile.required' => trans('agreement_system.required_mailaddress_mobile'),
            'mCorp.mailaddress_mobile.emails' => trans('agreement_system.emails_mailaddress_mobile'),
            'mCorp.commission_dial.required' => trans('agreement_system.required_commission_dial'),
            'mCorp.commission_dial.numeric' => trans('agreement_system.numeric_commission_dial'),
            'mCorp.coordination_method.required' => trans('agreement_system.required_coordination_method'),
            'mCorp.fax.required' => trans('agreement_system.required_fax'),
            'mCorp.fax.numeric' => trans('agreement_system.numeric_fax'),
            'mCorp.support24hour.required' => trans('agreement_system.required_support24hour'),
            'mCorp.available_time_from.required' => trans('agreement_system.required_available_time_from'),
            'mCorp.available_time_from.date_format' => trans('agreement_system.available_time_to_wrong_format'),
            'mCorp.available_time_to.required' => trans('agreement_system.required_available_time_to'),
            'mCorp.available_time_to.date_format' => trans('agreement_system.available_time_to_wrong_format'),
            'mCorp.contactable_support24hour.required' => trans('agreement_system.required_contactable_support24hour'),
            'mCorp.contactable_time_from.required' => trans('agreement_system.required_contactable_time_from'),
            'mCorp.contactable_time_from.date_format' => trans('agreement_system.contactable_time_to_wrong_format'),
            'mCorp.contactable_time_to.required' => trans('agreement_system.required_contactable_time_to'),
            'mCorp.contactable_time_to.date_format' => trans('agreement_system.contactable_time_to_wrong_format'),
            'holidays.holidayNo.required' => trans('agreement_system.required_holidayNo'),
            'mCorp.refund_bank_name.required' => trans('agreement_system.required_refund_bank_name'),
            'mCorp.refund_branch_name.required' => trans('agreement_system.required_refund_branch_name'),
            'mCorp.refund_account_type.required' => trans('agreement_system.required_refund_account_type'),
            'mCorp.refund_account.required' => trans('agreement_system.required_refund_account'),
            'mCorp.support_language_employees.numeric' => trans('agreement_system.numeric_support_language_employees'),
            'mCorp.available_time_to.after' => trans('agreement_system.available_time_to_greater_available_time_to'),
            'mCorp.contactable_time_to.after' => trans('agreement_system.contactable_time_to_greater_available_time_to')
        ];
    }
}
