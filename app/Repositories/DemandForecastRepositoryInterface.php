<?php

namespace App\Repositories;

/**
 * Interface DemandForecastRepositoryInterface
 *
 * @package App\Repositories
 */
interface DemandForecastRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Get total data by date
     *
     * @param string $date
     * @return mixed
     */
    public function countByDate($date);
}
