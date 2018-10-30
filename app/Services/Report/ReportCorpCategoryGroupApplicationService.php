<?php

namespace App\Services\Report;

use App\Repositories\ApprovalRepositoryInterface;
use App\Repositories\CorpCategoryGroupApplicationRepositoryInterface;

class ReportCorpCategoryGroupApplicationService
{
    /**
     * @var CorpCategoryGroupApplicationRepositoryInterface
     */
    protected $corpCateGroupAppRepository;
    /**
     * @var ApprovalRepositoryInterface
     */
    protected $approval;

    /**
     * ReportCorpCategoryGroupApplicationService constructor.
     *
     * @param CorpCategoryGroupApplicationRepositoryInterface $corpCateGroupAppRepository
     * @param ApprovalRepositoryInterface                     $approval
     */
    public function __construct(
        CorpCategoryGroupApplicationRepositoryInterface $corpCateGroupAppRepository,
        ApprovalRepositoryInterface $approval
    ) {
        $this->corpCateGroupAppRepository = $corpCateGroupAppRepository;
        $this->approval = $approval;
    }

    /**
     * Search corp category group application
     *
     * @param  array $params
     * @return array
     */
    public function searchCorpCategoryGroupApplication($params)
    {
        $data = $this->corpCateGroupAppRepository->searchCorpCategoryGroupApplication($params);
        return $data;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getDataExportCsvCorpCateGroupApp($params)
    {
        $data = $this->corpCateGroupAppRepository->getDataExportCorpCateGroupApp($params);
        return $data;
    }

    /**
     * @return mixed
     */
    public function corpCategoryGroupApplicationAdmin()
    {
        $data = $this->approval->getCorpCategoryGroupApplicationAdmin();
        return $data;
    }
}
