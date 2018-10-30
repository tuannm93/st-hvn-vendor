<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


use Illuminate\Support\Facades\Input;

class AutoMTimeFormRequest extends FormRequest
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
        $rules = [];
        $rules['item_hour_date9'] = 'required|integer';
        $rules['item_hour_date10'] = 'required|integer';
        $rules['item_hour_date11'] = 'required|integer';
        $rules['item_hour_date12'] = 'required|integer';
        $rules['item_hour_date13'] = 'required|integer';
        $rules['item_hour_date14'] = 'required|integer';
        $rules['item_hour_date15'] = 'required|integer';
        for ($i = 1; $i <= 6; $i++) {
            if (empty(Input::get('item_hour_date' . $i))) {
                if (Input::get('item_category' . $i) != 'send_mail') {
                    if ((!empty(Input::get('item_type' . $i)) && Input::get('item_type' . $i) == 0 && empty(Input::get('item_minute_date' . $i))) || (!empty(Input::get('item_type' . $i)) && Input::get('item_type' . $i) == 1) || (Input::get('item_minute_date' . $i) == 0)) {
                        $rules['item_hour_date'. $i] = 'required';
                        $rules['item_minute_date'. $i] = 'required';
                    }
                }
            }
            if (empty(Input::get('item_minute_date' . $i))) {
                if (Input::get('item_category' . $i) != 'send_mail') {
                    if ((!empty(Input::get('item_type' . $i)) && Input::get('item_type' . $i) == 0 && empty(Input::get('item_hour_date' . $i))) || (!empty(Input::get('item_type' . $i)) && Input::get('item_type' . $i) == 2)) {
                        $rules['item_hour_date'. $i] = 'required';
                        $rules['item_minute_date'. $i] = 'required';
                    }
                }
            }
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'item_hour_date1.required' => 'mtime.msg_error_required',
            'item_minute_date1.required' => 'mtime.msg_error_required',
            'item_hour_date2.required' => 'mtime.msg_error_required',
            'item_minute_date2.required' => 'mtime.msg_error_required',
            'item_hour_date3.required' => 'mtime.msg_error_required',
            'item_minute_date3.required' => 'mtime.msg_error_required',
            'item_hour_date4.required' => 'mtime.msg_error_required',
            'item_minute_date4.required' => 'mtime.msg_error_required',
            'item_hour_date5.required' => 'mtime.msg_error_required',
            'item_minute_date5.required' => 'mtime.msg_error_required',
            'item_hour_date6.required' => 'mtime.msg_error_required',
            'item_minute_date6.required' => 'mtime.msg_error_required',
            'item_hour_date9.required' => 'mtime.msg_error_required',
            'item_hour_date9.integer' => 'mtime.msg_error_numberic',
            'item_hour_date10.required' => 'mtime.msg_error_required',
            'item_hour_date10.integer' => 'mtime.msg_error_numberic',
            'item_hour_date11.required' => 'mtime.msg_error_required',
            'item_hour_date11.integer' => 'mtime.msg_error_numberic',
            'item_hour_date12.required' => 'mtime.msg_error_required',
            'item_hour_date12.integer' => 'mtime.msg_error_numberic',
            'item_hour_date13.required' => 'mtime.msg_error_required',
            'item_hour_date13.integer' => 'mtime.msg_error_numberic',
            'item_hour_date14.required' => 'mtime.msg_error_required',
            'item_hour_date14.integer' => 'mtime.msg_error_numberic',
            'item_hour_date15.required' => 'mtime.msg_error_required',
            'item_hour_date15.integer' => 'mtime.msg_error_numberic',
        ];
    }
}
