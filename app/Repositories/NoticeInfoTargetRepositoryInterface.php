<?php

namespace App\Repositories;

interface NoticeInfoTargetRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $noticeId
     * @return mixed
     */
    public function findCorpListByNoticeInfoId($noticeId);

    /**
     * @param string $user
     * @param integer $noticeId
     * @param array $listOfCorpIds
     * @return mixed
     */
    public function updateCorpListOfNoticeInfo($user, $noticeId, $listOfCorpIds);

    /**
     * @param integer $noticeId
     * @return mixed
     */
    public function removeCorpListOfNoticeInfo($noticeId);
}
