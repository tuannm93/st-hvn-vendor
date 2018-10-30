<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AddAdditionInfoRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function rules(Request $request)
    {
        $isMobile = utilIsMobile($request->header('user-agent'));
        $rules['customer_name'] = 'required';
        $rules['construction_price_tax_exclude'] = 'required|numeric';
        $rules['complete_date'] = 'required|date_format:Y/m/d';
        if (! $isMobile) {
            $rules['demand_type_update'] = 'required';
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'customer_name.required' => trans('addition.customer_name').trans('addition.required'),
            'construction_price_tax_exclude.required' => trans('addition.construction_price_tax_exclude').trans('addition.required'),
            'complete_date.required' => trans('addition.complete_date').trans('addition.required'),
            'complete_date.date_format' => trans('addition.complete_date').trans('addition.date_format'),
            'demand_type_update.required' => trans('addition.demand_type_update').trans('addition.required'),
        ];
    }
}
