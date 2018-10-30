<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DemandListRequest extends FormRequest
{
    /**
     * @return bool
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
        return [
            'data.id' => 'numeric|nullable',
            'data.from_contact_desired_time' => 'date_format:"Y/m/d H:i"|nullable',
            'data.to_contact_desired_time' => 'date_format:"Y/m/d H:i"|nullable',
            'data.from_receive_datetime' => 'date_format:"Y/m/d H:i"|nullable',
            'data.to_receive_datetime' => 'date_format:"Y/m/d H:i"|nullable',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'data.id.numeric' => __('affiliation.refund_account_numberic'),
            'data.from_contact_desired_time.date_format' => __('demandlist.datetime'),
            'data.to_contact_desired_time.date_format' => __('demandlist.datetime'),
            'data.from_receive_datetime.date_format' => __('demandlist.datetime'),
            'data.to_receive_datetime.date_format' => __('demandlist.datetime'),
        ];
    }
}
