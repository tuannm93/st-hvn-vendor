<?php

namespace App\Repositories\Eloquent;

use App\Repositories\PublicHolidayRepositoryInterface;
use App\Models\PublicHoliday;
use DB;

class PublicHolidayRepository extends SingleKeyModelRepository implements PublicHolidayRepositoryInterface
{
    /**
     * @var PublicHoliday
     */
    protected $model;

    /**
     * PublicHolidayRepository constructor.
     *
     * @param PublicHoliday $model
     */
    public function __construct(PublicHoliday $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|PublicHoliday|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new PublicHoliday();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }

    /**
     * @param string $date
     * @return \Illuminate\Support\Collection
     */
    public function getPublicHoliday($date)
    {
        return $this->model
            ->where('holiday_date', '>', $date->format('Y/m/00'))
            ->where('holiday_date', '<', $date->format('Y/m/99'))
            ->get();
    }

/**
 * get public_holidays table data
 *
 * @return mixed
 */
    public function getPublicHolidayExclusion()
    {
        $getPublicHolidayExclusion = $this->model->select('id', 'holiday_date')
            ->whereRaw("to_date(holiday_date,'yyyy/MM/dd') >= to_date('" . date('Y/m/d') . "', 'yyyy/MM/dd')")
            ->orderBy('holiday_date')
            ->get();
        return $getPublicHolidayExclusion;
    }

    /**
     * get public_holidays table data
     *
     * @return mixed
     */
    public function getPublicHolidayExclusionOld()
    {
        $getPublicHolidayExclusion = $this->model->select('id', 'holiday_date')->get();
        return $getPublicHolidayExclusion;
    }

    /**
     * delete all data
     * @return mixed
     * @throws \Exception
     */
    public function deleteAll()
    {
        return $this->model->whereNotNull('modified_user_id')->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|mixed|static[]
     */
    public function findAll()
    {
        return $this->model->all();
    }

    /**
     * @param string $date
     * @return bool|mixed
     */
    public function checkHoliday($date)
    {
        $results = $this->findAll();

        // use strtotime for compare date
        foreach ($results as $row) {
            if (strtotime($row['holiday_date']) == strtotime($date)) {
                return true;
            }
        }

        return false;
    }
}
