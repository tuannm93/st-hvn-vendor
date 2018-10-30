<?php

namespace App\Repositories;

interface PgDescriptionRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param string $tableName
     * @param array $columns
     * @return mixed
     */
    public function getColumnAlias($tableName, $columns);
}
