<?php

namespace App\Repositories;

interface AuctionGenreAreaRepositoryInterface extends SingleKeyModelRepositoryInterface
{

    /**
     * @param integer $genreId
     * @param integer $prefCd
     * @return mixed
     */
    public function getFirstByGenreIdAndPrefCd($genreId, $prefCd);

    /**
     * @param array $data
     * @return mixed
     */
    public function saveData($data = null);
}
