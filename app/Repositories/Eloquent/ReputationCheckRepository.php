<?php

namespace App\Repositories\Eloquent;

use App\Models\ReputationChecks;
use App\Repositories\ReputationCheckRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ReputationCheckRepository extends SingleKeyModelRepository implements ReputationCheckRepositoryInterface
{
    /**
     * @var ReputationChecks
     */
    protected $model;

    /**
     * ReputationCheckRepository constructor.
     *
     * @param ReputationChecks $model
     */
    public function __construct(ReputationChecks $model)
    {
        $this->model = $model;
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
     * @param integer $corpId
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|mixed|null|object|static
     */
    public function findHistoryByCorpId($corpId, $type = 'first')
    {
        $query = $this->model->where('corp_id', $corpId)
            ->select('date')
            ->selectRaw(DB::raw("COALESCE(m_users.user_name, reputation_checks.created_user_id) AS created_user"))
            ->leftJoin('m_users', 'm_users.user_id', '=', 'reputation_checks.created_user_id')
            ->where('corp_id', $corpId)
            ->orderBy('date', 'desc');
        if ($type == 'first') {
            $result = $query->first();
        } else {
            $result = $query->get();
        }
        return $result;
    }

    /**
     * get list corp from with pagination
     *
     * @param  integer $page
     * @param  integer $limit
     * @return array|mixed
     */
    public function getListCorpReport($page = 1, $limit = 100)
    {
        $query = $this->selectParam();
        $query = $this->whereJoinParam($query);
        $total = $query->count();
        $query = $this->orderGroupByParam($query);
        $query->limit($limit)->skip(($page - 1) * $limit);
        $result = $query->get()->toArray();
        $numberPage = (int)($total / $limit);
        if ($total % $limit != 0) {
            $numberPage += 1;
        }
        return [
            'total' => $total,
            'listCorp' => $result,
            'pageNumber' => $numberPage,
            'curPage' => $page
        ];
    }

    /**
     * create query select builder
     *
     * @return Builder $query
     */
    private function selectParam()
    {
        $query = $this->model->select('m_corps.id', 'm_corps.official_corp_name', 'm_corps.commission_dial');
        $query->addSelect(DB::raw('MAX("reputation_checks"."date") AS last_reputation_date'));
        $query->addSelect(
            DB::raw(
                'concat((("m_corps"."antisocial_check_month" + 4) % 12 + 1), \'月\') AS schedule_month'
            )
        );
        return $query;
    }

    /**
     * add where and join param to query builder
     *
     * @param  Builder $query
     * @return Builder
     */
    private function whereJoinParam(Builder $query)
    {
        $query->rightJoin('m_corps', 'm_corps.id', '=', 'reputation_checks.corp_id');
        $query->whereNotIn('m_corps.id', [1751, 1755, 3539])
            ->where('m_corps.affiliation_status', '=', 1)
            ->whereRaw('NOT (COALESCE("m_corps"."corp_commission_status", 0) = 2)')
            ->where('m_corps.del_flg', '=', 0)
            ->where('m_corps.last_antisocial_check', '=', DB::raw('\'OK\''))
            ->where(
                function ($where) {
                    /* @var Builder $where */
                    $where->whereRaw(
                        'now() >= to_timestamp(
                            (date_part(\'year\', "reputation_checks"."date") + 
                            CASE WHEN date_part(\'month\', "reputation_checks"."date") <
                            (("m_corps"."antisocial_check_month"+ 4) % 12 + 1) THEN 0 ELSE 1 END) 
                            || \'/\' ||
                            (("m_corps"."antisocial_check_month" + 4) % 12 + 1) || \'/1\', \'YYYY/MM/DD\'
                        )'
                    )
                        ->orWhere(
                            function ($where2) {
                                /* @var Builder $where2 */
                                $where2->whereNull('reputation_checks.id')
                                    ->whereRaw(
                                        'now() >= (
                                SELECT MIN(DATE_TRUNC(\'month\',"antisocial_checks"."date" + interval \'4 month\')) 
                                FROM antisocial_checks WHERE "m_corps"."id" = "antisocial_checks"."corp_id")'
                                    );
                            }
                        );
                }
            )
            ->whereNotExists(
                function ($select) {
                    /**
                * @var Builder $select
                */
                    $select->select(DB::raw(1))->from(DB::raw('reputation_checks AS RC'))
                        ->where(DB::raw('RC.corp_id'), '=', DB::raw('"reputation_checks"."corp_id"'))
                        ->where(DB::raw('RC.created'), '>', DB::raw('"reputation_checks"."created"'));
                }
            );
        return $query;
    }

    /**
     * add order and group by to query builder
     *
     * @param  Builder $query
     * @return Builder
     */
    private function orderGroupByParam(Builder $query)
    {
        $query->groupBy('m_corps.id')
            ->orderByRaw('(MAX("reputation_checks"."date") Is NULL) desc')
            ->orderByRaw('MAX("reputation_checks"."date") asc')
            ->orderBy('m_corps.id', 'asc');
        return $query;
    }

    /**
     * get data to save to CSV file
     *
     * @return array
     */
    public function getListCorpReportDownload()
    {
        $query = $this->selectParamDownloadCsv();
        $query = $this->whereJoinParam($query);
        $query = $this->orderGroupByParam($query);
        return $query->get()->toArray();
    }

    /**
     * @return Builder
     */
    private function selectParamDownloadCsv()
    {
        $query = $this->model->select(
            'm_corps.id',
            'm_corps.official_corp_name',
            'm_corps.commission_dial',
            'm_corps.corp_name_kana'
        );
        $query->addSelect(DB::raw('MAX("reputation_checks"."date") AS last_reputation_date'));
        $query->addSelect(
            DB::raw(
                'concat((("m_corps"."antisocial_check_month" + 4) % 12 + 1), \'月\') AS schedule_month'
            )
        );
        return $query;
    }

    /**
     * check corp_id exist in reputation_checks to update or insert new
     *
     * @param integer $id
     * @return boolean
     */
    public function updateDateTime($id)
    {
        $curDate = date('Y-m-d H:i:s');
        $model = $this->getBlankModel();
        $model->corp_id = $id;
        $model->date = $curDate;
        $model->created = $curDate;
        $model->modified = $curDate;
        $model->created_user_id = \Auth::user()->user_id;
        $model->modified_user_id = \Auth::user()->user_id;
        return $model->save();
    }

    /**
     * @return \App\Models\Base|ReputationChecks|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new ReputationChecks();
    }
}
