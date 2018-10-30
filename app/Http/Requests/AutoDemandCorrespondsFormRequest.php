<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class AutoDemandCorrespondsFormRequest extends FormRequest
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
        $rules['corresponding_contens'] = 'max:1000';

        if (empty(Input::get('corresponding_contens'))) {
            if (! empty(Input::get('responders'))) {
                $rules['corresponding_contens'] = 'required';
            }
        }
        if (empty(Input::get('responders'))) {
            if (! empty(Input::get('corresponding_contens'))) {
                $rules['responders'] = 'required';
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
            'responders.required' => 'demandcorresponds.msg_error_required_responders',
            'corresponding_contens.required' => 'demandcorresponds.msg_error_required',
            'corresponding_contens.max' => 'demandcorresponds.msg_error_corresponding_contens_max',
        ];
    }
}
