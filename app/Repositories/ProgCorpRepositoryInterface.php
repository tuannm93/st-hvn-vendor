<?php

namespace App\Repositories;

interface ProgCorpRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param integer $fileId
     * @return mixed
     */
    public function getCorpWithHolidayByFileId($fileId, $corpName = '');

    /**
     * @param array $corpIds
     * @return mixed
     */
    public function getHolidayByCorpId(array $corpIds);

    /**
     * @param array $corpIds
     * @param string $importFileId
     * @return mixed
     */
    public function findByMutilCorpIdAndFileId($corpIds, $importFileId);

    /**
     * @param array $data
     * @return mixed
     */
    public function insertGetIds($data);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function countProgCropForShowDialogBox($corpId);

    /**
     * @param integer $pCorpId
     * @param array $data
     * @return mixed
     */
    public function updateProgressCorp($pCorpId, $data);

    /**
     * @param integer $pCorpId
     * @return mixed
     */
    public function getDataWithMcorpAndDemandInfoById($pCorpId);

    /**
     * @param integer $corpId
     * @param integer $fileId
     * @return mixed
     */
    public function findFirstByCorpIdAndFileId($corpId, $fileId);

    /**
     * @param integer $corpId
     * @param integer $fileId
     * @param bool $progressFlag
     * @return mixed
     */
    public function findProgCorpWithFlag($corpId, $fileId, $progressFlag);

    /**
     * @param array $saveData
     * @return mixed
     */
    public function updateProgCorp($saveData);
}
