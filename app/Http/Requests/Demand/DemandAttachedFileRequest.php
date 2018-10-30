<?php

namespace App\Http\Requests\Demand;

use Illuminate\Foundation\Http\FormRequest;
use Lang;

class DemandAttachedFileRequest extends FormRequest
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
            'demand_attached_file.*' => 'mimes:jpg,jpeg,png,bmp,pdf|max:20480'
        ];
    }

    /**
     * message validate
     *
     * @return array
     */
    public function messages()
    {
        return [
            'mimes' => Lang::get('demand.wrong_format_file'),
            'max' => Lang::get('demand.the_file_may_not_be_greater_than_20_MB')
        ];
    }
}
