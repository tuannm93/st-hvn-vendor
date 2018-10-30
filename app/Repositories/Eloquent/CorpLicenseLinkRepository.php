<?php

namespace App\Repositories\Eloquent;

use App\Models\CorpLicenseLink;
use App\Repositories\CorpLicenseLinkRepositoryInterface;

class CorpLicenseLinkRepository extends SingleKeyModelRepository implements CorpLicenseLinkRepositoryInterface
{
    /**
     * @var CorpLicenseLink
     */
    protected $model;

    /**
     * CorpLicenseLinkRepository constructor.
     *
     * @param CorpLicenseLink $model
     */
    public function __construct(CorpLicenseLink $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $licenseId
     * @return mixed
     */
    public function findByLicenseId($licenseId)
    {
        return $this->model
            ->where('lisense_id', $licenseId)
            ->get();
    }
}
