<?php

namespace App\Repositories;

interface RefusalsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $auctionId
     * @param array $dataUpdate
     * @return mixed
     */
    public function updateData($auctionId, $dataUpdate);
}
