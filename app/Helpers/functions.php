<?php
/**
 * dateTimeWeek format date
 *
 * @param  string $date date
 * @param  string $format date format
 * @return string        string date formated
 */
function dateTimeWeek($date, $format = '%Y/%m/%d(%a)%R')
{
    if (empty($date)) {
        return "";
    }

    setlocale(LC_TIME, 'ja_JP.utf8');
    return strftime($format, strtotime($date));
}

/**
 * @param $email
 * @return bool
 */
function valid_email($email)
{
    return !!filter_var($email, FILTER_VALIDATE_EMAIL);
}

if (!function_exists('chgSearchValue')) {
    /**
     * @param $value
     * @return string
     */
    function chgSearchValue($value)
    {

        $val = mb_convert_kana($value, "aks", "UTF-8");

        $val = strtolower($val);

        $val = str_replace(' ', '', $val);

        return $val;
    }
}

/**
 * @param $name
 * @return string
 */
function getSortIcon($name)
{
    $orderBy = \Request::get('order_by');
    $sortBy = \Request::get('sort_by');

    if (is_array($orderBy) || !isset($orderBy) || $orderBy != $name) {
        return '';
    }
    return $sortBy == 'asc' ? trans('common.asc') : trans('common.desc');
}

/**
 * @param $name
 * @return array
 */
function getQueryOrder($name)
{
    $sort = [
        'order_by' => $name
    ];
    return \Request::get('order_by') != $name ?
        array_merge($sort, ['sort_by' => 'desc']) : array_merge($sort, ['sort_by' => \Request::get('sort_by') == 'desc' ? 'asc' : 'desc']);
}


if (!function_exists('createLogPathCyzen')) {
    /**
     * @param $name
     * @return string
     */
    function createLogPathCyzen($name)
    {
        return storage_path($name . '-' . date('Y-m-d') . '.log');
    }
}
