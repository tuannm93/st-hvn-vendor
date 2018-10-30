<?php

namespace App\Repositories;

interface SelectGenrePrefectureRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function getByGenreIdAndPrefectureCd($data);
}
