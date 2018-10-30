<?php

namespace App\Http\Requests\Demand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Log;

class DemandInfoDataRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $contactDesiredTimeCheck = '';
        $flg = true;

        if (!Input::get('quick_order_fail')
            && empty(Input::get('demandInfo.contact_desired_time'))
            && empty(Input::get('demandInfo.contact_desired_time_from'))
            && empty(Input::get('demandInfo.contact_estimated_time_from'))
        ) {
            foreach (Input::get('visitTime') as $val) {
                if (!empty($val['visit_time']) || (!empty($val['visit_time_from']))) {
                    $flg = false;
                }
            }
            if ($flg) {
                $contactDesiredTimeCheck = 'required|max:100';
            }
        }
        $crossSellSourceSiteCheck = '';

        if (in_array(Input::get('demandInfo.site_id'), [861, 863, 889, 890, 1312, 1313, 1314]) && Input::get('demandInfo.cross_sell_source_site') == null) {
            $crossSellSourceSiteCheck = 'required|numeric';
        }

        $crossSellSourceGenreCheck = '';

        if (in_array(Input::get('demandInfo.site_id'), [861, 863, 889, 890, 1312, 1313, 1314]) && Input::get('demandInfo.cross_sell_source_genre') == null) {
            $crossSellSourceGenreCheck = 'required|numeric';
        }

        $sourceDemandIdCheck = '';

        if (in_array(Input::get('demandInfo.site_id'), [861, 863, 889, 890, 1312, 1313, 1314]) && Input::get('demandInfo.source_demand_id') == null) {
            $sourceDemandIdCheck = 'required|numeric';
        }
        $cContent = '';
        foreach ($this->get('visitTime') as $time) {
            if (isset($this->get('demandInfo')['id'])
                && (!empty($time['visit_time'])) || !empty($time['visit_time_from']) || !empty($time['visit_time_to'])) {
                $cContent .='|required';
            }
        }
        $rules = [
            'demandInfo.demand_status' => Input::get('quick_order_fail') ? '' : 'required|numeric',
            'demandInfo.order_fail_reason' => Input::get('demandInfo.order_fail_reason') ? 'numeric' : '',
            'demandInfo.mail_demand' => Input::get('demandInfo.mail_demand') ? 'numeric' : '',
            'demandInfo.nighttime_takeover' => Input::get('demandInfo.nighttime_takeover') ? 'numeric' : '',
            'demandInfo.low_accuracy' => Input::get('demandInfo.low_accuracy') ? 'numeric' : '',
            'demandInfo.remand' => Input::get('demandInfo.remand') ? 'numeric' : '',
            'demandInfo.corp_change' => Input::get('demandInfo.corp_change') ? 'numeric' : '',
            'demandInfo.sms_reorder' => Input::get('demandInfo.sms_reorder') ? 'numeric' : '',
            'demandInfo.site_id' => 'required|numeric',
            'demandInfo.genre_id' => 'required|numeric',
            'demandInfo.category_id' => 'required|numeric',
            'demandInfo.cross_sell_source_site' => $crossSellSourceSiteCheck,
            'demandInfo.cross_sell_source_genre' => $crossSellSourceGenreCheck,
            'demandInfo.source_demand_id' => $sourceDemandIdCheck,
            'demandInfo.cross_sell_source_category' => Input::get('demandInfo.cross_sell_source_category') ? 'numeric' : '',
            'demandInfo.postcode' => Input::get('demandInfo.postcode') ? 'numeric|digits_between:0,7' : '',
            'demandInfo.tel1' => Input::get('quick_order_fail') ? '' : 'required|numeric|digits_between:0,11',
            'demandInfo.tel2' => Input::get('demandInfo.tel2') ? 'numeric|digits_between:0,11' : '',
            'demandInfo.jbr_estimate_status' => Input::get('demandInfo.jbr_estimate_status') ? 'numeric' : '',
            'demandInfo.jbr_receipt_status' => Input::get('demandInfo.jbr_receipt_status') ? 'numeric' : '',
            'demandInfo.business_trip_amount' => Input::get('demandInfo.business_trip_amount') ? 'numeric' : '',
            'demandInfo.cost_from' => Input::get('demandInfo.cost_from') ? 'numeric' : '',
            'demandInfo.cost_to' => Input::get('demandInfo.cost_to') ? 'numeric' : '',
            'demandInfo.jbr_receipt_price' => Input::get('demandInfo.jbr_receipt_price') ? 'numeric' : '',
            'demandInfo.receive_datetime' => 'required|date',
            'demandInfo.customer_name' => Input::get('quick_order_fail') ? 'max:50' : 'required|max:50',
            'demandInfo.customer_tel' => Input::get('quick_order_fail') ? 'max:11' : 'required|max:11',
            'demandInfo.address1' => Input::get('quick_order_fail') ? 'max:10' : 'required|max:10',
            'demandInfo.address2' => Input::get('quick_order_fail') ? 'max:20' : 'required|max:20',
            'demandInfo.address3' => Input::get('demandInfo.address3') ? 'max:100' : '',
            'demandInfo.address4' => Input::get('demandInfo.address4') ? 'max:100' : '',
            'demandInfo.building' => Input::get('demandInfo.building') ? 'max:100' : '',
            'demandInfo.room' => Input::get('demandInfo.room') ? 'max:20' : '',
            'demandInfo.selection_system' => Input::get('quick_order_fail') ? '' : 'required',
            'demandInfo.construction_class' => Input::get('quick_order_fail') ? '' : 'required',
            'demandInfo.complete_date' => Input::get('demandInfo.complete_date') ? 'date' : '',
            'demandInfo.order_fail_date' => Input::get('demandInfo.order_fail_date') ? 'date' : '',
            'demandInfo.receptionist' => Input::get('demandInfo.receptionist') ? 'max:20' : '',
            'demandInfo.customer_corp_name' => Input::get('demandInfo.customer_corp_name') ? 'max:50' : '',
            'demandInfo.customer_mailaddress' => Input::get('demandInfo.customer_mailaddress') ? 'email|max:255' : '',
            'demandInfo.contents' => Input::get('demandInfo.contents') ? 'max:1000' : '',
            'demandInfo.contact_desired_time' => $contactDesiredTimeCheck,
            'demandInfo.contact_desired_time_from' => Input::get('demandInfo.contact_desired_time_from') ? 'max:100' : '',
            'demandInfo.contact_desired_time_to' => Input::get('demandInfo.contact_desired_time_to') ? 'max:100' : '',
            'demandInfo.contact_estimated_time_from' => Input::get('demandInfo.contact_estimated_time_to') ? 'max:100' : '',
            'demandInfo.contact_estimated_time_to' => Input::get('demandInfo.contact_estimated_time_to') ? 'max:100' : '',
            'demandInfo.mail' => Input::get('demandInfo.mail') ? 'max:1000' : '',
            'demandInfo.share_notice' => Input::get('demandInfo.share_notice') ? 'max:1000' : '',
            'demandInfo.acceptance_status' => Input::get('quick_order_fail') ? '' : 'required',
            'demandCorrespond.corresponding_contens' => $cContent,
            'visitTime.*.visit_time' =>'sometimes|nullable|date',
            'visitTime.*.visit_time_from' =>'sometimes|nullable|date',
            'visitTime.*.visit_time_to' =>'sometimes|nullable|date',
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [
            'required' => __('demand.validation_error.not_empty'),
            'max'     => __('demand.validation_error.max_error'),
            'digits_between' => __('demand.validation_error.max_error'),
            'numeric' => __('demand.validation_error.numeric_error'),
            'email'   => __('demand.validation_error.email_error'),
            'date'    => __('demand.validation_error.date_error'),
        ];

        return $messages;
    }
    // protected function failedValidation(Validator $validator)
    // {
    //     dd($validator->errors());
    // }
}
