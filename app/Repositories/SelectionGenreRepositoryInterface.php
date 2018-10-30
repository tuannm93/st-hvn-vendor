<?php

namespace App\Repositories;

interface SelectionGenreRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Update or save selection genre
     *
     * @param array $data
     * @param  integer $id
     * @return mixed
     */
    public function updateOrSave($data, $id = null);

    /**
     * @param integer $id
     * @param string $field
     * @return mixed
     */
    public function findBaseOnGenreId($id, $field = null);
}
