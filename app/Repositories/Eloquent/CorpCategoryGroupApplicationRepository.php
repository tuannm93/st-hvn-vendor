<?php

namespace App\Repositories\Eloquent;

use App\Models\CorpCategoryGroupApplication;
use App\Repositories\CorpCategoryGroupApplicationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CorpCategoryGroupApplicationRepository extends SingleKeyModelRepository implements CorpCategoryGroupApplicationRepositoryInterface
{
    /**
     * @var CorpCategoryGroupApplication
     */
    protected $model;

    /**
     * CorpCategoryGroupApplicationRepository constructor.
     *
     * @param CorpCategoryGroupApplication $model
     */
    public function __construct(CorpCategoryGroupApplication $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|CorpCategoryGroupApplication|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CorpCategoryGroupApplication();
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
     * @param $params
     * @return $this|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchCorpCategoryGroupApplication($params)
    {
        $query = $this->model
            ->join(
                'm_corps',
                function ($join) {
                    $join->on('m_corps.id', '=', 'corp_category_group_applications.corp_id');
                }
            )
            ->join(
                'corp_category_applications',
                function ($join) {
                    $join->on('corp_category_applications.group_id', '=', 'corp_category_group_applications.id');
                }
            )
            ->join(
                'approvals',
                function ($join) {
                    $join->on('corp_category_applications.id', '=', 'approvals.relation_application_id')
                        ->where('approvals.application_section', 'CorpCategoryApplication');
                }
            )
            ->select(
                'corp_category_group_applications.id as cid',
                'corp_category_group_applications.created',
                'corp_category_group_applications.created_user_id',
                'm_corps.id AS m_corps_id',
                'm_corps.official_corp_name',
                DB::raw('count(corp_category_applications.id) AS application_count'),
                DB::raw('SUM(CASE WHEN "approvals"."status" = -1 THEN 1 ELSE 0 END) AS unapproved_count'),
                DB::raw('SUM(CASE WHEN "approvals"."status" = 1 THEN 1 ELSE 0 END) AS approval_count'),
                DB::raw('SUM(CASE WHEN "approvals"."status" = 2 THEN 1 ELSE 0 END) AS reject_count')
            );

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if ($value) {
                    switch ($key) {
                        case 'corp_id':
                            $query = $query->where('m_corps.id', $value);
                            break;
                        case 'corp_name':
                            $query = $query->where('m_corps.official_corp_name', $value);
                            break;
                        case 'group_id':
                            $query = $query->where('corp_category_group_applications.id', $value);
                            break;
                        case 'application_date_from':
                            $query = $query->where('corp_category_group_applications.created', '>=', date('Y-m-d 00:00:00', strtotime($value)));
                            break;
                        case 'application_date_to':
                            $query = $query->where('corp_category_group_applications.created', '<=', date('Y-m-d 23:59:59', strtotime($value)));
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        $query = $query->groupBy('corp_category_group_applications.id', 'm_corps.id')
            ->orderBy('corp_category_group_applications.id', 'desc')
            ->paginate(config('datacustom.report_number_row'));
        return $query;
    }

    /**
     * @param array $params
     * @return $this|array
     */
    public function getDataExportCorpCateGroupApp($params)
    {
        $query = $this->model
            ->join(
                'corp_category_applications',
                function ($join) {
                    $join->on('corp_category_applications.group_id', '=', 'corp_category_group_applications.id');
                }
            )
            ->join(
                'approvals',
                function ($join) {
                    $join->on('corp_category_applications.id', '=', 'approvals.relation_application_id')
                        ->where('approvals.application_section', 'CorpCategoryApplication');
                }
            )
            ->join(
                'm_corps',
                function ($join) {
                    $join->on('m_corps.id', '=', 'corp_category_group_applications.corp_id');
                }
            )
            ->join(
                'm_genres',
                function ($join) {
                    $join->on('m_genres.id', '=', 'corp_category_applications.genre_id');
                }
            )
            ->join(
                'm_categories',
                function ($join) {
                    $join->on('m_categories.id', '=', 'corp_category_applications.category_id');
                }
            );

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if ($value) {
                    switch ($key) {
                        case 'corp_id':
                            $query = $query->where('m_corps.id', $value);
                            break;
                        case 'corp_name':
                            $query = $query->where('m_corps.official_corp_name', $value);
                            break;
                        case 'group_id':
                            $query = $query->where('corp_category_group_applications.id', $value);
                            break;
                        case 'application_date_from':
                            $query = $query->where('corp_category_group_applications.created', '>=', $value);
                            break;
                        case 'application_date_to':
                            $query = $query->where('corp_category_group_applications.created', '<=', $value);
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        $query = $query->select(
            'corp_category_group_applications.id',
            'corp_category_group_applications.corp_id',
            'corp_category_applications.category_id',
            'corp_category_applications.introduce_fee',
            'corp_category_applications.id as corp_category_applications_id',
            'corp_category_applications.genre_id',
            'corp_category_applications.order_fee',
            'corp_category_applications.note',
            'approvals.id as approvals_id',
            'approvals.application_user_id',
            'approvals.application_datetime',
            'approvals.approval_user_id',
            'approvals.approval_datetime',
            'approvals.application_reason',
            'm_corps.official_corp_name',
            'm_genres.genre_name',
            'm_categories.category_name',
            DB::raw('(CASE WHEN "approvals".application_section = \'corp_category_applications\' THEN \'カテゴリ手数料\' END) as custom_application_section'),
            DB::raw('(CASE "corp_category_applications".order_fee_unit WHEN 1 THEN \'%\' WHEN 0 THEN \'円\' ELSE \'\' END) AS custom_order_fee_unit'),
            DB::raw('(CASE WHEN "corp_category_applications".introduce_fee IS NULL THEN \'\' ELSE "corp_category_applications".introduce_fee || \'円\' END) AS custom_introduce_fee'),
            DB::raw('(CASE WHEN "corp_category_applications".corp_commission_type != 2 THEN \'成約ベース\' ELSE \'紹介ベース\' END) AS custom_corp_commission_type'),
            DB::raw('(SELECT item_name FROM m_items WHERE item_category = \'申請\' AND item_id = "approvals".status) AS custom_status')
        )
            ->orderBy('corp_category_group_applications.id', 'desc')
            ->orderBy('approvals.id')
            ->get()
            ->toArray();

        return $query;
    }
}
