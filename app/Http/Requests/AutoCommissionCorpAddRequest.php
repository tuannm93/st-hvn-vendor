<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoCommissionCorpAddRequest extends FormRequest
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
            'corp_id' => 'required',
            'category_id' => 'required',
            'genre_id' => 'required',
            'jis_cd' => 'required',
            'process_type' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'corp_id.required' => trans('auto_commission_corp.required_corp_id'),
            'category_id.required' => trans('auto_commission_corp.required_category_id'),
            'genre_id.required' => trans('auto_commission_corp.required_genre_id'),
            'jis_cd.required' => trans('auto_commission_corp.required_jicd'),
            'process_type.required' => trans('auto_commission_corp.required_process_type'),
        ];
    }
}
