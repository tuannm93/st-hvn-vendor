<?php
namespace App\Repositories;

interface CyzenModelRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function saveData($data);

    /**
     * @return mixed
     */
    public function getModel();
}
