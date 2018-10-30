<?php

namespace App\Repositories\Eloquent;

use App\Repositories\ProgAddDemandInfoLogRepositoryInterface;
use App\Models\ProgAddDemandInfoLog;

class ProgAddDemandInfoLogRepository extends SingleKeyModelRepository implements ProgAddDemandInfoLogRepositoryInterface
{
    /**
     * @var ProgAddDemandInfoLog
     */
    protected $model;

    /**
     * ProgAddDemandInfoLogRepository constructor.
     *
     * @param ProgAddDemandInfoLog $model
     */
    public function __construct(ProgAddDemandInfoLog $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert($data)
    {
        return $this->model->insert($data);
    }

    /**
     * @param array $data
     * @return \App\Models\Base|bool
     */
    public function save($data)
    {
        return $this->model->save($data);
    }
}
