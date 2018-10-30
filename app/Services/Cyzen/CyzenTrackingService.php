<?php

namespace App\Services\Cyzen;

use App\Repositories\CyzenTrackingRepositoryInterface;
use Monolog\Logger;

class CyzenTrackingService extends BaseCyzenServices
{
    /**
     * @var $cyzenScheduleRepository
     */
    protected $cyzenTrackingRepository;
    /**
     * @var string $path
     */
    private $path = '/webapi/v0/members_statuses';

    /**
     * CyzenTrackingService constructor.
     *
     * @param \App\Repositories\CyzenTrackingRepositoryInterface $cyzenTrackingRepository
     * @throws \Exception
     */
    public function __construct(CyzenTrackingRepositoryInterface $cyzenTrackingRepository)
    {
        parent::__construct(BaseCyzenServices::LOG_PATH_TRACKING);
        $this->cyzenTrackingRepository = $cyzenTrackingRepository;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->logger->log(Logger::INFO, '==========START CRON JOB GET TRACKING (MEMBER STATUS)==========');
        $now = date('Y-m-d H:i:s');
        $rawData = $this->get($this->path, []);

        if (empty($rawData['members_statuses'])) {
            return null;
        }

        foreach ($rawData['members_statuses'] as $key => $item) {
            if ($item['history']) {
                $importData = [
                    'user_id' => $item['history']['user_id'],
                    'group_id' => $item['history']['group_id'],
                    'address' => $item['history']['address'],
                    'tracking_accuracy' => (int)$item['history']['history_accuracy'],
                    'tracking_location' => \DB::raw('ST_GeographyFromText(\'point(' .
                        $item['history']['history_longitude'] . ' ' . $item['history']['history_latitude'] . ')\')'),
                    'created_at' => gmt_to_jst_time($item['history']['create_at'])
                ];
                if ($item['history'] && $item['tracking']) {
                    if (strtotime($item['history']['updated_at']) < strtotime($item['tracking']['create_at'])) {
                        $importData['tracking_location'] = \DB::raw('ST_GeographyFromText(\'point(' .
                            $item['tracking']['tracking_longitude'] . ' ' . $item['tracking']['tracking_latitude'] . ')\')');
                        $importData['created_at'] = gmt_to_jst_time($item['tracking']['create_at']);
                        $importData['tracking_accuracy'] = (int)$item['tracking']['tracking_accuracy'];
                    }
                }
                $importData['crawler_time'] = $now;
                $this->processingData($importData, $this->cyzenTrackingRepository);
            }
        }
        $this->logger->log(Logger::INFO, '==========END CRON JOB GET TRACKING (MEMBER STATUS)==========');
        return null;
    }
}
