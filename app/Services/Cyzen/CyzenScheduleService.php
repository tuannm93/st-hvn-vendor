<?php

namespace App\Services\Cyzen;

use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\CyzenSchedulesRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\DemandNotificationRepositoryInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CyzenScheduleService extends BaseCyzenServices
{
    /**
     * @var $cyzenScheduleRepository
     */
    protected $cyzenScheduleRepository;
    /**
     * @var \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     */
    protected $cyzenGroupService;
    /**
     * @var \App\Services\Cyzen\CyzenSpotServices $cyzenSpotService
     */
    protected $cyzenSpotService;

    /**
     * @var \App\Services\Cyzen\CyzenUserServices $cyzenUserService
     */
    protected $cyzenUserService;

    /** @var DemandInfoRepositoryInterface $demandRepo */
    protected $demandRepo;

    /** @var DemandNotificationRepositoryInterface $demandNotificationRepo */
    protected $demandNotificationRepo;

    /** @var CommissionInfoRepositoryInterface $commissionRepo */
    protected $commissionRepo;

    /**
     * @var string $path
     */
    private $path = '/webapi/v0/schedules';

    /**
     * CyzenScheduleService constructor.
     *
     * @param \App\Repositories\CyzenSchedulesRepositoryInterface $cyzenSchedulesRepository
     * @param \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     * @param \App\Services\Cyzen\CyzenSpotServices $cyzenSpotServices
     * @param \App\Services\Cyzen\CyzenUserServices $cyzenUserServices
     * @param DemandInfoRepositoryInterface $demandInfoInterface
     * @param DemandNotificationRepositoryInterface $demandNotificationInterface
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @throws \Exception
     */
    public function __construct(
        CyzenSchedulesRepositoryInterface $cyzenSchedulesRepository,
        CyzenGroupService $cyzenGroupService,
        CyzenSpotServices $cyzenSpotServices,
        CyzenUserServices $cyzenUserServices,
        DemandInfoRepositoryInterface $demandInfoInterface,
        DemandNotificationRepositoryInterface $demandNotificationInterface,
        CommissionInfoRepositoryInterface $commissionInfoRepository
    ) {
        parent::__construct(BaseCyzenServices::LOG_PATH_SCHEDULE);
        $this->cyzenScheduleRepository = $cyzenSchedulesRepository;
        $this->cyzenGroupService = $cyzenGroupService;
        $this->cyzenSpotService = $cyzenSpotServices;
        $this->cyzenUserService = $cyzenUserServices;
        $this->demandRepo = $demandInfoInterface;
        $this->demandNotificationRepo = $demandNotificationInterface;
        $this->commissionRepo = $commissionInfoRepository;
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->logger->log(Logger::INFO, '==========START CRON JOB GET SCHEDULE ONE MONTH==========');
        $now = date('Y-m-d H:i:s');
        if ($this->checkExistDataInDb()) {
            $lastUpdated = $this->cyzenScheduleRepository->getLastUpdatedDate();
            $curDate = strtotime($lastUpdated->updated_at);
            $timeDiff = 7 * 24 * 60 * 60;
            $executeTime = 0;
            $rawData = null;
            do { //Call 4 time for get 1 month data
                $queryStartDate = date('Y-m-d\TH:i:s', $curDate + $executeTime * $timeDiff);
                $nextStartDate = null;
                do { // Call if result have pagination
                    $query['from_date'] = $queryStartDate;
                    $query['to_date'] = date('Y-m-d\TH:i:s', strtotime($queryStartDate . ' + 7 days'));
                    $query['get_deleted'] = 1;
                    $nextStartDate = (isset($rawData['next_start_date'])) ? $rawData['next_start_date'] : null;
                    if (!empty($nextStartDate)) {
                        $query['next_start_date'] = $nextStartDate;
                    }
                    $rawData = $this->get($this->path, $query);
                    $this->saveDataSchedule($rawData, $now);
                    //sleep 1s for API call limit
                    sleep(1);
                } while ($nextStartDate !== null);
                $executeTime++;
            } while ($executeTime < 4);
        } else {
            $rawData = null;
            $query['get_deleted'] = 1;
            do {
                if (isset($rawData['next_start_date']) && !empty($rawData['next_start_date'])) {
                    $query['next_start_date'] = $rawData['next_start_date'];
                }
                $rawData = $this->get($this->path, $query);
                $this->saveDataSchedule($rawData, $now);
            } while (!empty($rawData['next_start_date']));
        }
        $this->logger->log(Logger::INFO, '==========END CRON JOB GET SCHEDULE ONE MONTH==========');
        return null;
    }

    /**
     * Check if exist in DB for first run crond
     */
    private function checkExistDataInDb()
    {
        $query = $this->cyzenScheduleRepository->getCountData();
        if ($query == 0) {
            return false;
        }
        return true;
    }

    /**
     * @param $rawData
     * @param $nowDate
     * @throws \Exception
     */
    private function saveDataSchedule($rawData, $nowDate)
    {
        foreach ($rawData['schedules'] as $key => $item) {
            $item['crawler_time'] = $nowDate;
            $importData = $this->initParams($item);

            if (isset($item['deleted_at']) && strlen(trim($item['deleted_at'])) > 0) {
                $this->executeDemandInfoAndCommissionInfo($item['schedule_id']);
                continue;
            }
            //check table cyzen_schedule_users & cyzen_users
            $scheduleUserData = [];
            foreach ($item['users'] as $user) {
                $isUserReady = $this->checkDbRelationship(
                    $user['user_id'],
                    $this->cyzenUserService,
                    $this->cyzenScheduleRepository
                );
                if (!$isUserReady) {
                    continue 2;
                }
                $scheduleUserData[] = [
                    'schedule_id' => $item['schedule_id'],
                    'user_id' => $user['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'crawler_time' => $nowDate,
                ];
            }

            //check table cyzen_groups
            $isGroupReady = $this->checkDbRelationship(
                $importData['group_id'],
                $this->cyzenGroupService,
                $this->cyzenScheduleRepository
            );
            //check table cyzen_spots

            if ($isGroupReady) {
                $this->processingData($importData, $this->cyzenScheduleRepository);
                if (!empty($scheduleUserData)) {
                    $this->cyzenScheduleRepository->saveScheduleUser($scheduleUserData);
                }
                // update demand_notifications table
                $this->updateDemandNotification(
                    $importData['spot_id'],
                    $item['users'],
                    $importData['start_date'],
                    $importData['end_date']
                );
            }
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function initParams($data)
    {
        return $params = [
            'id' => $data['schedule_id'],
            'group_id' => $data['group_id'],
            'title' => $data['title'],
            'detail' => $data['detail'],
            'start_date' => gmt_to_jst_time($data['start_date']),
            'end_date' => gmt_to_jst_time($data['end_date']),
            'is_all_day' => $data['all_day'],
            'spot_id' => isset($data['place']['spot_id']) ? $data['place']['spot_id'] : null,
            'address' => $data['place']['address'],
            'location' => \DB::raw('ST_GeographyFromText(\'point(' . $data['place']['longitude'] . ' ' . $data['place']['latitude'] . ')\')'),
            'created_at' => gmt_to_jst_time($data['created_at']),
            'updated_at' => gmt_to_jst_time($data['updated_at']),
            'crawler_time' => $data['crawler_time']
        ];
    }

    /**
     * @param $scheduleId
     */
    public function executeDemandInfoAndCommissionInfo($scheduleId)
    {
        $result = $this->demandNotificationRepo->getDemandIdAndUserIdFromScheduleId($scheduleId);
        if (!empty($result)) {
            foreach ($result as $item) {
                try {
                    $this->demandNotificationRepo->updateStatusWhenDeleteSchedule(
                        $item['demand_id'],
                        $item['user_id'],
                        $this->logger
                    );
                    $this->commissionRepo->updateWorkStatusAfterDeleteSchedule($item['commission_id'], $this->logger);
                    $this->cyzenScheduleRepository->deleteScheduleById($scheduleId);
                    $this->logger->log(
                        Logger::WARNING,
                        'DELETE AT SCHEDULE: ' . $scheduleId . ' >>> ' . $item['user_id'] . ' >>> ' . $item['commission_id']
                    );
                } catch (\Exception $exception) {
                    $this->logger->log(
                        Logger::ALERT,
                        'DELETE FAIL AT SCHEDULE: ' . $scheduleId . ' >>> ' . $item['user_id'] . ' >>> ' . $item['commission_id'] .
                        $exception->getMessage()
                    );
                }
            }
        } else {
            try {
                $this->logger->log(Logger::WARNING, 'DELETE SCHEDULE NOT MATCH IN DEMAND NOTIFICATION: ' . $scheduleId);
                $this->cyzenScheduleRepository->deleteScheduleById($scheduleId);
            } catch (\Exception $exception) {
                $this->logger->log(Logger::ALERT, 'DELETE FAIL AT SCHEDULE: ' . $scheduleId . $exception->getMessage());
            }
        }
    }

    /**
     * @param $spotId
     * @param $listUsers
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    protected function updateDemandNotification($spotId, $listUsers, $startDate, $endDate)
    {
        if (empty($listUsers)) {
            return null;
        }

        foreach ($listUsers as $user) {
            $staffId = $this->cyzenScheduleRepository->getStaffFromUserId($user['user_id']);
            $demandId = $this->cyzenScheduleRepository->getDemandIdFromSpot($spotId);

            if ($staffId && $demandId) {
                $importData = [
                    'demand_id' => $demandId,
                    'user_id' => $staffId,
                    'draft_start_time' => $startDate,
                    'draft_end_time' => $endDate
                ];
                $this->cyzenScheduleRepository->updateDemandNotification($importData);
            }
        }
        return null;
    }

    /**
     * @return null
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handleOneDay()
    {
        $this->logger = new Logger('Cyzen');
        $this->logger->pushHandler(
            new StreamHandler(createLogPathCyzen(BaseCyzenServices::LOG_PATH_SCHEDULE_ONE_DAY), Logger::INFO)
        );
        $this->logger->log(Logger::INFO, '==========START CRON JOB GET SCHEDULE ONE DAY==========');
        $now = date('Y-m-d H:i:s');
        $tmp = date('Y-m-d');
        $start = date('Y-m-d H:i:s', strtotime($tmp));
        $end = date('Y-m-d H:i:s', strtotime($tmp . ' + 1 day - 1 second'));
        $resultApi = null;
        $query['get_deleted'] = 1;
        do {
            $query['from_date'] = date('Y-m-d\TH:i:s', strtotime($start));
            $query['to_date'] = date('Y-m-d\TH:i:s', strtotime($end));
            if (isset($resultApi['next_start_date']) && !empty($resultApi['next_start_date'])) {
                $query['next_start_date'] = $resultApi['next_start_date'];
            }
            $resultApi = $this->get($this->path, $query);
            $this->saveDataSchedule($resultApi, $now);
        } while (!empty($resultApi['next_start_date']));

        $this->logger->log(Logger::INFO, '==========END CRON JOB GET SCHEDULE ONE DAY==========');
        return null;
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createSchedule(array $params)
    {
        $created = $this->post($this->path, $params);
        if ($created) {
            return $created['schedule_id'];
        }
        return null;
    }

    /**
     * @param $demandId
     * @return array
     */
    public function getGenreAndCategoryNameFromDemandId($demandId)
    {
        $result = $this->demandRepo->getGenreCategoryNameByDemand($demandId);
        return $result;
    }

    /**
     * @throws \Exception
     */
    public function deleteScheduleManual()
    {
        $this->logger->log(Logger::INFO, '==========START DELETE SCHEDULE MANUAL==========');
        $resultApi = null;
        $paramRequest['get_deleted'] = 1;
        do {
            if (isset($resultApi['next_start_date']) && !empty($resultApi['next_start_date'])) {
                $paramRequest['next_start_date'] = $resultApi['next_start_date'];
            }
            $resultApi = $this->get($this->path, $paramRequest);
            if (is_array($resultApi['schedules']) && count($resultApi['schedules']) > 0) {
                foreach ($resultApi['schedules'] as $key => $item) {
                    if (isset($item['deleted_at']) && strlen(trim($item['deleted_at'])) > 0) {
                        $this->executeDemandInfoAndCommissionInfo($item['schedule_id']);
                    }
                }
            }
            sleep(1);
        } while (!empty($resultApi['next_start_date']));
        $this->logger->log(Logger::INFO, '==========END DELETE SCHEDULE MANUAL==========');
    }
}
