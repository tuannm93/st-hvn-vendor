<?php

namespace App\Repositories\Eloquent;

use App\Models\AgreementStatusHistory;
use App\Repositories\AgreementStatusHistoryRepositoryInterface;

class AgreementStatusHistoryRepository extends SingleKeyModelRepository implements AgreementStatusHistoryRepositoryInterface
{

    /**
     * @var AgreementStatusHistory
     */
    protected $model;

    /**
     * AgreementStatusHistoryRepository constructor.
     *
     * @param AgreementStatusHistory $model
     */
    public function __construct(AgreementStatusHistory $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getFirstAgreementStatusHistory()
    {
        return $this->model->orderBy('id', 'desc')->first();
    }
}
