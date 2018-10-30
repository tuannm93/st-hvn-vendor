<?php

namespace App\Services;

use App\Repositories\CorpRegisteredScheduleRepositoryInterface;

class CorpRegisteredScheduleService
{
    /**
     * @var CorpRegisteredScheduleRepositoryInterface $corpRegisteredSheduleRepository
     */
    protected $corpRegisteredScheduleRepository;

    /**
     * CorpRegisteredScheduleService constructor.
     * @param CorpRegisteredScheduleRepositoryInterface $corpRegisteredScheduleRepository
     */
    public function __construct(CorpRegisteredScheduleRepositoryInterface $corpRegisteredScheduleRepository)
    {
        $this->corpRegisteredSheduleRepository = $corpRegisteredScheduleRepository;
    }

    /**
     * @param $corp_id
     * @return mixed
     */
    public function timeFinish($corp_id)
    {
        return $this->corpRegisteredSheduleRepository->getTimeFinish($corp_id);
    }

    /**
     * @param $time
     * @return false|string
     */
    public function timeSpecifyTo($time)
    {
        return date("Y-m-d H:i:s", strtotime($time . "-5 minutes"));
    }
}
