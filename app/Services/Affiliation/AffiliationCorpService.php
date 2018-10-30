<?php

namespace App\Services\Affiliation;

use App\Helpers\MailHelper;
use App\Mail\CorpResponsibility;
use App\Mail\StResponsibility;
use App\Models\MCorpNewYear;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MCorpSub;
use App\Models\MCorp;
use App\Repositories\AffiliationInfoRepositoryInterface;
use App\Repositories\MCorpNewYearRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MCorpSubRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;

class AffiliationCorpService extends BaseService
{
    /**
     * @var MCorpRepositoryInterface
     */
    private $mCorpRepository;
    /**
     * @var MTargetAreaRepositoryInterface
     */
    private $mTargetAreaRepository;
    /**
     * @var AffiliationInfoRepositoryInterface
     */
    private $affiliationInfoRepository;
    /**
     * @var MPostRepositoryInterface
     */
    private $mPostRepository;
    /**
     * @var MCorpNewYearRepositoryInterface
     */
    private $mCorpNewYearRepository;
    /**
     * @var MCorpSubRepositoryInterface
     */
    private $mCorpSubRepository;

    /**
     * AffiliationCorpService constructor.
     *
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MTargetAreaRepositoryInterface $mTargetAreaRepository
     * @param AffiliationInfoRepositoryInterface $affiliationInfoRepository
     * @param MPostRepositoryInterface $mPostRepository
     * @param MCorpNewYearRepositoryInterface $mCorpNewYearRepository
     * @param MCorpSubRepositoryInterface $mCorpSubRepository
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        MTargetAreaRepositoryInterface $mTargetAreaRepository,
        AffiliationInfoRepositoryInterface $affiliationInfoRepository,
        MPostRepositoryInterface $mPostRepository,
        MCorpNewYearRepositoryInterface $mCorpNewYearRepository,
        MCorpSubRepositoryInterface $mCorpSubRepository
    ) {
        $this->mCorpRepository = $mCorpRepository;
        $this->mTargetAreaRepository = $mTargetAreaRepository;
        $this->affiliationInfoRepository = $affiliationInfoRepository;
        $this->mPostRepository = $mPostRepository;
        $this->mCorpNewYearRepository = $mCorpNewYearRepository;
        $this->mCorpSubRepository = $mCorpSubRepository;
    }

    //region Public Functions

    /**
     * Get MCrop Data
     *
     * @param  integer $id
     * @return Collection|mixed
     */
    public function getMCorpData($id)
    {
        return $this->mCorpRepository->findByIdForAffiliation($id);
    }

    /**
     * @param integer $corpId
     * @return array
     */
    public function getAffiliationInfo($corpId)
    {
        return $this->affiliationInfoRepository->findAffiliationInfoByCorpId($corpId);
    }

    /**
     * @param integer $corpId
     * @return array
     */
    public function getPrefList($corpId)
    {
        // Prefecture list (All region correspondence -
        // Partial region correspondence - No correspondence available setting)
        $prefList = [];

        foreach (Config::get('rits.prefecture_div') as $prefectureDivKey => $prefectureDivValue) {
            // 99 skipped reading
            if ($prefectureDivKey == 99) {
                continue;
            }
            $obj = [];
            $obj['id'] = $prefectureDivKey;
            $translatedPrefectureDivValue = __("rits_config.$prefectureDivValue");
            $obj['name'] = $translatedPrefectureDivValue;
            // Number of areas set by franchisees of designated prefectures
            $corpCount = $this->mPostRepository->getCorpPrefAreaCount($corpId, $translatedPrefectureDivValue);
            if ($corpCount > 0) {
                // Number of areas in the specified prefecture
                $areaCount = $this->mPostRepository->getPrefAreaCount($translatedPrefectureDivValue);
                if ($corpCount >= $areaCount) {
                    // All regions correspondence
                    $obj['rank'] = 2;
                } else {
                    // For some areas
                    $obj['rank'] = 1;
                }
                $prefList[] = $obj;
            }
        }

        return $prefList;
    }

    /**
     * @param integer $corpId
     * @return array
     */
    public function getMCorpSubByMCorpId($corpId)
    {
        $mCorps = $this->mCorpSubRepository->findByCorpIdForAffiliation($corpId);
        $holiday = [];
        $developmentResponse = [];
        foreach ($mCorps as $mCorp) {
            switch ($mCorp->item_category) {
                case __('common.holiday'):
                    $holiday[] = $mCorp->item_id;
                    break;
                case __('common.development_reaction'):
                    $developmentResponse[] = $mCorp->item_id;
                    break;
            };
        }
        return [
            'holiday' => $holiday,
            'developmentResponse' => $developmentResponse
        ];
    }

    /**
     * Create company master
     *
     * @param  integer $id
     * @param  array $data
     * @return mixed
     * @throws \Exception
     */
    public function updateCorp($id, $data)
    {
        /** @var MCorp $mCorp */
        $mCorp = $this->mCorpRepository->find($id);
        $validateResult = $this->validateUpdateCorpInputs($mCorp, $data);
        if ($validateResult['success'] == false) {
            return $validateResult;
        }

        $oldResponsibility = $mCorp->responsibility;
        $mCorpNewYears = $this->mCorpNewYearRepository->find($data['m_corp_new_years']['id']);

        //region Prepare data for $mCorp
        $this->getPrepareDataForUpdateCorp($mCorp, $data);
        //endregion
        //region Prepare data for $mCorpNewYears
        if (!empty($mCorpNewYears)) {
            foreach ($data['m_corp_new_years'] as $key => $value) {
                $mCorpNewYears->{$key} = $value;
                $mCorpNewYears->modified = date('Y-m-d H:i:s');
            }
        } else {
            $mCorpNewYears = new MCorpNewYear();
            $mCorpNewYears->corp_id = $id;
            $mCorpNewYears->created = date('Y-m-d H:i:s');
            foreach ($data['m_corp_new_years'] as $key => $value) {
                $this->checkKeyId($mCorpNewYears, $key, $value);
            }
        }
        //endregion
        DB::beginTransaction();

        try {
            $this->mCorpRepository->save($mCorp);
            $this->mCorpNewYearRepository->save($mCorpNewYears);


            //region save holiday $mCorpSub
            if (!empty($data['holiday'])) {
                $conditions = [
                    ['m_corp_subs.corp_id', '=', $id],
                    ['m_corp_subs.item_category', '=', __('common.holiday')]
                ];
                $this->mCorpSubRepository->deleteByCondition($conditions);
                foreach (array_keys($data ['holiday']) as $holidayK) {
                    $newMCorpSub = new MCorpSub();
                    $newMCorpSub->corp_id = $id;
                    $newMCorpSub->item_category = __('common.holiday');
                    $newMCorpSub->item_id = $holidayK;
                    $newMCorpSub->modified_user_id = auth()->user()->user_id;
                    $newMCorpSub->modified = date('Y-m-d H:i:s');
                    $newMCorpSub->created_user_id = auth()->user()->user_id;
                    $newMCorpSub->created = date('Y-m-d H:i:s');
                    $this->mCorpSubRepository->save($newMCorpSub);
                }
            }
            //endregion

            DB::commit();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('affiliation.m_corp_category_error_updating')
            ];
        }
        $this->checkAuth($data, $oldResponsibility, $id);
        return [
            'success' => true,
            'message' => __('common.updated_completed')
        ];
    }

    /**
     * @param array $data
     * @param string $oldResponsibility
     * @param integer $id
     */
    private function checkAuth(&$data, $oldResponsibility, $id)
    {
        if (Auth::user()->auth == 'affiliation') {
            if ($data['m_corps']['responsibility'] != $oldResponsibility) {
                $data['m_corps']['id'] = $id;
                // Mail delivery processing
                $this->sendResponsibilityMail($data);
            }
        }
    }

    /**
     * @param object $mCorpNewYears
     * @param string $key
     * @param mixed $value
     */
    private function checkKeyId(&$mCorpNewYears, &$key, &$value)
    {
        if ($key != 'id') {
            $mCorpNewYears->{$key} = $value;
            $mCorpNewYears->modified = date('Y-m-d H:i:s');
        }
    }

    //endregion Public Functions

    //region Private Functions

    /**
     * @param object $mCorp
     * @param array $data
     * @return array
     */
    private function validateUpdateCorpInputs($mCorp, $data)
    {
        $coordinationMethods = Config::get('rits.coordination_method');
        $requireEmailCoordMethods[] = array_search('mail_fax', $coordinationMethods);
        $requireEmailCoordMethods[] = array_search('mail', $coordinationMethods);
        $requireEmailCoordMethods[] = array_search('mail_app', $coordinationMethods);
        $requireEmailCoordMethods[] = array_search('mail_fax_app', $coordinationMethods);
        $coordinationMethod = $data['m_corps']['coordination_method'];
        $mailaddressPC = $data['m_corps']['mailaddress_pc'] ?? null;
        $mailaddressMobile = $data['m_corps']['mailaddress_mobile'] ?? null;
        if (in_array($coordinationMethod, $requireEmailCoordMethods)
            && (empty($mailaddressPC) && empty($mailaddressMobile))
        ) {
            return [
                'success' => false,
                'type' => 'field',
                'message' => __('affiliation.email_pc_or_email_mobile_empty')
            ];
        }
        if ($data['m_corps']['modified'] != $mCorp->modified) {
            return [
                'success' => false,
                'message' => __('affiliation.modified_not_check')
            ];
        }

        return [
            'success' => true,
        ];
    }

    /**
     * @param array $corp
     */
    private function sendResponsibilityMail($corp = null)
    {
        $fromCorp = env('ST_MAIL_FROM');
        $subjectCorp = __('affiliation.subject_corp_mail');

        $toST = env('KAMEITEN_MAIL_TO');
        $fromST = env('ST_MAIL_FROM');
        $subjectST = __('affiliation.subject_st_mail');

        if (!empty($corp['m_corps']['mailaddress_pc'])
            || !empty($corp['m_corps']['mailaddress_mobile'])
        ) {
            $toCorpArr = $this->getToCorpArr($corp);
            foreach ($toCorpArr as $toCorp) {
                $dataCorp = [
                    'subject' => $subjectCorp,
                    'to' => $toCorp,
                    'from' => $fromCorp
                ];
                try {
                    MailHelper::sendMail($dataCorp['to'], new CorpResponsibility($dataCorp, $corp));
                } catch (\Exception $exception) {
                    $msg = 'MailSend: Failure subject:' . $subjectCorp . "\n to:" . $toCorp;
                    Log::error($exception->getMessage());
                    MailHelper::sendRawMail($msg, 'ERROR: ' . $subjectST, $fromST, $toST);
                }
            }

            try {
                $dataST = [
                    'subject' => $subjectST,
                    'to' => $toST,
                    'from' => $fromST
                ];
                MailHelper::sendMail($dataST['to'], new StResponsibility($dataST, $corp));
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
            }
        }
    }

    /**
     * @param array $corp
     * @return array
     */
    private function getToCorpArr($corp)
    {
        $toCorpArr = [];
        if (!empty($corp['m_corps']['mailaddress_pc'])) {
            /* Because there is a possibility that more than one has been specified, split by semicolon */
            $tmpAddrs = explode(";", $corp['m_corps']['mailaddress_pc']);
            /* Store all email addresses */
            foreach ($tmpAddrs as $oneAddr) {
                $toCorpArr[] = $oneAddr;
            }
        }

        if (!empty($corp['m_corps']['mailaddress_mobile'])) {
            /* Because there is a possibility that more than one has been specified, split by semicolon */
            $tmpAddrs = explode(";", $corp['m_corps']['mailaddress_mobile']);
            /* Store all email addresses */
            foreach ($tmpAddrs as $oneAddr) {
                $toCorpArr[] = $oneAddr;
            }
        }

        return $toCorpArr;
    }

    /**
     * @param object $mCorp
     * @param array $data
     */
    private function getPrepareDataForUpdateCorp(&$mCorp, &$data)
    {
        $columns = [
            'support24hour',
            'contactable_support24hour',
            'contactable_time_other',
            'available_time_other',
            'support_language_en',
            'support_language_zh',
            'mobile_mail_none'
        ];
        foreach ($columns as $column) {
            $this->getData($data, 'm_corps', $column);
        }
        // Comment because the database don't have registration_details_check field
        // Need make sure the production database have that field to un-comment
        //        if (empty ( $data ['MCorp'] ['registration_details_check'] )) {
        //            $data ['MCorp'] ['registration_details_check'] = 0;
        //        }
        $data['m_corps']['responsibility'] = trim($data['m_corps']['responsibility_sei']) . ' ' .
            trim($data['m_corps']['responsibility_mei']);
        unset($data['m_corps']['responsibility_sei']);
        unset($data['m_corps']['responsibility_mei']);
        $data['m_corps']['modified'] = new Carbon();

        if (!empty($mCorp)) {
            foreach ($data['m_corps'] as $key => $value) {
                $mCorp->{$key} = $value;
            }
            $mCorp->modified_user_id = auth()->user()->user_id;
        }
    }

    /**
     * @param array $data
     * @param string $table
     * @param string $column
     */
    private function getData(&$data, $table, $column)
    {
        if (empty($data[$table][$column])) {
            $data[$table][$column] = 0;
        }
    }

    //endregion Private Functions
}
