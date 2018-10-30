<?php

namespace App\Services;

use App\Helpers\MailHelper;
use App\Mail\UpdateConfirmResponsibility;
use App\Models\ProgDemandInfo;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\ProgAddDemandInfoLogRepositoryInterface;
use App\Repositories\ProgAddDemandInfoRepositoryInterface;
use App\Repositories\ProgCorpRepositoryInterface;
use App\Repositories\ProgDemandInfoLogRepositoryInterface;
use App\Repositories\ProgDemandInfoRepositoryInterface;
use App\Repositories\ProgImportFilesRepositoryInterface;
use App\Services\Log\CorpLogService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Monolog\Logger;

class PMUpdateConfirmService
{
    /**
     * @var ProgCorpRepositoryInterface
     */
    protected $progCorpRepository;
    /**
     * @var ProgDemandInfoRepositoryInterface
     */
    protected $progDemandInfoRepo;
    /**
     * @var ProgAddDemandInfoRepositoryInterface
     */
    protected $progAddDemandInfoRepo;
    /**
     * @var ProgImportFilesRepositoryInterface
     */
    protected $progImportFilesRepo;
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $comInfoRepo;
    /**
     * @var CorpLogService
     */
    protected $log;
    /**
     * @var ProgDemandInfoLogRepositoryInterface
     */
    protected $progDemandInfoLog;
    /**
     * @var ProgAddDemandInfoLogRepositoryInterface
     */
    protected $progAddDemandInfoLog;
    /**
     * @var PMDeleteTempService
     */
    protected $pMDeleteTempService;

    /**
     * PMUpdateConfirmService constructor.
     *
     * @param ProgCorpRepositoryInterface               $progCorpRepository
     * @param ProgDemandInfoRepositoryInterface         $progDemandInfoRepo
     * @param ProgAddDemandInfoRepositoryInterface      $progAddDemandInfoRepo
     * @param ProgImportFilesRepositoryInterface        $progImportFilesRepo
     * @param CommissionInfoRepositoryInterface         $comInfoRepo
     * @param CorpLogService                            $log
     * @param ProgDemandInfoLogRepositoryInterface      $progDemandInfoLog
     * @param ProgAddDemandInfoLogRepositoryInterface   $progAddDemandInfoLog
     * @param PMDeleteTempService                       $pMDeleteTempService
     */
    public function __construct(
        ProgCorpRepositoryInterface $progCorpRepository,
        ProgDemandInfoRepositoryInterface $progDemandInfoRepo,
        ProgAddDemandInfoRepositoryInterface $progAddDemandInfoRepo,
        ProgImportFilesRepositoryInterface $progImportFilesRepo,
        CommissionInfoRepositoryInterface $comInfoRepo,
        CorpLogService $log,
        ProgDemandInfoLogRepositoryInterface $progDemandInfoLog,
        ProgAddDemandInfoLogRepositoryInterface $progAddDemandInfoLog,
        PMDeleteTempService $pMDeleteTempService
    ) {
        $this->progCorpRepository = $progCorpRepository;
        $this->progDemandInfoRepo = $progDemandInfoRepo;
        $this->progAddDemandInfoRepo = $progAddDemandInfoRepo;
        $this->progImportFilesRepo = $progImportFilesRepo;
        $this->comInfoRepo = $comInfoRepo;
        $this->log = $log;
        $this->progDemandInfoLog = $progDemandInfoLog;
        $this->progAddDemandInfoLog = $progAddDemandInfoLog;
        $this->pMDeleteTempService = $pMDeleteTempService;
    }

    /**
     * vaidate data
     *
     * @param  array $data
     * @return boolean
     */
    public function addValidate($data = null)
    {
        if (empty($data['display'])) {
            return false;
        }
        return $this->setValidate($data);
    }

    /**
     * set validate
     * @return bool
     */
    protected function setValidate($data)
    {
        if (!empty($data['demand_id_update']) || !empty($data['customer_name_update']) ||
            !empty($data['category_name_update']) || !empty($data['commission_datus_update']) ||
            !empty($data['complete_date_update']) || !empty($data['construction_price_tax_exclude_update']) ||
            !empty($data['comment_update']) || !empty($data['demand_type_update'])
        ) {
            return true;
        }
        return false;
    }

    /**
     * update confirm
     *
     * @param  array   $data
     * @param  integer $progImportFileId
     * @param  string $officialCorpName
     * @return boolean
     * @throws Exception
     */
    public function updateConfirm($data, $progImportFileId, $officialCorpName)
    {
        try {
            DB::beginTransaction();
            $corpId = Auth::user()->affiliation_id;
            $progCorp = $this->progCorpRepository->findFirstByCorpIdAndFileId($corpId, $progImportFileId);

            // update prog demand info
            $updatateResult = $this->updateProgDemandInfo($data['ProgDemandInfo'], $corpId, $data['ProgImportFile']['file_id']);
            $this->checkResultFlg($updatateResult);
            if (!empty($data['ProgAddDemandInfo'])) {
                // insert prog add demand info
                $resultInsert = $this->insertProgAddDemandInfo($data['ProgAddDemandInfo'], $corpId, $data['ProgImportFile']['file_id'], $progCorp);
                $this->checkResultFlg($resultInsert);

                // Delete prog add demand info
                $resultDelete = $this->deleteProgAddDemandInfo($data['ProgAddDemandInfo'], $progCorp, $data['ProgImportFile']['file_id']);
                $this->checkResultFlg($resultDelete);
            }

            // Update prog corp
            $resultUpdate = $this->updateProgCorp($progCorp);
            $this->checkResultFlg($resultUpdate);

            // Insert demand info log and update commission
            $resultInsert = $this->insertDemandInfoLogAndUpdateCommission($corpId, $data['ProgImportFile']['file_id']);
            $this->checkResultFlg($resultInsert);

            // Insert add demand info log
            $resultInsert = $this->insertAddDemandInfoLog($corpId, $data['ProgImportFile']['file_id']);
            $this->checkResultFlg($resultInsert);

            $resultDelete = $this->pMDeleteTempService->deleteTmp($progCorp);
            $this->checkResultFlg($resultDelete);

            DB::commit();
            $resultFlg = true;
        } catch (Exception $exception) {
            DB::rollback();
            $resultFlg = false;
        }
        if ($resultFlg) {
            try {
                // send mail
                if (!empty($progCorp->mail_address)) {
                    $address = explode(';', $progCorp->mail_address);
                    foreach ($address as $item) {
                        $this->sendMail($item, $corpId, $data['ProgDemandInfo'], !empty($data['ProgAddDemandInfo']) ? $data['ProgAddDemandInfo'] : '', $officialCorpName);
                    }
                }
            } catch (Exception $ex) {
                return $resultFlg;
            }
        }
        return $resultFlg;
    }

    /**
     * check flag
     * @param $resultFlg
     * @throws Exception
     */
    protected function checkResultFlg($resultFlg)
    {
        if (!$resultFlg) {
            throw new Exception();
        }
    }

    /**
     * update prog demand info
     * @param  array $dataProgDemandInfo
     * @param  integer $corpId
     * @param  integer $fileId
     * @return boolean
     */
    public function updateProgDemandInfo($dataProgDemandInfo, $corpId, $fileId)
    {
        $dateNow = date('Y-m-d H:i:s');
        $userId = Auth::user()->user_id;
        foreach ($dataProgDemandInfo as $item) {
            $pdInfo = $this->progDemandInfoRepo->findByDataTmp($item, $corpId, $fileId);
            if (!$pdInfo) {
                return false;
            }
            if (!empty(Request::server('X-ClientIP'))) {
                $item['ip_address_update'] = Request::server('X-ClientIP');
            } elseif (!empty(Request::server('REMOTE_ADDR'))) {
                $item['ip_address_update'] = Request::server('REMOTE_ADDR');
            }

            $item['user_agent_update'] = substr(Request::server('HTTP_USER_AGENT'), 0, 255);
            $item['modified_user_id'] = $userId;
            $item['modified'] = $dateNow;
            unset($item['id']);
            if (!$pdInfo->update($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * insert prog add demand info
     * @param  array $dataProgAddDemandInfo
     * @param  integer $corpId
     * @param  integer $fileId
     * @param  object  $progCorp
     * @return boolean
     */
    public function insertProgAddDemandInfo($dataProgAddDemandInfo, $corpId, $fileId, $progCorp)
    {
        if (!count($dataProgAddDemandInfo)) {
            return true;
        }
        $dataInsert = [];
        $dateNow = date('Y-m-d H:i:s');
        $userId = Auth::user()->user_id;
        foreach ($dataProgAddDemandInfo as $item) {
            if ($this->addValidate($item)) {
                $item['prog_corp_id'] = !empty($progCorp->id) ? $progCorp->id : '';
                $item['prog_import_file_id'] = $fileId;
                $item['corp_id'] = $corpId;
                if (!empty(Request::server('X-ClientIP'))) {
                    $item['ip_address_update'] = Request::server('X-ClientIP');
                } elseif (!empty(Request::server('REMOTE_ADDR'))) {
                    $item['ip_address_update'] = Request::server('REMOTE_ADDR');
                }
                $item['user_agent_update'] = substr(Request::server('HTTP_USER_AGENT'), 0, 255);
                $item['created_user_id'] = $userId;
                $item['modified_user_id'] = $userId;
                $item['modified'] = $dateNow;
                $item['created'] = $dateNow;
                unset($item['id']);
                unset($item['display']);
                $dataInsert[] = $item;
            }
        }
        $resultInsert = $this->progAddDemandInfoRepo->insert($dataInsert);
        if (!$resultInsert) {
            return false;
        }

        return true;
    }

    /**
     * delete prog add demand info
     *
     * @param  array   $dataProgAddDemandInfo
     * @param  object  $progCorp
     * @param  integer $fileId
     * @return boolean
     */
    public function deleteProgAddDemandInfo($dataProgAddDemandInfo, $progCorp, $fileId)
    {

        $modified = date('Y-m-d H:i:s');
        $litsIdNotDelete = [];
        foreach ($dataProgAddDemandInfo as $item) {
            if (!empty($item['display']) && !empty($item['id'])) {
                $litsIdNotDelete[] = $item['id'];
            }
        }
        $progCorpId = !empty($progCorp->id) ? $progCorp->id : '';
        return $this->progAddDemandInfoRepo->deleteBy($progCorpId, (int)$fileId, $modified, $litsIdNotDelete) !== false ? true : false;
    }

    /**
     * update prog corp
     *
     * @param  object $progCorp
     * @return boolean
     */
    public function updateProgCorp($progCorp)
    {
        if (!$progCorp) {
            return false;
        }
        $progCorp->progress_flag = 7;
        $progCorp->collect_date = date("Y-m-d H:i:s");
        $progCorp->modified = date('Y-m-d H:i:s');
        $progCorp->modified_user_id = Auth::user()['user_id'];
        if (date('d') >= 16) {
            $progCorp->rev_mail_count += 1;
        }
        return $progCorp->save();
    }

    /**
     * insert demand info log and update commission
     *
     * @param  integer $corpId
     * @param  integer $fileId
     * @return boolean
     * @throws Exception
     */
    public function insertDemandInfoLogAndUpdateCommission($corpId, $fileId)
    {
        if (!$corpId || !$fileId) {
            return false;
        }
        $demandInfos = $this->progDemandInfoRepo->getByCorpIdAndProgImportFileId($corpId, $fileId);
        $item = $this->progImportFilesRepo->findItemReleaseFlagLastest();
        if (!empty($item) && $item->id == $fileId && !empty($demandInfos)) {
            $this->updateCommissionInfos($demandInfos);
        }
        return $this->insertLog($demandInfos);
    }

    /**
     * update commission info
     * @param  array $data progDemaninfo data with commission info
     * @return boolean
     * @throws Exception
     */
    public function updateCommissionInfos($data)
    {
        $now = date('Y-m-d H:i:s');
        $collectDate = substr($now, 0, 16);
        $updateSql = '';
        foreach ($data as $item) {
            try {
                $diffFlg                     = 1;
                $dataUpdate                  = $this->formatDataUpdateCommissionInfo($item);
                $orderFailDate               = $dataUpdate['order_fail_date'];
                $completeDate                = $dataUpdate['complete_date'];
                $orderFailResult             = $dataUpdate['order_fail_result'];
                $commissionStatus            = $dataUpdate['commission_status'];
                $constructionPriceTaxExclude = $dataUpdate['construction_price_tax_exclude'];
                // Progress collection date
                if (! empty($item->commission_id)
                    && $this->checUpdateCommissionInfo()) {
                    if ($item->commissionInfo->commission_status == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[0] && array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[3] && ! empty($item->commissionInfo->re_commission_exclusion_status) && $item->commissionInfo->re_commission_exclusion_status == 2) {
                        // update commission info
                        $this->comInfoRepo->update($item->commission_id, ['re_commission_exclusion_status' => 0]);
                    }

                    $updateSql = "SELECT set_commission("."'".$item->demand_id."',"."'".$item->commission_id."',"."'".$commissionStatus."',"."'".$completeDate."',"."'".$orderFailDate."',"."'".$orderFailResult."',"."'".$constructionPriceTaxExclude."',"."'".$collectDate."',"."'".$item->comment_update.$this->getComment($item)."',"."'".$diffFlg."');";
                    DB::select($updateSql);
                }
                DB::select("SELECT set_lock_status(".$item->commission_id.", 0);");
            } catch (Exception $exception) {
                return $this->sendMailWhenUpdateCommissionError($exception, $updateSql);
            }
        }
        return true;
    }

    /**
     * check update commission info
     * @return boolean
     */
    private function checUpdateCommissionInfo()
    {
        $dataInfo        = $this->getWeekAndEigyoDate();
        $eigyoDate       = $dataInfo['eigyo_date'];
        $week            = $dataInfo['week'];
        $pmRelease       = config('constant.PM_RELEASE');
        $eigyoDateBorder = config('constant.EXTERNAL_DATE_BODER');
        $startWeek       = config('constant.START_WEEK');
        $endWeek         = config('constant.END_WEEK');
        if (!$pmRelease
            || (
                $eigyoDate <= $eigyoDateBorder
                && ($week != $startWeek && $week != $endWeek)
            )
        ) {
            return true;
        }
        return false;
    }

    /**
     * send mail when update commission error
     * @param  Exception $exception
     * @param  string $updateSql
     * @return boolean
     */
    private function sendMailWhenUpdateCommissionError($exception, $updateSql)
    {
        $this->log->log($exception->getMessage());
        $toAdmin     = env('PM_ADMIN_MAIL_TO', config('constant.PM_ADMIN_MAIL_TO'));
        $fromAddress = env('PM_ADMIN_MAIL_FROM', config('constant.PM_ADMIN_MAIL_FROM'));
        try {
            MailHelper::sendRawMail($exception->getMessage(), config('rits.fail_update_commission'), $fromAddress, $toAdmin);
        } catch (Exception $exception) {
            $this->log->log($exception->getMessage());
        }
        if (! empty($updateSql)) {
            $this->log->log('SQL: ', $updateSql, Logger::INFO);
        }
        return false;
    }

    /**
     * format data update commission info
     * @param  \Illuminate\Database\Eloquent\Model|mixed|static $item [description]
     * @return array
     */
    private function formatDataUpdateCommissionInfo($item)
    {
        $orderFailDate               = null;
        $completeDate                = null;
        $orderFailResult             = 0;
        $commissionStatus            = 0;
        $constructionPriceTaxExclude = 0;
        $constructionPriceTaxExclude = $this->checkEmptyValue($item->construction_price_tax_exclude, $constructionPriceTaxExclude);
        $commissionStatus            = $this->checkEmptyValue($item->commission_status, $commissionStatus);
        // complete status
        if ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[2] && ! empty($item->complete_date)) {
            $completeDate = $item->complete_date;
        } else { //Loss of order
            if ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[3]) {
                $orderFailDate   = $this->checkEmptyValue($item->complete_date, $orderFailDate);
                $orderFailResult = $this->checkEmptyValue($item->commission_order_fail_reason, $orderFailResult);
            }
        }

        //changed
        if ($item->diff_flg == array_keys(ProgDemandInfo::PM_DIFF_LIST)[2]) {
            //Set the intermediary status
            $commissionStatus = $item->commission_status_update;

            //Construction completed
            if ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[2]) {
                $completeDate                = $item->complete_date_update;
                // Construction amount
                $constructionPriceTaxExclude = $this->checkEmptyValue($item->construction_price_tax_exclude_update, $constructionPriceTaxExclude);
            } else { //Loss of order
                if ($commissionStatus == array_keys(ProgDemandInfo::PM_COMMISSION_STATUS)[3]) {
                    $orderFailDate   = $item->complete_date_update;
                    $orderFailResult = $item->commission_order_fail_reason_update;
                }
            }
        }

        return [
            'order_fail_date'                => $orderFailDate,
            'complete_date'                  => $completeDate,
            'order_fail_result'              => $orderFailResult,
            'commission_status'              => $commissionStatus,
            'construction_price_tax_exclude' => $constructionPriceTaxExclude
        ];
    }

    /**
     * check empty value
     * @param  string $value
     * @param  string $default
     * @return string
     */
    protected function checkEmptyValue($value, $default)
    {
        if (! empty($value)) {
            return $value;
        }
        return $default;
    }

    /**
     * @param $type
     * @param $eigyoDate
     * @return int|string
     */
    protected function getWeekAndEigyoDate()
    {
        $eigyoDate = 0;
        $week = 0;
        for ($i = 1; $i <= date("d"); $i++) {
            $datetime = new DateTime();
            $datetime->setDate(date("Y"), date("m"), $i);
            $week = (int) $datetime->format('w');
            if ($week != config('constant.START_WEEK') && $week != config('constant.END_WEEK')) {
                $eigyoDate++;
            }
        }
        return [
            'eigyo_date' => $eigyoDate,
            'week' => $week,
        ];
    }

    /**
     * get comment
     *
     * @param  object $demandInfo
     * @return string
     */
    public function getComment($demandInfo)
    {
        try {
            $result = "\r\n";

            $result .= config('rits.construct_status');

            if (isset(ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status])) {
                $result .= ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status].config('rits.jp_arrow');
            }
            if ($demandInfo->commission_status_update == 0 && isset(ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status])) {
                $result .= ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status]."\r\n";
            } else {
                if (isset(ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status_update])) {
                    $result .= ProgDemandInfo::PM_COMMISSION_STATUS[$demandInfo->commission_status_update]."\r\n";
                }
            }
            $result .= config('rits.change');
            $result = $this->checkDiffFlg($demandInfo, $result);
            $result = $this->checkCommissionStatus($demandInfo, $result);
            return $result;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return '';
        }
    }

    /**
     * check diff flag
     * @param object $demandInfo
     * @param string $result
     * @return string
     */
    protected function checkDiffFlg($demandInfo, $result)
    {
        if ($demandInfo->diff_flg == array_keys(ProgDemandInfo::PM_DIFF_LIST)[1]) {
            $result .= ProgDemandInfo::PM_DIFF_LIST[2]."\r\n";
        } else {
            if ($demandInfo->diff_flg == array_keys(ProgDemandInfo::PM_DIFF_LIST)[2]) {
                $result .= ProgDemandInfo::PM_DIFF_LIST[3]."\r\n";
            }
        }
        return $result;
    }

    /**
     * check commission status
     * @param object $demandInfo
     * @param string $result
     * @return string
     */
    protected function checkCommissionStatus($demandInfo, $result)
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
        } else {
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
     * insert log
     *
     * @param  array  $data
     * @param  string $type
     * @return boolean
     * @throws Exception
     * @throws Exception
     */
    public function insertLog($data, $type = 'demand_info')
    {
        DB::beginTransaction();
        $date = date('Y-m-d H:i:s');
        $userId = Auth::user()['user_id'];
        try {
            $saveData = [];
            foreach ($data as $item) {
                $tmpItem = $item->toArray();
                $data['created'] = $date;
                $data['created_user_id'] = $userId;
                $data['modified'] = $date;
                $data['modified_user_id'] = $userId;
                unset($tmpItem['commission_info']);
                if ($type == 'demand_info') {
                    $tmpItem['prog_demand_info_id'] = $tmpItem['id'];
                    $tmpItem['comment_update'] = $tmpItem['comment_update'].$this->getComment($item);
                } else {
                    if ($type == 'add_demand_info') {
                        $tmpItem['prog_add_demand_info_id'] = $tmpItem['id'];
                    }
                }
                if (!$tmpItem['commission_status_update']) {
                    $tmpItem['commission_status_update'] = 0;
                }
                unset($tmpItem['id']);

                $saveData[] = $tmpItem;
            }
            $rtn = false;
            if ($type == 'demand_info') {
                $rtn = $this->progDemandInfoLog->insert($saveData);
            } else {
                if ($type == 'add_demand_info') {
                    $rtn = $this->progAddDemandInfoLog->insert($saveData);
                }
            }
            if (! $rtn) {
                DB::rollback();
                return false;
            } else {
                DB::commit();
            }
            return true;
        } catch (Exception $e) {
            $this->log->log($e->getMessage());
            DB::rollback();
            return false;
        }
    }

    /**
     * insert add demand info log
     *
     * @param  integer $corpId
     * @param  integer $fileId
     * @return boolean
     * @throws Exception
     */
    public function insertAddDemandInfoLog($corpId, $fileId)
    {
        if (empty($corpId) || empty($fileId)) {
            return false;
        }
        $addDemandInfos = $this->progAddDemandInfoRepo->getByCorpIdAndProgImportFileId($corpId, $fileId);
        if (count($addDemandInfos)) {
            return $this->insertLog($addDemandInfos, 'add_demand_info');
        }
        return true;
    }

    /**
     * send mail to admin and user
     *
     * @param  string  $address
     * @param  integer $corpId
     * @param  array   $dataProgDemandInfo
     * @param  array   $dataProgAddDemandInfo
     * @param  string   $officialCorpName
     * @return void
     */
    public function sendMail($address, $corpId, $dataProgDemandInfo, $dataProgAddDemandInfo, $officialCorpName)
    {
        $pmCommissionStatus              = ProgDemandInfo::PM_COMMISSION_STATUS;
        $diffFllags                      = ProgDemandInfo::PM_DIFF_LIST;
        $toAdmin                         = env('PM_ADMIN_MAIL_TO', config('constant.PM_ADMIN_MAIL_TO'));
        $fromAddress                     = env('PM_ADMIN_MAIL_FROM', config('constant.PM_ADMIN_MAIL_FROM'));
        $toAddress                       = $address;
        $adminSubject                    = env('PM_SUBJECT_PREFIX', config('constant.PM_SUBJECT_PREFIX')) . "■[管理側：{" . $officialCorpName . "御中]<進捗管理入力内容メール>";
        $subject                         = env('PM_SUBJECT_PREFIX', config('constant.PM_SUBJECT_PREFIX')) . "[".$officialCorpName . "御中] ご入力誠にありがとうございました。[進捗管理入力内容確認メール]";

        $list = '';
        $data['list'] = $this->getProgDemandInfo($dataProgDemandInfo, $list, $corpId, $diffFllags, $pmCommissionStatus);
        $addList = '';
        $data['add_list'] = $this->getProgAddDemandInfo($dataProgAddDemandInfo, $addList, $corpId);

        $dataSend = [
            'subject'            => $subject,
            'from'               => $fromAddress,
            'to'                 => $toAddress,
            'official_corp_name' => $officialCorpName,
            'list'               => $data['list'],
            'add_list'           => $data['add_list'],
        ];
        MailHelper::sendMail($dataSend['to'], new UpdateConfirmResponsibility($dataSend));

        $dataSendAdmin = [
            'subject'            => $adminSubject,
            'from'               => $fromAddress,
            'to'                 => $toAdmin,
            'official_corp_name' => $officialCorpName,
            'list'               => $data['list'],
            'add_list'           => $data['add_list'],
        ];
        MailHelper::sendMail($dataSendAdmin['to'], new UpdateConfirmResponsibility($dataSendAdmin));
    }

    /**
     * @param $dataProgDemandInfo
     * @param $list
     * @param $corpId
     * @param $diffFllags
     * @param $pmCommissionStatus
     * @return string
     */
    protected function getProgDemandInfo($dataProgDemandInfo, $list, $corpId, $diffFllags, $pmCommissionStatus)
    {
        if (!empty($dataProgDemandInfo)) {
            foreach ($dataProgDemandInfo as $item) {
                $list .= "案件番号              => " . $item['demand_id'] . "<br>";
                $list .= "加盟店様ID            => " . $corpId . "<br>";
                $list .= "お客様名              => " . $item['customer_name'] . "<br>";
                $fee = $this->checkFeeOrFeeRate($item);
                $list .= "手数料率（手数料金額）=> ". $fee ."<br>";
                $feeTargetPrice = '';
                if (!empty($item['fee_target_price'])) {
                    $feeTargetPrice = number_format($item['fee_target_price']) . '円';
                }
                $list .= "手数料対象金額        => " . $feeTargetPrice . "<br>";
                $list .= "情報相違              => " . $diffFllags[$item['diff_flg']] . "<br>";

                $commissionStatusUpdate = $this->checkCommissionStatusUpdateOrNot($item, $pmCommissionStatus);
                $list .= "変更後の状況          => " . $commissionStatusUpdate . "<br>";
                $completeDateUpdate = $this->checkCompleteDateUpdateOrNot($item);
                $list .= "施工完了日            => " . $completeDateUpdate . "<br>";
                $dataPriceTax                  = $this->setConstructPriceTax($item);

                $list .= "施工金額(税別)        => " . $dataPriceTax['construct_price_tax_exclude_update'] ."<br>";
                $list .= "施工金額(税込)        => " . $dataPriceTax['construct_price_tax_include_update'] . "<br>";

                $list .= "備考欄                => " . $item['comment_update'] . "<br>";
                $list .= "===========================================================<br>";
            }
        }
        return $list;
    }

    /**
     * check empty item
     * @param $item
     * @return string
     */
    protected function checkFeeOrFeeRate($item)
    {
        $fee = '';
        if (!empty($item['fee'])) {
            $fee = number_format($item['fee']) . '円';
        } elseif (!empty($item['fee_rate'])) {
            $fee = $item['fee_rate'] . '%';
        }
        return $fee;
    }

    /**
     * check commission status
     * @param $item
     * @param $pmCommissionStatus
     * @return string
     */
    protected function checkCommissionStatusUpdateOrNot($item, $pmCommissionStatus)
    {
        $commissionStatusUpdate = '';
        if (!empty($item['commission_status'])) {
            $commissionStatusUpdate = $pmCommissionStatus[$item['commission_status']];
        }
        if (!empty($item['commission_status_update'])) {
            $commissionStatusUpdate = $pmCommissionStatus[$item['commission_status_update']];
        }
        return $commissionStatusUpdate;
    }

    /**
     * @param $item
     * @return false|string
     */
    protected function checkCompleteDateUpdateOrNot($item)
    {
        $completeDateUpdate = '';
        if (!empty($item['complete_date'])) {
            $completeDateUpdate = date('Y/m/d', strtotime($item['complete_date']));
        }
        if (!empty($item['complete_date_update'])) {
            $completeDateUpdate = date('Y/m/d', strtotime($item['complete_date_update']));
        }
        return $completeDateUpdate;
    }

    /**
     * set construct value
     * @param $item
     * @return array
     */
    protected function setConstructPriceTax($item)
    {
        $constructPriceTaxExcludeUpdate = '-円';
        $constructPriceTaxIncludeUpdate = '-円';
        if (!empty($item['construction_price_tax_exclude'])) {
            $constructPriceTaxExcludeUpdate = number_format($item['construction_price_tax_exclude']) . '円';
        }
        if (!empty($item['construction_price_tax_include'])) {
            $constructPriceTaxIncludeUpdate = number_format($item['construction_price_tax_include']) . '円';
        }
        if (!empty($item['construction_price_tax_exclude_update'])) {
            $constructPriceTaxExcludeUpdate = number_format($item['construction_price_tax_exclude_update']) . '円';
        }
        if (!empty($item['construction_price_tax_include_update'])) {
            $constructPriceTaxIncludeUpdate = number_format($item['construction_price_tax_include_update']) . '円';
        }
        return [
            'construct_price_tax_exclude_update' => $constructPriceTaxExcludeUpdate,
            'construct_price_tax_include_update' => $constructPriceTaxIncludeUpdate
        ];
    }

    /**
     * @param $dataProgAddDemandInfo
     * @param $addList
     * @param $corpId
     * @return string
     */
    protected function getProgAddDemandInfo($dataProgAddDemandInfo, $addList, $corpId)
    {
        if (!empty($dataProgAddDemandInfo)) {
            foreach ($dataProgAddDemandInfo as $item) {
                $demandId = '';
                if (!empty($item['demand_id_update'])) {
                    $demandId = $item['demand_id_update'];
                }
                $addList .= "案件番号          => " . $demandId . "<br>";

                $addList .= "加盟店様ID        => " . $corpId . "<br>";

                $customerName = '';
                if (!empty($item['customer_name_update'])) {
                    $customerName = $item['customer_name_update'];
                }
                $addList .= "お客様名          => " . $customerName . "<br>";

                $completeDateUpdate = '';
                if (!empty($item['complete_date_update'])) {
                    $completeDateUpdate = date('Y/m/d', strtotime($item['complete_date_update']));
                }
                $addList .= "施工完了日        => " . $completeDateUpdate . "<br>";

                $constructPriceTaxExcludeUpdate = '';
                if (!empty($item['construction_price_tax_exclude_update'])) {
                    $constructPriceTaxExcludeUpdate = number_format($item['construction_price_tax_exclude_update']) . '円';
                }
                $addList .= "施工金額(税別)    => " . $constructPriceTaxExcludeUpdate . "<br>";

                $commentUpdate = '';
                if (!empty($item['comment_update'])) {
                    $commentUpdate = $item['comment_update'];
                }
                $addList .= "備考欄            => " . $commentUpdate . "<br>";
                $addList .= "===========================================================<br>";
            }
        }
        return $addList;
    }
}
