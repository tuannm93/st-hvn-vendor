<?php

namespace App\Repositories;

interface MSiteCategoryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $siteId
     * @return mixed
     */
    public function getCategoriesBySite($siteId = null);
}
