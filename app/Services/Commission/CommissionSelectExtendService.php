<?php

namespace App\Services\Commission;

use App\Repositories\CorpRegisteredScheduleRepositoryInterface;
use App\Repositories\CyzenDemandInfoRepositoryInterface;
use App\Repositories\CyzenHistoryRepositoryInterface;
use App\Repositories\CyzenSchedulesRepositoryInterface;
use App\Repositories\CyzenTrackingRepositoryInterface;
use App\Repositories\FiltersConditionsRepositoryInterface;
use App\Repositories\FiltersRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MStaffCategoryExclusionsRepositoryInterface;
use App\Repositories\MStaffRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class CommissionSelectExtendService extends BaseService
{
    const CONDITION_CD_FEE = 'fee';
    const CONDITION_CD_SCHEDULE = 'schedule';
    const CONDITION_CD_DISTANCE = 'distance';
    const DEFAULT_CONTACT_DEADLINE = 5; //5 minutes
    const DEFAULT_ESTIMATED_TIME = 120; //2 hours
    const COLUMN_EQUAL_FEE_SORT_CONDITION = 'order_fee';
    const COLUMN_EQUAL_DISTANCE_SORT_CONDITION = 'distance';
    const COLUMN_EQUAL_SCHEDULE_SORT_CONDITION = 'sort_schedule';
    const COLUMN_EQUAL_RANK_2_SORT_CONDITION = 'commission_unit_price_rank_2';
    const COLUMN_EQUAL_RANK_1_SORT_CONDITION = 'commission_unit_price_rank_1';
    const KEY_SORT_BY_STATUS_STAFF = '退勤';

    /** @var MCorpRepositoryInterface $mCorpRepos */
    protected $mCorpRepos;
    /** @var FiltersRepositoryInterface $filtersRepos */
    protected $filtersRepos;
    /** @var FiltersConditionsRepositoryInterface $filtersConditionRepos */
    protected $filtersConditionRepos;
    /** @var CorpRegisteredScheduleRepositoryInterface $affScheduleRepos */
    protected $affScheduleRepos;
    /** @var CyzenSchedulesRepositoryInterface $cyzenScheduleRepos */
    protected $cyzenScheduleRepos;
    /** @var CyzenTrackingRepositoryInterface $cyzenTrackingRepos */
    protected $cyzenTrackingRepos;
    /** @var MCorpCategoryRepositoryInterface $mCorpCategoryRepos */
    protected $mCorpCategoryRepos;
    /** @var MStaffCategoryExclusionsRepositoryInterface */
    protected $mStaffCategoryExclude;
    /** @var CyzenHistoryRepositoryInterface $cyzenHistoryRepos */
    protected $cyzenHistoryRepos;
    /** @var \App\Repositories\MStaffRepositoryInterface */
    protected $mStaffRepos;

    /**
     * @var CyzenDemandInfoRepositoryInterface $demandInfoRepos
     */
    protected $demandInfoRepos;

    /**
     * CommissionSelectExtendService constructor.
     *
     * @param MCorpRepositoryInterface $corpRepository
     * @param FiltersConditionsRepositoryInterface $filtersConditionsRepository
     * @param FiltersRepositoryInterface $filtersRepository
     * @param CorpRegisteredScheduleRepositoryInterface $affRegisteredScheduleRepository
     * @param CyzenTrackingRepositoryInterface $cyzenTrackingRepository
     * @param CyzenSchedulesRepositoryInterface $cyzenSchedulesRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     * @param MStaffCategoryExclusionsRepositoryInterface $staffCategoryExclusionsInterfaces
     * @param CyzenHistoryRepositoryInterface $cyzenHistoryRepository
     * @param CyzenDemandInfoRepositoryInterface $demandInfoRepository
     * @param MStaffRepositoryInterface $mStaffRepos
     */
    public function __construct(
        MCorpRepositoryInterface $corpRepository,
        FiltersConditionsRepositoryInterface $filtersConditionsRepository,
        FiltersRepositoryInterface $filtersRepository,
        CorpRegisteredScheduleRepositoryInterface $affRegisteredScheduleRepository,
        CyzenTrackingRepositoryInterface $cyzenTrackingRepository,
        CyzenSchedulesRepositoryInterface $cyzenSchedulesRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MStaffCategoryExclusionsRepositoryInterface $staffCategoryExclusionsInterfaces,
        CyzenHistoryRepositoryInterface $cyzenHistoryRepository,
        CyzenDemandInfoRepositoryInterface $demandInfoRepository,
        MStaffRepositoryInterface $mStaffRepos
    ) {
        $this->cyzenTrackingRepos = $cyzenTrackingRepository;
        $this->cyzenScheduleRepos = $cyzenSchedulesRepository;
        $this->affScheduleRepos = $affRegisteredScheduleRepository;
        $this->filtersRepos = $filtersRepository;
        $this->filtersConditionRepos = $filtersConditionsRepository;
        $this->mCorpRepos = $corpRepository;
        $this->mCorpCategoryRepos = $mCorpCategoryRepository;
        $this->mStaffCategoryExclude = $staffCategoryExclusionsInterfaces;
        $this->cyzenHistoryRepos = $cyzenHistoryRepository;
        $this->demandInfoRepos = $demandInfoRepository;
        $this->mStaffRepos = $mStaffRepos;
    }

    /**
     * @param $listId
     * @param $jisCd
     * @param $genreId
     * @param $categoryId
     * @param $lat
     * @param $lng
     * @param $startTime
     * @param $endTime
     * @param bool $bCount
     * @param string $excludeStaff
     * @param bool $isEstimateTime
     * @param bool $targetCheck
     * @param bool $isSpecificTime
     * @return mixed
     *
     * // $targetCheck = false => sort by commission_unit_price_rank_1
     */
    public function executeFilter(
        $listId,
        $jisCd,
        $genreId,
        $categoryId,
        $lat,
        $lng,
        $startTime,
        $endTime,
        $bCount = false,
        $excludeStaff = '',
        $isEstimateTime = false,
        $targetCheck = false,
        $isSpecificTime = false
    ) {
        $listExclude = $this->mStaffCategoryExclude->getListStaffExclude($jisCd, $genreId, $categoryId);
        $listCondition = $this->getConditionDetail($jisCd, $genreId, $categoryId);
        $listAll = collect($listId);
        $listFeeCondition = new Collection();
        $listDistanceCondition = new Collection();
        $listScheduleCondition = new Collection();
        $listSort = [];
        $distanceFirst = false;
        if (!empty($listCondition)) {
            // run by list order
            $i = 0;
            foreach ($listCondition as $condition) {
                $i++;
                if (strtolower($condition['condition_cd']) == strtolower(self::CONDITION_CD_FEE)) {
                    $listFeeCondition = $this->filterByFee($listId, $genreId, $categoryId, $condition);
                    $listSort[] = self::COLUMN_EQUAL_FEE_SORT_CONDITION;
                    $listFeeCondition = collect($listFeeCondition);
                }
                if (strtolower($condition['condition_cd']) == strtolower(self::CONDITION_CD_DISTANCE)) {
                    if ($i == 1) {
                        $distanceFirst = true;
                    }
                    $listSort[] = self::COLUMN_EQUAL_DISTANCE_SORT_CONDITION;
                    $listDistanceCondition = $this->filterByDistance($listId, $lat, $lng, $condition);
                    $listDistanceCondition = collect($listDistanceCondition)->unique('id_staff');
                }
                if (strtolower($condition['condition_cd']) == strtolower(self::CONDITION_CD_SCHEDULE)) {
                    $listSort[] = self::COLUMN_EQUAL_SCHEDULE_SORT_CONDITION;
                    $listScheduleCondition = $this->filterBySchedule(
                        $listId,
                        $startTime,
                        $endTime,
                        $genreId,
                        $categoryId,
                        $isEstimateTime,
                        $jisCd,
                        $isSpecificTime
                    );
                    $listScheduleCondition = collect($listScheduleCondition)->unique('id_staff');
                }
            }
        } else {
            //use for default if not setting condition
            //don't have condition fee
            $listSort[] = self::COLUMN_EQUAL_SCHEDULE_SORT_CONDITION;
            $listScheduleCondition = $this->filterBySchedule(
                $listId,
                $startTime,
                $endTime,
                $genreId,
                $categoryId,
                $isEstimateTime,
                $jisCd,
                $isSpecificTime
            );
            $listScheduleCondition = collect($listScheduleCondition);
        }
        $listSortKey = $this->createDataMapKey(
            $listScheduleCondition,
            $listDistanceCondition,
            $listFeeCondition,
            $listAll,
            $jisCd,
            $genreId,
            $categoryId
        );
        if ($bCount) {
            $corpCount = $this->countKameitenFullCondition(
                $listScheduleCondition,
                $listDistanceCondition,
                $listFeeCondition,
                $listAll,
                $listSortKey,
                $listExclude,
                $distanceFirst
            );
            return $corpCount;
        }
        $excludeStaffId = preg_split('/,/', $excludeStaff, -1, PREG_SPLIT_NO_EMPTY);
        $excludeStaffId = array_merge($excludeStaffId, $listExclude);
        $listCorp = $this->editListStaffFilter(
            $listScheduleCondition,
            $listDistanceCondition,
            $listFeeCondition,
            $listAll,
            $listSortKey,
            $listSort,
            $excludeStaffId,
            $targetCheck
        );
        if ($distanceFirst) {
            $listCorp = $this->removeGrayIfDistanceFirst($listCorp);
        }
        return $listCorp;
    }

    /**
     * @param $listCorp
     * @return mixed
     */
    private function removeGrayIfDistanceFirst($listCorp)
    {
        $listCorp = collect($listCorp)->reject(function ($item) {
            if (isset($item['is_gray']) && $item['is_gray'] == true) {
                return true;
            }
        });
        return array_values($listCorp->toArray());
    }

    /**
     * @param $jisCd
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    private function getConditionDetail($jisCd, $genreId, $categoryId)
    {
        $result = $this->filtersRepos->getFiltersId($jisCd, $genreId, $categoryId);
        if (!empty($result)) {
            $detail = $this->filtersConditionRepos->getConditionById($result[0]['id']);
            return $detail;
        } else {
            return null;
        }
    }

    /**
     * @param $listIdCorp
     * @param $genreId
     * @param $categoryId
     * @param $condition
     * @return array
     */
    public function filterByFee($listIdCorp, $genreId, $categoryId, $condition)
    {
        $listFiltered = [];
        if (is_array($listIdCorp) && count($listIdCorp) > 0) {
            foreach ($listIdCorp as $corpId) {
                $corpId['bFee'] = false;
                $result = $this->mCorpCategoryRepos->getOrderFeeCorp($corpId['corp_id'], $genreId, $categoryId);
                if (!empty($result[0])) {
                    $unit = __('affiliation.yen');
                    $value = (double)trim(preg_replace('/[^\d.]+/', '', $condition['limit']));
                    if ($result[0]['order_fee_unit'] == 1) {
                        $unit = '%';
                        $unitCondition = '%';
                    } else {
                        $unitCondition = trim(str_replace($value, '', $condition['limit']));
                    }
                    if (!empty($result[0]['order_fee'])) {
                        if ($unit == $unitCondition) {
                            if ($result[0]['order_fee'] >= $value) {
                                $corpId['bFee'] = true;
                                $listFiltered[] = $corpId;
                            }
                        }
                    } else {
                        if ($result[0]['introduce_fee'] >= $value) {
                            $corpId['bFee'] = true;
                            $listFiltered[] = $corpId;
                        }
                    }
                }
            }
        }
        return $listFiltered;
    }

    /**
     * @param $listIdCorp
     * @param $lat
     * @param $lng
     * @param $condition
     * @return array|mixed
     */
    public function filterByDistance($listIdCorp, $lat, $lng, $condition = null)
    {
        $unitCondition = trim(preg_replace('/[^\w]+/', '', $condition['limit']));
        $value = (int)trim(preg_replace('/[^\d]+/', '', $condition['limit']));
        if ($unitCondition == 'km') {
            $value = $value * 1000;
        }
        if (empty($value)) {
            $value = env('DISTANCE_DEFAULT_FILTER', 10000);
        }
        $listFiltered = [];
        if (is_array($listIdCorp) && count($listIdCorp) > 0) {
            $listFiltered = $this->cyzenTrackingRepos->getListDistanceOfUser(
                $listIdCorp,
                $lat,
                $lng,
                $value
            );
        }
        if (count($listFiltered) > 0) {
            $listFiltered = array_map(function ($item) {
                $item['bDistance'] = true;
                return $item;
            }, $listFiltered);
        }
        return $listFiltered;
    }

    /**
     * @param $listIdCorp
     * @param $startTime
     * @param $endTime
     * @param $genreId
     * @param $categoryId
     * @param $isEstimateTime
     * @param $jisCd
     * @param $isSpecificTime
     * @return array|mixed
     */
    public function filterBySchedule(
        $listIdCorp,
        $startTime,
        $endTime,
        $genreId,
        $categoryId,
        $isEstimateTime,
        $jisCd,
        $isSpecificTime
    ) {
        $listFiltered = [];
        $listCorp = array_pluck($listIdCorp, 'corp_id');
        if (is_array($listIdCorp) && count($listIdCorp) > 0) {
            $listFiltered = $this->demandInfoRepos->getStaffFreeSchedule(
                $listCorp,
                $startTime,
                $endTime,
                $genreId,
                $categoryId,
                $isSpecificTime
            );
        }

        //check staff schedule in $start and $end
        $listStaff = array_unique(array_pluck($listFiltered, 'id_staff'));
        $listStaffAccept = $this->filterByFreeRange(
            $listStaff,
            $listFiltered,
            $startTime,
            $endTime,
            $listCorp,
            $isEstimateTime,
            $jisCd,
            $genreId,
            $categoryId,
            $isSpecificTime
        );

        if (!empty($listStaffAccept)) {
            $listStaffAccept = array_map(function ($item) {
                $item['bSchedule'] = true;
                return $item;
            }, $listStaffAccept);
        }

        return $listStaffAccept;
    }

    /**
     * @param $listStaff
     * @param $listStaffOutSide
     * @param $start
     * @param $end
     * @param $listIdCorp
     * @param $isEstimateTime
     * @param $jisCd
     * @param $genreId
     * @param $categoryId
     * @param $isSpecificTime
     * @return mixed
     */
    private function filterByFreeRange(
        $listStaff,
        $listStaffOutSide,
        $start,
        $end,
        $listIdCorp,
        $isEstimateTime,
        $jisCd,
        $genreId,
        $categoryId,
        $isSpecificTime
    ) {
        $listStaffInside = $this->cyzenScheduleRepos->getListScheduleInRange(
            $listStaff,
            $start,
            $end,
            $listIdCorp,
            $genreId,
            $categoryId,
            $isEstimateTime
        );

        $listAllStaff = array_merge($listStaff, array_unique(array_pluck($listStaffInside, 'id_staff')));
        $staffInNotification = $this->cyzenScheduleRepos->getListScheduleInDemandNotification(
            $listAllStaff,
            $start,
            $end,
            $isSpecificTime,
            ''
        );
        if ($staffInNotification['outside'] && !empty($staffInNotification['data'])) {
            $listStaffOutSide = array_merge($listStaffOutSide, $staffInNotification['data']);
            $now = strtotime(now());
            $listStaffOutSide = collect($listStaffOutSide)->map(function ($item) use ($now, $start) {
                if (!isset($item['max_end']) ||
                    (isset($item['max_end']) && strtotime($item['max_end']) < $now && $now <= strtotime($start))) {
                    $item['max_end'] = date('Y-m-d H:i:s', $now);
                }
                return $item;
            })->toArray();
        } elseif (!empty($staffInNotification['data'])) {
            $listStaffInside = array_merge($listStaffInside, $staffInNotification['data']);
            $listStaffAfterMerge = array_unique(array_pluck($listStaffInside, 'id_staff'));
            $listStaffOutSide = collect($listStaffOutSide)->reject(function ($item) use ($listStaffAfterMerge) {
                if (is_array($listStaffAfterMerge) && in_array($item['id_staff'], $listStaffAfterMerge)) {
                    return true;
                }
                return false;
            })->toArray();
        }

        $listScheduleFree = $this->getScheduleFree(
            $start,
            $end,
            $listStaffInside,
            $listStaffOutSide,
            $listIdCorp,
            $isEstimateTime,
            $genreId,
            $categoryId
        );

        return $listScheduleFree;
    }

    /**
     * @param $start
     * @param $end
     * @param $listStaffIn
     * @param $listStaffOut
     * @param $listCorpId
     * @param $isEstimateTime
     * @param $genreId
     * @param $categoryId
     * @return  mixed
     */
    private function getScheduleFree(
        $start,
        $end,
        $listStaffIn,
        $listStaffOut,
        $listCorpId,
        $isEstimateTime,
        $genreId,
        $categoryId
    ) {
        $corpScheduleRange = $this->getRegisteredRange($listCorpId, $genreId, $categoryId, $isEstimateTime);
        //generate new draft schedule
        $staffTmp = [];
        $draft = [];
        $range = (strtotime($end) - strtotime($start)) / 60;

        foreach ($listStaffOut as $key => $item) {
            if ($range >= $corpScheduleRange[$item['corp_id']]) {
                $draft[$item['id_staff']] = $item;
                $draft[$item['id_staff']][self::COLUMN_EQUAL_SCHEDULE_SORT_CONDITION] = -1;
            }
        }


        //--sort schedule: include case overlap schedule
        $scheduleOverlap = [];
        usort($listStaffIn, function ($a, $b) {
            return ($b['start_date'] < $a['start_date']) ? 1 : 0;
        });

        foreach ($listStaffIn as $key => $item) {
            if (!isset($scheduleOverlap[$item['id_staff']]) || $item['start_date'] >= $scheduleOverlap[$item['id_staff']]['end_date']) {
                $scheduleOverlap[$item['id_staff']]['start_date'] = $item['start_date'];
                $scheduleOverlap[$item['id_staff']]['end_date'] = $item['end_date'];
            }

            if (isset($scheduleOverlap[$item['id_staff']])) {
                if ($item['start_date'] < $scheduleOverlap[$item['id_staff']]['end_date'] &&
                    $item['end_date'] > $scheduleOverlap[$item['id_staff']]['end_date']) {
                    $scheduleOverlap[$item['id_staff']]['end_date'] = $item['end_date'];
                }
            }

            $listStaffIn[$key]['start_date'] = $scheduleOverlap[$item['id_staff']]['start_date'];
            $listStaffIn[$key]['end_date'] = $scheduleOverlap[$item['id_staff']]['end_date'];
        }
        //--end sort schedule

        $draftIn = [];
        foreach ($listStaffIn as $key => $staff) {
            $endFree = $staff['end_date'];
            if (isset($draftIn[$staff['id_staff']]) && $draftIn[$staff['id_staff']]['is_real']) {
                continue;
            }

            if (!isset($staffTmp[$staff['id_staff']])) {
                $staffTmp[$staff['id_staff']]['start_free'] = (strtotime($start) > strtotime($staff['start_date']))
                    ? $staff['end_date']
                    : date('Y-m-d H:i:s', strtotime($start));
            }

            $free = (strtotime($staff['start_date']) - strtotime($staffTmp[$staff['id_staff']]['start_free'])) / 60; //in minutes
            $registeredTime = $corpScheduleRange[$staff['corp_id']];
            if ($free >= $registeredTime) {
                $staff['start_date'] = $staffTmp[$staff['id_staff']]['start_free'];
                $staff['end_date'] = date(
                    'Y-m-d H:i:s',
                    strtotime($staff['start_date'] . " + " . $registeredTime . " minutes")
                );
                $staff[self::COLUMN_EQUAL_SCHEDULE_SORT_CONDITION] = $key + 1;
                $staff['is_real'] = true;
                $draftIn[$staff['id_staff']] = $staff;
            } else {
                unset($draftIn[$staff['id_staff']]);
                $endDraft = (strtotime($end) - strtotime($staff['end_date'])) / 60; //in minutes
                if ($endDraft >= $registeredTime) {
                    $staff['start_date'] = $staff['end_date'];
                    $staff['end_date'] = date(
                        'Y-m-d H:i:s',
                        strtotime($staff['start_date'] . " + " . $registeredTime . " minutes")
                    );
                    $staff[self::COLUMN_EQUAL_SCHEDULE_SORT_CONDITION] = $key + 1;
                    $staff['is_real'] = false;
                    $draftIn[$staff['id_staff']] = $staff;
                }
            }
            $staffTmp[$staff['id_staff']]['start_free'] = $endFree;
        }

        $result = $this->generateSortIndex($draftIn, $draft);

        return array_values($result);
    }

    /**
     * @param $corpId
     * @param $genreId
     * @param $categoryId
     * @param $isEstimateTime
     * @return int|mixed
     */
    public function getRegisteredRange($corpId, $genreId, $categoryId, $isEstimateTime)
    {
        $output = [];
        if (!$isEstimateTime) {
            foreach ($corpId as $item) {
                $output[$item] = self::DEFAULT_CONTACT_DEADLINE;
            }
        } else {
            foreach ($corpId as $item) {
                $configTime = $this->getConfigRegisteredTime($genreId, $categoryId);

                if ($configTime) {
                    $output[$item] = $configTime;
                } else {
                    $output[$item] = self::DEFAULT_ESTIMATED_TIME;
                }
            }
            $registeredTime = $this->cyzenScheduleRepos->getCorpRegisteredSchedule($corpId, $genreId, $categoryId);
            if (!empty($registeredTime)) {
                foreach ($registeredTime as $item) {
                    $output[$item->corp_id] = $item->time_finish;
                }
            }
        }

        return $output;
    }

    /**
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    protected function getConfigRegisteredTime($genreId, $categoryId)
    {
        $allCorpSetting = config('corp_registered.all_corp');
        $allCorpCategorySetting = config('corp_registered.all_corp_category');

        foreach ($allCorpSetting as $setting) {
            if ($setting['genre_id'] == $genreId && $setting['category_id'] == $categoryId) {
                return $setting['time'];
            }
        }

        foreach ($allCorpCategorySetting as $setting) {
            if ($setting['genre_id'] == $genreId) {
                return $setting['time'];
            }
        }

        return false;
    }

    /**
     * @param $list
     * @param $origin
     * @return mixed
     */
    private function generateSortIndex($list, $origin)
    {
        usort($list, function ($a, $b) {
            return ($b['start_date'] < $a['start_date']) ? 1 : 0;
        });
        foreach ($list as $key => $item) {
            $item['sort_schedule'] = $key;
            $origin[$item['id_staff']] = $item;
        }
        return $origin;
    }

    /**
     * @param $listScheduleCondition
     * @param $listDistanceCondition
     * @param $listFeeCondition
     * @param $listAll
     * @param $jisCd
     * @param $genreId
     * @param $categoryId
     * @return static
     */
    private function createDataMapKey(
        &$listScheduleCondition,
        &$listDistanceCondition,
        &$listFeeCondition,
        &$listAll,
        $jisCd,
        $genreId,
        $categoryId
    ) {
        $this->mappingData($listScheduleCondition, $listDistanceCondition, $listFeeCondition, $listAll);
        $listCondition = $this->getConditionDetail($jisCd, $genreId, $categoryId);
        $listSortKey = collect([]);
        if (!empty($listCondition)) {
            foreach ($listCondition as $condition) {
                if (strtolower($condition['condition_cd']) == strtolower(self::CONDITION_CD_FEE)) {
                    $listSortKey = collect(array_keys($listFeeCondition))->merge($listSortKey);
                }
                if (strtolower($condition['condition_cd']) == strtolower(self::CONDITION_CD_DISTANCE)) {
                    $listSortKey = collect(array_keys($listDistanceCondition))->merge($listSortKey);
                }
                if (strtolower($condition['condition_cd']) == strtolower(self::CONDITION_CD_SCHEDULE)) {
                    $listSortKey = collect(array_keys($listScheduleCondition))->merge($listSortKey);
                }
            }
        } else {
            //use for default if not setting condition
            //don't have condition fee
            for ($i = 0; $i < 2; $i++) {
                if ($i == 0) {
                    $listSortKey = collect(array_keys($listDistanceCondition))->merge($listSortKey);
                }
                if ($i == 1) {
                    $listSortKey = collect(array_keys($listScheduleCondition))->merge($listSortKey);
                }
            }
        }
        $listSortKey = collect(array_keys($listAll))->merge($listSortKey);
        return $listSortKey;
    }

    /**
     * @param $listScheduleCondition
     * @param $listDistanceCondition
     * @param $listFeeCondition
     * @param $listAll
     */
    private function mappingData(
        &$listScheduleCondition,
        &$listDistanceCondition,
        &$listFeeCondition,
        &$listAll
    ) {
        $listDistanceTransform = [];
        foreach ($listDistanceCondition as $item) {
            if (isset($item['id_staff']) && !empty($item['id_staff'])) {
                $key = $item['corp_id'] . '#' . $item['id_staff'];
            } else {
                $key = $item['corp_id'];
            }
            $listDistanceTransform[$key] = $item;
        }
        $listScheduleTransform = [];
        foreach ($listScheduleCondition as $item) {
            if (isset($item['id_staff']) && !empty($item['id_staff'])) {
                $key = $item['corp_id'] . '#' . $item['id_staff'];
            } else {
                $key = $item['corp_id'];
            }
            $listScheduleTransform[$key] = $item;
        }
        $listFeeTransform = [];
        foreach ($listFeeCondition as $item) {
            $key = $item['corp_id'] . '';
            $listFeeTransform[$key] = $item;
        }
        $listAllTransform = [];
        foreach ($listAll as $item) {
            $key = $item['corp_id'] . '';
            $listAllTransform[$key] = $item;
        }
        $listAll = $listAllTransform;
        $listFeeCondition = $listFeeTransform;
        $listDistanceCondition = $listDistanceTransform;
        $listScheduleCondition = $listScheduleTransform;
    }

    /**
     * @param $listScheduleCondition
     * @param $listDistanceCondition
     * @param $listFeeCondition
     * @param $listAll
     * @param $listKey
     * @param $listExclude
     * @param $bDistanceFirst
     * @return mixed
     */
    private function countKameitenFullCondition(
        $listScheduleCondition,
        $listDistanceCondition,
        $listFeeCondition,
        $listAll,
        $listKey,
        $listExclude,
        $bDistanceFirst
    ) {
        $listCorp = $this->combineData(
            $listScheduleCondition,
            $listDistanceCondition,
            $listFeeCondition,
            $listAll,
            $listKey
        );
        $total = 0;
        $listCorp = collect($listCorp)->reject(function ($item) use (&$total) {
            if (!empty($item['bSchedule']) && !empty($item['bDistance']) && !empty($item['bFee'])) {
                $total++;
                return false;
            }
            return true;
        });
        $listCorp = collect($listCorp)->reject(function ($item) use (&$total, $listExclude) {
            if (isset($item['id_staff']) && strlen(trim($item['id_staff'])) > 0
                && in_array($item['id_staff'], $listExclude)) {
                $total--;
                return true;
            }
            return false;
        });
        if ($bDistanceFirst) {
            $listCorp = $this->getStatusNowForStaff($listCorp);
            $listCorp = $this->removeGrayIfDistanceFirst($listCorp);
        }
        if ($total > 0) {
            $total = count(collect($listCorp)->unique('corp_id'));
        }
        if ($total < 0) {
            $total = 0;
        }
        return $total;
    }

    /**
     * @param $listScheduleCondition
     * @param $listDistanceCondition
     * @param $listFeeCondition
     * @param $listAll
     * @param $listKey
     * @return mixed
     */
    private function combineData(
        $listScheduleCondition,
        $listDistanceCondition,
        $listFeeCondition,
        $listAll,
        $listKey
    ) {
        $listCorp = [];
        foreach ($listKey as $keys) {
            $keyArray = explode('#', $keys);
            $item = [];
            if (count($keyArray) == 2) {
                if (isset($listScheduleCondition[$keys])) {
                    $item += $listScheduleCondition[$keys];
                }
                if (isset($listDistanceCondition[$keys])) {
                    $item += $listDistanceCondition[$keys];
                }
                if (isset($listFeeCondition[$keyArray[0]])) {
                    $item += $listFeeCondition[$keyArray[0]];
                }
                if (isset($listAll[$keyArray[0]])) {
                    $item += $listAll[$keyArray[0]];
                }
            } else {
                if (isset($listAll[$keyArray[0]])) {
                    $item += $listAll[$keyArray[0]];
                }
            }
            $listCorp[] = $item;
        }
        return $listCorp;
    }

    /**
     * @param $listScheduleCondition
     * @param $listDistanceCondition
     * @param $listFeeCondition
     * @param $listAll
     * @param $listKey
     * @param $listSort
     * @param $excludeStaffId
     * @param $bTargetCheck
     * @return mixed
     */
    private function editListStaffFilter(
        $listScheduleCondition,
        $listDistanceCondition,
        $listFeeCondition,
        $listAll,
        $listKey,
        $listSort,
        $excludeStaffId,
        $bTargetCheck
    ) {
        $listCorp = $this->combineData(
            $listScheduleCondition,
            $listDistanceCondition,
            $listFeeCondition,
            $listAll,
            $listKey
        );
        //map data to set all_condition for show
        $listCorp = collect($listCorp)->map(function ($item) {
            $item['is_gray'] = false;
            $item['all_condition'] = false;
            if (!isset($item['id_staff']) || empty($item['id_staff'])) {
                $item['id_staff'] = '';
                $item['name_staff'] = '';
                $item['staff_phone'] = '';
            } else {
                if (!empty($item['bSchedule']) && $item['bSchedule']
                    && !empty($item['bDistance']) && $item['bDistance']
                    && !empty($item['bFee']) && $item['bFee']) {
                    $item['all_condition'] = true;
                } else {
                    if (empty($item['bSchedule'])) {
                        $item['id_staff'] = '';
                        $item['name_staff'] = '';
                        $item['staff_phone'] = '';
                    }
                }
            }
            return $item;
        });
        //sort group
        $listAllCondition = $this->sortAllCondition($listCorp, $listSort, $bTargetCheck);
        $listStaffNotAllCondition = $this->sortListHaveStaff($listCorp, $bTargetCheck);
        $listRest = $this->sortRestOfList($listCorp, $bTargetCheck);
        $listCorp = $this->uniqueAllList($listAllCondition, $listStaffNotAllCondition, $listRest);
        //reject staff have choice
        $listCorp = $this->removeStaffHadChoice($listCorp, $excludeStaffId);

        $listCorpSchedule = array_pluck($listScheduleCondition, 'corp_id');
        $listCorp = $this->removeCorpNoSchedule($listCorp, $listCorpSchedule);

        //add status for staffs
        $listCorp = $this->getStatusNowForStaff(collect($listCorp));
        //resort by  status
        $listCorp = $this->sortNewByGray($listCorp);
        return array_values($listCorp);
    }


    /**
     * @param Collection $listAllCorp
     * @param $listCorpSchedule
     * @return mixed
     */
    private function removeCorpNoSchedule($listAllCorp, $listCorpSchedule)
    {
        $corps = array_pluck($listAllCorp->toArray(), 'corp_id');
        $corpHaveStaff = $this->mStaffRepos->getCorpHaveStaff($corps);
        $listCorpSHaveStaff = array_pluck($corpHaveStaff, 'corp_id');

        $listCorp = $listAllCorp->reject(function ($item) use ($listCorpSHaveStaff, $listCorpSchedule) {
            if (! in_array($item['corp_id'], $listCorpSchedule) && in_array($item['corp_id'], $listCorpSHaveStaff)) {
                return true;
            }

            return false;
        });
        return $listCorp;
    }


    /**
     * @param collection $listCorp
     * @param $listCondition
     * @param $bTargetCheck
     * @return mixed
     */
    private function sortAllCondition($listCorp, $listCondition, $bTargetCheck)
    {
        $listSort = $this->createListConditionAndOrder($listCondition, $bTargetCheck);
        $listAllCondition = [];
        foreach ($listCorp->toArray() as $item) {
            if (isset($item['all_condition']) && $item['all_condition'] == true) {
                $item[self::COLUMN_EQUAL_DISTANCE_SORT_CONDITION] = (double)($item['distance']);
                $listAllCondition[] = $item;
            }
        }
        $listAllCondition = $this->arrayOrderByMultiColumn(
            $listAllCondition,
            $listSort
        );
        return $listAllCondition;
    }

    /**
     * @param $listSort
     * @param $targetCheck
     * @return mixed
     */
    private function createListConditionAndOrder($listSort, $targetCheck)
    {
        $listSorted = [];
        foreach ($listSort as $condition) {
            if ($condition == self::COLUMN_EQUAL_FEE_SORT_CONDITION) {
                $listSorted[self::COLUMN_EQUAL_FEE_SORT_CONDITION] = SORT_DESC;
            }
            if ($condition == self::COLUMN_EQUAL_DISTANCE_SORT_CONDITION) {
                $listSorted[self::COLUMN_EQUAL_DISTANCE_SORT_CONDITION] = SORT_ASC;
            }
            if ($condition == self::COLUMN_EQUAL_SCHEDULE_SORT_CONDITION) {
                $listSorted[self::COLUMN_EQUAL_SCHEDULE_SORT_CONDITION] = SORT_ASC;
            }
        }
        if ($targetCheck) {
            $listSorted[self::COLUMN_EQUAL_RANK_2_SORT_CONDITION] = SORT_ASC;
        } else {
            $listSorted[self::COLUMN_EQUAL_RANK_1_SORT_CONDITION] = SORT_ASC;
        }
        return $listSorted;
    }

    /**
     * @param $array
     * @param $cols
     * @return mixed
     */
    private function arrayOrderByMultiColumn($array, $cols)
    {
        $colArr = [];
        foreach ($cols as $col => $order) {
            $colArr[$col] = [];
            foreach ($array as $k => $row) {
                $colArr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colArr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = [];
        foreach ($colArr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k])) {
                    $ret[$k] = $array[$k];
                }
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;
    }

    /**
     * @param collection $listCorp
     * @param $targetCheck
     * @return mixed
     */
    private function sortListHaveStaff($listCorp, $targetCheck)
    {
        $listStaffNotAll = $listCorp->filter(function ($item) {
            if (!isset($item['all_condition']) || $item['all_condition'] == false) {
                if (!empty($item['name_staff'])) {
                    return true;
                }
            }
            return false;
        })->toArray();
        $listSort = $this->createBaseSortCondition($targetCheck);
        $listStaffNotAll = $this->arrayOrderByMultiColumn(
            $listStaffNotAll,
            $listSort
        );
        return $listStaffNotAll;
    }

    /**
     * @param $targetCheck
     * @return array
     */
    private function createBaseSortCondition($targetCheck)
    {
        $listSorted = [];
        $listSorted['group_corp'] = SORT_ASC;
        if ($targetCheck) {
            $listSorted[self::COLUMN_EQUAL_RANK_2_SORT_CONDITION] = SORT_ASC;
        } else {
            $listSorted[self::COLUMN_EQUAL_RANK_1_SORT_CONDITION] = SORT_ASC;
        }
        return $listSorted;
    }

    /**
     * @param collection $listCorp
     * @param $targetCheck
     * @return mixed
     */
    private function sortRestOfList($listCorp, $targetCheck)
    {
        $listRest = $listCorp->filter(function ($item) {
            if (!isset($item['all_condition']) || $item['all_condition'] == false) {
                if (empty($item['name_staff'])) {
                    return true;
                }
            }
            return false;
        })->toArray();
        $listSort = $this->createBaseSortCondition($targetCheck);
        $listRest = $this->arrayOrderByMultiColumn(
            $listRest,
            $listSort
        );
        return $listRest;
    }

    /**
     * @param $listAllCondition
     * @param $listStaff
     * @param $listRest
     * @return mixed
     */
    private function uniqueAllList($listAllCondition, $listStaff, $listRest)
    {
        $listCorp = collect($listAllCondition)->toBase()->merge(collect($listStaff))->toBase()->merge(collect($listRest));
        $listCorp = $listCorp->unique(function ($obj) {
            if (isset($obj['id_staff']) && !empty($obj['id_staff'])) {
                return $obj['corp_id'] . '#' . $obj['id_staff'];
            } else {
                return $obj['corp_id'];
            }
        });
        return $listCorp;
    }

    /**
     * @param collection $listCorp
     * @param $excludeStaffId
     * @return mixed
     */
    private function removeStaffHadChoice($listCorp, $excludeStaffId)
    {
        $listCorp = $listCorp->reject(function ($item) use ($excludeStaffId) {
            if (!empty($excludeStaffId)) {
                if (isset($item['id_staff']) && strlen(trim($item['id_staff'])) > 0
                    && in_array($item['id_staff'], $excludeStaffId)) {
                    return true;
                }
            }
            return false;
        });
        return $listCorp;
    }

    /**
     * getStatusNowForStaff
     * @param collection $listCorp
     * @return Collection
     */
    private function getStatusNowForStaff($listCorp)
    {
        $listIdStaff = $listCorp->pluck('id_staff')->filter();
        $result = $this->cyzenHistoryRepos->getStatusOfStaffs($listIdStaff);
        $listMap = $listCorp->map(function ($item) use ($result) {
            foreach ($result as $obj) {
                if ($obj['sp_user_id'] == $item['id_staff']) {
                    $item['status_name'] = $obj['status_name'];
                    $item['status_id'] = $obj['status_id'];
                    if ($obj['status_name'] == self::KEY_SORT_BY_STATUS_STAFF) {
                        $item['is_gray'] = true;
                    }
                    return $item;
                }
            }
            return $item;
        });
        return $listMap;
    }

    /**
     * @param collection $listCorp
     * @return mixed
     */
    private function sortNewByGray($listCorp)
    {
        $listSort['is_gray'] = SORT_DESC;
        $listSort['all_condition'] = SORT_DESC;
        $listSort['name_staff'] = SORT_DESC;
        $listCorp = $this->arrayOrderByMultiColumn(
            $listCorp->toArray(),
            $listSort
        );
        return $listCorp;
    }
}
