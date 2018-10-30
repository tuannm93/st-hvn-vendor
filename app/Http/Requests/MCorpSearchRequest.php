<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class MCorpSearchRequest extends FormRequest
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
        $rules = [];
        if (!empty(Input::get('corp_id'))) {
            $rules['corp_id'] = 'numeric';
        }
        if (!empty(Input::get('bill_id'))) {
            $rules['bill_id'] = 'numeric';
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'corp_id.numeric' => trans('mcorp_list.numeric'),
            'bill_id.numeric' => trans('mcorp_list.numeric'),
        ];
    }
}
