<?php

namespace App\Repositories;

interface AutoCallRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getItem();

    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);
}
