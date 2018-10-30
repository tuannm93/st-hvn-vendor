<?php

namespace App\Services\Affiliation;

use App\Models\CorpAgreementTempLink;
use App\Repositories\AgreementRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\MCategoryCopyRuleRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Services\BaseService;
use DB;
use Illuminate\Support\Facades\Session;

class AffiliationGenreResigningService extends BaseService
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorps;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenres;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategories;
    /**
     * @var MCategoryCopyRuleRepositoryInterface
     */
    protected $mCategoryCopyRules;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategories;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTemp;
    /**
     * @var CorpAgreementTempLinkRepositoryInterface
     */
    protected $corpAgreementTempLink;
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreement;
    /**
     * @var AgreementRepositoryInterface
     */
    protected $agreement;

    /**
     * AffiliationGenreResigningService constructor.
     * @param MCorpRepositoryInterface $mCorpRepositoryInterface
     * @param MGenresRepositoryInterface $mGenresRepositoryInterface
     * @param MCategoryRepositoryInterface $mCategoryRepositoryInterface
     * @param AgreementRepositoryInterface $agreementRepositoryInterface
     * @param MCategoryCopyRuleRepositoryInterface $mCategoryCopyRuleInterface
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepoInterface
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempInterface
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkInterface
     * @param CorpAgreementRepositoryInterface $corpAgreementInterface
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepositoryInterface,
        MGenresRepositoryInterface $mGenresRepositoryInterface,
        MCategoryRepositoryInterface $mCategoryRepositoryInterface,
        AgreementRepositoryInterface $agreementRepositoryInterface,
        MCategoryCopyRuleRepositoryInterface $mCategoryCopyRuleInterface,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepoInterface,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempInterface,
        CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkInterface,
        CorpAgreementRepositoryInterface $corpAgreementInterface
    ) {
        $this->mCorps = $mCorpRepositoryInterface;
        $this->mCategories = $mCategoryRepositoryInterface;
        $this->mGenres = $mGenresRepositoryInterface;
        $this->mCorpCategories = $mCorpCategoryRepoInterface;
        $this->mCorpCategoriesTemp = $mCorpCategoriesTempInterface;
        $this->corpAgreementTempLink = $corpAgreementTempLinkInterface;
        $this->corpAgreement = $corpAgreementInterface;
        $this->mCategoryCopyRules = $mCategoryCopyRuleInterface;
        $this->agreement = $agreementRepositoryInterface;
    }

    /**
     * @param integer $idCorp
     * @return bool | array
     */
    public function prepareDataForView($idCorp)
    {
        $tempId = null;
        $resultTempLink = $this->corpAgreementTempLink->getFirstIdByCorpId($idCorp);
        if (!empty($resultTempLink)) {
            $tempId = $resultTempLink['id'];
        }
        $data['bAllowShowReconfirm'] = $this->isShowReconfirm($idCorp);
        $listCategoryGenre = $this->getAllCategoryTemp($idCorp, $tempId);
        $titleList = \Config::get('datacustom.genre_group');
        $listCateGenreType1 = array_filter(
            $listCategoryGenre,
            function ($obj) {
                return $obj['m_genres_commission_type'] == 1;
            }
        );
        $listCateGenreType2 = array_filter(
            $listCategoryGenre,
            function ($obj) {
                return $obj['m_genres_commission_type'] == 2;
            }
        );
        $listCateGenreType3 = array_filter(
            $listCategoryGenre,
            function ($obj) {
                return $obj['m_genres_commission_type'] == 3;
            }
        );
        $listType1Group = $this->groupListCateGenreById($titleList, $listCateGenreType1);
        $listType2Group = $this->groupListCateGenreById($titleList, $listCateGenreType2);
        $listType3Group = $this->groupListCateGenreById($titleList, $listCateGenreType3);
        $modifiedDate = __('affiliation_genre_resign.time_default');
        $listCheckedCategoriesTempId = [];
        $this->editDataByGroup($listType1Group, $modifiedDate, $listCheckedCategoriesTempId);
        $this->editDataByGroup($listType2Group, $modifiedDate, $listCheckedCategoriesTempId);
        $this->editDataByGroup($listType3Group, $modifiedDate, $listCheckedCategoriesTempId);
        $data['listCommissionType1'] = $listType1Group;
        $data['listCommissionType2'] = $listType2Group;
        $data['listCommissionType3'] = $listType3Group;
        $data['modifiedDate'] = $modifiedDate;
        $corpData = $this->mCorps->getAllInformationById($idCorp);
        $data['inforCorp'] = $corpData;
        $mediationGenre = $this->mGenres->getListByMediation();
        $data['mediationGenre'] = $mediationGenre;
        $data['checkedCategoriesTempId'] = implode('-', $listCheckedCategoriesTempId);
        return $data;
    }

    /**
     * @param integer $idCorp
     * @return bool
     */
    public function isShowReconfirm($idCorp)
    {
        $bCanResign = true;
        $resultCorpAgreement = $this->corpAgreement->findByCorpId($idCorp);
        if (!empty($resultCorpAgreement['status'])) {
            if ($resultCorpAgreement['status'] == 'Complete' || $resultCorpAgreement['status'] == 'Application') {
                $bCanResign = true;
            } else {
                $bCanResign = false;
            }
        }
        return $bCanResign;
    }

    /**
     * @param integer $idCorp
     * @param integer $tempId
     * @return array
     */
    public function getAllCategoryTemp($idCorp, $tempId)
    {
        $limit = empty($tempId) ? 1 : 2;
        $countCorpCategoriesTemp = $this->mCorpCategoriesTemp->countByCorpIdAndTempId($idCorp, $tempId);
        if ($countCorpCategoriesTemp == 0) {
            $resultTempLink = $this->corpAgreementTempLink->getItemByCorpIdAndLimit($idCorp, $limit);
            if (count($resultTempLink) == $limit) {
                $lastElement = end($resultTempLink);
                $result = $this->mCategories->getAllTempCategory($idCorp, $lastElement['id']);
                foreach ($result as &$obj) {
                    $obj['check'] = "";
                    if (isset($obj['m_corp_categories_temp_id'])) {
                        $obj['m_corp_categories_temp_id'] = null;
                        $obj['check'] = 'checked';
                        $obj['init'] = true;
                    }
                }
            } else {
                $result = $this->mCategories->getAllCategoryByCorpId($idCorp);
                foreach ($result as &$obj) {
                    $this->formatCategory($obj);
                }
            }
        } else {
            $result = $this->mCategories->getAllTempCategory($idCorp, $tempId);
            foreach ($result as &$obj) {
                $obj['check'] = "";
                if (isset($obj['m_corp_categories_temp_id'])) {
                    $obj['check'] = 'checked';
                    $obj['init'] = true;
                }
            }
        }
        return $result;
    }

    /**
     * format category data
     * @param array $item
     */
    private function formatCategory(&$item)
    {
        $item['m_corp_categories_temp_id'] = null;
        $item['m_corp_categories_temp_modified'] = null;
        $item['m_corp_categories_temp_select_list'] = null;
        $item['check'] = "";
        if (isset($item['m_corp_categories_id'])) {
            $item['m_corp_categories_temp_id'] = null;
            $item['m_corp_categories_temp_modified'] = $item['m_corp_categories_modified'];
            $item['m_corp_categories_temp_select_list'] = $item['m_corp_categories_select_list'];
            $item['m_corp_categories_temp_corp_commission_type'] =
                isset($item['m_corp_categories_corp_commission_type']) ?
                    $item['m_corp_categories_corp_commission_type'] : null;
            $item['check'] = 'checked';
            $item['init'] = true;
        }
    }

    /**
     * @param array $listNameIdGenre
     * @param array $listData
     * @return array
     */
    private function groupListCateGenreById($listNameIdGenre, $listData)
    {
        $result = [];
        foreach ($listNameIdGenre as $key => $value) {
            foreach ($listData as $obj) {
                if ($obj['m_genres_genre_group'] == $key) {
                    $result[$value][] = $obj;
                }
            }
        }
        return $result;
    }

    /**
     * @param array $listData
     * @param string $modifiedDate
     * @param array $listCheckedCategoriesTempId
     */
    private function editDataByGroup(&$listData, &$modifiedDate, &$listCheckedCategoriesTempId)
    {
        foreach ($listData as $key => $list) {
            if (is_array($list) && count($list) > 0) {
                $tempList = [];
                foreach ($list as $subKey => &$obj) {
                    $this->setModifiedDate($obj, $modifiedDate);
                    $obj['chk_value'] = $obj['m_genres_id'] . '-' . $obj['m_categories_id'];
                    if (!empty($obj['init']) && $obj['init']) {
                        $obj['chk_value'] = $obj['m_genres_id'] . '-' . $obj['m_categories_id']
                            . '-' . $obj['m_corp_categories_temp_id'];
                        if (isset($obj['m_corp_categories_temp_id'])
                            && strlen(trim($obj['m_corp_categories_temp_id'])) > 0
                        ) {
                            $listCheckedCategoriesTempId[] = $obj['m_corp_categories_temp_id'];
                        }
                    }
                    $tempList[$subKey % 3][] = $obj;
                }
                $listData[$key] = $tempList;
            }
        }
    }

    /**
     * get modified date
     * @param array $item
     * @param string $modifiedDate
     */
    private function setModifiedDate($item, &$modifiedDate)
    {
        if (isset($item['m_corp_categories_temp_modified'])
            && (strtotime($item['m_corp_categories_temp_modified']) > strtotime($modifiedDate))
        ) {
            $modifiedDate = $item['m_corp_categories_temp_modified'];
        }
    }

    /**
     * @param array $dataRequest
     * @return array
     */
    public function updateGenreResign($dataRequest)
    {
        $bValidate = $this->validateGenreResign($dataRequest['listCategories']);
        if ($bValidate) {
            try {
                DB::beginTransaction();
                $idTemp = $this->getTempIdFromCorpIdInCorpAgreementTempLink($dataRequest['idCorp']);
                $result = $this->editCategoriesTemp($dataRequest, $idTemp);
                DB::commit();
                if ($result) {
                    Session::flash(
                        __('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN_SUCCESS'),
                        __('affiliation_resign.message_update_complete')
                    );
                    return ['code' => 'SUCCESS'];
                } else {
                    return ['code' => 'FAIL'];
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return ['code' => 'FAIL'];
            }
        }
        return ['code' => 'FAIL'];
    }

    /**
     * @param array $listCategories
     * @return boolean
     */
    private function validateGenreResign($listCategories)
    {
        $bValidate = true;
        if (is_array($listCategories) && count($listCategories) > 0) {
            foreach ($listCategories as $obj) {
                $bValidate = $this->isValidateMissSelectOption($obj);
                if (!$bValidate) {
                    break;
                }

                $bValidate = $this->isValidateMissChecked($obj);
                if (!$bValidate) {
                    break;
                }
            }
        }
        return $bValidate;
    }

    /**
     * @param object $obj
     * @return bool
     */
    private function isValidateMissSelectOption($obj)
    {
        $bValidate = true;
        if (isset($obj->category_id) && empty($obj->selectOption)) {
            $idGenre = explode('-', $obj->category_id)[0];
            if (strlen(trim($idGenre)) > 0) {
                $genreData = $this->mGenres->find($idGenre);
                if ($genreData->commission_type != 2) {
                    \Session::flash(
                        __('affiliation_genre_resign.KEY_SESSION_ERROR_GENRE_RESIGN'),
                        __('affiliation_genre_resign.error_miss_select_option')
                    );
                    $bValidate = false;
                }
            }
        }

        return $bValidate;
    }

    /**
     * @param object $obj
     * @return bool
     */
    private function isValidateMissChecked($obj)
    {
        $bValidate = true;
        if (!isset($obj->category_id) && !empty($obj->selectOption)) {
            \Session::flash(
                __('affiliation_genre_resign.KEY_SESSION_ERROR_GENRE_RESIGN'),
                __('affiliation_genre_resign.error_miss_checked')
            );
            $bValidate = false;
        }

        return $bValidate;
    }

    /**
     * get id if exist idCorp else insert and get id just insert
     *
     * @param integer $idCorp
     * @return mixed
     */
    public function getTempIdFromCorpIdInCorpAgreementTempLink($idCorp)
    {
        $tempResult = $this->corpAgreementTempLink->getTempLink($idCorp);
        if (empty($tempResult) || $tempResult->status == 'Complete' || $tempResult->status == 'Application') {
            $idTemp = $this->corpAgreementTempLink->insertAndGetIdBack($idCorp, null);
        } else {
            $idTemp = $tempResult->id;
        }
        return $idTemp;
    }

    /**
     * @param array $dataRequest
     * @param integer $idTemp
     * @return bool
     */
    private function editCategoriesTemp($dataRequest, $idTemp)
    {
        $count = 0;
        $tempData = $this->findCategoryTempCopy($dataRequest['idCorp'], $idTemp)->toArray();
        $registerData = array_filter($tempData, function ($arr) {
            if (!empty($arr['id'])) {
                return true;
            }
            return false;
        });

        $listIdDel = $dataRequest['checkedCategory'];
        if (empty($registerData)) {
            if (!empty($tempData)) {
                foreach ($tempData as $obj) {
                    $this->mCorpCategoriesTemp->saveWithData($obj);
                }
                $tempData = $this->findCategoryTempCopy($dataRequest['idCorp'], $idTemp)->toArray();
            }

            $checkedCorpCateList = [];
            foreach ($tempData as $v) {
                $checkedCorpCateList[] = $v['id'];
            }
            $dataRequest['checkedCategory'] = implode('-', $checkedCorpCateList);
            $listIdDel = $checkedCorpCateList;

            $dataRequest = $this->resetTempId($tempData, $dataRequest);
        }

        $orgCateIdArr = [];
        foreach ($dataRequest['listCategories'] as $orgObj) {
            if (!empty($orgObj->category_id)) {
                $orgArr = explode('-', $orgObj->category_id);
                $orgCateIdArr[] = $orgArr[1];
            }
        }
        $this->getListCategories($dataRequest, $orgCateIdArr, $idTemp);

        //Use to clone array object avoid change value property of element which is object in php 7.0
        $backupDataRequest = array_map(
            function ($obj) {
                return clone $obj;
            },
            $dataRequest['listCategories']
        );
        $saveResult = $this->saveListCategory($dataRequest, $idTemp, $listIdDel, $backupDataRequest, $count);
        return $saveResult;
    }

    /**
     * @param integer $idCorp
     * @param integer $idTemp
     * @return mixed
     */
    public function findCategoryTempCopy($idCorp, $idTemp)
    {
        $countTempLinkById = $this->mCorpCategoriesTemp->getCountByTempId($idTemp);
        if ($countTempLinkById == 0) {
            $latestTempLink = $this->corpAgreementTempLink->getFirstByCorpId($idCorp, $idTemp);
            if (!empty($latestTempLink)) {
                $result = $this->mCorpCategoriesTemp->getMCorpCategoryGenreList($idCorp, $latestTempLink->id);
                foreach ($result as &$obj) {
                    $obj->id = null;
                    $obj->temp_id = $idTemp;
                    $obj->action = null;
                }
            } else {
                $result = $this->mCorpCategories->getListByCorpId($idCorp, false);
                foreach ($result as &$obj) {
                    $obj->id = null;
                    $obj->temp_id = $idTemp;
                    unset($obj->created);
                    unset($obj->auction_status);
                    unset($obj->created_user_id);
                    unset($obj->m_sites_commission_type);
                    unset($obj->modified);
                    unset($obj->modified_user_id);
                }
            }
        } else {
            $result = $this->mCorpCategoriesTemp->getMCorpCategoryGenreList($idCorp, $idTemp);
        }
        return $result;
    }

    /**
     * @param array $tempData
     * @param array $dataRequest
     * @return mixed
     */
    private function resetTempId($tempData, $dataRequest)
    {
        foreach ($dataRequest['listCategories'] as &$obj) {
            if (isset($obj->category_id)) {
                $idArr = explode('-', $obj->category_id);
                $registeredTempData = array_filter(
                    $tempData,
                    function ($objTemp) use ($idArr) {
                        if ($objTemp['genre_id'] == $idArr[0] && $objTemp['category_id'] == $idArr[1]) {
                            return true;
                        }
                        return false;
                    }
                );
                if (!empty($registeredTempData)) {
                    $registeredTempData = array_shift($registeredTempData);
                    $idArr[2] = $registeredTempData['id'];
                    $obj->category_id = implode('-', $idArr);
                }
            }
        }
        return $dataRequest;
    }

    /**
     * Get list categories
     *
     * @param array $dataRequest
     * @param array $orgCateIdArr
     * @param integer $idTemp
     */
    private function getListCategories(&$dataRequest, $orgCateIdArr, $idTemp)
    {
        $copyCategoryIds = $this->mCategoryCopyRules->findAllByListOriginCategoryId($orgCateIdArr);
        $copyCorpCategories = $this->mCorpCategoriesTemp->findAllByCategoryIdAndTempId($copyCategoryIds, $idTemp);
        $categories = $this->mCategories->findAllByCopyCategoryIds($copyCategoryIds);
        foreach ($categories as $category) {
            $corpCategoryId = '';
            $corpCommissionType = $category['commission_type'];
            foreach ($copyCorpCategories as $corpCategory) {
                if ($corpCategory['corp_id'] == $dataRequest['idCorp']
                    && $corpCategory['genre_id'] == $category['genre_id']
                    && $corpCategory['category_id'] == $category['id']
                ) {
                    $corpCategoryId = '-' . $corpCategory['id'];
                    $corpCommissionType = $corpCategory['corp_commission_type'];
                    break;
                }
            }
            $addedCategory = $this->getAddedCategory($dataRequest['listCategories'], $category);
            if (!empty($addedCategory)) {
                continue;
            }
            $dataRequest['listCategories'][] = [
                'default_fee' => $category['category_default_fee'],
                'default_fee_unit' => $category['category_default_fee_unit'],
                'commission_type' => $category['commission_type'],
                'category_id' => $category['genre_id'] . '-' . $category['id'] . $corpCategoryId,
                'corp_commission_type' => $corpCommissionType,
            ];
        }
    }

    /**
     * get added category
     * @param  array $listCategories
     * @param  array $category
     * @return array
     */
    private function getAddedCategory($listCategories, $category)
    {
        return array_filter(
            $listCategories,
            function ($obj) use ($category) {
                if (!isset($obj->category_id)) {
                    return false;
                }
                $idArr = explode('-', $obj->category_id);
                if (count($idArr) >= 2) {
                    return $category['genre_id'] == $idArr[0] && $category['id'] == $idArr[1];
                } else {
                    return false;
                }
            }
        );
    }

    /**
     * Save list category
     *
     * @param array $dataRequest
     * @param integer $idTemp
     * @param array $listIdDel
     * @param array $backupDataRequest
     * @param integer $count
     * @return bool
     */
    private function saveListCategory($dataRequest, $idTemp, $listIdDel, $backupDataRequest, $count)
    {
        $saveData = [];
        $resultsFlg = true;
        foreach ($dataRequest['listCategories'] as $obj) {
            $obj->corp_id = $dataRequest['idCorp'];
            $obj->temp_id = $idTemp;

            if (!isset($obj->category_id)) {
                continue;
            }
            $idArrTemp = explode('-', $obj->category_id);
            if (count($idArrTemp) == 3) {
                $obj->id = $idArrTemp[2];
                foreach ($listIdDel as $key => $value) {
                    if ($value == $obj->id) {
                        array_splice($listIdDel, $key, 1);
                        break;
                    }
                }
            } else {
                if ($obj->commission_type == 2) {
                    $obj->introduce_fee = $obj->default_fee;
                } else {
                    $obj->order_fee = $obj->default_fee;
                    $obj->order_fee_unit = $obj->default_fee_unit;
                }
                $obj->corp_commission_type = $obj->commission_type;
            }
            $obj->genre_id = $idArrTemp[0];
            $obj->category_id = $idArrTemp[1];

            unset($obj->defaultCheckValue);
            unset($obj->default_fee);
            unset($obj->default_fee_unit);

            $saveData[] = $obj;
            $resultsFlg = $this->checkDuplicateCategory($backupDataRequest, $obj->category_id, $count);
            if (!$resultsFlg) {
                $error[$count] = __('affiliation_genre_resign.error_duplicate_category_id') . $obj->category_id;
                \Session::flash(__('affiliation_genre_resign.KEY_SESSION_ERROR_GENRE_RESIGN'), $error);
                break;
            }
            $count++;
        }
        $this->progressDelete($listIdDel, $dataRequest, $idTemp);
        $saveResult = $this->saveData($saveData, $resultsFlg, $dataRequest, $idTemp);

        return $saveResult;
    }

    /**
     * @param array $listCateRequest
     * @param integer $idCate
     * @param integer $count
     * @return bool
     */
    private function checkDuplicateCategory($listCateRequest, $idCate, $count)
    {
        $resultsFlg = true;
        for ($i = 0; $i < $count; $i++) {
            if (!empty($listCateRequest[$i]->category_id)) {
                if ($listCateRequest[$i]->category_id == $idCate) {
                    $resultsFlg = false;
                    break;
                }
            }
        }
        return $resultsFlg;
    }

    /**
     * @param array $listIdDel
     * @param array $dataRequest
     * @param integer $idTemp
     */
    private function progressDelete($listIdDel, $dataRequest, $idTemp)
    {
        $tempDelArr = [];
        foreach ($listIdDel as $obj) {
            $orgCategoryCategoryIds = $this->mCorpCategoriesTemp->getListCategoryIdById($obj);
            $copyCategoryCategoryIds = $this->mCategoryCopyRules->findCorpCateByOrgCateId($orgCategoryCategoryIds);
            $mCorpCategoryIds = $this->mCorpCategoriesTemp->getListIdBy(
                $copyCategoryCategoryIds,
                $dataRequest['idCorp'],
                $idTemp
            );
            $tempDelArr = $tempDelArr + $mCorpCategoryIds;
        }

        if (isset($tempDelArr)) {
            $dataRequest['checkedCategory'] = $tempDelArr;
            $listIdDel = $listIdDel + $tempDelArr;
        }

        foreach ($listIdDel as $str) {
            $delCorpCategory = $this->mCorpCategoriesTemp->getById($str);
            if (!empty($delCorpCategory)) {
                $delCorpCategory['delete_flag'] = true;
                $this->mCorpCategoriesTemp->saveWithData($delCorpCategory);
            }
        }
    }

    /**
     * Save data
     * @param array $saveData
     * @param bool $resultsFlg
     * @param array $dataRequest
     * @param integer $idTemp
     * @return bool
     */
    private function saveData($saveData, $resultsFlg, $dataRequest, $idTemp)
    {
        if (!empty($saveData)) {
            if ($resultsFlg) {
                foreach ($saveData as &$obj) {
                    if (empty($obj->id)) {
                        $checkRecord = $this->mCorpCategoriesTemp->getIdFirstBy(
                            (int)$dataRequest['idCorp'],
                            (int)$obj->genre_id,
                            (int)$obj->category_id,
                            (int)$idTemp
                        );
                        if (!empty($checkRecord)) {
                            $obj->id = $checkRecord->id;
                        }
                    }
                    if (!$this->mCorpCategoriesTemp->saveWithData($obj)) {
                        $resultsFlg = false;
                        break;
                    }
                }
                return $resultsFlg;
            }
            return false;
        }
        return true;
    }

    /**
     * @param integer $idCorp
     * @param string $kind
     * @return array
     */
    public function reconfirmResign($idCorp, $kind)
    {
        try {
            $idTemp = $this->getTempIdFromCorpIdInCorpAgreementTempLink($idCorp);
            $count = $this->mCorpCategoriesTemp->countByCorpIdAndTempId($idCorp, $idTemp);
            if ($count == 0) {
                $tempData = $this->findCategoryTempCopy($idCorp, $idTemp)->toArray();
                if (!empty($tempData)) {
                    foreach ($tempData as $obj) {
                        $this->mCorpCategoriesTemp->saveWithData($obj);
                    }
                }
            }
            $result = $this->updateReconfirmResign($idCorp, $kind);
            DB::commit();
            if ($result) {
                Session::flash(
                    __('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN_SUCCESS'),
                    __('affiliation_resign.message_update_complete')
                );
                return ['code' => 'SUCCESS'];
            } else {
                return ['code' => 'FAIL'];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ['code' => 'FAIL'];
        }
    }

    /**
     * @param integer $idCorp
     * @param string $kind
     * @return \App\Models\Base|bool
     */
    public function updateReconfirmResign($idCorp, $kind = 'WEB')
    {
        $resultFlg = false;
        /**
         * @var CorpAgreementTempLink $tempData
         */
        $tempData = $this->corpAgreementTempLink->getTempLink($idCorp);
        if (empty($tempData)) {
            $result = $this->insertDataReconfirmToCorpAgreement($idCorp, $kind);
            if ($result) {
                $resultFlg = $this->insertDataReconfirmToCorpAgreementTemp($idCorp, $result->id);
            }
        } else {
            if (empty($tempData->corp_agreement_id)) {
                $resultId = $this->insertDataReconfirmToCorpAgreement($idCorp, $kind);
                if ($resultId) {
                    $tempData->corp_agreement_id = $resultId;
                    $resultFlg = $tempData->save();
                }
            } else {
                $resultFlg = true;
            }
        }
        return $resultFlg;
    }

    /**
     * @param integer $idCorp
     * @param string $kind
     * @return mixed
     */
    private function insertDataReconfirmToCorpAgreement($idCorp, $kind = 'WEB')
    {
        $corpAgreement = $this->corpAgreement->findByCorpId($idCorp);
        $agreement = $this->agreement->findLastItem();
        $status = $kind == 'WEB' ? 'Resigning' : 'NotSigned';
        $dataInsert = [
            'corp_id' => $idCorp,
            'corp_kind' => isset($corpAgreement->corp_kind) ? $corpAgreement->corp_kind : 'Corp',
            'agreement_id' => isset($agreement->id) ? $agreement->id : 1,
            'agreement_history_id' => isset($agreement->agreement_history_id) ? $agreement->agreement_history_id : 1,
            'ticket_no' => isset($agreement->ticket_no) ? $agreement->ticket_no : 1,
            'status' => $status,
            'create_date' => date('Y-m-d H:i:s'),
            'create_user_id' => \Auth::user()->id,
            'update_date' => date('Y-m-d H:i:s'),
            'update_user_id' => \Auth::user()->id,
            'kind' => $kind
        ];
        return $this->corpAgreement->insertGetId($dataInsert);
    }

    /**
     * @param integer $idCorp
     * @param integer $idCorpAgreementId
     * @return \App\Models\Base
     */
    private function insertDataReconfirmToCorpAgreementTemp($idCorp, $idCorpAgreementId)
    {
        $model = $this->corpAgreementTempLink->getBlankModel();
        $model->corp_id = $idCorp;
        $model->corp_agreement_id = $idCorpAgreementId;
        return $this->corpAgreementTempLink->save($model);
    }
}
