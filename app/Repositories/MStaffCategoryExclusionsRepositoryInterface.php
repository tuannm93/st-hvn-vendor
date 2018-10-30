<?php

namespace App\Repositories;

interface MStaffCategoryExclusionsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $jisCd
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    public function getListStaffExclude($jisCd, $genreId, $categoryId);
}
