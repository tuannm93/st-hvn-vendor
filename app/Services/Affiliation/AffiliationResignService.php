<?php

namespace App\Services\Affiliation;

use App\Repositories\ApprovalRepositoryInterface;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\CorpCategoryApplicationRepositoryInterface;
use App\Repositories\CorpCategoryGroupApplicationRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AffiliationResignService extends BaseService
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorps;

    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategories;

    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTemp;

    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    protected $mCorpTargetArea;

    /**
     * @var MTargetAreaRepositoryInterface
     */
    protected $mTargetArea;

    /**
     * @var CorpCategoryGroupApplicationRepositoryInterface
     */
    protected $corpCategoryGroupApp;

    /**
     * @var CorpCategoryApplicationRepositoryInterface
     */
    protected $corpCategoryApp;

    /**
     * @var CorpAgreementTempLinkRepositoryInterface
     */
    protected $corpAgreementTempLink;

    /**
     * @var ApprovalRepositoryInterface
     */
    protected $approval;

    /**
     * @var AffiliationGenreResigningService
     */
    protected $affGenreResignService;

    /**
     * AffiliationResignService constructor.
     *
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTemp
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLink
     * @param AffiliationGenreResigningService $affGenreResignService
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetArea
     * @param MTargetAreaRepositoryInterface $mTargetAreaRepository
     * @param CorpCategoryGroupApplicationRepositoryInterface $corpCategoryGroupApp
     * @param CorpCategoryApplicationRepositoryInterface $corpCategoryApp
     * @param ApprovalRepositoryInterface $approvalInterface
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTemp,
        CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLink,
        AffiliationGenreResigningService $affGenreResignService,
        MCorpTargetAreaRepositoryInterface $mCorpTargetArea,
        MTargetAreaRepositoryInterface $mTargetAreaRepository,
        CorpCategoryGroupApplicationRepositoryInterface $corpCategoryGroupApp,
        CorpCategoryApplicationRepositoryInterface $corpCategoryApp,
        ApprovalRepositoryInterface $approvalInterface
    ) {
        $this->mCorps = $mCorpRepository;
        $this->mCorpCategories = $mCorpCategoryRepository;
        $this->mCorpCategoriesTemp = $mCorpCategoriesTemp;
        $this->corpAgreementTempLink = $corpAgreementTempLink;
        $this->affGenreResignService = $affGenreResignService;
        $this->mCorpTargetArea = $mCorpTargetArea;
        $this->mTargetArea = $mTargetAreaRepository;
        $this->corpCategoryGroupApp = $corpCategoryGroupApp;
        $this->corpCategoryApp = $corpCategoryApp;
        $this->approval = $approvalInterface;
    }

    /**
     * data for render view
     *
     * @param integer $idCorp
     * @return mixed
     */
    public function prepareDataForView($idCorp)
    {
        $tempId = null;
        $resultTempLink = $this->corpAgreementTempLink->getFirstIdByCorpId($idCorp);
        if (! empty($resultTempLink)) {
            $tempId = $resultTempLink['id'];
        }
        $data['bAllowShowReconfirm'] = $this->affGenreResignService->isShowReconfirm($idCorp);
        $corpData = $this->mCorps->getAllInformationById($idCorp);
        $data['infoCorp'] = $corpData;
        $data['tempId'] = $tempId;
        $cateList = $this->getGenreList($idCorp, $tempId);
        $listBasicCateA = $this->separateListArea($cateList['basic'], 1);
        $listIndividualCateA = $this->separateListArea($cateList['individual'], 1);
        $listBasicCateB = $this->separateListArea($cateList['basic'], 2);
        $listIndividualCateB = $this->separateListArea($cateList['individual'], 2);
        $data['listCateA'] = [
            'basic' => $listBasicCateA,
            'individual' => $listIndividualCateA,
        ];
        $data['listCateB'] = [
            'basic' => $listBasicCateB,
            'individual' => $listIndividualCateB,
        ];
        $modified1 = $this->getLastModified($listBasicCateA);
        $modified2 = $this->getLastModified($listIndividualCateA);

        $data['lastModified'] = $modified1 != '' ? $modified1 : $modified2;
        $data['listFeeUnit'] = ['' => __('affiliation_resign.none')] + __('affiliation_resign.fee_div');
        $data['listCorpCommisionType'] = __('affiliation_resign.list_corp_commission_type');

        return $data;
    }

    /**
     * get list genre to show on view
     *
     * @param integer $idCorp
     * @param integer $idTemp
     * @return array
     */
    private function getGenreList($idCorp, $idTemp)
    {
        $corpTargetArea = $this->mCorpTargetArea->getListByCorpId($idCorp, true);
        $corpTargetAreaCount = count($corpTargetArea);
        $listCategoryGenre = $this->affGenreResignService->findCategoryTempCopy($idCorp, $idTemp)->toArray();
        $registerData = array_filter($listCategoryGenre, function ($arr) {
            if (! empty($arr['id'])) {
                return true;
            }
            return false;
        });
        $listCustomArea = [];
        $listNormalArea = [];
        foreach ($listCategoryGenre as $obj) {
            $customFlg = false;
            $mstedtFlg = false;

            if ($obj['target_area_type'] == 0) {
                $check = $this->checkTargetAreaType($idCorp, $obj, $corpTargetAreaCount, $customFlg);
                $customFlg = $check['customFlg'];
                $mstedtFlg = $check['mstedtFlg'];
            }

            if ($obj['target_area_type'] != 0 && $obj['target_area_type'] == 2) {
                $customFlg = true;
            }

            list($listCustomArea, $listNormalArea) = $this->getDataForCorpCategoriesTemp(
                $listCustomArea,
                $listNormalArea,
                $obj,
                $customFlg,
                $mstedtFlg,
                $registerData
            );
        }
        $data = [
            'basic' => $listNormalArea,
            'individual' => $listCustomArea,
        ];

        return $data;
    }

    /**
     * @param integer $idCorp
     * @param object $obj
     * @param integer $corpTargetAreaCount
     * @param boolean $customFlg
     * @return array
     */
    private function checkTargetAreaType($idCorp, $obj, $corpTargetAreaCount, $customFlg)
    {
        $mstedtFlg = true;
        $corpCate = $this->mCorpCategories->findLastIdByCorpIdAndCategoryId($idCorp, $obj['category_id']);
        if (! empty($corpCate)) {
            $targetAreaCount = $this->mTargetArea->getCorpCategoryTargetAreaCount($corpCate->id);

            if ($targetAreaCount != $corpTargetAreaCount) {
                $customFlg = true;
            }
            if (! empty($corpTargetArea)) {
                foreach ($corpTargetArea as $areaObj) {
                    $areaCount = $this->mTargetArea->getCorpCategoryTargetAreaCount($corpCate->id, $areaObj['jis_cd']);
                    if ($areaCount <= 0) {
                        $customFlg = true;
                        break;
                    }
                }
            }
        }

        return ['customFlg' => $customFlg, 'mstedtFlg' => $mstedtFlg];
    }

    /**
     * @param array $listCustomArea
     * @param array $listNormalArea
     * @param object $obj
     * @param boolean $customFlg
     * @param boolean $mstedtFlg
     * @param array $registerData
     * @return array
     */
    private function getDataForCorpCategoriesTemp(
        $listCustomArea,
        $listNormalArea,
        $obj,
        $customFlg,
        $mstedtFlg,
        $registerData
    ) {
        if ($customFlg == true) {
            $listCustomArea[] = $obj;
            if ($mstedtFlg && ! empty($registerData)) {
                $this->mCorpCategoriesTemp->updateTargetAreaType($obj['id'], 2);
            }
        } else {
            $listNormalArea[] = $obj;
            if ($mstedtFlg && ! empty($registerData)) {
                $this->mCorpCategoriesTemp->updateTargetAreaType($obj['id'], 1);
            }
        }

        return [$listCustomArea, $listNormalArea];
    }

    /**
     * filter list by corp_commission_type and disable_flg = false
     *
     * @param array $listArea
     * @param string $type
     * @return array
     */
    private function separateListArea($listArea, $type)
    {
        $listResult = array_filter($listArea, function ($arr) use ($type) {
            if ($arr['corp_commission_type'] == $type && $arr['disable_flg'] == false) {
                return true;
            }

            return false;
        });

        return $listResult;
    }

    /**
     * get last modified
     *
     * @param array $listCategories
     * @return string
     */
    private function getLastModified($listCategories)
    {
        $lastModified = '';

        foreach ($listCategories as $arr) {
            if (isset($arr['modified']) && (strtotime($arr['modified']) > strtotime(__('affiliation_resign.time_default')))) {
                $lastModified = $arr['modified'];
            }
        }
        return $lastModified;
    }

    /**
     * update change info
     *
     * @param Request $request
     * @return array
     */
    public function updateResign(Request $request)
    {
        $corpId = $request->get('corpId');
        $sData = $request->get('listData');
        if ($sData && strlen(trim($sData)) > 0) {
            $listData = json_decode($sData, true);
            $resultValidate = $this->validateRequest($listData);
            if ($resultValidate['validate']) {
                $firstObj = reset($listData);
                if ($this->checkModifiedCategoryTemp($firstObj['id'], $firstObj['modified'])) {
                    try {
                        DB::beginTransaction();
                        $idTemp = $this->affGenreResignService->getTempIdFromCorpIdInCorpAgreementTempLink($corpId);
                        /*fail update to m_corp_categories_temp*/
                        $this->copyCorpCategoryByTempId($corpId, $idTemp);
                        $targetData = [];
                        foreach ($listData as $arr) {
                            if (! empty($arr['application_check'])) {
                                $targetData[] = $arr;
                            }
                        }
                        $bResult = $this->addCorpCategoryApp($corpId, $targetData);
                        DB::commit();
                        if ($bResult) {
                            $this->saveMessage(true);
                        } else {
                            $this->saveMessage(false, __('affiliation_resign.message_error_input_error'));
                        }
                    } catch (Exception $ex) {
                        DB::rollBack();
                        $this->saveMessage(false);
                    }
                }
            } else {
                $this->saveMessage(false, $resultValidate['msg']);
            }
        } else {
            $this->saveMessage(true);
        }

        return ['code' => 'END'];
    }

    /**
     * validate request data update
     *
     * @param array $listData
     * @return array
     */
    private function validateRequest($listData)
    {
        $bValidate = true;
        $errMsg = '';
        if (is_array($listData) && count($listData) > 0) {
            foreach ($listData as $arr) {
                if (! empty(trim($arr['order_fee'])) && $arr['corp_commission_type'] != 2 && strlen(trim($arr['order_fee_unit'])) == 0) {
                    $bValidate = false;
                    $errMsg = __('affiliation_resign.message_error_miss_unit');
                    break;
                }
                if (! empty($arr['application_check']) && empty($arr['application_reason'])) {
                    $bValidate = false;
                    $errMsg = __('affiliation_resign.message_error_miss_reason');
                    break;
                }
                if (strlen(trim($arr['order_fee'])) == 0) {
                    $bValidate = false;
                    $errMsg = __('affiliation_resign.message_error_miss_commission');
                    break;
                }
                //Never come here because select only have 2 option with value
                if (empty($arr['corp_commission_type'])) {
                    $bValidate = false;
                    $errMsg = __('affiliation_resign.message_error_miss_order_from');
                    break;
                }
            }
        }

        return ['validate' => $bValidate, 'msg' => $errMsg];
    }

    /**
     * check modified
     *
     * @param integer $id
     * @param string $dateModified
     * @return boolean
     */
    private function checkModifiedCategoryTemp($id, $dateModified)
    {
        if (empty($id)) {
            return true;
        }
        $results = $this->mCorpCategoriesTemp->getById($id);
        if (isset($results['modified'])) {
            if ($dateModified == $results['modified']) {
                return true;
            }
        }

        return false;
    }

    /**
     * IMPORTANT not update info of item in m_corp_categories_temp
     * insert data to m_corp_categories_temp
     *
     * @param integer $corpId
     * @param integer $tempId
     * @return boolean
     */
    private function copyCorpCategoryByTempId($corpId, $tempId)
    {
        $result = $this->affGenreResignService->findCategoryTempCopy($corpId, $tempId)->toArray();
        $bRegistered = false;
        foreach ($result as $arr) {
            if (! empty($arr['id'])) {
                $bRegistered = true;
                break;
            }
        }
        if (! $bRegistered) {
            $bSaved = true;
            foreach ($result as $obj) {
                $bSaved = $this->mCorpCategoriesTemp->saveWithData($obj);
                if (! $bSaved) {
                    break;
                }
            }

            return $bSaved;
        } else {
            return true;
        }
    }

    /**
     * add to 3 tables: corp_category_group_app, corp_category_app, approval
     *
     * @param integer $corpId
     * @param array $data
     * @return bool
     */
    private function addCorpCategoryApp($corpId, $data)
    {
        try {
            if (empty($data) || empty($corpId)) {
                $this->saveMessage(false, __('affiliation_resign.message_error_data_not_exist'));

                return false;
            }
            $time = date('Y-m-d H:i:s');
            $saveGroup['corp_id'] = $corpId;
            $saveGroup['created'] = $time;
            $saveGroup['created_user_id'] = \Auth::user()->user_id;
            $saveGroup['modified'] = $time;
            $saveGroup['modified_user_id'] = \Auth::user()->user_id;
            $newGroupId = $this->corpCategoryGroupApp->insertGetId($saveGroup);
            if (empty($newGroupId)) {
                $this->saveMessage(false, __('affiliation_resign.message_error_save_corp_cate_group_app'));

                return false;
            }
            foreach ($data as $arr) {
                $newCorpCateAppObj = [];
                $newCorpCateAppObj['corp_id'] = $corpId;
                $newCorpCateAppObj['group_id'] = $newGroupId;
                $newCorpCateAppObj['category_id'] = $arr['category_id'];
                if ($arr['corp_commission_type'] != 2) {
                    $newCorpCateAppObj['introduce_fee'] = null;
                    $newCorpCateAppObj['order_fee'] = $arr['order_fee'];
                    $newCorpCateAppObj['order_fee_unit'] = $arr['order_fee_unit'];
                } else {
                    $newCorpCateAppObj['introduce_fee'] = $arr['order_fee'];
                    $newCorpCateAppObj['order_fee'] = null;
                    $newCorpCateAppObj['order_fee_unit'] = null;
                }
                $newCorpCateAppObj['created'] = $time;
                $newCorpCateAppObj['created_user_id'] = \Auth::user()->user_id;
                $newCorpCateAppObj['modified'] = $time;
                $newCorpCateAppObj['modified_user_id'] = \Auth::user()->user_id;
                $newCorpCateAppObj['genre_id'] = $arr['genre_id'];
                $newCorpCateAppObj['corp_commission_type'] = $arr['corp_commission_type'];
                $newCorpCateAppObj['note'] = $arr['note'];
                $corpCateAppId = $this->corpCategoryApp->insertGetId($newCorpCateAppObj);

                if (empty($corpCateAppId)) {
                    $this->saveMessage(false, __('affiliation_resign.message_error_save_corp_cate_app'));
                    return false;
                }

                $approvalObj = [
                    'relation_application_id' => $corpCateAppId,
                    'application_section' => 'CorpCategoryApplication',
                    'application_reason' => $arr['application_reason'],
                    'application_user_id' => \Auth::user()->user_id,
                    'application_datetime' => $time,
                    'created' => $time,
                    'modified' => $time,
                    'created_user_id' => \Auth::user()->user_id,
                    'modified_user_id' => \Auth::user()->user_id,
                    'status' => -1,
                ];
                $bResult = $this->approval->insert($approvalObj);
                if (! $bResult) {
                    $this->saveMessage(false, __('affiliation_resign.message_error_save_approval'));

                    return false;
                }
            }

            return true;
        } catch (\Exception $ex) {
            $this->saveMessage(false, __('affiliation_resign.message_error_insert_fail'));

            return false;
        }
    }

    /**
     * save message to flash for show error or success action
     *
     * @param  boolean $bSuccess
     * @param  string $message
     */
    private function saveMessage($bSuccess, $message = null)
    {
        if ($message != null) {
            if (strlen(trim($message)) > 0) {
                if ($bSuccess) {
                    \Session::flash(__('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN_SUCCESS'), $message);
                } else {
                    \Session::flash(__('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN'), $message);
                }
            }
        } else {
            if ($bSuccess) {
                \Session::flash(__('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN_SUCCESS'), __('affiliation_resign.message_success_apply_fee'));
            } else {
                \Session::flash(__('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN'), __('affiliation_resign.message_error_insert_fail'));
            }
        }
    }

    /**
     * Update m_corp_categories_temp
     *
     * @param  Request $request
     * @return array
     */
    public function updateReconfirm(Request $request)
    {
        $kind = 'WEB';
        $isFax = (bool) $request->get('isFax');
        if ($isFax) {
            $kind = 'FAX';
        }
        $corpId = $request->get('corpId');
        try {
            $idTemp = $this->affGenreResignService->getTempIdFromCorpIdInCorpAgreementTempLink($corpId);
            $count = $this->mCorpCategoriesTemp->countByCorpIdAndTempId($corpId, $idTemp);
            if ($count == 0) {
                $tempData = $this->affGenreResignService->findCategoryTempCopy($corpId, $idTemp);
                if (! empty($tempData)) {
                    foreach ($tempData as $obj) {
                        $this->mCorpCategoriesTemp->saveWithData($obj);
                    }
                }
            }
            $result = $this->affGenreResignService->updateReconfirmResign($corpId, $kind);
            DB::commit();
            if ($result) {
                $this->saveMessage(true);
            } else {
                $this->saveMessage(false);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->saveMessage(false);
        }

        return ['code' => 'END'];
    }
}
