<?php

namespace App\Repositories\Eloquent;

use App\Models\AffiliationStat;
use App\Repositories\AffiliationStatsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AffiliationStatsRepository extends SingleKeyModelRepository implements AffiliationStatsRepositoryInterface
{
    /**
     * @var AffiliationStat
     */
    protected $model;

    /**
     * AffiliationStatsRepository constructor.
     *
     * @param AffiliationStat $model
     */
    public function __construct(AffiliationStat $model)
    {
        $this->model = $model;
    }

    /**
     * @return AffiliationStat|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AffiliationStat();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }


    /**
     * Acquisition of statistical information by franchise store genre
     *
     * @param integer $corpId
     * @return \Illuminate\Support\Collection
     */
    public function getAffiliationStatsList($corpId)
    {
        $results = $this->model->join('m_genres', 'm_genres.id', '=', 'affiliation_stats.genre_id')
            ->where('affiliation_stats.corp_id', $corpId)
            ->orderBy('affiliation_stats.id', 'asc')
            ->select(
                'affiliation_stats.*',
                'm_genres.genre_name'
            )->get();

        return $results;
    }

    /**
     * @param integer $id
     * @param array            $data
     * @return bool
     */
    public function update($id, $data = [])
    {
        return $this->model->where("id", $id)->update($data);
    }

    /**
     * Get first AffiliationStats by corpId and genreId
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $corpId
     * @param integer $genreId
     * @return array
     */
    public function getByCorpIdAndGenreId($corpId, $genreId)
    {
        return $this->model->where("corp_id", $corpId)->where("genre_id", $genreId)->limit(1)->first();
    }

    /**
     * Get affiliation_stats by group category
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return array
     */
    public function getCommissionGroupCategoryCountInitialize()
    {
        return $this->model->leftJoin(
            DB::raw(
                "(select corp_id, genre_id FROM commission_infos inner join demand_infos "
                    ."on(commission_infos.demand_id = demand_infos.id) where commission_infos.lost_flg = 0 "
                    ."and commission_infos.unit_price_calc_exclude = 0 group by commission_infos.corp_id, "
                ."demand_infos.genre_id) as \"subQuery\""
            ),
            function ($q) {
                $q->on("subQuery.corp_id", "=", "affiliation_stats.corp_id");
                $q->on("subQuery.genre_id", "=", "affiliation_stats.genre_id");
            }
        )->whereNull("subQuery.corp_id")->where("affiliation_stats.commission_count_category", ">", 0)
            ->select(["affiliation_stats.id", "affiliation_stats.corp_id", "affiliation_stats.genre_id"])->get();
    }

    /**
     * Get affiliation_stats by group category and commission_status
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $status
     * @return array
     */
    public function getCommissionGroupCategoryOrderCountInitialize($status)
    {
        return $this->model->leftJoin(
            DB::raw(
                "(select corp_id, genre_id FROM commission_infos inner join demand_infos "
                ."on(commission_infos.demand_id = demand_infos.id) where commission_infos.commission_status = $status "
                ."and commission_infos.lost_flg = 0 and commission_infos.unit_price_calc_exclude = 0 "
                ."group by commission_infos.corp_id, demand_infos.genre_id) as \"subQuery\""
            ),
            function ($q) {
                $q->on("subQuery.corp_id", "=", "affiliation_stats.corp_id");
                $q->on("subQuery.genre_id", "=", "affiliation_stats.genre_id");
            }
        )->whereNull("subQuery.corp_id")->where("affiliation_stats.orders_count_category", ">", 0)
            ->select(["affiliation_stats.id", "affiliation_stats.corp_id", "affiliation_stats.genre_id"])->get();
    }

    /**
     * Get affiliation_stats join shell_work_result
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return array
     */
    public function getWithJoinShellWork()
    {
        $first = $this->model->leftJoin(
            "shell_work_result",
            function ($q) {
                $q->on("affiliation_stats.corp_id", "=", "shell_work_result.corp_id");
                $q->on("affiliation_stats.genre_id", "=", "shell_work_result.genre_id");
            }
        )->where("affiliation_stats.commission_unit_price_category", ">", 0)
            ->whereNull("shell_work_result.corp_id")
            ->orderBy("corp_id")
            ->orderBy("genre_id")
            ->select(
                ["affiliation_stats.corp_id", "affiliation_stats.genre_id", DB::raw("0 as commission_unit_price"),
                DB::raw("0 as commission_unit_price_category"), "affiliation_stats.id"]
            );
        return DB::table('shell_work_result')->leftJoin(
            "affiliation_stats",
            function ($q) {
                $q->on("affiliation_stats.corp_id", "=", "shell_work_result.corp_id");
                $q->on("affiliation_stats.genre_id", "=", "shell_work_result.genre_id");
            }
        )->select(
            ["shell_work_result.corp_id", "shell_work_result.genre_id", "shell_work_result.commission_unit_price",
                DB::raw("0 as commission_unit_price_category"), "affiliation_stats.id"]
        )
            ->unionAll($first)->get();
    }
}
