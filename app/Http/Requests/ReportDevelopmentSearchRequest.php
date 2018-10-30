<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportDevelopmentSearchRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        if ($this->input("genre_id", null) == null) {
            $this->session()->put("genre_id", null);
        }

        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            "genre_id" => "required",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "genre_id.required" => trans('report_development_search.genre_id_required'),
        ];
    }
}
