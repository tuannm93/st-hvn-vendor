<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class AgreementProvisionRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'agreementProvision.provisions.required' => Lang::get('agreement_admin.provisions_required'),
            'agreementProvision.sort_no.required' => Lang::get('agreement_admin.sortNo_required'),
        ];
    }

    /**
     * @param $validator
     */
    public function withValidator($validator)
    {
        $validator->after(
            function ($validator) {
                $sortNo = $this->input('agreementProvision.sort_no');
                if ($this->checkSortNo($sortNo)) {
                    $validator->errors()->add('agreementProvision.sort_no', Lang::get('agreement_admin.sortNo_is_number'));
                }
            }
        );
    }

    /**
     * @param $sortNo
     * @return bool
     */
    private function checkSortNo($sortNo)
    {
        $sortNo = mb_convert_kana($sortNo, "KVa");
        if (!preg_match('/^-?([0-9])+$/', $sortNo)) {
            return true;
        }
    }
}
