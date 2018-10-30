<?php

namespace App\Repositories;

interface ProgAddDemandInfoLogRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param array $data
     * @return bool
     */
    public function insert($data);
}
