<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportCorpCateAppAdminRequest extends FormRequest
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
            "check" => "array|required",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "check.array" => trans('report_corp_cate_app_admin.check.array'),
            "check.required" => trans('report_corp_cate_app_admin.check.array'),
        ];
    }
}
