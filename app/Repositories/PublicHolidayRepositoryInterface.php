<?php

namespace App\Repositories;

interface PublicHolidayRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * get public_holidays data
     *
     * @return mixed
     */
    public function getPublicHolidayExclusion();

    /**
     * Delete public_holidays column
     * @return boolean
     */
    public function deleteAll();

    /**
     * find all public_holidays data
     *
     * @return mixed
     */
    public function findAll();

    /**
     * check holiday_date column
     *
     * @param string $date
     * @return mixed
     */
    public function checkHoliday($date);
}
