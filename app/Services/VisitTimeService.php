<?php

namespace App\Services;

use App\Repositories\VisitTimeRepositoryInterface;
use App\Models\VisitTime;
use Illuminate\Support\Facades\Log;

class VisitTimeService
{

    /**
     *
     * @var VisitTimeRepositoryInterface
     */
    protected $visitTimeRepo;
    /**
     * build visit time data by is_visit_time_range_flg field
     * @param  array $data     visit time data
     * @param  int $demandId demand id
     * @return array          visit time data
     */
    private function buildVisitTimeData($data, $demandId)
    {
        $visitTimeData = [];
        $idDelete = [];
        foreach ($data as $key => $val) {
            $doAuction = null;
            if (!empty($data['demandInfo']['do_auction'])) {
                $doAuction = 1;
            }
            if ($val['is_visit_time_range_flg'] == 0) {
                if (!empty($val['visit_time'])) {
                    $visitTimeData[$key]['id'] = $val['id'];
                    $visitTimeData[$key]['demand_id'] = $demandId;
                    $visitTimeData[$key]['visit_time'] = $val['visit_time'];
                    $visitTimeData[$key]['is_visit_time_range_flg'] = $val['is_visit_time_range_flg'];
                    $visitTimeData[$key]['do_auction'] = $doAuction;
                } elseif (!empty($val['id'])) {
                    $idDelete[] = $val['id'];
                }
                continue;
            }
            if (!empty($val['visit_time_from']) && !empty($val['visit_time_to'])) {
                $visitTimeData[$key]['id'] = $val['id'];
                $visitTimeData[$key]['demand_id'] = $demandId;
                $visitTimeData[$key]['visit_time_from'] = $val['visit_time_from'];
                $visitTimeData[$key]['visit_time_to'] = $val['visit_time_to'];
                $visitTimeData[$key]['is_visit_time_range_flg'] = $val['is_visit_time_range_flg'];
                $visitTimeData[$key]['visit_adjust_time'] = $val['visit_adjust_time'];
                $visitTimeData[$key]['do_auction'] = $doAuction;
            } elseif (!empty($val['id'])) {
                $idDelete[] = $val['id'];
            }
        }
        return ['visitTimeData' => $visitTimeData, 'idDelete' => $idDelete];
    }


    /**
     * constructor
     *
     * @param VisitTimeRepositoryInterface $visitTimeRepo
     */
    public function __construct(
        VisitTimeRepositoryInterface $visitTimeRepo
    ) {
        $this->visitTimeRepo = $visitTimeRepo;
    }

    /**
     * Update visit time
     *
     * @param  mixed $data
     * @return boolean
     */
    public function updateVisitTime($data)
    {
        Log::debug('___ start udpate visit-time __________');
        // If the visit date is not entered, I do nothing
        if (!array_key_exists('visitTime', $data)) {
            Log::debug('___ empty visitTime __________');
            return true;
        }
        // Retrieve deal ID
        $demandId = (array_key_exists('id', $data['demandInfo'])) ? $data['demandInfo']['id'] : null;
        // Register visit date information
        $allData = $this->buildVisitTimeData($data['visitTime'], $demandId);
        $idDelete = $allData['idDelete'];
        $visitTimeData = $allData['visitTimeData'];
        $insertData = [];
        $updateData = [];
        $date = date('Y-m-d h:i:s');
        $userId = auth()->user()->user_id;
        $visitTimeFiels = VisitTime::getField();

        foreach ($visitTimeData as $value) {
            unset($value['do_auction']);
            if ($value['id']) {
                $value['modified'] = $date;
                $value['modified_user_id'] = $userId;
                $updateData[] = $value;
            } else {
                unset($value['id']);
                $value['modified'] = $date;
                $value['created'] = $date;
                $value['modified_user_id'] = $userId;
                $value['created_user_id'] = $userId;
                $insertData[] = array_merge($visitTimeFiels, $value);
            }
        }

        if (!empty($insertData)) {
            Log::debug('___ insert visitTime __________');
            $this->visitTimeRepo->saveMany($insertData);
        }

        if (!empty($updateData)) {
            Log::debug('___ update visitTime __________');
            $this->visitTimeRepo->multipleUpdate($updateData);
        }

        if (!empty($idDelete)) {
            Log::debug('___ delete visitTime __________');
            $this->visitTimeRepo->multipleDelete($idDelete);
        }

        // Even if there is no data to be registered, normal termination
        Log::debug('___ end update visitTime __________');
        return true;
    }

    /**
     * Validate
     *
     * @param  mixed $attributes
     * @return boolean
     */
    public function validate($attributes, $key)
    {
        $validate = [
            'validateAdjustTime' => $this->validateVisitTime($attributes, $key),
            'validateVisitTimeFrom' => $this->validateVisitTimeFrom($attributes, $key),
            'validateVisitTimeTo' => $this->validateVisitTimeTo($attributes, $key),
            'validateVisitAdjustTime' => $this->validateVisitAdjustTime($attributes, $key)
        ];
        return !in_array(false, $validate);
    }

    /**
     * Validate visit time
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function validateVisitTime($attributes, $key)
    {
        if (!empty($attributes['visit_time'])) {
            if (!empty($attributes['do_auction'])) {
                if (strtotime($attributes['visit_time']) < strtotime(date('Y/m/d H:i'))) {
                    session()->flash('errors.visit_time.' . $key, __('demand.validation_error.past_date_time'));
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check is datetime
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function isDateTime($attributes, $key)
    {
        if (strtotime($attributes['visit_time_to']) == false) {
            session()->flash('errors.visit_time_to.' . $key, __('demand.validation_error.check_date_format'));
            return false;
        }
        return true;
    }

    /**
     * Validate visit time from
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function validateVisitTimeFrom($attributes, $key)
    {
        if (empty($attributes['is_visit_time_range_flg'])) {
            return true;
        }
        if (empty($attributes['visit_time_from'])) {
            return true;
        }
        return $this->checkVisitTimeFrom($attributes, $key) && $this->checkRequireTo($attributes, $key);
    }

    /**
     * Check visit time from
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function checkVisitTimeFrom($attributes, $key)
    {
        if (!empty($attributes['visit_time_from'])) {
            if (!empty($attributes['do_auction'])) {
                if (strtotime($attributes['visit_time_from']) < strtotime(date('Y/m/d H:i'))) {
                    session()->flash('errors.visit_time_from.' . $key, __('demand.validation_error.past_date_time'));
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Check require to
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function checkRequireTo($attributes, $key)
    {
        if (!isset($attributes['visit_time_from'])) {
            return true;
        }

        if (strlen($attributes['visit_time_from']) == 0) {
            return true;
        }

        if (!isset($attributes['visit_time_to']) || empty($attributes['visit_time_to'])) {
            session()->flash('errors.visit_time_to.' . $key, __('demand.validation_error.visitimeToRquired'));
            if (empty($attributes['visit_adjust_time'])) {
                session()->flash(
                    'errors.visit_adjust_time.' . $key,
                    __('demand.validation_error.adjustTimeRequire')
                );
            }
            return false;
        }

        return true;
    }

    /**
     * Validate visit time to
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function validateVisitTimeTo($attributes, $key)
    {
        if (empty($attributes['visit_time_to'])) {
            return true;
        }
        if (empty($attributes['visit_time_to'])) {
            return true;
        }
        $allValid = [
            $this->isDateTime($attributes, $key),
            $this->checkVisitTimeTo($attributes, $key),
            $this->checkVisitTimeTo2($attributes, $key),
            $this->checkRequireFrom($attributes, $key)
        ];
        return !in_array(false, $allValid);
    }

    /**
     * Check visit time to
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function checkVisitTimeTo($attributes, $key)
    {
        if (!empty($attributes['visit_time_to'])) {
            if (!empty($attributes['do_auction'])) {
                if (strtotime($attributes['visit_time_to']) < strtotime(date('Y/m/d H:i'))) {
                    session()->flash('errors.visit_time_to.' . $key, __('demand.validation_error.past_date_time'));
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check visit time 2
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function checkVisitTimeTo2($attributes, $key)
    {
        if (!empty($attributes['visit_time_to']) && !empty($attributes['visit_time_from'])) {
            if (strtotime($attributes['visit_time_to']) < strtotime($attributes['visit_time_from'])) {
                session()->flash('errors.visit_time_to.' . $key, __('demand.validation_error.past_date_time_2'));

                return false;
            }
        }

        return true;
    }

    /**
     * Check require from
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function checkRequireFrom($attributes, $key)
    {
        if (!isset($attributes['visit_time_to']) || empty($attributes['visit_time_to'])) {
            return true;
        }

        if (!isset($attributes['visit_time_from']) || empty($attributes['visit_time_from'])) {
            session()->flash('errors.visit_time_from.' . $key, __('demand.validation_error.visitTimeFromRequired'));

            return false;
        };

        return true;
    }

    /**
     * Validate visit adjust time
     *
     * @param  mixed $attributes
     * @return boolean
     */
    private function validateVisitAdjustTime($attributes, $key)
    {
        if ($attributes['is_visit_time_range_flg'] != 1) {
            return true;
        }
        if (!isset($attributes['visit_time_from']) || empty($attributes['visit_time_from'])) {
            return true;
        }

        if (empty($attributes['visit_adjust_time'])) {
            session()->flash('errors.adjust_time.' . $key, __('demand.adjust_visit_time'));
            return false;
        }

        return true;
    }

    /**
     * Process validate visit time
     *
     * @param  mixed $data
     * @return boolean
     */
    public function processValidateVisitTime($data)
    {
        $errFlg = false;

        if (isset($data['visitTime'])) {
            $vData = [];

            foreach ($data['visitTime'] as $v) {
                array_forget($v, 'commit_flg');
                array_forget($v, 'visit_time_before');
                array_forget($v, 'id');
                $addArray = [];
                $addArray['demand_id'] = $data['demandInfo']['id'] ?? 0;

                if (!empty($data['demandInfo']['do_auction'])) {
                    $addArray['do_auction'] = 1;
                }

                $vData[] = $v + $addArray;
            }
            foreach ($vData as $key => $viData) {
                if (!$this->validate($viData, $key + 1)) {
                    $errFlg = true;
                }
            }
        }
        return $errFlg;
    }
}
