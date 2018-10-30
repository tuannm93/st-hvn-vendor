<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CommissionValidateService
{
     /**
     * build validate
     * @param  array $data data to validate
     * @return Validator
     */
    private function buildValidate($data)
    {
        $message = [
            'required' => __('commission_detail.validation.required'),
            'date' => __('commission_detail.validation.date'),
            'max' => __('commission_detail.validation.max'),
            'numeric' => __('commission_detail.validation.numeric')
        ];
        $validate = Validator::make($data, [
            'appointers' => 'max:20',
            'first_commission' => 'numeric',
            'corp_fee' => 'numeric|nullable',
            'attention' => 'max:1000',
            'commission_dial' => 'numeric|nullable',
            'tel_commission_datetime' => 'date|nullable',
            'tel_commission_person' => 'max:20',
            'commission_fee_rate' => 'numeric|nullable',
            'commission_note_sender' => 'numeric|nullable',
            'commission_note_send_datetime' => 'date|nullable',
            'commission_status' => 'numeric|nullable',
            'construction_price_tax_exclude' => 'numeric|nullable',
            'complete_date' => 'max:10',
            'order_fail_date' => 'max:10',
            'estimate_price_tax_exclude' => 'numeric|nullable',
            'construction_price_tax_include' => 'numeric|nullable',
            'deduction_tax_include' => 'numeric|nullable',
            'deduction_tax_exclude' => 'numeric|nullable',
            'confirmd_fee_rate' => 'numeric|nullable',
            'unit_price_calc_exclude' => 'numeric|nullable',
            'report_note' => 'max:1000',
            'irregular_fee_rate' => 'numeric|nullable',
            'irregular_fee' => 'numeric|nullable',
            'reported_flg' => 'numeric|nullable',
            'follow_date' => 'date|nullable',
            'business_trip_amount' => 'numeric|nullable',
        ], $message);

        return $validate;
    }

    /**
     * @param $dateValue
     * @return bool
     */
    private function checkDateFormat($dateValue)
    {
        $datePart = explode('/', $dateValue);
        switch (true) {
            case count($datePart) < 3: // 「/」で分割した個数が3より少ない
                return false;
                break;
            case strlen($datePart[0]) < 4: // 年が4桁未満
                return false;
                break;
            case strlen($datePart[1]) < 2: // 月が4桁未満
                return false;
                break;
            case strlen($datePart[2]) < 2: // 日が4桁未満
                return false;
                break;
        }

        return true;
    }
    /**
     * @param $data
     * @param $status
     * @return bool
     */
    public function validate($data, $status)
    {
        $validate = Validator::make($data, [
            'appointers' => 'max:20',
            'first_commission' => 'numeric',
            'corp_fee' => 'numeric', //checkEmptyCorpFee
            'waste_collect_oath' => 'numeric',
            'attention' => 'max:1000',
            'official_name' => 'max:200',
            'tel_commission_person' => 'max:20',
            'commission_note_sender' => 'max:20',
            'commission_dial' => 'numeric',
            'commission_status' => 'numeric',
            'commission_note_send_datetime' => 'date',
            'commission_fee_rate' => 'numeric',
            'construction_price_tax_exclude' => 'numeric',
            'estimate_price_tax_exclude' => 'max:10',
            'construction_price_tax_include' => 'numeric',
            'deduction_tax_include' => 'numeric',
            'deduction_tax_exclude' => 'numeric',
            'confirmd_fee_rate' => 'numeric',
            'unit_price_calc_exclude' => 'numeric',
            'report_note' => 'max:1000',
            'irregular_fee_rate' => 'numeric',
            'irregular_fee' => 'numeric',
            'reported_flg' => 'numeric',
            'follow_date' => 'date',
            'business_trip_amount' => 'numeric',
        ]);
        $valids = [
            $validate->passes(),
            $this->validateDemandStatus($status, $data),
            $this->checkEmptyCorpFee($data),
            $this->checkCommissionStatusComplete($data),
            $this->checkEmptyCommissionFeeRate($data),
            $this->checkCommissionIntroduce($data),
            $this->checkCommissionStatusOrderFail($data),
            $this->checkCommissionOrderFailReason($data),
            $this->checkCommissionOrderFailConstructionPrice($data),
            $this->checkConstructionPrice($data),
            $this->checkCompleteDate($data),
            $this->checkFutureCompleteDate($data),
            $this->checkCompleteDateFormat($data),
            $this->checkOrderFailDate($data),
            $this->checkFutureOrderFailDate($data),
            $this->checkOrderFailDateFormat($data),
            $this->checkConstructionPriceTaxInclude($data),
            $this->checkFalsity($data)
        ];
        return !in_array(false, $valids);
    }

    /**
     * @param $data
     * @return array
     */
    public function validateRegist($data)
    {
        $result = ['check' => true];
        $validate = $this->buildValidate($data);
        $result['validate'] = $validate;
        $allValid = [
            $this->checkEmptyCorpFee($data),
            $this->checkEmptyCommissionFeeRate($data),
            $this->checkCommissionIntroduce($data),
            $this->checkCommissionStatusCompleteRegist($data),
            $this->checkCommissionStatusOrderFail($data),
            $this->checkCommissionOrderFailReason($data),
            $this->checkCommissionOrderFailConstructionPrice($data),
            $this->checkConstructionPrice($data),
            $this->checkCompleteDate($data),
            $this->checkFutureCompleteDate($data),
            $this->checkCompleteDateFormat($data),
            $this->checkOrderFailDate($data),
            $this->checkFutureOrderFailDate($data),
            $this->checkOrderFailDateFormat($data),
            $this->checkConstructionPriceTaxInclude($data),
            $this->checkFalsity($data),
            !$validate->fails()
        ];
        $result['check'] = !in_array(false, $allValid);
        return $result;
    }

    /**
     * @param $status
     * @param $data
     * @return bool
     */
    public function validateDemandStatus($status, $data)
    {
        foreach ($data as $key => $d) {
            if ($status == getDivValue('demand_status', 'telephone_already')) {    // 案件状況が【進行中】電話取次済の場合
                if (empty($data['tel_commission_datetime']) && $d['commit_flg'] == 1) {
                    Log::debug('false validateDemandStatus');
                    session()->flash(
                        'commission_errors.' . $key . '.tel_commission_datetime',
                        __('commission_detail.validation.required')
                    );
                    return false;
                }
            } elseif ($status == getDivValue('demand_status', 'information_sent')) { // 案件状況が【進行中】情報送信済の場合
                if (empty($d['commission_note_send_datetime']) && $d['commit_flg'] == 1) {
                    Log::debug('false validateDemandStatus');
                    session()->flash(
                        'commission_errors.' . $key . '.commission_note_send_datetime',
                        __('commission_detail.validation.required')
                    );
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function checkEmptyCorpFee($data)
    {
        if (isset($data['commit_flg']) && !empty($data['commit_flg']) && empty($data['corp_fee'])) {
            session()->flash('commission_errors.corp_fee', __('commission_detail.validation.error_empty_corp_fee'));
            Log::debug('false checkEmptyCorpFee');
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function checkCommissionStatusCompleteRegist($data)
    {
        if (!isset($data['commission_status'])) {
            return true;
        }

        if ($data['commission_status'] == getDivValue('construction_status', 'construction') &&
            $data['commission_status'] == getDivValue('construction_status', 'introduction') &&
            (!empty($data['complete_date']) || !empty($data['construction_price_tax_exclude']))) {
            session()->flash(
                'commission_errors.commission_status',
                __('commission_detail.validation.error_commission_status_complete')
            );
            Log::debug('false checkCommissionStatusComplete');
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function checkCommissionStatusComplete($data)
    {
        if (!isset($data['commission_status'])) {
            return true;
        }

        if ($data['commission_status'] != getDivValue('construction_status', 'construction') &&
            $data['commission_status'] != getDivValue('construction_status', 'introduction') &&
            $data['commission_status'] != getDivValue('construction_status', 'order_fail') &&
            (!empty($data['complete_date']) || !empty($data['construction_price_tax_exclude']))) {
            session()->flash(
                'commission_errors.commission_status',
                __('commission_detail.validation.error_commission_status_complete')
            );
            Log::debug('false checkCommissionStatusComplete');
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function checkEmptyCommissionFeeRate($data)
    {
        if (!empty($data['commit_flg']) && empty($data['commission_fee_rate'])) {
            session()->flash(
                'commission_errors.commission_fee_rate',
                __('commission_detail.validation.error_empty_commission_fee_rate')
            );
            Log::debug('false checkEmptyCommissionFeeRate');
            return false;
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    protected function checkCommissionStatusOrderFail($data)
    {
        if (!isset($data['commission_status'])) {
            return true;
        }
        if ($data['commission_status'] != getDivValue('construction_status', 'order_fail')) {
            if (!empty($data['order_fail_date'])) {
                session()->flash(
                    'commission_errors.commission_status',
                    __('commission_detail.validation.error_commission_status_order_fail')
                );
                Log::debug('false checkCommissionStatusOrderFail');
                return false;
            }
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkCommissionOrderFailReason($data)
    {
        if (!isset($data['commission_status'])) {
            return true;
        }

        if (empty($data['commission_order_fail_reason'])) {
            if ($data['commission_status'] == getDivValue('construction_status', 'order_fail')) {
                session()->flash(
                    'commission_errors.commission_order_fail_reason',
                    __('commission_detail.validation.not_empty_order_fail_date')
                );
                Log::debug('false checkCommissionOrderFailReason');
                return false;
            }
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkCommissionIntroduce($data)
    {
        if (!isset($data['commission_type'])) {
            return true;
        }

        if ($data['commission_type'] == getDivValue('commission_type', 'normal_commission')
            && $data['commission_status'] == getDivValue('construction_status', 'introduction')) {
            session()->flash(
                'commission_errors.commission_status',
                __('commission_detail.validation.error_commission_status_introcude')
            );
            Log::debug('false checkCommissionIntroduce');
            return false;
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkCommissionOrderFailConstructionPrice($data)
    {
        if (!isset($data['commission_status'])) {
            return true;
        }

        if (!empty($data['construction_price_tax_exclude'])) {
            if ($data['commission_status'] == getDivValue('construction_status', 'order_fail')) {
                session()->flash(
                    'commission_errors.construction_price_tax_exclude',
                    __('commission_detail.validation.empty_construction_price')
                );
                Log::debug('false checkCommissionOrderFailConstructionPrice');
                return false;
            }
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkConstructionPrice($data)
    {
        if (!isset($data['commission_status'])) {
            return true;
        }

        if (key_exists('construction_price_tax_exclude', $data)
            && strlen($data['construction_price_tax_exclude']) == 0
        ) {
            if ($data['commission_status'] == getDivValue('construction_status', 'construction')) {
                session()->flash(
                    'commission_errors.construction_price_tax_exclude',
                    __('commission_detail.validation.not_empty_construction_price')
                );
                Log::debug('false checkConstructionPrice');
                return false;
            }
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkCompleteDate($data)
    {
        if (!isset($data['commission_status'])) {
            return true;
        }

        if (empty($data['complete_date'])) {
            if ($data['commission_status'] == getDivValue('construction_status', 'construction')) {
                session()->flash(
                    'commission_errors.complete_date',
                    __('commission_detail.validation.not_empty_complete_date')
                );
                Log::debug('false checkCompleteDate');
                return false;
            }
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkFutureCompleteDate($data)
    {
        if (!isset($data['complete_date'])) {
            return true;
        }

        $user = auth()->user()->auth;

        if (!empty($data['complete_date']) && $user != 'accounting' && $user != 'system') {
            if (strtotime($data['complete_date']) > strtotime(date('Y-m-d'))) {
                session()->flash(
                    'commission_errors.complete_date',
                    __('commission_detail.validation.error_future_date')
                );
                Log::debug('false checkFutureCompleteDate');
                return false;
            }
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkCompleteDateFormat($data)
    {
        if (!isset($data['complete_date']) || $this->checkDateFormat($data['complete_date'])) {
            return true;
        }

        session()->flash('commission_errors.complete_date', __('commission_detail.validation.not_date_format'));

        return false;
    }
    /**
     * @param $data
     * @return bool
     */
    protected function checkOrderFailDateFormat($data)
    {
        if (!isset($data['order_fail_date']) || $this->checkDateFormat($data['order_fail_date'])) {
            return true;
        }
        session()->flash('commission_errors.order_fail_date', __('commission_detail.validation.not_date_format'));
        return false;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkOrderFailDate($data)
    {
        if (!isset($data['commission_status'])) {
            return true;
        }

        if (empty($data['order_fail_date'])) {
            if ($data['commission_status'] == getDivValue('construction_status', 'order_fail')) {
                session()->flash(
                    'commission_errors.order_fail_date',
                    __('commission_detail.validation.not_empty_order_fail_date')
                );
                Log::debug('false checkOrderFailDate');
                return false;
            }
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkFutureOrderFailDate($data)
    {
        if (!isset($data['order_fail_date'])) {
            return true;
        }

        if (!empty($data['order_fail_date'])) {
            if (strtotime($data['order_fail_date']) > strtotime(date('Y-m-d'))) {
                session()->flash(
                    'commission_errors.order_fail_date',
                    __('commission_detail.validation.error_future_date')
                );
                Log::debug('false checkFutureOrderFailDate');
                return false;
            }
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    protected function checkConstructionPriceTaxInclude($data)
    {
        if (!isset($data['commission_status'])) {
            return true;
        }

        if (key_exists('construction_price_tax_include', $data)
            && strlen($data['construction_price_tax_include']) == 0
        ) {
            if ($data['commission_status'] == getDivValue('construction_status', 'construction')) {
                session()->flash(
                    'commission_errors.construction_price_tax_include',
                    __('commission_detail.validation.not_empty_construction_price')
                );
                Log::debug('false checkConstructionPriceTaxInclude');
                return false;
            }
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    protected function checkFalsity($data)
    {
        if (isset($data['reported_flg']) && empty($data['reported_flg'])) {
            session()->flash('commission_errors.reported_flg', __('commission_detail.validation.falsity_not_check'));
            Log::debug('false reported_flg');
            return false;
        }

        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function validateCheckLimitCommitFlg($data)
    {
        $maxNum = $this->mSiteRepo->findMaxLimit((object)$data['demandInfo']);
        $commitFlgCount = 0;

        foreach ($data['commissionInfo'] as $item) {
            if (!empty($item['corp_id']) && $item['commit_flg'] == "1") {
                $commitFlgCount++;
            }
        }

        return $maxNum >= $commitFlgCount;
    }
    /**
     * @param $data
     * @param $commissionInfo
     * @return bool
     */
    public function checkConditionUpdateCommission($data, $commissionInfo)
    {
        if (!array_key_exists('commissionInfo', $data)) {
            Log::Debug('___ empty commissionInfo ________');
            return true;
        }
        if (empty($commissionInfo)) {
            Log::Debug('___ empty commission_type =1 ________');
            return true;
        }
        return false;
    }
    /**
     * @param $data
     * @param $val
     * @return bool
     */
    protected function checkConditionUpdateSendMailFax($data, $val)
    {
        if (!empty($val->id)
            && $val->commit_flg == 1
            && $val->introduction_not != 1
            && $val->lost_flg != 1
            && array_key_exists('commissionInfo', $data)) {
            return true;
        }
        return false;
    }
       /**
     * @param $data
     * @param $introduceInfo
     * @return bool
     */
    protected function checkConditionUpdateIntroduce($data, $introduceInfo)
    {
        if (!isset($data['commissionInfo'])) {
            return true;
        }
        if (empty($introduceInfo)) {
            return true;
        }
        return false;
    }
}
