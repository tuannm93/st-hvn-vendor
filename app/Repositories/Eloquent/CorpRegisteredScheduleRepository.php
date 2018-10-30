<?php

namespace App\Repositories\Eloquent;

use App\Models\CorpRegisteredSchedule;
use App\Repositories\CorpRegisteredScheduleRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CorpRegisteredScheduleRepository extends SingleKeyModelRepository implements CorpRegisteredScheduleRepositoryInterface
{
    /**
     * @var CorpRegisteredSchedule $model
     */
    protected $model;

    /**
     * @param $model
     */
    public function __construct(CorpRegisteredSchedule $model)
    {
        $this->model = $model;
    }

    /**
     * @param $corpId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getTimeFinish($corpId)
    {
        try {
            $timeFinish = $this->model->select('time_finish')->whereIn('corp_id', $corpId)
                ->get()->toArray();
            return $timeFinish;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
}
