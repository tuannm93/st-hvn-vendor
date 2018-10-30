<?php

namespace App\Services\Affiliation;

use App\Repositories\AffiliationStatsRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AffiliationStatService
{
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    private $mCorpCateRepo;

    /**
     * @var AffiliationStatsRepositoryInterface
     */
    private $affiliationStatsRepo;

    /**
     * @var CommissionInfoRepositoryInterface
     */
    private $commissionInfoRepo;

    const USER = "system";

    const DIV_CONSTRUCTION_STATUS = "construction_status";

    const CONSTRUCTION_STATUS = "construction";

    const INSERT_SHELL_WORK_CI = "/commands/sql/insert_shell_work_ci.sql";

    const INSERT_SHELL_WORK_RESULT = "/commands/sql/insert_shell_work_result.sql";

    /**
     * AffiliationStatService constructor.
     *
     * @param MCorpCategoryRepositoryInterface    $mCorpCateRepo
     * @param AffiliationStatsRepositoryInterface $affiliationStatsRepo
     * @param CommissionInfoRepositoryInterface   $commissionInfoRepo
     */
    public function __construct(
        MCorpCategoryRepositoryInterface $mCorpCateRepo,
        AffiliationStatsRepositoryInterface $affiliationStatsRepo,
        CommissionInfoRepositoryInterface $commissionInfoRepo
    ) {
        $this->mCorpCateRepo = $mCorpCateRepo;
        $this->affiliationStatsRepo = $affiliationStatsRepo;
        $this->commissionInfoRepo = $commissionInfoRepo;
    }

    /**
     * Insert record in table affiliation_stats
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setCreateAffiliationStatData()
    {
        $list = $this->mCorpCateRepo->getListByCorpDelFlagAndAffiliationStatus(["m_corps.id", "m_corp_categories.genre_id"]);
        foreach ($list as $r) {
            $affiliationStat = $this->affiliationStatsRepo->getByCorpIdAndGenreId($r->id, $r->genre_id);
            if (!$affiliationStat) {
                $this->affiliationStatsRepo->insert(
                    [
                        'corp_id' => $r->id,
                        'genre_id' => $r->genre_id,
                        'commission_count_category' => 0,
                        'orders_count_category' => 0,
                        'created' => date("Y/m/d H:i:s", time()),
                        'created_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            }
        }
    }

    /**
     * Update record in table affiliation_stats(commission_count_category)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setCommissionGroupCategoryCount()
    {
        $list = $this->commissionInfoRepo->getWithRelForGroupCategory();
        foreach ($list as $r) {
            if ($r->affiliation_stat_id != null && !empty($r->affiliation_stat_id)) {
                $this->affiliationStatsRepo->update(
                    $r->affiliation_stat_id,
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int) $r->genre_id,
                        'commission_count_category' => (int) $r->commission_count_category,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            } else {
                $this->affiliationStatsRepo->insert(
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int) $r->genre_id,
                        'commission_count_category' => (int) $r->commission_count_category,
                        'created' => date("Y/m/d H:i:s", time()),
                        'created_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            }
        }

        $list = $this->affiliationStatsRepo->getCommissionGroupCategoryCountInitialize();
        foreach ($list as $r) {
            if ($r->id != null && !empty($r->id)) {
                $this->affiliationStatsRepo->update(
                    $r->id,
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int) $r->genre_id,
                        'commission_count_category' => 0,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            } else {
                $this->affiliationStatsRepo->insert(
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int) $r->genre_id,
                        'commission_count_category' => 0,
                        'created' => date("Y/m/d H:i:s", time()),
                        'created_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            }
        }
    }

    /**
     * Update record in table affiliation_stats(orders_count_category)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setCommissionGroupCategoryOrderCount()
    {
        $status = getDivValue(self::DIV_CONSTRUCTION_STATUS, self::CONSTRUCTION_STATUS);
        $list = $this->commissionInfoRepo->getWithRelForGroupCategoryByComStatus($status);
        foreach ($list as $r) {
            if (isset($r->affiliation_stat_id)) {
                $this->affiliationStatsRepo->update(
                    $r->affiliation_stat_id,
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int) $r->genre_id,
                        'orders_count_category' => (int) $r->orders_count_category,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            } else {
                $this->affiliationStatsRepo->insert(
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int) $r->genre_id,
                        'orders_count_category' => (int) $r->orders_count_category,
                        'created' => date("Y/m/d H:i:s", time()),
                        'created_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            }
        }

        $list = $this->affiliationStatsRepo->getCommissionGroupCategoryOrderCountInitialize($status);
        foreach ($list as $r) {
            $this->affiliationStatsRepo->update(
                $r->id,
                [
                    'corp_id' => $r->corp_id,
                    'genre_id' => (int) $r->genre_id,
                    'orders_count_category' => 0,
                    'modified' => date("Y/m/d H:i:s", time()),
                    'modified_user_id' => self::USER,
                ]
            );
        }
    }

    /**
     * Update record in table affiliation_stats(commission_unit_price_category)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setCommissionInfo()
    {
        DB::table('shell_work_ci')->truncate();
        DB::table('shell_work_result')->truncate();

        $sql = File::get(storage_path(self::INSERT_SHELL_WORK_CI));
        DB::unprepared($sql);

        $sql = File::get(storage_path(self::INSERT_SHELL_WORK_RESULT));
        DB::unprepared($sql);
        $list = $this->affiliationStatsRepo->getWithJoinShellWork();
        foreach ($list as $r) {
            if ($r->commission_unit_price_category != 0) {
                $commissionUnitPriceCategory = (int)($r->commission_unit_price + (int) $r->commission_unit_price_category) / 2;
            } else {
                $commissionUnitPriceCategory = (int) $r->commission_unit_price;
            }

            if ($r->id != null && !empty($r->id)) {
                $this->affiliationStatsRepo->update(
                    $r->id,
                    [
                        'corp_id' => (int)$r->corp_id,
                        'genre_id' => (int) $r->genre_id,
                        'commission_unit_price_category' => $commissionUnitPriceCategory,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            } else {
                $this->affiliationStatsRepo->insert(
                    [
                        'corp_id' => (int)$r->corp_id,
                        'genre_id' => (int) $r->genre_id,
                        'commission_unit_price_category' => $commissionUnitPriceCategory,
                        'created' => date("Y/m/d H:i:s", time()),
                        'created_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            }
        }
    }
}
