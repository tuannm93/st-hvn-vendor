<?php

namespace App\Services\Commission;

use App\Services\BaseService;
use App\Repositories\CommissionCorrespondsRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCommissionTypeRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CorrespondService extends BaseService
{
    /**
     * @var CommissionCorrespondsRepositoryInterface
     */
    protected $commissionCorrespondRepo;
    /**
     * @var MUserRepositoryInterface
     */
    protected $userRepo;
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepo;
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepo;
    /**
     * @var MCommissionTypeRepositoryInterface
     */
    protected $mCommissionTypeRepo;
    /**
     *
     */
    const EXCLUSION_STATUS = ['0' => '', '1' => '成功', '2' => '失敗'];

    const REFORM_UP_SELL = ['1' => '申請', '2' => '認証', '3' => '非認証'];

    const CHECK_STATUS = ['0' => 'チェック無', '1' => 'チェック有'];

    const SUPPORT_STATUS = ['0' => '対応中', '1' => '非対応'];

    const UNIT = ['0' => '円', '1' => '%'];

    const PRIORITY = ['0' => '-', '1' => '大至急', '2' => '至急', '3' => '通常'];

    const IRREGULAR_REASON = 'イレギュラー理由';
    /**
     * CorrespondService constructor.
     * @param CommissionCorrespondsRepositoryInterface $commissionCorrespondsRepo
     * @param MUserRepositoryInterface $userRepo
     * @param CommissionInfoRepositoryInterface $commissionInfoRepo
     * @param DemandInfoRepositoryInterface $demandInfoRepo
     * @param MCommissionTypeRepositoryInterface $mCommissionTypeRepo
     */
    public function __construct(
        CommissionCorrespondsRepositoryInterface $commissionCorrespondsRepo,
        MUserRepositoryInterface $userRepo,
        CommissionInfoRepositoryInterface $commissionInfoRepo,
        DemandInfoRepositoryInterface $demandInfoRepo,
        MCommissionTypeRepositoryInterface $mCommissionTypeRepo
    ) {
        $this->commissionCorrespondRepo = $commissionCorrespondsRepo;
        $this->userRepo = $userRepo;
        $this->commissionInfoRepo = $commissionInfoRepo;
        $this->demandInfoRepo = $demandInfoRepo;
        $this->mCommissionTypeRepo = $mCommissionTypeRepo;
    }

    /**
     * @param int $id
     * @return \App\Models\Base|null
     */
    public function findById(int $id)
    {
        $item = null;
        try {
            $item = $this->commissionCorrespondRepo->find($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $item;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findByIdWithUserName(int $id)
    {
        $item = [];
        try {
            $item = $this->commissionCorrespondRepo->findByIdWithUserName($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $item;
    }

    /**
     * @return array|mixed
     */
    public function getListUser()
    {
        $users = [];
        try {
            $users = $this->userRepo->getUser();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $users;
    }

    /**
     * @param array $data
     * @return array
     */
    public function update(array $data)
    {
        $result = [
            'code' => 0,
            'message' => ''
        ];
        $data['modified_user_id'] = Auth::user()['user_id'];
        if (isset($data['modified'])) {
            if ($this->checkModifiedTime($data['id'], $data['modified'])) {
                try {
                    $this->commissionCorrespondRepo->save($data);
                    $result['message'] = trans('commission_corresponds.message_successfully');
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                    $result['code'] = 100;
                    $result['message'] = trans('commission_corresponds.message_failure');
                }
            } else {
                session()->flash('error', trans('commission_corresponds.message_modified_not_check'));
                $result['code'] = 100;
                $result['message'] = trans('commission_corresponds.message_modified_not_check');
            }
        }
        return $result;
    }

    /**
     * @param mixed $commissionCorrespond
     * @return boolean|mixed
     */
    public function save($commissionCorrespond)
    {
        try {
            $result = $this->commissionCorrespondRepo->save($commissionCorrespond);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $result = false;
        }
        return $result;
    }

    /**
     * @param mixed $commissionCorrespond
     * @return boolean
     */
    public function insert($commissionCorrespond)
    {
        try {
            $result = $this->commissionCorrespondRepo->insert($commissionCorrespond);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $result = false;
        }
        return $result;
    }

    /**
     * @param integer $id
     * @param string $modified
     * @return bool
     */
    private function checkModifiedTime($id, $modified)
    {
        $result = false;
        try {
            $commissionCorrespond = $this->commissionCorrespondRepo->find($id)->toArray();
            $result = $modified == $commissionCorrespond['modified'];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        return $result;
    }

    /**
     * @param integer $id
     * @param array $data
     * @return string
     */
    public function getCorrespond($id = null, $data = null)
    {
        $correspond = '';

        if (!empty($data['CommissionInfo'])) {
            $columns = $this->commissionInfoRepo->getAllFields();
            $commissionInfo = $this->commissionInfoRepo->find($id)->toArray();

            foreach ($data['CommissionInfo'] as $newKey => $newValue) {
                foreach ($commissionInfo as $oldKey => $oldValue) {
                    if ($newKey == $oldKey) {
                        $newValue = $this->checkDateTimeData($newKey, $newValue, [
                            'commission_note_send_datetime',
                            'tel_commission_datetime',
                            're_commission_exclusion_datetime'
                        ]);
                        if ($newKey == 'commission_status_last_updated') {
                            break;
                        }

                        if ($this->checkDiffValue($newKey, $newValue, $oldValue)) {
                            $comment = '';
                            $newText = '';
                            $oldText = '';

                            foreach ($columns as $column) {
                                if ($column->column_name == $newKey) {
                                    $comment = $column->column_comment;
                                    $newText = $this->getValueByTableCommissionInfo($column->column_name, $newValue);
                                    $oldText = $this->getValueByTableCommissionInfo($column->column_name, $oldValue);
                                    break;
                                }
                            }
                            $new = $this->setValue($newValue, $newText);
                            $old = $this->setValue($oldValue, $oldText);
                            $correspond .= $comment . ' : ' . $old . ' → ' . $new . "\n";
                        }
                        break;
                    }
                }
            }
        }

        $correspond .= $this->getCorrespondByDemand($data);

        return $correspond;
    }

    /**
     * @param array $data
     * @return string
     */
    private function getCorrespondByDemand($data = null)
    {
        $correspond = '';

        if (!empty($data['DemandInfo'])) {
            $columns = $this->demandInfoRepo->getAllFields();
            $di = $this->demandInfoRepo->find($data['DemandInfo']['id'])->toArray();

            foreach ($data['DemandInfo'] as $newKey => $newValue) {
                foreach ($di as $oldKey => $oldValue) {
                    if ($newKey == $oldKey) {
                        if ($this->checkDiffValue($newKey, $newValue, $oldValue)) {
                            $comment = '';
                            $newText = '';
                            $oldText = '';

                            foreach ($columns as $column) {
                                if ($column->column_name == $newKey) {
                                    $comment = $column->column_comment;
                                    $newText = $this->getValueByTableDemandInfo($column->column_name, $newValue);
                                    $oldText = $this->getValueByTableDemandInfo($column->column_name, $oldValue);
                                    break;
                                }
                            }
                            $new = $this->setValue($newValue, $newText);
                            $old = $this->setValue($oldValue, $oldText);
                            $correspond .= $comment . ' : ' . $old . ' → ' . $new . "\n";
                        }
                        break;
                    }
                }
            }
        }

        return $correspond;
    }

    /**
     * @param string $newValue
     * @param string $newText
     * @return mixed
     */
    public function setValue($newValue, $newText)
    {
        if (!empty($newText)) {
            return $newText;
        }
        return $newValue;
    }
    /**
     * @param integer $id
     * @param array $data
     * @return string
     */
    public function getCorrespondWithoutAlias($id = null, $data = null)
    {
        $correspond = '';

        if (!empty($data)) {
            $columns = $this->commissionInfoRepo->getAllFields();
            $commissionInfo = $this->commissionInfoRepo->find($id)->toArray();
            foreach ($data as $newKey => $newValue) {
                foreach ($commissionInfo as $oldKey => $oldValue) {
                    if ($newKey == $oldKey) {
                        $newValue = $this->checkDateTimeData($newKey, $newValue, ['commission_note_send_datetime', 'tel_commission_datetime']);
                        if ($newKey == 'commission_status_last_updated') {
                            break;
                        }
                        if ($newValue != $oldValue) {
                            $comment = '';
                            $newText = '';
                            $oldText = '';

                            foreach ($columns as $column) {
                                if ($column->column_name == $newKey) {
                                    $comment = $column->column_comment;
                                    $newText = $this->getValueByTableCommissionInfo($column->column_name, $newValue);
                                    $oldText = $this->getValueByTableCommissionInfo($column->column_name, $oldValue);
                                    break;
                                }
                            }
                            $new = $this->setValue($newValue, $newText);
                            $old = $this->setValue($oldValue, $oldText);
                            $correspond .= $comment . ' : ' . $old . ' → ' . $new . "\n";
                        }
                        break;
                    }
                }
            }
        }

        $correspond .= $this->getCorrespondByDemandWithoutAlias($data);

        return $correspond;
    }

    /**
     * @param string $newKey
     * @param string $newValue
     * @param array $listKey
     * @return mixed|string
     */
    public function checkDateTimeData($newKey, $newValue, $listKey)
    {
        if (in_array($newKey, $listKey)) {
            if (!empty($newValue)) {
                $newValue = str_replace('/', '-', $newValue);
                $newValue = $newValue . ':00';
            }
        }
        return $newValue;
    }
    /**
     * @param array $data
     * @return string
     */
    private function getCorrespondByDemandWithoutAlias($data = null)
    {
        $correspond = '';

        if (!empty($data['demand_info'])) {
            $columns = $this->demandInfoRepo->getAllFields();
            $di = $this->demandInfoRepo->find($data['demand_info']['id'])->toArray();

            foreach ($data['demand_info'] as $newKey => $newValue) {
                foreach ($di as $oldKey => $oldValue) {
                    if ($newKey == $oldKey) {
                        if ($newValue != $oldValue) {
                            $comment = '';
                            $newText = '';
                            $oldText = '';

                            foreach ($columns as $column) {
                                if ($column->column_name == $newKey) {
                                    $comment = $column[0]['column_comment'];
                                    $newText = $this->getValueByTableDemandInfo($column->column_name, $newValue);
                                    $oldText = $this->getValueByTableDemandInfo($column->column_name, $oldValue);
                                    break;
                                }
                            }
                            $new = $this->setValue($newValue, $newText);
                            $old = $this->setValue($oldValue, $oldText);
                            $correspond .= $comment . ' : ' . $old . ' → ' . $new . "\n";
                        }
                        break;
                    }
                }
            }
        }

        return $correspond;
    }

    /**
     * @param string $col
     * @param string $val
     * @return mixed|string
     */
    private function getValueByTableCommissionInfo($col = null, $val = null)
    {
        if (empty($col) || !isset($val) || $val == '') {
            return '';
        }

        $rtn = [];

        switch ($col) {
            case 'irregular_reason':
                $rtn = getDropList(config('constant.M_ITEM.IRREGULAR_REASON'));
                break;
            case 're_commission_exclusion_status':
                $rtn = self::EXCLUSION_STATUS;
                break;
            case 'reform_upsell_ic':
                $rtn = self::REFORM_UP_SELL;
                break;
            case 'commission_type':
                $rtn = $this->mCommissionTypeRepo->getList();
                break;
            case 'commission_status':
                $rtn = getDropList(config('constant.M_ITEM.ITEM_CATEGORY'));
                break;
            case 'commission_order_fail_reason':
                $rtn = getDropList(config('constant.M_ITEM.REASON_FOR_LOSING_CONSENT'));
                break;
            case 'progress_reported':
            case 'unit_price_calc_exclude':
            case 'first_commission':
            case 'introduction_free':
            case 'ac_commission_exclusion_flg':
                $rtn = self::CHECK_STATUS;
                break;
            case 'tel_support':
            case 'visit_support':
            case 'order_support':
                $rtn = self::SUPPORT_STATUS;
                break;
            case 'order_fee_unit':
                $rtn = self::UNIT;
                break;
            case 'appointers':
            case 'tel_commission_person':
            case 'commission_note_sender':
                $rtn = $this->userRepo->dropDownUser();
                break;
            default:
                break;
        }

        return isset($rtn[$val]) ? $rtn[$val] : '';
    }

    /**
     * @param null $col
     * @param null $val
     * @return string
     */
    private function getValueByTableDemandInfo($col = null, $val = null)
    {
        if (empty($col) || !isset($val) || $val == '') {
            return '';
        }

        $rtn = [];

        switch ($col) {
            case 'construction_class':
                $rtn = getDropList(config('constant.M_ITEM.BUILDING_TYPE'));
                break;
            case 'demand_status':
                $rtn = getDropList(config('constant.M_ITEM.PROPOSAL_STATUS'));
                break;
            case 'order_fail_reason':
                $rtn = getDropList(config('constant.M_ITEM.REASON_FOR_LOST_NOTE'));
                break;
            case 'jbr_work_contents':
                $rtn = getDropList(config('constant.M_ITEM.JBR_WORK_CONTENTS'));
                break;
            case 'jbr_category':
                $rtn = getDropList(config('constant.M_ITEM.JBR_CATEGORY'));
                break;
            case 'jbr_estimate_status':
                $rtn = getDropList(config('constant.M_ITEM.JBR_ESTIMATE_STATUS'));
                break;
            case 'jbr_receipt_status':
                $rtn = getDropList(config('constant.M_ITEM.JBR_RECEIPT_STATUS'));
                break;
            case 'pet_tombstone_demand':
                $rtn = getDropList(config('constant.M_ITEM.PET_TOMBSTONE_DEMAND'));
                break;
            case 'sms_demand':
                $rtn = getDropList(config('constant.M_ITEM.SMS_DEMAND'));
                break;
            case 'special_measures':
                $rtn = getDropList(config('constant.M_ITEM.PROJECT_SPECIAL_MEASURES'));
                break;
            case 'acceptance_status':
                $rtn = getDropList(config('constant.M_ITEM.ACCEPTANCE_STATUS'));
                break;
            case 'priority':
                $rtn = self::PRIORITY;
                break;
            case 'riro_kureka':
                $rtn = self::CHECK_STATUS;
                break;
            default:
                break;
        }

        return isset($rtn[$val]) ? $rtn[$val] : '';
    }

    /**
     * @param $key
     * @param $newVal
     * @param $oldVal
     * @return bool
     */
    private function checkDiffValue($key, $newVal, $oldVal)
    {
        $dateField = [
            'tel_commission_datetime', 'commission_note_send_datetime', 'progress_report_datetime', 'order_fail_date', 'complete_date',
            're_commission_exclusion_datetime', 'order_date'
        ];
        $affCheckField = ['jbr_estimate_status', 'jbr_receipt_status'];
        $diff = false;

        if (in_array($key, $dateField)) {
            $newVal = date('YmdHi00', strtotime($newVal));
            $oldVal = date('YmdHi00', strtotime($oldVal));
        }

        if ($newVal != $oldVal) {
            $diff = true;
        }

        if (Auth::user()['auth'] == 'affiliation' && in_array($key, $affCheckField)) {
            $diff = false;
        }

        return $diff;
    }
}
