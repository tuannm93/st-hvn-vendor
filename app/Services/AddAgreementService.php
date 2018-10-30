<?php

namespace App\Services;

use App\Models\MCorp;
use App\Repositories\AffiliationAreaStatRepositoryInterface;
use App\Repositories\AgreementRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;
use http\Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AddAgreementService
{
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
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
    protected $corpAgreementTempLinkRepo;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTempRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepository;
    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    protected $mCorpTargetAreaRepository;
    /**
     * @var AffiliationAreaStatRepositoryInterface
     */
    protected $affiliationAreaStatRepository;
    /**
     * @var AgreementRepositoryInterface
     */
    protected $agreementRepository;

    /**
     * AddAgreementService constructor.
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     * @param MTargetAreaRepositoryInterface $mTargetAreaRepository
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository
     * @param MCategoryRepositoryInterface $mCategoryRepository
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository
     * @param AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepository
     * @param AgreementRepositoryInterface $agreementRepository
     */
    public function __construct(
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MTargetAreaRepositoryInterface $mTargetAreaRepository,
        CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository,
        MCategoryRepositoryInterface $mCategoryRepository,
        MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository,
        AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepository,
        AgreementRepositoryInterface $agreementRepository
    ) {
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->mTargetAreaRepository = $mTargetAreaRepository;
        $this->corpAgreementTempLinkRepo = $corpAgreementTempLinkRepo;
        $this->mCorpCategoriesTempRepository = $mCorpCategoriesTempRepository;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->mCorpTargetAreaRepository = $mCorpTargetAreaRepository;
        $this->affiliationAreaStatRepository = $affiliationAreaStatRepository;
        $this->agreementRepository = $agreementRepository;
    }

    /**
     * check corp_kind column
     * @param $corpKind
     * @return string
     */
    public static function checkCorpKind($corpKind)
    {
        if (isset($corpKind)) {
            if ($corpKind == MCorp::CORP) {
                return MCorp::CORP_KIND[MCorp::CORP];
            } elseif ($corpKind == MCorp::PERSON) {
                return MCorp::CORP_KIND[MCorp::PERSON];
            } else {
                return MCorp::CORP_KIND[''];
            }
        } else {
            return MCorp::CORP_KIND[''];
        }
    }

    /**
     * check disable flag
     *
     * @param  string $role
     * @return boolean
     */
    public static function checkDisableFlg($role)
    {
        return in_array($role, ['system', 'admin']) ? false : true;
    }

    /**
     * update column function
     *
     * @param  $updateColumn
     * @return string
     */
    public static function updateColumn($updateColumn)
    {
        $retStr = implode(',', $updateColumn);
        if ($retStr !== '') {
            $retStr = 'Update:' . $retStr;
        }
        return $retStr;
    }

    /**
     * @param $corpId
     * @param $corpAgreement
     * @throws \Exception
     */
    public function addAgreement($corpId, $corpAgreement)
    {
        DB::beginTransaction();
        try {
            $tempLink = $this->copyAgreementTmp($corpId);
            $tempId = $tempLink['id'];
            $countCategoryTempData = $this->mCorpCategoriesTempRepository->countByCorpIdAndTempId($corpId, $tempId);
            if ($countCategoryTempData == 0) {
                $latestTempLink = $this->corpAgreementTempLinkRepo->getFirstByCorpId($corpId, $tempId);
                $saveDatas = $this->mCorpCategoriesTempRepository->findCategoryTempCopy($corpId, $tempId, $latestTempLink, $this->mCorpCategoryRepository);
                if (!empty($saveDatas)) {
                    $this->mCorpCategoriesTempRepository->insert($saveDatas);
                }
            }
            $ca = $this->corpAgreementRepository->createNewCorpAgreement($corpAgreement);
            $tempLink['corp_agreement_id'] = $ca['id'];
            if (!empty($tempLink)) {
                $tempLink->modified = date('Y-m-d H:i:s');
                $tempLink->modified_user_id = Auth::user()['user_id'];
                $this->corpAgreementTempLinkRepo->updateByTempLink($tempLink->toArray());
            }
            $this->updateOriginalCategoryData($ca);
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Copy input data copying process
     *
     * @param  null $corpId
     * @return boolean
     */
    protected function copyAgreementTmp($corpId = null)
    {
        $tempLink = $this->corpAgreementTempLinkRepo->getTempLink($corpId);
        if (empty($tempLink) || $tempLink['status'] == 'Complete' || $tempLink['status'] == 'Application') {
            $tempLink = $this->insertCorpAgreementTempLink($corpId);
        }
        return $tempLink;
    }

    /**
     * insert data function
     *
     * @param  null $corpId
     * @param  null $corpAgreementId
     * @return boolean|mixed
     */
    protected function insertCorpAgreementTempLink($corpId = null, $corpAgreementId = null)
    {
        return $this->corpAgreementTempLinkRepo->insertAgreementTempLink($corpId, $corpAgreementId);
    }

    /**
     * update data function
     *
     * @param  $corpAgreement
     * @return boolean|mixed
     */
    protected function updateOriginalCategoryData($corpAgreement)
    {
        if (empty($corpAgreement) || !isset($corpAgreement['status']) || $corpAgreement['status'] !== 'Complete') {
            $result = true;
        } else {
            $corpId = $corpAgreement['corp_id'];
            $corpAgreementId = $corpAgreement['id'];
            $result = $this->updateCorpCategoryFormTemp($corpId, $corpAgreementId);
        }

        return $result;
    }

    /**
     * check and update data function to corpCategoryTemp
     *
     * @param  null $id
     * @param  null $corpAgreementId
     * @return boolean|mixed
     */
    protected function updateCorpCategoryFormTemp($id = null, $corpAgreementId = null)
    {
        $resultsFlg = true;
        $tempLink = $this->corpAgreementTempLinkRepo->getByCorpIdAndCorpAgreementId($id, $corpAgreementId);
        $tempId = $tempLink['id'];
        $tempData = $this->mCorpCategoriesTempRepository->getTempData($id, $tempId);
        $data = [];
        if (count($tempData->toArray()) > 0) {
            foreach ($tempData as $key => $val) {
                $findCount = $this->mCategoryRepository->getCount($val['category_id'], $val['genre_id']);
                if (empty($findCount)) {
                    continue;
                }
                if ($val['delete_flag']) {
                    $tempData[$key]['action'] = 'Delete';
                    continue;
                }
                $data[] = [
                    'id' => isset($val['MCorpCategory.id']) ? $val['MCorpCategory.id'] : '',
                    'corp_id' => $val['corp_id'],
                    'genre_id' => $val['genre_id'],
                    'category_id' => $val['category_id'],
                    'order_fee' => $val['order_fee'],
                    'order_fee_unit' => $val['order_fee_unit'],
                    'introduce_fee' => $val['introduce_fee'],
                    'note' => $val['note'],
                    'select_list' => $val['select_list'],
                    'select_genre_category' => $val['select_genre_category'],
                    'target_area_type' => $val['target_area_type'],
                    'modified_user_id' => $val['modified_user_id'],
                    'modified' => $val['modified'],
                    'created_user_id' => $val['created_user_id'],
                    'created' => $val['created'],
                    'corp_commission_type' => $val['corp_commission_type']
                ];
                $action = null;
                if (isset($val['MCorpCategory.id'])) {
                    $action = $this->checkUpdateColumn($val, $val);
                    $action = empty($action) ? null : $action;
                } else {
                    $action = 'Add';
                }
                $tempData[$key]['action'] = $action;
            }
            $resultsFlg = $this->updateMCorpCategory($id, $tempData, $data, $resultsFlg);
        }
        return $resultsFlg;
    }

    /**
     * Update mCorp category; break from func updateCorpCategoryFormTemp
     * @param integer $id
     * @param array $tempData
     * @param array $data
     * @param bool $resultsFlg
     * @return bool|mixed
     */
    protected function updateMCorpCategory($id, $tempData, $data, $resultsFlg)
    {
        try {
            if (empty($data)) {
                return $resultsFlg;
            }
            $resultsFlg = $this->editCorpCategoryGenre2($id, $data);
            if ($resultsFlg) {
                foreach ($tempData as $key => $value) {
                    unset($tempData[$key]['auction_status']);
                }
                foreach ($tempData->toArray() as $value) {
                    $resultsFlg = $this->mCorpCategoriesTempRepository->saveAll($value);
                }
            }
            if ($resultsFlg) {
                $resultsFlg = $this->editTargetAreaGenre2($id, $data);
                if ($resultsFlg) {
                    $resultsFlg = $this->createAffiliationAreaStatsData($id);
                } else {
                    $resultsFlg = false;
                }
            } else {
                $resultsFlg = false;
            }
        } catch (\Exception $e) {
            $resultsFlg = false;
        }

        return $resultsFlg;
    }

    /**
     * check update column function
     *
     * @param  $src
     * @param  $dest
     * @return null|string
     */
    protected function checkUpdateColumn($src, $dest)
    {
        if (!empty($dest['action'])) {
            return $dest['action'];
        }
        $updateColumn = $this->updateColumnDest($src, $dest);
        $srcNote = empty($src['note']) ? '' : $src['note'];
        $destNote = empty($dest['MCorpCategory.note']) ? '' : $dest['MCorpCategory.note'];
        $srcSelectList = empty($src['select_list']) ? '' : $src['select_list'];
        $destSelectList = empty($dest['MCorpCategory.select_list']) ? '' : $dest['MCorpCategory.select_list'];
        if ($srcNote !== $destNote) {
            $updateColumn[] = 'note';
        }
        if ($srcSelectList !== $destSelectList) {
            $updateColumn[] = 'select_list';
        }
        return $this->setRetStr($updateColumn);
    }

    /**
     * @param $updateColumn
     * @return null|string
     */
    public function setRetStr($updateColumn)
    {
        if (count($updateColumn) > 0) {
            return $this->updateColumn($updateColumn);
        }
        return null;
    }
    /**
     * @param $src
     * @param $dest
     * @return array
     */
    public function updateColumnDest($src, $dest)
    {
        $updateColumn = [];
        if ($src['order_fee'] !== $dest['MCorpCategory.order_fee']) {
            $updateColumn[] = 'order_fee';
        }
        if ($src['order_fee_unit'] !== $dest['MCorpCategory.order_fee_unit']) {
            $updateColumn[] = 'order_fee_unit';
        }
        if ($src['introduce_fee'] !== $dest['MCorpCategory.introduce_fee']) {
            $updateColumn[] = 'introduce_fee';
        }
        if ($src['corp_commission_type'] !== $dest['MCorpCategory.corp_commission_type']) {
            $updateColumn[] = 'corp_commission_type';
        }
        return $updateColumn;
    }
    /**
     * check and edit function
     *
     * @param  $id
     * @param  $data
     * @return boolean
     */
    protected function editCorpCategoryGenre2($id, $data)
    {
        $resultsFlg = false;
        $count = 0;
        $delMccIdArray = $this->mCorpCategoryRepository->getListIdByCorpId($id);
        foreach ($data as $v) {
            if (!isset($v['category_id'])) {
                continue;
            }
            $saveData[] = $v;
            foreach ($delMccIdArray as $key => $value) {
                if ($value == $v['id']) {
                    unset($delMccIdArray[$key]);
                    break;
                }
            }
            $resultsFlg = $this->editCorpCategoryCheck($data, $v['category_id'], $count);
            if (!$resultsFlg) {
                return false;
            }
            $count++;
        }
        $this->mCorpCategoryRepository->deleteById($delMccIdArray);
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
     * check and edit function
     * @param $data
     * @param $categoryId
     * @param $count
     * @return boolean
     */
    protected function editCorpCategoryCheck($data, $categoryId, $count)
    {
        $resultsFlg = true;
        for ($i = 0; $i < $count; $i++) {
            if (!empty($data [$i] ['category_id'])) {
                if ($data [$i] ['category_id'] == $categoryId) {
                    $resultsFlg = false;
                    break;
                }
            }
        }
        return $resultsFlg;
    }

    /**
     * @param $id
     * @param $data
     * @return boolean
     */
    protected function editTargetAreaGenre2($id, $data)
    {
        $delMccIdArray = $this->mCorpCategoryRepository->getByCorpId($id);
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
        $this->mTargetAreaRepository->deleteById($delMccIdArray);
        $corpAreas = $this->mCorpTargetAreaRepository->getListByCorpId($id, true);
        $idList = $this->mCorpCategoryRepository->getListForIdByCorpId($id);
        $saveData = $this->genreSetData($id, $idList, $corpAreas);
        if (count($saveData)) {
            if (!$this->mTargetAreaRepository->insert($saveData)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param integer $id
     * @param array $idList
     * @param array $corpAreas
     * @return array
     */
    protected function genreSetData($id, $idList, $corpAreas)
    {
        $saveData = [];
        foreach ($idList as $val) {
            $areaCount = $this->mTargetAreaRepository->getCorpCategoryTargetAreaCount($val['id']);
            if ($areaCount > 0) {
                continue;
            }
            foreach ($corpAreas as $area) {
                $setData = [];
                $setData['corp_category_id'] = $val['id'];
                $setData['jis_cd'] = $area['jis_cd'];
                $saveData[] = $setData;
            }
            $gList = $this->mCorpCategoryRepository->getListForIdByCorpIdAndGenreId($id, $val['genre_id']);
            foreach ($gList as $gVal) {
                if ($gVal['target_area_type'] > 0) {
                    $this->editCorpCategoryTargetAreaType($val['id'], $gVal['target_area_type']);
                    break;
                }
            }
        }
        return $saveData;
    }

    /**
     * insert data function
     *
     * @param  $id
     * @return boolean
     */
    protected function createAffiliationAreaStatsData($id)
    {
        $list = $this->mCorpCategoryRepository->getListByCorpIdAndAffiliationStatus($id);
        foreach ($list as $value) {
            for ($prefecture = 1; $prefecture <= 47; $prefecture++) {
                $data = $this->affiliationAreaStatRepository->getByPrefecture($value, $prefecture);
                if (count($data) == 0) {
                    $this->affiliationAreaStatRepository->insertBy($value, $prefecture);
                }
            }
        }
        return true;
    }

    /**
     * edit function
     *
     * @param  $id
     * @param  $type
     * @return void
     */
    protected function editCorpCategoryTargetAreaType($id, $type)
    {
        $data['id'] = $id;
        $data['target_area_type'] = $type;
        $this->mCorpCategoryRepository->updateById($data);
    }
}
