<?php

use Carbon\Carbon;

const DAY_OF_WEEK = [
    "0" => '日', //0
    "1" => '月', //1
    "2" => '火', //2
    "3" => '水', //3
    "4" => '木', //4
    "5" => '金', //5
    "6" => '土', //6
];

if (!function_exists('date_time_format')) {
    /**
     * format date
     *
     * @param  string $date
     * @param  string $format
     * @return string
     */
    function date_time_format($date, $format)
    {
        if (checkIsNullOrEmptyStr($date)) {
            return "";
        }
        return Carbon::parse($date)->format($format);
    }
}

if (!function_exists('compare_two_date')) {
    /**
     * compare two date
     *
     * @param  date $firstDate
     * @param  date $secondDate
     * @return boolean
     */
    function compare_two_date($firstDate, $secondDate)
    {
        return $firstDate->gt($secondDate);
    }
}

if (!function_exists('date_time_format_jp')) {
    /**
     * format -> Y年m月d日（w）A h時i分 example: 2018年04月10日（火） 午後 20時15分
     *
     * @param  string $date
     * @return string
     */
    function date_time_format_jp($date)
    {
        if (checkIsNullOrEmptyStr($date)) {
            return "";
        } else {
            $dayOfWeek = date('w', strtotime($date));
            $dateFormat = date_time_format($date, 'Y年m月d日 A h時i分');
            $result = str_replace('AM', '(' . DAY_OF_WEEK[$dayOfWeek] . ') ' . '午前', $dateFormat);
            $result = str_replace('PM', '(' . DAY_OF_WEEK[$dayOfWeek] . ') ' . '午後', $result);
            return $result;
        }
    }
}

if (!function_exists('check_months_ago')) {
    /**
     * check months ago
     *
     * @return false|string
     */
    function check_months_ago()
    {
        return date('Y/m/01', strtotime('-1 month'));
    }
}

if (!function_exists('gmt_to_jst_time')) {
    /**
     * convert gmt to jst timezone for cyzen service
     * @param $time
     * @return false|string
     */
    function gmt_to_jst_time($time)
    {
        return date('Y-m-d H:i:s', strtotime($time.' + 9 hours'));
    }
}
