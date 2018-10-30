<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;

class AgreementAttachFileRequest extends FormRequest
{

    /**
     * @var string
     */
    protected $redirectRoute = 'agreementSystem.step5.get.fileUpload';
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
        if (Input::has('fileUpload')) {
            $rules['fileUpload'] = 'required|mimes:jpg,jpeg,bmp,png,pdf|max:20480';
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
        if (Input::has('fileUpload')) {
            $messages['fileUpload.required'] = Lang::get('agreement.the_file_field_is_required');
            $messages['fileUpload.mimes'] = Lang::get('agreement_system.format_image_error');
            $messages['fileUpload.max'] = Lang::get('agreement.the_file_may_not_be_greater_than_20_MB');
        }
        return $messages;
    }
}
