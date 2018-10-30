<?php

namespace App\Services\Cyzen;

use App\Models\Cyzen\CyzenSpotTag;
use App\Repositories\CyzenSpotRepositoryInterface;
use App\Repositories\CyzenUserRepositoryInterface;
use Illuminate\Database\QueryException;
use Monolog\Logger;

class CyzenSpotServices extends BaseCyzenServices
{
    /** @var  CyzenSpotRepositoryInterface $cyzenSpotRepository */
    protected $cyzenSpotRepository;

    /** @var CyzenUserRepositoryInterface $cyzenUserRepository */
    protected $cyzenUserRepository;

    /** @var string $path */
    private $path = '/webapi/v0/spots';

    /**
     * CyzenSpotServices constructor.
     *
     * @param CyzenSpotRepositoryInterface $cyzenSpotInterface
     * @param CyzenUserRepositoryInterface $cyzenUserInterface
     * @throws \Exception
     */
    public function __construct(
        CyzenSpotRepositoryInterface $cyzenSpotInterface,
        CyzenUserRepositoryInterface $cyzenUserInterface
    ) {
        parent::__construct(BaseCyzenServices::LOG_PATH_SPOT);
        $this->cyzenSpotRepository = $cyzenSpotInterface;
        $this->cyzenUserRepository = $cyzenUserInterface;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function executeApi()
    {
        //init variable for crawler
        $this->logger->log(Logger::INFO, '==========START CRON JOB GET SPOT==========');
        $nowDate = date('Y-m-d H:i:s');
        $listPeriod = [];
        //get last time crawler of spot
        $result = $this->cyzenSpotRepository->getLastSpotByCrawlerTime();
        if (!empty($result)) {
            $lastCrawlerTime = $result->crawler_time;
            $listPeriod = $this->getTimePeriodToCrawler($nowDate, $lastCrawlerTime);
        }
        if (is_array($listPeriod) && count($listPeriod) > 0) {
            foreach ($listPeriod as $paramObject) {
                $this->progressGetApi($this->path, $paramObject, $nowDate);
            }
        } else {
            $this->progressGetApi($this->path, [], $nowDate);
        }
        $this->logger->log(Logger::INFO, '==========END CRON JOB GET SPOT==========');
        return null;
    }

    /**
     * get all period from current time to last time crawler
     *
     * @param $lastCrawlerDate
     * @param $currentDate
     * @return array
     */
    private function getTimePeriodToCrawler($currentDate, $lastCrawlerDate = null)
    {
        //limit time diff by CyzenApi is 7 days -> equal 604800 second
        $maxSecondDiff = 604800;
        $paramRequestTimePeriod = [];
        //check if exist crawler time
        if (!empty($lastCrawlerDate)) {
            //exchange to second for calculate
            $timeLastCrawler = strtotime($lastCrawlerDate);
            $timeCurrent = strtotime($currentDate);
            //calculate number period time (start-end) with max 7 days from current date back
            do {
                $timePeriod = [];
                $timePeriod['updated_to'] = date(BaseCyzenServices::TIME_FORMAT_CYZEN_API, $timeCurrent);
                $timePeriod['updated_from'] = date(
                    BaseCyzenServices::TIME_FORMAT_CYZEN_API,
                    $timeCurrent - $maxSecondDiff
                );
                if ($timeCurrent - $maxSecondDiff <= $timeLastCrawler) {
                    $timePeriod['updated_from'] = date(BaseCyzenServices::TIME_FORMAT_CYZEN_API, $timeLastCrawler);
                }
                array_push($paramRequestTimePeriod, $timePeriod);
                //new point ['updated_to']
                $timeCurrent = $timeCurrent - $maxSecondDiff - 1;
            } while ($timeCurrent > $timeLastCrawler);
        }

        return $paramRequestTimePeriod;
    }

    /**
     * @param $path
     * @param $paramRequest
     * @param $nowDate
     * @throws \Exception
     */
    private function progressGetApi($path, $paramRequest, $nowDate)
    {
        $resultApi = null;
        do {
            if (!empty($resultApi)) {
                $paramRequest['next_spot_id'] = $resultApi['next_spot_id'];
            }
            $resultApi = $this->get($path, $paramRequest);
            if (is_array($resultApi['spots']) && count($resultApi['spots']) > 0) {
                $this->progressData($resultApi['spots'], $nowDate);
            }
            sleep(1);
        } while (!empty($resultApi['next_spot_id']));
    }

    /**
     * @param $listSpot
     * @param $nowDate
     */
    private function progressData($listSpot, $nowDate)
    {
        foreach ($listSpot as $arr) {
            try {
                $id = $arr['spot_id'];
                unset($arr['spot_id']);
                unset($arr['spot_tags']);
                $arr['crawler_time'] = $nowDate;
                $arr['spot_location'] = \DB::raw('point(' . $arr['latitude'] . ', ' . $arr['longitude'] . ')::geometry');
                unset($arr['latitude']);
                unset($arr['longitude']);
                $arr['zip_code'] = (int)$arr['zip_code'];
                $arr['tel'] = (int)$arr['tel'];
                $arr['fax'] = (int)$arr['fax'];
                $arr['valid_from'] = empty($arr['valid_from']) ? null : $arr['valid_from'];
                $arr['valid_to'] = empty($arr['valid_to']) ? null : $arr['valid_to'];
                $arr['spot_name'] = empty($arr['spot_name']) ? null : $arr['spot_name'];
                $this->cyzenSpotRepository->updateOrInsertData($id, $arr);
            } catch (QueryException $ex) {
                $this->logger->log(Logger::CRITICAL, $ex->getMessage());
            }
        }
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->cyzenSpotRepository->getModel();
    }

    /**
     * @param $address
     * @param $demandId
     * @param $userId
     * @param $latitude
     * @param $longitude
     * @param string $kanaName
     * @param string $zipCode
     * @param string $url
     * @param string $comment
     * @param string $tel
     * @param string $fax
     * @param string $start
     * @param string $end
     * @param string $groupId
     * @param string $spotTagId
     * @param array $extendData
     * @return bool|mixed
     * @throws \Exception
     */
    public function createSpot(
        $address,
        $demandId,
        $userId,
        $latitude,
        $longitude,
        $kanaName = '',
        $zipCode = '',
        $url = '',
        $comment = '',
        $tel = '',
        $fax = '',
        $start = '',
        $end = '',
        $groupId = '',
        $spotTagId = '',
        $extendData = []
    ) {
        $params = [
            'address' => $address,
            'spot_code' => md5($demandId . '-' . $userId . '-' . date('U')),
            'kana_name' => $kanaName,
            'spot_name' => $demandId,
            'zip_code' => $zipCode,
            'url' => $url,
            'comment' => $comment,
            'tel' => $tel,
            'fax' => $fax,
            'valid_from' => $start,
            'valid_to' => $end,
            'group_id' => $groupId,
            'lat' => $latitude,
            'lon' => $longitude,
            'spot_tag_id' => $spotTagId
        ];
        if (!empty($extendData)) {
            $params['extend_data'] = json_encode($extendData);
        }
        $params = array_filter($params);

        if ($this->validatePostParams($params)) {
            $result = $this->post($this->path, $params);
            $this->getById($result['spot_id']);
            return $result;
        } else {
            return null;
        }
    }

    /**
     * @param array $params
     * @return bool
     * @throws \Exception
     */
    protected function validatePostParams(array $params)
    {
        if ($this->checkExistSpotCode($params['spot_code'])) {
            $this->logger->log(Logger::ERROR, 'spot_code is exist ' . json_encode($params));
            return false;
        }
        return true;
    }

    /**
     * Check if exist spot code
     * @param $code
     * @return bool
     * @throws \Exception
     */
    public function checkExistSpotCode($code)
    {
        $query['spot_code'] = $code;
        $rawData = $this->get($this->path, $query);

        return (!empty($rawData['spots'])) ? true : false;
    }

    /**
     * @param $id
     * @return bool|mixed
     * @throws \Exception
     */
    public function getById($id)
    {
        //call api
        $now = date('Y-m-d H:i:s');
        $query = ['spot_id' => $id];
        $rawData = $this->get($this->path, $query);

        if ($rawData['spots']) {
            $rawData['spots'][0]['crawler_time'] = $now;
            $importData = $this->initParams($rawData['spots'][0]);
            $isSaved = $this->processingData($importData, $this->cyzenSpotRepository);
            return $isSaved;
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function initParams($data)
    {
        return [
            'id' => $data['spot_id'],
            'spot_code' => $data['spot_code'],
            'spot_name_kana' => $data['spot_name_kana'],
            'spot_name' => $data['spot_name'],
            'zip_code' => (int)$data['zip_code'],
            'tel' => (int)$data['tel'],
            'fax' => (int)$data['fax'],
            'address' => $data['address'],
            'valid_from' => ($data['valid_from']) ? gmt_to_jst_time($data['valid_from']) : null,
            'valid_to' => ($data['valid_to']) ? gmt_to_jst_time($data['valid_to']) : null,
            'url' => $data['url'],
            'comment' => $data['comment'],
            'create_user_id' => $data['create_user_id'],
            'spot_location' => \DB::raw('ST_GeographyFromText(\'point(' . $data['longitude'] . ' ' . $data['latitude'] . ')\')'),
            'created_at' => gmt_to_jst_time($data['created_at']),
            'updated_at' => gmt_to_jst_time($data['updated_at']),
            'crawler_time' => $data['crawler_time'],
        ];
    }

    /**
     * @param $spotId
     * @param $address
     * @param $spotCode
     * @param $latitude
     * @param $longitude
     * @param $spotName
     * @param string $kanaName
     * @param string $zipCode
     * @param string $url
     * @param string $comment
     * @param string $tel
     * @param string $fax
     * @param string $start
     * @param string $end
     * @param string $groupId
     * @param string $spotTagId
     * @param array $extendData
     * @return array|mixed|null
     * @throws \Exception
     */
    public function updateSpot(
        $spotId,
        $address,
        $spotCode,
        $latitude,
        $longitude,
        $spotName,
        $kanaName = '',
        $zipCode = '',
        $url = '',
        $comment = '',
        $tel = '',
        $fax = '',
        $start = '',
        $end = '',
        $groupId = '',
        $spotTagId = '',
        $extendData = []
    ) {
        $params = [
            'spot_id' => $spotId,
            'address' => $address,
            'spot_code' => $spotCode,
            'kana_name' => $kanaName,
            'spot_name' => $spotName,
            'zip_code' => $zipCode,
            'url' => $url,
            'comment' => $comment,
            'tel' => $tel,
            'fax' => $fax,
            'valid_from' => $start,
            'valid_to' => $end,
            'group_id' => $groupId,
            'lat' => $latitude,
            'lon' => $longitude,
            'spot_tag_id' => $spotTagId
        ];
        if (!empty($extendData)) {
            $params['extend_data'] = json_encode($extendData);
        }
        $params = array_filter($params);


        $result = $this->put($this->path, $params);
        if (isset($result['modified'])) {
            $this->getById($result['spot_id']);
        }
        return $result;
    }

    /**
     * @param $id
     * @param $groupId
     * @return mixed
     */
    public function checkSpotId($id, $groupId)
    {
        return $this->cyzenSpotRepository->checkSpotId($id, $groupId);
    }

    /**
     * @param $staffId
     * @return CyzenSpotTag
     */
    public function getTagByStaff($staffId)
    {
        $tag = $this->cyzenSpotRepository->getTagByStaff($staffId);
        if (!$tag) {
            $this->logger->log(Logger::CRITICAL, 'created or update demand fail , empty tag staff_id:' . $staffId);
        }
        return $tag;
    }
}
