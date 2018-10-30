<?php

namespace App\Services;

use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Models\DemandInfo;
use Excel;
use App;
use Lang;
use App\Repositories\AuctionRankingRepoInterface;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AuctionSettingService
{
    /**
     * @var DemandInfoRepositoryInterface
     */
    public $demandRepo;

    /**
     * @var MGenresRepositoryInterface
     */
    public $genreRepo;

    /**
     * @var bool|\Illuminate\Config\Repository|mixed
     */
    public $pagination;

    /**
     * @var AuctionRankingRepoInterface
     */
    protected $auctionRankingRepo;

    /**
     * AuctionSettingService constructor.
     *
     * @param DemandInfoRepositoryInterface $demandRepo
     * @param MGenresRepositoryInterface $genreRepo
     * @param AuctionRankingRepoInterface $auctionRankingRepo
     */
    public function __construct(
        DemandInfoRepositoryInterface $demandRepo,
        MGenresRepositoryInterface $genreRepo,
        AuctionRankingRepoInterface $auctionRankingRepo
    ) {
        $this->demandRepo = $demandRepo;
        $this->genreRepo = $genreRepo;
        $this->pagination = config('constant.PAGINATION');
        $this->auctionRankingRepo = $auctionRankingRepo;
    }

    /**
     * get list year from start year to current year
     *
     * @return array years
     */
    public function getListYear()
    {
        $years = [];
        for ($i = DemandInfo::AU_DROP_DOWN_START_YEAR; $i <= date('Y'); $i++) {
            $years[$i] = $i . __('auction_settings.year_title');
        }

        return $years;
    }

    /**
     * get list month
     *
     * @return array months
     */
    public function getListMonth()
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $i . __('auction_settings.month_title');
        }

        return $months;
    }

    /**
     * Get list genres
     *
     * @author thaihv Thai.HoangVan@nashtechglobal.com
     * @param bool $validFlag
     * @param bool $useExclusionFlag
     * @return \Illuminate\Support\Collection
     */
    public function getDropListGenre($validFlag = false, $useExclusionFlag = false)
    {
        $conditions = [];

        if ($validFlag) {
            $conditions[] = ['valid_flg', '=', 1];
        }

        if ($useExclusionFlag) {
            $conditions[] = ['exclusion_flg', '=', 0];
        }

        $genres = $this->genreRepo->getListSelectBox($conditions);

        return $genres;
    }

    /**
     * Get list genres and counting demand info for each.
     *
     * @author thaihv Thai.HoangVan@nashtechglobal.com
     * @param  $genreId
     * @param  integer $auctioned
     * @param  integer $year
     * @param  integer $month
     * @return mixed
     */
    public function getGenresAndCountingDemandInfo($genreId, $auctioned = null, $year = null, $month = null)
    {
        $systemType = [];
        // if $month add selection system condition
        if ($month) {
            //only select 2 types of selection_system
            $systemType = [
                DemandInfo::SELECTION_TYPE['auction'],
                DemandInfo::SELECTION_TYPE['auto_auction']
            ];
        }

        //get counting data for each genre
        return $this->demandRepo->getDemandbyGenreId(
            $genreId,
            $auctioned,
            $year,
            $month,
            $systemType
        );
    }

    /**
     * Calculate demand info data.
     *
     * @author thaihv Thai.HoangVan@nashtechglobal.com
     * @param  array   $genreIds
     * @param  integer $year
     * @param  integer $month
     * @return array
     */
    public function buildDemandInfoRatingData($genreIds, $year, $month)
    {
        $result = [];

        $genres = $this->genreRepo->getGenresByIds($genreIds);
        foreach ($genres as $key => $genre) {
            // get all demand info data with year by genre ids
            $yearData = $this->getGenresAndCountingDemandInfo($genre->id, null, $year, null);

            // get all demand info data with year and auctioned by genre ids
            $yearFlowingData = $this->getGenresAndCountingDemandInfo(
                $genre->id,
                DemandInfo::DEMAND_INFO_AUCTIONED,
                $year,
                null
            );
            // get all demand info data with year and month by genre ids
            $monthData = $this->getGenresAndCountingDemandInfo($genre->id, null, $year, $month);
            // get all demand info data with year and month, auctioned by genre ids
            $monthFlowingData = $this->getGenresAndCountingDemandInfo(
                $genre->id,
                DemandInfo::DEMAND_INFO_AUCTIONED,
                $year,
                $month
            );
            // get japan state by current locale
            $jpStates = config('jpstate.kanji');
            if (!App::isLocale('jp')) {
                $jpStates = config('jpstate.romaji');
            }
            // calculate ranking
            for ($i = 1; $i <= 47; $i++) {
                $row = [
                    'genre_id' => $i,
                    'genre_name' => $genre->genre_name
                ];
                $row['prefecture_id'] = $i;
                $key = $i . '';
                if ($i < 10) {
                    $key = '0' . $i;
                }
                $row['prefecture_name'] = $jpStates[$key];
                $row['year_count'] = 0;
                $row['year_flowing_ratio'] = '0%';
                $row['month_count'] = 0;
                $row['month_flowing_ratio'] = '0%';
                $row = $this->formatYearData($yearData, $row, $i);
                $row = $this->formatYearFlowingData($yearFlowingData, $row);
                foreach ($monthData as $r1) {
                    $row['month_count'] = $i == $r1['address1'] ? $r1['auction_count'] : 0;
                }

                $row = $this->formatMonthFlowingData($monthFlowingData, $row);
                $result[] = $row;
            }
        }
        return $result;
    }

    /**
     * format year data
     * @param  array $yearData
     * @param  array $item
     * @param  integer $key
     * @return array
     */
    public function formatYearData($yearData, $item, $key)
    {
        foreach ($yearData as $r1) {
            if ($key == $r1['address1']) {
                $item['year_count'] = $r1['auction_count'];
            } else {
                $item['year_count'] = 0;
            }
        }
        return $item;
    }

    /**
     * format year flowing data
     * @param  array $yearFlowingData
     * @param  array $item
     * @return array
     */
    public function formatYearFlowingData($yearFlowingData, $item)
    {
        foreach ($yearFlowingData as $r2) {
            if ($item['year_count'] != 0) {
                $item['year_flowing_ratio'] = floor($r2['auction_count'] / $item['year_count'] * 100). '%';
            } else {
                $item['year_flowing_ratio'] = '0%';
            }
        }
        return $item;
    }

    /**
     * format month flowing data
     * @param  array $monthFlowingData
     * @param  array $item
     * @return array
     */
    public function formatMonthFlowingData($monthFlowingData, $item)
    {
        foreach ($monthFlowingData as $r2) {
            if ($item['month_count'] != 0) {
                $item['month_flowing_ratio'] = floor($r2['auction_count'] / $item['month_count'] * 100). '%';
            } else {
                $item['month_flowing_ratio'] = '0%';
            }
        }
        return $item;
    }

    /**
     * Export excel
     *
     * @author thaihv Thai.HoangVan@nashtechglobal.com
     * @param  array $data
     * @return string location file
     */
    public function exportExcel($data)
    {
        $csvData = [];
        $columKeys = array_keys(DemandInfo::CSV_AUCTION_SETTING_HEADER);
        $headerValues = array_values(DemandInfo::CSV_AUCTION_SETTING_HEADER);
        //set first row data
        // loop end get value same key with columkey
        foreach ($data as $row) {
            $rData = [];
            foreach ($columKeys as $k) {
                $rData[$k] = '';
                if (isset($row[$k])) {
                    $rData[$k] = $row[$k];
                }
            }
            $tmpData = array_combine($headerValues, array_values($rData));
            $csvData[] = $tmpData;
        }
        if (empty($csvData)) {
            $csvData = $headerValues;
        }
        $fileName = 'auction_flowing_' . date('YmdHis');
        // process export
        return Excel::create(
            $fileName,
            function ($excel) use ($csvData, $fileName) {

                $excel->sheet(
                    $fileName,
                    function ($sheet) use ($csvData) {
                        $sheet->fromArray($csvData);
                    }
                );
            }
        );
    }

    /**
     * @author ducnguyent3 Duc.NguyenTai@nashtechglobal.com
     * @return array
     */
    public function getSelectedGenresData()
    {
        $list = $this->genreRepo->getListBySelectType();
        return $list;
    }

    /**
     * format detail sort auction setting follow
     *
     * @param  array $data
     * @return array
     */
    public function fomartDetailSortFollow($data)
    {
        if (!isset($data['orderBy'])) {
            $detailSort['orderBy'] = 'demand_infos.auction_start_time';
            $detailSort['orderByDisplay'] = 'demand_infos.auction_start_time';
            $detailSort['sort'] = 'asc';
        } else {
            $detailSort['orderBy'] = $data['orderBy'];
            $detailSort['orderByDisplay'] = $data['orderBy'];
            if ($data['orderBy'] == 'demand_infos.follow_tel_date') {
                $detailSort['orderBy'] = 'demand_infos.auction_start_time';
                $detailSort['orderByDisplay'] = 'demand_infos.follow_tel_date';
            }
            $detailSort['sort'] = $data['sort'];
        }
        return $detailSort;
    }

    /**
     * get spare tiem from follow data
     *
     * @param  array object $followData
     * @return integer
     */
    public function getSpareTimeFromFollowData($followData)
    {
        $spareTime = null;
        foreach ($followData as $item) {
            if ($item->item_detail == 'spare_time') {
                $spareTime = $item->item_hour_date;
            }
        }
        return $spareTime;
    }

    /**
     * get div value
     *
     * @param  string $code
     * @param  string $text
     * @return string
     */
    public static function getDivValue($code, $text)
    {
        $data = array_flip(config('rits.' . $code));
        return @$data[$text];
    }

    /**
     * format results auction setting follow
     *
     * @param  array object $results
     * @param  array object $followData
     * @return array object
     */
    public function formatResultAuctionSettingFollow($results, $followData)
    {
        $followData = $this->formatFollowData($followData);
        foreach ($results as &$item) {
            $item->follow_tel_date = dateFormat($this->getFollowTimeWithData($item->auction_start_time, $item->visit_time, $followData), 'Y/m/d H:i');
            $item->visit_time = getVisitTime($item);
        }
        return $results;
    }

    /**
     * format follow data
     *
     * @param  array $followData
     * @return array
     */
    private function formatFollowData($followData)
    {
        $spareTime          = null;
        $beforeVisit        = null;
        $followFrom         = null;
        $followTo           = null;
        $beforeDayFirstHalf = null;
        $beforeDay          = null;
        foreach ($followData as $row) {
            $itemHourDate = $row->item_hour_date;
            switch ($row->item_detail) {
                case 'spare_time':
                    $spareTime = $itemHourDate;
                    break;
                case 'before_visit':
                    $beforeVisit = $itemHourDate;
                    break;
                case 'follow_from':
                    $followFrom = $itemHourDate;
                    break;
                case 'follow_to':
                    $followTo = $itemHourDate;
                    break;
                case 'before_day_first_half':
                    $beforeDayFirstHalf = $itemHourDate;
                    break;
                case 'before_day':
                    $beforeDay = $itemHourDate;
                    break;
                default:
                    break;
            }
        }
        return [
            'spare_time'            => $spareTime,
            'before_visit'          => $beforeVisit,
            'follow_from'           => $followFrom,
            'follow_to'             => $followTo,
            'before_day_first_half' => $beforeDayFirstHalf,
            'before_day'            => $beforeDay,
        ];
    }

    /**
     * * get follow time with follow data
     *
     * @param $auctionStartTime
     * @param $visitTime
     * @param $followData
     * @return false|null|string
     */
    public function getFollowTimeWithData($auctionStartTime, $visitTime, $followData)
    {
        $diff = abs(strtotime($visitTime) - strtotime($auctionStartTime));
        $hours = floor($diff / 60 / 60);
        if ($followData['spare_time'] > $hours) {
            return null;
        }
        $followDate = date('Y-m-d H:i', strtotime($visitTime . " - " . $followData['before_visit'] . " hour"));
        $followStartDate = dateFormat($followDate) . ' ' . $followData['follow_from'] . ':00';
        $followEndDate = dateFormat($followDate) . ' ' . $followData['follow_to'] . ':00';
        $checkDate = dateFormat($followDate) . ' ' . '23:59';
        if (strtotime($followStartDate) <= strtotime($followDate) && strtotime($followDate) <= strtotime($checkDate)) {
            $followDate = date('Y-m-d', strtotime($visitTime . " - 1 day"));
            $followDate = $followDate . ' ' . $followData['before_day_first_half'] . ':00';
            return $followDate;
        }
        if (strtotime($followStartDate) > strtotime($followEndDate)) {
            $followStartDate = date('Y-m-d H:i', strtotime($followStartDate . " - 1 day"));
        }
        if (strtotime($followStartDate) <= strtotime($followDate) && strtotime($followDate) <= strtotime($followEndDate)) {
            $followDate = date('Y-m-d', strtotime($followDate . " - 1 day"));
            $followDate = $followDate . ' ' . $followData['before_day'] . ':00';
        }
        return $followDate;
    }

    /**
     * get order by sort item
     *
     * @param  string $sort
     * @param  string $order
     * @param  string $sortValue
     * @return array|string
     */
    public static function getInforOrderSort($sort, $order, $sortValue)
    {
        $isActive = false;
        $isAsc = false;
        if ($sort == $sortValue) {
            $isActive = true;
            if ($order == 'asc') {
                $isAsc = true;
            }
        }
        return [
            'is_active' => $isActive,
            'is_asc' => $isAsc
        ];
    }

    /**
     * get array list item sort follow
     *
     * @return array
     */
    public function getArrayListItemSortFollow()
    {
        return [
            [
                'text' => Lang::get('auction_settings.proposal_number'),
                'value' => 'demand_infos.id',
            ],
            [
                'text' => Lang::get('auction_settings.customer_name'),
                'value' => 'demand_infos.customer_name',
            ],
            [
                'text' => Lang::get('auction_settings.follow_up_date_time'),
                'value' => 'demand_infos.follow_tel_date',
            ],
            [
                'text' => Lang::get('auction_settings.visit_date_time'),
                'value' => 'visit_time_view_sort.visit_time',
            ],
            [
                'text' => Lang::get('auction_settings.site_name'),
                'value' => 'demand_infos.site_id',
            ],
            [
                'text' => Lang::get('auction_settings.category'),
                'value' => 'demand_infos.category_id',
            ],
            [
                'text' => Lang::get('auction_settings.contact'),
                'value' => 'demand_infos.address1',
            ],
            [
                'text' => Lang::get('auction_settings.supplier'),
                'value' => 'm_corps.corp_name',
            ],
        ];
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getSearchAuctionRanking($data)
    {
        $convertDay = $this->convertDaySearchAuctionRanking($data);
        return $this->auctionRankingRepo->getDataPaginateAuctionRanking($data['aggregate_date'], $convertDay);
    }

    /**
     * @param $dataRequest
     * @return bool|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadCSVAuctionRanking($dataRequest)
    {
        try {
            $dataCSV = $this->getDataCSVForAuctionRanking($dataRequest);
            $fileName = 'auction_ranking_' . str_replace([' ', '/', ':'], '', date('Y/m/d H:i:s'));
            $fieldList = [
                'MCorp__official_corp_name' => trans('auction_settings.business_name'),
                'CommissionInfo_corp_id' => trans('auction_settings.business_id'),
                'CommissionInfo__ranking' => trans('auction_settings.times'),
            ];
            $exportService = new ExportService();
            return $exportService->exportCsv($fileName, $fieldList, $dataCSV);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    private function getDataCSVForAuctionRanking($data)
    {
        $convertDay = $this->convertDaySearchAuctionRanking($data);
        return $this->auctionRankingRepo->getDataCSVAuctionRanking($data['aggregate_date'], $convertDay);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getCountSearchAuctionRanking($data)
    {
        $convertDay = $this->convertDaySearchAuctionRanking($data);
        return $this->auctionRankingRepo->getCountAuctionRanking($data['aggregate_date'], $convertDay);
    }

    /**
     * @param $data
     * @return false|string
     */
    public function convertDaySearchAuctionRanking($data)
    {
        if (!empty(strtotime($data['aggregate_date']))) {
            $aggregateDate = Carbon::parse($data['aggregate_date'])->format('Y-m-d');
        } else {
            $aggregateDate = Carbon::parse(Carbon::now())->format('Y-m-d');
        }
        switch ($data['aggregate_period']) {
            case 'day':
                return date('Y-m-d', strtotime($aggregateDate. ' - 1 day'));
                break;
            case 'week':
                return date('Y-m-d', strtotime($aggregateDate. ' - 7 day'));
                break;
            case 'month':
                return date('Y-m-d', strtotime($aggregateDate. ' - 30 day'));
                break;
        }
    }

    /**
     * get genre name by id
     * @param  integer $genreId
     * @return string|array
     */
    public function getMGenreNameById($genreId)
    {
        return $this->genreRepo->getNameById($genreId);
    }

    /**
     * get auction setting follow
     *
     * @param  array   $detailSort
     * @param  integer $spareTime
     * @return array
     */
    public function getAuctionSettingFollow($detailSort, $spareTime, $followData)
    {
        $results = $this->demandRepo->getAuctionSettingFollow($detailSort, $spareTime);
        return $this->formatResultAuctionSettingFollow($results, $followData);
    }
}
