<?php

namespace App\Repositories;

/**
 * Interface DemandActualRepositoryInterface
 *
 * @package App\Repositories
 */
interface DemandActualRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Get total data by date
     *
     * @param string $date
     * @return mixed
     */
    public function countByDate($date);
}
