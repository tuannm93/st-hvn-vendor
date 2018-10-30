<?php

namespace App\Services;

use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\AdditionInfoRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Repositories\CommissionInfoRepositoryInterface;

class ReportService
{
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;
    /**
     * @var AdditionInfoRepositoryInterface
     */
    protected $additionInfoRepository;
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionRepository;
    /**
     * ReportService constructor.
     *
     * @param DemandInfoRepositoryInterface   $demandInfoRepository
     * @param AdditionInfoRepositoryInterface $additionInfoRepository
     * @param CommissionInfoRepositoryInterface $commissionRepository
     */
    public function __construct(
        DemandInfoRepositoryInterface $demandInfoRepository,
        AdditionInfoRepositoryInterface $additionInfoRepository,
        CommissionInfoRepositoryInterface $commissionRepository
    ) {
        $this->demandInfoRepository = $demandInfoRepository;
        $this->additionInfoRepository = $additionInfoRepository;
        $this->commissionRepository = $commissionRepository;
    }

    /**
     * @param $requestData
     * @return array
     */
    public function getUnSentList($requestData)
    {
        $results = [
            'dataSort' => [],
            'unsentList' => [],
        ];
        $results['dataSort'] = $dataSort = self::getDataSort($requestData);
        $orderBy = self::formatDataSortUnSentList($dataSort);
        $fields = [
            'demand_infos.id as demand_id',
            'm_corps.id as m_corp_id',
            'm_corps.corp_name as corp_name',
            'm_sites.site_name as site_name',
            'm_sites.id as site_id',
            'demand_infos.receive_datetime as receive_datetime',
        ];
        try {
            $results['unsentList'] = $this->demandInfoRepository->getDataUnSentList($fields, $orderBy);
            if ($dataSort['sort'] !== null) {
                if ($dataSort['order'] !== null) {
                    $results['unsentList'] = $results['unsentList']->appends(['sort' => $dataSort['sort'], 'order' => $dataSort['order']]);
                } else {
                    $results['unsentList'] = $results['unsentList']->appends(['sort' => $dataSort['sort']]);
                }
            }
            return $results;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return abort(500);
        }
    }

    /**
     * @param $requestData
     * @return array
     */
    public function getAdditionList($requestData)
    {
        $results = [
            'dataSort' => [],
            'additionItems' => [],
            'paginationAppends' => []
        ];
        if (isset($requestData['check_demand_flg'])) {
            $results['checkDemandFlg'] = true;
            $results['paginationAppends']['check_demand_flg'] = 'true';
        }
        $results['dataSort'] = $dataSort = self::getDataSort($requestData);
        if ($results['dataSort']['sort'] != null) {
            $results['paginationAppends']['sort'] = $results['dataSort']['sort'];
            $results['paginationAppends']['order'] = $results['dataSort']['order'];
        }
        $orderBy = self::formatDataSortAdditionList($dataSort);
        $conditions = self::getConditionsAdditionList($requestData);
        $fields = [
            'addition_infos.id as addition_id',
            'addition_infos.demand_id as demand_id',
            'addition_infos.demand_flg as demand_flg',
            'addition_infos.note as note',
            'addition_infos.construction_price_tax_exclude as construction_price_tax_exclude',
            'addition_infos.complete_date as complete_date',
            'addition_infos.customer_name as customer_name',
            'addition_infos.demand_type_update as demand_type_update',
            'addition_infos.memo as memo',
            'm_genres.genre_name as genre_name',
            'm_corps.id as corp_id',
            'm_corps.official_corp_name as official_corp_name'
        ];
        try {
            $results['additionItems'] = $this->additionInfoRepository->getReportAdditionList($fields, $orderBy, $conditions);
            return $results;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return abort(500);
        }
    }

    /**
     * @return array
     */
    public function getDataCSV()
    {
        $conditions = [['addition_infos.del_flg', '=', 0]] ;
        $fields = $this->additionInfoRepository->getBlankModel()::csvFieldList();
        $orderBy = ['addition_infos.id' => 'desc'];
        try {
            return $this->additionInfoRepository->getDataCSV($fields, $conditions, $orderBy);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return abort(500);
        }
    }

    /**
     * @return array
     */
    public function getFieldListExportCSV()
    {
        $fieldList = [
            'addition_infos_id' => trans('report_addition.csv.id'),
            'addition_infos_corp_id' => trans('report_addition.csv.corp_id'),
            'addition_infos_demand_id' => trans('report_addition.csv.demand_id'),
            'addition_infos_customer_name' => trans('report_addition.csv.customer_name'),
            'm_genres_genre_name' => trans('report_addition.csv.genre_name'),
            'addition_infos_demand_type_update_text' => trans('report_addition.csv.demand_type_update'),
            'addition_infos_construction_price_tax_exclude' => trans('report_addition.csv.construction_price_tax_exclude'),
            'addition_infos_complete_date' => trans('report_addition.csv.complete_date'),
            'addition_infos_note' => trans('report_addition.csv.note'),
            'addition_infos_falsity' => trans('report_addition.csv.falsity'),
            'addition_infos_demand' => trans('report_addition.csv.demand'),
            'addition_infos_memo' => trans('report_addition.csv.memo'),
            'addition_infos_created' => trans('report_addition.csv.created'),
            'addition_infos_created_user_id' => trans('report_addition.csv.created_user_id'),
            'addition_infos_modified' => trans('report_addition.csv.modified'),
            'addition_infos_modified_user_id' => trans('report_addition.csv.modified_user_id'),
        ];
        return $fieldList;
    }

    /**
     * @param $requestData
     * @return array
     */
    public function updateAdditionInfo($requestData)
    {
        $result = [
            'type' => 'success',
            'message' => trans('report_addition.update_success')
        ];
        if (count($requestData['item']) > 0) {
            foreach ($requestData['item'] as $item) {
                try {
                    $entity = $this->additionInfoRepository->find($item['id']);
                    if ($entity !== null) {
                        unset($item['id']);
                        $item['modified_user_id'] = \Auth::user()->user_id;
                        $item['modified'] = \Carbon\Carbon::now();
                        $this->additionInfoRepository->update($entity, $item);
                    }
                } catch (\Exception $exception) {
                    \Log::error($exception->getMessage());
                }
            }
        } else {
            $result['type'] = 'error';
            $result['message'] = trans('report_addition.update_error_no_data');
        }
        return $result;
    }
    /**
     * format detail sort jbr ongoin
     *
     * @param  array $data
     * @return array
     */
    public static function formatDetailSortJbrOngoing($data)
    {
        if (!isset($data['orderBy'])) {
            $detailSort['orderBy'] = 'demand_infos.contact_desired_time';
            $detailSort['sort'] = 'asc';
        } else {
            $detailSort['orderBy'] = $data['orderBy'];
            $detailSort['sort'] = $data['sort'];
        }
        return $detailSort;
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
     * @param $data
     * @return mixed
     */
    public static function getDataSort($data)
    {
        $dataSort['sort'] = null;
        $dataSort['order'] = null;
        if (!empty($data['sort'])) {
            $dataSort['sort'] = $data['sort'];
        }
        if (!empty($data['order'])) {
            $dataSort['order'] = $data['order'];
        }
        return $dataSort;
    }

    /**
     * @param $data
     * @return array
     */
    public static function formatDataSortUnSentList($data)
    {
        $sort = isset($data['sort']) ? $data['sort'] : null;
        $orderType = isset($data['order']) ? $data['order'] : 'asc';
        switch ($sort) {
            case 'demand_id':
                $order = ['demand_id' => $orderType];
                break;
            case 'corp_name':
                $order = ['corp_name' => $orderType];
                break;
            case 'receive_datetime':
                $order = ['receive_datetime' => $orderType];
                break;
            case 'detect_contact_desired_time':
                $order = ['detect_contact_desired_time' => $orderType];
                break;
            case 'site_id':
                $order = ['site_id' => $orderType];
                break;
            default:
                $order = [
                'demand_infos.contact_desired_time' => $orderType,
                ];
                break;
        }
        return $order;
    }

    /**
     * @param $data
     * @return array
     */
    public static function formatDataSortAdditionList($data)
    {
        $sort = isset($data['sort']) ? $data['sort'] : null;
        $orderType = isset($data['order']) ? $data['order'] : 'desc';
        switch ($sort) {
            case 'official_corp_name':
                $order = ['official_corp_name' => $orderType];
                break;
            default:
                $order = [
                'addition_infos.id' => $orderType,
                ];
                break;
        }
        return $order;
    }

    /**
     * @param $requestData
     * @return array
     */
    public static function getConditionsAdditionList($requestData)
    {
        $conditions = [];
        $conditions[] = ['addition_infos.del_flg', '=', 0];
        if (!isset($requestData['check_demand_flg'])) {
            $conditions[] = ['addition_infos.demand_flg', '=', 0];
        }
        return $conditions;
    }

    /**
     * order and paginate list jbr
     * @param $listJbr
     * @param $order
     * @param $nameColumn
     * @return mixed
     */
    public function orderList($listJbr, $order, $nameColumn)
    {
        $order = !empty($order) ? $order : 'asc';
        $nameColumn = !empty($nameColumn) ? $nameColumn : 'commission_infos.follow_date';

        return $listJbr->orderBy($nameColumn, $order)->paginate(\Config::get('datacustom.report_number_row'));
    }

    /**
     * get list jbr receipt follow
     * @param null $followDateFrom
     * @param null $followDateTo
     * @param bool $isGetAll
     * @return mixed
     */
    public function getListJbrReceiptFollow($followDateFrom, $followDateTo, $isGetAll)
    {
        return $this->commissionRepository->getListJbrReceiptFollow($followDateFrom, $followDateTo, $isGetAll);
    }
}
