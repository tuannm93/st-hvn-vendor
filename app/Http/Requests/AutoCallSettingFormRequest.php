<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoCallSettingFormRequest extends FormRequest
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
            'asap'          => 'nullable|numeric|min:0|max:60',
            'immediately'   => 'nullable|numeric|min:0|max:60',
            'normal'        => 'nullable|numeric|min:0|max:60'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'asap.numeric'          => 'autocall.msg_error_numeric',
            'asap.min'              => 'autocall.msg_error_range',
            'asap.max'              => 'autocall.msg_error_range',
            'immediately.numeric'   => 'autocall.msg_error_numeric',
            'immediately.min'       => 'autocall.msg_error_range',
            'immediately.max'       => 'autocall.msg_error_range',
            'normal.numeric'        => 'autocall.msg_error_numeric',
            'normal.min'            => 'autocall.msg_error_range',
            'normal.max'            => 'autocall.msg_error_range'
        ];
    }
}
