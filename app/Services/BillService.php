<?php

namespace App\Services;

use App\Models\BillInfo;
use App\Repositories\BillRepositoryInterface;
use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\MItemRepositoryInterface;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class BillService
{
    /**
     * @var BillRepositoryInterface
     */
    protected $billInfo;
    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepo;
    /**
     * @var MItemService
     */
    protected $mItemService;

    /**
     * BillService constructor.
     *
     * @param BillRepositoryInterface $billRepositoryInterface
     * @param MItemRepositoryInterface $mItemRepo
     * @param MItemService $mItemService
     */
    public function __construct(
        BillRepositoryInterface $billRepositoryInterface,
        MItemRepositoryInterface $mItemRepo,
        MItemService $mItemService
    ) {
        $this->billInfo = $billRepositoryInterface;
        $this->mItemRepo = $mItemRepo;
        $this->mItemService = $mItemService;
    }

    /**
     * @param string $fromDate 'yyyy/MM/dd'
     * @param string $toDate 'yyyy/MM/dd'
     * @param boolean $checkFee
     * @return \Maatwebsite\Excel\LaravelExcelWriter
     */
    public function getDataForDownloadBill($fromDate, $toDate, $checkFee)
    {
        $data = $this->billInfo->getDataByCondition($fromDate, $toDate);
        if (is_array($data) && count($data) > 0) {
            $dataNew = $this->editCsvFile($data, $checkFee);
            $file = $this->exportExcel($dataNew);
            return $file;
        } else {
            return null;
        }
    }

    /**
     * edit CSV File
     *
     * @param  array $list
     * @param  boolean $checkFee
     * @return array
     */
    private function editCsvFile($list, $checkFee)
    {
        $listBillStatus = $this->mItemService->prepareDataList(
            $this->mItemRepo->getListByCategoryItem(MItemRepository::BILLING_STATUS)
        );
        $saveData = $this->compareDataForBillInfo($list, $listBillStatus);
        if ($checkFee) {
            if (!empty($saveData)) {
                foreach ($saveData as $obj) {
                    try {
                        $model = $this->billInfo->find($obj['id']);
                        if ($model) {
                            $model->bill_status = $obj['bill_status'];
                            $model->fee_billing_date = $obj['fee_billing_date'];
                        }
                        $model->save();
                    } catch (\Exception $ex) {
                    }
                }
            }
        }
        return $list;
    }

    /**
     * compare data list 4 download csv
     * @param $list
     * @param $listBillStatus
     * @return array
     */
    private function compareDataForBillInfo(&$list, $listBillStatus)
    {
        $saveData = [];
        foreach ($list as &$val) {
            $val['BillInfo__indivisual_billing'] = !empty($val['BillInfo__indivisual_billing'])
                ? __('bill.bill_key_maru') : __('bill.bill_key_batu');
            if (empty($val['BillInfo__fee_payment_balance'])) {
                $val['BillInfo__fee_payment_balance'] = $val['BillInfo__total_bill_price']
                    - $val['BillInfo__fee_payment_price'];
            }
            if ($val['BillInfo__bill_status'] == $this->getStatusConfig('rits.bill_status', 'not_issue')) {
                $saveData[] = [
                    'id' => $val['BillInfo__id'],
                    'bill_status' => $this->getStatusConfig('rits.bill_status', 'issue'),
                    'fee_billing_date' => date('Y/m/d')
                ];
            }
            $val['BillInfo__bill_status'] = !empty($val['BillInfo__bill_status'])
                ? $listBillStatus[$val['BillInfo__bill_status']] : '';
        }

        return $saveData;
    }

    /**
     * get status config from rits
     *
     * @param  $code
     * @param  $text
     * @return mixed
     */
    private function getStatusConfig($code, $text)
    {
        $listStatus = Config::get($code);
        $listDiv = array_flip($listStatus);
        return $listDiv[$text];
    }

    /**
     * export to file
     *
     * @param  $data
     * @return \Maatwebsite\Excel\LaravelExcelWriter
     */
    private function exportExcel($data)
    {
        $columKeys = array_keys(BillInfo::$csvFormat);
        $headerValues = array_values(BillInfo::$csvFormat);
        // loop end get value same key with columkey
        $csvData = [];
        foreach ($data as $row) {
            $rData = [];
            foreach ($columKeys as $k) {
                $rData[$k] = '';
                if (isset($row[$k])) {
                    $rData[$k] = $row[$k];
                }
            }
            //combine key values for csv
            $csvData[] = array_combine($headerValues, array_values($rData));
        }
        $fileName = __('bill.bill_information') . '_' . Auth::user()->user_id;
        // process export
        return Excel::create(
            $fileName,
            function ($excel) use ($csvData, $fileName) {
                $excel->sheet(
                    $fileName,
                    function ($sheet) use ($csvData) {
                        $sheet->fromArray($csvData, null, 'A1', true);
                    }
                );
            }
        );
    }

    /**
     * @param $billId
     * @param $modified
     * @return bool
     */
    public function checkBillModified($billId, $modified)
    {
        $billInfo = $this->billInfo->find($billId);
        if (!empty($billInfo)) {
            if ($billInfo['modified'] == $modified) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $billId
     * @return mixed
     */
    public function getDataBillDetail($billId)
    {
        return $this->billInfo->getData($billId);
    }

    /**
     * @return array
     */
    public function getMItemBillDetail()
    {
        return $this->mItemService->prepareDataList($this->mItemRepo->getListByCategoryItem(\Config::get('constant.MITEM.CATEGORY')));
    }

    /**
     * @param $billId
     * @param $data
     * @return array
     */
    public function updateBillDetail($billId, $data)
    {
        if ($this->billInfo->updateData($billId, $data)) {
            return [
                'message' => trans('bill.updated'),
                'class' => 'success'
            ];
        } else {
            return [
                'message' => trans('bill.input_error'),
                'class' => 'error'
            ];
        }
    }

    /**
     * @param null $id
     * @param array $data
     * @return array
     */
    public function getDataEditBillDetail($id = null, $data = [])
    {
        $data['id'] = $id;
        if (!isset($data ['bill_infos_bill_status'])) {
            if ($data ['bill_infos_fee_payment_balance'] == 0) {
                $data ['bill_infos_bill_status'] = 3;
            }
        }
        if (!empty($data ['bill_infos_fee_payment_price']) && !isset($data ['bill_infos_fee_payment_date'])) {
            $data ['bill_infos_fee_payment_date'] = date('Y/m/d');
        }
        return $data;
    }
}
