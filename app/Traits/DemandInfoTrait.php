<?php

namespace App\Traits;

use App\Models\MCorp;
use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\AutoCommissionCorpRepositoryInterface;
use App\Repositories\DemandAttachedFileRepositoryInterface;
use App\Repositories\DemandCorrespondsRepositoryInterface;
use App\Repositories\DemandExtendInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\DemandNotificationRepositoryInterface;
use App\Repositories\Eloquent\CommissionInfoRepository;
use App\Repositories\MAnswerRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\MSiteCategoryRepositoryInterface;
use App\Repositories\MSiteGenresRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Repositories\VisitTimeRepositoryInterface;
use App\Services\Auction\AuctionService;
use App\Services\CommissionService;
use App\Services\Cyzen\CyzenNotificationServices;
use App\Services\MPostService;
use App\Services\MSiteService;

trait DemandInfoTrait
{
    /**
     * @var DemandCorrespondsRepositoryInterface
     */
    protected $demandCorrespondsRepository;
    /**
     * @var MUserRepositoryInterface
     */
    protected $mUserRepository;
    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionInfoRepo;
    /**
     * @var MSiteService
     */
    protected $mSiteService;
    /**
     * @var MPostService
     */
    protected $mPostService;
    /**
     * @var AuctionService
     */
    protected $auctionService;
    /**
     * @var CommissionService
     */
    protected $commissionService;
    /**
     * @var MSiteRepositoryInterface
     */
    protected $mSite;
    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepo;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCateRepo;

    /**
     * @var MCorp
     */
    protected $mCorp;
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepo;
    /**
     * @var DemandAttachedFileRepositoryInterface
     */
    protected $demandAttachedFileRepo;
    /**
     * @var VisitTimeRepositoryInterface
     */
    protected $visitTimeRepo;
    /**
     * @var AutoCommissionCorpRepositoryInterface
     */
    protected $autoCommissionCorp;
    /**
     * @var MAnswerRepositoryInterface
     */
    protected $mAnswerRepository;
    /**
     * @var MSiteGenresRepositoryInterface
     */
    protected $mSiteGenresRepository;
    /**
     * @var MSiteCategoryRepositoryInterface
     */
    protected $mSiteCategoryRepo;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenresRepository;
    /**
     * @var DemandExtendInfoRepositoryInterface
     */
    protected $mDemandExtendRepo;
    /**
     * @var DemandNotificationRepositoryInterface
     */
    protected $mDemandNotifyRepo;

    /**
     * @var CommissionInfoRepository
     */
    protected $commissionInfoRepository;

    /**
     * @des get Error
     * @return array
     */
    public function getErrors()
    {
        $errs = [];

        $sessionErrors = is_array(session('errors')) ? session('errors') : [session('errors')];

        $errs = session('errors') ? array_merge($errs, $sessionErrors) : $errs;

        $errs = session('demand_errors') ? array_merge($errs, session()->all()) : $errs;

        if (session('commission_errors')) {
            $errs['commission_errors'] = session('commission_errors');
        }
        $errs['error_msg_input'] = session('error_msg_input') ? session('error_msg_input') : null;

        return $errs;
    }

    /**
     * @param $data
     * @param $errFlg
     * @return bool
     */
    public function validateAndDebug($data, $errFlg)
    {
        /* validate demand info */
        if (!$this->demandInfoService->validateDemandInfo($data)) {
            \Log::debug('validateDemandInfo');
            $errFlg = true;
        }
        /* validate visit time */
        if ($this->demandInfoService->validateDemandInquiryAnswer($data)
            || $this->visitTimeService->processValidateVisitTime($data)
        ) {
            \Log::debug('validateDemandInquiryAnswer, processValidateVisitTime');
            $errFlg = true;
        }
        if ($this->demandInfoService->checkErrSendCommissionInfo($data)) {
            \Log::debug('checkErrSendCommissionInfo');
            $errFlg = true;
        }
        /* validate commission info */
        if (isset($data['commissionInfo'])) {
            if (!$this->commissionService->validate($data['commissionInfo'], $data['demandInfo']['demand_status'])) {
                \Log::debug('commissionService->validate');
                $errFlg = true;
            }
            if (!$this->commissionService->validateCheckLimitCommitFlg($data)) {
                \Log::debug('commissionService->validateCheckLimitCommitFlg');
                session()->flash('error_msg_input', __('demand.error_miss_input'));
                session()->flash('errors.error_commit_flg', __('demand.commitFlgLimit'));
                $errFlg = true;
            }
        }
        /* validate demand correspond */
        if (isset($data['demandCorrespond']) && $data['send_commission_info'] == 0) {
            if (!$this->demandCorrespondService->validate($data['demandCorrespond'], $data['frm_action'])) {
                \Log::debug('demandCorrespondService->validate');
                $errFlg = true;
            }
        }

        return $errFlg;
    }

    /**
     * @param $data
     * @param $errFlg
     * @return mixed
     */
    public function validateAndBackToDetail($data, $errFlg)
    {
        /*validate commission type div*/
        $cTypeDiv = $data['demandInfo']['commission_type_div'];
        $commissionExits = array_key_exists('commissionInfo', $data);
        $demandStatus = $data['demandInfo']['demand_status'];
        if (!$this->demandInfoService->validateCommissionTypeDiv($cTypeDiv, $commissionExits, $demandStatus)) {
            session()->flash('error_msg_input', __('demand.notEmptyIntroduceInfo'));
            \Log::debug('validateCommissionTypeDiv');
            return true;
        }

        if (!$this->demandInfoService->checkModifiedDemand($data['demandInfo'])) {
            session()->flash('error_msg_input', __('demand.modifiedNotCheck'));
            session()->flash('again_enabled', true);
            \Log::debug('checkModifiedDemand');
            return true;
        }

        if (!$this->demandInfoService->validateSelectSystemType($data)) {
            \Log::debug('validateSelectSystemType');
            return true;
        }

        return $errFlg;
    }

    /**
     * @param $hasStartTimeErr
     * @param $auctionNoneFlg
     * @param $auctionFlg
     */
    public function setWarningMessage($hasStartTimeErr, $auctionNoneFlg, $auctionFlg)
    {
        if ($hasStartTimeErr) {
            return __('demand.start_time_error');
        } elseif (!$auctionNoneFlg) {
            return __('demand.aff_nothing');
        } elseif (!$auctionFlg) {
            return __('demand.auction_ng_update');
        }
        return;
    }

    /**
     * @param $data
     * @param $commissionData
     * @throws \Exception
     */
    public function sendNotify($data, $commissionData)
    {
        $listCorpNoStaff = collect($commissionData)->reject(function ($item) {
            if ((isset($item['id_staff']) && strlen(trim($item['id_staff'])) > 0)
                || (isset($item['commit_flg']) && (int)$item['commit_flg'] == 0)) {
                return true;
            }
            return false;
        })->pluck('corp_id')->toArray();
        $demandId = $data['demandInfo']['id'];
        if (!$data['demandInfo']['id']) {
            $demandId = $this->demandInfoRepo->getMaxIdInsert();
        }
        if (isset($data['send_commission_info']) && $data['send_commission_info'] == 1) {
            $this->commissionService->sendNotify($demandId, $listCorpNoStaff);
        }
    }

    /**
     * @des transaction for update
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function updateData(&$data)
    {
        $data['demandInfo'] = $this->demandInfoService->updateDemand($data['demandInfo']);
        $this->demandInquiryAnswerService->updateDemandInquiryAnswer($data);
        $this->visitTimeService->updateVisitTime($data);
        $errorNo = $this->commissionService->updateCommission($data);
        $introduceCommission = $this->commissionService->updateIntroduce($data);
        if (!empty($introduceCommission)) {
            if (isset($data['commissionInserted'])) {
                $data['commissionInserted'] = array_merge($data['commissionInserted'], $introduceCommission);
            } else {
                $data['commissionInserted'] = $introduceCommission;
            }
        }
        $this->auctionService->updateAuctionInfos($data['demandInfo']['id'], $data);
        $this->demandCorrespondService->updateDemandCorrespond($data);

        return $errorNo;
    }

    /**
     * @param $data
     * @param $commissionInfo
     * @param $sendCommissionInfo
     * @param $errFlg
     * @return bool
     */
    public function checkCommissionFlgCount($data, $commissionInfo, $sendCommissionInfo, $errFlg)
    {
        if (!empty($commissionInfo) && $sendCommissionInfo == 1) {
            if (!$this->commissionService->checkCommissionFlgCount($data)) {
                \Log::debug('checkCommissionFlgCount');
                session()->flash('error_commission_flg_count', __('demand.check_input_confirmation'));
                $errFlg = true;
            }
        }
        return $errFlg;
    }

    /**
     * @param $data
     */
    public function updateCommissionSendMailFax($data)
    {
        if ($data['send_commission_info'] == 1) {
            $this->commissionService->updateCommissionSendMailFax($data);
        }

        return;
    }

    /**
     * @param $id
     * @return \App\Models\Base|null
     */
    public function findDemandCorrespond($id)
    {
        return $this->demandCorrespondsRepository->find($id);
    }

    /**
     * @param $data
     * @param $notifyData
     * @return mixed
     */
    public function updateDemandExtendAndNotify($data, $notifyData)
    {
        if (!empty($notifyData) && $data['demandInfo']['demand_status'] === '5') {
            foreach ($notifyData as $key => $item) {
                if ($item['status'] === CyzenNotificationServices::STATUS_DEMAND_REGISTER) {
                    $this->commissionInfoRepository->updateWorkStatus([
                        'id' => $item['commission_id'],
                        'work_status' => 0
                    ]);
                }
            }
        }
        if (!empty($notifyData)) {
            $data['demandNotifyInfos'] = $this->mDemandNotifyRepo->updateOrCreate(
                $data['demandInfo']['id'],
                $notifyData
            );
        }
        return $data;
    }

    /**
     * @des init repository
     */
    private function initRepository()
    {
        $this->mCorpRepo = app()->make(MCorpRepositoryInterface::class);
        $this->demandInfoRepo = app()->make(DemandInfoRepositoryInterface::class);
        $this->demandAttachedFileRepo = app()->make(DemandAttachedFileRepositoryInterface::class);
        $this->visitTimeRepo = app()->make(VisitTimeRepositoryInterface::class);
        $this->autoCommissionCorp = app()->make(AutoCommissionCorpRepositoryInterface::class);
        $this->mSiteGenresRepository = app()->make(MSiteGenresRepositoryInterface::class);
        $this->mAnswerRepository = app()->make(MAnswerRepositoryInterface::class);
        $this->mCorp = new MCorp();
        $this->mSiteCategoryRepo = app()->make(MSiteCategoryRepositoryInterface::class);
        $this->mGenresRepository = app()->make(MGenresRepositoryInterface::class);
        $this->demandCorrespondsRepository = app()->make(DemandCorrespondsRepositoryInterface::class);
        $this->mUserRepository = app()->make(MUserRepositoryInterface::class);
        $this->auctionInfoRepo = app()->make(AuctionInfoRepositoryInterface::class);
        $this->mSite = app()->make(MSiteRepositoryInterface::class);
        $this->mItemRepository = app()->make(MItemRepositoryInterface::class);
        $this->mCateRepo = app()->make(MCategoryRepositoryInterface::class);
        $this->mDemandExtendRepo = app()->make(DemandExtendInfoRepositoryInterface::class);
        $this->mDemandNotifyRepo = app()->make(DemandNotificationRepositoryInterface::class);
        $this->commissionInfoRepository = app()->make(CommissionInfoRepository::class);
    }
}
