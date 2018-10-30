<?php

namespace App\Services\GeneralSearch;

use App\Models\MUser;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MGeneralSearchRepositoryInterface;
use App\Repositories\GeneralSearchConditionRepositoryInterface;
use App\Repositories\GeneralSearchItemRepositoryInterface;
use App\Repositories\PgDescriptionRepositoryInterface;
use DB;

class GeneralSearchService extends BaseGeneralSearchService
{
    const FUNCTION_LIST = [
        null => '--機能名を選択して下さい--',
        0 => '案件管理',
        1 => '取次管理',
        2 => '請求管理',
        3 => '加盟店管理',
    ];

    const FUNCTION_CASE_MANAGEMENT = "0";

    const FUNCTION_AGENCY_MANAGEMENT = "1";

    const FUNCTION_CHARGE_MANAGEMENT = "2";

    const FUNCTION_MEMBER_MANAGEMENT = "3";

    const DEFAULT_SELECTED_ITEMS = ['demand_infos.id'];

    /**
     * @var array
     */
    protected $functionTables;

    /**
     * @var array
     */
    protected $replaceFunctionTableColumnName;

    /**
     * @var array
     */
    protected $transferReferenceTable;

    /**
     * @var array
     */
    protected $functionTablesJoinOrder;

    /**
     * @var array
     */
    protected $functionTablesJoinRule;

    /**
     * @var MUser
     */
    private $mUser;

    /**
     * @var MGeneralSearchRepositoryInterface
     */
    public $generalSearchRepo;
    /**
     * @var GeneralSearchConditionRepositoryInterface
     */
    public $generalSearchConditionRepo;

    /**
     * @var GeneralSearchItemRepositoryInterface
     */
    public $generalSearchItemRepo;

    /**
     * @var PgDescriptionRepositoryInterface
     */
    private $pgDescriptionRepo;
    /**
     * @var InitializeGeneralService
     */
    protected $initializeGeneralService;
    /**
     * @var InitializeItemService
     */
    protected $initializeItemService;
    /**
     * @var InitializeCommissionService
     */
    protected $initializeCommissionService;
    /**
     * @var InitializeCorpService
     */
    protected $initializeCorpService;
    //end variable communicate field table
    // get csv title field
    /**
     * @var array
     */
    public $csvFormat = ['default' => []];

    // end get csv title field


    /**
     * GeneralSearchService constructor.
     * @param MUser $mUser
     * @param MGeneralSearchRepositoryInterface $generalSearchRepo
     * @param GeneralSearchItemRepositoryInterface $generalSearchItemRepo
     * @param GeneralSearchConditionRepositoryInterface $generalSearchConditionRepo
     * @param PgDescriptionRepositoryInterface $pgDescriptionRepo
     * @param InitializeGeneralService $initializeGeneralService
     * @param InitializeItemService $initializeItemService
     * @param InitializeCommissionService $initializeCommissionService
     * @param InitializeCorpService $initializeCorpService
     */
    public function __construct(
        MUser $mUser,
        MGeneralSearchRepositoryInterface $generalSearchRepo,
        GeneralSearchItemRepositoryInterface $generalSearchItemRepo,
        GeneralSearchConditionRepositoryInterface $generalSearchConditionRepo,
        PgDescriptionRepositoryInterface $pgDescriptionRepo,
        InitializeGeneralService $initializeGeneralService,
        InitializeItemService $initializeItemService,
        InitializeCommissionService $initializeCommissionService,
        InitializeCorpService $initializeCorpService
    ) {
        $this->mUser = $mUser;
        $this->generalSearchRepo = $generalSearchRepo;
        $this->generalSearchConditionRepo = $generalSearchConditionRepo;
        $this->generalSearchItemRepo = $generalSearchItemRepo;
        $this->pgDescriptionRepo = $pgDescriptionRepo;
        $this->initializeGeneralService = $initializeGeneralService;
        $this->initializeItemService = $initializeItemService;
        $this->initializeCommissionService = $initializeCommissionService;
        $this->initializeCorpService = $initializeCorpService;
        //get value for variable follow field

        $this->initDataFields();
        $this->initDataForFunction();
        $this->initializeGeneralService->initData();
        $this->initializeItemService->initItemData();
        $this->initializeCommissionService->initCommissionData();
        $this->initializeCorpService->initCorpData();
    }

    /**
     * initDataForFunction
     */
    public function initDataForFunction()
    {
        $this->functionTables = $this->generateFieldsForFunctionTables();
        $this->replaceFunctionTableColumnName = $this->generateColumns();
        $this->transferReferenceTable = $this->generateReferenceTable();
        $this->functionTablesJoinOrder = $this->generateFieldsForOrder();
        $this->functionTablesJoinRule = $this->generateRules();
    }

    /**
     * @return mixed
     */
    public function getListSite()
    {
        return $this->initializeGeneralService->siteRepo->getList();
    }

    /**
     * @return array
     */
    public function getListUser()
    {
        return $this->mUser->dropDownUser();
    }

    /**
     * @return boolean
     */
    public function isEnabledDisplaySaveAndDel()
    {
        return !UserService::checkRole('affiliation');
    }

    /**
     * @return boolean
     */
    public function isEnabledDisplayBillInfo()
    {
        if (Auth::user()->auth == 'system' || Auth::user()->auth == 'accounting' || Auth::user()->auth == 'accounting_admin' || Auth::user()->auth == 'admin' || Auth::user()->auth == 'popular') {
            return true;
        }

        return false;
    }

    /**
     * @param integer $functionId
     * @return string
     */
    public function getFunctionColumnList($functionId)
    {
        $result = $this->findFunctionTableColumn($functionId);
        $lists = [];
        foreach ($result as $row) {
            $key = $row->table_name.".".$row->column_name;
            if (isset($this->replaceFunctionTableColumnName[$functionId][$key])) {
                $row->column_comment = $this->replaceFunctionTableColumnName[$functionId][$key];
            }
            if ($functionId == 1) {
                if ($row->table_name == 'visit_times' && $row->column_name == 'visit_time') {
                    $key = $key.'.'.$functionId;
                }
                if ($row->table_name == 'demand_infos' && $row->column_name == 'contact_desired_time') {
                    $key = $key.'.'.$functionId;
                }
            }
            $lists[] = "[\"".$key."\",\"".$row->column_comment."\"]";
        }

        return implode(',', $lists);
    }

    /**
     * @param string $functionName
     * @return array
     */
    private function findFunctionTableColumn($functionName)
    {
        $retColumns = [];
        foreach ($this->functionTables[$functionName] as $key => $value) {
            $retColumns = array_merge($retColumns, $this->findTableColumn($key, $value));
        }

        return $retColumns;
    }

    /**
     * @param string $tableName
     * @param array $columns
     * @return array
     */
    private function findTableColumn($tableName, $columns)
    {
        $result = [];
        foreach ($this->pgDescriptionRepo->getColumnAlias($tableName, $columns) as $elem) {
            $object = (object) $elem;
            array_push($result, $object);
        }

        return $result;
    }

    /**
     * @param integer $mGeneralId
     * @return string
     */
    public function searchGeneralSearch($mGeneralId)
    {
        $results = $this->generalSearchRepo->findGeneralSearch($mGeneralId);
        $results = array_shift($results);
        if (empty($results)) {
            return '';
        }
        $selectedItem = "";
        foreach ($results['gs_item'] as $gsItem) {
            if (strlen($selectedItem) > 0) {
                $selectedItem .= ",";
            }
            if ($gsItem['function_id'] == 1) {
                if ($gsItem['table_name'] == 'visit_times' && $gsItem['column_name'] == 'visit_time') {
                    $selectedItem .= '"'.$gsItem['table_name'].".".$gsItem['column_name'].'.1"';
                } elseif ($gsItem['table_name'] == 'demand_infos' && $gsItem['column_name'] == 'contact_desired_time') {
                    $selectedItem .= '"'.$gsItem['table_name'].".".$gsItem['column_name'].'.1"';
                } else {
                    $selectedItem .= '"'.$gsItem['table_name'].".".$gsItem['column_name'].'"';
                }
            } else {
                $selectedItem .= '"'.$gsItem['table_name'].".".$gsItem['column_name'].'"';
            }
        }

        return $selectedItem;
    }

    /**
     * @return string
     */
    public function getDefaultSelectedItem()
    {
        return '"'.implode('","', self::DEFAULT_SELECTED_ITEMS).'"';
    }

    /**
     * @param integer $mGeneralId
     * @return array|string
     */
    public function getDataGeneralSearch($mGeneralId)
    {
        $dataResults = [];
        $dataMGeneralSearchCo = [];
        $results = $this->generalSearchRepo->findGeneralSearch($mGeneralId);
        $results = array_shift($results);
        if (empty($results)) {
            return '';
        }
        $dataResults += [
            'MGeneralSearch' => [
                'id' => $results['id'],
                'definition_name' => $results['definition_name'],
                'auth_popular' => $results['auth_popular'],
                'auth_admin' => $results['auth_admin'],
                'auth_accounting' => $results['auth_accounting'],
                'modified_user_id' => $results['modified_user_id'],
                'modified' => $results['modified'],
                'created_user_id' => $results['created_user_id'],
                'created' => $results['created'],
                'auth_accounting_admin' => $results['auth_accounting_admin'],
            ],
        ];
        $this->setMGeneralSearchCo($results, $dataMGeneralSearchCo);
        $dataResults += ['GeneralSearchCondition' => $dataMGeneralSearchCo];

        return $dataResults;
    }

    /**
     * @param array $results
     * @param array $dataMGeneralSearchCo
     * @return mixed
     */
    public function setMGeneralSearchCo($results, &$dataMGeneralSearchCo)
    {
        foreach ($results['gs_condition'] as $gsCondition) {
            switch ($gsCondition['condition_expression']) {
                case 0:
                case 1:
                    $dataMGeneralSearchCo[$gsCondition['condition_expression']][$gsCondition['table_name']."-".$gsCondition['column_name']] = $gsCondition['condition_value'];
                    break;
                case 2:
                case 3:
                case 4:
                    $dataMGeneralSearchCo[$gsCondition['condition_expression']][$gsCondition['table_name']."-".$gsCondition['column_name']] = explode('^', $gsCondition['condition_value']);
                    break;
                case 9:
                    if ($gsCondition['table_name']."-".$gsCondition['column_name'] == 'm_target_areas-jis_cd') {
                        $dataMGeneralSearchCo[$gsCondition['condition_expression']][$gsCondition['table_name']."-".$gsCondition['column_name']] = explode('^', $gsCondition['condition_value']);
                    } else {
                        $dataMGeneralSearchCo[$gsCondition['condition_expression']][$gsCondition['table_name']."-".$gsCondition['column_name']] = $gsCondition['condition_value'];
                    }
                    break;
            }
        }
    }


    /**
     * @param array $params
     * @return mixed|string
     * @throws \Exception
     */
    public function saveGeneralSearchAjax($params)
    {
        $data = $this->validateDataGeneralSearch($params);
        $id = "";
        if (isset($data['MGeneralSearch']['id'])) {
            $id = $data['MGeneralSearch']['id'];
            $this->generalSearchItemRepo->deleteById($id);
            $this->generalSearchConditionRepo->deleteById($id);
        }
        if (! empty($data['MGeneralSearch'])) {
            $this->generalSearchRepo->insertGeneralSearch($data['MGeneralSearch']);
            $id = $this->generalSearchRepo->getLastInsertID();
        }
        if (! empty($data['GeneralSearchItem'])) {
            $dataGeneralSearchItem = $this->addNewGenreId($data['GeneralSearchItem'], $id);
            $this->generalSearchItemRepo->insertGeneralSearch($dataGeneralSearchItem);
        }
        if (! empty($data['GeneralSearchCondition'])) {
            $dataGeneralSearchCondition = $this->addNewGenreId($data['GeneralSearchCondition'], $id);
            $this->generalSearchConditionRepo->insertGeneralSearch($dataGeneralSearchCondition);
        }
        if (strlen($id) === 0) {
            $id = $this->generalSearchRepo->getLastInsertID();
        }

        return $id;
    }


    /**
     * @param array $params
     * @return mixed|string
     * @throws \Exception
     */
    public function saveGeneralSearch($params)
    {
        $data = $this->validateDataGeneralSearch($params);
        $id = "";
        if (isset($data['MGeneralSearch']['id'])) {
            $id = $data['MGeneralSearch']['id'];
            $this->generalSearchItemRepo->deleteById($id);
            $this->generalSearchConditionRepo->deleteById($id);
        }
        if (! empty($data['MGeneralSearch'])) {
            $this->generalSearchRepo->updateGeneralSearch($data['MGeneralSearch']);
        }
        if (! empty($data['GeneralSearchItem'])) {
            $this->generalSearchItemRepo->insertGeneralSearch($data['GeneralSearchItem']);
        }
        if (! empty($data['GeneralSearchCondition'])) {
            $this->generalSearchConditionRepo->insertGeneralSearch($data['GeneralSearchCondition']);
        }
        if (strlen($id) === 0) {
            $id = $this->generalSearchRepo->getLastInsertID();
        }

        return $id;
    }

    /**
     * @param array $params
     * @param $id
     * @return mixed
     */
    private function addNewGenreId($params, $id)
    {
        for ($i = 0; $i < count($params); $i++) {
            $params[$i]['general_search_id'] = $id;
        }

        return $params;
    }

    /**
     * @param array $params
     * @return array
     * @throws \Exception
     */
    private function validateDataGeneralSearch($params)
    {
        $data = [];
        $data['MGeneralSearch'] = $this->validateDataMGeneralSearch($params);
        $data['GeneralSearchItem'] = $this->validateDataGeneralSearchItem($params);
        $data['GeneralSearchCondition'] = $this->validateDataGeneralSearchCondition($params);

        return $data;
    }

    /**
     * @param array $params
     * @return array
     */
    private function validateDataMGeneralSearch($params)
    {
        $data = [];
        if (strlen($params['MGeneralSearch']['id']) > 0) {
            $data['id'] = $params['MGeneralSearch']['id'];
        }
        $data['definition_name'] = (isset($params['MGeneralSearch']['definition_name'])) ? $params['MGeneralSearch']['definition_name'] : "";
        $data['auth_popular'] = (isset($params['MGeneralSearch']['auth_popular'])) ? 1 : 0;
        $data['auth_admin'] = (isset($params['MGeneralSearch']['auth_admin'])) ? 1 : 0;
        $data['auth_accounting_admin'] = (isset($params['MGeneralSearch']['auth_accounting_admin'])) ? 1 : 0;
        $data['auth_accounting'] = (isset($params['MGeneralSearch']['auth_accounting'])) ? 1 : 0;

        return $data;
    }

    /**
     * @param array $params
     * @return array
     * @throws \Exception
     */
    private function validateDataGeneralSearchItem($params)
    {
        $datas = [];

        if (! isset($params['GeneralSearchItem']['item'])) {
            throw new \Exception(trans('general_search.general_search_item_param_error'));
        }

        for ($i = 0; $i < count($params['GeneralSearchItem']['item']); $i++) {
            $elems = explode('.', $params['GeneralSearchItem']['item'][$i]);
            if (strlen($params['MGeneralSearch']['id']) > 0) {
                $data['general_search_id'] = $params['MGeneralSearch']['id'];
            }
            if (isset($elems[2])) {
                $data['function_id'] = $elems[2];
            } else {
                $data['function_id'] = $this->convertTableNameToFunctionId($elems[0]);
            }
            $data['table_name'] = $elems[0];
            $data['column_name'] = $elems[1];
            $data['output_order'] = $i;

            $datas[] = $data;
        }

        return $datas;
    }

    /**
     * @param string
     * @return integer|null|string
     */
    private function convertTableNameToFunctionId($tableName)
    {

        foreach ($this->functionTables as $k => $v) {
            if (array_key_exists($tableName, $v)) {
                return $k;
            }
        }

        return null;
    }

    /**
     * @param array $params
     * @return array
     */
    private function validateDataGeneralSearchCondition($params)
    {

        $datas = [];
        foreach ($params['GeneralSearchCondition'] as $conditionExpression => $val) {
            foreach ($val as $k => $v) {
                $e = $this->conditionExpression($conditionExpression, $v, $k);
                if (strlen($e) > 0) {
                    list($tableName, $columnName) = explode('-', $k);
                    if (strlen($params['MGeneralSearch']['id']) > 0) {
                        $data['general_search_id'] = $params['MGeneralSearch']['id'];
                    }
                    $data['table_name'] = $tableName;
                    $data['column_name'] = $columnName;
                    $data['condition_expression'] = $conditionExpression;
                    $data['condition_value'] = $e;
                    $data['condition_type'] = 0;

                    $datas[] = $data;
                }
            }
        }

        return $datas;
    }

    /**
     * @param integer $conditionExpression
     * @param mixed $value
     * @param string $key
     * @return float|mixed|string
     */
    public function conditionExpression($conditionExpression, $value, $key)
    {
        $elm = $value;
        switch ($conditionExpression) {
            case 2:
                $elm = $this->convertValue($value);
                break;
            case 3:
            case 4:
                $elm = (is_array($value)) ? implode('^', $value) : "";
                break;
            case 9:
                if ($key == 'm_target_areas-jis_cd') {
                    $elm = (is_array($value)) ? implode('^', $value) : "";
                }
                break;
        }

        return $elm;
    }

    /**
     * @param array $value
     * @return float
     */
    public function convertValue($value)
    {
        return ($value[0] != "" || $value[1] != "") ? implode('^', $value) : "";
    }

    /**
     * @param integer $generalId
     * @return array
     */
    public function getCsvPreview($generalId)
    {
        $conditions = $this->getCsvPreviewCondition($generalId);
        $tableHeaders = $this->getCsvPreviewHeader($generalId);
        $tableData = $this->getCsvPreviewData($generalId);

        return ['conditions' => $conditions, 'headers' => $tableHeaders, 'datas' => $tableData];
    }

    /**
     * @param integer $generalId
     * @return array
     */
    private function getCsvPreviewCondition($generalId)
    {
        $result = $this->generalSearchConditionRepo->findGeneralSearchCondition($generalId);
        $conditions = [];
        foreach ($result as $elem) {
            if ($elem['column_name'] == 'free_text') {
                $elem['table_name'] = 'm_corps';
                $elem['column_name'] = 'note';
            }
            $comments = $this->findTableColumn($elem['table_name'], [$elem['column_name']]);
            $title = $comments[0]->column_comment;
            $value = $elem['condition_value'];
            $key = $elem['table_name']."_".$elem['column_name'];
            if (array_key_exists($key, $this->transferReferenceTable)) {
                $values = explode('^', $value);
                $value = "";
                foreach ($values as $v) {
                    $serviceName = $this->transferReferenceTable[$key]['s'];
                    $varName = $this->transferReferenceTable[$key]['v'];
                    $this->setValue($value, $serviceName, $varName, $v);
                }
            }

            if ($value != "^") {
                $conditions[] = ['title' => $title, 'value' => $value];
            }
        }
        return $conditions;
    }

    /**
     * @param string $value
     * @param string $serviceName
     * @param string $varName
     * @param string $transferKey
     */
    public function setValue(&$value, $serviceName, $varName, $transferKey)
    {
        if ($serviceName == '' && is_array($this->{$varName}) && array_key_exists($transferKey, $this->{$varName})) {
            if (strlen($value) > 0) {
                $value .= "^";
            }
            $value .= $this->{$varName}[$transferKey];
        }
        if ($serviceName != '' && is_array($this->{$serviceName}->{$varName}) && array_key_exists($transferKey, $this->{$serviceName}->{$varName})) {
            if (strlen($value) > 0) {
                $value .= "^";
            }
            $value .= $this->{$serviceName}->{$varName}[$transferKey];
        }
    }
    /**
     * @param integer $generalId
     * @return array
     */
    private function getCsvPreviewHeader($generalId)
    {
        $result = $this->generalSearchItemRepo->findGeneralSearchCondition($generalId);
        $headers = [];
        foreach ($result as $elem) {
            $comments = $this->findTableColumn($elem['table_name'], [$elem['column_name']]);
            $key = $elem['table_name'].'.'.$elem['column_name'];
            if (isset($this->replaceFunctionTableColumnName[$elem['function_id']][$key])) {
                $comments[0]->column_comment = $this->replaceFunctionTableColumnName[$elem['function_id']][$key];
            }
            $headers[] = [$comments[0]->column_comment];
        }

        return $headers;
    }

    /**
     * @param integer $generalId
     * @return array
     */
    private function getCsvPreviewData($generalId)
    {
        return $this->findGeneralSearchToCsv($generalId, 100);
    }

    /**
     * @param integer $generalId
     * @param integer $limit
     * @return array
     */
    public function findGeneralSearchToCsv($generalId, $limit = 0)
    {
        $this->setCsvFormat($generalId);
        $sql = $this->buildQuery($generalId, $limit);
        $result = $this->generalSearchRepo->runQueryText($sql);
        $dataCSV = [];
        foreach ($result as $item) {
            $dataCSV[] = $this->getCsvDataRows($item, $limit);
        }

        return $dataCSV;
    }

    /**
     * @param array $item
     * @param integer $limit
     * @return array
     */
    public function getCsvDataRows($item, $limit)
    {
        $csvDataRows = [];
        $user = Auth::user()->auth;
        foreach ($item as $key => $value) {
            if (array_key_exists($key, $this->transferReferenceTable)) {
                $serviceName = $this->transferReferenceTable[$key]['s'];
                $varName = $this->transferReferenceTable[$key]['v'];
                if ($serviceName == '' && array_key_exists($value, $this->{$varName})) {
                    $value = $this->{$varName}[$value];
                }
                if ($serviceName != '' && array_key_exists($value, $this->{$serviceName}->{$varName})) {
                    $value = $this->{$serviceName}->{$varName}[$value];
                }
            }

            if ($this->isMaskingAll($limit, $value, $user)) {
                $value = maskingAll($value);
            }
            $csvDataRows[] = $value;
        }
        return $csvDataRows;
    }

    /**
     * @param integer $limit
     * @param string $key
     * @param object $user
     * @return boolean
     */
    public function isMaskingAll($limit, $key, $user)
    {
        if ($limit == 0) {
            if ($this->isAccount($user) && ($key == 'demand_infos_address3' || $key == 'demand_infos_customer_tel' || $key == 'demand_infos_customer_name' || $key == 'demand_infos_tel1' || $key == 'demand_infos_tel2')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param object $user
     * @return boolean
     */
    private function isAccount($user)
    {
        if ($user != 'system' && $user != 'admin' && $user != 'accounting_admin') {
            return true;
        }

        return false;
    }

    /**
     * @param integer $generalId
     */
    private function setCsvFormat($generalId)
    {
        $result = $this->generalSearchItemRepo->findGeneralSearchCondition($generalId);
        $this->csvFormat['default'] = [];
        foreach ($result as $elem) {
            $comments = $this->findTableColumn($elem['table_name'], [$elem['column_name']]);
            $key = $elem['table_name'].'.'.$elem['column_name'];
            if (isset($this->replaceFunctionTableColumnName[$elem['function_id']][$key])) {
                $comments[0]->column_comment = $this->replaceFunctionTableColumnName[$elem['function_id']][$key];
            }
            $this->csvFormat['default'][] = $comments[0]->column_comment;
        }
    }

    /**
     * @param integer $generalId
     * @param integer $limit
     * @return string
     */
    private function buildQuery($generalId, $limit = 0)
    {
        $result = $this->generalSearchRepo->findGeneralSearch($generalId);

        if (count($result) == 0) {
            return "";
        }

        $tableDeep = 0;

        $columns = [];
        foreach ($result[0]['gs_item'] as $item) {
            $columns[] = $this->buildColumn($item);
            $tableDeep = (array_search($item['table_name'], $this->functionTablesJoinOrder) > $tableDeep) ? array_search($item['table_name'], $this->functionTablesJoinOrder) : $tableDeep;
        }

        $wheres = ['1 = 1'];
        $tableDeep = $this->getWhereCondition($result, $tableDeep, $wheres);

        $fromTable = "demand_infos ";
        for ($i = 1; $i < $tableDeep + 1; $i++) {
            $fromTable .= " left join ".$this->functionTablesJoinOrder[$i]." ON ".$this->functionTablesJoinRule[$this->functionTablesJoinOrder[$i]]['cond'];
        }
        $wheres = array_filter($wheres, function ($var) {
            if ($var != '()') {
                return ! empty($var);
            }
        });
        $query = "select ".implode(',', $columns)." from ".$fromTable." where ".implode(' and ', $wheres).(($limit > 0) ? " limit ".$limit : "");

        return $query;
    }

    /**
     * @param array $result
     * @param integer $tableDeep
     * @param array $wheres
     * @return float
     */
    public function getWhereCondition($result, $tableDeep, &$wheres)
    {
        foreach ($result[0]['gs_condition'] as $cond) {
            switch ($cond['condition_expression']) {
                case 0:
                    $wheres[] = $cond['table_name'].".".$cond['column_name']." = ".DB::raw("'".$cond['condition_value']."'")."";
                    break;
                case 1:
                    $wheres[] = $cond['table_name'].".".$cond['column_name']." like '%".$cond['condition_value']."%'";
                    break;
                case 2:
                    $this->generateConditionForTwoCase($cond, $wheres);
                    break;
                case 3:
                    $wheres[] = $cond['table_name'].".".$cond['column_name']." in (".implode(',', explode('^', $cond['condition_value'])).")";
                    break;
                case 4:
                    $wheres[] = $cond['table_name'].".".$cond['column_name']." in ('".implode("','", explode('^', $cond['condition_value']))."')";
                    break;
                case 9:
                    $this->generateConditionForNineCase($cond, $wheres, $tableDeep);
                    break;
            }
            $tableDeep = (array_search($cond['table_name'], $this->functionTablesJoinOrder) > $tableDeep) ? array_search($cond['table_name'], $this->functionTablesJoinOrder) : $tableDeep;
        }

        return $tableDeep;
    }

    /**
     * @param array $cond
     * @param array $wheres
     * @param integer $tableDeep
     * @return mixed
     */
    public function generateConditionForNineCase($cond, &$wheres, &$tableDeep)
    {
        if ($cond['table_name'].".".$cond['column_name'] == 'demand_infos.customer_tel') {
            $wheres[] = '(demand_infos.customer_tel = \''.$cond['condition_value'].'\' or demand_infos.tel1 = \''.$cond['condition_value'].'\')';
        }
        if ($cond['table_name'].".".$cond['column_name'] == 'm_corps.tel1') {
            $wheres[] = '(m_corps.commission_dial = \''.$cond['condition_value'].'\' or m_corps.tel1 = \''.$cond['condition_value'].'\' or m_corps.tel2 = \''.$cond['condition_value'].'\')';
        }
        if ($cond['table_name'].".".$cond['column_name'] == 'm_corps-free_text') {
            $wheres[] = '(m_corps.note like \'%'.$cond['condition_value'].'%\' or affiliation_infos.attention like \'%'.$cond['condition_value'].'%\')';
            $tableDeep = ($tableDeep < array_search('affiliation_infos', $this->functionTablesJoinOrder)) ? array_search('affiliation_infos', $this->functionTablesJoinOrder) : $tableDeep;
        }
        if ($cond['table_name'].".".$cond['column_name'] == 'money_corresponds.nominee') {
            $wheres[] = "m_corps.id in (select corp_id from money_corresponds where nominee LIKE '%".$cond['condition_value']."%')";
        }
        if ($cond['table_name'].".".$cond['column_name'] == 'm_target_areas.jis_cd') {
            $wheres[] = "substring(m_target_areas.jis_cd, 1, 2)::integer in (".implode(',', explode('^', $cond['condition_value'])).")";
            $tableDeep = ($tableDeep < array_search('m_target_areas', $this->functionTablesJoinOrder)) ? array_search('m_target_areas', $this->functionTablesJoinOrder) : $tableDeep;
        }
    }

    /**
     * @param array $cond
     * @param array $wheres
     * @return mixed
     */
    public function generateConditionForTwoCase($cond, &$wheres)
    {
        $spVal = explode('^', $cond['condition_value']);
        $where = "";
        if (strlen($spVal[0]) > 0) {
            $where .= $cond['table_name'].".".$cond['column_name']." >= ".DB::raw("'".$spVal[0]."'")." ";
        }
        if (strlen($spVal[1]) > 0) {
            if (strlen($where) > 0) {
                $where .= " and ";
            }
            $where .= $cond['table_name'].".".$cond['column_name']." <= ".DB::raw("'".$spVal[1]."'")."";
        }
        $wheres[] = "(".$where.")";
    }

    /**
     * @param array $item
     * @return string
     */
    protected function buildColumn($item)
    {
        if ($item['table_name'] == 'demand_infos' && $item['column_name'] == 'commission_limitover_time') {
            $colData = <<< QUERY
                CASE WHEN demand_infos.commission_limitover_time > 0
                         THEN (demand_infos.commission_limitover_time / 60)::varchar || '時間' || (demand_infos.commission_limitover_time % 60)::varchar || '分'
                     ELSE NULL END
QUERY;
        } elseif ($item['table_name'] == 'affiliation_infos' && $item['column_name'] == 'default_tax') {
            $colData = <<<EOF
                CASE WHEN affiliation_infos.default_tax = false THEN '0'
                WHEN affiliation_infos.default_tax = true THEN '1'
                ELSE 'NULL' END
EOF;
        } elseif ($item['table_name'] == 'bill_infos' && $item['column_name'] == 'auction_id') {
            $colData = '(SELECT total_bill_price FROM bill_infos auction_bill_infos WHERE auction_bill_infos.commission_id = commission_infos.id AND auction_bill_infos.auction_id IS NOT NULL LIMIT 1)';
        } else {
            $colData = $item['table_name'].".".$item['column_name'];
        }

        return $colData." as ".$item['table_name']."_".$item['column_name'];
    }

    /**
     * @param integer $generalId
     */
    public function deleteGeneralSearchAll($generalId)
    {
        $this->generalSearchItemRepo->deleteById($generalId);
        $this->generalSearchConditionRepo->deleteById($generalId);
        $this->generalSearchRepo->deleteGeneralSearch($generalId);
    }
}
