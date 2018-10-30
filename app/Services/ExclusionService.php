<?php

namespace App\Services;

use App\Repositories\ExclusionTimeRepositoryInterface;
use App\Repositories\PublicHolidayRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExclusionService
{
    /**
     * @var publicHolidayRepositoryInterface
     */
    protected $publicHolidayRepository;

    /**
     * @var exclusionTimeRepositoryInterface
     */
    protected $exclusionTimeRepo;

    /**
     * ExclusionService constructor.
     *
     * @param PublicHolidayRepositoryInterface $publicHolidayRepository
     * @param ExclusionTimeRepositoryInterface $exclusionTimeRepository
     */
    public function __construct(PublicHolidayRepositoryInterface $publicHolidayRepository, ExclusionTimeRepositoryInterface $exclusionTimeRepository)
    {
        $this->publicHolidayRepository = $publicHolidayRepository;
        $this->exclusionTimeRepo = $exclusionTimeRepository;
    }


    /**
     * delete update and insert data into public_holidays, exclusion_times table
     *
     * @param  $request array
     * @return bool
     */
    public function postExclusion($request)
    {

        DB::beginTransaction();
        try {
            $insertData = [];
            $this->publicHolidayRepository->deleteAll();
            foreach ($request['holiday_date'] as $key => $holidayDate) {
                if (!empty($holidayDate)) {
                    $insertData[$key]['holiday_date'] = $holidayDate;
                    $insertData[$key]['modified_user_id'] = Auth::user()['user_id'];
                    $insertData[$key]['modified'] = date("Y/m/d H:i:s");
                    $insertData[$key]['created_user_id'] = Auth::user()['user_id'];
                    $insertData[$key]['created'] = date("Y/m/d H:i:s");
                }
            }
            $this->publicHolidayRepository->insert($insertData);
            $exclusionDatas = [];
            foreach ($request['exclusion_id'] as $key => $exclusionId) {
                if (isset($request['exclusion_day'][$key])) {
                    $exclusionDatas[$key]['exclusion_day'] = array_sum($request['exclusion_day'][$key]);
                } else {
                    $exclusionDatas[$key]['exclusion_day'] = 0;
                }
                $exclusionDatas[$key]['id'] = $exclusionId;
                $exclusionDatas[$key]['pattern'] = $request['pattern'][$key];
                $exclusionDatas[$key]['exclusion_time_from'] = $request['exclusion_time_from'][$key];
                $exclusionDatas[$key]['exclusion_time_to'] = $request['exclusion_time_to'][$key];
            }
            if (!empty($exclusionDatas)) {
                foreach ($exclusionDatas as $value) {
                    $exclusionData['pattern'] = $value['pattern'];
                    $exclusionData['exclusion_time_from'] = $value['exclusion_time_from'];
                    $exclusionData['exclusion_time_to'] = $value['exclusion_time_to'];
                    $exclusionData['modified_user_id'] = Auth::user()['user_id'];
                    $exclusionData['modified'] = date("Y/m/d H:i:s");
                    $exclusionData['exclusion_day'] = $value['exclusion_day'];
                    $this->exclusionTimeRepo->updateExclusion($value['id'], $exclusionData);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * set status for checkbox function
     *
     * @param  $data integer
     * @return array
     */
    public static function setChecked($data)
    {
        $cheked = 'checked';
        $check = [];
        switch ($data) {
            case 0:
                $check['checked1'] = $check['checked2'] = $check['checked3'] = '';
                break;
            case 1:
                $check['checked1'] = $cheked;
                break;
            case 2:
                $check['checked2'] = $cheked;
                break;
            case 3:
                $check['checked1'] = $check['checked2'] = $cheked;
                break;
            case 4:
                $check['checked3'] = $cheked;
                break;
            case 5:
                $check['checked1'] = $check['checked3'] = $cheked;
                break;
            case 6:
                $check['checked2'] = $check['checked3'] = $cheked;
                break;
            case 7:
                $check['checked1'] = $check['checked2'] = $check['checked3'] = $cheked;
                break;
        }
        return $check;
    }
}
