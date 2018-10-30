<?php

namespace App\Repositories;

interface ExclusionTimeRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * get list data ExclusionTime
     *
     * @return array
     */
    public function getList();

    /**
     * @param string $pattern
     * @return mixed
     */
    public function findByPattern($pattern = null);

    /**
     * @return mixed
     */
    public function getExclusionTime();

    /**
     * update exclusion_times table function
     *
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateExclusion($id, $data);

    /**
     * @param integer $genreId
     * @param integer $prefectureCd
     * @return mixed
     */
    public function getData($genreId, $prefectureCd);
}
