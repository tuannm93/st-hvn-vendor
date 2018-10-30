<?php

namespace App\Services\Commission;

use App\Models\CommissionApp;
use App\Models\MUser;
use App\Repositories\CommissionAppRepositoryInterface;
use App\Services\BaseService;
use App\Repositories\ApprovalRepositoryInterface;
use App\Repositories\BillRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\MTaxRateRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApprovalService extends BaseService
{
    /**
     * @var ApprovalRepositoryInterface
     */
    protected $approvalRepo;
    /**
     * @var BillRepositoryInterface
     */
    protected $billRepo;
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepo;
    /**
     * @var MTaxRateRepositoryInterface
     */
    protected $mTaxRateRepo;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepo;
    /**
     * @var CommissionAppRepositoryInterface
     */
    protected $commissionAppRepo;
    /**
     * @var CorrespondService
     */
    protected $commissionCorrespondService;
    /**
     * @var CorrespondService
     */
    protected $commissionSupportService;

    /**
     * ApprovalService constructor.
     * @param ApprovalRepositoryInterface $approvalRepo
     * @param BillRepositoryInterface $billRepo
     * @param CommissionInfoRepositoryInterface $commissionInfoRepo
     * @param MTaxRateRepositoryInterface $mTaxRateRepo
     * @param MCategoryRepositoryInterface $mCategoryRepo
     * @param CommissionAppRepositoryInterface $commissionAppRepo
     * @param CorrespondService $commissionCorrespondService
     * @param SupportService $commissionSupportService
     */
    public function __construct(
        ApprovalRepositoryInterface $approvalRepo,
        BillRepositoryInterface $billRepo,
        CommissionInfoRepositoryInterface $commissionInfoRepo,
        MTaxRateRepositoryInterface $mTaxRateRepo,
        MCategoryRepositoryInterface $mCategoryRepo,
        CommissionAppRepositoryInterface $commissionAppRepo,
        CorrespondService $commissionCorrespondService,
        SupportService $commissionSupportService
    ) {
        $this->approvalRepo = $approvalRepo;
        $this->billRepo = $billRepo;
        $this->commissionInfoRepo = $commissionInfoRepo;
        $this->mTaxRateRepo = $mTaxRateRepo;
        $this->mCategoryRepo = $mCategoryRepo;
        $this->commissionAppRepo = $commissionAppRepo;
        $this->commissionCorrespondService = $commissionCorrespondService;
        $this->commissionSupportService = $commissionSupportService;
    }

    /**
     * @param integer $id
     * @return object|null
     */
    public function getApprovalById($id)
    {
        $approval = null;
        try {
            $approval = $this->approvalRepo->find($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $approval;
    }

    /**
     * @param integer $id
     * @return \App\Models\Base|null
     */
    public function getItemById($id)
    {
        $item = null;
        try {
            $item = $this->commissionAppRepo->find($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $item;
    }

    /**
     * @param string $correspond
     * @param array $commissionData
     * @param object $commissionApp
     * @param object $user
     * @param boolean $resultsFlg
     */
    private function approvalRegistBillInfoAndInsertCorrespond($correspond, &$commissionData, &$commissionApp, &$user, &$resultsFlg)
    {
        if ($commissionData['commission_status'] == getDivValue('construction_status', 'construction')
            || $commissionData['commission_status'] == getDivValue('construction_status', 'introduction')) {
            $resultsFlg = $this->registBillInfo($commissionApp->commission_id, $commissionData);
        }

        if ($resultsFlg && !empty($correspond)) {
            $cd = [];
            $cd['corresponding_contens'] = $correspond;
            $cd['responders'] = trans("commission.service.automatic.registration") . '[' . $user->user_name . ']';
            $cd['rits_responders'] = null;
            $cd['commission_id'] = $commissionApp->commission_id;
            $cd['created_user_id'] = 'system';
            $cd['created'] = date('Y-m-d H:i:s');
            $cd['modified_user_id'] = 'system';
            $cd['modified'] = date('Y-m-d H:i:s');
            $cd['correspond_datetime'] = date('Y-m-d H:i:s');

            $this->commissionCorrespondService->insert($cd);
        }
    }

    /**
     * Update approvals.status
     *
     * @param integer $approvalId
     * @param \App\Models\Base|null $commissionApp
     * @param string $actionName
     * @param MUser $user
     * @return bool
     * @throws \Exception
     */
    public function approval($approvalId, $commissionApp, $actionName, $user)
    {
        DB::beginTransaction();
        try {
            /* Update approval */
            $resultsFlg = $this->approvalRepo->updateStatus($approvalId, $actionName);
            /* Set data for commission */
            $commissionData = $this->setDataForCommission($commissionApp->commission_id);
            $commissionData = $this->addDataForCommission($commissionApp, $commissionData);
            /* Get modified time of commission Info */
            $modified = $commissionData['modified'];
            /* Remove unused data */
            unset($commissionData['commit_flg']);
            unset($commissionData['modified']);
            /* Calculator price in bill info */
            $commissionData = $this->priceCalc($commissionData);
            $correspond = $this->commissionCorrespondService->getCorrespondWithoutAlias($commissionApp->commission_id, $commissionData);
            if ($this->checkModifiedCommission($commissionApp->commission_id, $modified)
                && $actionName == 'approval'
                && $this->editCommission($commissionApp->commission_id, $commissionData, $user, 0)) {
                $this->approvalRegistBillInfoAndInsertCorrespond($correspond, $commissionData, $commissionApp, $user, $resultsFlg);
            }

            if ($resultsFlg) {
                DB::commit();
            } else {
                DB::rollback();
            }
        } catch (\Exception $exception) {
            $resultsFlg = false;
            DB::rollback();
        };
        return $resultsFlg;
    }

    /**
     * @param array $commissionData
     */
    private function changeDataForAddDataForCommission(&$commissionData)
    {
        if (!empty($commissionData['commission_note_send_datetime'])) {
            $commissionData['commission_note_send_datetime'] = substr($commissionData['commission_note_send_datetime'], 0, strlen($commissionData['commission_note_send_datetime']) - 3);
        }
        if (!empty($commissionData['tel_commission_datetime'])) {
            $commissionData['tel_commission_datetime'] = substr($commissionData['tel_commission_datetime'], 0, strlen($commissionData['tel_commission_datetime']) - 3);
        }
    }

    /**
     * @param \App\Models\Base|null $commissionApp
     * @param array $commissionData
     * @return array
     */
    private function addDataForCommission($commissionApp, $commissionData)
    {
        // Add data from commission application to commission data
        if ($commissionApp->chg_deduction_tax_include) {
            $commissionData['deduction_tax_include'] = $commissionApp->deduction_tax_include;
        }
        if ($commissionApp->chg_irregular_fee_rate) {
            $commissionData['irregular_fee_rate'] = $commissionApp->irregular_fee_rate;
        }
        if ($commissionApp->chg_irregular_fee) {
            $commissionData['irregular_fee'] = $commissionApp->irregular_fee;
        }
        if ($commissionApp->chg_introduction_free) {
            $commissionData['introduction_free'] = $commissionApp->introduction_free;
        }
        if ($commissionApp->chg_irregular_fee_rate || $commissionApp->chg_irregular_fee) {
            $commissionData['irregular_reason'] = $commissionApp->irregular_reason;
        }
        if ($commissionApp->chg_ac_commission_exclusion_flg) {
            $commissionData['ac_commission_exclusion_flg'] = $commissionApp->ac_commission_exclusion_flg;
        }
        if ($commissionApp->chg_introduction_not) {
            $commissionData['introduction_not'] = $commissionApp->introduction_not;
        }
        // Change data
        $this->changeDataForAddDataForCommission($commissionData);
        return $commissionData;
    }

    /**
     * Calculator price tax
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param array $commissionData
     * @return array
     */
    private function priceCalc($commissionData = null)
    {
        /* Get tax rate */
        $taxRate = $this->setMTaxRates($commissionData['complete_date']);

        /* Add key m_tax_rate */
        $commissionData['m_tax_rate'] = [];
        /* Add ['m_tax_rate']['tax_rate'] = $taxRate['tax_rate'] */
        $commissionData['m_tax_rate']['tax_rate'] = $taxRate['tax_rate'];
        /* Add key insurance_price */

        if (!isset($commissionData['bill_info'])) {
            $commissionData['bill_info']['id'] = null;
            $commissionData['bill_info']['irregular_fee_rate'] = null;
            $commissionData['bill_info']['irregular_fee'] = null;
            $commissionData['bill_info']['bill_status'] = null;
            $commissionData['bill_info']['fee_target_price'] = null;
            $commissionData['bill_info']['fee_tax_exclude'] = null;
            $commissionData['bill_info']['fee_billing_date'] = null;
            $commissionData['bill_info']['total_bill_price'] = null;
            $commissionData['bill_info']['tax'] = null;
            $commissionData['bill_info']['insurance_price'] = null;
        }
        $commissionData['insurance_price'] = $commissionData['bill_info']['insurance_price'];

        $calcData = $this->calculateBillPrice($commissionData);

        $commissionData['corp_fee'] = $calcData['corp_fee'];
        $commissionData['construction_price_tax_exclude'] = $calcData['construction_price_tax_exclude'];
        $commissionData['construction_price_tax_include'] = $calcData['construction_price_tax_include'];
        $commissionData['deduction_tax_exclude'] = $calcData['deduction_tax_exclude'];
        $commissionData['deduction_tax_include'] = $calcData['deduction_tax_include'];
        $commissionData['confirmd_fee_rate'] = $calcData['confirmd_fee_rate'];

        $commissionData['bill_info']['fee_target_price'] = $calcData['bill_info']['fee_target_price'];
        $commissionData['bill_info']['fee_tax_exclude'] = $calcData['bill_info']['fee_tax_exclude'];
        $commissionData['bill_info']['total_bill_price'] = $calcData['bill_info']['total_bill_price'];
        $commissionData['bill_info']['tax'] = $calcData['bill_info']['tax'];
        $commissionData['bill_info']['insurance_price'] = $calcData['bill_info']['insurance_price'];

        return $commissionData;
    }

    /**
     * Get commission_info by $commissionId
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $commissionId
     * @return mixed
     */
    private function setDataForCommission($commissionId = null)
    {
        $commissionInfo = $this->commissionInfoRepo->getCommissionInfoByIdForApproval($commissionId);
        if (!empty($commissionInfo['demandInfo']['auctionInfo']['id'])) {
            $commissionInfo = $this->setAuctionCommission($commissionInfo['demandInfo']['auctionInfo']['id'], $commissionInfo);
        }
        $commissionInfo = $this->commissionSupportService->setSupport($commissionId, $commissionInfo);

        return $commissionInfo;
    }

    /**
     * Find and add bill_info in $data if $auctionId not empty
     *
     * @param integer $auctionId
     * @param array $data
     * @return array
     */
    private function setAuctionCommission($auctionId, $data)
    {
        if (empty($auctionId)) {
            return $data;
        }

        $billInfo = $this->billRepo->findBy("auction_id", $auctionId);
        $data['bill_info'] = ($billInfo !== null) ? $billInfo->toArray() : [];
        return $data;
    }

    /**
     * Get tax rate in table m_tax_rates by date
     * return array[tax_rate, tax_rate_val] with tax_rate_val is real value of tax_rate
     * and tax_rate = tax_rate*100
     *
     * @param string $date
     * @return array
     */
    private function setMTaxRates($date = null)
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $result = $this->mTaxRateRepo->findByDate($date);
        if ($result) {
            $result = $result->toArray();
            $result['tax_rate_val'] = $result['tax_rate'];
            $result['tax_rate'] *= 100;

            return $result;
        } else {
            return ['tax_rate' => '', 'tax_rate_val' => ''];
        }
    }

    /**
     * @param array $commissionData
     * @param array $taxRate
     * @param float $constructionPriceTaxExclude
     */
    private function setDataForCalBillPriceCommissionStatusTaxRateVal(&$commissionData, &$taxRate, &$constructionPriceTaxExclude)
    {
        if (array_key_exists('tax_rate_val', $taxRate) && $taxRate['tax_rate_val'] != '') {
            if (!empty($commissionData['construction_price_tax_exclude'])) {
                $commissionData['construction_price_tax_include'] = round($constructionPriceTaxExclude * (1 + $taxRate['tax_rate_val']));
            } else {
                $commissionData['construction_price_tax_include'] = $commissionData['construction_price_tax_exclude'];
            }
            if (!empty($commissionData['deduction_tax_include'])) {
                $commissionData['deduction_tax_exclude'] = round($commissionData['deduction_tax_include'] / (1 + $taxRate['tax_rate_val']));
            } else {
                $commissionData['deduction_tax_exclude'] = 0;
            }
        } else {
            $commissionData['construction_price_tax_include'] = $commissionData['construction_price_tax_exclude'];
            $commissionData['deduction_tax_exclude'] = $commissionData['deduction_tax_include'];
        }
    }

    /**
     * @param array $commissionData
     * @param array $taxRate
     */
    private function setDataForCalBillPriceCommissionStatus(&$commissionData, &$taxRate)
    {
        if ($commissionData['commission_status'] != getDivValue('construction_status', 'introduction')) {
            $constructionPriceTaxExclude = $commissionData['construction_price_tax_exclude'];
            if (empty($constructionPriceTaxExclude)) {
                $constructionPriceTaxExclude = 0;
            }
            if (empty($commissionData['business_trip_amount'])) {
                $commissionData['business_trip_amount'] = 0;
            }
            if (empty($commissionData['deduction_tax_include'])) {
                $commissionData['deduction_tax_include'] = 0;
            }

            /* Update key construction_price_tax_include and deduction_tax_exclude*/
            $this->setDataForCalBillPriceCommissionStatusTaxRateVal($commissionData, $taxRate, $constructionPriceTaxExclude);

            if (empty($commissionData['deduction_tax_exclude'])) {
                $commissionData['deduction_tax_exclude'] = 0;
            }

            /* Update $data['bill_info']['fee_target_price'] */
            if ($constructionPriceTaxExclude != 0) {
                $commissionData['bill_info']['fee_target_price'] = $constructionPriceTaxExclude - $commissionData['deduction_tax_exclude'];
            } else {
                $commissionData['bill_info']['fee_target_price'] = 0;
            }
            /* Update $data['bill_info']['insurance_price'] */
            if ($commissionData['demand_info']['m_genres']['insurant_flg'] == 1 && $commissionData['affiliation_info']['liability_insurance'] == 2) {
                $commissionData['bill_info']['insurance_price'] = round($constructionPriceTaxExclude * 0.01);
            } else {
                $commissionData['bill_info']['insurance_price'] = 0;
            }
        }
    }

    /**
     * @param array $commissionData
     */
    private function setDataForCallBillPriceConfirmdFeeRate(&$commissionData)
    {
        if (!empty($commissionData['irregular_fee_rate'])) {
            $commissionData['confirmd_fee_rate'] = $commissionData['irregular_fee_rate'];
        } else {
            if (empty($commissionData['confirmd_fee_rate'])) {
                $commissionData['confirmd_fee_rate'] = $commissionData['commission_fee_rate'];
            }
        }
        /* Update $data['bill_info']['fee_tax_exclude'] */
        if (!empty($commissionData['irregular_fee'])) {
            $commissionData['bill_info']['fee_tax_exclude'] = $commissionData['irregular_fee'];
        } else {
            $commissionData['bill_info']['fee_tax_exclude'] = round($commissionData['bill_info']['fee_target_price'] * $commissionData['confirmd_fee_rate'] * 0.01);
        }
        /* Update $data['corp_fee'] */
        if (!empty($commissionData['bill_info']['fee_tax_exclude'])) {
            $commissionData['corp_fee'] = $commissionData['bill_info']['fee_tax_exclude'];
        }
    }

    /**
     * @param array $commissionData
     */
    private function setDataForCallBillPriceBillInfoFeeTaxExclude(&$commissionData)
    {
        if (!empty($commissionData['irregular_fee'])) {
            $commissionData['bill_info']['fee_tax_exclude'] = $commissionData['irregular_fee'];
        } else {
            $commissionData['bill_info']['fee_tax_exclude'] = $commissionData['corp_fee'];
        }
        /* If $data['commission_status'] == 5 */
        if ($commissionData['commission_status'] == getDivValue('construction_status', 'introduction')) {
            $commissionData['bill_info']['fee_target_price'] = $commissionData['bill_info']['fee_tax_exclude'];
            if ($commissionData['introduction_free'] == 1) {
                $commissionData['bill_info']['fee_tax_exclude'] = 0;
            }
        }
    }

    /**
     * @param array $commissionData
     */
    private function setDataForCalBillPriceOrderFeeUnit(&$commissionData)
    {
        if (!isset($commissionData['order_fee_unit']) || is_null($commissionData['order_fee_unit'])) {
            if (!isset($commissionData['m_corp_category']['order_fee_unit']) || is_null($commissionData['m_corp_category']['order_fee_unit'])) {
                $defaultCategory = $this->mCategoryRepo->find($commissionData['demand_info']['category_id']);
                if ($defaultCategory) {
                    $commissionData['order_fee_unit'] = $defaultCategory->category_default_fee_unit;
                }
            } else {
                $commissionData['order_fee_unit'] = $commissionData['m_corp_category']['order_fee_unit'];
            }
        }
    }

    /**
     * Update item in $data by get tax rate
     *
     * @param array $commissionData
     * @return mixed
     */
    private function calculateBillPrice($commissionData)
    {
        /* Get tax rate */
        $taxRate = $this->setMTaxRates($commissionData['complete_date']);
        $commissionData['m_tax_rate']['tax_rate'] = $taxRate['tax_rate'];
        $this->setDataForCalBillPriceCommissionStatus($commissionData, $taxRate);

        /* Update $data['order_fee_unit'] if null */
        $this->setDataForCalBillPriceOrderFeeUnit($commissionData);

        if ($commissionData['order_fee_unit'] != 0 && $commissionData['commission_status'] != getDivValue('construction_status', 'introduction')) {
            /* Update $data['confirmd_fee_rate'] */
            $this->setDataForCallBillPriceConfirmdFeeRate($commissionData);
        } else {
            /* Update $data['bill_info']['fee_tax_exclude'] */
            $this->setDataForCallBillPriceBillInfoFeeTaxExclude($commissionData);
        }

        /* Update $data['bill_info']['tax'] */
        if (!empty($taxRate['tax_rate_val'])) {
            $commissionData['bill_info']['tax'] = round($commissionData['bill_info']['fee_tax_exclude'] * $taxRate['tax_rate_val']);
        } else {
            $commissionData['bill_info']['tax'] = 0;
        }

        /* Update $data['bill_info']['total_bill_price'] */
        $feeTaxExclude = !empty($commissionData['bill_info']['fee_tax_exclude']) ? $commissionData['bill_info']['fee_tax_exclude'] : 0;
        $commissionData['bill_info']['total_bill_price'] = $feeTaxExclude + $commissionData['bill_info']['tax'] + $commissionData['bill_info']['insurance_price'];

        return $commissionData;
    }

    /**
     * Check time modified commission_info
     *
     * @param integer $id
     * @param string $modified
     * @return boolean
     */
    private function checkModifiedCommission($id, $modified)
    {
        $result = $this->commissionInfoRepo->find($id);
        if ($result && $modified == $result->modified) {
            return true;
        }

        return false;
    }

    /**
     * @param integer $id
     * @param array $data
     * @param object $user
     * @param int $hiddenLastUpdate
     * @return bool
     */
    private function editCommission($id = null, $data = [], $user = null, $hiddenLastUpdate = 0)
    {
        $data['id'] = $id;
        $commissionInfo = $this->commissionInfoRepo->find($id);
        if ($user->auth != "affiliation") {
            $oldData = $commissionInfo->toArray();
            $oldStatus = $oldData['commission_status'];
            $newStatus = $data['commission_status'];

            if ($oldStatus != getDivValue('construction_status', 'progression') &&
                $newStatus == getDivValue('construction_status', 'progression')) {
                $data['reported_flg'] = 0;
            }
        }

        if (empty($data['first_commission'])) {
            $data['first_commission'] = 0;
        }
        if (empty($data['unit_price_calc_exclude'])) {
            $data['unit_price_calc_exclude'] = 0;
        }
        if (empty($data['commission_order_fail_reason'])) {
            $data['commission_order_fail_reason'] = 0;
        }

        $data['complete_date'] = str_replace("-", "/", $data['complete_date']);
        $data['order_fail_date'] = str_replace("-", "/", $data['order_fail_date']);

        if ($hiddenLastUpdate == 1) {
            $data['commission_status_last_updated'] = date("Y-m-d G:i:s");
        }

        $data['modified_user_id'] = auth()->user()->user_id;
        $data['modified'] = date('Y-m-d H:i:s');

        unset($data['demand_info']);
        unset($data['bill_info']);
        unset($data['affiliation_info']);
        unset($data['m_corp']);
        unset($data['m_tax_rate']);
        unset($data['insurance_price']);
        unset($data['m_corp_category']);
        if ($commissionInfo->update($data)) {
            return true;
        };

        return false;
    }


    /**
     * @param array $data
     * @param array $setData
     */
    private function setDataForRegistBullInfo(&$data, &$setData)
    {
        if (isset($data['deduction_tax_include'])) {
            $setData['deduction_tax_include'] = $data['deduction_tax_include'];
        }
        if (isset($data['deduction_tax_exclude'])) {
            $setData['deduction_tax_exclude'] = $data['deduction_tax_exclude'];
        }
        if (isset($data['irregular_fee_rate'])) {
            $setData['irregular_fee_rate'] = $data['irregular_fee_rate'];
        }
        if (isset($data['irregular_fee'])) {
            $setData['irregular_fee'] = $data['irregular_fee'];
        }
    }

    /**
     * @param integer $id
     * @param array $data
     * @return bool
     */
    private function registBillInfo($id = null, $data = [])
    {
        $setData = $data['bill_info'];
        if ($data['commission_status'] == getDivValue('construction_status', 'introduction') && $data['introduction_free'] == 1) {
            $setData['fee_target_price'] = 0;
            $setData['fee_tax_exclude'] = 0;
        }
        $setData['demand_id'] = $data['demand_id'];
        $setData['commission_id'] = $id;

        $this->setDataForRegistBullInfo($data, $setData);

        if (isset($data['confirmd_fee_rate'])) {
            $setData['comfirmed_fee_rate'] = $data['confirmd_fee_rate'];
        }

        $data['m_tax_rate']['tax_rate'] = (empty($data['m_tax_rate']['tax_rate'])) ? 0 : $data['m_tax_rate']['tax_rate'];
        $setData['tax'] = $setData['fee_tax_exclude'] * ($data['m_tax_rate']['tax_rate'] / 100);
        if (isset($data['insurance_price'])) {
            $setData['insurance_price'] = $data['insurance_price'];
        } else {
            $setData['insurance_price'] = 0;
        }

        $setData['total_bill_price'] = $setData['fee_tax_exclude'] + $setData['tax'] + $setData['insurance_price'];
        if (empty($data['bill_info']['id'])) {
            $setData['bill_status'] = 1;
            $setData['fee_payment_price'] = 0;
            $setData['fee_payment_balance'] = $setData['total_bill_price'];
        } else {
            if (empty($setData['fee_payment_price'])) {
                $setData['fee_payment_price'] = 0;
            }
            $setData['fee_payment_balance'] = $setData['total_bill_price'] - $setData['fee_payment_price'];
        }
        $setData['deduction_tax_exclude'] = isset($setData['deduction_tax_exclude']) ? intval($setData['deduction_tax_exclude']) : 0;
        $setData['fee_target_price'] = intval($setData['fee_target_price']);
        $setData['fee_tax_exclude'] = intval($setData['fee_tax_exclude']);
        $setData['tax'] = intval($setData['tax']);
        $setData['total_bill_price'] = intval($setData['total_bill_price']);
        $setData['fee_payment_balance'] = intval($setData['fee_payment_balance']);
        if(empty($setData['id'])) {
            unset($setData['id']);
            return $this->billRepo->insert($setData);
        } else {
            return $this->billRepo->getBlankModel()->where("id", $setData['id'])->update($setData);
        }
    }
}
