<?php
namespace App\Services\Commission;

use App\Services\BaseService;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MTaxRateRepositoryInterface;
use App\Repositories\DemandAttachedFileRepositoryInterface;
use App\Repositories\MTimeRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\VisitTimeRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\BillRepositoryInterface;
use Exception;

class CommissionDetailService extends BaseService
{

    /**
     *
     * @var CommissionInfoRepositoryInterface
     */
    private $commissionInfoRepository;

    /**
     *
     * @var BillRepositoryInterface
     */
    private $billRepository;

    /**
     * @var DemandInfoRepositoryInterface
     */
    private $demandInfoRepository;

    /**
     * @var MTaxRateRepositoryInterface
     */
    private $taxRateRepository;
    /**
     * @var DemandAttachedFileRepositoryInterface
     */
    private $demandAttachedFileRepository;
    /**
     * @var MTimeRepositoryInterface
     */
    private $timeRepository;
    /**
     * @var MSiteRepositoryInterface
     */
    private $siteRepository;
    /**
     * @var VisitTimeRepositoryInterface
     */
    private $visitTimeRepository;
    /**
     * @var CorrespondService
     */
    private $commissionCorrespondService;
    /**
     * CommissionDetailService constructor.
     *
     * @param CommissionInfoRepositoryInterface          $commissionInfoRepository
     * @param BillRepositoryInterface                    $billRepository
     * @param DemandInfoRepositoryInterface              $demandInfoRepository
     * @param MTaxRateRepositoryInterface                $mTaxRateRepository
     * @param DemandAttachedFileRepositoryInterface      $demandAttachedFileRepository
     * @param MTimeRepositoryInterface                   $mTimeRepository
     * @param MSiteRepositoryInterface                   $mSiteRepository
     * @param VisitTimeRepositoryInterface               $visitTimeRepository
     * @param CorrespondService                          $commissionCorrespondService
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        BillRepositoryInterface $billRepository,
        DemandInfoRepositoryInterface $demandInfoRepository,
        MTaxRateRepositoryInterface $mTaxRateRepository,
        DemandAttachedFileRepositoryInterface $demandAttachedFileRepository,
        MTimeRepositoryInterface $mTimeRepository,
        MSiteRepositoryInterface $mSiteRepository,
        VisitTimeRepositoryInterface $visitTimeRepository,
        CorrespondService $commissionCorrespondService
    ) {
            $this->commissionInfoRepository = $commissionInfoRepository;
            $this->billRepository = $billRepository;
            $this->demandInfoRepository = $demandInfoRepository;
            $this->taxRateRepository = $mTaxRateRepository;
            $this->demandAttachedFileRepository = $demandAttachedFileRepository;
            $this->timeRepository = $mTimeRepository;
            $this->siteRepository = $mSiteRepository;
            $this->visitTimeRepository = $visitTimeRepository;
            $this->commissionCorrespondService = $commissionCorrespondService;
    }

    /**
     * Update commission if exist data
     *
     * @param  integer $id
     * @return boolean
     * @throws Exception
     */
    public function updateCommissionData($id = null)
    {
        if (Auth::user()->auth == 'affiliation') {
            // get data commission
            $commissionData = $this->commissionInfoRepository->find($id)->toArray();

            // update checked_flg
            if(empty($commissionData['checked_flg'])){
                $data['id'] = $id;
                $data['checked_flg'] = getDivValue('checked_flg', 'sumi');

                DB::beginTransaction();
                if ($this->commissionInfoRepository->save($data)) {
                    DB::commit();
                } else {
                    DB::rollback();
                }
            }

            // only execute when exist commission data
            if (! empty($commissionData) && $commissionData['app_notread'] == 1) {
                $commissionData['id'] = $id;
                $commissionData['app_notread'] = 0;
                DB::beginTransaction();

                if ($this->commissionInfoRepository->save($commissionData)) {
                    DB::commit();
                } else {
                    DB::rollback();

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param integer $id
     * @return array
     */
    public function getCommissionData($id)
    {
        $result = $this->commissionInfoRepository->getCommissionInfoById($id);

        if (! empty($result['AuctionInfo__id'])) {
            $result = $this->getAuctionCommission($result['AuctionInfo__id'], $result);
        }

        return $result;
    }

    /**
     * @param string $date
     * @return float|int|string
     */
    public function getTaxRates($date = null)
    {
        $result = '';

        if (!empty($date)) {
            $row = $this->taxRateRepository->findByDate($date);
            $result = $row->tax_rate * 100;
        }

        return $result;
    }

    /**
     * @param string $date
     * @return array
     */
    public function getMTaxRates($date = null)
    {
        $result = [
            'tax_rate_val' => '',
            'tax_rate' => ''

        ];

        if (empty($date)) {
            $date = date('Y-m-d');
        }

        if (!empty($date)) {
            $row = $this->taxRateRepository->findByDate($date);

            if ($row) {
                $result['tax_rate_val'] = $row->tax_rate;
                $result['tax_rate'] = $row->tax_rate * 100;
            }
        }

        return $result;
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function getDemandAttachedFiles($id)
    {
        $demandAttachedFiles = $this->demandAttachedFileRepository->findByDemandId($id);

        return $demandAttachedFiles;
    }

    /**
     * Get detail demand attach file by id
     * @param integer $id
     * @return mixed
     */
    public function getDetailDemandAttachFile($id)
    {
        $demandAttachFile = $this->demandAttachedFileRepository->getFileDownload($id);

        return $demandAttachFile;
    }

    /**
     * Get default JBR info
     * @param array $data
     * @return array
     */
    public function getDefaultJbrInfo($data)
    {
        // Set initial value of JBR quotation status
        if (empty($data['DemandInfo__jbr_estimate_status'])) {
            switch ($data['DemandInfo__genre_id']) {
                case Config::get('datacustom.jbr_glass_genre_id'):
                    $data['DemandInfo__jbr_estimate_status'] = getDivValue('estimate_status', 'mikaisyu');
                    break;
                case Config::get('datacustom.jbr_moving_genre_id'):
                    $data['DemandInfo__jbr_estimate_status'] = '';
                    break;
                default:
                    $data['DemandInfo__jbr_estimate_status'] = '';
                    break;
            }
        }

        // Set initial value of receipt status
        if (empty($data['DemandInfo__jbr_receipt_status'])) {
            switch ($data['DemandInfo__genre_id']) {
                case Config::get('datacustom.jbr_glass_genre_id'):
                    $data['DemandInfo__jbr_receipt_status'] = getDivValue('receipt_status', 'mikaisyu');
                    break;
                case Config::get('datacustom.jbr_moving_genre_id'):
                    $data['DemandInfo__jbr_receipt_status'] = '';
                    break;
                default:
                    $data['DemandInfo__jbr_receipt_status'] = getDivValue('receipt_status', 'mikaisyu');
                    break;
            }
        }

        return $data;
    }

    /**
     * @param string $itemCategory
     * @return array
     */
    public function getDisclosureData($itemCategory)
    {
        $list = $this->timeRepository->getByItemCategory($itemCategory);

        return $list;
    }

    /**
     * @param integer $id
     * @return object | array
     */
    public function getMsiteById($id)
    {
        $result = $this->siteRepository->find($id);

        return $result;
    }

    /**
     * @param integer $id
     * @return object
     */
    public function getVisitTime($id)
    {
        $result = $this->visitTimeRepository->findById($id);

        return $result;
    }

    /**
     * @param integer $id
     * @param string $modified
     * @return bool
     */
    public function checkModifiedCommission($id, $modified)
    {
        $result = $this->commissionInfoRepository->find($id);

        if ($modified != $result->modified) {
            session()->flash('error', __('commission_detail.modified_not_check'));

            return false;
        }

        return true;
    }

    /**
     * @param integer  $id
     * @param array $data
     * @return bool
     */
    public function editCommission($id = null, $data = [])
    {
        $data['CommissionInfo__id'] = $id;

        $data = $this->setCommissionReportedFlag($id, $data);

        if (empty($data['CommissionInfo']['first_commission'])) {
            $data['CommissionInfo']['first_commission'] = 0;
        }
        if (empty($data['CommissionInfo']['unit_price_calc_exclude'])) {
            $data['CommissionInfo']['unit_price_calc_exclude'] = 0;
        }
        if (empty($data['CommissionInfo']['commission_order_fail_reason'])) {
            $data['CommissionInfo']['commission_order_fail_reason'] = 0;
        }

        $data['CommissionInfo']['complete_date'] = isset($data['CommissionInfo']['complete_date']) ? str_replace("-", "/", $data['CommissionInfo']['complete_date']) : '';
        $data['CommissionInfo']['order_fail_date'] = isset($data['CommissionInfo']['order_fail_date']) ? str_replace("-", "/", $data['CommissionInfo']['order_fail_date']) : '';

        if ($data['hidden_last_updated'] == 1) {
            $data['CommissionInfo']['commission_status_last_updated'] = date("Y-m-d G:i:s");
        }

        // 更新
        if ($this->commissionInfoRepository->save($data['CommissionInfo'])) {
            return true;
        }

        return false;
    }

    /**
     * @param $commissionId
     * @param $data
     * @return mixed
     */
    private function setCommissionReportedFlag($commissionId, $data)
    {
        $auth = Auth::user()->auth;

        if ($auth != 'affiliation') {
            $oldData = $this->getCommissionData($commissionId);
            $oldStatus = $oldData['CommissionInfo__commission_status'];
            $newStatus = $data['CommissionInfo']['commission_status'];

            if ($oldStatus != getDivValue('construction_status', 'progression') && $newStatus == getDivValue('construction_status', 'progression')) {
                $data['CommissionInfo__reported_flg'] = 0;
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function editDemand($data = [])
    {
        unset($data['DemandInfo']['demand_status']);

        $data['DemandInfo']['order_date'] = str_replace('-', '/', $data['DemandInfo']['order_date']);

        // 更新
        if ($this->demandInfoRepository->save($data['DemandInfo'])) {
            return true;
        }

        return false;
    }

    /**
     * @param integer  $commissionId
     * @param array $data
     * @return bool
     */
    public function registHistory($commissionId = null, $data = [])
    {
        $data['commission_id'] = $commissionId;

        if (!empty($data['responders']) || !empty($data['rits_responders']) || !empty($data['corresponding_contens'])) {
            if ($this->commissionCorrespondService->save($data)) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * @param integer  $id
     * @param array $data
     * @return bool
     */
    public function registBillInfo($id = null, $data = [])
    {
        $setData = $data['BillInfo'];

        if ($data['CommissionInfo']['commission_status'] == getDivValue('construction_status', 'introduction') && $data['CommissionInfo']['introduction_free'] == 1) {
            $setData['fee_target_price'] = 0;
            $setData['fee_tax_exclude'] = 0;
        }

        $setData['demand_id'] = $data['CommissionInfo']['demand_id'];
        $setData['commission_id'] = $id;
        $setData['deduction_tax_include'] = isset($data['CommissionInfo']['deduction_tax_include']) ? (int) $data['CommissionInfo']['deduction_tax_include'] : 0;
        $setData['deduction_tax_exclude'] = isset($data['CommissionInfo']['deduction_tax_exclude']) ? (int) $data['CommissionInfo']['deduction_tax_exclude'] : 0;
        $setData['irregular_fee_rate'] = isset($data['CommissionInfo']['irregular_fee_rate']) ? (int) $data['CommissionInfo']['irregular_fee_rate'] : null;
        $setData['irregular_fee'] = isset($data['CommissionInfo']['irregular_fee']) ? (int) $data['CommissionInfo']['irregular_fee'] : null;
        $setData['comfirmed_fee_rate'] = isset($data['CommissionInfo']['confirmd_fee_rate']) ? (int) $data['CommissionInfo']['confirmd_fee_rate'] : null;
        $setData['tax'] = round($setData['fee_tax_exclude'] * ($data['MTaxRate']['tax_rate'] / 100));
        $setData['insurance_price'] = isset($data['BillInfo']['insurance_price']) ? $data['BillInfo']['insurance_price'] : 0;
        $setData['total_bill_price'] = $setData['fee_tax_exclude'] + $setData['tax'] + $setData['insurance_price'];

        if (empty($data['BillInfo']['id'])) {
            $setData['bill_status'] = 1;
            $setData['fee_payment_price'] = 0;
            $setData['fee_payment_balance'] = $setData['total_bill_price'];
        } else {
            if (empty($setData['fee_payment_price'])) {
                $setData['fee_payment_price'] = 0;
            }
            $setData['fee_payment_balance'] = $setData['total_bill_price'] - $setData['fee_payment_price'];
        }

        if ($this->billRepository->insertData($setData)) {
            return true;
        }

        return false;
    }

    /**
     * @param integer $id
     * @param integer $demandId
     * @return bool
     */
    public function setOtherCommission($id = null, $demandId = null)
    {
        $result = false;
        $rows = $this->commissionInfoRepository->getByDemandId($demandId, $id);

        if (count($rows) == 0) {
            $result = true;
        } else {
            foreach ($rows as $row) {
                $saveData = [
                    'id' => $row['id'],
                    'demand_id' => $demandId,
                    'unit_price_calc_exclude' => 1
                ];

                $this->commissionInfoRepository->save($saveData);
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateUploadFileName($data)
    {
        try {
            $demandInfo = [
                'id' => $data['DemandInfo']['id']
            ];

            if (isset($data['DemandInfo']['jbr_estimate']['name'])) {
                $demandInfo['upload_estimate_file_name'] = $data['DemandInfo']['jbr_estimate']['name'];
            }

            if (isset($data['DemandInfo']['jbr_receipt']['name'])) {
                $demandInfo['upload_receipt_file_name'] = $data['DemandInfo']['jbr_receipt']['name'];
            }

            if (count($demandInfo) > 1) {
                $this->demandInfoRepository->save($demandInfo);
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param integer $id
     * @param array $data
     * @return bool
     */
    public function registCorrespond($id, $data)
    {
        $correspond = $this->commissionCorrespondService->getCorrespond($id, $data);
        $result = true;

        if (! empty($correspond)) {
            $dataCorrespond['corresponding_contens'] = $correspond;
            $dataCorrespond['responders'] = '自動登録[' . Auth::user()->user_name . ']';
            $dataCorrespond['rits_responders'] = null;
            $dataCorrespond['commission_id'] = $id;
            $dataCorrespond['created_user_id'] = 'system';
            $dataCorrespond['modified_user_id'] = 'system';
            $dataCorrespond['correspond_datetime'] = date('Y-m-d H:i:s');

            if (! $this->commissionCorrespondService->save($dataCorrespond)) {
                $result = false;
            }
        }

        return $result;
    }
    /**
     * @param integer $auctionId
     * @param array $data
     * @return array
     */
    private function getAuctionCommission($auctionId, $data)
    {
        if (empty($auctionId)) {
            return $data;
        }

        $auction = $this->billRepository->findByAuctionId($auctionId);
        $data['AuctionBillInfo__total_bill_price'] = isset($auction['AuctionBillInfo__total_bill_price']) ? $auction['AuctionBillInfo__total_bill_price'] : 0;

        return $data;
    }
}
