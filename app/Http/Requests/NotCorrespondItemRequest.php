<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class NotCorrespondItemRequest extends FormRequest
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
            'small_lower_limit' => 'required|numeric',
            'midium_lower_limit' => 'required|numeric',
            'large_lower_limit' => 'required|numeric',
            'immediate_date' => 'required|numeric',
            'immediate_lower_limit' => 'required|numeric',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'small_lower_limit.required' => trans('not_correspond.validate.required'),
            'small_lower_limit.numeric' => trans('not_correspond.validate.numeric'),
            'small_lower_limit.min' => trans('not_correspond.validate.min'),
            'midium_lower_limit.required' => trans('not_correspond.validate.required'),
            'midium_lower_limit.numeric' => trans('not_correspond.validate.numeric'),
            'midium_lower_limit.min' => trans('not_correspond.validate.min'),
            'large_lower_limit.required' => trans('not_correspond.validate.required'),
            'large_lower_limit.numeric' => trans('not_correspond.validate.numeric'),
            'large_lower_limit.min' => trans('not_correspond.validate.numeric'),
            'immediate_date.required' => trans('not_correspond.validate.required'),
            'immediate_date.numeric' => trans('not_correspond.validate.numeric'),
            'immediate_date.min' => trans('not_correspond.validate.min'),
            'immediate_lower_limit.required' => trans('not_correspond.validate.required'),
            'immediate_lower_limit.numeric' => trans('not_correspond.validate.numeric'),
            'immediate_lower_limit.min' => trans('not_correspond.validate.min'),
        ];
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator)
    {
        $message['type'] = 'error';
        $message['text'] = trans('not_correspond.message_error');
        $this->session()->flash('flash_message', $message);
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
