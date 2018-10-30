<?php

namespace App\Repositories\Eloquent;

use App\Models\NoticeInfoTarget;


use App\Repositories\NoticeInfoTargetRepositoryInterface;

class NoticeInfoTargetRepository extends SingleKeyModelRepository implements NoticeInfoTargetRepositoryInterface
{
    /**
     * @var NoticeInfoTarget
     */
    protected $model;

    /**
     * NoticeInfoTargetRepository constructor.
     *
     * @param NoticeInfoTarget $noticeInfoTarget
     */
    public function __construct(NoticeInfoTarget $noticeInfoTarget)
    {
        $this->model = $noticeInfoTarget;
    }

    /**
     * @return \App\Models\Base|NoticeInfoTarget|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new  NoticeInfoTarget();
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
     * @param $noticeId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findCorpListByNoticeInfoId($noticeId)
    {
        return $this->model->leftJoin('m_corps', 'm_corps.id', '=', 'notice_info_targets.corp_id')
            ->where('notice_info_id', $noticeId)
            ->select('corp_id', 'm_corps.corp_name AS corp_name')
            ->get();
    }

    /**
     * @param object $user
     * @param integer $noticeId
     * @param array $listOfCorpIds
     * @return mixed|void
     * @throws \Exception
     */
    public function updateCorpListOfNoticeInfo($user, $noticeId, $listOfCorpIds)
    {
        $this->removeCorpListOfNoticeInfo($noticeId);

        // Insert new list of corp ids
        foreach ($listOfCorpIds as $corpId) {
            $this->model->create(
                [
                'notice_info_id'    => $noticeId,
                'corp_id'           => $corpId,
                'modified_user_id'  => $user->user_id,
                'created_user_id'   => $user->user_id
                ]
            );
        }
    }

    /**
     * @param $noticeId
     * @return mixed|void
     * @throws \Exception
     */
    public function removeCorpListOfNoticeInfo($noticeId)
    {
        // Remove all current rows that has notice_info_id equal $noticeId
        $this->model->where('notice_info_id', $noticeId)->delete();
    }
}
