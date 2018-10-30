<?php

namespace App\Repositories;

interface NoticeInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $noticeId
     * @param string $mCorp
     * @return mixed
     */
    public function getNoticeInfoByAffiliation($noticeId, $mCorp);

    /**
     * @param integer $noticeId
     * @return mixed
     */
    public function getNoticeInfoByOtherRoles($noticeId);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findActiveById($id);

    /**
     * @param integer $corpId
     * @param integer $corpCommissionType
     * @return mixed
     */
    public function countUnreadNoticeInfoByCorpId($corpId, $corpCommissionType);

    /**
     * @param integer $corpId
     * @param integer $corpCreated
     * @param integer $corpCommissionType
     * @return mixed
     */
    public function countUnansweredByCorpId($corpId, $corpCreated, $corpCommissionType);

    /**
     * @param integer $corpId
     * @param integer $corpCommissionType
     * @param integer $corpCreatedDate
     * @return mixed
     */
    public function countUnreadByCorpIdAndCreatedDate($corpId, $corpCommissionType, $corpCreatedDate);

    /**
     * @param $corpId
     * @return mixed
     */
    public function getNoticeInfoStatusByCorpId($corpId);

    /**
     * save notice info
     * @param  array $fields
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function saveNoticeInfo($fields, $id = null);
}
