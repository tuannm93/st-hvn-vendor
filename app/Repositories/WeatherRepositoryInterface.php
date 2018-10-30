<?php

namespace App\Repositories;

/**
 * Interface WeatherRepositoryInterface
 *
 * @package App\Repositories
 */
interface WeatherRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Get total data by date
     *
     * @param string $date
     * @return mixed
     */
    public function countByDate($date);
}
