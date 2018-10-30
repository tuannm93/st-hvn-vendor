<?php

namespace App\Repositories;

interface ProgImportFilesRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @author thaihv
     * check exist row in database
     * @param  integer $id id of prog import file
     * @return boolean
     */
    public function findById($id);

    /**
     * @return mixed
     */
    public function getImportFileReleased();

    /**
     * @param integer $paginate
     * @return mixed
     */
    public function getImportFileNotDelete($paginate);

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateDelete($id, $data);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findNotDeleteById($id);

    /**
     * @param array $saveData
     * @return mixed
     */
    public function updateProgImportFile($saveData);
}
