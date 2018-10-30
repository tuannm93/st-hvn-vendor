<?php


namespace App\Services\Logic;

use App\Models\CorpAgreement;
use App\Models\MCorp;
use App\Repositories\AffiliationInfoRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MCorpSubRepositoryInterface;
use App\Services\Affiliation\AffiliationCorpService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Step2Logic
{

    /**
     * @var AffiliationCorpService
     */
    protected $affiliationCorpService;
    /**
     * @var AgreementSystemLogic
     */
    protected $agreementSystemLogic;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var AffiliationInfoRepositoryInterface
     */
    protected $affiliationInfoRepository;
    /**
     * @var MCorpSubRepositoryInterface
     */
    protected $mCorpSubRepository;

    /**
     * Step2Logic constructor.
     * @param AffiliationCorpService $affiliationCorpService
     * @param AgreementSystemLogic $agreementSystemLogic
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param AffiliationInfoRepositoryInterface $affiliationInfoRepository
     * @param MCorpSubRepositoryInterface $mCorpSubRepository
     */
    public function __construct(
        AffiliationCorpService $affiliationCorpService,
        AgreementSystemLogic $agreementSystemLogic,
        MCorpRepositoryInterface $mCorpRepository,
        AffiliationInfoRepositoryInterface $affiliationInfoRepository,
        MCorpSubRepositoryInterface $mCorpSubRepository
    ) {
        $this->affiliationCorpService = $affiliationCorpService;
        $this->agreementSystemLogic = $agreementSystemLogic;
        $this->mCorpRepository = $mCorpRepository;
        $this->affiliationInfoRepository = $affiliationInfoRepository;
        $this->mCorpSubRepository = $mCorpSubRepository;
    }

    /**
     * @param object $mCorp
     * @return array
     */
    public function getStep2($mCorp)
    {
        $affiliationInfo = $this->affiliationCorpService->getAffiliationInfo($mCorp->id);
        $mCorpSubs = $this->affiliationCorpService->getMCorpSubByMCorpId($mCorp->id);
        $corpHolidays = $mCorpSubs['holiday'];
        $data = $this->getDataStep2($mCorp->responsibility);
        return ['data' => $data, 'corpHolidays' => $corpHolidays, 'affiliationInfo' => $affiliationInfo];
    }

    /**
     * @param string $responsibility
     * @return mixed
     */
    public function getDataStep2($responsibility)
    {
        $prefectureDiv = \Config::get('datacustom.prefecture_div');
        $coordinationMethodList = MCorp::COORDINATION_METHOD_LIST;
        $responsibilityMei = '';
        if (stripos($responsibility, " ") > 0) {
            $idx = stripos($responsibility, " ");
            $responsibilitySei = substr($responsibility, 0, $idx);
            $responsibilityMei = substr($responsibility, $idx + 1);
        } else {
            $responsibilitySei = $responsibility;
        }
        $data['responsibilitySei'] = $responsibilitySei;
        $data['responsibilityMei'] = $responsibilityMei;
        $data['prefectureDiv'] = $prefectureDiv;
        $data['coordinationMethodList'] = $coordinationMethodList;
        return $data;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function convertRequestData($data)
    {
        $data['mCorp']['available_time_from'] = isset($data['mCorp']['available_time_from']) ? $data['mCorp']['available_time_from'] : '';
        $data['mCorp']['available_time_to'] = isset($data['mCorp']['available_time_to']) ? $data['mCorp']['available_time_to'] : '';
        $data['mCorp']['contactable_time_from'] = isset($data['mCorp']['contactable_time_from']) ? $data['mCorp']['contactable_time_from'] : '';
        $data['mCorp']['contactable_time_to'] = isset($data['mCorp']['contactable_time_to']) ? $data['mCorp']['contactable_time_to'] : '';
        if (!array_key_exists('support_language_en', $data['mCorp'])) {
            $data['mCorp']['support_language_en'] = '0';
        }
        if (!array_key_exists('support_language_zh', $data['mCorp'])) {
            $data['mCorp']['support_language_zh'] = '0';
        }
        if (!array_key_exists('mobile_mail_none', $data['mCorp'])) {
            $data['mCorp']['mobile_mail_none'] = '0';
        }
        if (!array_key_exists('support24hour', $data['mCorp'])) {
            $data['mCorp']['support24hour'] = '0';
        }
        if (!array_key_exists('available_time_other', $data['mCorp'])) {
            $data['mCorp']['available_time_other'] = '0';
        }
        if (!array_key_exists('contactable_support24hour', $data['mCorp'])) {
            $data['mCorp']['contactable_support24hour'] = '0';
        }
        if (!array_key_exists('contactable_time_other', $data['mCorp'])) {
            $data['mCorp']['contactable_time_other'] = '0';
        }
        $data['mCorp']['responsibility'] = $data['mCorp']['responsibility_sei'] . ' ' . $data['mCorp']['responsibility_mei'];
        unset($data['mCorp']['responsibility_sei']);
        unset($data['mCorp']['responsibility_mei']);
        $data['affiliationInfo']['corp_id'] = $data['mCorp']['id'];
        return $data;
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function updateData($data)
    {
        DB::beginTransaction();
        try {
            $this->mCorpRepository->updateById($data['mCorp']);
            $this->affiliationInfoRepository->updateByCorpId($data['affiliationInfo']);
            $this->updateMCorpSub($data['mCorp']['id'], $data['holidays']);
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param integer $corpId
     * @param array $holidays
     * @throws \Exception
     */
    private function updateMCorpSub($corpId, $holidays)
    {
        DB::beginTransaction();
        try {
            if (empty($holidays) && empty($corpId)) {
                throw new \Exception("Invalid Params");
            }
            $conditions = [
                'corp_id' => $corpId,
                'item_category' => __('common.holiday')
            ];
            $getMCorpSubs = $this->mCorpSubRepository->getItemByCorpIdAndCate($corpId, __('common.holiday'));
            $itemsId = [];
            if (!$getMCorpSubs->isEmpty()) {
                foreach ($getMCorpSubs as $mCorpSub) {
                    $itemsId[] = $mCorpSub->item_id;
                }
                $conditions['item_id'] = array_intersect($itemsId, $holidays);
                $this->mCorpSubRepository->deleteItemsNotExist($conditions);
            }

            $corpSubList = [];
            foreach (array_values($holidays) as $key => $holiday) {
                if (in_array($holiday, $itemsId)) {
                    continue;
                }
                $corpSubList[$key]['corp_id'] = $corpId;
                $corpSubList[$key]['item_category'] = __('common.holiday');
                $corpSubList[$key]['item_id'] = $holiday;
                $corpSubList[$key]['created_user_id'] = Auth::user()->user_id;
                $corpSubList[$key]['created'] = Carbon::now()->toDateTimeString();
                $corpSubList[$key]['modified_user_id'] = Auth::user()->user_id;
                $corpSubList[$key]['modified'] = Carbon::now()->toDateTimeString();
            }
            if ($corpSubList) {
                $this->mCorpSubRepository->insert($corpSubList);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }

    /**
     * @param object $user
     * @param string $companyKind
     */
    public function step2Process($user, $companyKind)
    {
        $corpAgreement = $this->agreementSystemLogic->checkFirstCorpAgreementNotComplete($user);
        if (!is_null($corpAgreement)) {
            if ($corpAgreement->status == CorpAgreement::STEP1) {
                $corpAgreement->status = CorpAgreement::STEP2;
            }
            $corpAgreement->corp_kind = $companyKind;
            $this->agreementSystemLogic->updateCorpAgreement($corpAgreement, null, $user);
        }
    }
}
