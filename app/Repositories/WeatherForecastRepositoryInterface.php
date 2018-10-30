<?php

namespace App\Repositories;

/**
 * Interface WeatherForecastRepositoryInterface
 *
 * @package App\Repositories
 */
interface WeatherForecastRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Get total data by date range
     *
     * @param string $fromDate
     * @param string $toDate
     * @return mixed
     */
    public function countByDateRange($fromDate, $toDate);
}
