<?php

namespace App\Helpers;

class Sanitize
{
    /**
     * @param $string
     * @param array  $options
     * @return string
     */
    public static function html($string, $options = [])
    {
        static $defaultCharset = false;
        if ($defaultCharset === false) {
            $defaultCharset = \Config::get('datacustom.defaultcharset');
            if ($defaultCharset === null) {
                $defaultCharset = 'UTF-8';
            }
        }
        $defaults = [
            'remove' => false,
            'charset' => $defaultCharset,
            'quotes' => ENT_QUOTES,
            'double' => true
        ];

        $options += $defaults;

        if ($options['remove']) {
            $string = strip_tags($string);
        }

        return htmlentities($string, $options['quotes'], $options['charset'], $options['double']);
    }

    /**
     * @param $string
     * @param string $connection
     * @return bool|string
     */
    public static function escape($string, $connection = 'default')
    {
        if (is_numeric($string) || $string === null || is_bool($string)) {
            return $string;
        }
        $db = ConnectionManager::getDataSource($connection);
        $string = $db->value($string, 'string');
        $start = 1;
        if ($string{0} === 'N') {
            $start = 2;
        }

        return substr(substr($string, $start), 0, -1);
    }
}
