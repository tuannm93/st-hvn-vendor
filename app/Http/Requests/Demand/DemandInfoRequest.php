<?php

namespace App\Http\Requests\Demand;

use Illuminate\Foundation\Http\FormRequest;

class DemandInfoRequest extends FormRequest
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

        $roles = $this->checkVisitTime($this->get('visitTime'));
        return $roles;
    }

    /**
     * @param $data
     * @return array
     */
    private function checkVisitTime($data)
    {
        $roles = [];
        $date = date('Y/m/d H:i');
        foreach ($data as $key => $value) {
            if (!empty($data['visit_time']) && !empty($data['demanInfo']['id'])) {
                $roles['visitTime.' . $key . '.visit_time'] = 'date_format:' . 'Y/m/d H:i|after:' . $date;
            }
        }
        return $roles;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'visitTime.*.visit_time.date_format' => 'date fomat',
            'visitTime.*.visit_time.after' => 'date after',
        ];
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
    }
}
