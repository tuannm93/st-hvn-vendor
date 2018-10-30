<?php

namespace App\Repositories\Eloquent;

use App\Models\NotCorrespondLog;
use App\Repositories\NotCorrespondLogRepositoryInterface;

class NotCorrespondLogRepository extends SingleKeyModelRepository implements NotCorrespondLogRepositoryInterface
{
    /**
     * @var NotCorrespondLog
     */
    protected $model;
    /**
     * @var mixed
     */
    protected $notCorrespondItem;

    /**
     * NotCorrespondLogRepository constructor.
     *
     * @param NotCorrespondLog $model
     */
    public function __construct(NotCorrespondLog $model)
    {
        $this->model = $model;
    }

    /**
     * @return NotCorrespondLog|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new NotCorrespondLog();
    }
}
