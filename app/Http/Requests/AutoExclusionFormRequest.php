<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class AutoExclusionFormRequest extends FormRequest
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
        for ($i = 0; $i <= 10; $i++) {
            $keyExFrom = 'exclusion_time_from' . $i;
            $keyExTo = 'exclusion_time_to' . $i;
            if (Input::get($keyExFrom) != null) {
                $rules[$keyExTo] = 'required';
            }
            if (Input::get($keyExTo) != null) {
                $rules[$keyExFrom] = 'required';
            }
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'exclusion_time_from*.required' => 'exclusion.msg_error_required_from',
            'exclusion_time_to*.required' => 'exclusion.msg_error_required_to',
        ];
    }
}
