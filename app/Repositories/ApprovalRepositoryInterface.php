<?php

namespace App\Repositories;

interface ApprovalRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getApplicationAnswer();

    /**
     * @return mixed
     */
    public function getApprovalForReport();

    /**
     * @param integer $groupId
     * @return mixed
     */
    public function getApprovalForCropCategoryAppAdmin($groupId);

    /**
     * @param integer $groupId
     * @param array $approvalIds
     * @param integer $userId
     * @return mixed
     */
    public function getApprovalForCropCategoryAppAdminService($groupId, $approvalIds, $userId);

    /**
     * @return mixed
     */
    public function getApplicationAnswerCsv();

    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $groupId
     * @param integer $pageNumber
     * @return mixed
     */
    public function getApprovalForCropCategoryAppAnswer($groupId, $pageNumber);

    /**
     * @return mixed
     */
    public function getCorpCategoryGroupApplicationAdmin();

    /**
     * @param integer $id
     * @param string $action
     * @return mixed
     */
    public function updateStatus($id, $action);
}
