<?php

namespace App\Repositories;

interface ProgDemandInfoOtherTmpRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * find by prog corp id
     *
     * @param  object $progCorp
     * @return object
     */
    public function findByProgCorpId($progCorp);

    /**
     * delete by prog corp id
     *
     * @param  integer $progCorpId
     * @return boolean
     */
    public function deleteByProgCorpId($progCorpId);
}
