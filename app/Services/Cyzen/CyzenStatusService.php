<?php

namespace App\Services\Cyzen;

use App\Repositories\CyzenStatusRepositoryInterface;

class CyzenStatusService extends BaseCyzenServices
{
    /**
     * @var string $path
     */
    private $path = '/webapi/v0/statuses';

    /**
     * @var $cyzenStatusRepository
     */
    protected $cyzenStatusRepository;

    /**
     * @var \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     */
    protected $cyzenGroupService;

    /**
     * CyzenStatusService constructor.
     *
     * @param CyzenStatusRepositoryInterface $cyzenStatusRepository
     * @param \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     * @throws \Exception
     */
    public function __construct(
        CyzenStatusRepositoryInterface $cyzenStatusRepository,
        CyzenGroupService $cyzenGroupService
    ) {
        parent::__construct(BaseCyzenServices::LOG_PATH_STATUS);
        $this->cyzenStatusRepository = $cyzenStatusRepository;
        $this->cyzenGroupService = $cyzenGroupService;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        //call api
        $now = date('Y-m-d H:i:s');
        $rawRawData = $this->get($this->path, []);

        foreach ($rawRawData['statuses'] as $key => $item) {
            $item['crawler_time'] = $now;
            $importData = $this->initParams($item);
            $isGroupReady = $this->checkDbRelationship(
                $item['group_id'],
                $this->cyzenGroupService,
                $this->cyzenStatusRepository
            );
            if ($isGroupReady) {
                $this->processingData($importData, $this->cyzenStatusRepository);
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
            'group_id' => $data['group_id'],
            'status_id' => $data['status_id'],
            'status_name' => $data['status_name'],
            'updated_at' => gmt_to_jst_time($data['updated_at']),
            'created_at' => gmt_to_jst_time($data['created_at']),
            'crawler_time' => $data['crawler_time']
        ];
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->cyzenStatusRepository->getModel();
    }
}
