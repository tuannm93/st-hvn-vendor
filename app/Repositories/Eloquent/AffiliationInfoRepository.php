<?php

namespace App\Repositories\Eloquent;

use App\Models\AffiliationInfo;
use App\Repositories\AffiliationInfoRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AffiliationInfoRepository extends SingleKeyModelRepository implements AffiliationInfoRepositoryInterface
{
    /**
     * @var AffiliationInfo
     */
    protected $model;

    /**
     * AffiliationInfoRepository constructor.
     *
     * @param AffiliationInfo $model
     */
    public function __construct(AffiliationInfo $model)
    {
        $this->model = $model;
    }


    /**
     * @param $corpId
     * @return array|mixed
     */
    public function getIdByCorpId($corpId)
    {
        return $this->model->select('id')->whereIn('corp_id', $corpId)->get()->toArray();
    }

    /**
     * @param integer $corpId
     * @return array
     */
    public function findAffiliationInfoByCorpId($corpId)
    {
        $affiliationInfo = $this->model->with('mCorp')->where('corp_id', '=', $corpId)->first();
        if (isset($affiliationInfo)) {
            return $affiliationInfo->toArray();
        } else {
            return $affiliationInfo;
        }
    }

    /**
     * @return AffiliationInfo|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AffiliationInfo();
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
     * find all
     *
     * @return object
     */
    public function findAll()
    {
        $query = DB::table('affiliation_infos as t1')
            ->select(
                't1.id',
                't1.capital_stock as capitalStock',
                't1.listed_kind as listedKind',
                't1.corp_id as corpId',
                't0.corp_kind as corpKind',
                't0.corp_name as corpName',
                't0.official_corp_name as officialCorpName',
                't2.id as corpAgreementId',
                't2.status as agreementStatus',
                't2.transactions_law_date as transactionsLawDate',
                't2.hansha_check as hanshaCheckStatus',
                't2.hansha_check_date as hanshaCheckDate',
                't3.corp_id as customizeLabel',
                'u1.user_name as hanshaCheckUserName',
                'u2.user_name as transactionsLawUserName'
            )
            ->join('m_corps as t0', 't1.corp_id', '=', 't0.id')
            ->leftJoin(
                'corp_agreement as t2',
                function ($join) {
                    $join->on('t2.corp_id', '=', 't1.corp_id')
                        ->on(
                            't2.id',
                            '=',
                            DB::raw(
                                "
                        (SELECT MAX(t4.id) 
                        FROM m_corps as t5, corp_agreement as t4, m_corps as t6 
                        WHERE (((t5.id = t6.id) 
                          AND (t4.delete_flag = false)) 
                          AND ((t5.id = t4.corp_id) 
                          AND (t6.id = t2.corp_id))))
                      "
                            )
                        );
                }
            )
            ->leftJoin('agreement_customize as t3', 't3.corp_id', '=', 't1.corp_id')
            ->leftJoin('m_users as u1', 't2.hansha_check_user_id', '=', 'u1.user_id')
            ->leftJoin('m_users as u2', 't2.transactions_law_user_id', '=', 'u2.user_id')
            ->where(
                [
                    ['t0.agreement_target_flag', true],
                    ['t0.affiliation_status', 1],
                    ['t0.del_flg', 0]
                ]
            )
            ->distinct();
        return $query;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateByCorpId($data)
    {
        return $this->model->where('corp_id', $data['corp_id'])->update($data);
    }

    /**
     * @param integer $id
     * @param array $data
     * @return bool
     */
    public function updateById($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param integer $corpId
     */
    public function getMCorp($corpId)
    {
        $this->model->getOneMCorp($corpId)->first();
    }

    /**
     * Use in AffiliationInfoService
     *
     * @return array
     */
    public function getCommissionCountOfAffiliationInitialize()
    {
        $subQuery = "(select corp_id FROM commission_infos where lost_flg = 0 and unit_price_calc_exclude = 0 "
            . "group by corp_id) as \"subQuery\"";

        return $this->model->leftJoin(DB::raw($subQuery), "subQuery.corp_id", "=", "affiliation_infos.corp_id")
            ->whereNull("subQuery.corp_id")->where("affiliation_infos.commission_count", ">", 0)
            ->select("affiliation_infos.corp_id")->get();
    }

    /**
     * Get list corp_id in week
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return array
     */
    public function getCommissionWeekCountOfAffiliation()
    {
        $subQuery = "(select corp_id FROM commission_infos where lost_flg = 0 and unit_price_calc_exclude = 0 "
            . "and created >= '%s' and to_char(created, 'yyyy/mm/dd') <= '%s' group by corp_id) as \"subQuery\"";
        $subQuery = vsprintf(
            $subQuery,
            [
                date('Y/m/d', strtotime('-8 day')),
                date('Y/m/d', strtotime('-1 day'))
            ]
        );

        return $this->model->leftJoin(DB::raw($subQuery), "subQuery.corp_id", "=", "affiliation_infos.corp_id")
            ->whereNull("subQuery.corp_id")->where("affiliation_infos.weekly_commission_count", ">", 0)
            ->select("affiliation_infos.corp_id")->get();
    }

    /**
     * Get list corp_id by status
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $status
     * @return array
     */
    public function getReceiptCountInitialize($status)
    {
        $subQuery = "(select corp_id FROM commission_infos where commission_status = $status and lost_flg = 0 "
            . "and unit_price_calc_exclude = 0 group by corp_id) as \"subQuery\"";

        return $this->model->leftJoin(DB::raw($subQuery), "subQuery.corp_id", "=", "affiliation_infos.corp_id")
            ->whereNull("subQuery.corp_id")->where("affiliation_infos.orders_count", ">", 0)
            ->select("affiliation_infos.corp_id")->get();
    }

    /**
     * Get affiliation_infos join shell_work_result
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return array
     */
    public function getWithJoinShellWork()
    {
        $first = $this->model->leftJoin(
            "shell_work_result",
            "affiliation_infos.corp_id",
            "=",
            "shell_work_result.corp_id"
        )
            ->whereNull("shell_work_result.corp_id")
            ->where("affiliation_infos.commission_unit_price", ">", 0)
            ->groupBy("affiliation_infos.corp_id")
            ->select(["affiliation_infos.corp_id", DB::raw("0 as commission_unit_price_category")]);

        return DB::table("shell_work_result as t1")->groupBy("t1.corp_id")->select(
            [
                "t1.corp_id",
                DB::raw("sum(COALESCE(t1.total_corp_fee,0)) / sum(t1.target_count) as commission_unit_price_category")
            ]
        )
            ->unionAll($first)->get();
    }

    /**
     * Get list corp_id
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return array
     */
    public function getReceiptRateInitialize()
    {
        $subQuery = "(select corp_id FROM commission_infos where lost_flg = 0 "
            . "and unit_price_calc_exclude = 0 group by corp_id) as \"subQuery\"";

        return $this->model->leftJoin(DB::raw($subQuery), "subQuery.corp_id", "=", "affiliation_infos.corp_id")
            ->whereNull("subQuery.corp_id")
            ->select("affiliation_infos.corp_id")->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function updateRecord($data)
    {
        return $this->model->where('id', '>', 0)->update($data);
    }
}
