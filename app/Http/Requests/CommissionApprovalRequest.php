<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommissionApprovalRequest extends FormRequest
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
            "approval_id" => "required",
            "action_name" => ["required", Rule::in(["approval", "rejected"])],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "approval_id.required" => trans('commission.approval_id_required'),
            "action_name.required" => trans('commission.action_name_required'),
        ];
    }
}
