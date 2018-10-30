<?php

namespace App\Repositories;

interface CorpRegisteredScheduleRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $corpId
     * @return mixed
     */
    public function getTimeFinish($corpId);
}
