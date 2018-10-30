<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddAgreementRequest extends FormRequest
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
            'corp_id' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'corp_id.required' => trans('add_agreement.required_corp_id')
        ];
    }
}
