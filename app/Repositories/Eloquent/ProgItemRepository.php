<?php

namespace App\Repositories\Eloquent;

use App\Models\ProgItem;
use App\Repositories\ProgItemRepositoryInterface;

class ProgItemRepository extends SingleKeyModelRepository implements ProgItemRepositoryInterface
{
    /**
     * @var ProgItem
     */
    protected $model;

    /**
     * ProgItemRepository constructor.
     *
     * @param ProgItem $model
     */
    public function __construct(ProgItem $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $id
     * @return mixed|static
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }
}
