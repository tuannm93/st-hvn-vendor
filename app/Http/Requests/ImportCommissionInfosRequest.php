<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Input;

class ImportCommissionInfosRequest extends FormRequest
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
        $rules['commission_info_id'] = 'required';
        $rules['commission_info_lock'] = 'required';
        if (Input::get('commission_info_id')) {
            $commissionInfoIds = str_replace(["\r\n", "\r", "\n", "　", '', ' '], '', Input::get('commission_info_id'));
            $arrayId = explode(',', $commissionInfoIds);
            foreach ($arrayId as $id) {
                if (!ctype_digit($id)) {
                    $rules['commission_info_id'] = 'numeric';
                }
            }
        }
        return $rules;
    }

    /**
     * message validate
     *
     * @return array
     */
    public function messages()
    {
        return [
            'commission_info_id.required' => trans('progress_management.import_commission_corp.required'),
            'commission_info_id.numeric' => trans('progress_management.import_commission_corp.numeric'),
            'commission_info_lock.required' => trans('progress_management.import_commission_corp.lock_required'),

        ];
    }
}
