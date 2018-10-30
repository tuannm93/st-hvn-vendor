<?php

namespace App\Services\Commission;

use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ValidateService extends BaseService
{
    /**
     * ValidateService constructor.
     */
    public function __construct()
    {
    }
    /**
     * Validate before regist
     * @param array $data
     * @return array
     */
    public function checkCorrespond($data)
    {
        $result = ['check' => true];
        $message = [
            'required' => '必須入力です。',
            'date' => '日時形式で入力してください。',
            'max' => ':max文字以内で設定してください。'
        ];
        $validate = Validator::make($data, [
            'commission_id' => 'required',
            'correspond_datetime' => 'date|nullable',
            'responders' => 'max:20',
            'corresponding_contens' => 'max:1000'
        ], $message);

        $result['validate'] = $validate;
        $allValid = [
            $this->checkCorrespondResponder($data),
            $this->checkCorrespondContent($data)
        ];

        if ($validate->fails()) {
            $result['check'] = false;
        }

        if (in_array(false, $allValid)) {
            $result['check'] = false;
        }

        return $result;
    }

    /**
     * Validate demand info
     * @param array $data
     * @return array
     */
    public function validateDemandInfo($data)
    {
        $result = ['check' => true];
        $message = [
            'numeric' => __('commission_detail.validation.numeric'),
        ];
        $validate = Validator::make($data, [
            'jbr_receipt_price' => 'numeric|nullable',
        ], $message);

        $result['validate'] = $validate;

        if ($validate->fails()) {
            $result['check'] = false;
        }

        return $result;
    }

    /**
     * @param array $data
     * @return boolean
     */
    private function checkCorrespondContent($data)
    {
        if (empty($data['corresponding_contens'])) {
            if ((array_key_exists('responders', $data) && !empty($data['responders'])) || (array_key_exists('rits_responders', $data) && !empty($data['rits_responders']))) {
                session()->flash('corresponding_contens_error', trans('commission.corresponding_contens_require'));
                Log::debug('false checkInputContens');
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @return boolean
     */
    private function checkCorrespondResponder($data)
    {
        if ((array_key_exists('responders', $data) && empty($data['responders']))
            || (array_key_exists('rits_responders', $data) && empty($data['rits_responders']))) {
            if (!empty($data['corresponding_contens'])) {
                session()->flash('rits_responders_error', trans('commission.rits_responders_require'));
                Log::debug('false checkInputResponders');
                return false;
            }
        }

        return true;
    }
}
