<?php

namespace App\Services\WeatherForecast;

use App\Helpers\MailHelper;
use App\Repositories\DemandActualRepositoryInterface;
use App\Repositories\DemandForecastRepositoryInterface;
use App\Repositories\WeatherForecastRepositoryInterface;
use App\Repositories\WeatherRepositoryInterface;
use App\Services\Log\ShellLogService;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\File;

const DS = '/';

class WeatherForeCastService
{
    const ERROR_LOG = 400;

    const INFO_LOG = 200;

    const AREA_FORECAST = '区域予報';

    const DAY_RANGE = 2;

    const FORECAST_DIR = 'tmp/file/forecast';

    const USER = 'system';

    /**
     * @var string
     * I: INSERT
     * U: UPDATE
     * FC: FILE CLEAN 毎週月曜を予定
     * D: DELETE 予備
     */
    protected $action;

    /**
     * @var string
     * A: ALL -- NORMAL
     * WO: WEATHER OLD ONLY
     * WF: WEATHER FORECAST ONLY
     * DA: DEMAND ACTUAL ONLY
     * DF: DEMAND FORECAST ONLY
     */
    protected $type;

    /**
     * Format Y-m-d
     *
     * @var string date time
     */
    protected $targetDate;

    /**
     * @var integer
     */
    protected $rtn;

    /**
     * @var WeatherRepositoryInterface
     */
    protected $weatherRepository;

    /**
     * @var \Illuminate\Config\Repository|mixed
     * Area List
     */
    protected $areaList;

    /**
     * @var \Illuminate\Config\Repository|mixed
     * Region List
     */
    protected $regionList;

    /**
     * @var \Illuminate\Config\Repository|mixed
     * International Point Number List
     */
    protected $ipn;

    /**
     * @var ShellLogService
     */
    protected $weatherLog;

    /**
     * @var WeatherForecastRepositoryInterface
     */
    protected $weatherForecastRepository;

    /**
     * @var DemandActualRepositoryInterface
     */
    protected $demandForecastRepository;

    /**
     * @var DemandActualRepositoryInterface
     */
    protected $demandActualRepository;

    /**
     * WeatherForeCastService constructor.
     *
     * @param WeatherRepositoryInterface $weatherRepository
     * @param WeatherForecastRepositoryInterface $weatherForecastRepository
     * @param DemandActualRepositoryInterface $demandActualRepository
     * @param DemandForecastRepositoryInterface $demandForecastRepository
     */
    public function __construct(
        WeatherRepositoryInterface $weatherRepository,
        WeatherForecastRepositoryInterface $weatherForecastRepository,
        DemandActualRepositoryInterface $demandActualRepository,
        DemandForecastRepositoryInterface $demandForecastRepository
    ) {
        $this->weatherLog = new ShellLogService('logs/weather.log', 'weather');
        $this->weatherRepository = $weatherRepository;
        $this->weatherForecastRepository = $weatherForecastRepository;
        $this->demandActualRepository = $demandActualRepository;
        $this->demandForecastRepository = $demandForecastRepository;
        $this->action = 'I';
        $this->type = 0;
        $this->targetDate = null;
        $this->rtn = 1;
        $this->areaList = config('weather.area_state');
        $this->regionList = config('weather.state_region');
        $this->ipn = config('weather.international_point_number');
    }

    /**
     * Main function
     *
     * @param array $arguments
     * @throws \Exception
     */
    public function main($arguments)
    {
        $this->saveLog(trans('weather_forecast.command_start'));
        $this->initData($arguments);
        if ($this->action == 'I') {
            $this->mainActionI();
        } elseif ($this->action == 'U') {
            $this->mainActionU();
        } elseif ($this->action = 'FC') {
            $this->saveLog(trans('weather_forecast.rm_start'));
            $this->removeFile();
            $this->saveLog(trans('weather_forecast.rm_end'));
        }
    }

    /**
     * Log when run service and send mail alert if error.
     * @param null $msg
     * @param int $level
     * @throws \Exception
     */
    public function saveLog($msg = null, $level = self::INFO_LOG)
    {
        if (strpos($msg, 'EC') !== false) {
            MailHelper::sendRawMail(
                $msg,
                config('weather.mail_alert.subject'),
                config('weather.mail_alert.from_address'),
                config('weather.mail_alert.to_address')
            );
        }
        $this->weatherLog->log($msg, $level);
    }

    /**
     * Process argument when service run
     *
     * @param array $arguments
     * @throws \Exception
     */
    public function initData($arguments)
    {
        if ($arguments['action'] != null) {
            $this->action = $arguments['action'];
        } else {
            $this->saveLog(trans('weather_forecast.missing_action'), self::ERROR_LOG);
            $this->rtn = 0;
        }

        if ($arguments['type'] != null) {
            $this->type = $arguments['type'];
        } else {
            if ($this->action != 'FC') {
                $this->saveLog(trans('weather_forecast.missing_type'), self::ERROR_LOG);
                $this->rtn = 0;
            }
        }
        if ($arguments['date'] != null && Carbon::createFromFormat('Y-m-d', $arguments['date']) !== false) {
            $this->targetDate = $arguments['date'];
        }
    }

    /**
     * Execute if action = I
     * @throws \Exception
     */
    private function mainActionI()
    {
        DB::beginTransaction();
        if ($this->rtn && in_array($this->type, ['A', 'WO'])) {
            $this->saveLog(trans('weather_forecast.wo_start'));
            $this->rtn = $this->getOldWeather();
            $this->saveLog(trans('weather_forecast.wo_end'));
        }
        if ($this->rtn && in_array($this->type, ['A', 'WF'])) {
            $this->saveLog(trans('weather_forecast.wf_start'));
            $this->rtn = $this->getWeatherForecast();
            $this->saveLog(trans('weather_forecast.wf_end'));
        }
        if ($this->rtn && in_array($this->type, ['A', 'DA'])) {
            $this->rtn = $this->insertDemandActual();
        }
        if ($this->rtn && in_array($this->type, ['A', 'DF'])) {
            $this->rtn = $this->insertDemandForecast();
            if ($this->rtn) {
                $this->rtn = $this->reviseDemandForecast();
            }
        }
        if ($this->rtn) {
            $this->saveLog(trans('weather_forecast.chk_start'));
            $this->checkData($this->type, $this->targetDate);
            $this->saveLog(trans('weather_forecast.chk_end'));
        }

        if ($this->rtn) {
            DB::commit();
            $this->saveLog(trans('weather_forecast.commit_data'));
        } else {
            DB::rollBack();
            $this->saveLog(trans('weather_forecast.rollback_data'), self::ERROR_LOG);
        }
    }

    /**
     * Get weather forecast before 2 days
     *
     * @return int
     * @throws \Exception
     */
    public function getOldWeather()
    {
        $rtn = 1;
        $oldDate = strtotime("-2 day");
        $date = date("Ymd", $oldDate);
        $oldWeatherDir = storage_path(self::FORECAST_DIR) . DS . config('weather.fc_ftp_user3');

        if (!is_dir($oldWeatherDir)) {
            mkdir($oldWeatherDir, 777, true);
        }

        try {
            $remoteFileName = 'Z__C_JMBS_' . $date . '220000_STA_SURF_Rjp.tar.gz';
            $localFile = $oldWeatherDir . DS . $remoteFileName;
            // Not ready because FTP not ready to test.
            if (!$this->checkFileExist($localFile, $remoteFileName)) {
                return false;
            }

            //
            $year = date("Y", $oldDate);
            $month = date("m", $oldDate);
            $unCompressFile = $oldWeatherDir . '/surface/daily/' . $year . DS . $month;
            if (!File::exists($unCompressFile)) {
                system("tar xvfz " . $localFile . ' -C ' . $oldWeatherDir . ' ./surface/daily/ ', $ret);
                if ($ret != "0") {
                    $this->saveLog($localFile . ' ' . trans('weather_forecast.wo_unzip_failed'), self::ERROR_LOG);

                    return false;
                }
            }
            $weatherCount = $this->weatherRepository->countByDate(date("Y-m-d", $oldDate));
            if ($weatherCount == 0) {
                $weatherList = $this->parseFileWeather($oldDate, $oldWeatherDir, $year, $month);

                if (count($weatherList) > 0) {
                    foreach ($weatherList as $i => $weather) {
                        $weatherList[$i]['created'] = date("Y/m/d H:i:s", time());
                        $weatherList[$i]['modified'] = date("Y/m/d H:i:s", time());
                    }
                    if ($this->weatherRepository->insert($weatherList)) {
                        $this->saveLog(trans('weather_forecast.wo_update_success'));
                    } else {
                        $this->saveLog(trans('weather_forecast.wo_update_failed'), self::ERROR_LOG);
                        $rtn = 0;
                    }
                }
            }
        } catch (\Exception $e) {
            $rtn = 0;
            $this->saveLog($e->getMessage(), self::ERROR_LOG);
        }

        return $rtn;
    }

    /**
     * Check if file not exist then get file from ftp
     *
     * @param string $localFile
     * @param string $remoteFileName
     * @return bool
     * @throws \Exception
     */
    private function checkFileExist($localFile, $remoteFileName)
    {
        $result = true;
        if (!File::exists($localFile)) {
            $ftpValue = [
                'ftp_server' => config('weather.fc_ftp_address'),
                'ftp_user_name' => config('weather.fc_ftp_user3'),
                'ftp_user_pass' => config('weather.fc_ftp_pass'),
            ];
            $connection = ftp_connect($ftpValue['ftp_server']);
            $fptLogin = @ftp_login($connection, $ftpValue['ftp_user_name'], $ftpValue['ftp_user_pass']);
            if ($fptLogin) {
                $this->saveLog(trans('weather_forecast.wo_ftp_connect_success'));
            } else {
                $result = false;
                $this->saveLog(trans('weather_forecast.wo_ftp_connect_failed'), self::ERROR_LOG);
            }

            if ($result) {
                ftp_pasv($connection, true);

                if (@ftp_size($connection, $remoteFileName)) {
                    $ftpResult = @ftp_get($connection, $localFile, $remoteFileName, FTP_BINARY, false);

                    if ($ftpResult) {
                        $this->saveLog($remoteFileName . ' ' . trans('weather_forecast.wo_ftp_get_file_success'));
                    } else {
                        $result = false;
                        $this->saveLog(
                            $remoteFileName . ' ' . trans('weather_forecast.wo_ftp_get_file_failed'),
                            self::ERROR_LOG
                        );
                    }
                } else {
                    $result = false;
                    $this->saveLog(
                        $remoteFileName . ' ' . trans('weather_forecast.wo_ftp_get_file_failed'),
                        self::ERROR_LOG
                    );
                }
            }
            ftp_close($connection);
        }

        return $result;
    }

    /**
     * Parse file weather after get from ftp
     *
     * @param string $oldDate
     * @param string $oldWeatherDir
     * @param string $year
     * @param string $month
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function parseFileWeather($oldDate, $oldWeatherDir, $year, $month)
    {
        $idx = 0;
        $yearAndMonth = date("Ym", $oldDate);
        $yd = date("Ynj", $oldDate);
        $weatherDate = date("Y-m-d", $oldDate);
        $weatherList = [];

        foreach ($this->ipn as $key => $val) {
            $stateId = $key;
            $stateFile = $oldWeatherDir . '/surface/daily/' . $year . DS . $month . DS . 'sfc_d_' . $yearAndMonth . '.' . $val;
            if (!File::exists($stateFile)) {
                continue;
            }
            $fileContent = File::get($stateFile);

            $data = wordwrap(bin2hex($fileContent), 2908, ',', true);
            $arData = preg_split('~,~', $data);

            if ($arData) {
                foreach ($arData as $value) {
                    $y = mb_strcut($value, 16, 4);
                    $m = mb_strcut($value, 20, 4);
                    $j = mb_strcut($value, 24, 4);

                    $y = hexdec(mb_strcut($y, 2, 2) . mb_strcut($y, 0, 2));
                    $m = hexdec(mb_strcut($m, 2, 2) . mb_strcut($m, 0, 2));
                    $j = hexdec(mb_strcut($j, 2, 2) . mb_strcut($j, 0, 2));

                    if ($yd == $y . $m . $j) {
                        $d = mb_strcut($value, 484, 8);
                        $wa = substr_replace(
                            sprintf('%02d', hexdec(mb_strcut($d, 6, 2) .
                                mb_strcut($d, 4, 2) .
                                mb_strcut($d, 2, 2) .
                                mb_strcut($d, 0, 2))),
                            '.',
                            1,
                            0
                        );
                        $weatherList[$idx]['weather_datetime'] = $weatherDate;
                        $weatherList[$idx]['referer'] = config('weather.fc_referer');
                        $weatherList[$idx]['state_id'] = $stateId;
                        $weatherList[$idx]['wind_speed_avg'] = $wa;
                        $idx++;
                    }
                }
            }
        }

        return $weatherList;
    }

    /**
     * Get weather forecast today
     *
     * @return int
     * @throws \Exception
     */
    private function getWeatherForecast()
    {
        $increase = 0;
        $rtn = 1;
        $weatherList = [];
        $ftpValue = [
            'ftp_server' => config('weather.fc_ftp_address'),
            'ftp_user_name' => config('weather.fc_ftp_user1'),
            'ftp_user_pass' => config('weather.fc_ftp_pass'),
        ];
        $weatherForecastDir = storage_path(self::FORECAST_DIR) . DS . config('weather.fc_ftp_user1');

        if (!is_dir($weatherForecastDir)) {
            mkdir($weatherForecastDir, 777, true);
        }

        try {
            $connection = ftp_connect($ftpValue['ftp_server']);

            $ftpLogin = @ftp_login($connection, $ftpValue['ftp_user_name'], $ftpValue['ftp_user_pass']);
            if ($ftpLogin) {
                $this->saveLog(trans('weather_forecast.wf_ftp_connect_success'));
            } else {
                $this->saveLog(trans('weather_forecast.wf_ftp_connect_failed'), self::ERROR_LOG);

                return false;
            }
            ftp_pasv($connection, true);
            $fList = ftp_rawlist($connection, '-t .');
            $result = [];
            $oldDate = strtotime("-1 day");
            $targetName = '_' . date("d", $oldDate) . '2000_';
            foreach ($fList as $dl) {
                $fileInfo = preg_split("/[\s]+/", $dl, 9);
                if (strpos($fileInfo[8], $targetName) !== false && (strpos($fileInfo[8], '_CCA_') === false)) {
                    $result[] = $fileInfo[8];
                }
            }
            if (count($result) > 0) {
                $this->getFilesFromFtp($result, $connection, $weatherForecastDir);
                $weatherList = $this->parseFiles($result, $weatherForecastDir, $increase, $weatherList);
            } else {
                $this->saveLog(trans('weather_forecast.wf_ftp_get_list_failed'), self::ERROR_LOG);
            }
            if (count($weatherList) > 0) {
                foreach ($weatherList as $i => $weather) {
                    $weatherList[$i]['created'] = date("Y/m/d H:i:s", time());
                    $weatherList[$i]['modified'] = date("Y/m/d H:i:s", time());
                }
                if ($this->weatherForecastRepository->insert($weatherList)) {
                    $this->saveLog(trans('weather_forecast.wf_update_success'));
                } else {
                    $this->saveLog(trans('weather_forecast.wf_update_failed'), self::ERROR_LOG);
                    $rtn = 0;
                }
            }
        } catch (\Exception $e) {
            $rtn = 0;
            $this->saveLog($e->getMessage(), self::ERROR_LOG);
        }

        return $rtn;
    }

    /**
     * Get files from ftp
     *
     * @param array $results
     * @param resource $connection
     * @param string $weatherForecastDir
     * @throws \Exception
     */
    private function getFilesFromFtp($results, $connection, $weatherForecastDir)
    {
        foreach ($results as $fileName) {
            if (ftp_size($connection, $fileName)) {
                $ftpResult = @ftp_get($connection, $weatherForecastDir . DS . $fileName, $fileName, FTP_BINARY, false);
                if ($ftpResult) {
                    $this->saveLog($fileName . ' ' . trans('weather_forecast.wf_ftp_get_file_success'));
                } else {
                    $this->saveLog($fileName . ' ' . trans('weather_forecast.wf_ftp_get_file_failed'), self::ERROR_LOG);
                }
            } else {
                $this->saveLog($fileName . ' ' . trans('weather_forecast.wf_ftp_get_file_failed'), self::ERROR_LOG);
            }
        }
        ftp_close($connection);
    }

    /**
     * Parse files after get from ftp
     *
     * @param array $results
     * @param string $weatherForecastDir
     * @param integer $increase
     * @param array $weatherList
     * @return array|mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function parseFiles($results, $weatherForecastDir, &$increase, $weatherList)
    {
        foreach ($results as $fileName) {
            $weatherFile = $weatherForecastDir . DS . $fileName;
            if (!File::exists($weatherFile)) {
                continue;
            }
            $simple = simplexml_load_string(File::get($weatherFile));
            $xmlArray = json_decode(json_encode($simple), 1);
            if (!empty($xmlArray['Body']['MeteorologicalInfos'])) {
                $this->saveLog($fileName . ' ' . trans('weather_forecast.wf_read_xml_file_success'));
                $infoList = $xmlArray['Body']['MeteorologicalInfos'];

                $weatherList = $this->parseInfoList($infoList, $increase, $weatherList);
            } else {
                $this->saveLog(trans('weather_forecast.wf_read_xml_file_failed'), self::ERROR_LOG);
            }
        }
        return $weatherList;
    }

    /**
     * @param array $infoList
     * @param integer $increase
     * @param array $weatherList
     * @return array
     */
    private function parseInfoList($infoList, &$increase, $weatherList)
    {
        foreach ($infoList as $info) {
            if (isset($info['@attributes']['type']) && $info['@attributes']['type'] === self::AREA_FORECAST) {
                $stageFlg = false;
                if (!empty($info['TimeSeriesInfo']['Item'])) {
                    $this->checkTimeSeriesInfoItem($info, $increase, $idx, $stageFlg, $weatherList);
                }

                $idx = 0;
                if ($stageFlg) {
                    if (!empty($info['TimeSeriesInfo']['TimeDefines']['TimeDefine'][0]['DateTime'])) {
                        $times = $info['TimeSeriesInfo']['TimeDefines']['TimeDefine'];
                        foreach ($times as $key => $val) {
                            $idx = $increase + $key;
                            $datetime = date('Y-m-d H:i:s', strtotime($val['DateTime']));
                            $time = date('H:i:s', strtotime($val['DateTime']));
                            $weatherList[$idx]['forecast_datetime'] = $datetime;
                            $weatherList[$idx]['forecast_time'] = $time;
                        }
                    }
                    $increase = $idx + 1;
                }
            }
        }
        return $weatherList;
    }

    /**
     * Check item in TimeSeriesInfo
     * @param array $info
     * @param integer $increase
     * @param integer $idx
     * @param boolean $stageFlg
     * @param array $weatherList
     */
    private function checkTimeSeriesInfoItem($info, &$increase, &$idx, &$stageFlg, &$weatherList)
    {
        foreach ($info['TimeSeriesInfo']['Item'] as $item) {
            $stateId = '';
            if (!empty($item['Area']['Code'])) {
                if (!empty($this->areaList[$item['Area']['Code']])) {
                    $stateId = $this->areaList[$item['Area']['Code']];
                }
            } elseif (!empty($item['Code'])) {
                if (!empty($this->areaList[$item['Code']])) {
                    $stateId = $this->areaList[$item['Code']];
                }
            }

            if (!empty($stateId)) {
                $stageFlg = true;
                $regionId = $this->regionList[$stateId];

                $idx = $increase;
                if (!empty($item['Kind'][1]['Property']['WindSpeedPart']['WindSpeedLevel'])) {
                    $this->checkWindSpeedLevel(
                        $item['Kind'][1]['Property']['WindSpeedPart']['WindSpeedLevel'],
                        $idx,
                        $weatherList,
                        $increase,
                        $stateId,
                        $regionId
                    );
                } elseif (!empty($info['TimeSeriesInfo']['Item']['Kind'][1]['Property']['WindSpeedPart']['WindSpeedLevel'])) {
                    $this->checkWindSpeedLevel(
                        $info['TimeSeriesInfo']['Item']['Kind'][1]['Property']['WindSpeedPart']['WindSpeedLevel'],
                        $idx,
                        $weatherList,
                        $increase,
                        $stateId,
                        $regionId
                    );
                }
            }
        }
    }

    /**
     * Check item in WindSpeedLevel
     * @param array $windSpeedLevel
     * @param integer $idx
     * @param array $weatherList
     * @param integer $increase
     * @param integer $stateId
     * @param integer $regionId
     */
    private function checkWindSpeedLevel($windSpeedLevel, &$idx, &$weatherList, $increase, $stateId, $regionId)
    {
        foreach ($windSpeedLevel as $key => $val) {
            $idx = $increase + $key;
            $weatherList[$idx]['wind_speed_level'] = $val;
            $weatherList[$idx]['forecast_day_range'] = self::DAY_RANGE;
            $weatherList[$idx]['state_id'] = $stateId;
            $weatherList[$idx]['region_id'] = $regionId;
        }
    }

    /**
     * Insert data to demand actual
     *
     * @return int
     * @throws \Exception
     */
    private function insertDemandActual()
    {
        $this->saveLog(trans('weather_forecast.da_start'));
        $rtn = 1;
        try {
            $path = storage_path(config('weather.da_file_sql'));
            DB::unprepared(File::get($path));
            $this->saveLog(trans('weather_forecast.da_update_success'));
        } catch (\Exception $e) {
            $this->saveLog(trans('weather_forecast.da_update_failed'), self::ERROR_LOG);
            $this->saveLog($e->getMessage(), self::ERROR_LOG);
            $rtn = 0;
        }
        $this->saveLog(trans('weather_forecast.da_end'));

        return $rtn;
    }

    /**
     * Insert data to demand forecast table
     *
     * @return int
     * @throws \Exception
     */
    public function insertDemandForecast()
    {
        $this->saveLog(trans('weather_forecast.dw_start'));
        $rtn = 1;

        try {
            $path = storage_path(config('weather.dw_file_sql'));
            DB::unprepared(File::get($path));
            $this->saveLog(trans('weather_forecast.dw_update_success'));
        } catch (\Exception $e) {
            $this->saveLog(trans('weather_forecast.dw_update_failed'), self::ERROR_LOG);
            $this->saveLog($e->getMessage(), self::ERROR_LOG);
            $rtn = 0;
        }

        $this->saveLog(trans('weather_forecast.dw_end'));

        return $rtn;
    }

    /**
     * Revise data of demand forecast after insert
     *
     * @return int
     * @throws \Exception
     */
    public function reviseDemandForecast()
    {
        $this->saveLog(trans('weather_forecast.revise_dw_start'));
        $date = date('Y-m-d');
        $rtn = 1;

        try {
            if (!empty($this->targetDate)) {
                $date = $this->targetDate;
            }
            $query = File::get(storage_path(config('weather.revise_dw_file_sql')));
            $query = str_replace('$target_date', "'" . $date . "'", $query);
            DB::unprepared($query);
            $this->saveLog(trans('weather_forecast.revise_dw_success'));
        } catch (\Exception $e) {
            $this->saveLog(trans('weather_forecast.revise_dw_failed'), self::ERROR_LOG);
            $this->saveLog($e->getMessage(), self::ERROR_LOG);
            $rtn = 0;
        }

        $this->saveLog(trans('weather_forecast.revise_dw_end'));

        return $rtn;
    }

    /**
     * Check data after insert.
     *
     * @param string $type
     * @param null $targetDate
     * @throws \Exception
     */
    public function checkData($type = 'A', $targetDate = null)
    {
        try {
            if (in_array($type, ['A', 'WO'])) {
                $this->checkTypeInCaseOne($targetDate);
            }
            if (in_array($type, ['A', 'WF'])) {
                $this->checkTypeInCaseTwo($targetDate);
            }
            if (in_array($type, ['A', 'DA'])) {
                $this->checkTypeInCaseThree($targetDate);
            }
            if (in_array($type, ['A', 'DF'])) {
                $this->checkTypeInCaseFour($targetDate);
            }
        } catch (\Exception $e) {
            $this->saveLog(trans('weather_forecast.chk_failed'), self::ERROR_LOG);
            $this->saveLog($e->getMessage(), self::ERROR_LOG);
        }
    }

    /**
     * Execute if type in ['A', 'WO']
     * @param string $targetDate
     * @throws \Exception
     */
    private function checkTypeInCaseOne($targetDate)
    {
        $date = date('Y-m-d', strtotime('-2 day'));
        if (!empty($targetDate)) {
            $date = $targetDate;
        }
        $weatherCount = $this->weatherRepository->countByDate($date);
        $this->saveLog($weatherCount);

        if ($weatherCount <> 47) {
            $this->saveLog(trans('weather_forecast.chk_wo_failed'), self::ERROR_LOG);
        } else {
            $this->saveLog(trans('weather_forecast.chk_wo_success'));
        }
    }

    /**
     * Execute if type in ['A', 'WF']
     * @param string $targetDate
     * @throws \Exception
     */
    private function checkTypeInCaseTwo($targetDate)
    {
        $date1 = date('Y-m-d');
        $date2 = date('Y-m-d', strtotime('+2 day'));
        if (!empty($targetDate)) {
            $date1 = $targetDate;
            $date2 = date_create_from_format('Y-m-d', $targetDate);
            date_add($date2, date_interval_create_from_date_string('2 days'));
            $date2 = date_format($date2, 'Y-m-d');
        }
        $weatherForecastCount = $this->weatherForecastRepository->countByDateRange($date1, $date2);
        $this->saveLog($weatherForecastCount);

        if ($weatherForecastCount < 94) {
            $this->saveLog(trans('weather_forecast.chk_wf_failed'), self::ERROR_LOG);
        } else {
            $this->saveLog(trans('weather_forecast.chk_wf_success'));
        }
    }

    /**
     * Execute if type in ['A', 'DA']
     * @param string $targetDate
     * @throws \Exception
     */
    private function checkTypeInCaseThree($targetDate)
    {
        $date = date('Y-m-d', strtotime('-2 day'));
        if (!empty($targetDate)) {
            $date = $targetDate;
        }
        $countDemandActual = $this->demandActualRepository->countByDate($date);
        $this->saveLog($countDemandActual);

        if ($countDemandActual < 47) {
            $this->saveLog(trans('weather_forecast.chk_da_failed'), self::ERROR_LOG);
        } else {
            $this->saveLog(trans('weather_forecast.chk_da_success'));
        }
    }

    /**
     * Execute if type in ['A', 'DF']
     * @param string $targetDate
     * @throws \Exception
     */
    private function checkTypeInCaseFour($targetDate)
    {
        $date = date('Y-m-d');
        if (!empty($targetDate)) {
            $date = $targetDate;
        }
        $countDemandForecast = $this->demandForecastRepository->countByDate($date);

        $this->saveLog($countDemandForecast);

        if ($countDemandForecast == 0) {
            $this->saveLog(trans('weather_forecast.chk_df_failed'), self::ERROR_LOG);
        } else {
            $this->saveLog(trans('weather_forecast.chk_df_success'));
        }
    }

    /**
     * Execute if action = U
     * @throws \Exception
     */
    private function mainActionU()
    {
        if ($this->type == 'WO') {
            $this->saveLog(trans('weather_forecast.wo_start_up'));
            $this->saveLog(trans('weather_forecast.wo_end_up'));
        }

        if ($this->type == 'WF') {
            $this->saveLog(trans('weather_forecast.wf_start_up'));
            $this->saveLog(trans('weather_forecast.wf_end_up'));
        }

        if ($this->type == 'DA') {
            $this->saveLog(trans('weather_forecast.da_start_up'));
            $this->updateDemandActual($this->targetDate);
            $this->saveLog(trans('weather_forecast.da_end_up'));
        }

        if ($this->type == 'DF') {
            $this->saveLog(trans('weather_forecast.dw_start_up'));
            $this->updateDemandForecast($this->targetDate);
            $this->saveLog(trans('weather_forecast.dw_end_up'));
        }
    }

    /**
     * @param null $targetDate
     * @return int
     * @throws \Exception
     */
    private function updateDemandActual($targetDate = null)
    {
        $date = date('Y-m-d');
        $rtn = 1;
        try {
            if (!empty($targetDate)) {
                $date = $targetDate;
            }
            $query = File::get(storage_path(config('weather.update_demand_actual_sql')));
            $query = str_replace('$target_date', "'" . $date . "'", $query);
            DB::unprepared($query);
            $this->saveLog(trans('weather_forecast.da_up_success'));
        } catch (\Exception $e) {
            $this->saveLog(trans('weather_forecast.da_up_failed'), self::ERROR_LOG);
            $this->saveLog($e->getMessage(), self::ERROR_LOG);
            $rtn = 0;
        }

        return $rtn;
    }


    /**
     * @param null $targetDate
     * @return int
     * @throws \Exception
     */
    private function updateDemandForecast($targetDate = null)
    {
        $date = date('Y-m-d');
        $rtn = 1;
        try {
            if (!empty($targetDate)) {
                $date = $targetDate;
            }
            $query = File::get(storage_path(config('weather.update_demand_forecast_sql')));
            $query = str_replace('$target_date', "'" . $date . "'", $query);
            DB::unprepared($query);
            $this->saveLog(trans('weather_forecast.dw_up_success'));
        } catch (\Exception $e) {
            $this->saveLog(trans('weather_forecast.dw_up_failed'), self::ERROR_LOG);
            $this->saveLog($e->getMessage(), self::ERROR_LOG);
            $rtn = 0;
        }

        return $rtn;
    }

    /**
     * Remove file in weather forecast directory
     * @throws \Exception
     */
    public function removeFile()
    {
        $oldWeatherDir = storage_path(self::FORECAST_DIR) . DS . config('weather.fc_ftp_user3');
        $weatherForecastDir = storage_path(self::FORECAST_DIR) . DS . config('weather.fc_ftp_user1');
        try {
            system('rm -rf ' . $oldWeatherDir . DS . '* ' . $weatherForecastDir . DS . '*', $ret);
            if (intval($ret) == 0) {
                $this->saveLog(trans('weather_forecast.rm_success'));
            } else {
                $this->saveLog(trans('weather_forecast.rm_failed'), self::ERROR_LOG);
            }
        } catch (\Exception $e) {
            $this->saveLog(trans('weather_forecast.rm_failed'), self::ERROR_LOG);
            $this->saveLog($e->getMessage(), self::ERROR_LOG);
        }
    }
}
