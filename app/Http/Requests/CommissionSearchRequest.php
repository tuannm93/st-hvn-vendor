<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommissionSearchRequest extends FormRequest
{
    /**
     * @var string
     */
    protected $sessionKeyForCommissionSearch = 'datas@CommissionSearch';

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
        if (isset($this->demand_id) && $this->demand_id != null) {
            $rules['demand_id'] = 'numeric';
        }
        if (isset($this->customer_tel) && $this->customer_tel != null) {
            $rules['customer_tel'] = 'numeric';
        }
        if (isset($this->contact_desired_time) && $this->contact_desired_time != null) {
            $rules['contact_desired_time'] = 'date_format:"Y/m/d"';
        }
        if (isset($this->visit_desired_time) && $this->visit_desired_time != null) {
            $rules['visit_desired_time'] = 'date_format:"Y/m/d"';
        }
        $rules = $this->rulesForCommissionDate($rules);
        return $rules;
    }

    /**
     * @param $rules
     * @return mixed
     */
    private function rulesForCommissionDate($rules)
    {
        if (isset($this->commission_date1) && $this->commission_date1 != null) {
            $rules['commission_date1'] = 'date_format:"Y/m/d"';
        }
        if (isset($this->commission_date2) && $this->commission_date2 != null) {
            $rules['commission_date2'] = 'date_format:"Y/m/d"';
        }
        if ($this->commission_date1 != null && $this->commission_date2 != null) {
            $rules['commission_date2'] = 'after_or_equal:commission_date1';
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            "demand_id" => trans('commissioninfos.lbl.demand_id'),
            "customer_tel" => trans('commissioninfos.lbl.demand_id'),
            "contact_desired_time" => trans('commissioninfos.lbl.contact_desired_time'),
            "commission_date1" => trans('commissioninfos.lbl.commission_date'),
            "commission_date2" => trans('commissioninfos.lbl.commission_date'),
            "from_follow_date" => trans('commissioninfos.lbl.commission_date'),
            "to_follow_date" => trans('commissioninfos.lbl.commission_dat'),
            "visit_desired_time" => trans('commissioninfos.lbl.visit_desired_time'),
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'demand_id.numeric' => trans('commissioninfos.validation.numeric'),
            'customer_tel.numeric' => trans('commissioninfos.validation.numeric'),
            'contact_desired_time.date_format' => trans('commissioninfos.validation.date_format'),
            'commission_date1.date_format' => trans('commissioninfos.validation.date_format'),
            'commission_date2.date_format' => trans('commissioninfos.validation.date_format'),
            'from_follow_date.date_format' => trans('commissioninfos.validation.date_format'),
            'to_follow_date.date_format' => trans('commissioninfos.validation.date_format'),
            'visit_desired_time.date_format' => trans('commissioninfos.validation.date_format'),
            'to_follow_date.after_or_equal' => trans('commissioninfos.validation.after_or_equal'),
            'commission_date2.after_or_equal' => trans('commissioninfos.validation.after_or_equal'),
        ];
    }
}
