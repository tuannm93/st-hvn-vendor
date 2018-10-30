<?php

namespace App\Http\Requests\Affiliation;

use App\Http\Requests\BaseRequest;

class RegistCorpRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        $mailaddressPCValidation = 'sometimes|nullable|affiliation_add_validate_mail_format|max:255';
        $mailaddressMobileValidation = 'sometimes|nullable|affiliation_add_validate_mail_format|max:255';

        $mCorpValidate = [
            'data.m_corps.responsibility_sei' => 'required|max:10',
            'data.m_corps.responsibility_mei' => 'required|max:10',
            'data.m_corps.corp_person' => 'required|max:20',
            'data.m_corps.postcode' => 'nullable|numeric|digits_between:0,7',
            'data.m_corps.address1' => 'required|max:10',
            'data.m_corps.address2' => 'required|max:20',
            'data.m_corps.address3' => 'required|max:100',
            'data.m_corps.representative_postcode' => 'nullable|numeric|digits_between:0,7',
            'data.m_corps.representative_address1' => 'required|max:10',
            'data.m_corps.representative_address2' => 'required|max:20',
            'data.m_corps.representative_address3' => 'required|max:100',
            'data.m_corps.tel1' => 'required|numeric|digits_between:0,11',
            'data.m_corps.mailaddress_pc' => $mailaddressPCValidation,
            'data.m_corps.mailaddress_mobile' => $mailaddressMobileValidation,
            'data.m_corps.commission_dial' => 'numeric|digits_between:0,11',
            'data.m_corps.mailaddress_auction' => 'sometimes|nullable|affiliation_add_validate_mail_format|max:255',
            'data.m_corps.coordination_method' => 'required|numeric',
            'data.m_corps.fax' => 'nullable|numeric|digits_between:0,11',
            'data.m_corps.refund_bank_name' => 'sometimes|nullable|max:50',
            'data.m_corps.refund_branch_name' => 'sometimes|nullable|max:50',
            'data.m_corps.refund_account_type' => 'sometimes|nullable|max:50',
            'data.m_corps.refund_account' => 'nullable|numeric|digits_between:0,14',
            'data.m_corps.support_language_employees' => 'nullable|numeric',
            'data.m_corps.contactable_time_from' => 'sometimes|nullable|max:50',
            'data.m_corps.contactable_time_to' => 'sometimes|nullable|max:50',
            'data.m_corps.available_time_from' => 'sometimes|nullable|max:50',
            'data.m_corps.available_time_to' => 'sometimes|nullable|max:50',
            'data.holiday' => 'required'
        ];
        return $mCorpValidate;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $mCorpValidateMsg = [
            'data.m_corps.responsibility_sei.required' => __('affiliation.responsibility_sei_required'),
            'data.m_corps.responsibility_mei.required' => __('affiliation.responsibility_mei_required'),
            'data.m_corps.corp_person.required' => __('affiliation.not_empty_corp_person'),
            'data.m_corps.mailaddress_pc.required_if' =>
                __('affiliation.pc_mail') .
                __('affiliation.email_pc_or_email_mobile_empty'),
            'data.m_corps.mailaddress_mobile.required_if' =>
                __('affiliation.mobile_mail') .
                __('affiliation.email_pc_or_email_mobile_empty'),
            'data.m_corps.address1.required' => __('affiliation.not_empty_address1'),
            'data.m_corps.address2.required' => __('affiliation.not_empty_address2'),
            'data.m_corps.address3.required' => __('affiliation.not_empty_address3'),
            'data.m_corps.representative_address1.required' => __('affiliation.not_empty_address1'),
            'data.m_corps.representative_address2.required' => __('affiliation.not_empty_address2'),
            'data.m_corps.representative_address3.required' => __('affiliation.not_empty_address3'),
            'data.m_corps.coordination_method.required' => __('affiliation.not_empty_coordination_method'),
            'data.m_corps.refund_account.numeric' => __('affiliation.refund_account_numberic'),
            'data.m_corps.support_language_employees.numeric' => __('affiliation.support_language_employees_numberic'),
            'data.holiday.required' => __('affiliation.not_empty_holiday'),
            'digits_between' => __('affiliation.max_length'),
            'email' => __('affiliation.email_invalid'),
        ];
        return $mCorpValidateMsg;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'data.m_corps.postcode' =>
                __('affiliation.the_office_location_books_are_paid_first') .
                __('affiliation.post_code'),
            'data.m_corps.address1' =>
                __('affiliation.the_office_location_books_are_paid_first') .
                __('affiliation.prefectures'),
            'data.m_corps.address2' =>
                __('affiliation.the_office_location_books_are_paid_first') .
                __('affiliation.municipality'),
            'data.m_corps.address3' =>
                __('affiliation.the_office_location_books_are_paid_first') .
                __('affiliation.later_address'),
            'data.m_corps.representative_postcode' =>
                __('affiliation.head_office_representative_residence') .
                __('affiliation.post_code'),
            'data.m_corps.representative_address1' =>
                __('affiliation.head_office_representative_residence') .
                __('affiliation.prefectures'),
            'data.m_corps.representative_address2' =>
                __('affiliation.head_office_representative_residence') .
                __('affiliation.municipality'),
            'data.m_corps.representative_address3' =>
                __('affiliation.head_office_representative_residence') .
                __('affiliation.later_address'),
            'data.m_corps.tel1' => __('affiliation.phone_number'),
            'data.m_corps.mailaddress_pc' => __('affiliation.pc_mail'),
            'data.m_corps.mailaddress_mobile' => __('affiliation.mobile_mail'),
            'data.m_corps.commission_dial' => __('affiliation.agency_telephone_number'),
            'data.m_corps.mailaddress_auction' => __('affiliation.bidding_ceremony_delivery_destination_address'),
            'data.m_corps.coordination_method' => __('affiliation.intermediary_method'),
            'data.m_corps.fax' => __('affiliation.fax_number'),
        ];
    }
}
