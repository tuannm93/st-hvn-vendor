<?php

namespace App\Services;

use App\Helpers\MailHelper;
use App\Mail\Agreementponsibility;
use App\Repositories\AffiliationAreaStatRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Lang;
use App\Services\Affiliation\AffiliationAgreementFormatService;
use App\Services\Affiliation\AffiliationAgreementUploadService;

class AffiliationService
{
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepository;
    /**
     * @var MTargetAreaRepositoryInterface
     */
    protected $mTargetAreaRepository;
    /**
     * @var CorpAgreementTempLinkRepositoryInterface
     */
    protected $corpAgrTempLinkRepository;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTempRepository;
    /**
     * @var AffiliationAreaStatRepositoryInterface
     */
    protected $affiliationAreaStatRepository;
    /**
     * @var AffiliationAgreementFormatService
     */
    protected $affiliationAgreementFormat;
    /**
     * @var AffiliationAgreementUploadService
     */
    protected $affiliationAgreementUpload;
    /**
     * AffiliationService constructor.
     *
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     * @param MTargetAreaRepositoryInterface $mTargetAreaRepository
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgrTempLinkRepository
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository
     * @param AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepository
     * @param AffiliationAgreementFormatService $affiliationAgreementFormat
     * @param AffiliationAgreementUploadService $affiliationAgreementUpload
     */
    public function __construct(
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        MCorpRepositoryInterface $mCorpRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MTargetAreaRepositoryInterface $mTargetAreaRepository,
        CorpAgreementTempLinkRepositoryInterface $corpAgrTempLinkRepository,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository,
        AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepository,
        AffiliationAgreementFormatService $affiliationAgreementFormat,
        AffiliationAgreementUploadService $affiliationAgreementUpload
    ) {
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->mCorpRepository = $mCorpRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->mTargetAreaRepository = $mTargetAreaRepository;
        $this->corpAgrTempLinkRepository = $corpAgrTempLinkRepository;
        $this->mCorpCategoriesTempRepository = $mCorpCategoriesTempRepository;
        $this->affiliationAreaStatRepository = $affiliationAreaStatRepository;
        $this->affiliationAgreementFormat = $affiliationAgreementFormat;
        $this->affiliationAgreementUpload = $affiliationAgreementUpload;
    }

    /**
     * get agreement provisions of corp agreement
     *
     * @param  object $corpAgreement
     * @return string
     */
    public static function getAgreementProvisions($corpAgreement)
    {
        if (!$corpAgreement) {
            return null;
        }
        return empty($corpAgreement->customize_agreement)
            ? str_replace(["\n", "\r\n"], "<br>\n", $corpAgreement->original_agreement)
            : str_replace(["\n", "\r\n"], "<br>\n", $corpAgreement->customize_agreement);
    }

    /**
     * get url download by role and status corp agreement
     *
     * @param  string  $role
     * @param  object  $corpAgreement
     * @param  integer $corpId
     * @return string
     */
    public static function getUrlDownloadByRoleAndStatusCorpAgreement($role, $corpAgreement, $corpId)
    {
        $isReportDownloadUrl = false;
        if ($role != 'affiliation'
            && !empty($corpAgreement->status)
            && $corpAgreement->status == 'Complete'
        ) {
            $isReportDownloadUrl = true;
        }
        return $isReportDownloadUrl;
    }

    /**
     * check role
     *
     * @param  string $role
     * @param  array  $roleOption
     * @return boolean
     */
    public static function isRole($role, $roleOption)
    {
        return in_array($role, $roleOption) ? true : false;
    }

    /**
     * format date
     *
     * @param  string $date
     * @param  string $format
     * @return string
     */
    public static function dateTimeFormat($date, $format)
    {
        return Carbon::parse($date)->format($format);
    }

    /**
     * update agreement
     *
     * @param  array   $data
     * @param  integer $corpId
     * @param  integer $agreementId
     * @return view
     * @throws Exception
     */
    public function agreementUpdate($data, $corpId, $agreementId = null)
    {
        $status = false;
        DB::beginTransaction();
        try {
            $corpAgreement = $this->corpAgreementRepository->getFirstByCorpIdAndAgreementId(
                $corpId,
                $agreementId
            );
            $corpData = $this->mCorpRepository->find($corpId);
            $userId = Auth::user()['user_id'];
            $corpData = $this->affiliationAgreementFormat->formatInputDataCorp($corpData, $data, $userId);
            if (!$corpAgreement) {
                $dataCorpAgreement = $this->affiliationAgreementFormat->formatInputDataCorpAgreementTypeInsert($corpId, $data, $userId);
                $resultInsertCorpAgreement = $this->corpAgreementRepository->insertOrUpdateItem($dataCorpAgreement);
                if ($resultInsertCorpAgreement['status']) {
                    if (self::updateCategory($resultInsertCorpAgreement['item'])) {
                        if ($this->mCorpRepository->save($corpData)) {
                            $status = true;
                        }
                    }
                }
            } else {
                $corpAgreement = $this->affiliationAgreementFormat->formatInputDataCorpAgreementTypeUpdate($corpAgreement, $data, $userId);
                $resultSave = $this->saveAgreementData($data, $corpAgreement, $corpData);
                $status = $resultSave['status'];
                $resultSendMail = $resultSave['result_send_mail'];
            }
            if ($status) {
                $result['message'] = Lang::get('agreement.update_is_completed');
                $result['class'] = 'box--success';
                if (isset($resultSendMail) && !$resultSendMail) {
                    $result['message'] = Lang::get('agreement.failed_to_send_approval_notification_mail');
                    $result['class'] = 'box--error';
                }
                DB::commit();
            } else {
                $result['message'] = Lang::get('agreement.update_error');
                $result['class'] = 'box--error';
                DB::rollback();
            }
            return $result;
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception();
        }
    }

    /**
     * save agreement data
     * @param  array $data
     * @param  object $corpAgreement
     * @param  object $corpData
     * @return array
     */
    public function saveAgreementData($data, $corpAgreement, $corpData)
    {
        $resultSendMail = true;
        $status         = false;
        if ($this->corpAgreementRepository->save($corpAgreement)
            && self::updateCategory($corpAgreement)
            && $this->mCorpRepository->save($corpData)
        ) {
            $status = true;
        }
        if ($status
            && isset($data['CorpAgreement']['acceptation'])
            && $data['CorpAgreement']['acceptation'] == 1
            && in_array($corpData->coordination_method, [1, 2, 6, 7])
        ) {
            $resultSendMail = self::sendAgreementEmail($corpData);
        }

        return [
            'status' => $status,
            'result_send_mail' => $resultSendMail
        ];
    }
    /**
     * update category
     *
     * @param  object $corpAgreement
     * @return boolean
     */
    private function updateCategory($corpAgreement)
    {
        if (empty($corpAgreement)) {
            return true;
        } elseif (!isset($corpAgreement->status)
            || $corpAgreement->status !== 'Complete'
        ) {
            return true;
        }
        try {
            $corpId = $corpAgreement->corp_id;
            $corpAgreementId = $corpAgreement->id;
            $result = self::updateCorpCategoryFormCorpCategoryTemp($corpId, $corpAgreementId);
        } catch (Exception $e) {
            $result = false;
        }
        return $result;
    }

    /**
     * update corp category form corp category temp
     *
     * @param  integer $corpId
     * @param  integer $corpAgreementId
     * @return boolean
     */
    private function updateCorpCategoryFormCorpCategoryTemp($corpId = null, $corpAgreementId = null)
    {
        $resultsFlg = true;
        $tempLink = $this->corpAgrTempLinkRepository->getItemByCorpIdAndCorpAgreementId(
            $corpId,
            $corpAgreementId
        );
        if (!$tempLink) {
            return $resultsFlg;
        }
        $tempId = $tempLink->id;
        $tempData = $this->mCorpCategoriesTempRepository->getByCorpIdAndTempId($corpId, $tempId);
        $data = [];
        $this->formatDataCorpCategoryFormCorpCategoryTemp($tempData, $data);
        try {
            if (empty($data)) {
                return $resultsFlg;
            }
            $resultsFlg = $this->updateCorpCategoryGenre($corpId, $data);
            if ($resultsFlg) {
                $resultsFlg = $this->mCorpCategoriesTempRepository->saveManyData($tempData);
            }
            if ($resultsFlg) {
                $resultsFlg = $this->updateTargetAreaGenre($corpId, $data);
                if ($resultsFlg) {
                    $resultsFlg = $this->createAffiliationAreaStats($corpId);
                } else {
                    $resultsFlg = false;
                }
            } else {
                $resultsFlg = false;
            }
        } catch (Exception $e) {
            $resultsFlg = false;
        }
        return $resultsFlg;
    }

    /**
     * format data corp category form corp category temp
     * @param  array $tempData
     * @param  array $data
     */
    public function formatDataCorpCategoryFormCorpCategoryTemp(&$tempData, &$data)
    {
        foreach ($tempData as $key => $val) {
            $findCount = $this->affiliationAgreementFormat->countByCategoryIdAndGenreId($val->category_id, $val->genre_id);
            if (empty($findCount)) {
                continue;
            }
            if ($val->delete_flag) {
                $tempData[$key]->action = 'Delete';
                continue;
            }
            $arrayField = $this->mCorpCategoryRepository->getArrayFieldCategory();
            $data[] = $this->affiliationAgreementFormat->getDataCategoryFromCategoryTemp($arrayField, $val);
            if (isset($val->mcc_id)) {
                $action = $this->affiliationAgreementFormat->checkUpdateCategory($val);
                $action = empty($action) ? null : $action;
            } else {
                $action = 'Add';
            }
            $tempData[$key]->action = $action;
        }
    }
    /**
     * update category genre
     *
     * @param  integer $id
     * @param  array   $data
     * @return boolean
     */
    private function updateCorpCategoryGenre($id, $data)
    {
        $resultsFlg = false;
        $count = 0;
        $delMccIdArray = $this->mCorpCategoryRepository->getListIdByCorpId($id);
        foreach ($data as $v) {
            if (!isset($v['category_id'])) {
                continue;
            }
            $saveData [] = $v;
            foreach ($delMccIdArray as $key => $value) {
                if ($value == $v['id']) {
                    unset($delMccIdArray[$key]);
                    break;
                }
            }
            $resultsFlg = $this->affiliationAgreementFormat->checkEditCorpCategory($data, $v['category_id'], $count);
            if (!$resultsFlg) {
                return false;
            }
            $count++;
        }
        $this->mCorpCategoryRepository->deleteListItemByArrayId($delMccIdArray);
        if (!empty($saveData)) {
            if ($resultsFlg) {
                if ($this->mCorpCategoryRepository->updateManyItemWithArray($saveData)) {
                    return true;
                }
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * update target area genre
     *
     * @param $id
     * @param $data
     * @return bool
     * @throws Exception
     */
    private function updateTargetAreaGenre($id, $data)
    {
        $delMccIdArray = $this->mCorpCategoryRepository->getListIdByCorpId($id);
        $saveData = [];
        $this->getDelMccArrayId($data, $delMccIdArray);
        $this->mTargetAreaRepository->deleteListItemByArrayCorpCategoryId($delMccIdArray);
        $corpAreas = $this->affiliationAgreementFormat->getListByCorpId($id);
        $idList = $this->mCorpCategoryRepository->getItemByCorpId($id);
        foreach ($idList as $val) {
            $areaCount = $this->mTargetAreaRepository->getCorpCategoryTargetAreaCount($val->id);
            if ($areaCount > 0) {
                continue;
            }
            foreach ($corpAreas as $area) {
                $setData = [];
                $setData['corp_category_id'] = $val->id;
                $setData['jis_cd'] = $area->jis_cd;
                $setData['created'] = date('Y-m-d H:i:s');
                $setData['created_user_id'] = Auth::user()->user_id;
                $setData['modified'] = date('Y-m-d H:i:s');
                $setData['modified_user_id'] = Auth::user()->user_id;
                $saveData[] = $setData;
            }
            $listMCorpCategory = $this->mCorpCategoryRepository->getAllByCorpIdAndGenreId(
                $id,
                $val->genre_id
            );
            foreach ($listMCorpCategory as $mCorpCategory) {
                if ($mCorpCategory->target_area_type > 0) {
                    $this->mCorpCategoryRepository->updateCorpCategoryTargetAreaType(
                        $mCorpCategory->id,
                        $mCorpCategory->target_area_type
                    );
                    break;
                }
            }
        }
        if (count($saveData)) {
            if (!$this->mTargetAreaRepository->insert($saveData)) {
                return false;
            }
        }
        return true;
    }

    /**
     * get del mcc array id
     * @param  array $data
     * @param  array $delMccIdArray
     */
    public function getDelMccArrayId($data, &$delMccIdArray)
    {
        foreach ($data as $v) {
            if (!isset($v['category_id'])) {
                continue;
            }
            foreach ($delMccIdArray as $key => $value) {
                if ($value == $v['id']) {
                    unset($delMccIdArray[$key]);
                    break;
                }
            }
        }
    }
    /**
     * create affiliation area stats
     *
     * @param  integer $id
     * @return boolean
     */
    private function createAffiliationAreaStats($id)
    {
        $listMCC = $this->mCorpCategoryRepository->getListByCorpIdAndAffiliationStatus($id);
        foreach ($listMCC as $mcc) {
            for ($i = 1; $i <= 47; $i++) {
                $data = $this->affiliationAreaStatRepository->findByCorpIdAndGenerIdAndPrefecture(
                    $mcc->id,
                    $mcc->genre_id,
                    $i
                );
                if (count($data) === 0) {
                    $dataAAS = [];
                    $dataAAS['corp_id'] = $mcc->id;
                    $dataAAS['genre_id'] = $mcc->genre_id;
                    $dataAAS['prefecture'] = $i;
                    $dataAAS['commission_count_category'] = 0;
                    $dataAAS['orders_count_category'] = 0;
                    $dataAAS['created'] = date("Y/m/d H:i:s", time());
                    $dataAAS['created_user_id'] = Auth::user()['user_id'];
                    $dataAAS['commission_unit_price_rank'] = 'z';
                    if (!$this->affiliationAreaStatRepository->insert($dataAAS)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * send agreement mail
     * @param $corpData
     * @return bool
     */
    public static function sendAgreementEmail($corpData)
    {
        try {
            $addrArr = [];
            if (!empty($corpData->mailaddress_pc)) {
                $tmpAddrs = explode(";", $corpData->mailaddress_pc);
                foreach ($tmpAddrs as $address) {
                    $addrArr[] = $address;
                }
            }
            if (!empty($corpData->mailaddress_mobile)) {
                $tmpAddrs = explode(";", $corpData->mailaddress_mobile);
                foreach ($tmpAddrs as $address) {
                    $addrArr[] = $address;
                }
            }
            if (count($addrArr)) {
                $toAddr = $addrArr;
            } else {
                return true;
            }
            $corpName = $corpData->official_corp_name;
            $fromST = config('rits.agreement_alert_mail_setting.from_address');
            $subjectST = config('rits.agreement_alert_mail_setting.title');
            foreach ($toAddr as $toAddress) {
                $dataST = [
                    'subject' => $subjectST,
                    'from' => $fromST,
                    'to' => $toAddress,
                ];
                MailHelper::sendMail($dataST['to'], new Agreementponsibility($dataST, $corpName));
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * update reconfirmation
     *
     * @param  integer $corpId
     * @return view
     * @throws Exception
     */
    public function updateReconfirmation($corpId)
    {
        DB::beginTransaction();
        try {
            $corpAgreementCnt = $this->corpAgreementRepository->getCountByCorpIdAndStatus($corpId);
            $corpAgreement    = $this->corpAgreementRepository->getFirstByCorpIdAndAgreementId($corpId, null, true);
            $agreement        = $this->affiliationAgreementFormat->findLastAgreement();
            $agreementId      = null;
            if ((empty($corpAgreementCnt) || $corpAgreementCnt == 0)
                && $corpAgreement && $agreement
            ) {
                $dataCorpAgreement['corp_kind']            = $corpAgreement->corp_kind;
                $dataCorpAgreement['agreement_history_id'] = $agreement->last_history_id;
                $dataCorpAgreement['ticket_no']            = $agreement->ticket_no;
                $dataCorpAgreement['status']               = 'Reconfirmation';
                $dataCorpAgreement['corp_id']              = $corpId;
                $resultInsertCorpAgreement                 = $this->corpAgreementRepository->insertOrUpdateItem($dataCorpAgreement);
                if (!$resultInsertCorpAgreement['status']) {
                    throw new Exception();
                }
            }
            if (!empty($resultInsertCorpAgreement['item'])) {
                $agreementId = $resultInsertCorpAgreement['item']->id;
                $dataCorpAgreementTempLink['corp_id']           = $corpId;
                $dataCorpAgreementTempLink['corp_agreement_id'] = $agreementId;
                if (!$this->corpAgrTempLinkRepository->insertOrUpdateItem($dataCorpAgreementTempLink)['status']) {
                    throw new Exception();
                }
            }
            DB::commit();
            $result['message'] = Lang::get('agreement.update_is_completed');
            $result['class'] = 'box--success';
            $result['agreement_id'] = $agreementId;
            return $result;
        } catch (Exception $ex) {
            DB::rollback();
            throw new Exception();
        }
    }

    /**
     * agreement upload file
     *
     * @param  object  $request
     * @param  integer $corpId
     * @param  integer $corpAgreementId
     * @return array
     */
    public function agreementUploadFile($request, $corpId, $corpAgreementId = null)
    {
        return $this->affiliationAgreementUpload->agreementUploadFile($request, $corpId, $corpAgreementId);
    }

    /**
     * check file exists
     *
     * @param  $corpId
     * @param  object $file
     * @return boolean
     */
    public static function checkFileExists($corpId, $file)
    {
        $array = explode('.', $file->name);
        $fileExtension = array_pop($array);
        $pathFile = $corpId . '/' . $file->id . '.' . $fileExtension;
        if (Storage::exists($pathFile)) {
            return true;
        }
        return false;
    }
}
