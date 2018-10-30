<?php

namespace App\Services\Affiliation;

use App\Repositories\AffiliationAreaStatRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AffiliationAreaStatService
{
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    private $mCorpCategoryRepo;

    /**
     * @var AffiliationAreaStatRepositoryInterface
     */
    private $affiliationAreaStatRepo;

    /**
     * @var CommissionInfoRepositoryInterface
     */
    private $commissionInfoRepo;

    const USER = "system";

    const INSERT_SHELL_WORK_CI = "/commands/sql/insert_shell_work_ci.sql";

    const INSERT_SHELL_WORK_RESULT_AREA = "/commands/sql/insert_shell_work_result_area.sql";

    /**
     * AffiliationAreaStatService constructor.
     *
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     * @param AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepository
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     */
    public function __construct(
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepository,
        CommissionInfoRepositoryInterface $commissionInfoRepository
    ) {
        $this->mCorpCategoryRepo = $mCorpCategoryRepository;
        $this->affiliationAreaStatRepo = $affiliationAreaStatRepository;
        $this->commissionInfoRepo = $commissionInfoRepository;
    }

    /**
     * Insert record in table affiliation_area_stats
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setCreateAffiliationAreaStatData()
    {
        $list = $this->mCorpCategoryRepo->getListAndGroupByCorpDelFlagAndAffiliationStatus(["m_corps.id", "m_corp_categories.genre_id"]);
        foreach ($list as $r) {
            $affiliationAreaStats = $this->affiliationAreaStatRepo->finByCorpIdAndGenreId($r->id, $r->genre_id);
            for ($i = 1; $i <= 47; $i++) {
                $filtered = $affiliationAreaStats->filter(function ($value) use ($i) {
                    return $value->prefecture == $i;
                });

                if ($filtered->count() == 0) {
                    $this->affiliationAreaStatRepo->insert(
                        [
                            'corp_id' => $r->id,
                            'genre_id' => $r->genre_id,
                            'prefecture' => $i,
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
    }

    /**
     * Insert or Update record in table affiliation_area_stats(commission_count_category)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setAreaCommissionGroupCategoryCount()
    {
        $list = $this->commissionInfoRepo->getWithRelForGroupCategoryByPrefecture();
        foreach ($list as $r) {
            if (isset($r->affiliation_area_stat_id)) {
                $this->affiliationAreaStatRepo->update(
                    $r->affiliation_area_stat_id,
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int)$r->genre_id,
                        'prefecture' => (int)$r->address1,
                        'commission_count_category' => (int)$r->commission_count_category,
                        'modified_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                    ]
                );
            } else {
                $this->affiliationAreaStatRepo->insert(
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int) $r->genre_id,
                        'prefecture' => (int) $r->address1,
                        'commission_count_category' => (int) $r->commission_count_category,
                        'created' => date("Y/m/d H:i:s", time()),
                        'created_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            }
        }

        $list = $this->affiliationAreaStatRepo->getGroupCategoryCountInitialize();
        foreach ($list as $r) {
            if (isset($r->id)) {
                $this->affiliationAreaStatRepo->update(
                    $r->id,
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int)$r->genre_id,
                        'prefecture' => (int)$r->prefecture,
                        'commission_count_category' => 0,
                        'modified_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                    ]
                );
            } else {
                $this->affiliationAreaStatRepo->insert(
                    [
                        'corp_id' => $r->corp_id,
                        'genre_id' => (int)$r->genre_id,
                        'prefecture' => (int)$r->prefecture,
                        'commission_count_category' => 0,
                        'created' => date("Y/m/d H:i:s", time()),
                        'created_user_id' => self::USER,
                    ]
                );
            }
        }
    }

    /**
     * Insert or Update record in table affiliation_area_stats(commission_unit_price_category, commission_unit_price_rank)
     * Use in Console CalculateCommission
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function setAreaCommissionInfo()
    {
        DB::table('shell_work_ci')->truncate();
        DB::table('shell_work_result')->truncate();

        $sql = File::get(storage_path(self::INSERT_SHELL_WORK_CI));
        DB::unprepared($sql);

        $sql = File::get(storage_path(self::INSERT_SHELL_WORK_RESULT_AREA));
        DB::unprepared($sql);

        $list = $this->affiliationAreaStatRepo->getWithJoinShellWork();
        foreach ($list as $r) {
            if (is_null($r->affiliation_area_stats_id) || empty($r->affiliation_area_stats_id)) {
                $this->affiliationAreaStatRepo->insert(
                    [
                        'corp_id' => (int)$r->corp_id,
                        'genre_id' => (int)$r->genre_id,
                        'prefecture' => (int)$r->address1,
                        'commission_unit_price_category' => (int)$r->corp_fee,
                        'commission_unit_price_rank' => $r->unit_price_rank,
                        'created' => date("Y/m/d H:i:s", time()),
                        'created_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                        'modified_user_id' => self::USER,
                    ]
                );
            } else {
                $this->affiliationAreaStatRepo->update(
                    $r->affiliation_area_stats_id,
                    [
                        'corp_id' => (int)$r->corp_id,
                        'genre_id' => (int)$r->genre_id,
                        'prefecture' => (int)$r->address1,
                        'commission_unit_price_category' => (int)$r->corp_fee,
                        'commission_unit_price_rank' => $r->unit_price_rank,
                        'modified_user_id' => self::USER,
                        'modified' => date("Y/m/d H:i:s", time()),
                    ]
                );
            }
        }
    }
}
