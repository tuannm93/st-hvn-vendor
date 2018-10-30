<?php

namespace App\Repositories\Eloquent;

use App\Models\AgreementEditHistory;
use App\Repositories\AgreementEditHistoryRepositoryInterface;

class AgreementEditHistoryRepository extends SingleKeyModelRepository implements AgreementEditHistoryRepositoryInterface
{

    /**
     * @var AgreementEditHistory
     */
    protected $model;

    /**
     * AgreementEditHistoryRepository constructor.
     *
     * @param AgreementEditHistory $model
     */
    public function __construct(AgreementEditHistory $model)
    {
        $this->model = $model;
    }

    /**
     * @return object
     */
    public function getFirstAgreementEditHistory()
    {
        return $this->model->orderBy('id', 'desc')->first();
    }
}
