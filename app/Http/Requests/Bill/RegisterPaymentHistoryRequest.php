<?php

namespace App\Http\Requests\Bill;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPaymentHistoryRequest extends FormRequest
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
        return [
            'payment_date' => 'required|date_format:Y/m/d',
            'nominee' => 'required',
            'payment_amount' => 'required|numeric',
            'corp_id' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "payment_date.required" => __('money_correspond.required'),
            "payment_date.date_format" => __('money_correspond.invalid_date'),
            "nominee.required" => __('money_correspond.required'),
            "payment_amount.required" => __('money_correspond.required'),
            "payment_amount.numeric" => __('money_correspond.invalid_numeric'),
        ];
    }
}
