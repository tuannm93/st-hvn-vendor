<?php

namespace App\Services\Commission;

use App\Helpers\MailHelper;
use App\Repositories\CommissionAppRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCommissionAlertSettingRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class CommissionDataService extends BaseService
{
    /**
     * @var CommissionAppRepositoryInterface
     */
    private $commissionAppRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    private $mCorpRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    private $mCategoryRepository;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    private $mCorpCategoryRepository;

    /**
     * @var CommissionDetailService
     */
    private $commissionDetailService;
    /**
     * @var CommissionFileService
     */
    private $commissionFileService;
    /**
     * @var CorrespondService;
     */
    private $commissionCorrespondService;
    /**
     * @var MCommissionAlertSettingRepositoryInterface
     */
    private $commissionAlertSettingRepo;
    /**
     * @var MUserRepositoryInterface
     */
    private $userRepository;

    /**
     * CommissionDataService constructor.
     *
     * @param CommissionAppRepositoryInterface $commissionAppRepository
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MCategoryRepositoryInterface $mCategoryRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     * @param \App\Repositories\MCommissionAlertSettingRepositoryInterface $commissionAlertSettingRepo
     * @param MUserRepositoryInterface $userRepository ;
     * @param CommissionDetailService $commissionDetailService
     * @param CommissionFileService $commissionFileService
     * @param \App\Services\Commission\CorrespondService $commissionCorrespondService
     */
    public function __construct(
        CommissionAppRepositoryInterface $commissionAppRepository,
        MCorpRepositoryInterface $mCorpRepository,
        MCategoryRepositoryInterface $mCategoryRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MCommissionAlertSettingRepositoryInterface $commissionAlertSettingRepo,
        MUserRepositoryInterface $userRepository,
        CommissionDetailService $commissionDetailService,
        CommissionFileService $commissionFileService,
        CorrespondService $commissionCorrespondService
    ) {
        $this->commissionAppRepository = $commissionAppRepository;
        $this->mCorpRepository = $mCorpRepository;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->commissionAlertSettingRepo = $commissionAlertSettingRepo;
        $this->userRepository = $userRepository;

        $this->commissionDetailService = $commissionDetailService;
        $this->commissionFileService = $commissionFileService;
        $this->commissionCorrespondService = $commissionCorrespondService;
    }

    /**
     * @param integer $commissionId
     * @return array
     */
    public function getApplicationData($commissionId)
    {
        $results = $this->commissionAppRepository->findByCommissionId($commissionId);
        $arrStatus = getDropList('申請');

        foreach ($results as $key => $val) {
            $results[$key]['Approval__status_disp'] = isset($arrStatus[$val['Approval__status']]) ? $arrStatus[$val['Approval__status']] : '';
        }

        return $results;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getDefaultFee($id)
    {
        $result = $this->mCategoryRepository->getDefaultFee($id);

        return $result;
    }

    /**
     * @param $affiliationId
     * @return bool
     */
    public function checkHaveEnoughData($affiliationId)
    {
        $mCorps = $this->mCorpRepository->findByAffiliationId($affiliationId);

        if ($this->checkMCorpBasicInfo($mCorps)) {
            return true;
        }

        if ($this->checkMCorpCoordinationMethod($mCorps)) {
            return true;
        }

        if ($this->checkMCorpCommissionType($mCorps)) {
            return true;
        }

        if ($this->checkMCorpSupport($mCorps)) {
            return true;
        }

        if ($this->checkMCorpContactable($mCorps)) {
            return true;
        }

        if ($this->checkMCorpCategory($affiliationId)) {
            return true;
        }

        return false;
    }

    /**
     * @param $mCorps
     * @return bool
     */
    private function checkMCorpBasicInfo($mCorps)
    {
        if (strlen($mCorps['MCorp__corp_person']) == 0) {
            return true;
        }

        if (strlen($mCorps['MCorp__responsibility']) == 0) {
            return true;
        }

        if (strlen($mCorps['MCorp__address1']) == 0) {
            return true;
        }

        if (strlen($mCorps['MCorp__address2']) == 0) {
            return true;
        }

        if (strlen($mCorps['MCorp__address3']) == 0) {
            return true;
        }

        if (strlen($mCorps['MCorp__tel1']) == 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $mCorps
     * @return bool
     */
    private function checkMCorpCoordinationMethod($mCorps)
    {
        if (strlen($mCorps['MCorp__coordination_method']) == 0) {
            return true;
        }
        $mailCheck = [
            getDivValue('coordination_method', 'mail_fax'),
            getDivValue('coordination_method', 'mail'),
            getDivValue('coordination_method', 'mail_app'),
            getDivValue('coordination_method', 'mail_fax_app'),
        ];
        if (in_array($mCorps['MCorp__coordination_method'], $mailCheck)) {
            if (empty($mCorps['MCorp__mailaddress_pc'])) {
                return true;
            }
        }

        $faxCheck = [
            getDivValue('coordination_method', 'mail_fax'),
            getDivValue('coordination_method', 'fax'),
            getDivValue('coordination_method', 'mail_fax_app'),
        ];
        if (in_array($mCorps['MCorp__coordination_method'], $faxCheck)) {
            if (empty($mCorps['MCorp__fax'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $mCorps
     * @return bool
     */
    private function checkMCorpCommissionType($mCorps)
    {
        if ($mCorps['MCorp__corp_commission_type'] != 2) {
            $mailCheck = [
                getDivValue('coordination_method', 'mail_fax'),
                getDivValue('coordination_method', 'mail'),
            ];
            if (in_array($mCorps['MCorp__coordination_method'], $mailCheck)) {
                if ($mCorps['MCorp__mobile_mail_none'] != 1 && empty($mCorps['MCorp__mailaddress_mobile'])) {
                    return true;
                }
            }
            $mailAppCheck = [
                getDivValue('coordination_method', 'mail_app'),
                getDivValue('coordination_method', 'mail_fax_app'),
            ];
            if (in_array($mCorps['MCorp__coordination_method'], $mailAppCheck)) {
                if (empty($mCorps['MCorp__mailaddress_mobile'])) {
                    return true;
                }
            }
            if (empty($mCorps['MCorp__commission_dial'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $mCorps
     * @return bool
     */
    private function checkMCorpSupport($mCorps)
    {
        if (($mCorps['MCorp__support24hour'] != 1) && ($mCorps['MCorp__available_time_other'] != 1)) {
            return true;
        }

        if (($mCorps['MCorp__support24hour'] == 1) && ($mCorps['MCorp__available_time_other'] == 1)) {
            return true;
        }

        if (($mCorps['MCorp__support24hour'] != 1) && empty($mCorps['MCorp__available_time_from'])) {
            return true;
        }

        if (($mCorps['MCorp__support24hour'] != 1) && empty($mCorps['MCorp__available_time_to'])) {
            return true;
        }

        return false;
    }

    /**
     * @param $mCorps
     * @return bool
     */
    private function checkMCorpContactable($mCorps)
    {
        if (($mCorps['MCorp__contactable_support24hour'] != 1) && ($mCorps['MCorp__contactable_time_other'] != 1)) {
            return true;
        }

        if (($mCorps['MCorp__contactable_support24hour'] == 1) && ($mCorps['MCorp__contactable_time_other'] == 1)) {
            return true;
        }

        if (($mCorps['MCorp__contactable_support24hour'] != 1) && empty($mCorps['MCorp__contactable_time_from'])) {
            return true;
        }

        if (($mCorps['MCorp__contactable_support24hour'] != 1) && empty($mCorps['MCorp__contactable_time_to'])) {
            return true;
        }
        return false;
    }

    /**
     * @param $affiliationId
     * @return bool
     */
    private function checkMCorpCategory($affiliationId)
    {
        $mCorpCategories = $this->mCorpCategoryRepository->findByAffiliationId($affiliationId);
        if (empty($mCorpCategories)) {
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @param $data
     * @param $files
     * @return bool|mixed|string
     */
    public function regist($id, $data, $files)
    {
        if ($this->commissionDetailService->checkModifiedCommission($id, $data['modified'])) {
            try {
                DB::beginTransaction();

                $correspond = $this->commissionCorrespondService->getCorrespond($id, $data);
                $resultFlg = $this->commissionDetailService->editCommission($id, $data);

                if ($resultFlg) {
                    $resultFlg = $this->commissionDetailService->editDemand($data);
                }

                if ($resultFlg && isset($data['CommissionCorrespond'])) {
                    $resultFlg = $this->commissionDetailService->registHistory($id, $data['CommissionCorrespond']);
                }

                if ($resultFlg && !empty($correspond)) {
                    $dataCorrespond = $data['CommissionCorrespond'];

                    $dataCorrespond['corresponding_contens'] = $correspond;
                    $dataCorrespond['responders'] = '自動登録[' . Auth::user()->user_name . ']';
                    $dataCorrespond['commission_id'] = $id;
                    $dataCorrespond['created_user_id'] = 'system';
                    $dataCorrespond['modified_user_id'] = 'system';
                    $dataCorrespond['correspond_datetime'] = date('Y-m-d H:i:s');
                    $dataCorrespond['rits_responders'] = null;

                    $resultFlg = $this->commissionCorrespondService->save($dataCorrespond);
                }

                if ($resultFlg) {
                    if ($data['CommissionInfo']['commission_status'] == getDivValue(
                        'construction_status',
                        'construction'
                    ) || $data['CommissionInfo']['commission_status'] == getDivValue(
                        'construction_status',
                        'introduction'
                    )) {
                        $resultFlg = $this->commissionDetailService->registBillInfo($id, $data);
                    }
                }

                if ($resultFlg) {
                    if ($data['CommissionInfo']['commission_status'] == getDivValue(
                        'construction_status',
                        'construction'
                    )) {
                        $resultFlg = $this->commissionDetailService->setOtherCommission(
                            $id,
                            $data['CommissionInfo']['demand_id']
                        );
                    }
                }

                if ($resultFlg) {
                    $resultFlg = $this->commissionFileService->uploadFile($data['DemandInfo']['id'], $files, $data);
                }

                if ($resultFlg) {
                    $resultFlg = $this->commissionDetailService->updateUploadFileName($data);
                }

                //send mail
                $this->send($data, $id);

                if ($resultFlg) {
                    session()->flash('success', Lang::get('commission_corresponds.message_successfully'));
                    DB::commit();
                } else {
                    session()->flash('error', Lang::get('commission_corresponds.message_failure'));
                    DB::rollBack();
                }
            } catch (Exception $e) {
                logger(__METHOD__ . ': ' . $e->getMessage());
                session()->flash('error', Lang::get('commission_corresponds.message_failure'));
                DB::rollBack();
                $resultFlg = false;
            }
        } else {
            $resultFlg = false;
        }

        return $resultFlg;
    }

    /**
     * @param $data
     * @param $id
     * @return bool
     */
    private function send($data, $id)
    {
        $from = env('CM_MAIL_FROM');
        $to = env('CM_MAIL_TO');
        $subject = '《' . $data['DemandInfo']['id'] . '》加盟店からの画像アップロードがありました。';
        $body = '取次ぎ管理のURL: ' . route('commission.detail', ['id' => $id]);
        $attachments = [];

        $prefix = storage_path('upload/');
        $estimatePath = $prefix . 'estimate/';
        $receiptPath = $prefix . 'receipt/';

        if(!is_dir($prefix)){
            mkdir($prefix, 0777, true);
        }

        if(!is_dir($estimatePath)){
            mkdir($estimatePath, 0777, true);
        }

        if(!is_dir($receiptPath)){
            mkdir($receiptPath, 0777, true);
        }

        if (isset($data['DemandInfo']['jbr_estimate']['name'])) {
            $fileId = 'estimate_' . $data['DemandInfo']['id'];
            $currentFile = $this->commissionFileService->findFileByFileId($estimatePath, $fileId);
            $this->commissionFileService->imageResize(
                $estimatePath,
                $currentFile,
                $data['DemandInfo']['jbr_estimate']['name'],
                1000000
            );
            $attachments[] = $estimatePath . $data['DemandInfo']['jbr_estimate']['name'];
        }

        if (isset($data['DemandInfo']['jbr_receipt']['name'])) {
            $fileId = 'receipt_' . $data['DemandInfo']['id'];
            $currentFile = $this->commissionFileService->findFileByFileId($receiptPath, $fileId);
            $this->commissionFileService->imageResize(
                $receiptPath,
                $currentFile,
                $data['DemandInfo']['jbr_receipt']['name'],
                1000000
            );
            $attachments[] = $receiptPath . $data['DemandInfo']['jbr_receipt']['name'];
        }

        if (count($attachments) == 0) {
            return false;
        }

        MailHelper::sendAttachMail($from, $to, $subject, $body, $attachments);

        foreach ($attachments as $f) {
            @unlink($f);
        }
    }

    /**
     * @param null $correspondStatus
     * @param $phaseId
     * @return mixed
     */
    public function mCommissionAlertSettings($correspondStatus = null, $phaseId = null)
    {
        $alertSetting = $this->commissionAlertSettingRepo->findByPhaseId($phaseId, $correspondStatus);

        return $alertSetting;
    }

    /**
     * @return array
     */
    public function getMUsers()
    {
        $rows = $this->userRepository->getListUserNotAffiliation();
        $results = [];

        foreach ($rows as $row) {
            $results[$row['id']] = $row['user_name'];
        }

        return $results;
    }

    /**
     * Get list div value
     * @return array
     */
    public function getDivValueList()
    {
        $result = [
            'introduction' => getDivValue('construction_status', 'introduction'),
            'order_fail' => getDivValue('construction_status', 'order_fail'),
            'construction' => getDivValue('construction_status', 'construction'),
            'auction_selection' => getDivValue('selection_type', 'auction_selection'),
            'automatic_auction_selection' => getDivValue('selection_type', 'automatic_auction_selection'),
            'without' => getDivValue('auction_masking', 'without'),
            'all_exclusion' => getDivValue('auction_masking', 'all_exclusion'),
            'package_estimate' => getDivValue('commission_type', 'package_estimate'),
            'payment' => getDivValue('bill_status', 'payment'),
            'normal_commission' => getDivValue('commission_type', 'normal_commission'),
            'tel_correspond_status' => getDivValue('tel_correspond_status', config('constant.M_ITEM.LOSS_SUPPORT')),
            'visit_correspond_status' => getDivValue('visit_correspond_status', config('constant.M_ITEM.LOSS_SUPPORT')),
            'order_correspond_status' => getDivValue('order_correspond_status', config('constant.M_ITEM.CANCEL_SUPPORT')),
            'progression' => getDivValue('construction_status', 'progression'),
        ];

        return $result;
    }

    /**
     * Get drop list
     * @return array
     */
    public function getDropList()
    {
        $result = [
            'irregular_reason' => getDropList(config('constant.M_ITEM.IRREGULAR_REASON')),
            'commission_status' => getDropList(config('constant.M_ITEM.ITEM_CATEGORY')),
            'commission_order_fail_reason' => getDropList(config('constant.M_ITEM.REASON_FOR_LOSING_CONSENT')),
            'reform_upsell_ic' => getDropList(config('constant.M_ITEM.REFORM_UP_CELL_IC')),
            'jbr_estimate_status' => getDropList(config('constant.M_ITEM.JBR_ESTIMATE_STATUS')),
            'jbr_receipt_status' => getDropList(config('constant.M_ITEM.JBR_RECEIPT_STATUS')),
            'tel_correspond_status' => getDropList(config('constant.M_ITEM.TELEPHONE_SUPPORT_STATUS')),
            'tel_order_fail_reason' => getDropList(config('constant.M_ITEM.COMMISSION_TEL_SUPPORTS_ORDER_FAIL_REASON')),
            'visit_correspond_status' => getDropList(config('constant.M_ITEM.VISIT_SUPPORT_STATUS')),
            'visit_order_fail_reason' => getDropList(config('constant.M_ITEM.COMMISSION_VISIT_SUPPORTS_ORDER_FAIL_REASON')),
            'order_correspond_status' => getDropList(config('constant.M_ITEM.ORDER_SUPPORT_STATUS')),
            'order_order_fail_reason' => getDropList(config('constant.M_ITEM.COMMISSION_ORDER_SUPPORTS_ORDER_FAIL_REASON')),
        ];

        return $result;
    }

    /**
     * Compare data before response
     * @param $data
     * @return mixed
     */
    public function compareBeforeResponse($data)
    {
        $items = [
            'DemandInfo',
            'CommissionInfo',
            'CommissionCorrespond',
            'BillInfo'
        ];

        foreach ($data['data'] as $key => $val) {
            $data['data'][$key] = $this->convertToEmpty($val);
        }

        foreach ($data as $key => $val) {
            $data[$key] = $this->convertToEmpty($val);
        }

        foreach ($items as $item) {
            if (array_key_exists($item, $data['data'])) {
                foreach ($data['data'][$item] as $key => $val) {
                    $data['data'][$item][$key] = $this->convertToEmpty($val);
                }
            }
        }

        return $data;
    }

    /**
     * Convert null to empty
     * @param $val
     * @return string
     */
    private function convertToEmpty($val)
    {
        return is_null($val) ? '' : $val;
    }
}
