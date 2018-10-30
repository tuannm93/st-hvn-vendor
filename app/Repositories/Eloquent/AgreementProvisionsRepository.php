<?php

namespace App\Repositories\Eloquent;

use App\Models\AgreementProvision;
use App\Models\Agreement;
use App\Repositories\AgreementProvisionsRepositoryInterface;

class AgreementProvisionsRepository extends SingleKeyModelRepository implements AgreementProvisionsRepositoryInterface
{

    /**
     * @var AgreementProvision
     */
    protected $model;

    /**
     * AgreementProvisionsRepository constructor.
     *
     * @param AgreementProvision $agreementProvisions
     */
    public function __construct(AgreementProvision $agreementProvisions)
    {
        $this->model = $agreementProvisions;
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return get_class($this->model);
    }

    /**
     * @param integer $id
     * @param integer $deleteFlag
     * @return integer
     */
    public function findAgreementById($id, $deleteFlag)
    {
        return Agreement::where('id', $id)->where('delete_flag', $deleteFlag)->get()->count();
    }

    /**
     * @param integer $agreementId
     * @param integer $deleteFlag
     * @return \Illuminate\Support\Collection
     */
    public function findAgreementProvisionsByAgreementId($agreementId, $deleteFlag)
    {
        return AgreementProvision::where('agreement_id', $agreementId)->where('delete_flag', $deleteFlag)
            ->orderBy('sort_no')
            ->get();
    }

    /**
     * @param integer $id
     * @return object
     */
    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }
}
