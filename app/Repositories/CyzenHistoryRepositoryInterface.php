<?php
namespace App\Repositories;

interface CyzenHistoryRepositoryInterface extends CyzenModelRepositoryInterface
{
    /**
     * @param $field
     * @return mixed
     */
    public function max($field);

    /**
     * @param $key
     * @param $model
     * @return mixed
     */
    public function checkForeignKey($key, $model);

    /**
     * @param $listStaff
     * @return mixed
     */
    public function getStatusOfStaffs($listStaff);
}
