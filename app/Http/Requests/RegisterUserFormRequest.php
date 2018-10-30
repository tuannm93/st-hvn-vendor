<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RegisterUserFormRequest extends FormRequest
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
        if ($this->segment(3)) {
            $validate = [
                'user_id' => 'required|regex:/^[a-zA-Z0-9\-]+$/|max:20|unique:m_users,user_id,' . (int)$this->segment(3),
                'user_name' => 'required|max:40',
                'password_confirm' => 'same:password|max:40',
                'auth' => 'required'
            ];
            if (!empty($this->request->get('password'))) {
                $validate += ['password' => 'required|regex:/^[a-zA-Z0-9_\<\>\!\$%&@\+\-\*\=]*$/|max:40'];
            }
        } else {
            $validate = [
                'user_id' => 'required|regex:/^[a-zA-Z0-9\-]+$/|unique:m_users,user_id|max:20',
                'user_name' => 'required|max:40',
                'password' => 'required|regex:/^[a-zA-Z0-9_\<\>\!\$%&@\+\-\*\=]*$/|max:40',
                'password_confirm' => 'required|same:password|max:40',
                'auth' => 'required'
            ];
        }

        if ($this->request->get('auth') == 'affiliation') {
            $validate += [
                'official_corp_name' => [
                    'required',
                    'max:40',
                    function ($attribute, $value, $fail) {
                        $corpName = DB::table('m_corps')->whereRaw('z2h_kana(m_corps.'.$attribute.') = ?', chgSearchValue($value))->first();
                        if (empty($corpName)) {
                            return $fail(trans('user.required'));
                        }
                    }
                ]
            ];
        }
        return $validate;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'required' => trans('user.required'),
            'same' => trans('user.same_password'),
            'unique' => trans('user.unique_id'),
            'max' => trans('user.max_length_field'),
            'user_id.regex' => trans('user.user_id_regex'),
            'password.regex' => trans('user.password_regex')
        ];
    }

    /**
     * @param mixed $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            Session::flashInput($this->request->all());
        });
    }
}
