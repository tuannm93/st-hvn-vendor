<?php

namespace App\Services\Cyzen;

use App\Repositories\CyzenHistoryRepositoryInterface;
use App\Repositories\CyzenStatusRepositoryInterface;
use Monolog\Logger;

class CyzenHistoryService extends BaseCyzenServices
{
    /**
     * @var $cyzenScheduleRepository
     */
    protected $cyzenHistoryRepository;

    /**
     * @var \App\Repositories\CyzenStatusRepositoryInterface $cyzenStatusRepository
     */
    protected $cyzenStatusRepository;
    /**
     * @var \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     */
    protected $cyzenGroupService;

    /**
     * @var \App\Services\Cyzen\CyzenUserServices $cyzenUserService
     */
    protected $cyzenUserService;

    /**
     * @var string $path
     */
    private $path = '/webapi/v0/histories';

    /**
     * @var int $apiDateRange | second ( 15 days )
     */
    private $apiDateRange = 1296000;

    /**
     * CyzenHistoryService constructor.
     *
     * @param \App\Repositories\CyzenHistoryRepositoryInterface $cyzenHistoryRepository
     * @param \App\Repositories\CyzenStatusRepositoryInterface $cyzenStatusRepository
     * @param \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     * @param \App\Services\Cyzen\CyzenUserServices $cyzenUserServices
     * @throws \Exception
     */
    public function __construct(
        CyzenHistoryRepositoryInterface $cyzenHistoryRepository,
        CyzenStatusRepositoryInterface $cyzenStatusRepository,
        CyzenGroupService $cyzenGroupService,
        CyzenUserServices $cyzenUserServices
    ) {
        parent::__construct(BaseCyzenServices::LOG_PATH_HISTORY);
        $this->cyzenHistoryRepository = $cyzenHistoryRepository;
        $this->cyzenStatusRepository = $cyzenStatusRepository;
        $this->cyzenGroupService = $cyzenGroupService;
        $this->cyzenUserService = $cyzenUserServices;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->logger->log(Logger::INFO, '==========START CRON JOB GET HISTORY==========');
        $now = date('Y-m-d\TH:i:s');
//        $now = date('Y-m-d\TH:i:s', strtotime($now . ' - 5 minutes'));
//        $newest = date('Y-m-d\TH:i:s', strtotime($now . ' - 10 hours'));

        // The parameter corresponds to Greenwich time
        $now = date('Y-m-d\TH:i:s', strtotime($now . ' - 9 hours, - 5 minutes'));
        $newest = date('Y-m-d\TH:i:s', strtotime($now . ' - 1 hours'));

        $query = ['from_date' => $newest, 'to_date' => $now];
        $rawData = null;
        do {
            if (isset($rawData['next_start_date']) && !empty($rawData['next_start_date'])) {
                $query['next_history_id'] = $rawData['next_history_id'];
            }
            $rawData = $this->get($this->path, $query);
            $this->handleApiResult($rawData['histories'], $now);
        } while (!empty($rawData['next_history_id']));

        $this->logger->log(Logger::INFO, '==========END CRON JOB GET HISTORY==========');
        return null;
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    public function getListQueryDate($from, $to)
    {
        $from = date('Y-m-d\TH:i:s', strtotime($from));
        $to = date('Y-m-d\TH:i:s', strtotime($to));
        $queryDate = [];
        $diff = strtotime($to) - strtotime($from);

        if ($diff > $this->apiDateRange) {
            $step = ceil($diff / $this->apiDateRange);

            for ($i = 1; $i <= $step; $i++) {
                if (! isset($end)) {
                    $start = $from;
                    $end = date('Y-m-d\TH:i:s', strtotime($start.' + '.$this->apiDateRange.' seconds'));
                } else {
                    $start = date('Y-m-d\TH:i:s', strtotime($end.' + 1 second'));
                    $end = ($i == $step) ? $to : date('Y-m-d H:i:s', strtotime($start.' + '.$this->apiDateRange.' seconds'));
                }
                $queryDate[] = ['from_date' => $start, 'to_date' => $end];
            }
        } else {
            $queryDate[] = ['from_date' => $from, 'to_date' => $to];
        }

        return $queryDate;
    }

    /**
     * @param $data
     * @param $now
     * @return null
     * @throws \Exception
     */
    public function handleApiResult($data, $now)
    {
        if (empty($data)) {
            return null;
        }

        foreach ($data as $key => $item) {
            $item['crawler_time'] = $now;
            $importData = $this->initParams($item);

            //check table cyzen_users
            $isUserReady = $this->checkDbRelationship(
                $item['user_id'],
                $this->cyzenUserService,
                $this->cyzenHistoryRepository
            );

            //check table cyzen_groups
            $isGroupReady = $this->checkDbRelationship(
                $item['group_id'],
                $this->cyzenGroupService,
                $this->cyzenHistoryRepository
            );

            //processing data
            if ($isUserReady && $isGroupReady) {
                $this->processingData($importData, $this->cyzenHistoryRepository);
            }
        }

        //sleep for api limit
        sleep(1);

        return null;
    }

    /**
     * @param $data
     * @return array
     */
    public function initParams($data)
    {
        return [
            'id' => $data['history_id'],
            'user_id' => $data['user_id'],
            'group_id' => $data['group_id'],
            'history_comment' => $data['history_comment'],
            'status_id' => $data['status_id'],
            'address' => $data['address'],
            'history_accuracy' => $data['history_accuracy'],
            'history_location' => \DB::raw('ST_GeographyFromText(\'point('.$data['history_longitude'].' '.$data['history_latitude'].')\')'),
            'created_at' => gmt_to_jst_time($data['create_at']),
            'updated_at' => gmt_to_jst_time($data['updated_at']),
            'crawler_time' => $data['crawler_time'],
        ];
    }
}
