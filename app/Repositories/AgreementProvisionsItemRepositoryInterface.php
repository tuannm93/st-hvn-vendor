<?php


namespace App\Repositories;

interface AgreementProvisionsItemRepositoryInterface
{
    /**
     * @param string $column
     * @param string $value
     * @return mixed
     */
    public function deleteByColumn($column, $value);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);
}
