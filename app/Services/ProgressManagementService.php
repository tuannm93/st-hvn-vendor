<?php

namespace App\Services;

use App\Helpers\MailHelper;
use App\Models\ProgCorp;
use App\Models\ProgDemandInfo;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\ProgAddDemandInfoLogRepositoryInterface;
use App\Repositories\ProgAddDemandInfoRepositoryInterface;
use App\Repositories\ProgCorpRepositoryInterface;
use App\Repositories\ProgDemandInfoLogRepositoryInterface;
use App\Repositories\ProgDemandInfoRepositoryInterface;
use App\Services\Log\CorpLogService;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProgressManagementService
{
    /**
     * @var ProgDemandInfoRepositoryInterface
     */
    public $progDemandInfoRepo;

    /**
     * @var ProgAddDemandInfoRepositoryInterface
     */
    public $progAddDemandInfoRepo;

    /**
     * @var MItemRepositoryInterface
     */
    public $mItemRepo;

    /**
     * @var CommissionInfoRepositoryInterface
     */
    public $comInfoRepo;

    /**
     * @var ProgDemandInfoLogRepositoryInterface
     */
    public $progDemandInfoLog;

    /**
     * @var ProgAddDemandInfoLogRepositoryInterface
     */
    public $progAddDemandInfoLog;


    /**
     * @var integer
     */
    public $paginate = 500; // 500

    /**
     * @var CorpLogService
     */
    public $log;

    /**
     * @var ProgCorpRepositoryInterface
     */
    protected $progCorpRepository;

    /**
     * ProgressManagementService constructor.
     * @param ProgDemandInfoRepositoryInterface $progDemandInfoRepo
     * @param ProgAddDemandInfoRepositoryInterface $progAddDemandInfoRepo
     * @param MItemRepositoryInterface $mItemRepo
     * @param CommissionInfoRepositoryInterface $comInfoRepo
     * @param ProgCorpRepositoryInterface $progCorpRepository
     * @param ProgDemandInfoLogRepositoryInterface $progDemandInfoLog
     * @param ProgAddDemandInfoLogRepositoryInterface $progAddDemandInfoLog
     * @param CorpLogService $log
     */
    public function __construct(
        ProgDemandInfoRepositoryInterface $progDemandInfoRepo,
        ProgAddDemandInfoRepositoryInterface $progAddDemandInfoRepo,
        MItemRepositoryInterface $mItemRepo,
        CommissionInfoRepositoryInterface $comInfoRepo,
        ProgCorpRepositoryInterface $progCorpRepository,
        ProgDemandInfoLogRepositoryInterface $progDemandInfoLog,
        ProgAddDemandInfoLogRepositoryInterface $progAddDemandInfoLog,
        CorpLogService $log
    ) {
        $this->progDemandInfoRepo = $progDemandInfoRepo;
        $this->progAddDemandInfoRepo = $progAddDemandInfoRepo;
        $this->mItemRepo = $mItemRepo;
        $this->comInfoRepo = $comInfoRepo;
        $this->progCorpRepository = $progCorpRepository;
        $this->progDemandInfoLog = $progDemandInfoLog;
        $this->log = $log;
        $this->progAddDemandInfoLog = $progAddDemandInfoLog;
    }

    /**
     * get demand detail with all relationship
     * @param  integer $progCorpId prog_corp_id
     * @return mixed list of demand infos
     */
    public function adminDemandDetail($progCorpId)
    {

        if (! is_numeric($progCorpId)) {
            return [];
        }

        return $this->progDemandInfoRepo->getProgDemandInfoByProgCorpId($progCorpId)
                ->orderBy('receive_datetime', 'ASC')
                ->paginate($this->paginate);
    }

    /**
     * get guide title
     * @param $flag
     * @return array|null|string
     */
    public function getGuideTitle($flag)
    {
        $title = __('progress_management.guide_alert');
        if ($flag == ProgCorp::MAIL_COLLECTED) {
            $title .= '<br />'.__('progress_management.mail_collected_alert');
        }

        return $title;
    }

    /**
     * @param $lengthAwarePaginator
     * @return string
     */
    public function getPageInfo($lengthAwarePaginator)
    {
        $pageInfo = __('progress_management.total_items').': '.$lengthAwarePaginator->total();
        $pageInfo .= __('progress_management.items');
        if ($lengthAwarePaginator->total() < $lengthAwarePaginator->perPage()) {
            if ($lengthAwarePaginator->total() == 0) {
                $pageInfo .= ' 0' . trans('common.wavy_seal') . $lengthAwarePaginator->total();
            } else {
                $pageInfo .= ' 1' . trans('common.wavy_seal') . $lengthAwarePaginator->total();
            }
        } else {
            $currentRow = (($lengthAwarePaginator->currentPage() - 1) * $lengthAwarePaginator->perPage() + 1);
            $pageInfo .= $currentRow . trans('common.wavy_seal');
            if ($lengthAwarePaginator->total() - $currentRow < $lengthAwarePaginator->perPage()) {
                $pageInfo .= $lengthAwarePaginator->total();
            } else {
                $pageInfo .= $lengthAwarePaginator->perPage() + $currentRow -1;
            }
        }

        $pageInfo .= ' '.__('progress_management.display').' ('.$lengthAwarePaginator->currentPage();
        $pageInfo .= ' / '.$lengthAwarePaginator->lastPage().' '.__('progress_management.page').')';

        return $pageInfo;
    }

    /**
     * get user agent
     * @param  Request $request http request
     * @return array info of user
     */
    public function getUserAgent($request)
    {
        $userUpdateInfo['ip_address_update'] = $request->ip();
        $userUpdateInfo['user_agent_update'] = $request->header('User-Agent');
        $userUpdateInfo['modified'] = date('Y-m-d H:i:s');
        $userUpdateInfo['modified_user_id'] = auth()->user()->user_id;

        return $userUpdateInfo;
    }

    /**
     * @param $category
     * @return array
     */
    public function getMItemList($category)
    {
        if (empty($category)) {
            return [];
        }

        return $this->mItemRepo->getListByCategoryItem($category);
    }

    /**
     * update demand info
     *
     * @author thaihv
     * @param  integer $id   row id
     * @param  array   $data data
     * @return boolean
     */
    public function updateProgDemandInfo($id, $data)
    {
        return $this->progDemandInfoRepo->update($id, $data);
    }

    /**
     * reacquisition
     *
     * @author thaihv
     * @param  integer $pDemandId      progress demand info id
     * @param  array   $userUpdateInfo
     * @return boolean               result of update
     */
    public function reacquisition($pDemandId, $userUpdateInfo = [])
    {
        $progDInfo = $this->progDemandInfoRepo->findById($pDemandId);
        $commissionInfo = $this->comInfoRepo->getWithRelationById($progDInfo->commission_id);
        if (! $commissionInfo || ! $progDInfo) {
            return false;
        }
        $newProgDInfo['corp_id'] = $commissionInfo->corp_id;
        $newProgDInfo['demand_id'] = $commissionInfo->demandInfo->id;
        $newProgDInfo['genre_name'] = $commissionInfo->demandInfo->mGenres->genre_name;
        $newProgDInfo['category_name'] = $commissionInfo->demandInfo->mCategory->category_name;
        $newProgDInfo['customer_name'] = $commissionInfo->demandInfo->customer_name;
        $newProgDInfo['receive_datetime'] = $commissionInfo->demandInfo->receive_datetime;
        $newProgDInfo['commission_status'] = $commissionInfo->commission_status;
        $newProgDInfo['commission_order_fail_reason'] = $commissionInfo->commission_order_fail_reason;

        $newProgDInfo = $this->setDataForProgDemandInfo($commissionInfo, $newProgDInfo);

        if ($commissionInfo->billInfos->first()) {
            $newProgDInfo['fee_target_price'] = $commissionInfo->billInfos->first()->fee_target_price;
            $newProgDInfo['fee_billing_date'] = $commissionInfo->billInfos->first()->fee_billing_date;
        }
        $newProgDInfo = array_merge($userUpdateInfo, $newProgDInfo);
        $update = $this->progDemandInfoRepo->update($pDemandId, $newProgDInfo);
        if ($update) {
            $tmpProg = $progDInfo->rogDemandInfoTmps()->first();
            if ($tmpProg) {
                $progTmpData['corp_id'] = $progDInfo->corp_id;
                $progTmpData['commission_id'] = $progDInfo->commission_id;
                $progTmpData['demand_id'] = $progDInfo->demand_id;
                $progTmpData['fee_billing_date'] = $progDInfo->fee_billing_date;
                $progTmpData['genre_name'] = $progDInfo->genre_name;
                $progTmpData['receive_datetime'] = $progDInfo->receive_datetime;
                $progTmpData['category_name'] = $progDInfo->category_name;
                $progTmpData['customer_name'] = $progDInfo->customer_name;
                $progTmpData['fee'] = $progDInfo->fee;
                $progTmpData['fee_rate'] = $progDInfo->fee_rate;
                $progTmpData['fee_target_price'] = $progDInfo->fee_target_price;
                $progTmpData['commission_status'] = $progDInfo->commission_status;
                $progTmpData['commission_order_fail_reason'] = $progDInfo->commission_order_fail_reason;
                $progTmpData['complete_date'] = $progDInfo->complete_date;
                $progTmpData['construction_price_tax_exclude'] = $progDInfo->construction_price_tax_exclude;
                $progTmpData['construction_price_tax_include'] = $progDInfo->construction_price_tax_include;
                $progTmpData = array_merge($userUpdateInfo, $progTmpData);

                return $tmpProg->update($progTmpData);
            } else {
                return false;
            }
        }

        return $update;
    }

    /**
     * @param $commissionInfo
     * @param $newProgDInfo
     * @return mixed
     */
    private function setDataForProgDemandInfo($commissionInfo, $newProgDInfo)
    {
        if ($commissionInfo->commission_status == 3) {
            $newProgDInfo['complete_date'] = $commissionInfo->complete_date;
        } elseif ($commissionInfo->commission_status == 4) {
            $newProgDInfo['complete_date'] = $commissionInfo->order_fail_date;
        }
        $newProgDInfo['construction_price_tax_exclude'] = $commissionInfo->construction_price_tax_exclude;
        $newProgDInfo['construction_price_tax_include'] = $commissionInfo->construction_price_tax_include;
        $newProgDInfo['commission_id'] = $commissionInfo->id;
        //Initialization of fee
        $newProgDInfo['fee'] = null;
        $newProgDInfo['fee_rate'] = null;

        if ($commissionInfo->order_fee_unit == "0") {
            if ($commissionInfo->irregular_fee != 0 && $commissionInfo->irregular_fee != '') {
                // Irregular fee Amount (excluding tax) 【Unit: yen】
                $newProgDInfo['fee'] = $commissionInfo->irregular_fee;
            } else {
                //Brokerage commission 【Unit: yen】
                $newProgDInfo['fee'] = $commissionInfo->corp_fee;
            }
            // When the commission unit price is%
        } elseif ($commissionInfo->order_fee_unit == "1") {
            $newProgDInfo = $this->setDataByOrderFeeUnitIsPercentage($commissionInfo, $newProgDInfo);
        } elseif (is_null($commissionInfo->order_fee_unit)) {
            $newProgDInfo = $this->setDataByOrderFeeUnitIsNull($commissionInfo, $newProgDInfo);
        }

        return $newProgDInfo;
    }

    /**
     * @param $commissionInfo
     * @param $newProgDInfo
     * @return mixed
     */
    private function setDataByOrderFeeUnitIsPercentage($commissionInfo, $newProgDInfo)
    {
        if ($commissionInfo->irregular_fee != 0 && $commissionInfo->irregular_fee != '') {
            // Irregular fee Amount (excluding tax) 【Unit: yen】
            $newProgDInfo['fee'] = $commissionInfo->irregular_fee;
        } elseif ($commissionInfo->irregular_fee_rate != 0 && $commissionInfo->irregular_fee_rate != '') {
            // Irregular commission rate [Unit:%]
            $newProgDInfo['fee_rate'] = $commissionInfo->irregular_fee_rate;
        } else {
            // Commission rate at the time of transaction 【Unit:%】
            $newProgDInfo['fee_rate'] = $commissionInfo->commission_fee_rate;
        }
        return $newProgDInfo;
    }

    /**
     * @param $commissionInfo
     * @param $newProgDInfo
     * @return mixed
     */
    private function setDataByOrderFeeUnitIsNull($commissionInfo, $newProgDInfo)
    {
        // When category ID is not tied
        if ($commissionInfo->irregular_fee != 0 && $commissionInfo->irregular_fee != '') {
            // Irregular fee Amount (excluding tax) 【Unit: yen】
            $newProgDInfo['fee'] = $commissionInfo->irregular_fee;
        } elseif ($commissionInfo->irregular_fee_rate != 0 && $commissionInfo->irregular_fee_rate != '') {
            $newProgDInfo['fee_rate'] = $commissionInfo->irregular_fee_rate;
        } elseif ($commissionInfo->commission_fee_rate != 0 && $commissionInfo->commission_fee_rate != '') {
            $newProgDInfo['fee_rate'] = $commissionInfo->commission_fee_rate;
        } elseif ($commissionInfo->corp_fee != 0 && $commissionInfo->corp_fee != '') {
            $newProgDInfo['fee'] = $commissionInfo->corp_fee;
        }

        return $newProgDInfo;
    }

    /**
     * @param $value
     * @return bool
     */
    private function checkValue($value)
    {
        return (!empty($value['demand_id_update'])
            || !empty($value['customer_name_update'])
            || !empty($value['category_name_update'])
            || !empty($value['commission_datus_update'])
            || !empty($value['complete_date_update'])
            || !empty($value['construction_price_tax_exclude_update'])
            || !empty($value['comment_update'])
            || !empty($value['demand_type_update'])) ? true: false;
    }

    /**
     * @param $data
     * @return array|bool
     */
    public function insertUpdateProgAddDemandInfo($data)
    {
        // Validate data
        $dataToInsert = [];
        foreach ($data as $value) {
            if (empty($value['display'])) {
                continue;
            }
            unset($value['display']);
            if ($this->checkValue($value)) {
                $dataToInsert[] = $value;
            }
        }
        if (empty($dataToInsert)) {
            return false;
        }
        $idReturn = [];
        foreach ($dataToInsert as $k => $value) {
            if ((isset($value['id']))) {
                $id = $value['id'];
                $idReturn[] = $id;
                $this->progAddDemandInfoRepo->updateById($id, $value);
                unset($dataToInsert[$k]);
            } else {
                $dataToInsert[$k]['created'] = date('Y-m-d H:i:s');
                $dataToInsert[$k]['created_user_id'] = auth()->user()->user_id;
            }
        }
        if (! empty($dataToInsert)) {
            $ids = $this->progAddDemandInfoRepo->insertGetIds($dataToInsert);
            $idReturn = array_merge($ids, $idReturn);
        }
        return $idReturn;
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function getProgAddDemandByIds($ids)
    {
        return $this->progAddDemandInfoRepo->findByIds($ids);
    }

    /**
     * Insert prog_demand_infos data
     * @param array $request
     * @param integer $fileId
     * @return boolean
     * @throws Exception
     */
    public function postImportCommissionInfos($request, $fileId)
    {
        DB::beginTransaction();
        try {
            $commissionInfoIds = str_replace(["\r\n", "\r", "\n", "　", '', ' '], '', $request['commission_info_id']);
            $arrayId = explode(',', $commissionInfoIds);
            $commissionInfos = $this->comInfoRepo->getListByIds($arrayId);
            foreach ($commissionInfos as $commissionInfo) {
                $progCorp = $this->insertProgCorp($commissionInfo, $fileId);
                $this->insertProgDemandInfo($commissionInfo, $fileId, $progCorp, $request['commission_info_lock']);
            }
            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * insert and get prog_corps id
     *
     * @param  array   $data
     * @param  integer $fileId
     * @return mixed
     */
    public function insertProgCorp($data, $fileId)
    {
        $result = $this->progCorpRepository->findFirstByCorpIdAndFileId($data['corp_id'], $fileId);
        if (!$result) {
            $progCorp = $this->setDataToInsert($data, $fileId);
            $result['id'] = $this->progCorpRepository->insertGetIds($progCorp);
        }
        return $result;
    }

    /**
     * set data to insert prog_corps table
     *
     * @param  array   $progCorp
     * @param  integer $fileId
     * @return array
     */
    public function setDataToInsert($progCorp, $fileId)
    {
        $date = Carbon::now();
        $userId = Auth::user()['user_id'];
        $insert['corp_id'] = $progCorp['corp_id'];
        $insert['progress_flag'] = 1;
        $insert['mail_last_send_date'] = null;
        $insert['collect_date'] = null;
        $insert['sf_register_date'] = null;
        $insert['call_back_phone_flag'] = '1';
        $insert['note'] = '';
        $insert['unit_cost'] = $progCorp['construction_unit_price'];
        $insert['mail_count'] = 0;
        $insert['call_back_phone_date'] = null;
        $insert['fax_count'] = 0;
        $insert['fax_last_send_date'] = null;
        $insert['prog_import_file_id'] = $fileId;
        $insert['contact_type'] = $progCorp['prog_send_method'];
        $insert['fax'] = $progCorp['prog_send_fax'];
        $insert['mail_address'] = $progCorp['prog_send_mail_address'];
        $insert['irregular_method'] = $progCorp['prog_irregular'];
        $insert['created'] = $date;
        $insert['created_user_id'] = $userId;
        $insert['modified'] = $date;
        $insert['modified_user_id'] = $userId;
        return $insert;
    }

    /**
     * check exists data and insert data
     *
     * @param array   $commissionInfo
     * @param integer $fileId
     * @param integer $progCorpIds
     * @param integer $lock
     */
    public function insertProgDemandInfo($commissionInfo, $fileId, $progCorpIds, $lock)
    {
        $csvData = $this->progDemandInfoRepo->findByMulticondition($commissionInfo, $fileId);
        if (empty($csvData)) {
            $progDemandInfo = $this->setProgDemandInfoData($commissionInfo, $fileId, $progCorpIds);
            $this->progDemandInfoRepo->insert($progDemandInfo);
            if (!empty($lock) && $lock == 2) {
                DB::select("SELECT set_lock_status(" . $commissionInfo['commission_infos_id'] . ", 1);");
            }
        }
    }

    /**
     * @param $commissionInfo
     * @param $insert
     */
    private function switchOrderUnitCaseOne($commissionInfo, &$insert)
    {
        if ($commissionInfo['irregular_fee'] != "" && $commissionInfo['irregular_fee'] != 0) {
            $insert['fee'] = $commissionInfo['irregular_fee'];
        } elseif ($commissionInfo['irregular_fee_rate'] != "" && $commissionInfo['irregular_fee_rate'] != 0) {
            $insert['fee_rate'] = $commissionInfo['irregular_fee_rate'];
        } else {
            $insert['fee_rate'] = $commissionInfo['commission_fee_rate'];
        }
    }

    /**
     * set prog_demand_infos data to insert
     * @param  array   $commissionInfo
     * @param  integer $fileId
     * @param  integer $progCorpIds
     * @return array
     */
    public function setProgDemandInfoData($commissionInfo, $fileId, $progCorpIds)
    {
        $insert = [];
        $date = Carbon::now();
        $userId = Auth::user()['user_id'];
        $insert['corp_id'] = $commissionInfo['corp_id'];
        $insert['demand_id'] = $commissionInfo['demand_infos_id'];
        $insert['genre_name'] = $commissionInfo['genre_name'];
        $insert['category_name'] = $commissionInfo['category_name'];
        $insert['customer_name'] = $commissionInfo['customer_name'];
        $insert['commission_status_update'] = 0;
        $insert['diff_flg'] = 0;
        $insert['complete_date_update'] = "";
        $insert['construction_price_tax_exclude_update'] = "";
        $insert['construction_price_tax_include_update'] = "";
        $insert['comment_update'] = "";
        $insert['prog_import_file_id'] = $fileId;
        $insert['prog_corp_id'] = $progCorpIds['id'];
        $insert['commission_status'] = $commissionInfo['commission_status'];
        $insert['commission_order_fail_reason'] = $commissionInfo['commission_order_fail_reason'];
        if ($commissionInfo['commission_status'] == 3) {
            $insert['complete_date'] = $commissionInfo['complete_date'];
        } elseif ($commissionInfo['commission_status'] == 4) {
            $insert['complete_date'] = $commissionInfo['order_fail_date'];
        } else {
            $insert['complete_date'] = null;
        }
        $insert['construction_price_tax_exclude'] = $commissionInfo['construction_price_tax_exclude'];
        $insert['construction_price_tax_include'] = $commissionInfo['construction_price_tax_include'];
        $insert['commission_id'] = $commissionInfo['commission_infos_id'];
        $insert['agree_flag'] = 0;
        $insert['receive_datetime'] = $commissionInfo['receive_datetime'];
        switch ($commissionInfo['order_fee_unit']) {
            case '0':
                $insert['fee'] = ($commissionInfo['irregular_fee'] != "" && $commissionInfo['irregular_fee'] != 0)
                        ? $commissionInfo['irregular_fee'] : $commissionInfo['corp_fee'];
                break;
            case '1':
                $this->switchOrderUnitCaseOne($commissionInfo, $insert);
                break;
            case null:
                $this->switchOrderUnitCaseOne($commissionInfo, $insert);
                break;
            default:
                break;
        }
        $insert['fee_target_price'] = $commissionInfo['fee_target_price'];
        if (is_null($commissionInfo['fee_billing_date'])) {
            $insert['fee_billing_date'] = null;
        } else {
            $insert['fee_billing_date'] = $commissionInfo['fee_billing_date'];
        }
        $insert['created'] = $date;
        $insert['created_user_id'] = $userId;
        $insert['modified'] = $date;
        $insert['modified_user_id'] = $userId;

        return $insert;
    }

    /**
     * filter prog add demand info
     *
     * @author thaihv
     * @param  integer $pCorpId
     * @return collection
     */
    public function getProgAddDemandInfos($pCorpId)
    {
        $progAddData = $this->progAddDemandInfoRepo->getDataByProgCorpId($pCorpId);
        return $progAddData;
    }

    /**
     * Delete commission infos
     *
     * @param  $delTarget
     * @param  $fileId
     * @return array message
     */
    public function deleteCommissionInfos($delTarget, $fileId)
    {
        $delTarget = str_replace(["\r\n", "\r", "\n", "　", '', ' '], '', $delTarget);
        $arrayDeleteIds = explode(',', $delTarget);

        // Check list id input
        $delIdIsCorrect = true;
        $message = '';

        foreach ($arrayDeleteIds as $id) {
            if (! ctype_digit($id)) {
                $message = ['error' => trans('progress_management.delete_commission_infos.error_input_message')];
                $delIdIsCorrect = false;
            }
        }

        // Delete prog_demand
        if ($delIdIsCorrect) {
            logger(trans('progress_management.logger.progress_management_case_start'));
            logger(trans('progress_management.logger.execute_user').Auth::getUser()->id);
            logger(trans('progress_management.logger.agency_id').$delTarget);
            logger(trans('progress_management.logger.delete_commission_infos_start'));

            try {
                $this->progDemandInfoRepo->delProgDemand($arrayDeleteIds, $fileId);
                $message = ['success' => trans('progress_management.delete_commission_infos.success_message')];
                logger(trans('progress_management.logger.delete_successful'));
            } catch (Exception $e) {
                logger($e->getMessage());
                logger(trans('progress_management.logger.delete_error'));
                $message = ['error' => trans('progress_management.delete_commission_infos.error_delete_message')];
            }
            logger(trans('progress_management.logger.progress_management_case_end'));
        }

        return $message;
    }

    /**
     * @author thaihv
     * @param  integer $pDemandInfoId prog_demand_infos id
     * @return eloquent
     */
    public function findWithCommissionById($pDemandInfoId)
    {
        if (! is_numeric($pDemandInfoId) || $pDemandInfoId < 1) {
            return null;
        }

        return $this->progDemandInfoRepo->findWithCommissionById($pDemandInfoId);
    }

    /**
     * @author thaihv
     * @param  array $ids prog_demand_infos id
     * @return object|array
     */
    public function findByIds($ids)
    {
        if (! is_array($ids)) {
            $ids = [$ids];
        }
        if (empty($ids)) {
            return null;
        }

        return $this->progDemandInfoRepo->findByIds($ids);
    }

    /**
     * @param $item
     * @param $commissionStatus
     * @param $constructionPriceTaxExclude
     * @param $completeDate
     * @param $orderFailDate
     * @param $orderFailResult
     */
    private function handleVariableCommissionStepOne($item, &$commissionStatus, &$constructionPriceTaxExclude, &$completeDate, &$orderFailDate, &$orderFailResult)
    {
        if (! empty($item->construction_price_tax_exclude)) {
            $constructionPriceTaxExclude = $item->construction_price_tax_exclude;
        }

        if (! empty($item->commission_status)) {
            $commissionStatus = $item->commission_status;
        }

        // complete status
        if ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[2] && ! empty($item->complete_date)) {
            $completeDate = $item->complete_date;
        } else { //Loss of order
            if ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[3]) {
                if (! empty($item->complete_date)) {
                    $orderFailDate = $item->complete_date;
                }
                if (! empty($item->commission_order_fail_reason)) {
                    $orderFailResult = $item->commission_order_fail_reason;
                }
            }
        }

        if ($item->diff_flg == array_keys(ProgDemandInfo::PM_DIFF_LIST)[2]) {
            //Set the intermediary status
            $commissionStatus = $item->commission_status_update;

            //Finished construction
            if ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[2]) {
                $completeDate = $item->complete_date_update;

                if (! empty($item->construction_price_tax_exclude_update)) {
                    $constructionPriceTaxExclude = $item->construction_price_tax_exclude_update;
                }
            } elseif ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[3]) {
                $orderFailDate = $item->complete_date_update;
                $orderFailResult = $item->commission_order_fail_reason_update;
            }
        }
    }

    /**
     * @param $item
     * @param $commissionStatus
     * @param $constructionPriceTaxExclude
     * @param $completeDate
     * @param $orderFailDate
     * @param $orderFailResult
     */
    private function handleVariableCommissionStepTwo($item, &$commissionStatus, &$constructionPriceTaxExclude, &$completeDate, &$orderFailDate, &$orderFailResult)
    {
        if ($item->diff_flg == array_values(ProgDemandInfo::PM_DIFF_LIST)[2]) {
            //Set the intermediary status
            $commissionStatus = $item->commission_status_update;

            //Construction completed
            if ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[2]) {
                $completeDate = $item->complete_date_update;
                // Construction amount
                if (! empty($item->construction_price_tax_exclude_update)) {
                    $constructionPriceTaxExclude = $item->construction_price_tax_exclude_update;
                }
            } else { //Loss of order
                if ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[3]) {
                    $orderFailDate = $item->complete_date_update;
                    $orderFailResult = $item->commission_order_fail_reason_update;
                }
            }
        }
    }

    /**
     * @param $type
     * @param $eigyoDate
     * @return mixed
     */
    private function countEigyoDate($type, $eigyoDate)
    {
        $result = [];
        $ws = 0;
        if ($type == 'corp') {
            for ($i = 1; $i <= date("d"); $i++) {
                $datetime = new DateTime();
                $datetime->setDate(date("Y"), date("m"), $i);
                $ws = (int) $datetime->format('w');
                if ($ws != config('constant.START_WEEK') && $ws != config('constant.END_WEEK')) {
                    $eigyoDate++;
                }
            }
        }
        $result[] = $ws;
        $result[] = $eigyoDate;
        return $result;
    }

    /**
     * @param $item
     * @param $eigyoDate
     * @param $ws
     * @param $commissionStatus
     * @param $completeDate
     * @param $orderFailDate
     * @param $orderFailResult
     * @param $constructionPriceTaxExclude
     * @param $collectDate
     */
    private function updateCommissionWithEigyoDate(
        $item,
        $eigyoDate,
        $ws,
        $commissionStatus,
        $completeDate,
        $orderFailDate,
        $orderFailResult,
        $constructionPriceTaxExclude,
        $collectDate
    ) {
        $diffFlg = 1;
        $pmRelease = config('constant.PM_RELEASE');
        $eigyoDateBorder = config('constant.EXTERNAL_DATE_BODER');
        if (!$pmRelease
            || $eigyoDate < $eigyoDateBorder
            || (
                $eigyoDate == $eigyoDateBorder
                && (
                    $ws != config('constant.START_WEEK')
                    && $ws != config('constant.END_WEEK')
                )
            )
        ) {
            if ($this->checkItem($item)) {
                $this->comInfoRepo->update($item->commission_id, ['re_commission_exclusion_status' => 0]);
            }

            $updateSql = "SELECT set_commission("."'".$item->demand_id."',"."'".$item->commission_id."',"."'".$commissionStatus."',"."'".$completeDate."',"."'".$orderFailDate."',"."'".$orderFailResult."',"."'".$constructionPriceTaxExclude."',"."'".$collectDate."',"."'".$item->comment_update.$this->getComment($item)."',"."'".$diffFlg."');";
            DB::select($updateSql);
        }
    }

    /**
     * @param $item
     * @return bool
     */
    private function checkItem($item)
    {
        return ($item->commissionInfo->commission_status == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[0]
            && array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[3]
            && ! empty($item->commissionInfo->re_commission_exclusion_status)
            && $item->commissionInfo->re_commission_exclusion_status == 2) ? true: false;
    }

    /**
     * @param $data
     * @param string $type
     * @return bool|null
     * @throws Exception
     */
    public function updateCommissionInfos($data, $type = 'corp')
    {
        $now = date('Y-m-d H:i:s');
        if (empty($data)) {
            return null;
        }
        try {
            foreach ($data as $item) {
                $orderFailDate = null;
                $completeDate = null;
                $orderFailResult = 0;
                $commissionStatus = 0;
                $constructionPriceTaxExclude = 0;
                $this->handleVariableCommissionStepOne(
                    $item,
                    $commissionStatus,
                    $constructionPriceTaxExclude,
                    $completeDate,
                    $orderFailDate,
                    $orderFailResult
                );
                //changed
                $this->handleVariableCommissionStepTwo(
                    $item,
                    $commissionStatus,
                    $constructionPriceTaxExclude,
                    $completeDate,
                    $orderFailDate,
                    $orderFailResult
                );

                // Progress collection date
                $collectDate = substr($now, 0, 16);

                if (! empty($item->commission_id)) {
                    $eigyoDate = 0;
                    $resultEigyoDate = $this->countEigyoDate($type, $eigyoDate);
                    $ws        = $resultEigyoDate[0];
                    $eigyoDate = $resultEigyoDate[1];
                    $this->updateCommissionWithEigyoDate(
                        $item,
                        $eigyoDate,
                        $ws,
                        $commissionStatus,
                        $completeDate,
                        $orderFailDate,
                        $orderFailResult,
                        $constructionPriceTaxExclude,
                        $collectDate
                    );
                }
                DB::select("SELECT set_lock_status(".$item->commission_id.", 0);");
            }
        } catch (Exception $e) {
            $this->log->log($e->getMessage());
            $toAdmin = env('PM_ADMIN_MAIL_TO', config('constant.PM_ADMIN_MAIL_TO'));
            $fromAddress = env('PM_ADMIN_MAIL_FROM', config('constant.PM_ADMIN_MAIL_FROM'));
            try {
                MailHelper::sendRawMail(
                    $e->getMessage(),
                    config('rits.fail_update_commission'),
                    $fromAddress,
                    $toAdmin
                );
            } catch (Exception $e) {
                $this->log->log($e->getMessage());
            }
            if (! empty($updateSql)) {
                $this->log->log('SQL: ', $updateSql, Logger::INFO);
            }
            return false;
        }

        return true;
    }

    /**
     * @param $demandInfo
     * @param $result
     * @return string
     */
    private function editResultCommentStepOne($demandInfo, $result)
    {
        if (isset(ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status])) {
            $result .= ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status].config('rits.jp_arrow');
        }
        // 変更後状況
        if ($demandInfo->commission_status_update == 0 && isset(ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status])) {
            $result .= ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status]."\r\n";
        } else {
            if (isset(ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status_update])) {
                $result .= ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status_update]."\r\n";
            }
        }
        return $result;
    }

    /**
     * @param $demandInfo
     * @param $result
     * @return string
     */
    private function editResultCommentStepTwo($demandInfo, $result)
    {
        if ($demandInfo->commission_status_update == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[2]) {
            $result .= config('rits.construction_complete_date');
            $result .= (! empty($demandInfo->complete_date)) ? $demandInfo->complete_date : config('rits.none');
            $result .= "⇒";
            $result .= (! empty($demandInfo->complete_date_update)) ? $demandInfo->complete_date_update : config('rits.none');
            $result .= "\r\n";
            $result .= config('rits.constructin_amount');
            $result .= (! empty($demandInfo->construction_price_tax_exclude)) ? $demandInfo->construction_price_tax_exclude : config('rits.none');
            $result .= "⇒";
            $result .= (! empty($demandInfo->construction_price_tax_exclude_update)) ? $demandInfo->construction_price_tax_exclude_update : config('rits.none');
        } else { // 失注日
            if ($demandInfo->commission_status_update == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[3]) {
                $result .= config('rits.missing_date');
                $result .= (! empty($demandInfo->complete_date)) ? $demandInfo->complete_date : config('rits.none');
                $result .= "⇒";
                $result .= (! empty($demandInfo->complete_date_update)) ? $demandInfo->complete_date_update : config('rits.none');
            }
        }
        return $result;
    }

    /**
     * @author thaihv
     * @param  integer $demandInfo
     * @return string             comment
     */
    public function getComment($demandInfo)
    {
        try {
            $result = "\r\n";

            $result .= config('rits.construct_status');
            $result = $this->editResultCommentStepOne($demandInfo, $result);
            // 変更あり・なし
            $result .= config('rits.change');
            if ($demandInfo->diff_flg == array_keys(ProgDemandInfo::PM_DIFF_LIST)[1]) {
                //変更なしの場合は以下の処理を行わない
                $result .= ProgDemandInfo::PM_DIFF_LIST[2]."\r\n";
                return $result;
            } else {
                if ($demandInfo->diff_flg == array_keys(ProgDemandInfo::PM_DIFF_LIST)[2]) {
                    $result .= ProgDemandInfo::PM_DIFF_LIST[3]."\r\n";
                }
            }
            $result = $this->editResultCommentStepTwo($demandInfo, $result);
            return $result;
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * insert log
     *
     * @param  array  $data
     * @param  string $type
     * @return boolean
     * @throws Exception
     */
    public function insertLog($data, $type = 'demand_info')
    {
        DB::beginTransaction();
        try {
            $saveLogData = [];
            $userId = auth()->user()->user_id;
            $dateTime = date('Y-m-d H:i:s');
            foreach ($data as $item) {
                $tmpItem = $item->toArray();
                unset($tmpItem['commission_info']);

                if ($type == 'demand_info') {
                    $tmpItem['prog_demand_info_id'] = $tmpItem['id'];
                    $tmpItem['comment_update'] = $tmpItem['comment_update'].$this->getComment($item);
                } else {
                    if ($type == 'add_demand_info') {
                        $tmpItem['prog_add_demand_info_id'] = $tmpItem['id'];
                    }
                }
                unset($tmpItem['id']);

                $tmpItem['created'] = $dateTime;
                $tmpItem['created_user_id'] = $userId;
                $tmpItem['modified'] = $dateTime;
                $tmpItem['modified_user_id'] = $userId;

                $saveLogData[] = $tmpItem;
            }
            $rtn = false;
            if ($type == 'demand_info') {
                $rtn = $this->progDemandInfoLog->insert($saveLogData);
            } elseif ($type == 'add_demand_info') {
                $rtn = $this->progAddDemandInfoLog->insert($saveLogData);
            }
            if (! $rtn) {
                DB::rollback();
            } else {
                DB::commit();
            }
        } catch (Exception $e) {
            $this->log->log($e->getMessage());
            DB::rollback();
        }
        return true;
    }

    /**
     * @param $pDemandInfo
     * @return mixed
     */
    public function handelDataUpdate($pDemandInfo)
    {
        if ($pDemandInfo['diff_flg'] == 2) {
            unset($pDemandInfo['commission_status_update']);
            unset($pDemandInfo['complete_date_update']);
            unset($pDemandInfo['commission_order_fail_reason_update']);
            unset($pDemandInfo['construction_price_tax_exclude_update']);
        } else {
            if (empty($pDemandInfo['complete_date_update'])) {
                unset($pDemandInfo['complete_date_update']);
            }
            if (empty($pDemandInfo['commission_order_fail_reason_update'])) {
                unset($pDemandInfo['commission_order_fail_reason_update']);
            }
            if (empty($pDemandInfo['construction_price_tax_exclude_update'])) {
                unset($pDemandInfo['construction_price_tax_exclude_update']);
            }
        }

        return $pDemandInfo;
    }
}
