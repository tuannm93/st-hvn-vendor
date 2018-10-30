<?php

namespace App\Services;

use App\Repositories\BillRepositoryInterface;
use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\MoneyCorrespondRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BillListService
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepo;

    /**
     * @var BillRepositoryInterface
     */
    protected $billInfoRepo;

    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepository;

    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepo;

    /**
     * @var MoneyCorrespondRepositoryInterface
     */
    protected $moneyCorrespondRepository;

    const BILL_STATUS_DEFAULT = 1;

    /**
     * serivce used for bill_list,bill_search and bill_save
     * BillListService constructor.
     *
     * @param MCorpRepositoryInterface $mCorpRepo
     * @param BillRepositoryInterface $billInfoRepo
     * @param MItemRepositoryInterface $mItemRepository
     * @param MCategoryRepositoryInterface $mCategoryRepo
     * @param \App\Repositories\MoneyCorrespondRepositoryInterface $moneyCorrespondRepository
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepo,
        BillRepositoryInterface $billInfoRepo,
        MItemRepositoryInterface $mItemRepository,
        MCategoryRepositoryInterface $mCategoryRepo,
        MoneyCorrespondRepositoryInterface $moneyCorrespondRepository
    ) {
        $this->mCorpRepo = $mCorpRepo;
        $this->billInfoRepo = $billInfoRepo;
        $this->mItemRepository = $mItemRepository;
        $this->mCategoryRepo = $mCategoryRepo;
        $this->moneyCorrespondRepository = $moneyCorrespondRepository;
    }

    /**
     * get bill info list
     *
     * @param $billSession
     * @param  $id
     * @param  $request
     * @return mixed
     */
    public function getBillList($billSession, $id, $request = null)
    {
        $officialName = $this->mCorpRepo->getOfficialName($id);
        $data = $request;
        if (!empty($request['bill_status'])) {
            $data['bill_status'] = $request['bill_status'];
        } else {
            $data['bill_status'] = $billSession[0]['bill_status'];
        }
        $data['corp_id'] = $id;
        $data['official_corp_name'] = $officialName->official_corp_name;
        $result = $this->billInfoRepo->searchByConditions($data);
        return $result;
    }

    /**
     * check modified column
     *
     * @param  $request
     * @return bool
     */
    public function checkBillModified($request)
    {
        foreach ($request['target'] as $target) {
            $results = $this->billInfoRepo->findModified($request['id'][$target]);
            if ($request['modified'][$target] == $results['modified']) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * update bill info data
     *
     * @param  $data
     * @return bool|mixed
     */
    public function updateBillInfo($data)
    {
        $resultFlg = false;
        $billInfoData = [];
        foreach ($data['target'] as $value) {
            if (!isset($data['bill_status'])) {
                if ($data['fee_payment_balance'][$value] == 0) {
                    $billInfoData['bill_status'] = getDivValue('bill_status', 'payment');
                }
            }
            if (!empty($data['fee_payment_price']) && !isset($data['fee_payment_date'])) {
                $billInfoData['fee_payment_date'] = date('Y/m/d');
            }
            $billInfoData['fee_payment_price'] = $data['fee_payment_price'][$value];
            $billInfoData['fee_payment_balance'] = $data['fee_payment_balance'][$value];
            $billInfoData['modified'] = date("Y-m-d H:i:s");
            $billInfoData['modified_user_id'] = Auth::user()['user_id'];
            $resultFlg = $this->billInfoRepo->updateRecord($data['id'][$value], $billInfoData);
        }
        return $resultFlg;
    }

    /**
     * change status
     *
     * @param $id
     * @param null $billStatus
     * @param null $feeBillingDate
     * @return mixed
     */
    public function billStatusChange($id, $billStatus = null, $feeBillingDate = null)
    {
        $data = [];
        if (!empty($billStatus)) {
            $data['bill_status'] = $billStatus;
        }
        if (!empty($feeBillingDate)) {
            $data['fee_billing_date'] = $feeBillingDate;
        }
        $data['modified'] = date('Y/m/d');
        $data['modified_user_id'] = Auth::user()['user_id'];
        return $this->billInfoRepo->updateRecord($id, $data);
    }

    /**
     * get bill list
     *
     * @param  $ids
     * @return mixed
     */
    public function getBillListDownload($ids)
    {
        return $this->billInfoRepo->getDownloadList($ids);
    }

    /**
     * get bill pass condition
     *
     * @param  $ids
     * @param $mCorpId
     * @param  $billStatus
     * @return mixed
     */
    public function getBillPastIssueList($ids, $mCorpId, $billStatus)
    {
        return $this->billInfoRepo->getPastIssueList($ids, $mCorpId, $billStatus);
    }


    /**
     * get bill status
     * @return mixed
     */
    public function getBillStatus()
    {
        return $this->mItemRepository->getListByCategoryItem(MItemRepository::BILLING_STATUS);
    }

    /**
     * get list from m_items
     * @return mixed
     */
    public function getListByCategoryItem()
    {
        return $this->mItemRepository->getListByCategoryItem(MItemRepository::CATEGORY);
    }

    /**
     * get first list from m_items
     * @return mixed
     */
    public function getFirstOldList()
    {
        return $this->mItemRepository->getFirstOldList(MItemRepository::CATEGORY);
    }

    /**
     * get m_categories data
     * @return mixed
     */
    public function getMCategoryList()
    {
        return $this->mCategoryRepo->getList();
    }

    /**
     * search m_corps data and paginate
     * @param array $data
     * @param integer $page
     * @return mixed
     */
    public function searchMCorpAndPaging($data, $page)
    {
        return $this->mCorpRepo->searchCorpAndPaging($data, $page);
    }

    /**
     * @param $corpId
     * @param $searchValue
     * @param $order
     * @return mixed
     */
    public function getMoneyCorrespondDataInitial($corpId, $searchValue, $order)
    {
        return $this->moneyCorrespondRepository->getMoneyCorrespondDataInitial($corpId, $searchValue, $order);
    }

    /**
     * @param $array
     * @return \App\Models\Base
     */
    public function create($array)
    {
        return $this->moneyCorrespondRepository->create($array);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRecord($id)
    {
        return $this->moneyCorrespondRepository->deleteMoneyRecord($id);
    }

    /**
     * @return \Carbon\Carbon
     */
    public function getDateTimeNow()
    {
        return Carbon::now();
    }
}
