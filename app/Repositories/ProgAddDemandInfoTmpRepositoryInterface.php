<?php

namespace App\Repositories;

interface ProgAddDemandInfoTmpRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * get by corp id
     *
     * @param  object $progCorp
     * @return array object
     */
    public function getByProgCorpId($progCorp);

    /**
     * delete by prog corp id
     *
     * @param  integer $progCorpId
     * @return boolean
     */
    public function deleteByProgCorpId($progCorpId);
}
