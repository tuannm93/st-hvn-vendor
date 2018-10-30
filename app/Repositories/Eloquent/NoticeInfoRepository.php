<?php

namespace App\Repositories\Eloquent;

use App\Models\NoticeInfo;
use App\Repositories\NoticeInfoRepositoryInterface;

use App\Repositories\MTargetAreaRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

class NoticeInfoRepository extends SingleKeyModelRepository implements NoticeInfoRepositoryInterface
{
    /**
     * @var NoticeInfo
     */
    protected $model;

    /**
     * @var MTargetAreaRepositoryInterface
     */
    protected $mTargetAreaRepo;

    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    protected $mCorpTargetArea;

    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategory;

    /**
     * NoticeInfoRepository constructor.
     *
     * @param NoticeInfo                         $noticeInfo
     * @param MTargetAreaRepositoryInterface     $mTargetAreaRepo
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetArea
     * @param MCorpCategoryRepositoryInterface   $mCorpCategory
     */
    public function __construct(
        NoticeInfo $noticeInfo,
        MTargetAreaRepositoryInterface $mTargetAreaRepo,
        MCorpTargetAreaRepositoryInterface $mCorpTargetArea,
        MCorpCategoryRepositoryInterface $mCorpCategory
    ) {
        $this->model = $noticeInfo;
        $this->mTargetAreaRepo = $mTargetAreaRepo;
        $this->mCorpTargetArea = $mCorpTargetArea;
        $this->mCorpCategory = $mCorpCategory;
    }

    /**
     * @return \App\Models\Base|NoticeInfo|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new  NoticeInfo();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * @param integer $noticeId
     * @param object $corp
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getNoticeInfoByAffiliation($noticeId, $corp)
    {
        return $this->model->leftJoin(
            'm_corps_notice_infos',
            function ($join) use ($corp) {
                return $join->on('notice_infos.id', '=', 'm_corps_notice_infos.notice_info_id')->where('m_corps_notice_infos.m_corp_id', $corp->id);
            }
        )->where('del_flg', 0)->where('notice_infos.id', $noticeId)->where(
            function ($query) use ($corp) {
                    return $query->where(
                        function ($q) use ($corp) {
                            return $q->where('notice_infos.corp_commission_type', $corp->corp_commission_type)->where('notice_infos.is_target_selected', false);
                        }
                    )->orWhere(
                        function ($q) {
                            return $q->where('notice_infos.corp_commission_type', null)->where('notice_infos.is_target_selected', false);
                        }
                    )->orWhere(
                        function ($q) use ($corp) {
                            return $q->where('notice_infos.is_target_selected', true)->whereExists(
                                function ($subQ) use ($corp
                                ) {
                                    $subQ->select(DB::raw(1))->from('notice_info_targets')
                                        ->where('notice_info_targets.notice_info_id', DB::raw('notice_infos.id'))
                                        ->where('notice_info_targets.corp_id', $corp->id);
                                }
                            );
                        }
                    );
            }
        )->select(
            [
                "notice_infos.id as notice_infos_id",
                "notice_infos.created as notice_infos_created",
                "notice_infos.*",
                "m_corps_notice_infos.*",
                ]
        )->first();
    }

    /**
     * @param integer $noticeId
     * @return \Illuminate\Database\Eloquent\Model|mixed|static
     */
    public function getNoticeInfoByOtherRoles($noticeId)
    {
        return $this->model->where('del_flg', 0)->where('id', $noticeId)->select(
            [
                'notice_infos.id as notice_infos_id',
                "notice_infos.created as notice_infos_created",
                'notice_infos.*',
            ]
        )->firstOrFail();
    }

    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findActiveById($id)
    {
        return $this->model->where('id', $id)->where('del_flg', 0)->first();
    }

    /**
     * save notice info
     * @param  array $fields
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function saveNoticeInfo($fields, $id = null)
    {
        if ($id) {
            return $this->model->where('id', $id)->update($fields);
        }
        return $this->model->create($fields);
    }

    /**
     * @param integer $id
     * @param array $fields
     * @return bool|mixed
     */
    public function updateNoticeInfo($id, $fields)
    {
        return $this->model->where('id', $id)->update($fields);
    }

    /**
     * @param integer $id
     * @return bool|mixed
     */
    public function removeNoticeInfo($id)
    {
        return $this->model->where('id', $id)->update(
            [
            'del_flg' => 1,
            ]
        );
    }

    /**
     * saveNearNoticeInfo
     * @param integer $corpId
     * @param array $saveData
     * @return boolean
     */
    public function saveNearNoticeInfo($corpId, $saveData)
    {
        try {
            DB::beginTransaction();
            $corpCategoryIds = [];
            $corpTargetAreas = [];
            if ($this->mTargetAreaRepo->saveAll($saveData)) {
                $corpTargetAreas = $this->mCorpTargetArea->getListByCorpId($corpId, true);
                foreach ($saveData as $val) {
                    if (!array_key_exists($val['corp_category_id'], $corpCategoryIds)) {
                        $corpCategoryIds[] = $val['corp_category_id'];
                    }
                }
            }

            $saveCategory = [];
            foreach ($corpCategoryIds as $val) {
                $taCount = count($corpTargetAreas);
                $ctaCount = $this->mTargetAreaRepo->getCorpCategoryTargetAreaCount3($val);
                $targetAreaType = 2;
                if ($taCount == $ctaCount) {
                    $jisCds = [];
                    foreach ($corpTargetAreas as $corpTargetArea) {
                        $jisCds[] = $corpTargetArea['jis_cd'];
                    }
                    $defaultCount = $this->mTargetAreaRepo->getCorpCategoryTargetAreaCount2($val, $jisCds);
                    if ($taCount == $defaultCount) {
                        $targetAreaType = 1;
                    }
                }
                $saveCategory[] = [
                    'id' => $val,
                    'target_area_type' => $targetAreaType,
                ];
            }
            if ($this->mCorpCategory->updateManyItemWithArray($saveCategory)) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * get list notice infos role aff
     *
     * @param integer $mCorp
     * @param array $data
     * @return Illuminate\Pagination\Paginator
     */
    public function getListNoticeInfosAff($mCorp, $data)
    {
        return $this->model->select(
            'notice_infos.*',
            'm_corps_notice_infos.answer_value',
            'm_corps_notice_infos.answer_user_id',
            'm_corps_notice_infos.answer_date',
            DB::raw('(case when (notice_infos.choices is not null and m_corps_notice_infos.answer_value is null) then 3 when m_corps_notice_infos.id is null then 2 else 1 end) as status'),
            DB::raw('(m_corps_notice_infos.id is null) as unread'),
            DB::raw('(notice_infos.choices is not null) and (m_corps_notice_infos.answer_value is null) as unanswered')
        )
            ->leftJoin(
                'm_corps_notice_infos',
                function ($join) use ($mCorp) {
                    $join->on('notice_infos.id', '=', 'm_corps_notice_infos.notice_info_id');
                    if ($mCorp) {
                        $join->where('m_corps_notice_infos.m_corp_id', $mCorp->id);
                    }
                }
            )->where('notice_infos.del_flg', 0)->where(
                function ($query) use ($mCorp) {
                        $query->orWhere(
                            function ($query) use ($mCorp) {
                                $query->where('notice_infos.corp_commission_type', $mCorp->corp_commission_type);
                                $query->where('notice_infos.is_target_selected', false);
                            }
                        );
                        $query->orWhere(
                            function ($query) {
                                $query->whereNull('notice_infos.corp_commission_type');
                                $query->where('notice_infos.is_target_selected', false);
                            }
                        );
                        $query->orWhere(
                            function ($query) use ($mCorp) {
                                $query->where('notice_infos.is_target_selected', true);
                                $query->whereExists(
                                    function ($query) use ($mCorp) {
                                        $query->select(DB::raw('1'))->from('notice_info_targets')
                                            ->where('notice_info_targets.notice_info_id', DB::raw('notice_infos.id'))
                                            ->where('notice_info_targets.corp_id', $mCorp->id);
                                    }
                                );
                            }
                        );
                }
            )->orderBy($data['orderBy'], $data['sort'])->orderBy('id', 'desc')->paginate(config('rits.list_limit'));
    }

    /**
     * get list notice infos
     *
     * @param  array $data
     * @return Illuminate\Pagination\Paginator
     */
    public function getListNoticeInfos($data)
    {
        return $this->model->select('*', DB::raw('1 as status'), DB::raw('1 as unread'), DB::raw('1 as unanswered'))->where('del_flg', 0)->orderBy($data['orderBy'], $data['sort'])->orderBy('id', 'desc')->paginate(config('rits.list_limit'));
    }

    /**
     * @param integer $corpId
     * @param integer $corpCommissionType
     * @return int|mixed
     */
    public function countUnreadNoticeInfoByCorpId($corpId, $corpCommissionType)
    {
        $query = $this->model->leftJoin(
            'm_corps_notice_infos',
            function ($join) use ($corpId) {
                /**
            * @var JoinClause $join
            */
                $join->on('notice_infos.id', '=', 'm_corps_notice_infos.notice_info_id');
                $join->where('m_corps_notice_infos.m_corp_id', '=', $corpId);
            }
        )->where(
            function ($query) {
                    /**
                * @var Builder $query
                */
                    $query->whereNull('m_corps_notice_infos.id')->orWhere(
                        function ($query) {
                            /**
                        * @var Builder $query
                        */
                            $query->whereNull('notice_infos.choices', 'and', true);
                            $query->whereNull('m_corps_notice_infos.answer_value');
                        }
                    );
            }
        );

        $this->additionQueryForGetNoticeInfoByCorpId($query, $corpId, $corpCommissionType);

        return $query->count();
    }

    /**
     * @param integer $corpId
     * @param integer $corpCommissionType
     * @param string $corpCreatedDate
     * @return integer
     */
    public function countUnreadByCorpIdAndCreatedDate($corpId, $corpCommissionType, $corpCreatedDate)
    {
        $query = $this->model->leftJoin(
            'm_corps_notice_infos',
            function ($join) use ($corpId) {
                /**
            * @var JoinClause $join
            */
                $join->on('notice_infos.id', '=', 'm_corps_notice_infos.notice_info_id');
                $join->where('m_corps_notice_infos.m_corp_id', '=', $corpId);
            }
        )->where('notice_infos.created', '>=', date('Y-m-d', strtotime('-2 week')))->where('notice_infos.created', '>=', $corpCreatedDate)->where(
            function (
                $query
            ) use ($corpCommissionType) {
                    /**
                * @var Builder $query
                */
                    $query->where('notice_infos.corp_commission_type', '=', $corpCommissionType);
                    $query->whereNull('notice_infos.corp_commission_type');
            }
        )->whereNull('m_corps_notice_infos.id');

        $this->additionQueryForGetNoticeInfoByCorpId($query, $corpId, $corpCommissionType);

        return $query->count();
    }

    /**
     * @param integer $corpId
     * @param string $corpCreated
     * @param integer $corpCommissionType
     * @return integer
     */
    public function countUnansweredByCorpId($corpId, $corpCreated, $corpCommissionType)
    {
        $query = $this->model->leftJoin(
            'm_corps_notice_infos',
            function ($join) use ($corpId) {
                /**
            * @var JoinClause $join
            */
                $join->on('notice_infos.id', '=', 'm_corps_notice_infos.notice_info_id');
                $join->where('m_corps_notice_infos.m_corp_id', '=', $corpId);
            }
        )->orWhere(
            function ($query) use ($corpCreated) {
                    /**
                * @var Builder $query
                */
                    $query->where('notice_infos.created', '>=', $corpCreated);
                    $query->whereNull('notice_infos.choices', 'and', true);
            }
        )->whereNull('m_corps_notice_infos.answer_value');

        $this->additionQueryForGetNoticeInfoByCorpId($query, $corpId, $corpCommissionType);

        return $query->count();
    }

    /**
     * @param integer $corpId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getNoticeInfoStatusByCorpId($corpId)
    {
        $query = $this->model->select(
            'notice_infos.id AS id',
            DB::raw(
                '
                (CASE WHEN (notice_infos.choices IS NOT NULL AND m_corps_notice_infos.answer_value IS NULL) THEN 3
                WHEN m_corps_notice_infos.id IS NULL THEN 2 ELSE 1 END) AS status
            '
            )
        )->leftJoin(
            'm_corps_notice_infos',
            function ($join) use ($corpId) {
                    /**
                * @var JoinClause $join
                */
                    $join->on('notice_infos.id', '=', 'm_corps_notice_infos.notice_info_id');
                    $join->where('m_corps_notice_infos.m_corp_id', '=', $corpId);
            }
        )->orderBy('notice_infos.id');
        $this->additionQueryForGetNoticeInfoByCorpId($query, $corpId, 1);

        return $query->get();
    }

    /**
     * @param Builder            $query
     * @param integer $corpId
     * @param integer $corpCommissionType
     */
    private function additionQueryForGetNoticeInfoByCorpId(&$query, $corpId, $corpCommissionType)
    {
        $query->where('notice_infos.del_flg', '=', 0);

        $query->where(
            function ($query) use ($corpId, $corpCommissionType) {
                /**
            * @var Builder $query
            */
                $query->where(
                    function ($query) use ($corpCommissionType) {
                        /**
                    * @var Builder $query
                    */
                        $query->where('notice_infos.corp_commission_type', '=', $corpCommissionType);
                        $query->where('notice_infos.is_target_selected', '=', 'FALSE');
                    }
                );
                $query->orWhere(
                    function ($query) {
                        /**
                    * @var Builder $query
                    */
                        $query->whereNull('notice_infos.corp_commission_type');
                        $query->where('notice_infos.is_target_selected', '=', 'FALSE');
                    }
                );
                $query->orWhere(
                    function ($q) use ($corpId) {
                        return $q->where('notice_infos.is_target_selected', true)->whereExists(
                            function ($subQ) use ($corpId) {
                                $subQ->select(DB::raw(1))->from('notice_info_targets')
                                    ->where('notice_info_targets.notice_info_id', DB::raw('notice_infos.id'))
                                    ->where('notice_info_targets.corp_id', $corpId);
                            }
                        );
                    }
                );
            }
        );
    }
}
