<?php

namespace App\Repositories\Eloquent;

use App\Models\ProgDemandInfoOtherTmp;
use App\Repositories\ProgDemandInfoOtherTmpRepositoryInterface;

class ProgDemandInfoOtherTmpRepository extends SingleKeyModelRepository implements ProgDemandInfoOtherTmpRepositoryInterface
{
    /**
     * @var ProgDemandInfoOtherTmp
     */
    protected $model;

    /**
     * ProgDemandInfoOtherTmpRepository constructor.
     *
     * @param ProgDemandInfoOtherTmp $model
     */
    public function __construct(ProgDemandInfoOtherTmp $model)
    {
        $this->model = $model;
    }

    /**
     * find by prog corp id
     *
     * @param  object $progCorp
     * @return object
     */
    public function findByProgCorpId($progCorp)
    {
        $conditions = $this->model
            ->select('prog_import_file_id', 'add_flg', 'agree_flag');
        if (!empty($progCorp->id)) {
            $conditions = $conditions->where('prog_corp_id', $progCorp->id);
        } else {
            $conditions = $conditions->whereNull('prog_corp_id');
        }
        return $conditions->orderBy('id', 'desc')
            ->first();
    }

    /**
     * delete by prog corp id
     *
     * @param  integer $progCorpId
     * @return boolean
     * @throws \Exception
     */
    public function deleteByProgCorpId($progCorpId)
    {
        return $this->model
            ->where('prog_corp_id', $progCorpId)
            ->delete();
    }
}
