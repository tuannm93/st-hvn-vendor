<?php

namespace App\Services\Cyzen;

use App\Repositories\CyzenSpotTagRepositoryInterface;
use Monolog\Logger;

class CyzenSpotTagService extends BaseCyzenServices
{
    /** @var CyzenSpotTagRepositoryInterface $cyzenSpotTagRepository */
    protected $cyzenSpotTagRepository;

    /**
     * @var \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     */
    protected $cyzenGroupService;

    /** @var string $path */
    private $path = '/webapi/v0/spot_tags';

    /**
     * CyzenSpotServices constructor.
     *
     * @param CyzenSpotTagRepositoryInterface $cyzenSpotTagInterface
     * @param \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     * @throws \Exception
     */
    public function __construct(
        CyzenSpotTagRepositoryInterface $cyzenSpotTagInterface,
        CyzenGroupService $cyzenGroupService
    ) {
        parent::__construct(BaseCyzenServices::LOG_PATH_SPOT_TAG);
        $this->cyzenSpotTagRepository = $cyzenSpotTagInterface;
        $this->cyzenGroupService = $cyzenGroupService;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->logger->log(Logger::INFO, '==========START CRON JOB GET SPOT TAG==========');
        $now = date('Y-m-d H:i:s');
        $rawRawData = $this->get($this->path, []);

        foreach ($rawRawData['spot_tags'] as $key => $item) {
            $item['crawler_time'] = $now;
            $importData = $this->initParams($item);

            //check relation of table cyzen_group
            $isGroupReady = $this->checkDbRelationship($item['group_id'], $this->cyzenGroupService, $this->cyzenSpotTagRepository);

            if ($isGroupReady) {
                $this->processingData($importData, $this->cyzenSpotTagRepository);
            }
        }
        $this->logger->log(Logger::INFO, '==========END CRON JOB GET SPOT TAG==========');
    }


    /**
     * @param $data
     * @return array
     */
    public function initParams($data)
    {
        return [
            'spot_tag_id' => $data['spot_tag_id'],
            'group_id'  => $data['group_id'],
            'spot_tag_name'  => $data['spot_tag_name'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],
            'crawler_time' => $data['crawler_time'],
        ];
    }
}
