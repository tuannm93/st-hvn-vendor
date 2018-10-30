<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class AutoCommissionCorrespondsRequest extends FormRequest
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
        $rules = [];
        if (Input::has('responders')) {
            $rules['responders'] = 'required|max:20';
        }
        if (Input::has('rits_responders')) {
            $rules['rits_responders'] = 'required';
        }
        $rules['corresponding_contens'] = 'required|max:1000';
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'rits_responders.required' => 'commission_corresponds.msg_error_required',
            'responders.required' => 'commission_corresponds.msg_error_required',
            'responders.max' => 'commission_corresponds.msg_error_responders_max',
            'corresponding_contens.required' => 'commission_corresponds.msg_error_required',
            'corresponding_contens.max' => 'commission_corresponds.msg_error_corresponding_contens_max',
        ];
    }
}
