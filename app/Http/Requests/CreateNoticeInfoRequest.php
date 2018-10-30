<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNoticeInfoRequest extends FormRequest
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
            'info_title'    => 'required|max:100',
            'info_contents' => 'required|max:20000',
            'choices'       => 'sometimes|max:200'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'info_title.required'    => __('notice_info_update.info_title_required'),
            'info_title.max'         => __('notice_info_update.info_title_max_100'),
            'info_contents.required' => __('notice_info_update.info_contents_required'),
            'info_contents.max'      => __('notice_info_update.info_contents_max_20000'),
            'choices.sometimes'      => __('notice_info_update.choices_required'),
            'choices.max'            => __('notice_info_update.choices_max_100'),
        ];
    }
}
