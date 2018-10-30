<?php

namespace App\Repositories;

interface MTaxRateRepositoryInterface
{
    /**
     * @param string $date
     * @return mixed
     */
    public function findByDate($date);
}
