<?php

namespace App\Services\Job;

use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCorpNewYearRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MCorpSubRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\PublicHolidayRepositoryInterface;
use App\Services\ExportService;
use App\Services\Log\ShellLogService;

/**
 * Class AuctionAutoCallService
 *
 * @package App\Services\Job
 */
class AuctionAutoCallService
{
    const HOLIDAY_CATEGORY = '休業日';

    const WORKING_IN_HOLIDAY = '稼働';

    const USER = 'system';

    const STATUS_API_SUCCESS = '開始';

    const INFO_LOG = 200;

    const ERROR_LOG = 400;

    const DATA_NAME = '入札案内オートコール_';

    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandRepo;

    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionRepo;

    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepo;

    /**
     * @var MCorpNewYearRepositoryInterface
     */
    protected $mCorpNewYearRepo;

    /**
     * @var MCorpSubRepositoryInterface
     */
    protected $mCorpSubRepo;

    /**
     * @var PublicHolidayRepositoryInterface
     */
    protected $publicHolidayRepo;

    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepo;

    /**
     * @var ShellLogService
     */
    protected $auctionLog;

    /**
     * @var array
     */
    protected $setting;

    /**
     * @var ExportService
     */
    protected $exportService;

    /**
     * AuctionAutoCallService constructor.
     *
     * @param DemandInfoRepositoryInterface $demandInfoRepository
     * @param AuctionInfoRepositoryInterface $auctionInfoRepository
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MCorpNewYearRepositoryInterface $mCorpNewYearRepository
     * @param MCorpSubRepositoryInterface $mCorpSubRepository
     * @param PublicHolidayRepositoryInterface $publicHolidayRepository
     * @param MItemRepositoryInterface $mItemRepo
     * @param ExportService $exportService
     */
    public function __construct(
        DemandInfoRepositoryInterface $demandInfoRepository,
        AuctionInfoRepositoryInterface $auctionInfoRepository,
        MCorpRepositoryInterface $mCorpRepository,
        MCorpNewYearRepositoryInterface $mCorpNewYearRepository,
        MCorpSubRepositoryInterface $mCorpSubRepository,
        PublicHolidayRepositoryInterface $publicHolidayRepository,
        MItemRepositoryInterface $mItemRepo,
        ExportService $exportService
    ) {
        $this->demandRepo = $demandInfoRepository;
        $this->auctionRepo = $auctionInfoRepository;
        $this->mCorpRepo = $mCorpRepository;
        $this->mCorpNewYearRepo = $mCorpNewYearRepository;
        $this->mCorpSubRepo = $mCorpSubRepository;
        $this->publicHolidayRepo = $publicHolidayRepository;
        $this->mItemRepo = $mItemRepo;
        $this->exportService = $exportService;
        $this->auctionLog = new ShellLogService('logs/auto_call.log', 'auction');
        $this->setting = [];
    }

    /**
     * @param bool $autoCallFlg
     * @throws \Exception
     */
    public function execute($autoCallFlg = false)
    {
        $this->saveLog(trans('auction_auto_call_job.job_start'));

        $this->setting = config('auto_call.setting');

        try {
            $listAuctionAutoCall = $this->demandRepo->getAutoCallList($autoCallFlg);

            $listAuctionAutoCall = $this->getAutoCallExceptHoliday($listAuctionAutoCall);

            if (config('datacustom.boolSalesFlag')) {
                $listAuctionAutoCall = $this->getAutoCallExceptOvertime($listAuctionAutoCall);
            }

            $csvPath = null;
            if (count($listAuctionAutoCall) > 0) {
                $csvPath = $this->generateCSV($listAuctionAutoCall);
            }
            if (!empty($csvPath)) {
                $this->executeAutoCall($csvPath);
                foreach ($listAuctionAutoCall as $row) {
                    if ($row->auction_infos_auto_call_flg !== 1) {
                        $updateData['id'] = $row->auction_id;
                        $updateData['auto_call_flg'] = 1;
                        $updateData['modified_user_id'] = self::USER;
                        $updated = $this->auctionRepo->updateFlag($updateData, ['auto_call_flg', 'modified_user_id']);
                        if ($updated == false) {
                            $this->saveLog(
                                trans('auction_auto_call_job.update_data_failed') . $updateData['id'],
                                self::ERROR_LOG
                            );
                        }
                    }
                }
            } else {
                $this->saveLog('auction_auto_call_job.no_csv_path', self::ERROR_LOG);
            }
        } catch (\Exception $exception) {
            $this->saveLog(trans('auction_auto_call_job.job_error') . $exception->getMessage(), self::ERROR_LOG);
        }
        $this->saveLog(trans('auction_auto_call_job.job_end'));
    }

    /**
     * Save log data
     *
     * @param string $msg
     * @param int $level
     * @throws \Exception
     */
    private function saveLog($msg, $level = self::INFO_LOG)
    {
        $this->auctionLog->log($msg, $level);
    }

    /**
     * In list of auto call, remove data which is holiday
     *
     * @param array $autoCalls
     * @return mixed
     */
    private function getAutoCallExceptHoliday($autoCalls)
    {
        $dateAndMonth = date('n/d');
        $week = config('datacustom.arrWeekZList');
        $dateInWeek = $week[date('w')];
        $currentDate = date('Y/n/d');
        $holidayItems = getDropList(self::HOLIDAY_CATEGORY);
        foreach ($autoCalls as $key => $val) {
            $mCorpNewYearItems = $this->mCorpNewYearRepo->getItemByMCorpId($val->m_corp_id);
            $autoCallFlg = true;
            if ($mCorpNewYearItems !== null) {
                $mCorpNewYearItems = $mCorpNewYearItems->toArray();
                for ($i = 1; $i <= 10; $i++) {
                    if (!empty($mCorpNewYearItems['label_' . sprintf('%02d', $i)])) {
                        if (strtotime($mCorpNewYearItems['label_' .
                            sprintf('%02d', $i)]) == strtotime($dateAndMonth) &&
                            strcmp(
                                $mCorpNewYearItems['status_' . sprintf('%02d', $i)],
                                self::WORKING_IN_HOLIDAY
                            ) !== 0
                        ) {
                            $autoCallFlg = false;
                            break;
                        }
                    }
                }
            }
            if ($autoCallFlg) {
                $autoCallFlg = $this->checkHoliday($autoCallFlg, $val, $currentDate, $holidayItems, $dateInWeek);
            }

            if (!$autoCallFlg) {
                unset($autoCalls[$key]);
            }
        }

        return $autoCalls;
    }

    /**
     * @param boolean $autoCallFlg
     * @param object $val
     * @param string $currentDate
     * @param array $holidayItems
     * @param array $dateInWeek
     * @return bool
     */
    private function checkHoliday($autoCallFlg, $val, $currentDate, $holidayItems, $dateInWeek)
    {
        $fields = ['*'];
        $orders = ['item_id' => 'asc'];
        $mCorpSub = $this->mCorpSubRepo->getItemByMCorpId($val->m_corp_id, $fields, $orders);
        foreach ($mCorpSub as $v) {
            if ((int)$v->item_id == 1) {
                $autoCallFlg = true;
            } elseif ((int)$v->item_id == 9) {
                if ($this->publicHolidayRepo->checkHoliday($currentDate)) {
                    $autoCallFlg = false;
                }
            } else {
                if ($holidayItems[$v->item_id] == $dateInWeek) {
                    $autoCallFlg = false;
                }
            }
        }

        return $autoCallFlg;
    }

    /**
     * In list of auto call, remove data contact is overtime
     *
     * @param array $autoCalls
     * @return mixed
     */
    private function getAutoCallExceptOvertime($autoCalls)
    {
        foreach ($autoCalls as $key => $value) {
            $mCorpAbleTime = $this->mCorpRepo->getContactableTime($value->m_corp_id);
            $nowTime = date("H:i");
            // if data exists
            // if contactable_support24hour is enabled set autoCallFlg is true
            // if contactable_support24hour is disabled and timeFrom less than nowTime and timeTo great than nowTime
            // set autoCallFlg is true
            // else autoCallFlg is false
            if ($mCorpAbleTime) {
                $autoCallFlg = true;
                if (intval($mCorpAbleTime->contactable_support24hour) == 0) {
                    $timeFrom = $mCorpAbleTime->contactable_time_from;
                    $timeTo = $mCorpAbleTime->contactable_time_to;
                    if ($nowTime < $timeFrom || $nowTime > $timeTo) {
                        $autoCallFlg = false;
                    }
                }
                if (!$autoCallFlg) {
                    unset($autoCalls[$key]);
                }
            }
        }
        return $autoCalls;
    }

    /**
     * Generate CSV file
     *
     * @param array $autoCalls
     * @return string
     */
    private function generateCSV($autoCalls)
    {
        $column = ['csv_id', 'name', 'number'];
        $csvDir = storage_path($this->setting['add_contact']['csv_dir']);
        if(!is_dir($csvDir)){
            //Directory does not exist, so lets create it.
            mkdir($csvDir, 0777, true);
        }
        $outPath = $csvDir . '/auto_call_' . date('YmdHis');
        $csvData = [];
        foreach ($autoCalls as $item) {
            $csvData[$item->m_corp_id] = ['', $item->m_corp_official_corp_name, $item->m_corp_commission_dial];
        }
        $this->exportService->generateCsv($outPath, $column, $csvData);

        return $outPath . '.csv';
    }

    /**
     * Show result when execute auto call
     *
     * @param $csvFile
     * @throws \Exception
     */
    private function executeAutoCall($csvFile)
    {
        $result = $this->callApiRegister($csvFile);

        $status = isset($result['status']) ? $result['status'] : null;
        $id = isset($result['id']) ? $result['id'] : null;
        $name = isset($result['name']) ? $result['name'] : null;
        $filename = isset($result['filename']) ? $result['filename'] : null;

        if ($status !== self::STATUS_API_SUCCESS) {
            $error = trans('auction_auto_call_job.call_api_failed') . " result:{contact_id:{$id}, contact_name:{$name}, status:{$status}}";
            $this->saveLog($error, self::ERROR_LOG);

            throw new \Exception($error);
        }

        $this->saveLog(
            trans('auction_auto_call_job.call_api_error') . "  result:{contact_id:{$id}, contact_name:{$name}, status:{$status}, filename:{$filename}}",
            self::ERROR_LOG
        );
    }

    /**
     * Send API with csv attachment
     *
     * @param object $csvFile
     * @return mixed
     * @throws \Exception
     */
    private function callApiRegister($csvFile)
    {

        if (isset($this->setting['api']['add_contact'])) {
            $url = $this->setting['api']['add_contact'];
        } else {
            throw new \Exception(trans('auction_auto_call_job.api_empty'));
        }

        $data = [];
        $data['name'] = self::DATA_NAME . date('YmdHis');
        if (isset($this->setting['add_contact']['campain_id'])) {
            $data['campain_id'] = $this->setting['add_contact']['campain_id'];
        }
        if (isset($this->setting['add_contact']['status'])) {
            $data['status'] = $this->setting['add_contact']['status'];
        }
        if (isset($this->setting['add_contact']['number_of_channels'])) {
            $data['number_of_channels'] = $this->setting['add_contact']['number_of_channels'];
        }
        $files = [
            'attachment' => $csvFile,
        ];
        $contents = $this->getContent('POST', $url, $data, $files);
        $result = json_decode($contents, true);

        return $result;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $params
     * @param array $files
     * @return bool|string
     * @throws \Exception
     */
    private function getContent($method, $url, $params = null, $files = null)
    {

        $formData = $this->makeMultipartFormData($params, $files);

        $header = [
            $formData['contentType'],
            "Content-Length: " . strlen($formData['data']),
        ];

        $context = [
            'http' => [
                'method' => $method,
                'header' => $header,
                'content' => $formData['data'],
            ],
        ];

        $this->saveLog(trans('auction_auto_call_job.connect_api') . ' url:' . $url);
        $response = file_get_contents($url, false, stream_context_create($context));

        if (!empty($httpResponseHeader)) {
            $statusCode = preg_split("<[[:space:]]+>", $httpResponseHeader[0]);

            if ($statusCode[1] != 200) {
                $error = trans('auction_auto_call_job.cant_get_data_api') . ' url:' . $url . ' status_code:' . $statusCode[1];
                $this->saveLog($error, self::ERROR_LOG);
                throw new \Exception($error);
            }
        } else {
            $error = trans('auction_auto_call_job.cant_get_data_api') . ' url:' . $url . ' timeout';
            $this->saveLog($error, self::ERROR_LOG);

            throw new \Exception($error);
        }

        return $response;
    }

    /**
     * @param array $params
     * @param array $files
     * @return array
     */
    private function makeMultipartFormData($params = null, $files = null)
    {
        $boundaryString = '---------------------------' . time();
        $contentType = "Content-Type:multipart/form-data;boundary=" . $boundaryString;
        $data = '';

        if (!empty($params)) {
            foreach ($params as $name => $value) {
                $data .= "--" . $boundaryString . "\r\n";
                $data .= 'Content-Disposition: form-data; name=' . $name . "\r\n";
                $data .= "\r\n";
                $data .= $value . "\r\n";
            }
        }

        foreach ($files as $name => $file) {
            $data .= "--" . $boundaryString . "\r\n";
            $data .= sprintf(
                "Content-Disposition: form-data; name=\"%s\"; filename=\"%s\"\r\n",
                $name,
                basename($file)
            );
            $data .= 'Content-Type: application/octet-stream' . "\r\n";
            $data .= "\r\n";
            $data .= file_get_contents($file) . "\r\n";
        }
        $data .= "--" . $boundaryString . "--\r\n";

        return [
            'contentType' => $contentType,
            'data' => $data,
        ];
    }
}
