<?php

namespace App\Services\GeneralSearch;

use App\Repositories\Eloquent\MItemRepository;
use App\Services\MItemService;
use App\Repositories\MItemRepositoryInterface;

class InitializeCorpService extends BaseGeneralSearchService
{
    /**
     * @var MItemService
     */
    public $itemService;
    /**
     * @var MItemRepositoryInterface
     */
    public $itemRepo;

    /**
     * @var mixed
     */
    public $corpStatusList;
    /**
     * @var mixed
     */
    public $corpOrderFailReasonList;
    /**
     * @var mixed
     */
    public $corpsMobileTelType;
    /**
     * @var mixed
     */
    public $corpsAutoCallFlag;
    /**
     * @var mixed
     */
    public $itemDemandStatusList;
    /**
     * @var mixed
     */
    public $itemDemandOrderFailReasonList;
    /**
     * @var mixed
     */
    public $itemSmsDemandList;
    /**
     * @var mixed
     */
    public $jbrWordcontentsList;
    /**
     * @var mixed
     */
    public $itemJbrCategoryList;
    /**
     * @var mixed
     */
    public $itemPaymentSiteList;
    /**
     * @var mixed
     */
    public $corpCommissionStatusList;
    /**
     * @var mixed
     */
    public $corpsCorpCommissionType;
    /**
     * @var mixed
     */
    public $corpsJbrAvailableStatus;

    /**
     * InitializeCorpService constructor.
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
     * initCorpData
     */
    public function initCorpData()
    {
        $this->corpCommissionStatusList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::CONTRACT_STATUS));
        $this->corpsCorpCommissionType = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::CORPORATE_BROKERAGE_FORM));
        $this->corpsJbrAvailableStatus = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::JBR_STATUS));
        $this->itemPaymentSiteList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::PAYMENT_SITE));
        $this->itemJbrCategoryList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::JBR_CATEGORY));
        $this->itemDemandStatusList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::PROPOSAL_STATUS));
        $this->itemDemandOrderFailReasonList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::REASON_FOR_LOST_NOTE));
        $this->itemSmsDemandList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::SMS_DEMAND));
        $this->jbrWordcontentsList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::JBR_WORK_CONTENTS));
        $this->corpStatusList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::CORP_STATUS));
        $this->corpOrderFailReasonList = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::LOSS_OF_DEVELOPMENT));
        $this->corpsAutoCallFlag = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::AUTO_CALL_CLASSIFICATION));
        $mobileTelType = $this->itemService->prepareDataList($this->itemRepo->getListByCategoryItem(MItemRepository::LIST_MOBILE_PHONE_TYPES));
        $this->corpsMobileTelType = array_merge(['0' => 'なし'], $mobileTelType);
    }
}
