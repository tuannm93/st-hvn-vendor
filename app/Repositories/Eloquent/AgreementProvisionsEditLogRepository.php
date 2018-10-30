<?php


namespace App\Repositories\Eloquent;

use App\Models\AgreementProvisionsEditLog;
use App\Repositories\AgreementProvisionsEditLogRepositoryInterface;

class AgreementProvisionsEditLogRepository extends SingleKeyModelRepository implements AgreementProvisionsEditLogRepositoryInterface
{

    /**
     * @var AgreementProvisionsEditLog
     */
    protected $model;

    /**
     * AgreementProvisionsEditLogRepository constructor.
     *
     * @param AgreementProvisionsEditLog $model
     */
    public function __construct(AgreementProvisionsEditLog $model)
    {
        $this->model = $model;
    }
}
