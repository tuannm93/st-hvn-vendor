<?php
namespace App\Repositories;

interface CyzenStatusRepositoryInterface extends CyzenModelRepositoryInterface
{
    /**
     * @param $key
     * @param $model
     * @return mixed
     */
    public function checkForeignKey($key, $model);
}
