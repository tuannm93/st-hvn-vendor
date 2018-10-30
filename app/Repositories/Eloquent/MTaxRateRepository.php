<?php

namespace App\Repositories\Eloquent;

use App\Models\MTaxRate;
use App\Repositories\MTaxRateRepositoryInterface;

class MTaxRateRepository extends SingleKeyModelRepository implements MTaxRateRepositoryInterface
{
    /**
     * @var MTaxRate
     */
    protected $model;

    /**
     * MTaxRateRepository constructor.
     *
     * @param MTaxRate $model
     */
    public function __construct(MTaxRate $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MTaxRate|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MTaxRate();
    }

    /**
     * @param string $date
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findByDate($date)
    {
        return $this->model->where('start_date', '<=', $date)->orWhere(
            [
            ['end_date', ''],
            ['end_date', '>=', $date],
            ]
        )->first();
    }
}
