<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class AffiliationAddRequest extends FormRequest
{
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
        $mailPc = Input::get('data.m_corps.mailaddress_pc');
        $mailMobile = Input::get('data.m_corps.mailaddress_mobile');
        $coordinationMethod = Input::get('data.m_corps.coordination_method');
        $mCorpValidate = [
            'data.m_corps.document_send_request_date' => 'max:10',
            'data.m_corps.commission_ng_date' => 'max:10',
            'data.m_corps.affiliation_status' => 'required',
            'data.m_corps.contract_date' => 'max:10',
            'data.m_corps.corp_name' => 'required|max:100',
            'data.m_corps.corp_name_kana' => 'required|max:200',
            'data.m_corps.official_corp_name' => 'required|max:200',
            'data.m_corps.responsibility_sei' => 'required',
            'data.m_corps.corp_person' => 'required|max:20',
            'data.m_corps.postcode' => 'regex:/^-?[0-9]\d*(\.\d+)?$/|between:0,7|nullable',
            'data.m_corps.address1' => 'required|max:10',
            'data.m_corps.address2' => 'required|max:20',
            'data.m_corps.address3' => 'max:100',
            'data.m_corps.address4' => 'max:100',
            'data.m_corps.representative_postcode' => 'regex:/^-?[0-9]\d*(\.\d+)?$/|between:0,7|nullable',
            'data.m_corps.representative_address1' => 'max:10',
            'data.m_corps.representative_address2' => 'max:20',
            'data.m_corps.representative_address3' => 'max:100',
            'data.m_corps.tel1' => 'required|numeric|digits_between:0,12',
            'data.m_corps.mailaddress_pc' => 'affiliation_add_validate_mail_required:' . $mailMobile . ','  . $coordinationMethod . '|affiliation_add_validate_mail_format|max:255',
            'data.m_corps.mailaddress_mobile' => 'affiliation_add_validate_mail_required:' . $mailPc . ','  . $coordinationMethod . '|affiliation_add_validate_mail_format|max:255',
            'data.m_corps.commission_dial' => 'required|numeric|digits_between:0,11',
            'data.m_corps.mailaddress_auction' => 'sometimes|nullable|affiliation_add_validate_mail_format|max:255',
            'data.m_corps.coordination_method' => 'required|numeric',
            'data.m_corps.fax' => 'numeric|digits_between:0,12|nullable',
            'data.m_corps.trade_name1' => 'max:100',
            'data.m_corps.trade_name2' => 'max:100',
            'data.m_corps.tel2' => 'numeric|digits_between:0,100|nullable',
            'data.m_corps.mobile_tel' => 'numeric|digits_between:0,11|nullable',
            'data.m_corps.url' => 'max:2048|nullable',
            'data.m_corps.target_range' => 'max:20',
            'data.m_corps.contactable_time' => 'max:50',
            'data.m_corps.reg_send_date' => 'max:10',
            'data.m_corps.reg_collect_date' => 'max:10',
            'data.m_corps.ps_app_send_date' => 'max:10',
            'data.m_corps.ps_app_collect_date' => 'max:10',
            'data.m_corps.prog_send_address' => 'max:500',
            'data.m_corps.prog_irregular' => 'max:1000',
            'data.m_corps.bill_send_address' => 'max:500',
            'data.m_corps.order_fail_date' => 'max:10',
            'data.m_corps.geocode_lat' => 'max:12',
            'data.m_corps.geocode_long' => 'max:13',
            'data.m_corps.note' => 'max:5000',
            'data.m_corps.advertising_send_date' => 'max:10',
            'data.m_corps.progress_check_tel' => 'numeric|digits_between:0,11|nullable',
            'data.m_corps.progress_check_person' => 'max:200',
            'data.m_corps.listed_media' => 'max:255',
            'data.m_corps.available_time_from' => 'max:10',
            'data.m_corps.available_time_to' => 'max:10',
            'data.m_corps.contactable_time_from' => 'max:10',
            'data.m_corps.contactable_time_to' => 'max:10',
            'data.m_corps.corp_kind' => 'max:20',
            'data.m_corps.refund_bank_name' => 'max:100',
            'data.m_corps.refund_branch_name' => 'max:100',
            'data.m_corps.refund_account_type' => 'max:20',
            'data.m_corps.refund_account' => 'numeric|digits_between:0,14|nullable',
            'data.m_corps.support_language_employees' => 'max:50',
            'data.m_corps.prog_send_mail_address' => 'max:500',
            'data.m_corps.prog_send_fax' => 'max:500',
            'data.m_corps.last_antisocial_check' => 'max:30',

            'data.m_corp_new_years.note' => 'max:5000',

            'data.affiliation_correspond.corresponding_contens' => 'required|max:1000',

            'data.affiliation_infos.credit_limit' => 'required',
            'data.affiliation_infos.add_month_credit' => 'required',
            'data.affiliation_infos.reg_follow_date1' => 'max:10',
            'data.affiliation_infos.reg_follow_date2' => 'max:10',
            'data.affiliation_infos.reg_follow_date3' => 'max:10',
            'data.affiliation_infos.reg_pdf_path' => 'mimes:pdf|max:20480',
            'data.affiliation_infos.virtual_account' => 'max:7',
            'data.affiliation_infos.attention' => 'text_area_max_length:1000',
        ];
        return $mCorpValidate;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $inputRequiredMessage = __('affiliation.input_required');
        $mCorpValidateMsg = [
            'data.m_corps.refund_account.numeric' => __('affiliation.refund_account_numberic'),
            'data.m_corps.support_language_employees.numeric' => __('affiliation.support_language_employees_numberic'),
            'digits_between' => __('affiliation.max_length'),
            'email' => __('affiliation.email_invalid'),
            'data.m_corps.url.url' => __('affiliation.url_invalid'),
            'data.m_corps.mailaddress_pc.affiliation_add_validate_mail_format' => __('affiliation.pc_mail_format'),
            'data.m_corps.mailaddress_mobile.affiliation_add_validate_mail_format' => __('affiliation.mobile_mail_format'),
            'data.m_corps.mailaddress_auction.affiliation_add_validate_mail_format' => __('affiliation.mailaddress_auction'),
            'data.affiliation_infos.reg_pdf_path.mimes' => __('affiliation.pdf_invalid'),
            'data.affiliation_infos.reg_pdf_path.max' => __('affiliation.pdf_invalid_max'),
            'data.m_corps.affiliation_status.required' => $inputRequiredMessage,
            'data.m_corps.corp_name.required' => $inputRequiredMessage,
            'data.m_corps.corp_name_kana.required' => $inputRequiredMessage,
            'data.m_corps.official_corp_name.required' => $inputRequiredMessage,
            'data.m_corps.responsibility_sei.required' => $inputRequiredMessage,
            'data.m_corps.corp_person.required' => $inputRequiredMessage,
            'data.m_corps.address1.required' => $inputRequiredMessage,
            'data.m_corps.address2.required' => $inputRequiredMessage,
            'data.m_corps.tel1.required' => $inputRequiredMessage,
            'data.m_corps.mailaddress_pc.affiliation_add_validate_mail_required' =>
                $inputRequiredMessage,
            'data.m_corps.mailaddress_mobile.affiliation_add_validate_mail_required' =>
                $inputRequiredMessage,
            'data.m_corps.commission_dial.required' => $inputRequiredMessage,
            'data.m_corps.coordination_method.required' => $inputRequiredMessage,
            'data.affiliation_correspond.corresponding_contens.required' => $inputRequiredMessage,
            'data.affiliation_infos.credit_limit.required' => $inputRequiredMessage,
            'data.affiliation_infos.add_month_credit.required' => $inputRequiredMessage,
            'data.affiliation_infos.attention.text_area_max_length' => __('affiliation_detail.attention_max_length'),
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
            'data.m_corps.url' => __('affiliation_detail.url'),
            'data.m_corps.mailaddress_pc' => __('affiliation.pc_mail'),
            'data.m_corps.mailaddress_mobile' => __('affiliation.mobile_mail'),
            'data.m_corps.commission_dial' => __('affiliation.agency_telephone_number'),
            'data.m_corps.mailaddress_auction' => __('affiliation.bidding_ceremony_delivery_destination_address'),
            'data.m_corps.coordination_method' => __('affiliation.intermediary_method'),
            'data.m_corps.fax' => __('affiliation.fax_number'),
            'data.m_corps.progress_check_tel' => __('affiliation_detail.progress_check_tel'),
            'data.m_corps.tel2' => __('affiliation_detail.tel2'),
            'data.m_corps.tel1' => __('affiliation_detail.tel1'),
            'data.m_corps.mobile_tel' => __('affiliation_detail.mobile_tel'),
            'data.m_corps.affiliation_status' => __('affiliation_detail.affiliation_status'),
            'data.m_corps.corp_name' => __('affiliation_detail.corp_name'),
            'data.m_corps.corp_name_kana' => __('affiliation_detail.corp_name_kana'),
            'data.m_corps.official_corp_name' => __('affiliation_detail.official_corp_name'),
            'data.m_corps.refund_account' => __('affiliation_detail.refund_account'),
            'data.affiliation_correspond.corresponding_contens' => __('affiliation_detail.corresponding_contents'),
            'data.affiliation_infos.credit_limit' => __('affiliation_detail.credit_limit'),
            'data.affiliation_infos.add_month_credit' => __('affiliation_detail.add_month_credit'),
            'data.affiliation_infos.reg_follow_date1' => __('affiliation_detail.reg_follow_date'),
            'data.affiliation_infos.reg_follow_date2' => __('affiliation_detail.reg_follow_date'),
            'data.affiliation_infos.reg_follow_date3' => __('affiliation_detail.reg_follow_date'),
            'data.affiliation_infos.reg_pdf_path' => __('affiliation_detail.reg_pdf_path'),
            'data.affiliation_infos.virtual_account' => __('affiliation_detail.virtual_account'),
            'data.affiliation_infos.attention' => __('affiliation_detail.attention'),
            'data.m_corps.progress_check_person' => __('affiliation_detail.progress_check_person'),
        ];
    }
}
