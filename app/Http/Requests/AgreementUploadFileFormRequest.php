<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Lang;
use Illuminate\Support\Facades\Input;

class AgreementUploadFileFormRequest extends FormRequest
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
        if (Input::has('upload_file_path')) {
            $rules['upload_file_path.*'] = 'mimes:jpeg,bmp,png,pdf|max:20480';
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
        $messages = [];
        if (Input::has('upload_file_path')) {
            $messages['upload_file_path.*.required'] = Lang::get('agreement.the_file_field_is_required');
            $messages['upload_file_path.*.mimes'] = Lang::get('agreement.the_file_must_be_a_file_of_type_jpeg, bmp, png, pdf');
            $messages['upload_file_path.*.max'] = Lang::get('agreement.the_file_may_not_be_greater_than_20_MB');
        }
        return $messages;
    }
}
