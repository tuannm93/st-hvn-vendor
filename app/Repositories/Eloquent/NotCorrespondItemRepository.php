<?php

namespace App\Repositories\Eloquent;

use App\Models\NotCorrespondItem;
use App\Repositories\NotCorrespondItemRepositoryInterface;

class NotCorrespondItemRepository extends SingleKeyModelRepository implements NotCorrespondItemRepositoryInterface
{
    /**
     * @var NotCorrespondItem
     */
    protected $model;

    /**
     * NotCorrespondItemRepository constructor.
     *
     * @param NotCorrespondItem $model
     */
    public function __construct(NotCorrespondItem $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|NotCorrespondItem|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new NotCorrespondItem();
    }

    /**
     * @param integer $corpId
     * @return mixed|void
     */
    public function findBy($corpId = null)
    {
    }

    /**
     * @return mixed
     */
    public function findFirst()
    {
        return $this->model->orderBy('id', 'desc')->first();
    }
}
