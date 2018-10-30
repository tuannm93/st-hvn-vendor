<?php

namespace App\Repositories;

interface AffiliationSubsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Acquisition of affiliated store incidental information
     *
     * @param integer $corpId
     * @return \Illuminate\Support\Collection
     */
    public function getAffiliationSubsList($corpId);
}
