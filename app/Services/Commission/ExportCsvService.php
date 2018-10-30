<?php

namespace App\Services\Commission;

use App\Services\BaseService;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Services\ExportService;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExportCsvService extends BaseService
{
    /**
     * @var CommissionInfoRepositoryInterface
     */
    private $commissionInfoRepository;
    /**
     * @var MGenresRepositoryInterface
     */
    private $mGenresRepository;
    /**
     * @var MUserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    private $mCategoryRepository;
    /**
     * @var MSiteRepositoryInterface
     */
    private $mSiteRepository;
    /**
     * @var SearchService
     */
    private $commissionSearchService;

    /**
     * ExportCsvService constructor.
     *
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param MGenresRepositoryInterface        $mGenresRepository
     * @param MUserRepositoryInterface          $userRepository
     * @param MCategoryRepositoryInterface      $mCategoryRepository
     * @param MSiteRepositoryInterface          $mSiteRepository
     * @param SearchService           $commissionSearchService
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        MGenresRepositoryInterface $mGenresRepository,
        MUserRepositoryInterface $userRepository,
        MCategoryRepositoryInterface $mCategoryRepository,
        MSiteRepositoryInterface $mSiteRepository,
        SearchService $commissionSearchService
    ) {
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->userRepository = $userRepository;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->mSiteRepository = $mSiteRepository;
        $this->mGenresRepository = $mGenresRepository;
        $this->commissionSearchService = $commissionSearchService;
    }


    /**
     * Get list data csv export
     *
     * @param array $dataSession
     * @param integer $affiliationId
     * @return array
     */
    public function getDataCSV($dataSession, $affiliationId)
    {
        $dataExport = [];
        try {
            $conditions = $this->commissionSearchService->getSearchConditions($dataSession, $affiliationId);
            $listCommission = $this->commissionInfoRepository->getListCommissionExportCSV($conditions)->toArray();
            if (count($listCommission) > 0) {
                $dataExport = $this->processDataBeforeExportCSV($listCommission);
            }
        } catch (\Exception $exception) {
            Log::error('Export commission csv error at ' . Carbon::now()->format('Y-m-d H:s:i') . ' with message: ' . $exception->getMessage());
        }
        return $dataExport;
    }


    /**
     * Get list column csv export
     *
     * @return mixed
     * @throws \Exception
     */
    public function getFieldListExportCSV()
    {
        $fieldList = [];
        try {
            $fieldList = $this->commissionInfoRepository->getBlankModel()->csvFieldListFormat();
        } catch (\Exception $exception) {
            Log::error('Export commission csv error at ' . Carbon::now()->format('Y-m-d H:s:i') . ' with message: ' . $exception->getMessage());
        }
        return $fieldList;
    }

    /**
     * @param array $listCommission
     * @return mixed
     */
    public function processDataBeforeExportCSV($listCommission)
    {
        $demandStatusList = getDropList(trans('commissioninfos.demand_status'));
        $orderFailReasonList = getDropList(trans('commissioninfos.order_fail_reason'));
        $siteList = $this->mSiteRepository->getList();
        $genreList = $this->mGenresRepository->getList();
        $categoryList = $this->mCategoryRepository->getList();
        $petTombstoneDemandList = getDropList(trans('commissioninfos.pet_tombstone_demand'));
        $smsDemandList = getDropList(trans('commissioninfos.sms_demand'));
        $jbrWorkContentsList = getDropList(trans('commissioninfos.jbr_work_contents'));
        $jbrCategoryList = getDropList(trans('commissioninfos.jbr_category'));
        $userList = $this->userRepository->dropDownUser();
        $jbrEstimateStatusList = getDropList(trans('commissioninfos.jbr_estimate_status'));
        $jbrReceiptStatusList = getDropList(trans('commissioninfos.jbr_receipt_status'));
        $sendMailFaxList = ['' => '', '0' => '', '1' => '送信済み'];
        $acceptanceStatusList = getDropList(trans('commissioninfos.acceptance_status'));
        $commissionStatusList = getDropList(trans('commissioninfos.commission_status'));
        $commissionOrderFailReasonList = getDropList(trans('commissioninfos.commission_order_fail_reason'));
        $selectionSystemList = getDivList('datacustom.selection_type', 'commissioninfos.selection_type');
        $data = $listCommission;
        $changeArray = [0 => 'mail_demand', 1 => 'nighttime_takeover', 2 => 'low_accuracy', 3 => 'remand', 4 => 'immediately', 5 => 'corp_change', 6 => 'sms_reorder'];
        foreach ($listCommission as $key => $val) {
            foreach ($changeArray as $v) {
                if ($val['demand_infos_' . $v] == 0) {
                    $data[$key]['demand_infos_' . $v] = trans('commissioninfos.batu');
                } else {
                    $data[$key]['demand_infos_' . $v] = trans('commissioninfos.maru');
                }
            }
            $data[$key]['demand_infos_demand_status'] = !empty($demandStatusList[$val['demand_infos_demand_status']]) ? $demandStatusList[$val['demand_infos_demand_status']] : '';
            $data[$key]['demand_infos_order_fail_reason'] = !empty($orderFailReasonList[$val['demand_infos_order_fail_reason']]) ? $orderFailReasonList[$val['demand_infos_order_fail_reason']] : '';
            $data[$key]['demand_infos_site_name'] = !empty($val['m_sites_site_name']) ? $val['m_sites_site_name'] : '';
            $data[$key]['demand_infos_genre_name'] = !empty($val['m_genres_genre_name']) ? $val['m_genres_genre_name'] : '';
            $data[$key]['demand_infos_category_name'] = !empty($val['m_categories_category_name']) ? $val['m_categories_category_name'] : '';
            $data[$key]['demand_infos_cross_sell_source_site'] = !empty($siteList[$val['demand_infos_cross_sell_source_site']]) ? $siteList[$val['demand_infos_cross_sell_source_site']] : '';
            $data[$key]['demand_infos_cross_sell_source_genre'] = !empty($genreList[$val['demand_infos_cross_sell_source_genre']]) ? $genreList[$val['demand_infos_cross_sell_source_genre']] : '';
            $data[$key]['demand_infos_cross_sell_source_category'] = !empty($categoryList[$val['demand_infos_cross_sell_source_category']]) ? $categoryList[$val['demand_infos_cross_sell_source_category']] : '';
            $data[$key]['demand_infos_pet_tombstone_demand'] = !empty($petTombstoneDemandList[$val['demand_infos_pet_tombstone_demand']]) ? $petTombstoneDemandList[$val['demand_infos_pet_tombstone_demand']] : '';
            $data[$key]['demand_infos_sms_demand'] = !empty($smsDemandList[$val['demand_infos_sms_demand']]) ? $smsDemandList[$val['demand_infos_sms_demand']] : '';
            $data[$key]['demand_infos_receptionist'] = !empty($userList[$val['demand_infos_receptionist']]) ? $userList[$val['demand_infos_receptionist']] : '';
            $data[$key]['demand_infos_address1'] = !empty($val['demand_infos_address1']) ? getDivTextJP('prefecture_div', $val['demand_infos_address1']) : '';
            $data[$key]['demand_infos_jbr_work_contents'] = !empty($jbrWorkContentsList[$val['demand_infos_jbr_work_contents']]) ? $jbrWorkContentsList[$val['demand_infos_jbr_work_contents']] : '';
            $data[$key]['demand_infos_jbr_category'] = !empty($jbrCategoryList[$val['demand_infos_jbr_category']]) ? $jbrCategoryList[$val['demand_infos_jbr_category']] : '';
            $data[$key]['demand_infos_jbr_estimate_status'] = !empty($jbrEstimateStatusList[$val['demand_infos_jbr_estimate_status']]) ? $jbrEstimateStatusList[$val['demand_infos_jbr_estimate_status']] : '';
            $data[$key]['demand_infos_jbr_receipt_status'] = !empty($jbrReceiptStatusList[$val['demand_infos_jbr_receipt_status']]) ? $jbrReceiptStatusList[$val['demand_infos_jbr_receipt_status']] : '';
            $data[$key]['demand_infos_contact_desired_time'] = getContactDesiredTimeExport($val, '〜');
            $data[$key]['demand_infos_acceptance_status'] = !empty($acceptanceStatusList[$val['demand_infos_acceptance_status']]) ? $acceptanceStatusList[$val['demand_infos_acceptance_status']] : '';
            $data[$key]['demand_infos_nitoryu_flg'] = !empty($val['demand_infos_nitoryu_flg']) ? trans('commissioninfos.maru') : trans('commissioninfos.batu');
            $data[$key]['commission_infos_send_mail_fax'] = !empty($sendMailFaxList[$val['commission_infos_send_mail_fax']]) ? $sendMailFaxList[$val['commission_infos_send_mail_fax']] : '';
            $data[$key]['commission_infos_commit_flg'] = !empty($val['commission_infos_commit_flg']) ? trans('commissioninfos.maru') : trans('commissioninfos.batu');
            $data[$key]['commission_infos_commission_type'] = !empty($val['commission_infos_commission_type']) ? trans('commissioninfos.bulk_quote') : trans('commissioninfos.normal_commission');
            $data[$key]['commission_infos_appointers'] = !empty($userList[$val['commission_infos_appointers']]) ? $userList[$val['commission_infos_appointers']] : '';
            $data[$key]['commission_infos_first_commission'] = !empty($val['commission_infos_first_commission']) ? trans('commissioninfos.maru') : trans('commissioninfos.batu');
            $data[$key]['commission_infos_tel_commission_person'] = !empty($userList[$val['commission_infos_tel_commission_person']]) ? $userList[$val['commission_infos_tel_commission_person']] : '';
            $data[$key]['commission_infos_commission_note_sender'] = !empty($userList[$val['commission_infos_commission_note_sender']]) ? $userList[$val['commission_infos_commission_note_sender']] : '';
            $data[$key]['commission_infos_commission_status'] = !empty($commissionStatusList[$val['commission_infos_commission_status']]) ? $commissionStatusList[$val['commission_infos_commission_status']] : '';
            $data[$key]['commission_infos_commission_order_fail_reason'] = !empty($commissionOrderFailReasonList[$val['commission_infos_commission_order_fail_reason']]) ? $commissionOrderFailReasonList[$val['commission_infos_commission_order_fail_reason']] : '';
            $data[$key]['demand_infos_selection_system'] = !is_null($val['demand_infos_selection_system']) ? $selectionSystemList[$val['demand_infos_selection_system']] : '';
        }
        return $data;
    }

    /**
     * @param array $dataRequest
     * @param integer $affiliationId
     * @return mixed
     * @throws \Exception
     */
    public function exportcsv($dataRequest, $affiliationId)
    {
        $dataExport = $this->getDataCSV($dataRequest, $affiliationId);
        $fieldList = $this->getFieldListExportCSV();
        $fileName = trans('commissioninfos.commission_info_csv') . '_' . \Auth::user()->user_id;
        $exportService = new ExportService();
        return $exportService->exportCsv($fileName, $fieldList, $dataExport);
    }
}
