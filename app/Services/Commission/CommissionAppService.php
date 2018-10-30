<?php
namespace App\Services\Commission;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Repositories\CommissionAppRepositoryInterface;
use App\Repositories\ApprovalRepositoryInterface;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;

class CommissionAppService extends BaseService
{
    /**
     * @var CommissionAppRepositoryInterface
     */
    private $commissionAppRepository;
    /**
     * @var ApprovalRepositoryInterface
     */
    private $approvalRepository;

    /**
     * CommissionAppService constructor.
     *
     * @param CommissionAppRepositoryInterface $commissionAppRepository
     * @param ApprovalRepositoryInterface      $approvalRepository
     */
    public function __construct(
        CommissionAppRepositoryInterface $commissionAppRepository,
        ApprovalRepositoryInterface $approvalRepository
    ) {
        $this->commissionAppRepository = $commissionAppRepository;
        $this->approvalRepository = $approvalRepository;
    }

    /**
     * @param array $data
     */
    public function registerApplication($data)
    {
        try {
            DB::beginTransaction();

            $saveData = [];
            if (!empty($data['chk1'])) {
                $saveData['CommissionApplication']['chg_deduction_tax_include'] = 1;
            }

            if (!empty($data['deduction_tax_include'])) {
                $saveData['CommissionApplication']['deduction_tax_include'] = $data['deduction_tax_include'];
            }
            if (!empty($data['chk2'])) {
                $saveData['CommissionApplication']['chg_irregular_fee_rate'] = 1;
            }
            if (!empty($data['irregular_fee_rate'])) {
                $saveData['CommissionApplication']['irregular_fee_rate'] = $data['irregular_fee_rate'];
            }
            if (!empty($data['chk3'])) {
                $saveData['CommissionApplication']['chg_irregular_fee'] = 1;
            }
            if (!empty($data['irregular_fee'])) {
                $saveData['CommissionApplication']['irregular_fee'] = $data['irregular_fee'];
            }
            if (!empty($data['chk2']) || !empty($data['chk3'])) {
                $saveData['CommissionApplication']['irregular_reason'] = $data['irregular_reason'];
            }
            if (!empty($data['chk4'])) {
                $saveData['CommissionApplication']['chg_introduction_free'] = 1;
            }
            if (!empty($data['introduction_free'])) {
                $saveData['CommissionApplication']['introduction_free'] = 1;
            } else {
                $saveData['CommissionApplication']['introduction_free'] = 0;
            }
            if (!empty($data['chk5'])) {
                $saveData['CommissionApplication']['chg_ac_commission_exclusion_flg'] = 1;
            }
            if (!empty($data['ac_commission_exclusion_flg'])) {
                $saveData['CommissionApplication']['ac_commission_exclusion_flg'] = 1;
            } else {
                $saveData['CommissionApplication']['ac_commission_exclusion_flg'] = 0;
            }
            if (!empty($data['chk6'])) {
                $saveData['CommissionApplication']['chg_introduction_not'] = 1;
            }
            if (!empty($data['introduction_not'])) {
                $saveData['CommissionApplication']['introduction_not'] = 1;
            } else {
                $saveData['CommissionApplication']['introduction_not'] = 0;
            }
            if (!empty($data['commission_id'])) {
                $saveData['CommissionApplication']['commission_id'] = $data['commission_id'];
            }
            if (!empty($data['demand_id'])) {
                $saveData['CommissionApplication']['demand_id'] = $data['demand_id'];
            }
            if (!empty($data['corp_id'])) {
                $saveData['CommissionApplication']['corp_id'] = $data['corp_id'];
            }

            $commissionApp = $this->commissionAppRepository->saveApp($saveData['CommissionApplication']);

            if ($commissionApp) {
                $saveData['Approval']['application_section'] = 'CommissionApplication';
                $saveData['Approval']['relation_application_id'] = $commissionApp->id;
                $saveData['Approval']['application_user_id'] = Auth::user()->user_id;

                if (!empty($data['application_reason'])) {
                    $saveData['Approval']['application_reason'] = $data['application_reason'];
                }

                if ($this->approvalRepository->save($saveData['Approval'])) {
                    DB::commit();
                    session()->flash('application_message', __('commission_detail.application_message'));
                } else {
                    DB::rollBack();
                    session()->flash('error', Lang::get('commission_corresponds.message_failure'));
                }
            }
        } catch (Exception $e) {
            logger(__METHOD__ . ': ' . $e->getMessage());
            DB::rollBack();
            session()->flash('error', Lang::get('commission_corresponds.message_failure'));
        }
    }
}
