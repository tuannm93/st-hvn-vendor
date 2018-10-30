<?php
namespace App\Services;

use App\Models\ProgAddDemandInfoTmp;
use App\Models\ProgDemandInfoOtherTmp;
use App\Models\ProgDemandInfoTmp;
use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\ProgCorpRepositoryInterface;
use App\Repositories\ProgItemRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\ProgDemandInfoRepositoryInterface;
use App\Repositories\ProgAddDemandInfoRepositoryInterface;
use App\Models\ProgCorp;
use App\Models\ProgDemandInfo;
use App\Models\ProgItem;
use Carbon\Carbon;
use App\Helpers\MailHelper;
use App\Mail\ProgCorpNotice;
use App\Mail\FaxEmail;

use Log;

use Mpdf\Mpdf;
use App\Services\Log\CorpLogService;

class ProgCorpService
{

    // progcorp repository
    /**
     * @var ProgCorpRepositoryInterface
     */
    public $progCorpRepo;
    // progcorp repository
    /**
     * @var ProgItemRepositoryInterface
     */
    public $progItemRepo;
        /**
     * @var MItemRepositoryInterface
     */
    public $mItemRepo;
    // Mitem repository
    /**
     * @var ProgDemandInfoRepositoryInterface
     */
    public $progDemandInfoRepo;
    /**
     * @var ProgAddDemandInfoRepositoryInterface
     */
    public $progAddDemandInfoRepo;
    /**
     * @var MItemService
     */
    public $mItemService;

    /**
     * @var ProgDemandInfoTmp
     */
    public $progDemandInfoTmp;
    /**
     * @var ProgAddDemandInfoTmp
     */
    public $progAddDemandInfoTmp;
    /**
     * @var ProgDemandInfoOtherTmp
     */
    public $progDemandInfoOtherTmp;

    //number of record each page
    /**
     * @var integer
     */
    public $paginate = 30;

    /**
     * @var integer
     */
    public $progItemId = 1;
    /**
     * @var CorpLogService
     */
    public $log;

    /**
     * constructor
     * @param ProgCorpRepositoryInterface $progCorpRepo
     * @param ProgItemRepositoryInterface $progItemRepo
     * @param MItemRepositoryInterface $mItemRepo
     * @param ProgDemandInfoRepositoryInterface $progDemandInfoRepo
     * @param ProgAddDemandInfoRepositoryInterface $progAddDemandInfoRepo
     * @param CorpLogService                       $log
     * @param ProgDemandInfoTmp                    $progDemandInfoTmp
     * @param ProgAddDemandInfoTmp                 $progAddDemandInfoTmp
     * @param ProgDemandInfoOtherTmp               $progDemandInfoOtherTmp
     * @param \App\Services\MItemService           $mItemService
     */
    public function __construct(
        ProgCorpRepositoryInterface $progCorpRepo,
        ProgItemRepositoryInterface $progItemRepo,
        MItemRepositoryInterface $mItemRepo,
        ProgDemandInfoRepositoryInterface $progDemandInfoRepo,
        ProgAddDemandInfoRepositoryInterface $progAddDemandInfoRepo,
        CorpLogService $log,
        ProgDemandInfoTmp $progDemandInfoTmp,
        ProgAddDemandInfoTmp $progAddDemandInfoTmp,
        ProgDemandInfoOtherTmp $progDemandInfoOtherTmp,
        MItemService $mItemService
    ) {
        $this->progCorpRepo = $progCorpRepo;
        $this->progItemRepo = $progItemRepo;
        $this->mItemRepo = $mItemRepo;
        $this->progDemandInfoRepo = $progDemandInfoRepo;
        $this->progAddDemandInfoRepo = $progAddDemandInfoRepo;
        $this->mItemService = $mItemService;
        $this->log = $log;
        $this->progDemandInfoTmp = $progDemandInfoTmp;
        $this->progAddDemandInfoTmp = $progAddDemandInfoTmp;
        $this->progDemandInfoOtherTmp = $progDemandInfoOtherTmp;
    }

    /**
     * @param $corpId
     * @param $fileId
     * @param $progressFlag
     * @return mixed
     */
    public function getProgCorpByFlag($corpId, $fileId, $progressFlag)
    {
        return $this->progCorpRepo->findProgCorpWithFlag($corpId, $fileId, $progressFlag);
    }

    /**
     * @param $id
     * @return null
     */
    public function getProgCorpById($id)
    {
        if (!is_numeric($id)) {
            return null;
        }

        return $this->progCorpRepo->findById($id);
    }

    /**
     * get progcorp with holiday
     *
     * @author thaihv
     * @param  $imPortFileId
     * @param  array        $conditionSearch
     * @return [type] [description]
     */
    public function getProgCorpWithHoliday($imPortFileId, $conditionSearch = [])
    {

        if (isset($conditionSearch['limit'])) {
            $this->paginate = $conditionSearch['limit'];
        }
        $corpName = '';
        if (isset($conditionSearch['corp_name'])) {
            $corpName = $conditionSearch['corp_name'];
        }
        $progCorps = $this->progCorpRepo->getCorpWithHolidayByFileId($imPortFileId, $corpName);
        if (!empty($conditionSearch)) {
            $progCorps = $this->setProgCorps($progCorps, $conditionSearch);
        } else {
            $progCorps->orderBy('id', 'ASC');
        }
        $progCorps = $progCorps->paginate($this->paginate);
        $corpIds = [];
        foreach ($progCorps as $pcorp) {
            $corpIds[] = $pcorp->corp_id;
        }
        $holidayData = $this->progCorpRepo->getHolidayByCorpId($corpIds);
        // filter holiday by corp_id
        $progCorps = $this->filterHoliday($progCorps, $holidayData);

        return $progCorps;
    }

    /**
     * @param $progCorps
     * @param $conditionSearch
     * @param $field
     * @param $valueName
     * @param string $condition
     */
    private function setSearchConditionForField(&$progCorps, $conditionSearch, $field, $valueName, $condition = "=")
    {
        if (isset($conditionSearch[$valueName]) && !empty($conditionSearch[$valueName])) {
            $progCorps->where($field, $condition, $conditionSearch[$valueName]);
        }
    }

    /**
     * @param $progCorps
     * @param $conditionSearch
     */
    private function setCallBackPhoneDate(&$progCorps, $conditionSearch)
    {
        if (isset($conditionSearch['call_back_phone_date']) && !empty($conditionSearch['call_back_phone_date'])) {
            if ($conditionSearch['call_back_phone_date'] == 1) {
                $progCorps->whereNull('call_back_phone_date');
            } elseif ($conditionSearch['call_back_phone_date'] == 2) {
                $progCorps->whereNotNull('call_back_phone_date');
            }
        }
    }

    /**
     * @param $progCorps
     * @param $conditionSearch
     */
    private function setUnitCost(&$progCorps, $conditionSearch)
    {
        if (isset($conditionSearch['unit_cost']) && !empty($conditionSearch['unit_cost'])) {
            if ($conditionSearch['unit_cost'] == 1) {
                $progCorps->orderByRaw('unit_cost is null, unit_cost ASC');
            } elseif ($conditionSearch['unit_cost'] == 2) {
                $progCorps->orderByRaw('unit_cost DESC, unit_cost is null ASC');
            }
        } else {
            $progCorps->orderBy('id', 'ASC');
        }
    }

    /**
     * @param $progCorps
     * @param $conditionSearch
     */
    private function setPostHistory(&$progCorps, $conditionSearch)
    {
        if (isset($conditionSearch['post_history']) && !empty($conditionSearch['post_history'])) {
            if ($conditionSearch['post_history'] == 1) {
                $progCorps->where(function ($where) {
                    $where->orWhereNull('note')->orWhere('note', '=', '');
                });
            } elseif ($conditionSearch['post_history'] == 2) {
                $progCorps->whereNotNull('note')->where('note', '!=', '');
            }
        }
    }

    /**
     * @param $progCorps
     * @param $conditionSearch
     * @return mixed
     */
    private function setProgCorps(&$progCorps, $conditionSearch)
    {
        $this->setSearchConditionForField($progCorps, $conditionSearch, 'corp_id', 'corp_id');
        $this->setSearchConditionForField($progCorps, $conditionSearch, 'progress_flag', 'progress_flag');
        $this->setSearchConditionForField($progCorps, $conditionSearch, 'contact_type', 'contact_type');
        $this->setSearchConditionForField($progCorps, $conditionSearch, 'call_back_phone_flag', 'call_back_phone_flag');
        $this->setCallBackPhoneDate($progCorps, $conditionSearch);
        $this->setUnitCost($progCorps, $conditionSearch);
        $this->setSearchConditionForField($progCorps, $conditionSearch, 'collect_date', 'collection_date_from', '>=');
        $this->setSearchConditionForField($progCorps, $conditionSearch, 'collect_date', 'collection_date_to', '<=');
        if (isset($conditionSearch['after_tel_date_from']) && !empty($conditionSearch['after_tel_date_from'])) {
            $date = Carbon::createFromFormat('Y/m/d', $conditionSearch['after_tel_date_from']);
            $progCorps->where('call_back_phone_date', '>=', $date);
        }
        if (isset($conditionSearch['after_tel_date_to']) && !empty($conditionSearch['after_tel_date_to'])) {
            $date = Carbon::createFromFormat('Y/m/d', $conditionSearch['after_tel_date_to']);
            $progCorps->where('call_back_phone_date', '<=', $date);
        }
        $this->setPostHistory($progCorps, $conditionSearch);
        if (isset($conditionSearch['note']) && !empty($conditionSearch['note'])) {
            $progCorps->where('note', 'LIKE', '%' . $conditionSearch['note'] . '%');
        }

        return $progCorps;
    }

    /**
     * filter holiday by prog_corps
     *
     * @param $progCorps
     * @param $holidayData
     * @return array
     */
    private function filterHoliday($progCorps, $holidayData)
    {
        $progCorps->each(function ($prog) use ($holidayData) {
            foreach ($holidayData as $hd) {
                if ($hd->corp_id == $prog->corp_id) {
                    $prog->holidays = $hd->holidays;
                    break;
                }
            }
        });

        return $progCorps;
    }

    /**
     * @param $corpId
     * @return boolean
     */
    public function isShowProg($corpId)
    {
        $count = $this->progCorpRepo->countProgCropForShowDialogBox($corpId);
        if ($count > 0) {
            return true;
        }
        return false;
    }
    /**
     * update fiel for prog_corp
     *
     * @param  integer $pCorpId prog_corps id
     * @param  array   $data    field data
     * @return boolean          updated or not
     */
    public function updateProgressCorp($pCorpId, $data)
    {
        if (!is_numeric($pCorpId)) {
            return false;
        }
        $data['modified_user_id'] = auth()->user()->user_id;
        return $this->progCorpRepo->updateProgressCorp($pCorpId, $data);
    }
    /**
     * send email to
     *
     * @author thaihv
     * @param  array   $corpData name and emails
     * @throws \Exception
     * @return boolean           sent or not
     */
    public function sendEmail($corpData, $fileId)
    {
        $progItem = $this->progItemRepo->findById($this->progItemId);
        $templeteData = [];
        $templeteData['url'] = route('get.progress_management.demand_detail', $fileId);
        $templeteData['date'] = $progItem->return_limit;
        $corpName = $corpData['name'];
        $emails = explode(';', $corpData['emails']);
        $mailValids = [];
        foreach ($emails as $key => $email) {
            $email = trim($email);
            if (valid_email($email)) {
                $mailValids[] = $email;
            }
        }
        $count = 0;
        // loop and set email data for valid email
        Log::debug('============ start process send email ===================');
        foreach ($mailValids as $key => $item) {
            $emailData = [];
            $toAdmin = env('PM_ADMIN_MAIL_TO', config('constant.PM_ADMIN_MAIL_TO'));
            $fromAddress = env('PM_ADMIN_MAIL_FROM', config('constant.PM_ADMIN_MAIL_FROM'));
            $adminSubject = __('progress_management.prog_corp_mail_admin_subject', ['corpName' => $corpName]);
            $subject = __('progress_management.prog_corp_mail_subject', ['corpName' => $corpName]);
            $emailData['subject'] = $subject;
            $emailData['from'] = $fromAddress;
            $msg = __('progress_management.prog_corp_email_msg', ['item' => $item, 'subject' => $subject]);
            try {
                Log::debug('_____ send email to input email ___');
                MailHelper::sendMail($item, new ProgCorpNotice($emailData, $templeteData));
                Log::debug('_____ end send email to input email ___');
                $emailData['subject'] = $adminSubject;
                Log::debug('_____ send email to admin ___');
                MailHelper::sendMail($toAdmin, new ProgCorpNotice($emailData, $templeteData));
                Log::debug('_____ end send email to admin ___');
                $count += 1;
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                $this->log->log($msg);
                try {
                    MailHelper::sendRawMail($msg, 'ERROR: ' . $subject, $fromAddress, $toAdmin);
                } catch (Exception $e) {
                    Log::error($exception->getMessage());
                    $this->log->log($msg);
                }
            }
        }
        Log::debug('================= end process send email ===================');
        return $count;
    }

    /**
     * updated count_email
     *
     * @author thaihv
     * @param  integer $pCorpId prog_corps id
     * @param  $count
     * @return boolean          updated or not
     */
    public function updateAfterSendEmail($pCorpId, $count)
    {
        $progCorp = $this->progCorpRepo->findById($pCorpId);
        if (!$progCorp) {
            return false;
        }

        $progCorp->mail_count = $progCorp->mail_count + $count;
        $progCorp->progress_flag = 2;
        $progCorp->mail_last_send_date = date("Y-m-d H:i:s");
        if ($progCorp->save()) {
            return true;
        }
        return false;
    }

    /**
     * send email fax to
     *
     * @author thaihv
     * @param  integer         $pCorpId prog_corps id
     * @param  $corpRequestData
     * @return boolean           sent or not
     */
    public function sendFax($pCorpId, $corpRequestData)
    {
        ini_set("pcre.backtrack_limit", "10000000");
        $filename = $this->outputPDF($pCorpId, 'F');
        $fromAddress = env('PM_ADMIN_MAIL_FROM', config('constant.PM_ADMIN_MAIL_FROM')); //PM_ADMIN_MAIL_FROM
        $faxMailTo = env('PM_FAX_MAIL_TO', config('constant.PM_FAX_MAIL_TO')); //PM_FAX_MAIL_TO
        $adminMailTo = env('PM_ADMIN_MAIL_TO', config('constant.PM_ADMIN_MAIL_TO'));
        $corpName = $corpRequestData['name'];
        $address = explode(';', $corpRequestData['faxs']);
        $count = 0;
        $subject = config('constant.FAX_SUBJECT');
        foreach ($address as $item) {
            $mailSubject = __('progress_management.prog_corp_mail_subject', ['corpName' => $corpName]);
            $mailContents = sprintf(getDivText('fax_setting', 'contents'), $item, $corpRequestData['name']);
            try {
                Log::debug('_____ send fax to input email ___');
                MailHelper::sendAttachMail(
                    $fromAddress,
                    $faxMailTo,
                    $subject,
                    $mailContents,
                    [$filename],
                    $adminMailTo
                );
                Log::debug('_____ end fax to input email ___');
                $count += 1;
            } catch (Exception $e) {
                $msg = __(
                    'progress_management.prog_corp_fax_msg',
                    ['item' => $item, 'subject' => $mailSubject]
                );
                $this->log->log($msg);
                MailHelper::sendRawMail($msg, 'ERROR: ' . $mailSubject, $fromAddress, $adminMailTo);
            }
        }
        Log::debug('================= end process send fax ===================');
        return $count;
    }

    /**
     * updated count_fax
     *
     * @author thaihv
     * @param  integer $pCorpId prog_corps id
     * @param  $count
     * @return boolean          updated or not
     */
    public function updateAfterSendFax($pCorpId, $count)
    {
        $progCorp = $this->progCorpRepo->findById($pCorpId);
        if (!$progCorp) {
            return false;
        }
        $progCorp->fax_count = $progCorp->fax_count + $count;
        $progCorp->progress_flag = 2;
        $progCorp->fax_last_send_date = date("Y-m-d H:i:s");
        if ($progCorp->save()) {
            return true;
        }
        return false;
    }

    /**
     * @param $pCorpId
     * @param string  $type
     * @return boolean|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function outputCSV($pCorpId, $type = "corp")
    {
        if (!$this->progCorpRepo->findById($pCorpId)) {
            return false;
        }
        if ($type == "corp") {
            $fileId = 'prog_corps.id';
        } else {
            $fileId = 'prog_corps.prog_import_file_id';
        }
        $data = $this->progDemandInfoRepo->getCSVData($pCorpId, $fileId);
        $addData = $this->progAddDemandInfoRepo->getCSVData($pCorpId, $fileId);
        $commissStatusList = config('rits.PM_COMMISSION_STATUS', []);
        $diffList = config('rits.PM_DIFF_LIST', []);
        $agreeList = config('rits.agress_list', []);
        $contactTypes = $this->mItemService->prepareDataList(
            $this->mItemRepo->getListByCategoryItem(MItemRepository::CATE_CONTACT_TYPE)
        );
        $notReplyList = $this->mItemService->prepareDataList(
            $this->mItemRepo->getListByCategoryItem(MItemRepository::CATE_NOT_REPLY)
        );
        $progressList = $this->mItemService->prepareDataList(
            $this->mItemRepo->getListByCategoryItem(MItemRepository::CATE_PROGRESS)
        );
        $commissFailResult = $this->mItemService->prepareDataList(
            $this->mItemRepo->getListByCategoryItem(MItemRepository::REASON_FOR_LOSING_CONSENT)
        );
        $titleCol = ProgDemandInfo::CSV_FIELD;

        $demandData = $this->buildDemandInfo($data, $commissStatusList, $diffList, $agreeList, $contactTypes, $notReplyList, $progressList, $commissFailResult);
        $demandAddData = $this->buildDemandInfo($addData, $commissStatusList, $diffList, $agreeList, $contactTypes, $notReplyList, $progressList, $commissFailResult, true);
        $csvData = array_merge($demandData, $demandAddData);
        $fileName = date('Ymd_His');

        $exportService = new ExportService;

        return $exportService->exportCsv($fileName, $titleCol, $csvData);
    }

    /**
     * @param $progCorpId
     * @return array
     */
    public function getProgDemandInfos($progCorpId)
    {
        $data = [];
        $demandInfos = $this->progDemandInfoRepo->findByProgCorpId($progCorpId);
        $data['ProgDemandInfoPaginate'] = $demandInfos;

        $demandInfosArr = $demandInfos->toArray();
        if ($demandInfosArr['data']) {
            foreach ($demandInfosArr['data'] as $demandInfo) {
                $data['ProgDemandInfo'][] = $demandInfo;
            }
        } else {
            $data['ProgDemandInfo'] = [];
        }

        $addDemandInfos = $this->progAddDemandInfoRepo->getDataByProgCorpId($progCorpId)->toArray();
        foreach ($addDemandInfos as $addDemandInfo) {
            $data['ProgDemandInfoOther']['add_flg'] = 1;
            $addDemandInfo['display'] = 1;
            $data['ProgAddDemandInfo'][] = $addDemandInfo;
        }
        return $data;
    }

    /**
     * @param $progCorpId
     * @param string     $type
     * @return array
     */
    public function getTmp($progCorpId, $type = 'demand_info')
    {
        if ($type == 'demand_info') {
            return $this->getTmpDemandInfo($progCorpId);
        } elseif ($type == 'add_demand_info') {
            return $this->getTmpAddDemandInfo($progCorpId);
        } elseif ($type == 'other') {
            return $this->progDemandInfoOtherTmp
                ->where('prog_corp_id', $progCorpId)
                ->orderBy('id', 'asc')->get()->toarray();
        } elseif ($type == 'file') {
            return $this->getTmpFile($progCorpId);
        }
    }

    /**
     * @param $progCorpId
     * @return array
     */
    private function getTmpDemandInfo($progCorpId)
    {
        $rtn = [];
        $tmp = $this->progDemandInfoTmp
            ->where('prog_corp_id', $progCorpId)
            ->orderBy('receive_datetime', 'asc')->get()->toarray();

        foreach ($tmp as $key => $item) {
            $rtn[$key] = $item;
            unset($rtn[$key]['id']);
            if ($rtn[$key]['prog_demand_info_id']) {
                $rtn[$key]['id'] = $rtn[$key]['prog_demand_info_id'];
            }
        }
        return $rtn;
    }

    /**
     * @param $progCorpId
     * @return array
     */
    private function getTmpAddDemandInfo($progCorpId)
    {
        $rtn = [];
        $tmp = $this->progAddDemandInfoTmp
            ->where('prog_corp_id', $progCorpId)
            ->orderBy('id', 'asc')->get()->toarray();

        foreach ($tmp as $key => $item) {
            $rtn[$key] = $item;
            unset($rtn[$key]['id']);
            if ($rtn[$key]['prog_add_demand_info_id']) {
                $rtn[$key]['id'] = $rtn[$key]['prog_add_demand_info_id'];
            }
        }
        return $rtn;
    }

    /**
     * @param $progCorpId
     * @return array
     */
    private function getTmpFile($progCorpId)
    {
        $rtn = [];
        $tmp = $this->progDemandInfoOtherTmp
            ->where('prog_corp_id', $progCorpId)
            ->orderBy('id', 'asc')->get()->toarray();

        foreach ($tmp as $item) {
            $rtn['file_id'] = $item['prog_import_file_id'];
        }
        return $rtn;
    }

    /**
     * @return mixed
     */
    public function getProgItem()
    {
        return $this->progItemRepo->findById(1);
    }

    /**
     * @param $pCorpId
     * @param string  $type
     * @return string
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function outputPDF($pCorpId, $type = "D")
    {
        if (!$this->progCorpRepo->findById($pCorpId)) {
            return false;
        }
        $pathFile = storage_path(ProgCorp::STORAGE_PATH);
        $progCorp = $this->progCorpRepo->getDataWithMcorpAndDemandInfoById($pCorpId);
        $progItem  = $this->progItemRepo->findById(1);
        $commissStatusList = config('rits.PM_COMMISSION_STATUS', []);
        $editDate = ProgItem::EDIT_DATE;
        $upText = '';
        $downText = '';
        $pmCaution1 = config('rits.PM_CAUTION1');
        $pmCaution2 = config('rits.PM_CAUTION2');
        $pmCaution3 = config('rits.PM_CAUTION3');

        if (!empty($progItem->return_limit)) {
            $editDate = $progItem->return_limit;
        }
        if (!empty($progItem->up_text)) {
            $upText = str_replace("\n", '<br />', $progItem->up_text);
        }
        if (!empty($progItem->down_text)) {
            $downText = str_replace("\n", '<br />', $progItem->down_text);
        }

        // setting config for mpdf
        $config = [
            'mode' => '+aCJK',
            'format' => 'A4-L',
            'default_font_size' => 8,
            'default_font' => 'sjis',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 35,
            'margin_bottom' => 8,
            'margin_header' => 8,
            'margin_footer' => 4,
            'orientation' => 'P',
        ];
        $pdf = new Mpdf($config);
        $pdf->mirrorMargins = 0;
        $pdf->defaultfooterfontsize = 12;
        $pdf->defaultfooterfontstyle = "B";
        $pdf->defaultfooterline = 1;
        $footer = [
            'C' => [
                    'content' => '{PAGENO} / {nbpg}',
                    'font-style' => 'B',
                    'font-size' => '9',
            ],
            'L' => 1,
        ];

        $pdf->SetFooter($footer, 'O');
        $pdf->SetFooter($footer, 'E');
        $header = view('progress_management.pdf_header')->with(compact('progCorp'))->render();
        $body = view('progress_management.pdf_body')
                    ->with(
                        compact(
                            'progCorp',
                            'progItem',
                            'editDate',
                            'upText',
                            'pmCaution1',
                            'pmCaution2',
                            'pmCaution3',
                            'downText'
                        )
                    )->render();
        $addition = view('progress_management.pdf_addition')
                    ->with(
                        compact(
                            'progCorp',
                            'progItem',
                            'editDate',
                            'commissStatusList',
                            'pmCaution1',
                            'pmCaution2',
                            'pmCaution3',
                            'upText',
                            'downText'
                        )
                    )->render();
        $pdf->SetHTMLHeader($header);
        $pdf->WriteHTML($body);
        $pdf->AddPage();
        $pdf->WriteHTML($addition);
        // type = D do download file type = F do storage file
        if ($type == 'D') {
            return $pdf->Output($progCorp->mCorp->corp_name . "_" . $progCorp->mCorp->id . ".pdf", "D");
        } elseif ($type == 'F') {
            $pdf->Output($pathFile . $progCorp->id . ".pdf", "F");
            $fileOut =  $pathFile . $progCorp->id . ".pdf";
            chmod($fileOut, 0755);
            return $fileOut;
        }
    }

    /**
     * @param $data
     * @param $commissStatusList
     * @param $diffList
     * @param $agreeList
     * @param $contactTypes
     * @param $notReplyList
     * @param $progressList
     * @param $commissFailResult
     * @param boolean           $addDemand
     * @return array
     */
    private function buildDemandInfo($data, $commissStatusList, $diffList, $agreeList, $contactTypes, $notReplyList, $progressList, $commissFailResult, $addDemand = false)
    {
        $csvData = [];
        foreach ($data as $element) {
            $tmp = [
                'corp_name' => $element->mCorp->corp_name,
                'official_corp_name' => $element->mCorp->official_corp_name,
                'corp_id' => $element->corp_id,
                'commission_dial' => $element->mCorp->commission_dial,
                'mail_address' => $element->progCorp->mail_address,
                'fax' => $element->progCorp->fax,
                'contact_type' => $element->progCorp->contact_type,
                'unit_cost' => $element->progCorp->unit_cost,
                'progress_flag' => $this->getProgressFlag($progressList, $element),
                'demand_id' => $addDemand ? $element->demand_id_update : $element->demand_id,
                'commission_id' => $element->commission_id,
                'receive_datetime'=> $element->receive_datetime,
                'customer_name' => $addDemand ? $element->customer_name_update : $element->customer_name,
                'category_name' => $addDemand ? $element->category_name_update : $element->category_name,
            ];

            $tmp['fee'] = $this->convertFee($element);
            $tmp['complete_date'] = $element->complete_date;
            $tmp['construction_price_tax_exclude'] = $element->construction_price_tax_exclude;
            $tmp['commission_status'] = $this->getCommissionStatus($commissStatusList, $element);

            $tmp['diff_flg'] = $this->getDiffFlag($addDemand, $diffList, $element);

            $getUpdateByDiffflg =  $this->getUpdateByDiffflg($element, $commissStatusList);

            $tmp['complete_date_update'] = $getUpdateByDiffflg['completeDateUpdate'];

            $tmp['construction_price_tax_exclude_update'] = $getUpdateByDiffflg['constructionPriceTaxExclude'];

            $tmp['commission_status_update'] = $getUpdateByDiffflg['comissionStatusUpdate'];

            $tmp['fee_target_price'] = $element->fee_target_price;

            $tmp['commission_order_fail_reason_update'] = isset($commissFailResult[$element->commission_order_fail_reason_update]) ? $commissFailResult[$element->commission_order_fail_reason_update] : '';
            $tmp['comment_update'] = $element->comment_update;
            $tmp['koujo'] = '';
            $tmp['collect_date'] = $element->progCorp->collect_date;
            $tmp['sf_register_date'] = $element->progCorp->sf_register_date;
            $tmp['contact_type'] = isset($contactTypes[$element->progCorp->contact_type]) ? $contactTypes[$element->progCorp->contact_type] : '';

            $tmp['last_send_date'] = $this->getLastSendDate($element);

            $tmp['mail_count'] = !empty($element->progCorp->mail_count) ? $element->progCorp->mail_count : '0';
            $tmp['fax_count'] = !empty($element->progCorp->fax_count) ? $element->progCorp->fax_count : '0';

            $tmp['note'] = $element->progCorp->note;
            $tmp['not_replay_flag'] = $this->getNotReplayFlag($notReplyList, $element);

            $tmp['fee_billing_date'] = $element->fee_billing_date;
            $tmp['genre_name'] = $element->genre_name;
            $tmp['tel1'] = $element->progCorp->mCorp->tel1;
            $tmp['agree_flag'] = $this->getAgreeFlag($agreeList, $element);

            $tmp['ip_address_update'] = $element->ip_address_update;
            $tmp['user_agent_update'] = $element->user_agent_update;
            $tmp['host_name_update'] = $element->host_name_update;
            $csvData[] = $tmp;
        }

        return $csvData;
    }

    /**
     * @param $agreeList
     * @param $element
     * @return string
     */
    private function getAgreeFlag($agreeList, $element)
    {
        return isset($agreeList[$element->agree_flag]) ? $agreeList[$element->agree_flag] : '';
    }

    /**
     * @param $notReplyList
     * @param $element
     * @return string
     */
    private function getNotReplayFlag($notReplyList, $element)
    {
        return isset($notReplyList[$element->progCorp->not_replay_flag]) ? $notReplyList[$element->progCorp->not_replay_flag] : '';
    }

    /**
     * @param $commissStatusList
     * @param $element
     * @return string
     */
    private function getCommissionStatus($commissStatusList, $element)
    {
        return isset($commissStatusList[$element->commission_status]) ? $commissStatusList[$element->commission_status] : '';
    }

    /**
     * @param $progressList
     * @param $element
     * @return string
     */
    private function getProgressFlag($progressList, $element)
    {
        return isset($progressList[$element->progCorp->progress_flag]) ? $progressList[$element->progCorp->progress_flag] : '';
    }

    /**
     * @param $element
     * @return string
     */
    private function convertFee($element)
    {
        $fee = '';
        if (!empty($element->fee)) {
            $fee = $element->fee . '円';
        } elseif (!empty($element->fee_rate)) {
            $fee = $element->fee_rate . '%';
        }
        return $fee;
    }

    /**
     * @param $element
     * @return string
     */
    private function getLastSendDate($element)
    {
        $lastSendDate = '';
        if ($element->progCorp->contact_type == 1) {
            $lastSendDate = $element->progCorp->mail_last_send_date;
        } elseif ($element->contact_type == 2) {
            $lastSendDate = $element->progCorp->fax_last_send_date;
        }
        return $lastSendDate;
    }

    /**
     * @param $element
     * @param $commissStatusList
     * @return string
     */
    /**
     * @param $element
     * @param $commissStatusList
     * @return string
     */

    private function getUpdateByDiffflg($element, $commissStatusList)
    {
        $results = [];
        if ($element->diff_flg == 2) {
            $results['comissionStatusUpdate'] = isset($commissStatusList[$element->commission_status]) ? $commissStatusList[$element->commission_status] : '';
            $results['constructionPriceTaxExclude'] = $element->construction_price_tax_exclude;
            $results['completeDateUpdate'] = $element->complete_date;
        } else {
            $results['comissionStatusUpdate'] = isset($commissStatusList[$element->commission_status_update]) ? $commissStatusList[$element->commission_status_update] : '';
            $results['constructionPriceTaxExclude'] = $element->construction_price_tax_exclude_update;
            $results['completeDateUpdate'] = $element->complete_date_update;
        }

        return $results;
    }

    /**
     * @param $addDemand
     * @param $diffList
     * @param $element
     * @return string
     */
    private function getDiffFlag($addDemand, $diffList, $element)
    {
        if ($addDemand) {
            $diffFlag =  config('constant.diffFlag');
        } else {
            $diffFlag = isset($diffList[$element->diff_flg]) ? $diffList[$element->diff_flg] : '';
        }
        return $diffFlag;
    }
    /**
     * getProCorById get procorp
     * @param  int $id prog corp id
     * @return Eloquent     ProgCorp object
     */
    public function getProCorById($id)
    {
        $pCorp = $this->progCorpRepo->findById($id);
        if (!$pCorp) {
            $pCorp = $this->progCorpRepo->getModel();
        }

        return $pCorp;
    }
}
