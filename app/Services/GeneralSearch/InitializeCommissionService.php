<?php

namespace App\Services\GeneralSearch;

use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\MCommissionTypeRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Services\MItemService;

class InitializeCommissionService extends BaseGeneralSearchService
{
    /**
     * @var MItemService
     */
    public $itemService;

    /**
     * @var \App\Repositories\MItemRepositoryInterface
     */
    public $itemRepo;
    /**
     * @var MCommissionTypeRepositoryInterface
     */
    public $commissionTypeRepo;

    /**
     * @var mixed
     */
    public $telSupportsCorrespondStatus;
    /**
     * @var mixed
     */
    public $visitSupportsCorrespondStatus;
    /**
     * @var mixed
     */
    public $orderSupportsCorrespondStatus;
    /**
     * @var mixed
     */
    public $irregularReasonList;
    /**
     * @var mixed
     */
    public $telSupportsOrderFailReason;
    /**
     * @var mixed
     */
    public $visitSupportsOrderFailReason;//not isset
    /**
     * @var mixed
     */
    public $orderSupportsOrderFailReason;
    /**
     * @var mixed
     */
    public $demandInfoConstructionClass;
    /**
     * @var mixed
     */
    public $reExclusionStatus;
    /**
     * @var mixed
     */
    public $commissionTypeList;
    /**
     * @var mixed
     */
    public $itemCommissionStatusList;
    /**
     * @var mixed
     */
    public $itemCmOrderFailReasonList;

    /**
     * InitializeCommissionService constructor.
     * @param MCommissionTypeRepositoryInterface $commissionTypeRepo
     * @param MItemService $itemService
     * @param MItemRepositoryInterface $itemRepo
     */
    public function __construct(
        MCommissionTypeRepositoryInterface $commissionTypeRepo,
        MItemService $itemService,
        MItemRepositoryInterface $itemRepo
    ) {
        $this->itemService = $itemService;
        $this->itemRepo = $itemRepo;
        $this->commissionTypeRepo = $commissionTypeRepo;
    }

    /**
     * initCommissionData
     */
    public function initCommissionData()
    {
        $this->commissionTypeList = $this->commissionTypeRepo->getListCommissionTypeName();
        $this->reExclusionStatus = $this->generateReExclusionStatus();
        $this->demandInfoConstructionClass = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::BUILDING_TYPE));
        $this->itemCmOrderFailReasonList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::REASON_FOR_LOSING_CONSENT));
        $this->itemCommissionStatusList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::COMMISSION_STATUS));
        $this->telSupportsCorrespondStatus = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::TELEPHONE_SUPPORT_STATUS));
        $this->visitSupportsCorrespondStatus = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::VISIT_SUPPORT_STATUS));
        $this->orderSupportsCorrespondStatus = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::ORDER_SUPPORT_STATUS));
        $this->irregularReasonList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::IRREGULAR_REASON));
        $commissionTelSupports = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::COMMISSION_TEL_SUPPORTS_ORDER_FAIL_REASON));
        $commissionVisitSupports = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::COMMISSION_VISIT_SUPPORTS_ORDER_FAIL_REASON));
        $commissionOrderSupports = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::COMMISSION_ORDER_SUPPORTS_ORDER_FAIL_REASON));
        $this->telSupportsOrderFailReason = array_merge([0 => ''], $commissionTelSupports);
        $this->visitSupportsOrderFailReason = array_merge([0 => ''], $commissionVisitSupports);
        $this->orderSupportsOrderFailReason = array_merge([0 => ''], $commissionOrderSupports);
    }
}
