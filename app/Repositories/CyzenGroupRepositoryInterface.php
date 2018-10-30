<?php
namespace App\Repositories;

interface CyzenGroupRepositoryInterface extends CyzenModelRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllId();
}
