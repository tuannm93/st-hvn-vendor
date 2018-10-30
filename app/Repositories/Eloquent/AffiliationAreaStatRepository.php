<?php

namespace App\Repositories\Eloquent;

use App\Models\AffiliationAreaStat;
use App\Repositories\AffiliationAreaStatRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AffiliationAreaStatRepository extends SingleKeyModelRepository implements AffiliationAreaStatRepositoryInterface
{
    /**
     * @var AffiliationAreaStat
     */
    protected $model;

    /**
     * AffiliationAreaStatRepository constructor.
     *
     * @param AffiliationAreaStat $model
     */
    public function __construct(AffiliationAreaStat $model)
    {
        $this->model = $model;
    }

    /**
     * @return AffiliationAreaStat|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AffiliationAreaStat();
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
     * find by corp id and gener id and prefecture
     *
     * @param  integer $corpId
     * @param  integer $genreId
     * @param  string  $prefecture
     * @return object
     */
    public function findByCorpIdAndGenerIdAndPrefecture($corpId, $genreId, $prefecture)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->where('genre_id', $genreId)
            ->where('prefecture', $prefecture)
            ->first();
    }

    /**
     * get data by corp_id,genre_id and prefecture
     * @param array $data
     * @param integer $prefecture
     * @return mixed
     */
    public function getByPrefecture($data, $prefecture)
    {
        return $this->model->where([
            ['corp_id', $data['id']],
            ['genre_id', $data['genre_id']],
            ['prefecture', $prefecture]
        ])->first();
    }

    /**
     * insert data
     * @param array $data
     * @param integer $prefecture
     */
    public function insertBy($data, $prefecture)
    {
        $this->model->corp_id = $data['id'];
        $this->model->genre_id = $data['genre_id'];
        $this->model->prefecture = $prefecture;
        $this->model->commission_count_category = 0;
        $this->model->orders_count_category = 0;
        $this->model->created = date("Y/m/d H:i:s", time());
        $this->model->created_user_id = Auth::user()->user_id;
        $this->model->commission_unit_price_rank = 'z';
        $this->model->save();
    }

    /**
     * @param array $data
     * @return array|mixed
     */
    public function getMaxCommissionUnitPriceCategory($data = null)
    {
        $result = $this->model->join(
            'm_corps',
            function ($join) {
                $join->on('m_corps.id', '=', 'affiliation_area_stats.corp_id');
            }
        )->join(
            'm_corp_categories',
            function ($join) use ($data) {
                    $join->on('m_corp_categories.corp_id', '=', 'm_corps.id')
                        ->where('affiliation_area_stats.genre_id', '=', DB::raw('m_corp_categories.genre_id'))
                        ->where('m_corp_categories.category_id', '=', $data['category_id']);
            }
        )
            ->where('affiliation_area_stats.commission_unit_price_category', '>', 0)
            ->where('affiliation_area_stats.commission_count_category', '>=', 5)
            ->select('affiliation_area_stats.commission_unit_price_category', 'm_corps.corp_name')
            ->orderBy('affiliation_area_stats.commission_unit_price_category', 'desc');

        $result =  $result->first();

        if (isset($result)) {
            return $result->toarray();
        }

        $result = [];
        $result['commission_unit_price_category'] = 0;
        $result['corp_name'] = "";

        return $result;
    }

    /**
     * Update data by id
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  integer $id
     * @param  array            $data
     * @return \App\Models\Base|boolean
     */
    public function update($id, $data)
    {
        return $this->model->where("id", $id)->update($data);
    }

    /**
     * Find by corp_id and genre_id
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $corpId
     * @param integer $genreId
     * @return array
     */
    public function finByCorpIdAndGenreId($corpId, $genreId)
    {
        return $this->model->where("corp_id", $corpId)->where("genre_id", $genreId)->get();
    }

    /**
     * Get list affiliation_area_stats join subQuery
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return array
     */
    public function getGroupCategoryCountInitialize()
    {
        $subQuery = "(select corp_id, genre_id, address1 FROM commission_infos inner join demand_infos "
            ."on(commission_infos.demand_id = demand_infos.id) where commission_infos.lost_flg = 0 "
            ."and commission_infos.unit_price_calc_exclude = 0 group by commission_infos.corp_id, "
            ."demand_infos.genre_id, demand_infos.address1) as \"subQuery\"";

        return $this->model->leftJoin(
            DB::raw($subQuery),
            function ($q) {
                $q->on("subQuery.corp_id", "=", "affiliation_area_stats.corp_id");
                $q->on("subQuery.genre_id", "=", "affiliation_area_stats.genre_id");
                $q->on("subQuery.address1", "=", "affiliation_area_stats.prefecture");
            }
        )->whereNull("subQuery.corp_id")->where("affiliation_area_stats.commission_count_category", ">", 0)
            ->select(
                ["affiliation_area_stats.id", "affiliation_area_stats.corp_id",
                "affiliation_area_stats.genre_id", "affiliation_area_stats.prefecture"]
            )->get();
    }

    /**
     * Get list affiliation_area_stats order by corp_id, genre_id, address1
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return array
     */
    public function getWithJoinShellWork()
    {
        $subQuery = "case when (select max(commission_count_category) from affiliation_area_stats "
            ."where corp_id = \"AAS\".corp_id and genre_id = \"AAS\".genre_id and prefecture = \"AAS\".prefecture) <= 5 then 'z' "
            ."when(max(\"MG\".targer_commission_unit_price) is null) or (max(\"MG\".targer_commission_unit_price) = 0) "
            ."then 'a' else 'd' end as unit_price_rank";

        $first = DB::table("affiliation_area_stats as AAS")->leftJoin(
            "shell_work_result as t1",
            function ($q) {
                $q->on("AAS.corp_id", "=", "t1.corp_id");
                $q->on("AAS.genre_id", "=", "t1.genre_id");
                $q->on("AAS.prefecture", "=", "t1.address1");
            }
        )->leftJoin(
            "m_genres as MG",
            function ($q) {
                    $q->on("AAS.genre_id", "=", "MG.id");
                    $q->where("MG.valid_flg", 1);
            }
        )->whereNull("t1.corp_id")
            ->where("AAS.commission_unit_price_category", ">", 0)
            ->groupBy(
                "AAS.corp_id",
                "AAS.genre_id",
                "AAS.prefecture",
                "AAS.id"
            )
            ->orderByRaw("\"corp_id\", \"genre_id\", \"address1\"")
            ->select(
                ["AAS.corp_id as corp_id", "AAS.genre_id as genre_id",
                "AAS.prefecture as address1", DB::raw("0 as corp_fee"),
                DB::raw($subQuery), "AAS.id as affiliation_area_stats_id"]
            );

        return DB::table("shell_work_result as t1")->leftJoin(
            "affiliation_area_stats as t2",
            function ($q) {
                $q->on("t2.corp_id", "=", "t1.corp_id");
                $q->on("t2.genre_id", "=", "t1.genre_id");
                $q->on("t2.prefecture", "=", "t1.address1");
            }
        )->select(
            ["t1.corp_id as corp_id", "t1.genre_id as genre_id", "t1.address1 as address1",
                "t1.commission_unit_price as corp_fee", "t1.commission_unit_price_rank as unit_price_rank",
                "t2.id as affiliation_area_stats_id"]
        )->unionAll($first)->get();
    }
}
