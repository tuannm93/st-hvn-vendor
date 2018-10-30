<?php

namespace App\Repositories;

interface FiltersRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $jisCd
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    public function getFiltersId($jisCd, $genreId, $categoryId);
}
