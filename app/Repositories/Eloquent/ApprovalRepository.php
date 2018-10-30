<?php

namespace App\Repositories\Eloquent;

use App\Models\Approval;
use App\Repositories\ApprovalRepositoryInterface;
use DB;

class ApprovalRepository extends SingleKeyModelRepository implements ApprovalRepositoryInterface
{
    /**
     * @var Approval
     */
    protected $model;

    /**
     * AuctionGenreAreaRepository constructor.
     *
     * @param Approval $model
     */
    public function __construct(Approval $model)
    {
        $this->model = $model;
    }

    /**
     * @return Approval|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new Approval();
    }

    /**
     * Get list have paginate approvals, commission_applications, m_corps
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getApprovalForReport()
    {
        return $this->model->leftJoin(
            'commission_applications',
            function ($join) {
                $join->on('approvals.relation_application_id', '=', 'commission_applications.id')->where('approvals.application_section', '=', 'CommissionApplication');
            }
        )->join('m_corps', 'commission_applications.corp_id', '=', 'm_corps.id')->where('approvals.status', '=', -1)->select(
            [
                "approvals.id",
                "commission_id",
                "application_section",
                "official_corp_name",
                "corp_id",
                "application_user_id",
                "application_datetime",
                "application_reason",
                "chg_deduction_tax_include",
                "deduction_tax_include",
                "chg_irregular_fee_rate",
                "irregular_fee_rate",
                "chg_irregular_fee",
                "irregular_fee",
                "irregular_reason",
                "chg_introduction_free",
                "introduction_free",
                "chg_ac_commission_exclusion_flg",
                "ac_commission_exclusion_flg",
                ]
        )->orderBy('approvals.id', 'asc')->paginate(\Config::get('datacustom.limit'));
    }

    /**
     * @param integer $groupId
     * @return \Illuminate\Support\Collection
     */
    public function getApprovalForCropCategoryAppAdmin($groupId)
    {
        return $this->model->leftJoin(
            "corp_category_applications",
            function ($join) {
                $join->on("approvals.relation_application_id", "=", "corp_category_applications.id");
                $join->where("approvals.application_section", "CorpCategoryApplication");
            }
        )
            ->join("m_corps", "m_corps.id", "=", "corp_category_applications.corp_id")
            ->leftJoin("m_genres", "m_genres.id", "=", "corp_category_applications.genre_id")
            ->leftJoin("m_categories", "m_categories.id", "=", "corp_category_applications.category_id")
            ->where("approvals.status", -1)
            ->where("corp_category_applications.group_id", $groupId)
            ->orderBy("approvals.id", "asc")
            ->select(
                ["approvals.id", "approvals.relation_application_id", "approvals.application_section",
                "approvals.approval_user_id", "approvals.approval_datetime", "approvals.application_reason",
                "approvals.application_user_id", "approvals.application_datetime", "approvals.status",
                "approvals.created", "approvals.created_user_id", "approvals.modified", "approvals.modified_user_id",
                "corp_category_applications.id as CCAId", "corp_category_applications.corp_id", "corp_category_applications.group_id",
                "corp_category_applications.order_fee", "corp_category_applications.order_fee_unit",
                "corp_category_applications.introduce_fee", "corp_category_applications.note",
                "corp_category_applications.corp_commission_type", "m_corps.id as MCorpId", "m_corps.official_corp_name",
                "m_genres.genre_name", "m_categories.category_name"]
            )
            ->get();
    }

    /**
     * @param integer $groupId
     * @param array $approvalIds
     * @param integer $userId
     * @return \Illuminate\Support\Collection
     */
    public function getApprovalForCropCategoryAppAdminService($groupId, $approvalIds, $userId)
    {
        return $this->model->leftJoin(
            "corp_category_applications",
            function ($join) {
                $join->on("approvals.relation_application_id", "=", "corp_category_applications.id");
                $join->where("approvals.application_section", "CorpCategoryApplication");
            }
        )->where("approvals.status", -1)
            ->where("corp_category_applications.group_id", $groupId)
            ->whereIn("approvals.id", $approvalIds)
            ->where("approvals.application_user_id", "<>", $userId)
            ->select(["corp_category_applications.*", "corp_category_applications.id as CCAId", "approvals.*"])
            ->get();
    }

    /**
     * query for report application answer
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getApplicationAnswer()
    {
        $results = $this->model
            ->select(
                'approvals.*',
                'm_corps.official_corp_name',
                'commission_applications.chg_deduction_tax_include',
                'commission_applications.deduction_tax_include',
                'commission_applications.chg_irregular_fee_rate',
                'commission_applications.irregular_fee_rate',
                'commission_applications.chg_irregular_fee',
                'commission_applications.irregular_fee',
                'commission_applications.irregular_reason',
                'commission_applications.introduction_free',
                'commission_applications.chg_introduction_free',
                'commission_applications.chg_ac_commission_exclusion_flg',
                'commission_applications.ac_commission_exclusion_flg',
                'm_corps.id AS corp_id',
                'commission_applications.commission_id'
            )
            ->leftJoin(
                'commission_applications',
                function ($join) {
                    $join->on('approvals.relation_application_id', '=', 'commission_applications.id');
                    $join->on('approvals.application_section', '=', DB::raw("'CommissionApplication'"));
                }
            )
            ->join('m_corps', 'commission_applications.corp_id', '=', 'm_corps.id')
            ->orderBy('approvals.id', 'desc')->paginate(\Config::get('datacustom.limit'));
        return $results;
    }

    /**
     * query for report application answer get file csv
     * @return array
     */
    public function getApplicationAnswerCsv()
    {
        $results = $this->model
            ->select(
                DB::raw(
                    "approvals.id,
					        CASE WHEN approvals.application_section = 'CommissionApplication' THEN '取次管理' END AS custom__application_section,
							approvals.application_user_id, approvals.application_datetime, approvals.application_reason,
							commission_applications.deduction_tax_include, commission_applications.irregular_fee_rate, commission_applications.irregular_fee,
							(SELECT item_name FROM m_items WHERE m_items.item_category = 'イレギュラー理由' AND m_items.item_id = commission_applications.irregular_reason) AS custom__irregular_reason,
							CASE WHEN commission_applications.introduction_free = 1 THEN '有効' ELSE '無効' END AS custom__introduction_free,
							CASE WHEN commission_applications.ac_commission_exclusion_flg = TRUE THEN '除外する' ELSE '除外しない' END AS custom__ac_commission_exclusion_flg,
							m_corps.official_corp_name, commission_applications.commission_id, commission_applications.demand_id, commission_applications.corp_id,
							(SELECT item_name FROM m_items WHERE m_items.item_category = '申請' AND m_items.item_id = approvals.status) AS custom__status, approvals.approval_user_id, approvals.approval_datetime"
                )
            )
            ->leftJoin(
                'commission_applications',
                function ($join) {
                    $join->on('approvals.relation_application_id', '=', 'commission_applications.id');
                    $join->on('approvals.application_section', '=', DB::raw("'CommissionApplication'"));
                }
            )
            ->join('m_corps', 'commission_applications.corp_id', '=', 'm_corps.id')
            ->orderBy('approvals.id', 'desc')->get()->toarray();
        return $results;
    }

    /**
     * @param array $data
     * @return Approval|\App\Models\Base|bool|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function save($data)
    {
        $approval = $this->getBlankModel();

        if (isset($data['id'])) {
            $approval = $this->model->where('id', $data['id'])->first();
        }

        if (isset($data['application_section'])) {
            $approval->application_section = $data['application_section'];
        }
        if (isset($data['relation_application_id'])) {
            $approval->relation_application_id = $data['relation_application_id'];
        }
        if (isset($data['application_reason'])) {
            $approval->application_reason = $data['application_reason'];
        }
        if (isset($data['application_user_id'])) {
            $approval->application_user_id = $data['application_user_id'];
        }

        $approval->application_datetime = date('Y-m-d H:i:s');
        $approval->created = date('Y-m-d H:i:s');
        $approval->created_user_id = auth()->user()->user_id;
        $approval->modified = date('Y-m-d H:i:s');
        $approval->modified_user_id = auth()->user()->user_id;
        $approval->save();

        return $approval;
    }

    /**
     * @param integer $groupId
     * @param integer $pageNumber
     * @return \Illuminate\Support\Collection
     */
    public function getApprovalForCropCategoryAppAnswer($groupId, $pageNumber)
    {
        return $this->model->leftJoin(
            "corp_category_applications",
            function ($join) {
                $join->on("approvals.relation_application_id", "=", "corp_category_applications.id");
                $join->where("approvals.application_section", "CorpCategoryApplication");
            }
        )
            ->join("m_corps", "m_corps.id", "=", "corp_category_applications.corp_id")
            ->leftJoin("m_genres", "m_genres.id", "=", "corp_category_applications.genre_id")
            ->leftJoin("m_categories", "m_categories.id", "=", "corp_category_applications.category_id")
            ->where("corp_category_applications.group_id", $groupId)
            ->orderBy("approvals.id", "asc")
            ->select(
                ["approvals.id", "approvals.relation_application_id", "approvals.application_section",
                "approvals.approval_user_id", "approvals.approval_datetime", "approvals.application_reason",
                "approvals.application_user_id", "approvals.application_datetime", "approvals.status",
                "approvals.created", "approvals.created_user_id", "approvals.modified", "approvals.modified_user_id",
                "corp_category_applications.id as CCAId", "corp_category_applications.corp_id", "corp_category_applications.group_id",
                "corp_category_applications.order_fee", "corp_category_applications.order_fee_unit",
                "corp_category_applications.introduce_fee", "corp_category_applications.note",
                "corp_category_applications.corp_commission_type", "m_corps.id as MCorpId", "m_corps.official_corp_name",
                "m_genres.genre_name", "m_categories.category_name"]
            )
            ->paginate($pageNumber);
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCorpCategoryGroupApplicationAdmin()
    {
        $query = $this->model->leftJoin(
            'corp_category_applications',
            function ($join) {
                $join->on('approvals.relation_application_id', '=', 'corp_category_applications.id')
                    ->where('approvals.application_section', 'CorpCategoryApplication');
            }
        )
        ->leftJoin('corp_category_group_applications', 'corp_category_group_applications.id', '=', 'corp_category_applications.group_id')
        ->join('m_corps', 'm_corps.id', '=', 'corp_category_group_applications.corp_id')
        ->where('approvals.status', -1)
        ->select(
            'corp_category_group_applications.id',
            'corp_category_group_applications.created',
            'corp_category_group_applications.created_user_id',
            'm_corps.id as m_corps_id',
            'm_corps.official_corp_name',
            DB::raw('count(corp_category_applications.id) as application_count')
        )
        ->orderBy('corp_category_group_applications.id')
        ->groupBy('corp_category_group_applications.id', 'm_corps.id')
        ->paginate(config('datacustom.report_number_row'));

        return $query;
    }


    /**
     * @param integer $id
     * @param integer $action
     * @return mixed
     */
    public function updateStatus($id, $action)
    {
        $status = $action == 'approval' ? 1 : 2;
        return $this->model->where('id', $id)->update([
            'status' => $status,
            'approval_user_id' => auth()->user()->user_id,
            'approval_datetime' => date('Y-m-d H:i:s'),
            'modified_user_id' => auth()->user()->user_id,
            'modified' => date('Y-m-d H:i:s'),
        ]);
    }
}
