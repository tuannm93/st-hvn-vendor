<?php

namespace App\Repositories;

interface SelectionGenrePrefectureRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $id
     * @return mixed
     */
    public function getSelectGenrePrefecture($id);

    /**
     * @param integer $id
     * @return mixed
     */
    public function deleteBaseOnGenreId($id);

    /**
     * @param array $data
     * @return mixed
     */
    public function saveNewSelectionGenrePrefecture($data);
}
