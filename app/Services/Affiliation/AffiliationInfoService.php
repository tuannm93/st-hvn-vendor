<?php

namespace App\Services\Affiliation;

use App\Repositories\AffiliationInfoRepositoryInterface;
use App\Repositories\AffiliationStatsRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\Eloquent\MTargetAreaRepository;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AffiliationInfoService
{
    const USER = "system";
    const DIV_CONSTRUCTION_STATUS = "construction_status";
    const CONSTRUCTION_STATUS = "construction";
    const INSERT_SHELL_WORK_CI = "/commands/sql/insert_shell_work_ci.sql";
    const INSERT_SHELL_WORK_RESULT = "/commands/sql/insert_shell_work_result.sql";
    /**
     * @var AffiliationInfoRepositoryInterface
     */
    private $affiliationInfoRepo;
    /**
     * @var AffiliationStatsRepositoryInterface
     */
    private $affiliationStatsRepo;
    /**
     * @var CommissionInfoRepositoryInterface
     */
    private $commissionInfoRepo;
    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    private $mCorpTargetAreaRepository;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    private $mCorpCategoryRepository;
    /**
     * @var MPostRepositoryInterface
     */
    private $mPostRepository;
    /**
     * @var AffiliationMCorpCategoryService
     */
    private $affMCorpCategoryService;
    /**
     * @var MTargetAreaRepository
     */
    private $mTargetAreaRepository;

    /**
     * AffiliationInfoService constructor.
     * @param CommissionInfoRepositoryInterface $commissionInfoRepo
     * @param AffiliationInfoRepositoryInterface $affiliationInfoRepo
     * @param AffiliationStatsRepositoryInterface $affiliationStatsRepo
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     * @param MPostRepositoryInterface $mPostRepository
     * @param AffiliationMCorpCategoryService $affMCorpCategoryService
     * @param MTargetAreaRepository $mTargetAreaRepository
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepo,
        AffiliationInfoRepositoryInterface $affiliationInfoRepo,
        AffiliationStatsRepositoryInterface $affiliationStatsRepo,
        MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MPostRepositoryInterface $mPostRepository,
        AffiliationMCorpCategoryService $affMCorpCategoryService,
        MTargetAreaRepository $mTargetAreaRepository
    ) {
        $this->affiliationInfoRepo = $affiliationInfoRepo;
        $this->commissionInfoRepo = $commissionInfoRepo;
        $this->affiliationStatsRepo = $affiliationStatsRepo;
        $this->mCorpTargetAreaRepository = $mCorpTargetAreaRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->mPostRepository = $mPostRepository;
        $this->affMCorpCategoryService = $affMCorpCategoryService;
        $this->mTargetAreaRepository = $mTargetAreaRepository;
    }

    /**
     * Update record in table affiliation_infos(commission_count)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setCommissionCountOfAffiliation()
    {
        $list = $this->commissionInfoRepo->getWithMCorpAndCountRow();
        foreach ($list as $r) {
            $this->affiliationInfoRepo->updateByCorpId(
                [
                    "corp_id" => $r->corp_id,
                    "commission_count" => $r->total,
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }

        $resetList = $this->affiliationInfoRepo->getCommissionCountOfAffiliationInitialize();
        foreach ($resetList as $r) {
            $this->affiliationInfoRepo->updateByCorpId(
                [
                    "corp_id" => $r->corp_id,
                    "commission_count" => 0,
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }
    }

    /**
     * Update record in table affiliation_infos(weekly_commission_count)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setCommissionWeekCountOfAffiliation()
    {
        $list = $this->commissionInfoRepo->getWithMCorpAndCountRow(true);
        foreach ($list as $r) {
            $this->affiliationInfoRepo->updateByCorpId(
                [
                    "corp_id" => $r->corp_id,
                    "weekly_commission_count" => $r->total,
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }

        $list = $this->affiliationInfoRepo->getCommissionWeekCountOfAffiliation();
        foreach ($list as $r) {
            $this->affiliationInfoRepo->updateByCorpId(
                [
                    "corp_id" => $r->corp_id,
                    "weekly_commission_count" => 0,
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }
    }

    /**
     * Update record in table affiliation_infos(orders_count, construction_unit_price)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setReceiptCount()
    {
        $status = getDivValue(self::DIV_CONSTRUCTION_STATUS, self::CONSTRUCTION_STATUS);
        $list = $this->commissionInfoRepo->getWithAVGPriceTaxByStatus($status);
        foreach ($list as $r) {
            $this->affiliationInfoRepo->updateByCorpId(
                [
                    "corp_id" => $r->corp_id,
                    "orders_count" => $r->total,
                    "construction_unit_price" => round($r->construction_price_tax_exclude),
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }

        $list = $this->affiliationInfoRepo->getReceiptCountInitialize($status);
        foreach ($list as $r) {
            $this->affiliationInfoRepo->updateByCorpId(
                [
                    "corp_id" => $r->corp_id,
                    "orders_count" => 0,
                    "construction_unit_price" => 0,
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }
    }

    /**
     * Update record in table affiliation_infos(commission_unit_price)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setCommissionPrice()
    {
        DB::table('shell_work_ci')->truncate();
        DB::table('shell_work_result')->truncate();

        $sql = File::get(storage_path(self::INSERT_SHELL_WORK_CI));
        DB::unprepared($sql);

        $sql = File::get(storage_path(self::INSERT_SHELL_WORK_RESULT));
        DB::unprepared($sql);

        $list = $this->affiliationInfoRepo->getWithJoinShellWork();
        foreach ($list as $r) {
            $this->affiliationInfoRepo->updateByCorpId(
                [
                    "corp_id" => $r->corp_id,
                    "commission_unit_price" => round($r->commission_unit_price_category),
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }
    }

    /**
     * Update record in table affiliation_infos(orders_rate)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setReceiptRate()
    {
        $list = $this->commissionInfoRepo->getWithAvgCorpFee();
        foreach ($list as $r) {
            $this->affiliationInfoRepo->updateByCorpId(
                [
                    "corp_id" => $r->corp_id,
                    "orders_rate" => DB::raw("trunc(cast(coalesce(orders_count, 0) as dec) / cast(coalesce(commission_count, 0) as dec) * 100, 1)"),
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }

        $list = $this->affiliationInfoRepo->getReceiptRateInitialize();
        foreach ($list as $r) {
            $this->affiliationInfoRepo->updateByCorpId(
                [
                    "corp_id" => $r->corp_id,
                    "orders_rate" => 0,
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }
    }

    /**
     * Get corp target area
     *
     * @param integer $id
     * @param array $data
     * @param integer $initPref
     * @return array
     * @throws Exception
     */
    public function getCorpTargetArea($id, $data, $initPref)
    {
        $this->checkPostRequest($id, $data);
        $prefectureDiv = config('datacustom.prefecture_div');
        $prefList = $this->getPrefList($prefectureDiv, $id);
        $corpAreas = $this->mCorpTargetAreaRepository->getListByCorpId($id, true);
        $corpTargetAreaCount = $this->mCorpTargetAreaRepository->countByCorpId($id);
        $selectGenreList = $this->mCorpCategoryRepository->getCorpSelectGenreList($id);

        $genreCustomAreaList = [];
        $genreNormalAreaList = [];

        foreach ($selectGenreList as $val) {
            $listData = $this->mCorpCategoryRepository->getListForIdByCorpIdAndGenreId($id, $val['id']);
            $checkMcorpCategory = $this->checkMCorpCategory($listData, $corpAreas, $corpTargetAreaCount, $data);
            $customFlg = $checkMcorpCategory['customFlg'];
            $mstedtFlg = $checkMcorpCategory['mstedtFlg'];

            $obj = [];
            $obj['genre_name'] = $val['genre_name'];
            $obj['genre_id'] = $val['id'];
            $rowData = $this->mCorpCategoryRepository->getIdByCorpIdAndGenreId($id, $val['id']);
            $obj['id'] = $rowData['id'];
            if ($customFlg == true) {
                $genreCustomAreaList[] = $obj;
                if ($mstedtFlg) {
                    foreach ($listData as $cgVal) {
                        $this->editCorpCategoryTargetAreaType($cgVal['id'], 2);
                    }
                }
            } else {
                $genreNormalAreaList[] = $obj;
                if ($mstedtFlg) {
                    foreach ($listData as $cgVal) {
                        $this->editCorpCategoryTargetAreaType($cgVal['id'], 1);
                    }
                }
            }
        }

        $lastModified = $this->mCorpTargetAreaRepository->getLastModifiedByCorpId($id);

        return [
            "genre_custom_area_list" => $genreCustomAreaList,
            "genre_normal_area_list" => $genreNormalAreaList,
            'last_modified' => $lastModified,
            'pref_list' => $prefList,
            "init_pref" => $initPref,
            "id" => $id,
        ];
    }

    /**
     * Check post request
     *
     * @param integer $id
     * @param array $data
     */
    public function checkPostRequest($id, $data)
    {
        session()->forget('InputError');
        session()->forget('Update');
        if (isset($data['regist'])) {
            $this->corpWidthParmaRegist($id, $data);
        } elseif (isset($data['regist-base-update'])) {
            $this->checkRegisterBaseUpdate($id, $data);
        } elseif (isset($this->request->data['all_regist'])) {
            $this->checkAllRegister($id);
        } elseif (isset($this->request->data['all_remove'])) {
            $this->checkRemoveAll($id);
        }
    }

    /**
     * Check when submit pram had register
     *
     * @param  integer $id
     * @param  array $data
     */
    private function corpWidthParmaRegist($id, $data)
    {
        try {
            $resultsFlg = $this->mPostRepository->editTargetArea2($id, $data);
            if ($resultsFlg) {
                $resultsFlg = $this->mCorpTargetAreaRepository->editTargetAreaToGenre($id);
                if ($resultsFlg) {
                    session()->flash('Update', trans('aff_corptargetarea.update'));
                } else {
                    session()->flash('InputError', trans('aff_corptargetarea.input_error'));
                }
            } else {
                session()->flash('InputError', trans('aff_corptargetarea.input_error'));
            }
        } catch (Exception $e) {
            session()->flash('InputError', trans('aff_corptargetarea.input_error'));
        }
    }

    /**
     * Check register base update
     *
     * @param integer $id
     * @param array $data
     * @param bool $resultsFlg
     */
    private function checkRegisterBaseUpdate($id, $data, $resultsFlg = true)
    {
        if (!empty($id) && !empty($data['data']['genre_id'])) {
            foreach ($data['data']['genre_id'] as $val) {
                if (!ctype_digit($val)) {
                    abort(404);
                }
            }
            foreach ($data['data']['genre_id'] as $val) {
                $resultsFlg = $this->mCorpTargetAreaRepository->editTargetAreaToCategory($id, $val);
                if (!$resultsFlg) {
                    break;
                }
            }
        } else {
            $resultsFlg = false;
        }
        if ($resultsFlg) {
            session()->flash('Update', trans('aff_corptargetarea.update'));
        } else {
            session()->flash('InputError', trans('aff_corptargetarea.input_error'));
        }
    }

    /**
     * Check all register
     *
     * @param integer $id
     */
    private function checkAllRegister($id)
    {
        try {
            $resultsFlg = $this->mPostRepository->allRegistTargetArea($id);
            if ($resultsFlg) {
                session()->flash('Update', trans('aff_corptargetarea.update'));
            } else {
                session()->flash('InputError', trans('aff_corptargetarea.input_error'));
            }
        } catch (Exception $e) {
            session()->flash('InputError', trans('aff_corptargetarea.input_error'));
        }
    }

    /**
     * @param integer $id
     */
    private function checkRemoveAll($id)
    {
        try {
            $this->mCorpTargetAreaRepository->removeByCorpId($id);
            session()->flash('Update', trans('aff_corptargetarea.update'));
        } catch (Exception $e) {
            session()->flash('InputError', trans('aff_corptargetarea.input_error'));
        }
    }

    /**
     * Get pref list
     *
     * @param string $prefectureDiv
     * @param integer $id
     * @return array
     */
    private function getPrefList($prefectureDiv = null, $id = null)
    {
        $prefList = [];
        foreach ($prefectureDiv as $key => $val) {
            if ($key == 99) {
                continue;
            }
            $obj = [];
            $obj['id'] = $key;
            $obj['name'] = $val;
            $corpCount = $this->mPostRepository->getCorpPrefAreaCount($id, $val);
            if ($corpCount > 0) {
                $areaCount = $this->mPostRepository->getPrefAreaCount($val);
                if ($corpCount >= $areaCount) {
                    $obj['rank'] = 2;
                } else {
                    $obj['rank'] = 1;
                }
            } else {
                $obj['rank'] = 0;
            }
            $prefList[] = $obj;
        }
        return $prefList;
    }

    /**
     * @param $listData
     * @param $corpAreas
     * @param $corpTargetAreaCount
     * @param $data
     * @return array
     */
    private function checkMCorpCategory($listData, $corpAreas, $corpTargetAreaCount, $data)
    {
        $customFlg = false;
        $mstedtFlg = false;
        foreach ($listData as $cgVal) {
            if ($cgVal['target_area_type'] == 0 || isset($data['regist'])
                || isset($data['regist-base-update'])) {
                $mstedtFlg = true;
                $targetAreaCount = $this->mTargetAreaRepository->getCorpCategoryTargetAreaCount($cgVal['id']);
                if ($targetAreaCount != $corpTargetAreaCount) {
                    $customFlg = true;
                    break;
                }
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
     * @param integer $id
     * @param string $type
     * @throws Exception
     */
    private function editCorpCategoryTargetAreaType($id, $type)
    {
        $data['id'] = $id;
        $data['target_area_type'] = $type;
        $data['modified'] = false;
        $this->mCorpCategoryRepository->saveCorpCategory($data);
    }

    /**
     * @param $corp_id
     * @return mixed
     */
    public function findIdbyCorpId($corp_id)
    {
        return $this->affiliationInfoRepo->getIdByCorpId($corp_id);
    }
}
