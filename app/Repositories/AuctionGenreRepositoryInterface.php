<?php

namespace App\Repositories;

interface AuctionGenreRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * get data by id
     *
     * @param  integer $genreId
     * @return array
     */
    public function getFirstByGenreId($genreId = null);
    /**
     * save data auction
     * @param  array $data
     * @return boolean
     */
    public function saveAuctionGenre($data);
}
