<?php

namespace App\Repositories\Eloquent;

use App\Models\AffiliationSub;
use App\Repositories\AffiliationSubsRepositoryInterface;

class AffiliationSubsRepository extends SingleKeyModelRepository implements AffiliationSubsRepositoryInterface
{
    /**
     * @var AffiliationSub
     */
    protected $model;

    /**
     * AffiliationSubsRepository constructor.
     *
     * @param AffiliationSub $model
     */
    public function __construct(AffiliationSub $model)
    {
        $this->model = $model;
    }

    /**
     * @return AffiliationSub|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AffiliationSub();
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
     * Acquisition of affiliated store incidental information
     *
     * @param integer $corpId
     * @return \Illuminate\Support\Collection
     */
    public function getAffiliationSubsList($corpId)
    {
        // STOP category search
        $stopCategory = $this->model->join(
            'affiliation_infos',
            function ($join) use ($corpId) {
                $join->on('affiliation_infos.id', '=', 'affiliation_subs.affiliation_id')
                    ->where('affiliation_infos.corp_id', $corpId);
            }
        )
            ->join('m_categories', 'm_categories.id', '=', 'affiliation_subs.item_id')
            ->where('affiliation_subs.item_category', config('constant.stop_category'))
            ->orderBy('m_categories.id', 'asc')
            ->pluck('m_categories.category_name', 'm_categories.id');

        return $stopCategory;
    }
}
