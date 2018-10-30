<?php

namespace App\Services\GeneralSearch;

use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\MItemRepositoryInterface;
use App\Services\MItemService;

class InitializeItemService extends BaseGeneralSearchService
{
    /**
     * @var MItemService
     */
    protected $itemService;

    /**
     * @var \App\Repositories\MItemRepositoryInterface
    */
    public $itemRepo;
    /**
     * @var mixed
     */
    public $itemLiabilityInsurance;
    /**
     * @var mixed
     */
    public $itemWasteCollectOath;
    /**
     * @var mixed
     */
    public $itemAcceptanceStatusList;
    /**
     * @var mixed
     */
    public $itemRegSendMethodList;
    /**
     * @var mixed
     */
    public $itemSpecialMeasuresList;
    /**
     * @var mixed
     */
    public $itemPetTombstoneDemandList;
    /**
     * @var mixed
     */
    public $itemJbrEstimateList;
    /**
     * @var mixed
     */
    public $itemJbrReceiptList;
    /**
     * @var mixed
     */
    public $itemBillStatusList;
    /**
     * @var mixed
     */
    public $itemBillSendMethodList;
    /**
     * @var mixed
     */
    public $itemCoordinationMethodList;
    /**
     * @var mixed
     */
    public $itemProgSendMethodList;
    /**
     * @var mixed
     */
    public $itemAdvertisingStatusList;

    /**
     * InitializeItemService constructor.
     * @param MItemService $itemService
     * @param MItemRepositoryInterface $itemRepo
     */
    public function __construct(
        MItemService $itemService,
        MItemRepositoryInterface $itemRepo
    ) {
        $this->itemService = $itemService;
        $this->itemRepo = $itemRepo;
    }

    /**
     * initItemData
     */
    public function initItemData()
    {
        $this->itemPetTombstoneDemandList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::PET_TOMBSTONE_DEMAND));
        $this->itemJbrEstimateList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::JBR_ESTIMATE_STATUS));
        $this->itemJbrReceiptList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::JBR_RECEIPT_STATUS));
        $this->itemBillStatusList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::BILLING_STATUS));
        $this->itemCoordinationMethodList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::COORDINATION_METHOD));
        $this->itemProgSendMethodList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::PROG_SEND_METHOD));
        $this->itemBillSendMethodList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::BILL_SEND_METHOD));
        $this->itemAdvertisingStatusList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::ADVERTISEMENT_TYPE_SITE_SITUATION));
        $this->itemLiabilityInsurance = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::LIABILITY_INSURANCE));
        $this->itemWasteCollectOath = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::WASTE_COLLECT_OATH));
        $this->itemRegSendMethodList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::REG_SEND_METHOD));
        $this->itemSpecialMeasuresList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::PROJECT_SPECIAL_MEASURES));
        $this->itemAcceptanceStatusList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::ACCEPTANCE_STATUS));
    }
}
