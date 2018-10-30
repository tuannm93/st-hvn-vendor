<?php

namespace App\Services;

use App\Models\ProgAddDemandInfoTmp;
use App\Models\ProgDemandInfoOtherTmp;
use App\Models\ProgDemandInfoTmp;
use App\Repositories\Eloquent\ProgImportFilesRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProgressManagementAffService
{
    /**
     * @var ProgDemandInfoTmp
     */
    public $progDemandInfoTmp;
    /**
     * @var ProgAddDemandInfoTmp
     */
    public $progAddDemandInfoTmp;
    /**
     * @var ProgDemandInfoOtherTmp
     */
    public $progDemandInfoOtherTmp;
    /**
     * @var ProgImportFilesRepository
     */
    protected $progImportRepo;

    /**
     * ProgressManagementAffService constructor.
     * @param ProgDemandInfoTmp $progDemandInfoTmp
     * @param ProgAddDemandInfoTmp $progAddDemandInfoTmp
     * @param ProgDemandInfoOtherTmp $progDemandInfoOtherTmp
     * @param ProgImportFilesRepository $progImportRepo
     */
    public function __construct(
        ProgDemandInfoTmp $progDemandInfoTmp,
        ProgAddDemandInfoTmp $progAddDemandInfoTmp,
        ProgDemandInfoOtherTmp $progDemandInfoOtherTmp,
        ProgImportFilesRepository $progImportRepo
    ) {
        $this->progDemandInfoTmp = $progDemandInfoTmp;
        $this->progAddDemandInfoTmp = $progAddDemandInfoTmp;
        $this->progDemandInfoOtherTmp = $progDemandInfoOtherTmp;
        $this->progImportRepo = $progImportRepo;
    }
    /**
     * @param $progCorpId
     * @param null $progDemandIds
     */
    public function deleteProgDemandInfoTmp($progCorpId, $progDemandIds = null)
    {
        try {
            $this->progDemandInfoTmp->where('prog_corp_id', $progCorpId)
                ->whereIn('prog_demand_info_id', $progDemandIds)
                ->delete();
        } catch (Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * @param $data
     */
    public function createProgDemandInfoTmp($data)
    {
        try {
            $this->progDemandInfoTmp->insert($data);
        } catch (Exception $exception) {
            Log::info($exception);
        }
    }

    /**
     * @param null $progCorpId
     */
    public function deleteProgAddDemandInfoTmp($progCorpId = null)
    {
        try {
            $this->progAddDemandInfoTmp->where('prog_corp_id', $progCorpId)->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @param $data
     */
    public function createProgAddDemandInfoTmp($data)
    {
        try {
            $this->progAddDemandInfoTmp->insert($data);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @param null $progCorpId
     */
    public function deleteProgDemandInfoOtherTmp($progCorpId = null)
    {
        try {
            $this->progDemandInfoOtherTmp->where('prog_corp_id', $progCorpId)->delete();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @param $data
     * @return array|null
     */
    public function getDataProgDemandInfoUpdate($data)
    {
        $dataUpdate = [];
        $flag = false;
        if (isset($data['commission_status_update'])
            && isset($data['complete_date_update'])
            && isset($data['construction_price_tax_exclude_update'])
            && isset($data['commission_order_fail_reason_update'])
        ) {
            $flag = true;
            $dataUpdate['diff_flg'] = $data['diff_flg'];
            $dataUpdate['commission_status_update'] = $data['commission_status_update'];
            $dataUpdate['complete_date_update'] = $data['complete_date_update'];
            $dataUpdate['commission_order_fail_reason_update'] = $data['commission_order_fail_reason_update'];
            $dataUpdate['comment_update'] = $data['comment_update'];
        }

        return $flag ? $dataUpdate : null;
    }

    /**
     * @param $data
     * @param $progCorpId
     */
    private function handleProgDemandInfoTmp($data, $progCorpId)
    {
        if (!empty($data['ProgDemandInfo'])) {
            $progDemandIds = [];
            foreach ($data['ProgDemandInfo'] as $key => $item) {
                $progDemandIds[] = $item['id'];
            }
            $this->deleteProgDemandInfoTmp($progCorpId, $progDemandIds);

            $saveData = [];

            foreach ($data['ProgDemandInfo'] as $key => $item) {
                $saveData[$key]['prog_corp_id'] = $progCorpId;

                if (!empty($item['id'])) {
                    $saveData[$key]['demand_id'] = $item['demand_id'];
                    $saveData[$key]['commission_id'] = $item['commission_id'];
                    $saveData[$key]['receive_datetime'] = $item['receive_datetime'];
                    $saveData[$key]['customer_name'] = $item['customer_name'];
                    $saveData[$key]['category_name'] = $item['category_name'];
                    $saveData[$key]['complete_date'] = $item['complete_date'];
                    $saveData[$key]['fee_target_price'] = $item['fee_target_price'];
                    $saveData[$key]['construction_price_tax_exclude'] =  $item['construction_price_tax_exclude'];
                    $saveData[$key]['construction_price_tax_include'] =  $item['construction_price_tax_include'];
                    $saveData[$key]['commission_status'] = $item['commission_status'];
                    $saveData[$key]['fee'] = $item['fee'];
                    $saveData[$key]['fee_rate'] = $item['fee_rate'];
                    $saveData[$key]['diff_flg'] = $item['diff_flg'];
                    $saveData[$key]['comment_update'] = $item['comment_update'];
                    $saveData[$key]['commission_status_update'] = isset($item['commission_status_update'])? $item['commission_status_update']: null;
                    $saveData[$key]['complete_date_update'] = isset($item['complete_date_update'])? $item['complete_date_update']: null;
                    $saveData[$key]['construction_price_tax_exclude_update'] = isset($item['construction_price_tax_exclude_update'])? $item['construction_price_tax_exclude_update']: null;
                    $saveData[$key]['commission_order_fail_reason_update'] = isset($item['commission_order_fail_reason_update'])? $item['commission_order_fail_reason_update']: null;
                    $saveData[$key]['prog_demand_info_id'] = $item['id'];
                    $saveData[$key]['created_user_id'] = Auth::user()->user_id;
                    $saveData[$key]['modified_user_id'] = Auth::user()->user_id;
                    $saveData[$key]['created'] = date("Y/m/d H:i:s", time());
                    $saveData[$key]['modified'] = date("Y/m/d H:i:s", time());
                }
                unset($saveData[$key]['id']);
            }
            $this->createProgDemandInfoTmp($saveData);
        }
    }

    /**
     * @param $data
     * @param $progCorpId
     * @return array
     */
    private function dataSaveProgAddDemandInfoTmp($data, $progCorpId)
    {
        $saveAddData = [];
        foreach ($data['ProgAddDemandInfo'] as $key => $item) {
            $saveAddData[$key] = $this->checkData($item);
            $saveAddData[$key]['prog_corp_id'] = $progCorpId;
            $saveAddData[$key]['commission_status_update'] = isset($item['commission_status_update']) ? (intval($item['commission_status_update']) != 0 ? $item['commission_status_update'] : null) : null ;
            $saveAddData[$key]['construction_price_tax_exclude_update'] = isset($item['construction_price_tax_exclude_update']) ? $item['construction_price_tax_exclude_update']: null ;
        }
        return $saveAddData;
    }

    /**
     * @param $item
     * @return array
     */
    private function checkData($item)
    {
        $result = [];

        $result['sequence'] = isset($item['sequence']) ? $item['sequence']: null;
        $result['display'] = isset($item['display']) ? $item['display']: null ;
        $result['demand_id_update'] = isset($item['demand_id_update']) ? $item['demand_id_update']: null ;
        $result['customer_name_update'] = isset($item['customer_name_update']) ? $item['customer_name_update']: null ;
        $result['category_name_update'] = isset($item['category_name_update']) ? $item['category_name_update']: null ;
        $result['complete_date_update'] = isset($item['complete_date_update']) ? $item['complete_date_update']: null ;
        $result['demand_type_update'] = isset($item['demand_type_update']) ? $item['demand_type_update']: null;
        $result['comment_update'] = isset($item['comment_update']) ? $item['comment_update']: null ;
        $result['created_user_id'] = Auth::user()->user_id;
        $result['modified_user_id'] = Auth::user()->user_id;
        $result['created'] = date("Y/m/d H:i:s", time());
        $result['modified'] = date("Y/m/d H:i:s", time());

        return $result;
    }

    /**
     * @param $data
     * @param $progCorpId
     */
    private function handleProgAddDemandInfoTmp($data, $progCorpId)
    {
        if (isset($data['ProgDemandInfoOther']['add_flg'])) {
            $this->deleteProgAddDemandInfoTmp($progCorpId);
        }
        if (!empty($data['ProgAddDemandInfo'])) {
            $saveAddData = $this->dataSaveProgAddDemandInfoTmp($data, $progCorpId);
            $this->createProgAddDemandInfoTmp($saveAddData);
        }
    }

    /**
     * @param $data
     * @param $progCorpId
     */
    private function handleProgDemandInfoOtherTmp($data, $progCorpId)
    {
        if (!empty($data['ProgImportFile']) && !empty($data['ProgDemandInfoOther'])) {
            $this->deleteProgDemandInfoOtherTmp($progCorpId);
            $newProgDemandInfoOtherTmp = $this->progDemandInfoOtherTmp;
            $newProgDemandInfoOtherTmp->prog_corp_id = $progCorpId;
            if (!empty($data['ProgImportFile']['file_id'])) {
                $newProgDemandInfoOtherTmp->prog_import_file_id = $data['ProgImportFile']['file_id'];
            }
            if (!empty($data['ProgDemandInfoOther']['add_flg'])) {
                $newProgDemandInfoOtherTmp->add_flg = $data['ProgDemandInfoOther']['add_flg'];
            }
            if (!empty($data['ProgDemandInfoOther']['agree_flag'])) {
                $newProgDemandInfoOtherTmp->agree_flag = $data['ProgDemandInfoOther']['agree_flag'];
            }
            $newProgDemandInfoOtherTmp->created_user_id  = Auth::user()->user_id;
            $newProgDemandInfoOtherTmp->modified_user_id = Auth::user()->user_id;
            $newProgDemandInfoOtherTmp->created = date("Y/m/d H:i:s", time());
            $newProgDemandInfoOtherTmp->modified = date("Y/m/d H:i:s", time());
            $newProgDemandInfoOtherTmp->save();
        }
    }

    /**
     * @param $data
     * @param $progCorpId
     */
    public function setTmp($data, $progCorpId)
    {
        try {
            $this->handleProgDemandInfoTmp($data, $progCorpId);
            $this->handleProgAddDemandInfoTmp($data, $progCorpId);
            $this->handleProgDemandInfoOtherTmp($data, $progCorpId);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * @param $progData
     * @param $progTmp
     * @return mixed
     */
    public function parseProgData($progData, $progTmp)
    {
        foreach ($progData['ProgDemandInfo'] as $key => $val) {
            foreach ($progTmp as $tmp) {
                if ($tmp['prog_demand_info_id'] == $val['id']) {
                    $progData['ProgDemandInfo'][$key] = $tmp;
                    break;
                }
            }
        }
        return $progData;
    }

    public function getProgImportFile($id){
        return $this->progImportRepo->findById($id);
    }
}
