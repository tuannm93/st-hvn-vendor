<?php

namespace App\Services;

class ReportJbrCommissionService extends BaseService
{
    /**
     * remove request null
     * @param $filter
     * @return array
     */
    public function filterRequestIsNull($filter)
    {
        return array_filter(
            $filter,
            function ($data) {
                return $data != '' || $data != null;
            }
        );
    }

    /**
     * format datetime
     * @param $date
     * @param string $format
     * @return false|string
     */
    public static function dateTimeFormat($date, $format = 'Y/m/d H:i')
    {
        if (empty($date)) {
            return "";
        }


        try {
            new \DateTime($date);
        } catch (Exception $e) {
            return $date;
        }

        $error = \DateTime::getLastErrors();

        if ($error['warning_count'] != 0 || $error['error_count'] != 0) {
            return $date;
        }

        $createdDate = date_create($date);

        return date_format($createdDate, $format);
    }

    /**
     * create csv data
     * @param $data
     * @param $header
     * @return array
     */
    public function setCsvData($data, $header)
    {
        $csvData = [];
        foreach ($data as $value) {
            $tmp = [
                'demand_id' => $value['demand_id'],
                'official_corp_name' => $value['official_corp_name'],
                'genre_name' => $value['genre_name'],
                'commission_id' => $value['commission_id'],
                'jbr_order_no' => $value['jbr_order_no'],
                'customer_name' => $value['customer_name'],
                'complete_date' => $value['complete_date'],
                'construction_price_tax_include' => $value['construction_price_tax_include'],
                'MItem_item_name' => $value['MItem_item_name'],
                'MItem2_item_name' => $value['MItem2_item_name'],
            ];
            $tmp = array_combine($header, array_values($tmp));
            $csvData[] = $tmp;
        }
        return $csvData;
    }
}
