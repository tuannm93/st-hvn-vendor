<?php

namespace App\Repositories;

interface SelectGenreRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $genreId
     * @return mixed
     */
    public function findByGenreId($genreId);
}
