<?php

namespace App\Services\Affiliation;

use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AffiliationMCorpCategoryService extends BaseService
{
    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    private $mCorpTargetAreaRepository;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    private $mCorpCategoryRepository;
    /**
     * @var MTargetAreaRepositoryInterface
     */
    private $mTargetAreaRepository;

    /**
     * AffiliationMCorpCategoryService constructor.
     *
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository
     * @param MCorpCategoryRepositoryInterface   $mCorpCategoryRepository
     * @param MTargetAreaRepositoryInterface     $mTargetAreaRepository
     */
    public function __construct(
        MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MTargetAreaRepositoryInterface $mTargetAreaRepository
    ) {

        $this->mCorpTargetAreaRepository = $mCorpTargetAreaRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->mTargetAreaRepository = $mTargetAreaRepository;
    }

    //region Public functions

    /**
     * Get Genre List for genreCustomAreaList and genreNormalAreaList
     *
     * @param integer $corpId
     * @return array
     */
    public function getGenreList($corpId)
    {
        // Acquisition of company area master
        $corpAreas = $this->mCorpTargetAreaRepository->getListByCorpId($corpId);

        // Number of setting area of ​​basic correspondence area
        $corpTargetAreaCount = $this->mCorpTargetAreaRepository->countByCorpId($corpId);

        // Member Store Selected Genre List
        $selectGenreList = $this->mCorpCategoryRepository->getListByCorpId($corpId);

        $genreCustomAreaList = [];
        $genreNormalAreaList = [];
        $genreCustomAreaListA = [];
        $genreCustomAreaListB = [];
        $genreNormalAreaListA = [];
        $genreNormalAreaListB = [];
        foreach ($selectGenreList as $keyGenreList => $valueGenreList) {
            // Differentiation between genres that remain as basic correspondence areas and customized genres
            $customFlg = $this->isGenreCustom($valueGenreList, $corpTargetAreaCount, $corpAreas);

            if ($customFlg == true) {
                // Genre list customizing corresponding area
                if ($valueGenreList['corp_commission_type'] == 1) {
                    $genreCustomAreaListA[] = $selectGenreList[$keyGenreList];
                } elseif ($valueGenreList['corp_commission_type'] == 2) {
                    $genreCustomAreaListB[] = $selectGenreList[$keyGenreList];
                }
                $genreCustomAreaList[] = $selectGenreList[$keyGenreList];
            } else {
                if ($valueGenreList['corp_commission_type'] == 1) {
                    $genreNormalAreaListA[] = $selectGenreList[$keyGenreList];
                } elseif ($valueGenreList['corp_commission_type'] == 2) {
                    $genreNormalAreaListB[] = $selectGenreList[$keyGenreList];
                }
                // Genre list where the corresponding area remains the basic correspondence area
                $genreNormalAreaList[] = $selectGenreList[$keyGenreList];
            }
        }


        return [
            'genreCustomAreaList' => $genreCustomAreaList,
            'genreCustomAreaListA' =>  $genreCustomAreaListA,
            'genreCustomAreaListB' =>  $genreCustomAreaListB,
            'genreNormalAreaList' => $genreNormalAreaList,
            'genreNormalAreaListA' =>  $genreNormalAreaListA,
            'genreNormalAreaListB' =>  $genreNormalAreaListB,
            'lastItemGenre' => !empty($selectGenreList) ? $selectGenreList[count($selectGenreList) - 1] : null
        ];
    }

    /**
     * @param integer $id
     * @param array $inputData
     * @return mixed
     * @throws \Exception
     */
    public function updateStatusMCorpCategory($id, $inputData)
    {
        $mCorpCategory = $this->mCorpCategoryRepository->find($id);
        $mCorpCategory->auction_status = $inputData['auction_status'];
        $mCorpCategory->modified_user_id = Auth::user()['user_id'];
        $mCorpCategory->modified = Carbon::now();
        DB::beginTransaction();
        try {
            $mCorpCategory = $this->mCorpCategoryRepository->save($mCorpCategory);
            DB::commit();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return false;
        }

        return $mCorpCategory;
    }

    /**
     * @param array $genre
     * @param integer $corpTargetAreaCount
     * @param array $corpAreas
     * @return boolean
     */
    private function isGenreCustom($genre, $corpTargetAreaCount, $corpAreas)
    {
        $customFlg = false;
        if ($genre['target_area_type'] == 0) {
            $targetAreaCount = $this->mTargetAreaRepository->getCorpCategoryTargetAreaCount($genre['id']);
            if ($targetAreaCount != $corpTargetAreaCount) {
                $customFlg = true;
            }
            foreach ($corpAreas as $areaValue) {
                $areaCount = $this->mTargetAreaRepository->getCorpCategoryTargetAreaCount(
                    $genre['id'],
                    $areaValue->jis_cd
                );
                if ($areaCount <= 0) {
                    $customFlg = true;
                    break;
                }
            }
        } else {
            if ($genre['target_area_type'] == 2) {
                // Supportable area is different from basic correspondence area
                $customFlg = true;
            }
        }
        return $customFlg;
    }

    /**
     * Check mcorp category
     *
     * @param array $listData
     * @param integer $corpTargetAreaCount
     * @param array $corpAreas
     * @return array
     */
    public function checkMCorpCategory($listData, $corpTargetAreaCount, $corpAreas)
    {
        $customFlg = false;
        $mstedtFlg = false;

        foreach ($listData as $cgVal) {
            if ($cgVal['target_area_type'] == 0 || isset($data['regist'])|| isset($data['regist-base-update'])) {
                $mstedtFlg = true;
                $targetAreaCount = $this->mTargetAreaRepository->getCorpCategoryTargetAreaCount($cgVal['id']);
                if ($targetAreaCount != $corpTargetAreaCount) {
                    $customFlg = true;
                    break;
                }

                $customFlg = $this->isFlagCorpCategoryTargetAreaCount($corpAreas, $cgVal, $customFlg);

                if ($customFlg == true) {
                    break;
                }
            } elseif ($cgVal['target_area_type'] == 1) {
                break;
            } elseif ($cgVal['target_area_type'] == 2) {
                $customFlg = true;
                break;
            }
        }

        return ['customFlg' => $customFlg, 'mstedtFlg' => $mstedtFlg];
    }

    /**
     * Get flag corp category target area count
     *
     * @param array $corpAreas
     * @param array $cgVal
     * @param boolean $customFlg
     * @return bool
     */
    private function isFlagCorpCategoryTargetAreaCount($corpAreas, $cgVal, $customFlg)
    {
        foreach ($corpAreas as $areaV) {
            $areaCount = $this->mTargetAreaRepository->getCorpCategoryTargetAreaCount2(
                $cgVal['id'],
                $areaV['jis_cd']
            );
            if ($areaCount <= 0) {
                $customFlg = true;
                break;
            }
        }

        return $customFlg;
    }
}
