<?php

use App\Models\AuctionGenre;
use App\Models\AuctionGenreArea;
use App\Models\ExclusionTime;
use App\Models\MItem;
use App\Models\MSite;
use App\Models\PublicHoliday;
use App\Repositories\Eloquent\AuctionGenreAreaRepository;
use App\Repositories\Eloquent\AuctionGenreRepository;
use App\Repositories\Eloquent\ExclusionTimeRepository;
use App\Repositories\Eloquent\PublicHolidayRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent as Agent;

if (!function_exists('utilGetDropList')) {

    /**
     * Get item list
     * @param $category
     * @return array
     */

    function utilGetDropList($category)
    {
        $mItem = new MItem();
        return $mItem->getList($category);
    }
}

if (!function_exists('getDropdownList')) {
    /**
     * get item_category and item_id function
     *
     * @param  $list
     * @param  $oldList
     * @param  $itemId
     * @return array
     */
    function getDropdownList($list, $oldList, $itemId = null)
    {
        if ($itemId != null) {
            $list = array_merge($oldList, $list);
        }
        return $list;
    }
}

if (!function_exists('chgSearchValue')) {
    /**
     * change search value function
     *
     * @param  $value
     * @return mixed|string
     */
    function chgSearchValue($value)
    {

        $val = mb_convert_kana($value, "aks", "UTF-8");

        $val = strtolower($val);

        $val = str_replace(' ', '', $val);

        return $val;
    }
}

if (!function_exists('chgSearchValueSingle')) {
    /**
     * change search value function
     *
     * @param  $value
     * @return mixed|string
     */
    function chgSearchValueSingle($value)
    {
        $val = mb_convert_kana($value, "aks", "UTF-8");

        $val = strtolower($val);

        $val = str_replace(' ', '', $val);
        $val = str_replace("'", "''", $val);

        return $val;
    }
}

if (!function_exists('getDivValue')) {
    /**
     * Get the value of the partition list
     *
     * @param  $code
     * @param  $text
     * @return mixed
     */
    function getDivValue($code, $text)
    {
        $divList = array_flip(config('datacustom.' . $code));
        return @$divList[$text];
    }
}

if (!function_exists('getDivTextJP')) {
    /**
     * get config jp text
     *
     * @param  string $code
     * @param  string $key
     * @return string|array
     */
    function getDivTextJP($code, $key)
    {
        return config('datacustom.' . $code . '.' . $key);
    }
}

if (!function_exists('getDivText')) {
    /**
     * dateTimeWeekJP
     * get config jp text
     *
     * @param  string $code
     * @param  string $key
     * @return array|string
     */
    function getDivText($code, $key)
    {
        return config('datacustom.' . $code . '.' . $key);
    }
}

if (!function_exists('getDropText')) {
    /**
     * get item list
     *
     * @param  string $category
     * @param  string $value
     * @return string
     */
    function getDropText($category, $value)
    {
        $mItem = new MItem();
        return $mItem->getListText($category, $value);
    }
}

if (!function_exists('getDropList')) {
    /**
     * get item list
     * @param string $category
     * @return  array
     */
    function getDropList($category)
    {
        $mItem = new MItem();
        return $mItem->getList($category);
    }
}

if (!function_exists('yenFormatJbr')) {
    /**
     * get item list
     *
     * @param  $amount
     * @return string
     */
    function yenFormatJbr($amount)
    {
        if (is_numeric($amount)) {
            return __('money_correspond.yen2') . number_format($amount);
        } else {
            return __('money_correspond.yen2') . '0';
        }
    }
}

if (!function_exists('formatMoney')) {
    /**
     * get item list
     * @param $amount
     * @return string
     */
    function formatMoney($amount)
    {
        if (is_numeric($amount)) {
            return number_format($amount);
        } else {
            return 0;
        }
    }
}

if (!function_exists('getSiteList')) {
    /**
     * get sile list
     *
     * @return array
     */
    function getSiteList()
    {
        $mSite = new MSite();
        return $mSite->getList();
    }
}
if (!function_exists('getDivList')) {
    /**
     * get item list
     *
     * @param  string $code
     * @param  string $fileTrans
     * @return array
     */
    function getDivList($code, $fileTrans = null)
    {
        $list = config($code);

        if (!empty($fileTrans)) {
            foreach ($list as &$value) {
                $value = trans($fileTrans . '.' . $value);
            }
        }

        return $list;
    }
}
if (!function_exists('checkIsNullOrEmpty')) {
    /**
     * @param $array
     * @return boolean
     */
    function checkIsNullOrEmpty($array)
    {
        if (is_null($array) || empty($array)) {
            return true;
        }
        return false;
    }
}
if (!function_exists('checkIsNullOrEmptyCollection')) {

    /**
     * @param $collection
     * @return boolean
     */
    function checkIsNullOrEmptyCollection($collection)
    {
        if (is_null($collection) || $collection->isEmpty()) {
            return true;
        }
        return false;
    }
}

if (!function_exists('checkNotNullAndEmpty')) {
    /**
     * @param $array
     * @return boolean
     */
    function checkNotNullAndEmpty($array)
    {
        if (!is_null($array) && !empty($array)) {
            return true;
        }
        return false;
    }

}

if (!function_exists('checkIsNullOrEmptyStr')) {
    /**
     * @param $string
     * @return boolean
     */
    function checkIsNullOrEmptyStr($string)
    {
        if (is_null($string) || empty(trim($string))) {
            return true;
        }
        return false;
    }
}

if (!function_exists('validateStringIsNullOrEmpty')) {
    /**
     * this function return true when string is null or ''
     * else return false
     * @param $string
     * @return boolean
     */
    function validateStringIsNullOrEmpty($string)
    {
        if (is_null($string) || $string = "") {
            return true;
        }
        return false;
    }
}

if (!function_exists('utilIsMobile')) {
    /**
     * check is mobile
     *
     * @since  2015.05.10
     * @param  $userAgent
     * @return boolean
     */
    function utilIsMobile($userAgent)
    {
        $bool = preg_match('/(iPhone|iPad|Android|Windows.*Phone)/', $userAgent);

        return $bool;
    }
}

if (!function_exists('judgeHoliday')) {
    /**
     * Saturdays, Sundays, holidays
     *
     * @param $exclusionDay
     * @param $target
     * @return boolean
     */
    function judgeHoliday($exclusionDay, $target)
    {
        $result = false;
        $holiday = sprintf('0b%08b', $exclusionDay);
        $val = sprintf('0b%08b', hexdec($target));
        $cal = $holiday & $val;
        if ($cal == $val) {
            $result = true;
        }

        return $result;
    }
}

if (!function_exists('getCurrentDate')) {
    /**
     * get current date function
     *
     * @return boolean
     */
    function getCurrentDate()
    {
        return date('Y/m/d');
    }
}

if (!function_exists('dateTimeFormat')) {
    /**
     * datetime fomart
     *
     * @param  $date
     * @param  string $format
     * @return false|string
     */
    function dateTimeFormat($date, $format = 'Y/m/d H:i')
    {
        if (empty($date)) {
            return '';
        }

        try {
            new DateTime($date);
        } catch (Exception $e) {
            return $date;
        }

        $error = DateTime::getLastErrors();

        if ($error['warning_count'] != 0 || $error['error_count'] != 0) {
            return $date;
        }

        $createDate = date_create($date);

        return date_format($createDate, $format);
    }
}

/**
 * @param string $format
 * @return false|string
 */
function dateTimeNowFormat($format = 'Y/m/d H:i')
{
    return date($format);
}

if (!function_exists('getContactDesiredTime')) {
    /**
     * convert contact desired time
     * @param $data
     * @param string $separator
     * @param string $format
     * @return false|string
     */
    function getContactDesiredTime($data, $separator = '<br>〜<br>', $format = 'Y/m/d H:i')
    {
        $datetime = '';
        if ($data->is_contact_time_range_flg && isset($data->contact_desired_time_from)) {
            $out = dateTimeFormat($data->contact_desired_time_from, $format);
            $out .= $separator;
            $out .= dateTimeFormat($data->contact_desired_time_to, $format);
            return $out;
        }
        if (isset($data->contact_desired_time)) {
            $datetime = $data->contact_desired_time;
        }

        if (isset($data->is_visit_time_range_flg) && $data->is_visit_time_range_flg) {
            $datetime = $data->visit_adjust_time;
        }

        return dateTimeFormat($datetime, $format);
    }
}

if (!function_exists('yenFormat2')) {
    /**
     * Format as currency format (yen)
     *
     * @param  $amount
     * @return string
     */
    function yenFormat2($amount)
    {
        if (is_numeric($amount)) {
            return number_format($amount) . __('money_correspond.yen');
        } else {
            return '0' . __('money_correspond.yen');
        }
    }
}

if (!function_exists('getDateWeekMonth')) {
    /**
     * get date and week in month
     *
     * @author thaihv
     * @param  string $currentYear
     * @param  string $currentMonth
     * @return string
     * @throws Throwable
     */
    function getDateWeekMonth($currentYear = '', $currentMonth = '')
    {
        if (intval($currentMonth) < 10) {
            $currentMonth = '0' . intval($currentMonth);
        }
        $strDate = $currentYear . '/' . $currentMonth . '/';
        $holidays = PublicHoliday::where('holiday_date', 'LIKE', $strDate . '%')
            ->select('holiday_date')
            ->get();
        $arrHoliday = [];
        foreach ($holidays as $day) {
            if (!empty($day->holiday_date)) {
                $arrHoliday[] = explode('/', $day->holiday_date)[2];
            }
        }
        // generate currentYear and currentMonth
        $currentMonth = empty($currentMonth) ? date('m') : $currentMonth;
        $currentYear = empty($currentYear) ? date('Y') : $currentYear;

        // create carbon date from currentMonth and currentYear, start day = 1
        $tempDate = Carbon::createFromDate((int)$currentYear, (int)$currentMonth, 1);
        //get days of before currentMonth
        $skip = $tempDate->dayOfWeek;
        // sunday
        if ($skip == 0) {
            $skip = 7;
        }
        // remove the day before currentMonth
        for ($i = 1; $i < $skip; $i++) {
            $tempDate->subDay();
        }
        return view('calendar.calendar')->with(
            compact(
                'tempDate',
                'currentMonth',
                'currentYear',
                'arrHoliday'
            )
        )->render();
    }
}

if (!function_exists('getMenuByRole')) {
    /**
     * @param $role
     * @return mixed
     */
    function getMenuByRole($role)
    {
        return config('datacustom.menus')[$role];
    }
}

if (!function_exists('maskingAll')) {
    /**
     * @param $data
     * @return string
     */
    function maskingAll($data)
    {
        if (is_null($data)) {
            return '';
        }
        return '*******';
    }
}
if (!function_exists('formatDataBaseOnTable')) {
    /**
     * @param $tableName
     * @param $data
     * @return array
     */
    function formatDataBaseOnTable($tableName, $data)
    {
        $saveData = [];
        $columnName = DB::getSchemaBuilder()->getColumnListing($tableName);

        foreach ($columnName as $value) {
            if (array_key_exists($value, $data)) {
                $saveData[$value] = (isset($data[$value])) ? ($data[$value]) : null;
            }
        }

        if (isset($saveData['id'])) {
            unset($saveData['id']);
        }

        return $saveData;
    }
}


if (!function_exists('formatSec')) {
    /**
     * @param $sec
     */
    function formatSec($sec)
    {
        echo ($sec > 0) ? round($sec / 3600) . '時間' . round($sec % 3600 / 60) . '分' : '';
    }
}

if (!function_exists('outputClassOfCorpSelection')) {
    /**
     * @param $data
     * @return string
     */
    function outputClassOfCorpSelection($data)
    {
        $cssClass = '';

        $now = new DateTime();
        $contactDesiredTime = null;

        if (isset($data['contact_desired_time_from'])) {
            $contactDesiredTime = date_create_from_format(
                'Y/m/d H:i',
                dateTimeFormat($data['contact_desired_time_from'], 'Y/m/d H:i')
            );
        } elseif (isset($data['contact_desired_time'])) {
            $contactDesiredTime = date_create_from_format(
                'Y/m/d H:i',
                dateTimeFormat($data['contact_desired_time'], 'Y/m/d H:i')
            );
        }

        if ($contactDesiredTime instanceof DateTime) {
            $diffSec = $contactDesiredTime->format('U') - $now->format('U');
            $cssClass = $diffSec <= 3600 ? 'bgcolor-yellow' : $cssClass;
        }

        if (isset($data['visit_time'])) {
            $obj = date_create_from_format('Y-m-d H:i:s', $data['visit_adjust_time']);
            if ($obj instanceof DateTime) {
                $cssClass = 'bgcolor-red';
            } else {
                $obj = date_create_from_format('Y-m-d H:i:s', $data['visit_time']);
                if ($obj instanceof DateTime) {
                    $diffSec = $obj->format('U') - $now->format('U');
                    $cssClass = $diffSec <= 3600 ? 'bgcolor-yellow' : $cssClass;
                }
            }
        }

        addBgColorRed($contactDesiredTime, $cssClass);

        return $cssClass;
    }

    /**
     * @param DateTime $contactDesiredTime
     * @param string $cssClass
     */
    function addBgColorRed($contactDesiredTime, &$cssClass)
    {
        if ($contactDesiredTime instanceof DateTime && $contactDesiredTime->format('Hi') == '0000') {
            $cssClass = 'bgcolor-red';
        }
    }
}

if (!function_exists('outputClassOfCorpSelection2')) {
    /**
     * @param $data
     * @return string
     */
    function outputClassOfCorpSelection2($data)
    {
        $cssClass = '';
        $detectTime = '';
        if (isset($data->contact_desired_time) && $data->contact_desired_time) {
            $detectTime = $data->contact_desired_time;
        }
        if (isset($data->contact_desired_time_from) && $data->contact_desired_time_from) {
            $detectTime = $data->contact_desired_time_from;
        }
        if (isset($data->visit_adjust_time) && !empty($data->visit_adjust_time)) {
            $detectTime = $data->visit_adjust_time;
        }
        $untilLimitSec = strtotime($detectTime) - time();

        if ($data->modified_user_id == 'AutomaticAuction') {
            $cssClass = 'bgcolor-green';
        } elseif ($untilLimitSec < 3600) {
            $cssClass = 'bgcolor-red';
        } elseif ($untilLimitSec < 7200) {
            $cssClass = 'bgcolor-yellow';
        }

        return $cssClass;
    }

}

if (!function_exists('maskingAddress3')) {
    /**
     * @param $address3
     * @return string
     */
    function maskingAddress3($address3)
    {
        if (is_null($address3)) {
            return '';
        }

        $ret = mb_convert_kana(mb_substr($address3, 0, 3, "UTF-8"), 'n', 'UTF-8');
        $ret = preg_replace('/\d+/', '*', $ret) . '*******';

        return $ret;
    }
}

if (!function_exists('maskingAll')) {
    /**
     * @param $data
     * @return string
     */
    function maskingAll($data)
    {
        if (is_null($data)) {
            return '';
        }

        $ret = '*******';

        return $ret;
    }
}
if (!function_exists('getContactDesiredTime2')) {
    /**
     * @param $data
     * @param string $separator
     * @param string $format
     * @return string
     */
    function getContactDesiredTime2($data, $separator = '<br>〜<br>', $format = 'Y/m/d H:i')
    {
        $datetime = '';
        if (isset($data->demand_infos_contact_desired_time)) {
            $datetime = $data->demand_infos_contact_desired_time;
        }
        if (isset($data->demand_infos_contact_desired_time_from)) {
            $out = dateTimeWeek($data->demand_infos_contact_desired_time_from, $format);
            $out .= $separator;
            $out .= dateTimeWeek($data->demand_infos_contact_desired_time_to, $format);
            return $out;
        }
        if ($data->visit_time_view_visit_adjust_time) {
            $datetime = $data->visit_time_view_visit_adjust_time;
        }
        return dateTimeWeek($datetime, $format);
    }
}

if (!function_exists('getContactDesiredTimeExport')) {
    /**
     * convert contact desiredtime
     *
     * @param  $demandInfo
     * @param  string $separator
     * @param  string $format
     * @return false|string
     */
    function getContactDesiredTimeExport($demandInfo, $separator = '<br>〜<br>', $format = 'Y/m/d H:i')
    {
        $datetime = '';
        if (isset($demandInfo['demand_infos_is_contact_time_range_flg']) && isset($demandInfo['demand_infos_contact_desired_time_from'])) {
            $out = dateTimeFormat($demandInfo['demand_infos_contact_desired_time_from'], $format);
            $out .= $separator;
            $out .= dateTimeFormat($demandInfo['demand_infos_contact_desired_time_to'], $format);
            return $out;
        }
        if (isset($demandInfo['demand_infos_contact_desired_time'])) {
            $datetime = $demandInfo['demand_infos_contact_desired_time'];
        }
        return dateTimeFormat($datetime, $format);
    }
}

if (!function_exists('getMinVisitTime')) {
    /**
     * @param array $date
     * @return mixed|null
     */
    function getMinVisitTime($date = [])
    {
        if (!isset($date)) {
            return null;
        }
        sort($date);
        return $date[0];
    }
}
if (!function_exists('dateFormat')) {
    /**
     * @param $date
     * @param string $format
     * @return false|string
     */
    function dateFormat($date, $format = 'Y/m/d')
    {
        return date($format, strtotime($date));
    }
}

if (!function_exists('judgeNormal')) {
    /**
     * @param $createDate
     * @param $genreId
     * @param $prefectureCd
     * @param $limitDate
     * @param $priority
     * @return array
     */
    function judgeNormal($createDate, $genreId, $prefectureCd, &$limitDate, &$priority)
    {

        $exclusionTime = new ExclusionTimeRepository(new ExclusionTime);

        $exclusionData = $exclusionTime->getData($genreId, $prefectureCd);
        $auctionData = getAuctionSetting($genreId, $prefectureCd);

        // Exclusion time determination
        $judgeResult = judgeExclusion($createDate, $exclusionData);
        //Except for the exclusion time, find the difference between the project creation date and the desired date (minutes)
        if ($judgeResult['result_flg'] == 1) {
            $wkDate = $judgeResult['result_date'];
        } else {
            $wkDate = $createDate;
        }
        $minutes = $auctionData['normal3'];
        $limitDate = date('Y-m-d H:i', strtotime($wkDate . '+' . $minutes . 'minute'));

        // Setting priority of ordinary case
        $priority = getDivValue('priority', 'normal');

        return $judgeResult;
    }
}

if (!function_exists('judgeImmediately')) {
    /**
     * @param $createDate
     * @param $preferredDate
     * @param $genreId
     * @param $prefectureCd
     * @param $limitDate
     * @return array
     */
    function judgeImmediately($createDate, $preferredDate, $genreId, $prefectureCd, &$limitDate)
    {

        $exclusionTime = new ExclusionTimeRepository(new ExclusionTime);

        $exclusionData = $exclusionTime->getData($genreId, $prefectureCd);
        $auctionData = getAuctionSetting($genreId, $prefectureCd);

        // Exclusion time determination
        $judgeResult = judgeExclusion($createDate, $exclusionData);

        if ($judgeResult['result_flg'] == 1) {
            $wkDate = $judgeResult['result_date'];
        } else {
            $wkDate = $createDate;
        }

        $limitDate = date('Y-m-d H:i', strtotime($wkDate . '+' . $auctionData['immediately'] . ' minute'));

        // Find the difference between the calculated bidding deadline and the desired date (minutes)
        $preDate = strtotime($preferredDate);
        $limDate = strtotime($limitDate);
        $diff = abs($preDate - $limDate);

        // Fix it to minute
        $minutes = floor($diff / 60);

        if ($minutes <= $auctionData['immediately_small']) {
            //If [Z] - [Y] is 30 minutes or less, [Y] -30 minutes shall be the bidding deadline.
            $limitDate = date(
                'Y-m-d H:i',
                strtotime($preferredDate . '-' . $auctionData['immediately_small'] . ' minute')
            );
        }

        return $judgeResult;
    }
}

if (!function_exists('confirmHoliday')) {
    /**
     * @param $date
     * @param array $exclusionData
     * @return bool
     */
    function confirmHoliday($date, $exclusionData = [])
    {

        // Saturday
        if (judgeHoliday($exclusionData['exclusion_day'], getDivValue('holiday', 'saturday'))) {
            $datetime = new DateTime($date);

            if ((int)$datetime->format('w') == 6) {
                return true;
            }
        }

        // Sunday
        if (judgeHoliday($exclusionData['exclusion_day'], getDivValue('holiday', 'sunday'))) {
            $datetime = new DateTime($date);

            if ((int)$datetime->format('w') == 0) {
                return true;
            }
        }

        // holiday
        $publicHoliday = new PublicHolidayRepository(new PublicHoliday);
        if ($publicHoliday->checkHoliday(dateFormat($date))) {
            return true;
        }

        return false;
    }
}

if (!function_exists('judgeExclusion')) {
    /**
     * @param $createDate
     * @param array $exclusionData
     * @return array
     */
    function judgeExclusion($createDate, $exclusionData = [])
    {
        $exclusiveStartDate = null;
        $exclusiveEndDate = null;
        $judgeResult = [
            "result_flg" => 0,// 0 = No change, 1 = Changed (It is subject to exclusion time in normal case)
            "result_date" => null // start date
        ];
        // Get a holiday master
        $publicHoliday = new PublicHolidayRepository(new PublicHoliday);
        $holiday = $publicHoliday->findAll();
        // Get exclusion time
        if (!empty($exclusionData['exclusion_time_from'])) {
            // ■ Date of creation
            $exclusiveStartDate = dateFormat($createDate) . ' ' . $exclusionData['exclusion_time_from'];
            $exclusiveEndDate = dateFormat($createDate) . ' ' . $exclusionData['exclusion_time_to'];

            // When the exclusion time does not go over the day
            if (strtotime($exclusiveStartDate) < strtotime($exclusiveEndDate)) {
                $wkCreate = dateFormat($createDate, 'Y-m-d H:i');
                if (strtotime($exclusiveStartDate) <= strtotime($wkCreate) && strtotime($wkCreate) <= strtotime($exclusiveEndDate)) {
                    $judgeResult['result_flg'] = 1;
                    $createDate = $exclusiveEndDate;
                }// When the exclusion time straddles over the day
            } else {
                // Get opportunity creation time
                getOpportunityCreationTime(
                    $createDate,
                    $exclusionData,
                    $exclusiveStartDate,
                    $exclusiveEndDate,
                    $judgeResult
                );
            }
        }

        // Holiday / Holidays Judgment
        getHolidaysJudgment($createDate, $exclusionData, $judgeResult, $holiday);
        if ($judgeResult['result_flg'] == 1) {
            $createDate = date('Y-m-d H:i', strtotime($createDate . '+1 minute'));
            $judgeResult['result_date'] = $createDate;
        }


        Log::debug("判定a");
        Log::debug($judgeResult['result_flg']);
        Log::debug($judgeResult['result_date']);

        return $judgeResult;
    }

    /**
     * @param $createDate
     * @param $exclusionData
     * @param $judgeResult
     * @param $holiday
     */
    function getHolidaysJudgment(&$createDate, $exclusionData, &$judgeResult, $holiday)
    {
        if (confirmHoliday($createDate, $exclusionData)) {
            // Saturday
            if (judgeHoliday($exclusionData['exclusion_day'], getDivValue('holiday', 'saturday'))) {
                $datetime = new DateTime($createDate);

                if ((int)$datetime->format('w') == 6) {
                    $judgeResult['result_flg'] = 1;
                    // Set the exclusion time clearance of the next day for the case creation time
                    $createDate = date('Y/m/d', strtotime($createDate . '+1 day')) .
                        " " . $exclusionData['exclusion_time_to'];
                }
            }

            // Sunday
            if (judgeHoliday($exclusionData['exclusion_day'], getDivValue('holiday', 'sunday'))) {
                $datetime = new DateTime($createDate);

                if ((int)$datetime->format('w') == 0) {
                    $judgeResult['result_flg'] = 1;
                    // Set the exclusion time clearance of the next day for the case creation time
                    $createDate = date('Y/m/d', strtotime($createDate . '+1 day')) .
                        " " . $exclusionData['exclusion_time_to'];
                }
            }

            // public holiday
            foreach ($holiday as $row) {
                if ($row['holiday_date'] == dateFormat($createDate)) {
                    if (judgeHoliday($exclusionData['exclusion_day'], getDivValue('holiday', 'public_holiday'))) {
                        $judgeResult['result_flg'] = 1;
                        // Set the exclusion time clearance of the next day for the case creation time
                        $createDate = date('Y/m/d', strtotime($createDate . '+1 day')) .
                            " " . $exclusionData['exclusion_time_to'];
                        continue;
                    }
                }
            }
        }
    }

    /**
     * @param $createDate
     * @param $exclusionData
     * @param $exclusiveStartDate
     * @param $exclusiveEndDate
     * @param $judgeResult
     */
    function getOpportunityCreationTime(
        &$createDate,
        $exclusionData,
        &$exclusiveStartDate,
        &$exclusiveEndDate,
        &$judgeResult
    ) {
        $div = explode(" ", $createDate);

        if (strtotime($exclusionData['exclusion_time_from']) <= strtotime($div[1]) && strtotime($div[1]) <= strtotime('23:59')) {
            // If the opportunity creation time is between FROM - 23: 59, the exclusion end date is + 1 day
            $exclusiveEndDate = date('Y-m-d H:i', strtotime($exclusiveEndDate . " + 1 day"));
            // When the case creation date is included in the exclusion time, processing is terminated
            $wkCreate = dateFormat($createDate, 'Y-m-d H:i');
            if (strtotime($exclusiveStartDate) <= strtotime($wkCreate) && strtotime($wkCreate) <= strtotime($exclusiveEndDate)) {
                $judgeResult['result_flg'] = 1;
                $createDate = $exclusiveEndDate;
            }
        } elseif (strtotime('00:00') <= strtotime($div[1]) && strtotime($div[1]) <= strtotime($exclusionData['exclusion_time_to'])) {
            // If the opportunity creation time is between 00: 00 - TO, the exclusion start date - 1 day
            $exclusiveStartDate = date('Y-m-d H:i', strtotime($exclusiveStartDate . " - 1 day"));
            // When the case creation date is included in the exclusion time, processing is terminated
            $wkCreate = dateFormat($createDate, 'Y-m-d H:i');
            if (strtotime($exclusiveStartDate) <= strtotime($wkCreate) && strtotime($wkCreate) <= strtotime($exclusiveEndDate)) {
                $judgeResult['result_flg'] = 1;
                $createDate = $exclusiveEndDate;
            }
        }
    }
}

if (!function_exists('getAuctionSetting')) {
    /**
     * @param $genreId
     * @param $prefectureCd
     * @return array|\Illuminate\Support\Collection
     */
    function getAuctionSetting($genreId, $prefectureCd)
    {
        // Obtained from the auction regional detail table
        $auctionGenreArea = new AuctionGenreAreaRepository(new AuctionGenreArea);
        $results = $auctionGenreArea->getFirstByGenreIdAndPrefCd($genreId, $prefectureCd);
        if (!empty($results)) {
            return $results;
        }

        $auctionGenre = new AuctionGenreRepository(new AuctionGenre);
        $results = $auctionGenre->getFirstByGenreId($genreId);
        return $results;
    }
}

if (!function_exists('judgeAsap')) {
    /**
     * @param $createDate
     * @param $genreId
     * @param $prefectureCd
     * @param $limitDate
     * @return array
     */
    function judgeAsap($createDate, $genreId, $prefectureCd, &$limitDate)
    {

        $exclusionTime = new ExclusionTimeRepository(new ExclusionTime);

        $exclusionData = $exclusionTime->getData($genreId, $prefectureCd);
        $auctionData = getAuctionSetting($genreId, $prefectureCd);
        // Exclusion time determination
        $judgeResult = judgeExclusion($createDate, $exclusionData);
        if ($judgeResult['result_flg'] == 1) {
            $wkDate = $judgeResult['result_date'];
        } else {
            $wkDate = $createDate;
        }

        // Set correspondence deadline
        $limitDate = date('Y-m-d H:i', strtotime($wkDate . '+' . $auctionData['asap'] . ' minute'));

        return $judgeResult;
    }
}

if (!function_exists('judgeAuction')) {

    /**
     * @param $createDate
     * @param $preferredDate
     * @param $genreId
     * @param $prefectureCd
     * @param $limitDate
     * @param $priority
     * @return array
     */
    function judgeAuction($createDate, $preferredDate, $genreId, $prefectureCd, &$limitDate, &$priority)
    {

        $auctionData = getAuctionSetting($genreId, $prefectureCd);
        // Find the difference between the project creation date and the desired date (minutes)
        $creDate = strtotime($createDate);
        $preDate = strtotime($preferredDate);
        // If the opportunity creation date and the desired date are reversed, return it with false and make it manual.
        if ($preDate < $creDate) {
            $judgeResult = [
                "result_flg" => 1, // 0 = No change, 1 = Changed (It is subject to exclusion time in normal case)
                "result_date" => null // start date
            ];
            return $judgeResult;
        }
        $diff = abs($preDate - $creDate);
        // Fix it to minute
        $minutes = floor($diff / 60);
        // Perform priority determination and obtain correspondence deadline.
        if ($minutes <= $auctionData['limit_asap']) {
            $priority = getDivValue('priority', 'asap');
            //Correct to return return code at the same time at urgent processing
            return judgeAsap($createDate, $genreId, $prefectureCd, $limitDate);
        } elseif ($minutes <= $auctionData['limit_immediately']) {
            $priority = getDivValue('priority', 'immediately');
            //Correct to return return code at the same time during urgent processing
            return judgeImmediately($createDate, $preferredDate, $genreId, $prefectureCd, $limitDate);
        } else {
            return judgeNormal($createDate, $genreId, $prefectureCd, $limitDate, $priority);
        }
    }
}

if (!function_exists('getPushSendTimeOfVisitTime')) {

    /**
     * @param $createDate
     * @param $limitDate
     * @param $genreId
     * @param $prefectureCd
     * @return array
     */
    function getPushSendTimeOfVisitTime($createDate, $limitDate, $genreId, $prefectureCd)
    {

        $auctionData = getAuctionSetting($genreId, $prefectureCd);

        $rankA = empty($auctionData['open_rank_a']) ? 0 : $auctionData['open_rank_a'];
        $rankB = empty($auctionData['open_rank_b']) ? 0 : $auctionData['open_rank_b'];
        $rankC = empty($auctionData['open_rank_c']) ? 0 : $auctionData['open_rank_c'];
        $rankD = empty($auctionData['open_rank_d']) ? 0 : $auctionData['open_rank_d'];
        $rankZ = empty($auctionData['open_rank_z']) ? 0 : $auctionData['open_rank_z'];

        $rankA = 100 - $rankA;
        $rankB = 100 - $rankB;
        $rankC = 100 - $rankC;
        $rankD = 100 - $rankD;
        $rankZ = 100 - $rankZ;

        // Find the difference between opportunity creation date and correspondence deadline (minutes)
        $creDate = strtotime($createDate);
        $limDate = strtotime($limitDate);
        $diff = abs($limDate - $creDate);

        // Fix it to minute
        $minutes = floor($diff / 60);

        $rankAMins = floor($minutes * ($rankA / 100));
        $rankBMins = floor($minutes * ($rankB / 100));
        $rankCMins = floor($minutes * ($rankC / 100));
        $rankDMins = floor($minutes * ($rankD / 100));
        $rankZMins = floor($minutes * ($rankZ / 100));

        // Information transmission
        $arr = [];
        $arr['a'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankAMins . " minute"));
        $arr['b'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankBMins . " minute"));
        $arr['c'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankCMins . " minute"));
        $arr['d'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankDMins . " minute"));
        $arr['z'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankZMins . " minute"));

        // However, when% of open rank is not set, it is excluded from transmission
        $arr['a'] = setRank($rankA, $arr['a']);
        $arr['b'] = setRank($rankB, $arr['b']);
        $arr['c'] = setRank($rankC, $arr['c']);
        $arr['d'] = setRank($rankD, $arr['d']);
        $arr['z'] = setRank($rankZ, $arr['z']);

        return $arr;
    }

}
if (!function_exists('getPushSendTimeOfContactDesiredTime')) {

    /**
     * @param $createDate
     * @param $limitDate
     * @param $genreId
     * @param $prefectureCd
     * @return array
     */
    function getPushSendTimeOfContactDesiredTime($createDate, $limitDate, $genreId, $prefectureCd)
    {

        $auctionData = getAuctionSetting($genreId, $prefectureCd);

        $rankA = empty($auctionData['tel_hope_a']) ? 0 : $auctionData['tel_hope_a'];
        $rankB = empty($auctionData['tel_hope_b']) ? 0 : $auctionData['tel_hope_b'];
        $rankC = empty($auctionData['tel_hope_c']) ? 0 : $auctionData['tel_hope_c'];
        $rankD = empty($auctionData['tel_hope_d']) ? 0 : $auctionData['tel_hope_d'];
        $rankZ = empty($auctionData['tel_hope_z']) ? 0 : $auctionData['tel_hope_z'];

        $rankA = 100 - $rankA;
        $rankB = 100 - $rankB;
        $rankC = 100 - $rankC;
        $rankD = 100 - $rankD;
        $rankZ = 100 - $rankZ;

        // Find the difference between opportunity creation date and correspondence deadline (minutes)
        $creDate = strtotime($createDate);
        $limDate = strtotime($limitDate);
        $diff = abs($limDate - $creDate);

        // Fix it to minute
        $minutes = floor($diff / 60);

        $rankAMins = floor($minutes * ($rankA / 100));
        $rankBMins = floor($minutes * ($rankB / 100));
        $rankCMins = floor($minutes * ($rankC / 100));
        $rankDMins = floor($minutes * ($rankD / 100));
        $rankZMins = floor($minutes * ($rankZ / 100));

        $arr = [];
        $arr['a'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankAMins . " minute"));
        $arr['b'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankBMins . " minute"));
        $arr['c'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankCMins . " minute"));
        $arr['d'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankDMins . " minute"));
        $arr['z'] = date('Y-m-d H:i', strtotime($createDate . " + " . $rankZMins . " minute"));

        // However, when% of open rank is not set, it is excluded from transmission
        $arr['a'] = setRank($rankA, $arr['a']);
        $arr['b'] = setRank($rankB, $arr['b']);
        $arr['c'] = setRank($rankC, $arr['c']);
        $arr['d'] = setRank($rankD, $arr['d']);
        $arr['z'] = setRank($rankZ, $arr['z']);

        return $arr;
    }
}
if (!function_exists('setRank')) {
    /**
     * @param $rank
     * @param $rankValue
     * @return string
     */
    function setRank($rank, $rankValue)
    {
        if ($rank == 100) {
            return '';
        }
        return $rankValue;
    }
}
if (!function_exists('getLastStepStatusList')) {
    /**
     * @return array
     */
    function getLastStepStatusList()
    {
        $ret = [
            8 => '[電話対応]「検討（加盟店様対応中）」',
            9 => '[電話対応]「検討（営業支援対象）」',
            3 => '[電話対応]「失注」',
            10 => '[訪問対応]「検討（加盟店様対応中）」',
            11 => '[訪問対応]「検討（営業支援対象）」',
            6 => '[訪問対応]「失注」',
            7 => '[受注対応]「キャンセル」',
        ];
        return $ret;
    }
}

if (!function_exists('outputClassOfDateLimit')) {
    /**
     * @param $data
     */
    function outputClassOfDateLimit($data)
    {
        $detectTime = '';
        if (isset($data['contact_desired_time']) && $data['contact_desired_time']) {
            $detectTime = $data['contact_desired_time'];
        }
        if (isset($data['contact_desired_time_from']) && $data['contact_desired_time_from']) {
            $detectTime = $data['contact_desired_time_from'];
        }
        if (isset($data['visit_adjust_time'])) {
            $detectTime = $data['visit_adjust_time'];
        }

        $untilLimitSec = strtotime($detectTime) - time();

        if ($data['modified_user_id'] == 'AutomaticAuction') {
            echo 'bgcolor-green';
        } elseif ($untilLimitSec < 3600) {
            echo 'bgcolor-red';
        } elseif ($untilLimitSec < 7200) {
            echo 'bgcolor-yellow';
        }
    }
}

if (!function_exists('getFullQuery')) {
    /**
     * @param $builder
     * @return null|string|string[]
     */
    function getFullQuery($builder)
    {
        $sql = $builder->toSql();
        foreach ($builder->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }
}

if (!function_exists('getVisitTime')) {
    /**
     * @param $data
     * @param string $format
     * @return false|string
     */
    function getVisitTime($data, $format = 'Y/m/d H:i')
    {
        $out = dateTimeFormat($data->visit_time, $format);
        if ($data->is_visit_time_range_flg && isset($data->visit_time_to) && $data->visit_time_to) {
            $out .= '<br>〜<br>';
            $out .= dateTimeFormat($data->visit_time_to, $format);
        }
        return $out;
    }
}

if (!function_exists('formatDateWeek')) {

    /**
     * Format date week
     * @param $date
     * @return string
     */
    function formatDateWeek($date)
    {
        setlocale(LC_TIME, 'ja_JP.utf8');
        return strftime('%Y/%m/%d(%a)%R', strtotime($date));
    }
}

if (!function_exists('formatTextLineDown')) {

    /**
     * Format text line down
     * @param string $text
     * @return null|string|string[]
     */
    function formatTextLineDown($text)
    {
        return preg_replace("/\\r\\n|\\r|\\n/", '<br>', $text);
    }
}

if (!function_exists('dateTimeWeekJP')) {
    /**
     * dateTimeWeekJP
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param $date
     * @return false|string
     */
    function dateTimeWeekJP($date)
    {
        if (empty($date)) {
            return "";
        }
        try {
            new DateTime($date);
        } catch (Exception $exception) {
            return $date;
        }
        $error = DateTime::getLastErrors();
        if ($error['warning_count'] != 0 || $error['error_count'] != 0) {
            return $date;
        }
        $dateCreate = date_create($date);
        $week = ["日", "月", "火", "水", "木", "金", "土"];
        $date = date_format($dateCreate, 'Y年m月d日');
        $time = date_format($dateCreate, 'H:i');
        $weekSecond = (int)date_format($dateCreate, 'w');
        return $date . '(' . $week[$weekSecond] . ')　' . $time;
    }
}


if(!function_exists('checkDevice')){

    /**
     * @return string
     */
    function checkDevice()
    {
        $agent = new Agent();
        $result = 'callto:';
        if ($agent->isMobile()) {
            $result = 'tel:';
        }
        return $result;
    }
}