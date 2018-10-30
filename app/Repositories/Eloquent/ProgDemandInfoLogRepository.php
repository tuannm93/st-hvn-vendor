<?php

namespace App\Repositories\Eloquent;

use App\Repositories\ProgDemandInfoLogRepositoryInterface;
use App\Models\ProgDemandInfoLog;

class ProgDemandInfoLogRepository extends SingleKeyModelRepository implements ProgDemandInfoLogRepositoryInterface
{
    /**
     * @var ProgDemandInfoLog
     */
    protected $model;

    /**
     * ProgDemandInfoLogRepository constructor.
     *
     * @param ProgDemandInfoLog $model
     */
    public function __construct(ProgDemandInfoLog $model)
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
