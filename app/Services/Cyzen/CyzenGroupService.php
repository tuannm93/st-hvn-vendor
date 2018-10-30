<?php
namespace App\Services\Cyzen;

use App\Repositories\CyzenGroupRepositoryInterface;
use Monolog\Logger;

class CyzenGroupService extends BaseCyzenServices
{
    /**
     * @var string $path
     */
    private $path = '/webapi/v0/groups';

    /**
     * @var $cyzenGroupRepository
     */
    protected $cyzenGroupRepository;

    /**
     * CyzenGroupService constructor.
     *
     * @param \App\Repositories\CyzenGroupRepositoryInterface $cyzenGroupRepository
     * @throws \Exception
     */
    public function __construct(CyzenGroupRepositoryInterface $cyzenGroupRepository)
    {
        parent::__construct(BaseCyzenServices::LOG_PATH_GROUP);
        $this->cyzenGroupRepository = $cyzenGroupRepository;
    }

    /**
     * @param string $nextId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle($nextId = '')
    {
        $this->logger->log(Logger::INFO, '==========START CRON JOB GET GROUP==========');
        //call api
        $now = date('Y-m-d H:i:s');
        $query = ($nextId !== '') ? ['next_group_id' => $nextId] : [] ;
        $crawRawData = $this->get($this->path, $query);

        foreach ($crawRawData['groups'] as $key => $item) {
            $item['crawler_time'] = $now;
            $importData = $this->initParams($item);
            $this->processingData($importData, $this->cyzenGroupRepository);
        }
        //sleep 1s for API call limit
        sleep(1);
        //check pagination
        if (isset($crawRawData['next_group_id'])) {
            return $this->handle($crawRawData['next_group_id']);
        }
        $this->logger->log(Logger::INFO, '==========END CRON JOB GET GROUP==========');
        return null;
    }

    /**
     * @param $id
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getById($id)
    {
        //call api
        $now = date('Y-m-d H:i:s');
        $query = ['group_id' => $id];
        $rawData = $this->get($this->path, $query);
        if ($rawData) {
            $rawData['groups'][0]['crawler_time'] = $now;
            $importData = $this->initParams($rawData['groups'][0]);
            return $this->processingData($importData, $this->cyzenGroupRepository);
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
        return $params = [
            'id' => $data['group_id'],
            'group_join_id' => $data['group_join_id'],
            'group_code' => $data['group_code'],
            'group_name' => $data['group_name'],
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
        return $this->cyzenGroupRepository->getModel();
    }
}
