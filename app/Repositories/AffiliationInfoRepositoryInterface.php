<?php

namespace App\Repositories;

interface AffiliationInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $corpId
     * @return mixed
     */
    public function getIdByCorpId($corpId);
    /**
     * @param integer $corpId
     * @return array
     */
    public function findAffiliationInfoByCorpId($corpId);

    /**
     * @param array $data
     * @return mixed
     */
    public function updateByCorpId($data);

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateById($id, $data);

    /**
     * Get list corp_id in week
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getCommissionWeekCountOfAffiliation();

    /**
     * Get list corp_id by status
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $status
     * @return mixed
     */
    public function getReceiptCountInitialize($status);

    /**
     * Get affiliation_infos join shell_work_result
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getWithJoinShellWork();

    /**
     * Get list corp_id
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getReceiptRateInitialize();

    /**
     * @param array $data
     * @return mixed
     */
    public function updateRecord($data);

    /**
     * @return array
     */
    public function getCommissionCountOfAffiliationInitialize();
}
