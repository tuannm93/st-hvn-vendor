<?php

namespace App\Services\Auction;

use App\Models\AuctionAgreementItem;
use App\Models\MTaxRate;
use App\Repositories\AccumulatedInformationsRepositoryInterface;
use App\Repositories\AffiliationAreaStatRepositoryInterface;
use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MTimeRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuctionSupportService extends BaseService
{
    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionInfoRepository;

    /**
     * @var MGenresRepositoryInterface
     */
    protected $genreRepo;

    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;

    /**
     * @var MTimeRepositoryInterface
     */
    protected $timeRepository;

    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $corpCategoryRepository;

    /**
     * @var AffiliationAreaStatRepositoryInterface
     */
    protected $affiliationAreaStatRepo;

    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepository;

    /**
     * @var AccumulatedInformationsRepositoryInterface
     */
    protected $accumulatedInfoRepo;

    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;

    /**
     * @var BaseAuctionSupportService
     */
    protected $baseSupportService;
    /**
     * AuctionSupportService constructor.
     *
     * @param AuctionInfoRepositoryInterface $auctionInfoRepository
     * @param DemandInfoRepositoryInterface $demandInfoRepository
     * @param MCorpCategoryRepositoryInterface $corpCategoryRepository
     * @param AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepo
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param BaseAuctionSupportService $baseSupportService
     */
    public function __construct(
        AuctionInfoRepositoryInterface $auctionInfoRepository,
        DemandInfoRepositoryInterface $demandInfoRepository,
        MCorpCategoryRepositoryInterface $corpCategoryRepository,
        AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepo,
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo,
        MCorpRepositoryInterface $mCorpRepository,
        BaseAuctionSupportService $baseSupportService
    ) {
        $this->auctionInfoRepository = $auctionInfoRepository;
        $this->demandInfoRepository = $demandInfoRepository;
        $this->corpCategoryRepository = $corpCategoryRepository;
        $this->affiliationAreaStatRepo = $affiliationAreaStatRepo;
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->accumulatedInfoRepo = $accumulatedInfoRepo;
        $this->baseSupportService = $baseSupportService;
        $this->mCorpRepository = $mCorpRepository;
    }

    /**
     * @param integer $auctionId
     * @return mixed|null $lastCommission
     */
    public function getCommissionDataSupport($auctionId)
    {
        $auction = $this->auctionInfoRepository->getById($auctionId);
        $demand = $this->demandInfoRepository->getDemandById($auction->demand_id);
        $max = $this->baseSupportService->siteRepository->findMaxLimit($demand);

        $currentNum = $this->auctionInfoRepository->countByIdAndCommissionCommitFlag($auctionId);

        if ($currentNum >= $max) {
            $lastCommission = $this->auctionInfoRepository->getByIdAndCommissionCommitFlag($auctionId);

            return $lastCommission;
        }

        return null;
    }

    /**
     * @return AuctionAgreementItem
     */
    public function getItemAuctionAgreement()
    {
        return AuctionAgreementItem::find(1)->item;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function editSupport($data)
    {
        try {
            $this->auctionInfoRepository->updateAuctionInfo($data);
            $demandData = $this->demandInfoRepository->getDemandById($data['demand_id'])->toarray();
            $resultCategoryData = $this->corpCategoryRepository->findByCorpIdAndGenreIdAndCategoryId($data['corp_id'], $demandData['genre_id'], $demandData['category_id']);
            $categoryData = isset($resultCategoryData) ? $resultCategoryData->toarray() : [];
            if (count($categoryData) == 0) {
                return false;
            }
            $createInfoCommission = $this->getInfoCommissionToCreate($categoryData, $data, $demandData);

            if (!$this->checkEmptyCommissionFeeRate($createInfoCommission) || !$this->checkCommissionIntroduce($createInfoCommission) || !$this->checkCommissionStatusComplete($createInfoCommission) || !$this->checkCommissionStatusOrderFail($createInfoCommission)) {
                return false;
            }
            DB::beginTransaction();
            //Edit commission
            $newCommission = $this->commissionInfoRepository->save($createInfoCommission)->toArray();
            //Edit demand info
            $this->updateDemandInfo($data['demand_id']);
            //Create bill info
            $auctionFee = $this->auctionInfoRepository->getAuctionFee($data['id']);
            $this->createBillInfo($newCommission, $data, $auctionFee);
            //Create auction agreement link
            if ($auctionFee > 0) {
                $this->createAuctionAgreementLink($newCommission, $data, $auctionFee);
            }
            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * @param array $categoryData
     * @param array $data
     * @param array $demandData
     * @return array
     */
    private function getInfoCommissionToCreate($categoryData, $data, $demandData)
    {
        $edit = [];
        if ($categoryData['corp_commission_type'] != 2) {
            $commissionType = getDivValue('commission_type', 'normal_commission');
            $commissionStatus = getDivValue('construction_status', 'progression');
        } else {
            $commissionType = getDivValue('commission_type', 'package_estimate');
            $commissionStatus = getDivValue('construction_status', 'introduction');
            $categoryData['order_fee'] = $categoryData['introduce_fee'];
            $categoryData['order_fee_unit'] = 0;

            $edit['confirmd_fee_rate'] = 100;
            $edit['commission_fee_rate'] = 100;
            $edit['complete_date'] = date('Y/m/d');
        }
        if (empty($categoryData['order_fee']) || is_null($categoryData['order_fee_unit'])) {
            $mcCategoryData = $this->baseSupportService->categoryRepository->getFeeData($demandData['category_id']);

            if (empty($mcCategoryData)) {
                $mcCategoryData = ['category_default_fee' => '', 'category_default_fee_unit' => ''];
            } else {
                $mcCategoryData = $mcCategoryData->toarray();
            }
            $categoryData['order_fee'] = $mcCategoryData['category_default_fee'];
            $categoryData['order_fee_unit'] = $mcCategoryData['category_default_fee_unit'];
            $categoryData['note'] = '';
        }
        $edit['demand_id'] = $data['demand_id'];
        $edit['corp_id'] = $data['corp_id'];
        $edit['commit_flg'] = 1;
        $edit['commission_type'] = $commissionType;
        $edit['commission_status'] = $commissionStatus;
        $edit['unit_price_calc_exclude'] = 0;
        $edit['commission_note_send_datetime'] = date('Y/m/d H:i', time());
        $edit['commission_visit_time_id'] = isset($data['visit_time_id']) ? $data['visit_time_id'] : 0;
        if ($categoryData['order_fee_unit'] == 0) {
            $edit['corp_fee'] = $categoryData['order_fee'];
        } elseif ($categoryData['order_fee_unit'] == 1) {
            $edit['commission_fee_rate'] = $categoryData['order_fee'];
        }
        $edit['business_trip_amount'] = !empty($data['business_trip_amount']) ? $data['business_trip_amount'] : 0;
        $affiliationAreaData = $this->affiliationAreaStatRepo->findByCorpIdAndGenerIdAndPrefecture($data['corp_id'], $demandData['genre_id'], $demandData['address1']);
        $affiliationAreaData = $this->changeTypeCollectionModel($affiliationAreaData);
        $edit['select_commission_unit_price_rank'] = $this->getSelectCommissionUnitPriceRank($affiliationAreaData['commission_unit_price_rank']);
        $edit['select_commission_unit_price'] = $this->getSelectCommissionUnitPrice($affiliationAreaData['commission_unit_price_category']);
        $edit['order_fee_unit'] = $categoryData['order_fee_unit'];

        return $edit;
    }

    /**
     * @param object $data
     * @return null
     */
    private function changeTypeCollectionModel($data)
    {
        if (!empty($data)) {
            return $data->toArray();
        }

        return null;
    }

    /**
     * @param array $data
     * @return string
     */
    private function getSelectCommissionUnitPriceRank($data)
    {
        return !empty($data) ? $data : '-';
    }

    /**
     * @param array $data
     * @return int
     */
    private function getSelectCommissionUnitPrice($data)
    {
        return !empty($data) ? $data : 0;
    }

    /**
     * @param integer $demandId
     */
    private function updateDemandInfo($demandId)
    {
        $this->demandInfoRepository->updateDemandData($demandId, [
            'push_stop_flg' => 1,
            'demand_status' => getDivValue('demand_status', 'information_sent'),
            'modified' => date(config('constant.FullDateTimeFormat'), time()),
            'modified_user_id' => Auth::user()->user_id,
        ]);
    }

    /**
     * @param array $newCommission
     * @param array $data
     * @param float $auctionFee
     */
    private function createBillInfo($newCommission, $data = null, $auctionFee = null)
    {
        if ($newCommission['commission_type'] == getDivValue('commission_type', 'package_estimate')) {
            $billInfo = $this->getBillInfoToCreate($newCommission, true);
            $this->baseSupportService->billInfoRepository->insertData($billInfo);
        }
        if ($auctionFee > 0) {
            $billInfo = $this->getBillInfoToCreate($newCommission, false, $data, $auctionFee);
            $this->baseSupportService->billInfoRepository->insertData($billInfo);
        }
    }

    /**
     * @param array $newCommission
     * @param boolean $hasTax
     * @param array $data
     * @param float $auctionFee
     * @return array
     */
    private function getBillInfoToCreate($newCommission, $hasTax, $data = null, $auctionFee = null)
    {
        $dataSave = [];
        $mTax = $this->getTaxRate();
        $taxRate = count($mTax) > 0 ? floatval($mTax['tax_rate']) : 0;

        if ($hasTax) {
            $dataSave['commission_id'] = $newCommission['id'];
            $dataSave['demand_id'] = $newCommission['demand_id'];
            $dataSave['bill_status'] = getDivValue('bill_status', 'not_issue');
            $dataSave['comfirmed_fee_rate'] = 100;
            $dataSave['fee_target_price'] = isset($newCommission['corp_fee']) ? $newCommission['corp_fee'] : 0;
            $dataSave['fee_tax_exclude'] = isset($newCommission['corp_fee']) ? $newCommission['corp_fee'] : 0;
            $dataSave['tax'] = floor($dataSave['fee_tax_exclude'] * $taxRate);
            $dataSave['total_bill_price'] = $dataSave['fee_tax_exclude'] + $dataSave['tax'];
            $dataSave['fee_payment_price'] = 0;
            $dataSave['fee_payment_balance'] = $dataSave['fee_tax_exclude'] + $dataSave['tax'];

            return $dataSave;
        }

        $dataSave['commission_id'] = $newCommission['id'];
        $dataSave['demand_id'] = $data['demand_id'];
        $dataSave['bill_status'] = 1;
        $dataSave['comfirmed_fee_rate'] = 100;
        $dataSave['fee_target_price'] = $auctionFee;
        $dataSave['fee_tax_exclude'] = $auctionFee;
        $dataSave['tax'] = intval($dataSave['fee_tax_exclude'] * $taxRate);
        $dataSave['total_bill_price'] = $dataSave['fee_tax_exclude'] + $dataSave['tax'];
        $dataSave['fee_payment_price'] = 0;
        $dataSave['fee_payment_balance'] = $dataSave['total_bill_price'];
        $dataSave['auction_id'] = $data['id'];

        return $dataSave;
    }

    /**
     * @return array
     */
    private function getTaxRate()
    {
        return MTaxRate::where('start_date', '<=', date('Y-m-d H:i:s'))->orWhere([
            ['end_date', '=', ''],
            ['end_date', '>=', date('Y-m-d H:i:s')],
        ])->first()->toarray();
    }

    /**
     * @param array $infoCommission
     * @return bool
     */
    private function checkCommissionIntroduce($infoCommission)
    {
        if ($infoCommission['commission_type'] == getDivValue('commission_type', 'normal_commission')) {
            if ($infoCommission['commission_status'] == getDivValue('construction_status', 'introduction')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check empty commission fee
     *
     * @param array $infoCommission
     * @return bool
     */
    private function checkEmptyCommissionFeeRate($infoCommission)
    {
        if (isset($infoCommission['commit_flg'])) {
            if (!empty($infoCommission['commit_flg'])) {
                if (empty($infoCommission['commission_fee_rate'])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param array $infoCommission
     * @return bool
     */
    private function checkCommissionStatusComplete($infoCommission)
    {
        if ($infoCommission ['commission_status'] != getDivValue('construction_status', 'construction') && $infoCommission ['commission_status'] != getDivValue('construction_status', 'introduction') && $infoCommission ['commission_status'] != getDivValue('construction_status', 'order_fail')) {
            if (!empty($infoCommission ['complete_date']) || !empty($infoCommission ['construction_price_tax_exclude'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $infoCommission
     * @return bool
     */
    private function checkCommissionStatusOrderFail($infoCommission)
    {
        if ($infoCommission ['commission_status'] != getDivValue('construction_status', 'order_fail')) {
            if (!empty($infoCommission ['order_fail_date'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $newCommission
     * @param array $data
     * @param integer $auctionFee
     */
    private function createAuctionAgreementLink($newCommission, $data, $auctionFee)
    {
        $userLoginId = Auth::user()->user_id;
        $auctionLink = $this->baseSupportService->auctionAgreementLink;

        $auctionLink->auction_id = $data['id'];
        $auctionLink->corp_id = $data['corp_id'];
        $auctionLink->auction_agreement_id = 1;
        $auctionLink->demand_id = $data['demand_id'];
        $auctionLink->commission_id = $newCommission['id'];
        $auctionLink->auction_fee = $auctionFee;
        $auctionLink->agreement_check = $data['agreement_check'];
        $auctionLink->responders = $data['responders'];
        $auctionLink->modified = date(config('constant.FullDateTimeFormat'), time());
        $auctionLink->created = date(config('constant.FullDateTimeFormat'), time());
        $auctionLink->created_user_id = $userLoginId;
        $auctionLink->modified_user_id = $userLoginId;
        $auctionLink->save();
    }

    /**
     * @param integer $idCorp
     * @return bool
     */
    public function isPopupStopFlag($idCorp)
    {
        $mCorp = $this->baseSupportService->corpRepository->findById($idCorp);

        if (empty($mCorp)) {
            return false;
        }

        $popupStopFlag = $mCorp[0]['popup_stop_flg'];
        if (!$popupStopFlag) {
            return false;
        }

        return true;
    }

    /**
     * @param integer $demandId
     * @param integer $corpId
     */
    public function updateAccumulatedInfoRegistDate($demandId, $corpId)
    {
        try {
            DB::beginTransaction();
            $listAcc = $this->accumulatedInfoRepo->getAllInfos($demandId);
            if (!empty($listAcc)) {
                foreach ($listAcc as $acc) {
                    if ($acc->corp_id == $corpId) {
                        $acc->bid_regist_date = date('Y-m-d H:i');
                        $acc->modified_user_id = $corpId;
                    } else {
                        $acc->refusal_date = date('Y-m-d H:i');
                        $acc->modified_user_id = 'SYSTEM';
                    }
                    $acc->save();
                }
                DB::commit();
            }
        } catch (\Exception $exception) {
            DB::rollback();
            Log::info($exception);
        }
    }

    /**
     * update jbr status
     * @param integer $corpId
     * @param array $data
     */
    public function updateJbrStatus($corpId, $data)
    {
        $this->mCorpRepository->updateCorp($corpId, $data);
    }
}
