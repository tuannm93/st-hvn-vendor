<?php

namespace App\Repositories;

interface AdditionInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param array $fields
     * @param array $orderBy
     * @param array $conditions
     * @return mixed
     */
    public function getReportAdditionList($fields, $orderBy, $conditions);
}
