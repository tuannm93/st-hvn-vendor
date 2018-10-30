<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class ExclusionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
        $holidayIdKeys = array_keys(array_filter(Input::get('holiday_id')));
        $holidayDateKeys = array_keys(array_filter(Input::get('holiday_date')));
        $exclusionTimeFromKeys = array_keys(array_filter(Input::get('exclusion_time_from')));
        $exclusionTimeToKeys = array_keys(array_filter(Input::get('exclusion_time_to')));
        $ruleHolidayDates = $this->setHolidayRules($holidayDateKeys, $holidayIdKeys);
        $ruleExclusionTimes = $this->setExclusionRules($exclusionTimeFromKeys, $exclusionTimeToKeys);
        return array_merge($ruleHolidayDates, $ruleExclusionTimes);
    }

    /**
     *
     * @return array
     */
    public function messages()
    {
        $holidayIdKeys = array_keys(array_filter(Input::get('holiday_id')));
        $holidayDateKeys = array_keys(array_filter(Input::get('holiday_date')));
        $exclusionTimeFromKeys = array_keys(array_filter(Input::get('exclusion_time_from')));
        $exclusionTimeToKeys = array_keys(array_filter(Input::get('exclusion_time_to')));
        $messageHolidays = $this->setHolidayMessages($holidayDateKeys, $holidayIdKeys);
        $messageExclusions = $this->setExclusionMessages($exclusionTimeFromKeys, $exclusionTimeToKeys);
        return array_merge($messageHolidays, $messageExclusions);
    }

    /**
     * set holiday rules
     * @param array $holidayDateKeys
     * @param array $holidayIdKeys
     * @return array
     */
    private function setHolidayRules($holidayDateKeys, $holidayIdKeys)
    {
        $rules = [];
        if (count($holidayDateKeys) && !count($holidayIdKeys)) {
            foreach ($holidayDateKeys as $holidayDateKey) {
                $rules['holiday_date.' . $holidayDateKey] = 'date';
            }
        } elseif (count($holidayDateKeys) && count($holidayIdKeys)) {
            foreach ($holidayDateKeys as $holidayDateKey) {
                $rules['holiday_date.' . $holidayDateKey] = 'date';
            }
        } elseif (!count($holidayDateKeys) && !count($holidayIdKeys)) {
            $rules['holiday_date.*'] = 'required|date';
        }
        return $rules;
    }

    /**
     * set exclusion rules
     * @param array $exclusionTimeFromKeys
     * @param array $exclusionTimeToKeys
     * @return array
     */
    private function setExclusionRules($exclusionTimeFromKeys, $exclusionTimeToKeys)
    {
        $rules = [];
        if (count($exclusionTimeFromKeys) && count($exclusionTimeToKeys)) {
            foreach ($exclusionTimeToKeys as $exclusionTimeToKey) {
                if (!isset($exclusionTimeFromKeys[$exclusionTimeToKey])) {
                    $rules['exclusion_time_from.' . $exclusionTimeToKey] = 'required';
                } else {
                    $rules['exclusion_time_to.' . $exclusionTimeToKey] = 'date_format:H:i';
                    $rules['exclusion_time_from.' . $exclusionTimeToKey] = 'date_format:H:i';
                }
            }
            foreach ($exclusionTimeFromKeys as $exclusionTimeFromKey) {
                if (!isset($exclusionTimeToKeys[$exclusionTimeFromKey])) {
                    $rules['exclusion_time_to.' . $exclusionTimeFromKey] = 'required';
                } else {
                    $rules['exclusion_time_to.' . $exclusionTimeFromKey] = 'date_format:H:i';
                    $rules['exclusion_time_from.' . $exclusionTimeFromKey] = 'date_format:H:i';
                }
            }
        }
        return $rules;
    }

    /**
     * set holiday messages
     * @param array $holidayDateKeys
     * @param array $holidayIdKeys
     * @return array
     */
    private function setHolidayMessages($holidayDateKeys, $holidayIdKeys)
    {
        $messages = [];
        if (count($holidayDateKeys) && !count($holidayIdKeys)) {
            foreach ($holidayDateKeys as $holidayDateKey) {
                $messages['holiday_date.' . $holidayDateKey . '.date'] = trans('exclusion.date_format');
            }
        } elseif (count($holidayDateKeys) && count($holidayIdKeys)) {
            foreach ($holidayDateKeys as $holidayDateKey) {
                $messages['holiday_date.' . $holidayDateKey . '.date'] = trans('exclusion.date_format');
            }
        } elseif (!count($holidayDateKeys) && !count($holidayIdKeys)) {
            $messages['holiday_date.*.required'] = trans('exclusion.required');
            $messages['holiday_date.*.date'] = trans('exclusion.date_format');
        }
        return $messages;
    }

    /**
     * set exclusion times messages
     * @param array $exclusionTimeFromKeys
     * @param array $exclusionTimeToKeys
     * @return array
     */
    private function setExclusionMessages($exclusionTimeFromKeys, $exclusionTimeToKeys)
    {
        $messages = [];
        if (count($exclusionTimeFromKeys) && count($exclusionTimeToKeys)) {
            foreach ($exclusionTimeToKeys as $exclusionTimeToKey) {
                if (!isset($exclusionTimeFromKeys[$exclusionTimeToKey])) {
                    $messages['exclusion_time_from.' . $exclusionTimeToKey . '.required'] = trans('exclusion.required');
                } else {
                    $messages['exclusion_time_to.' . $exclusionTimeToKey . '.date_format'] = trans('exclusion.time_format');
                    $messages['exclusion_time_from.' . $exclusionTimeToKey . '.date_format'] = trans('exclusion.time_format');
                }
            }
            foreach ($exclusionTimeFromKeys as $exclusionTimeFromKey) {
                if (!isset($exclusionTimeToKeys[$exclusionTimeFromKey])) {
                    $messages['exclusion_time_to.' . $exclusionTimeFromKey . '.required'] = trans('exclusion.required');
                } else {
                    $messages['exclusion_time_to.' . $exclusionTimeFromKey . '.date_format'] = trans('exclusion.time_format');
                    $messages['exclusion_time_from.' . $exclusionTimeFromKey . '.date_format'] = trans('exclusion.time_format');
                }
            }
        }
        return $messages;
    }
}
