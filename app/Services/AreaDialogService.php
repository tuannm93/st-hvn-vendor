<?php


namespace App\Services;

use App\Models\MCorpTargetArea;
use App\Models\MTargetArea;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AreaDialogService
{
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepository;
    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    protected $mCorpTargetAreaRepository;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepository;
    /**
     * @var MTargetAreaRepositoryInterface
     */
    protected $mTargetAreaRepository;

    /**
     * AreaDialogService constructor.
     *
     * @param MPostRepositoryInterface           $mPostRepository
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository
     * @param MCorpCategoryRepositoryInterface   $mCorpCategoryRepository
     * @param MTargetAreaRepositoryInterface     $mTargetAreaRepository
     */
    public function __construct(
        MPostRepositoryInterface $mPostRepository,
        MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MTargetAreaRepositoryInterface $mTargetAreaRepository
    ) {
        $this->mPostRepository = $mPostRepository;
        $this->mCorpTargetAreaRepository = $mCorpTargetAreaRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->mTargetAreaRepository = $mTargetAreaRepository;
    }

    /**
     * @param $corpId
     * @param $addressCd
     * @return mixed
     */
    public function getListPostDialog($corpId, $addressCd)
    {
        return $this->mPostRepository->findByCorpIdAndPrefecturalCode($corpId, $addressCd);
    }

    /**
     * @param $data
     * @param $corpId
     */
    public function postListPostDialog($data, $corpId)
    {
        $addressCode = $data['addressCd'];
        $listMCorpTargetArea = $this->mCorpTargetAreaRepository->getListByCorpIdAndAddressCode($corpId, $addressCode);
        $listMCorpTargetAreaArray = [];
        if (!checkIsNullOrEmptyCollection($listMCorpTargetArea)) {
            $listMCorpTargetAreaArray = $listMCorpTargetArea->keyBy('jis_cd')->toArray();
        }
        $listAddressFromUI = $this->getListAddressFromUI($data);
        $newCorpTargetAreaList = $listMCorpTargetAreaArray;
        $this->insertNewArea($corpId, $listAddressFromUI, $listMCorpTargetAreaArray, $newCorpTargetAreaList);
        $this->deleteArea($listAddressFromUI, $listMCorpTargetAreaArray, $newCorpTargetAreaList);
        $this->insertTempTable($corpId, $newCorpTargetAreaList);
        $this->updateTargetAreaType($corpId);
    }

    /**
     * @param $corpId
     * @param $listAddressFromUI
     * @param $listMCorpTargetAreaArray
     * @param $newCorpTargetAreaList
     */
    private function insertNewArea($corpId, $listAddressFromUI, $listMCorpTargetAreaArray, &$newCorpTargetAreaList)
    {
        foreach ($listAddressFromUI as $value) {
            if (!$this->isExistInDatabase($listMCorpTargetAreaArray, $value)) {
                $this->insertMCorpTargetArea($corpId, $value);
                $newCorpTargetAreaList[$value] = $value;
            }
        }
    }

    /**
     * @param $listAddressFromUI
     * @param $listMCorpTargetAreaArray
     * @param $newCorpTargetAreaList
     */
    private function deleteArea($listAddressFromUI, $listMCorpTargetAreaArray, &$newCorpTargetAreaList)
    {
        $arrayIdDelete = [];
        foreach ($listMCorpTargetAreaArray as $value) {
            if (!array_key_exists($value['jis_cd'], $listAddressFromUI)) {
                array_push($arrayIdDelete, $value['id']);
                unset($newCorpTargetAreaList[$value['jis_cd']]);
            }
        }
        if (!checkIsNullOrEmpty($arrayIdDelete)) {
            $this->mCorpTargetAreaRepository->deleteByListId($arrayIdDelete);
        }
    }

    /**
     * @param $corpId
     * @param $newCorpTargetAreaList
     */
    private function insertTempTable($corpId, $newCorpTargetAreaList)
    {
        $newCorpTargetAreaListKey = array_keys($newCorpTargetAreaList);
        $mCorpCategoryList = $this->mCorpCategoryRepository->findAllByCorpId($corpId);
        foreach ($mCorpCategoryList as $mCorpCategory) {
            $mTargetAreaList = $this->mTargetAreaRepository->findAllByCorpCategoryId($mCorpCategory->id);
            if (checkIsNullOrEmptyCollection($mTargetAreaList)) {
                foreach ($newCorpTargetAreaListKey as $indexNewCorpTarget) {
                    $mTargetArea = new MTargetArea();
                    $mTargetArea->corp_category_id = $mCorpCategory->id;
                    $mTargetArea->jis_cd = $indexNewCorpTarget;
                    $mTargetArea->created = Carbon::now()->toDateTimeString();
                    $mTargetArea->created_user_id = Auth::user()->user_id;
                    $mTargetArea->modified = $mTargetArea->created;
                    $mTargetArea->modified_user_id = $mTargetArea->created_user_id;
                    $this->mTargetAreaRepository->save($mTargetArea);
                }
            }
        }
    }

    /**
     * @param $corpId
     */
    private function updateTargetAreaType($corpId)
    {
        $mCorpCategoryList = $this->mCorpCategoryRepository->findAllByCorpId($corpId);
        $corpTargetAreaList = $this->mCorpTargetAreaRepository->getListByCorpId($corpId);
        foreach ($mCorpCategoryList as $mCorpCategory) {
            $customFlag = false;
            $mCorpCategoryWithGenreList = $this->mCorpCategoryRepository->findAllByCorpIdAndGenreId($corpId, $mCorpCategory->genre_id);
            foreach ($mCorpCategoryWithGenreList as $mCorpCategoryWithGenre) {
                $mTargetArea = $this->mTargetAreaRepository->findAllByCorpCategoryId($mCorpCategoryWithGenre->id);
                if ($mTargetArea->count() != $corpTargetAreaList->count()) {
                    $customFlag = true;
                    break;
                }
                foreach ($corpTargetAreaList as $corpTargetArea) {
                    $area = $this->mTargetAreaRepository->findAllByCorpCategoryIdAndJisCd($mCorpCategoryWithGenre->id, $corpTargetArea->jis_cd);
                    if (checkIsNullOrEmptyCollection($area)) {
                        $customFlag = true;
                        break;
                    }
                }
                if ($customFlag) {
                    break;
                }
            }

            if ($customFlag) {
                $this->editCorpCategoryTargetAreaType($mCorpCategoryList, 2);
            } else {
                $this->editCorpCategoryTargetAreaType($mCorpCategoryList, 1);
            }
        }
    }

    /**
     * @param $mCorpCategoryList
     * @param $targetAreaType
     */
    private function editCorpCategoryTargetAreaType($mCorpCategoryList, $targetAreaType)
    {
        foreach ($mCorpCategoryList as $mCorpCategory) {
            $mCorpCategory->target_area_type = $targetAreaType;
            $mCorpCategory->modified = Carbon::now()->toDateTimeString();
            $mCorpCategory->modified_user_id = Auth::user()->user_id;
            $this->mCorpCategoryRepository->save($mCorpCategory);
        }
    }

    /**
     * @param $corpId
     * @param $address
     */
    private function insertMCorpTargetArea($corpId, $address)
    {
        $mCorpTargetAreas = new MCorpTargetArea();
        $mCorpTargetAreas->corp_id = $corpId;
        $mCorpTargetAreas->jis_cd = $address;
        $mCorpTargetAreas->created = Carbon::now()->toDateTimeString();
        $mCorpTargetAreas->modified = $mCorpTargetAreas->created;
        $mCorpTargetAreas->created_user_id = Auth::user()->user_id;
        $mCorpTargetAreas->modified_user_id = $mCorpTargetAreas->created_user_id;

        $this->mCorpTargetAreaRepository->save($mCorpTargetAreas);
    }

    /**
     * @param $listMCorpTargetAreaArray
     * @param $address
     * @return bool
     */
    private function isExistInDatabase($listMCorpTargetAreaArray, $address)
    {
        if (checkIsNullOrEmpty($listMCorpTargetAreaArray)) {
            return false;
        } else {
            if (array_key_exists($address, $listMCorpTargetAreaArray)) {
                return true;
            }
            return false;
        }
    }

    /**
     * @param $data
     * @return array
     */
    private function getListAddressFromUI($data)
    {
        $result = [];
        foreach ($data as $index => $value) {
            if (starts_with($index, 'jisCd_')) {
                $result[$value] = $value;
            }
        }
        return $result;
    }
}
