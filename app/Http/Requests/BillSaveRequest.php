<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
        $rule['target'] = 'required';
        return $rule;
    }

    /**
     * get the message apply to the validation rules
     *
     * @return array
     */
    public function messages()
    {
        return [
            'target.required' => trans('bill_list.target_required'),
        ];
    }
}
