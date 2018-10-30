<?php

namespace App\Services\Affiliation;

use App\Models\AffiliationInfo;
use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Services\BaseService;
use Auth;
use Excel;

class AffiliationSearchService extends BaseService
{
    /**
     * @var MCorpRepositoryInterface
     */
    private $mCorpRepository;

    /**
     * @var MGenresRepositoryInterface
     */
    private $mGenreRepository;

    /**
     * @var MItemRepositoryInterface
     */
    private $mItemRepository;

    /**
     * @var MUserRepositoryInterface
     */
    private $mUserRepository;

    /**
     * AffiliationSearchService constructor.
     *
     * @param MCorpRepositoryInterface   $mCorpRepository
     * @param MGenresRepositoryInterface $mGenresRepository
     * @param MUserRepositoryInterface   $mUserRepository
     * @param MItemRepositoryInterface   $mItemRepository
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        MGenresRepositoryInterface $mGenresRepository,
        MUserRepositoryInterface $mUserRepository,
        MItemRepositoryInterface $mItemRepository
    ) {
        $this->mCorpRepository = $mCorpRepository;
        $this->mGenreRepository = $mGenresRepository;
        $this->mItemRepository = $mItemRepository;
        $this->mUserRepository = $mUserRepository;
    }

    /**
     * @return array
     */
    public function prepareDataForViewAffiliation()
    {
        $bAllowDownloadCsv = true;
        $roleAccount = \Auth::user()->auth;
        if ($roleAccount == 'popular' || $roleAccount == 'accounting') {
            $bAllowDownloadCsv = false;
        }
        $listPrefecture = \Config::get('datacustom.prefecture_div');
        $listGenre = $this->mGenreRepository->getList(true);
        $listStatus = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::CORP_STATUS)
        );
        $listContractStatus = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::CONTRACT_STATUS)
        );
        $listUser = $this->mUserRepository->getListUserNotAffiliation();
        $listActive = array_combine(array_pluck($listUser, 'id'), array_values(array_pluck($listUser, 'user_name')));
        $dataView = [
            'stateJoin' => 1,
            'work24h' => 1,
            'listPref' => $listPrefecture,
            'listStatus' => $listStatus,
            'listRitsPerson' => $listActive,
            'listGenre' => $listGenre,
            'contractStatus' => $listContractStatus,
            'allowShowDownloadCsv' => $bAllowDownloadCsv,
            'showTableData' => true,
            'listCorp' => []
        ];
        return $dataView;
    }

    /**
     * @param array $list
     * @return array
     */
    private function prepareDataList($list)
    {
        $temp = [];
        foreach ($list as $arr) {
            $temp[$arr['id']] = $arr['category_name'];
        }
        return $temp;
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function searchCorpByCondition($data)
    {
        $page = 1;
        if ($data->page > 1) {
            $page = $data->page;
        }
        $limit = 100;
        $result = $this->mCorpRepository->getListCorpByConditionFromAffiliation(
            $data,
            $data->order,
            $data->direct,
            $page,
            $limit
        );
        return $result;
    }

    /**
     * @param object $data
     * @return object|null
     */
    public function getDataDownloadAffiliationCSV($data)
    {
        $result = $this->mCorpRepository->createDataDownloadCsvAffiliation($data);
        if (!is_null($result) && count($result) > 0) {
            $dateEdited = $this->editDataCsv($result);
            $file = $this->prepareDownloadCsv($dateEdited);
            return $file;
        }
        return null;
    }

    /**
     * @param array $data
     * @return array mixed
     */
    private function editDataCsv($data)
    {
        $freeEstimateList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::FREE_ESTIMATE)
        );
        $portalsiteList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::PORTAL_SITE)
        );
        $regSendMethodList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::REG_SEND_METHOD)
        );
        $coordinationMethodList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::COORDINATION_METHOD)
        );
        $progSendMethodList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::PROG_SEND_METHOD)
        );
        $billSendMethodList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::BILL_SEND_METHOD)
        );
        $collectionMethodList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::COLLECTION_METHOD)
        );
        $liabilityInsuranceList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::LIABILITY_INSURANCE)
        );
        $wasteCollectOathList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::WASTE_COLLECT_OATH)
        );
        $claimCountList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::CLAIM_COUNT)
        );
        $corpCommissionStatusList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::CONTRACT_STATUS)
        );
        $jbrAvailableStatusList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::JBR_STATUS)
        );
        $paymentSiteList = $this->prepareDataList(
            $this->mItemRepository->getListByCategoryItem(MItemRepository::PAYMENT_SITE)
        );
        $listedKind = \Config::get('rits.affiliation_csv_list_kind');
        $corpKind = \Config::get('rits.affiliation_csv_corp_kind');
        $commissionAcceptFlg = \Config::get('rits.affiliation_csv_commission_accept_flag');
        $auctionDeliveryStatusList = getDivList('rits.auction_delivery_status', 'rits_config');
        foreach ($data as &$obj) {
            $obj['m_corps_affiliation_status'] = $obj['m_corps_affiliation_status'] == 0 ?
                __('affiliation.not_accession') : __('affiliation.accession');
            $obj['m_corps_address1'] = !empty($obj['m_corps_address1']) ?
                getDivTextJP('prefecture_div', $obj['m_corps_address1']) : '';
            $obj['m_corps_support24hour'] = !empty($obj['m_corps_support24hour']) ?
                __('affiliation.maru') : __('affiliation.batu');
            $obj['m_corps_free_estimate'] = !empty($obj['m_corps_free_estimate']) ?
                $freeEstimateList[$obj['m_corps_free_estimate']] : '';
            $obj['m_corps_portalsite'] = !empty($obj['m_corps_portalsite']) ?
                $portalsiteList[$obj['m_corps_portalsite']] : '';
            $obj['m_corps_reg_send_method'] = !empty($obj['m_corps_reg_send_method']) ?
                $regSendMethodList[$obj['m_corps_reg_send_method']] : '';
            $obj['m_corps_coordination_method'] = !empty($obj['m_corps_coordination_method']) ?
                $coordinationMethodList[$obj['m_corps_coordination_method']] : '';
            $obj['m_corps_prog_send_method'] = !empty($obj['m_corps_prog_send_method']) ?
                $progSendMethodList[$obj['m_corps_prog_send_method']] : '';
            $obj['m_corps_bill_send_method'] = !empty($obj['m_corps_bill_send_method']) ?
                $billSendMethodList[$obj['m_corps_bill_send_method']] : '';
            $obj['m_corps_special_agreement_check'] = !empty($obj['m_corps_special_agreement_check']) ?
                __('affiliation.maru') : '';
            $obj['affiliation_infos_collection_method'] = !empty($obj['affiliation_infos_collection_method']) ?
                $collectionMethodList[$obj['affiliation_infos_collection_method']] : '';
            $obj['affiliation_infos_liability_insurance'] = !empty($obj['affiliation_infos_liability_insurance']) ?
                $liabilityInsuranceList[$obj['affiliation_infos_liability_insurance']] : '';
            $obj['affiliation_infos_waste_collect_oath'] = !empty($obj['affiliation_infos_waste_collect_oath']) ?
                $wasteCollectOathList[$obj['affiliation_infos_waste_collect_oath']] : '';
            $obj['affiliation_infos_claim_count'] = !empty($obj['affiliation_infos_claim_count']) ?
                $claimCountList[$obj['affiliation_infos_claim_count']] : '';
            $obj['m_corps_corp_commission_status'] = !empty($obj['m_corps_corp_commission_status']) ?
                $corpCommissionStatusList[$obj['m_corps_corp_commission_status']] : '';
            $obj['m_corps_mct_modified'] = !empty($obj['m_corps_mct_modified']) ?
                date("Y/m/d H:i:s", strtotime($obj['m_corps_mct_modified'])) : '';
            $obj['affiliation_infos_listed_kind'] = !empty($obj['affiliation_infos_listed_kind']) ?
                $listedKind[$obj['affiliation_infos_listed_kind']] : '';
            $obj['m_corps_corp_kind'] = !empty($obj['m_corps_corp_kind']) ?
                $corpKind[$obj['m_corps_corp_kind']] : '';
            $obj['m_corps_commission_accept_flg'] = !empty($obj['m_corps_commission_accept_flg']) ?
                $commissionAcceptFlg[$obj['m_corps_commission_accept_flg']] : '';
            $obj['m_corps_jbr_available_status'] = !empty($obj['m_corps_jbr_available_status']) ?
                $jbrAvailableStatusList[$obj['m_corps_jbr_available_status']] : '';
            $obj['m_corps_auction_status'] = !empty($obj['m_corps_auction_status']) ?
                $auctionDeliveryStatusList[$obj['m_corps_auction_status']] : '';
            $obj['m_corps_payment_site'] = !empty($obj['m_corps_payment_site']) ?
                $paymentSiteList[$obj['m_corps_payment_site']] : '';
        }
        return $data;
    }

    /**
     * export to file
     *
     * @param  array $data
     * @return object
     */
    private function prepareDownloadCsv($data)
    {
        $columKeys = array_keys(AffiliationInfo::CSV_FORMAT);
        $headerValues = array_values(AffiliationInfo::CSV_FORMAT);
        $csvData = [];
        foreach ($data as $row) {
            $rData = [];
            foreach ($columKeys as $k) {
                $rData[$k] = '';
                if (isset($row[$k])) {
                    $rData[$k] = $row[$k];
                }
            }
            //combine key values for csv
            $csvData[] = array_combine($headerValues, array_values($rData));
        }
        $fileName = __('affiliation.filename_csv') . '_' . Auth::user()->user_id;
        return Excel::create(
            $fileName,
            function ($excel) use ($csvData, $fileName) {
                $excel->sheet(
                    $fileName,
                    function ($sheet) use ($csvData) {
                        $sheet->fromArray($csvData);
                    }
                );
            }
        );
    }
}
