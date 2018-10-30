<?php

namespace App\Repositories;

interface DemandCorrespondsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function save($data);
}
