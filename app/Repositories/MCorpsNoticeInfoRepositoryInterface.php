<?php

namespace App\Repositories;

interface MCorpsNoticeInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param string $role
     * @param integer $noticeId
     * @param string $corp
     * @return mixed
     */
    public function markReadNotice($role, $noticeId, $corp);

    /**
     * @param integer $noticeId
     * @return mixed
     */
    public function findAnswerListByNoticeInfoId($noticeId);

    /**
     * @param string $noticeInfo
     * @param string $user
     * @param string $answerValue
     * @return mixed
     */
    public function answerNotice($noticeInfo, $user, $answerValue);

    /**
     * @param integer $noticeId
     * @return mixed
     */
    public function isNoticeAnswered($noticeId);

    /**
     * @param integer $noticeId
     * @return mixed
     */
    public function getListAnswerCSV($noticeId);
}
