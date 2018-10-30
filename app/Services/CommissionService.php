<?php

namespace App\Services;

use App\Models\MCorpNewYear;
use App\Repositories\BillRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\Eloquent\CommissionCorrespondsRepository;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MTaxRateRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Repositories\VisitTimeRepositoryInterface;
use App\Services\Aws\AwsUtilService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionService extends CommissionValidateService
{
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepo;
    /**
     * @var BillRepositoryInterface
     */
    protected $billRepo;
    /**
     * @var MTaxRateRepositoryInterface
     */
    protected $mTaxRateRepo;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepo;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepo;
    /**
     * @var CommissionCorrespondsRepository
     */
    protected $commissionCorRepo;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepo;
    /**
     * @var VisitTimeRepositoryInterface
     */
    protected $visitTimeRepo;
    /**
     * @var MUserRepositoryInterface
     */
    protected $mUserRepo;
    /**
     * @var AwsUtilService
     */
    protected $snsService;
    /**
     * @var MSiteRepositoryInterface
     */
    protected $mSiteRepo;
    /**
     * CommissionService constructor.
     * @param CommissionInfoRepositoryInterface $commissionInfoRepo
     * @param BillRepositoryInterface $billRepo
     * @param MTaxRateRepositoryInterface $mTaxRateRepo
     * @param MCategoryRepositoryInterface $mCategoryRepo
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepo
     * @param CommissionCorrespondsRepository $commissionCorrespondsRepo
     * @param MCorpRepositoryInterface $mCorpRepo
     * @param VisitTimeRepositoryInterface $visitTimeRepo
     * @param MUserRepositoryInterface $mUserRepo
     * @param AwsUtilService $snsService
     * @param MSiteRepositoryInterface $mSiteRepo
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepo,
        BillRepositoryInterface $billRepo,
        MTaxRateRepositoryInterface $mTaxRateRepo,
        MCategoryRepositoryInterface $mCategoryRepo,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepo,
        CommissionCorrespondsRepository $commissionCorrespondsRepo,
        MCorpRepositoryInterface $mCorpRepo,
        VisitTimeRepositoryInterface $visitTimeRepo,
        MUserRepositoryInterface $mUserRepo,
        AwsUtilService $snsService,
        MSiteRepositoryInterface $mSiteRepo
    ) {
        $this->commissionInfoRepo = $commissionInfoRepo;
        $this->billRepo = $billRepo;
        $this->mTaxRateRepo = $mTaxRateRepo;
        $this->mCategoryRepo = $mCategoryRepo;
        $this->commissionCorRepo = $commissionCorrespondsRepo;
        $this->mCorpRepo = $mCorpRepo;
        $this->mCorpCategoryRepo = $mCorpCategoryRepo;
        $this->visitTimeRepo = $visitTimeRepo;
        $this->snsService = $snsService;
        $this->mSiteRepo = $mSiteRepo;
        $this->mUserRepo = $mUserRepo;
    }
    /**
     * build commission status count filed
     * and merge all mcorp new year field
     * @return string
     */
    private function buildCommissionStatusSQL()
    {
        $newYear = new MCorpNewYear;
        $newYearColumns = \Schema::getColumnListing($newYear->getTable());
        $fields = '(SELECT COUNT(0) FROM commission_infos WHERE corp_id = "MCorp".id AND commission_status = 1 ) AS in_progress';
        $fields .= ',(SELECT COUNT(0) FROM commission_infos WHERE corp_id = "MCorp".id AND commission_status = 2 ) AS in_order';
        $fields .= ',(SELECT COUNT(0) FROM commission_infos WHERE corp_id = "MCorp".id AND commission_status = 3 ) AS complete';
        $fields .= ',(SELECT COUNT(0) FROM commission_infos WHERE corp_id = "MCorp".id AND commission_status = 4 ) AS failed';
        $fields .= ',' . implode(',', array_map(
            function ($value) {
                    return '"MCorpNewYear".' . '"' . $value . '"';
            },
            $newYearColumns
        ));
        return $fields;
    }
    /**
     * build holiday sql
     * @return string [description
     */
    private function buildHolidayField()
    {
        return '(SELECT ARRAY_TO_STRING(ARRAY( SELECT item_name FROM m_items INNER JOIN m_corp_subs ON '
            . 'm_corp_subs.item_category = m_items.item_category AND m_corp_subs.item_id = m_items.item_id WHERE '
            . 'm_corp_subs.item_category = \'休業日\' AND m_corp_subs.corp_id = "MCorp"."id" '
            . 'ORDER BY m_items.sort_order ASC ),\'｜\') as "MCorp__holiday" )';
    }
    /**
     * update join and select field
     * @param  Builder $qBuilder laravel query builder
     * @param  array $data     condition data
     * @return Builder           query Builder
     */
    private function updateBuilderByTargetCheckFlg($qBuilder, $data)
    {
        $prefecture = (int)substr($data['jis_cd'], 0, 2);
        $qBuilder->addSelect(
            'AffiliationAreaStat.commission_unit_price_category AS AffiliationAreaStat_commission_unit_price_category',
            'AffiliationAreaStat.commission_count_category AS AffiliationAreaStat_commission_count_category',
            'MCorpCategory.category_id AS MCorpCategory_category_id',
            'MCorpCategory.auction_status AS MCorpCategory_auction_status'
        );
        $qBuilder->join('m_target_areas AS MTargetArea', function ($join) use ($data) {
            $join->on('MTargetArea.corp_category_id', '=', 'MCorpCategory.id');
            $join->where('MTargetArea.jis_cd', $data['jis_cd']);
        });
        $qBuilder->join('affiliation_area_stats AS AffiliationAreaStat', function ($join) use ($prefecture) {
            $join->on('AffiliationAreaStat.corp_id', '=', 'MCorp.id');
            $join->on('AffiliationAreaStat.genre_id', '=', 'MCorpCategory.genre_id');
            $join->where('AffiliationAreaStat.prefecture', $prefecture);
        })->orderByRaw('"AffiliationAreaStat"."commission_unit_price_category" IS NULL ASC')
        ->orderByRaw('"AffiliationAreaStat"."commission_unit_price_category" DESC')
        ->orderByRaw('"AffiliationAreaStat"."commission_count_category" DESC');
        $qBuilder->where(function ($q) {
            $q->orWhereNull('MCorp.auction_status')
                ->orWhere(function ($q) {
                    $q->where('MCorp.auction_status', '!=', 3)
                        ->where('MCorpCategory.auction_status', '!=', 3);
                })
                ->orWhere(function ($q) {
                    $q->where('MCorp.auction_status', 1)
                        ->where('MCorpCategory.auction_status', '!=', 3);
                })
                ->orWhere(function ($q) {
                    $q->where('MCorp.auction_status', 3)
                        ->where('MCorpCategory.auction_status', 0);
                })
                ->orWhere(function ($q) {
                    $q->where('MCorp.auction_status', 3)
                        ->where('MCorpCategory.auction_status', 1);
                })
                ->orWhere(function ($q) {
                    $q->where('MCorp.auction_status', 3)
                        ->where('MCorpCategory.auction_status', 2);
                });
        });
        return $qBuilder;
    }
    /**
     * build query
     * @param  array $data     data condition
     * @param  string $joinType sql join type
     * @return Builder           query builder
     */
    private function buildQueryCorpList($data, $joinType)
    {
        $qBuilder = \DB::table('m_corps AS MCorp')
            ->join('affiliation_infos AS AffiliationInfo', 'MCorp.id', '=', 'AffiliationInfo.corp_id')
            ->join('m_items AS MItem', function ($join) {
                $join->on('MItem.item_id', '=', 'MCorp.coordination_method');
                $join->where('MItem.item_category', '=', '顧客情報連絡手段');
            })
            ->join('m_corp_categories AS MCorpCategory', function ($join) use ($data) {
                $join->on('MCorpCategory.corp_id', '=', 'MCorp.id');
                $join->where('MCorpCategory.category_id', '=', $data['category_id']);
            }, null, null, $joinType)
            ->leftJoin('affiliation_stats AS AffiliationStat', function ($join) use ($data) {
                $join->on('AffiliationStat.corp_id', '=', 'MCorp.id');
                $join->on('AffiliationStat.genre_id', '=', 'MCorpCategory.genre_id');
            })
            ->leftJoin('affiliation_subs AS AffiliationSubs', function ($join) use ($data) {
                $join->on('AffiliationSubs.affiliation_id', '=', 'AffiliationInfo.id');
                $join->where('AffiliationSubs.item_id', '=', $data['category_id']);
            })
            ->leftJoin('m_corp_new_years AS MCorpNewYear', 'MCorpNewYear.corp_id', '=', 'MCorp.id')
            ->select(
                'MCorp.id AS MCorp_id',
                'MCorp.corp_name AS MCorp_corp_name',
                'MCorp.commission_dial AS MCorp_commission_dial',
                'MCorp.coordination_method AS MCorp_coordination_method',
                'MCorp.mailaddress_pc AS MCorp_mailaddress_pc',
                'MCorp.fax AS MCorp_fax',
                'MCorp.note AS MCorp_note',
                'MCorp.support24hour AS MCorp_support24hour',
                'MCorp.available_time_from AS MCorp_available_time_from',
                'MCorp.available_time_to AS MCorp_available_time_to',
                'MCorp.available_time AS MCorp_available_time',
                'MCorp.contactable_support24hour AS MCorp_contactable_support24hour',
                'MCorp.contactable_time_from AS MCorp_contactable_time_from',
                'MCorp.contactable_time_to AS MCorp_contactable_time_to',
                'MCorp.contactable_time AS MCorp_contactable_time',
                'MCorp.address1 AS MCorp_address1',
                'MCorp.address2 AS MCorp_address2',
                'MCorp.address3 AS MCorp_address3',
                'AffiliationInfo.fee AS AffiliationInfo_fee',
                'AffiliationInfo.commission_unit_price AS AffiliationInfo_commission_unit_price',
                'AffiliationInfo.attention AS AffiliationInfo_attention',
                'AffiliationInfo.commission_count AS AffiliationInfo_commission_count',
                'AffiliationInfo.sf_construction_count AS AffiliationInfo_sf_construction_count',
                'AffiliationInfo.attention AS AffiliationInfo_attention',
                'MCorpCategory.order_fee AS MCorpCategory_order_fee',
                'MCorpCategory.order_fee_unit AS MCorpCategory_order_fee_unit',
                'MCorpCategory.note AS MCorpCategory_note',
                'MCorpCategory.select_list AS MCorpCategory_select_list',
                'MCorpCategory.introduce_fee AS MCorpCategory_introduce_fee',
                'MCorpCategory.corp_commission_type AS MCorpCategory_corp_commission_type',
                'AffiliationStat.commission_unit_price_category AS AffiliationStat_commission_unit_price_category',
                'AffiliationStat.commission_count_category AS AffiliationStat_commission_count_category',
                'AffiliationStat.orders_count_category AS AffiliationStat_orders_count_category'
            );
        return $qBuilder;
    }
    /**
     * @param $data
     * @return mixed|null
     */
    private function getVisittime($data)
    {
        /* Retrieve deal ID */
        $demandId = (array_key_exists('id', $data['demandInfo'])) ? $data['demandInfo']['id'] : null;
        $visittimeData = null;
        if (array_key_exists('visitTime', $data)) {
            /* Acquire the registered visit_times to store the ID of the desired visit date in commission_infos */
            $visittimeData = $this->visitTimeRepo->findAllByDemandId($demandId, true);
        }
        return $visittimeData;
    }
    /**
     * @param $data
     * @param $corp
     * @param $cnt
     * @param $errorNo
     */
    private function checkFaxNumberAndPcMail($data, $corp, $cnt, &$errorNo)
    {
        if ($data['send_commission_info'] == 1) {
            $coordinationMethod = $corp->coordination_method;
            if ($coordinationMethod == getDivValue('coordination_method', 'mail_fax')) {
                if (empty($corp->fax) && empty($corp->mailaddress_pc)) {
                    array_push($errorNo, $cnt);
                }
            } elseif ($coordinationMethod == getDivValue('coordination_method', 'mail')) {
                if (empty($corp->mailaddress_pc)) {
                    array_push($errorNo, $cnt);
                }
            } elseif ($coordinationMethod == getDivValue('coordination_method', 'fax')) {
                if (empty($corp->fax)) {
                    array_push($errorNo, $cnt);
                }
            }
        }
    }
    /**
     * @param $data
     * @param $val
     * @param $dateInsert
     * @param $visittimeData
     * @param $lostFlg
     * @return array|mixed
     */
    private function createCommissionDataWhenUpdate($data, $val, $dateInsert, $visittimeData, $lostFlg)
    {
        /* When updating*/
        $tempData = [
            'id' => $val['id'],
            'corp_id' => $val['corp_id'],
            'commit_flg' => $val['commit_flg'],
            'appointers' => $val['appointers'],
            'send_mail_fax' => $val['send_mail_fax'] == "" ? null : $val['send_mail_fax'],
            'modified' => $dateInsert,
            'modified_user_id' => auth()->user()->user_id,
            'first_commission' => empty($val['first_commission']) ? 0 : (int)$val['first_commission'],
            'unit_price_calc_exclude' => $val['unit_price_calc_exclude'],
            'position' => $val['position'] ?? -1
        ];
        $tempData = $this->filCommissionDataWithCommonFlag($tempData, $val);
        $tempData = $this->fillCommissionDataWithSendMail($tempData, $val);
        $tempData = $this->fillCommissionDataWithNoteSender($tempData, $val);
        $tempData = $this->fillCommissionDataWithUnitPrice($tempData, $val);
        $tempData = $this->fillCommissionDataWithCorpFee($tempData, $val);
        $tempData = $this->fillCommonCommissionData($tempData, $data, $val, $lostFlg);
        $tempData = $this->fillCommissionDataWithVisittime($tempData, $visittimeData);
        return $tempData;
    }

    /**
     * @param $data
     * @param $val
     * @param $dateInsert
     * @param $visittimeData
     * @param $lostFlg
     * @return array
     */
    private function createCommissionDataWhenInsert($data, $val, $dateInsert, $visittimeData, $lostFlg)
    {
        $tempInsert = [];
        /* In case of new registration*/
        $tempInsert['modified'] = $dateInsert;
        $tempInsert['created'] = $dateInsert;
        $tempInsert['modified_user_id'] = auth()->user()->user_id;
        $tempInsert['created_user_id'] = auth()->user()->user_id;
        $tempInsert['demand_id'] = $data['demandInfo']['id'];
        $tempInsert['corp_id'] = $val['corp_id'];
        $tempInsert['commit_flg'] = $val['commit_flg'];
        $tempInsert['send_mail_fax'] = empty($val['send_mail_fax']) ? 0 : $val['send_mail_fax'];
        $tempInsert['commission_type'] = getDivValue('commission_type', 'normal_commission');  // Order type: 0 (ordinary agency)
        $tempInsert['appointers'] = $val['appointers'];
        $tempInsert['first_commission'] = empty($val['first_commission']) ? 0 : (int)$val['first_commission'];
        $tempInsert['unit_price_calc_exclude'] = $val['unit_price_calc_exclude'];
        $tempInsert['commission_status'] = getDivValue('construction_status', 'progression'); // Contract status
        $tempInsert['unit_price_calc_exclude'] = 0;// Unit price per transaction: 0
        $tempInsert['position'] = $val['position'] ?? -1;
        $tempInsert = $this->fillCommissionDataWithSendMail($tempInsert, $val);
        $tempInsert = $this->fillCommissionDataWithUnitPrice($tempInsert, $val);
        $tempInsert = $this->filCommissionDataWithCommonFlag($tempInsert, $val);
        $tempInsert = $this->fillCommissionDataWithNoteSender($tempInsert, $val);
        $tempInsert = $this->fillCommonCommissionData($tempInsert, $data, $val, $lostFlg);
        $tempInsert = $this->fillCommissionDataWithCorpFee($tempInsert, $val);
        $tempInsert = $this->fillCommissionDataWithVisistimeWhenInsert($tempInsert, $visittimeData);
        $tempInsert = $this->fillCommissionDataWithFeeWhenInsert($tempInsert, $val);
        return $tempInsert;
    }
    /**
     * @param $tempData
     * @param $val
     * @return mixed
     */
    private function filCommissionDataWithCommonFlag($tempData, $val)
    {
        $tempData['corp_claim_flg'] = empty($val['corp_claim_flg']) ? null : (int)$val['corp_claim_flg'];
        $tempData['lost_flg'] = !empty($val['lost_flg']) ? (int)$val['lost_flg'] : 0;
        $tempData['del_flg'] = !empty($val['del_flg']) ? (int)$val['del_flg'] : 0;

        return $tempData;
    }
    /**
     * @param $tempData
     * @param $val
     * @return mixed
     */
    private function fillCommissionDataWithSendMail($tempData, $val)
    {
        $tempData['send_mail_fax_datetime'] = (!isset($val['send_mail_fax_datetime']) || $val['send_mail_fax_datetime'] == "") ? null : $val['send_mail_fax_datetime'];
        $tempData['send_mail_fax_sender'] = (!isset($val['send_mail_fax_sender']) || $val['send_mail_fax_sender'] == "") ? null : $val['send_mail_fax_sender'];
        return $tempData;
    }
    /**
     * @param $tempData
     * @param $val
     * @return mixed
     */
    private function fillCommissionDataWithNoteSender($tempData, $val)
    {
        $tempData['commission_note_sender'] = !empty($val['commission_note_sender']) ? $val['commission_note_sender'] : '';
        $tempData['commission_note_send_datetime'] = !empty($val['commission_note_send_datetime']) ? $val['commission_note_send_datetime'] : null;
        return $tempData;
    }
    /**
     * @param $tempData
     * @param $val
     * @return mixed
     */
    private function fillCommissionDataWithUnitPrice($tempData, $val)
    {
        $tempData['select_commission_unit_price_rank'] = $val['select_commission_unit_price_rank'];
        $tempData['select_commission_unit_price'] = empty($val['select_commission_unit_price']) ? 0 : (int)$val['select_commission_unit_price'];
        return $tempData;
    }
    /**
     * @param $tempData
     * @param $val
     * @return mixed
     */
    private function fillCommissionDataWithCorpFee($tempData, $val)
    {
        if (isset($val['corp_fee'])) {
            $tempData['corp_fee'] = $val['corp_fee'] == 'null' ? null : $val['corp_fee'];
        } elseif (isset($val['commission_fee_rate']) && !empty($val['commission_fee_rate'])) {
            $tempData['commission_fee_rate'] = $val['commission_fee_rate'];
        }
        return $tempData;
    }
    /**
     * @param $tempData
     * @param $visittimeData
     * @return mixed
     */
    private function fillCommissionDataWithVisittime($tempData, $visittimeData)
    {
        if ($visittimeData) {
            $tempData['commission_visit_time_id'] = $visittimeData->id;
        }
        return $tempData;
    }
    /**
     * @param $tempInsert
     * @param $data
     * @param $val
     * @param $lostFlg
     * @return mixed
     */
    private function fillCommonCommissionData($tempInsert, $data, $val, $lostFlg)
    {
        if (isset($val['auto_select_flg'])) {
            $tempInsert['auto_select_flg'] = $val['auto_select_flg'];
        }
        /*When sending an aggregate table separately*/
        if ($val['commit_flg'] == 1 && $data['not-send'] == 1) {
            $tempInsert['send_mail_fax_othersend'] = 1;
        }
        if ($lostFlg) {
            $tempInsert['commission_status'] = getDivValue('construction_status', 'order_fail');
        }

        return $tempInsert;
    }
    /**
     * @param $tempInsert
     * @param $visittimeData
     * @return mixed
     */
    private function fillCommissionDataWithVisistimeWhenInsert($tempInsert, $visittimeData)
    {
        if ($visittimeData) {
            $tempInsert['commission_visit_time_id'] = $visittimeData['visitTime']['id'];
        }
        return $tempInsert;
    }
    /**
     * @param $tempInsert
     * @param $val
     * @return mixed
     */
    private function fillCommissionDataWithFeeWhenInsert($tempInsert, $val)
    {
        $tempInsert['order_fee_unit'] = empty($val['order_fee_unit']) ? null : (int)$val['order_fee_unit'];
        return $tempInsert;
    }
    /**
     * @param $introduceData
     * @throws \Exception
     */
    private function updateMultipleIntroduceData($introduceData, $demandId)
    {
        $introduceCommission = [];
        $insertData = array_filter($introduceData, function ($data) {
            return empty($data['id']);
        });

        $updateData = array_filter($introduceData, function ($data) {
            return !empty($data['id']);
        });
        Log::debug("_________3@call CommissionInfo->saveAll");
        $updated = $this->commissionInfoRepo->multipleUpdate($updateData);

        if (!empty($insertData)) {
            foreach ($insertData as $key => $value) {
                $insertData[$key]['demand_id'] = $demandId;
            }
            $introduceCommission = $this->commissionInfoRepo->insertCommission($insertData);
        }

        if ($updated) {
            foreach ($updateData as $key => $val) {
                $updateData[$key]['demand_id'] = $demandId;
            }
            $introduceCommission = array_merge($introduceCommission, $updateData);
        }

        return $introduceCommission;
    }
    /**
     * @param $data
     * @param $inserted
     * @param $insertedDel
     * @param $demandId
     * @throws \Exception
     */
    private function updateBillWhenUpdateIntroduce($data, $inserted, $insertedDel, $demandId)
    {
        if (empty($data['demandInfo']['riro_kureka'])) {
            if (!empty($inserted)) {
                Log::debug("4@call __update_bill");
                if (!$this->commissionUpdateBill($demandId, $data['demandInfo']['category_id'], $inserted)) {
                    Log::debug("4@エラー __update_bill");
                    throw new \Exception();
                }
            }
            if (!empty($insertedDel)) {
                Log::debug("4@call __delete_bill");
                if (!$this->billRepo->deleteByDemandIdAndIds($demandId, $insertedDel)) {
                    Log::debug("4@エラー __delete_bill");
                    throw new \Exception();
                }
            }
        }
    }

    /**
     * @param $key
     * @param $val
     * @param $currentData
     * @param $demandId
     * @param $inserted
     * @param $insertedDel
     * @return mixed
     */
    private function createIntroduceDataWhenUpdate($val, $currentData, $demandId, &$inserted, &$insertedDel)
    {
        $introduceData['commission_note_send_datetime'] = $val['commission_note_send_datetime'];// Delivery date and time of introduction form
        $introduceData['commission_note_sender'] = $val['commission_note_sender'];
        /* Introduction slip sender*/
        $introduceData['id'] = (!empty($val['id']) ? $val['id'] : $currentData['id']);
        /* delete flag*/
        $introduceData['del_flg'] = (int)$val['del_flg'];
        /*Can not introduce*/
        $introduceData['introduction_not'] = $val['introduction_not'];
        $introduceData['lost_flg'] = (int)$val['lost_flg'];
        $introduceData['commit_flg'] = empty($val['commit_flg']) ? 0 : (int)$val['commit_flg'];
        $introduceData['appointers'] = $val['appointers'];
        $introduceData['corp_claim_flg'] = empty($val['corp_claim_flg']) ? null : (int)$val['corp_claim_flg'];
        $introduceData['first_commission'] = empty($val['first_commission']) ? 0 : $val['first_commission'];
        $introduceData['unit_price_calc_exclude'] = $val['unit_price_calc_exclude'];
        $introduceData['position'] = $val['position'] ?? -1;
        $introduceData['modified'] = date('Y-m-d H:i:s');
        $introduceData['modified_user_id'] = auth()->user()->user_id;
        $introduceData = $this->fillIntroduceDataWithCommitFlagWhenUpdate($introduceData, $val);
        $this->getArrayInsertAndDelete($currentData, $demandId, $inserted, $insertedDel);
        return $introduceData;
    }

    /**
     * @param $currentData
     * @param $demandId
     * @param $inserted
     * @param $insertedDel
     */
    private function getArrayInsertAndDelete($currentData, $demandId, &$inserted, &$insertedDel)
    {
        if (!empty($val['introduction_not']) || empty($val['commit_flg'])) {
            array_push($insertedDel, $currentData['id']);
        } else {
            $count = $this->billRepo->countByDemandIdAndCommissionId($demandId, $currentData['id']);
            if ($count == 0) {
                array_push($inserted, $val['corp_id']);
            }
        }
    }
    /**
     * @param $introduceData
     * @param $key
     * @param $val
     * @return mixed
     */
    private function fillIntroduceDataWithCommitFlagWhenUpdate($introduceData, $val)
    {
        if (!empty($val['commit_flg'])) {
            $introduceData['confirmd_fee_rate'] = 100;
            $introduceData['complete_date'] = date('Y/m/d');
        }
        return $introduceData;
    }

    /**
     * @param $introduceData
     * @param $key
     * @param $val
     * @return mixed
     */
    private function fillIntroduceDataWithCommissionNoteWhenInsert($introduceData, $val)
    {
        if (isset($val['commission_note_send_datetime'])) {
            $introduceData['commission_note_send_datetime'] = $val['commission_note_send_datetime']; // Delivery date and time of introduction form
        }
        if (isset($val['commission_note_sender'])) {
            /* Introduction slip sender*/
            $introduceData['commission_note_sender'] = $val['commission_note_sender'];
        }
        return $introduceData;
    }
    /**
     * @param $key
     * @param $val
     * @param $data
     * @param $demandId
     * @param $inserted
     * @return mixed
     */
    private function createIntroduceDataWhenInsert($val, $data, $demandId, &$inserted)
    {
        $introduceData['demand_id'] = $demandId;// Opportunity ID
        $introduceData['corp_id'] = $val['corp_id'];//Merchant ID
        $introduceData = $this->fillIntroduceDataWithCommissionNoteWhenInsert($introduceData, $val);
        /*Intermediary type: 1 (collective estimate)*/
        $introduceData['commission_type'] = getDivValue('commission_type', 'package_estimate');
        /* Confirmation flag: 0 (undetermined)*/
        $introduceData['commit_flg'] = $val['commit_flg'];
        // Selector: Login User
        $introduceData['appointers'] = $val['appointers'];
        /* First Check Check: 0*/
        $introduceData['first_commission'] = empty($val['first_commission']) ? 0 : $val['first_commission'];
        /* Intermediary fee: Compatible genre master by company. Referral fee*/
        $defaultFee = $this->mCategoryRepo->getDefaultFee($data['demandInfo']['category_id']);
        $introduceData['corp_fee'] = $this->mCorpCategoryRepo->getIntroduceFee($val['corp_id'], $data['demandInfo']['category_id'], $defaultFee);
        $introduceData['corp_claim_flg'] = $val['corp_claim_flg'];
        /* Interpretation commission rate: 100*/
        $introduceData['commission_fee_rate'] = 100;
        /*Agency situation: 5 (introduced)*/
        $introduceData['commission_status'] = getDivValue('construction_status', 'introduction');
        /* Unit price per transaction: 0*/
        $introduceData['unit_price_calc_exclude'] = 0;
        /* Before interruption flag*/
        $introduceData['lost_flg'] = (int)$val['lost_flg'];
        /* Delete flag*/
        $introduceData['del_flg'] = (int)$val['del_flg'];
        $introduceData['position'] = $val['position'] ?? -1;
        $introduceData['created'] = date('Y-m-d H:i:s');
        $introduceData['created_user_id'] = auth()->user()->user_id;
        $introduceData['modified'] = date('Y-m-d H:i:s');
        $introduceData['modified_user_id'] = auth()->user()->user_id;
        $introduceData = $this->fillIntroduceDataWithIntroduceNotWhenInsert($introduceData, $val, $inserted);

        if (!empty($val['commit_flg'])) {
            $introduceData['confirmd_fee_rate'] = 100;
            $introduceData['complete_date'] = date('Y/m/d');
        }
        $introduceData['order_fee_unit'] = empty($val['order_fee_unit']) ? null : (int)$val['order_fee_unit'];
        return $introduceData;
    }
    /**
     * @param $introduceData
     * @param $key
     * @param $val
     * @param $inserted
     * @return mixed
     */
    private function fillIntroduceDataWithIntroduceNotWhenInsert($introduceData, $val, &$inserted)
    {
        if (isset($val['introduction_not'])) {
            /* Can not introduce*/
            $introduceData['introduction_not'] = $val['introduction_not'];
        }
        if (empty($val['introduction_not']) && empty($val['del_flg']) && !empty($val['commit_flg'])) {
            array_push($inserted, $val['corp_id']);
        }
        return $introduceData;
    }
    /**
     * @param $commissionData
     * @param $corpInfo
     * @param $key
     * @param $val
     * @return mixed
     */
    private function fillCommissionDataWhenUpdateSendMailFax($commissionData, $corpInfo, $key, $val)
    {
        if (!empty($corpInfo->coordination_method)
            && (($corpInfo->coordination_method == getDivValue('coordination_method', 'mail_fax'))
                || ($corpInfo->coordination_method == getDivValue('coordination_method', 'mail'))
                || ($corpInfo->coordination_method == getDivValue('coordination_method', 'fax'))
                || ($corpInfo->coordination_method == getDivValue('coordination_method', 'mail_app'))
                || ($corpInfo->coordination_method == getDivValue('coordination_method', 'mail_fax_app'))
            )) {
            $commissionData[$key]['id'] = $val->id;
            $commissionData[$key]['send_mail_fax'] = 1;
            $commissionData[$key]['send_mail_fax_datetime'] = date('Y/m/d H:i:s');
            $commissionData[$key]['modified'] = date('Y/m/d H:i:s');
            $commissionData[$key]['modified_user_id'] = auth()->user()->user_id;
            $commissionData[$key]['send_mail_fax_sender'] = auth()->user()->id;
            $commissionData[$key]['send_mail_fax_othersend'] = 0;
        }
        return $commissionData;
    }

    /**
     * @param array $data
     * @param $check
     * @return \Illuminate\Support\Collection
     */
    public function getCorpList($data = [], $check = null)
    {
        $flashError = false;
        $limit = 100;
        /* Error checking */
        empty($data['category_id']) ? $data['category_id'] = 0 : '';
        $fieldsHoliday = $this->buildHolidayField();
        $fieldsCommissionUnitPrice = '(SELECT m_genres.targer_commission_unit_price FROM m_genres '
            . 'WHERE m_genres.id = "MCorpCategory"."genre_id") AS "targer_commission_unit_price"';
        $fields = $this->buildCommissionStatusSQL();
        $conditions = [
            ['MCorp.affiliation_status', '=', 1],
            ['MCorp.del_flg', '=', 0]
        ];
        $targetCheckFlg = false;
        $joinType = 'left';
        if (empty($data['target_check'])) {
            if (!empty($data['category_id']) && !empty($data['jis_cd'])) {
                $targetCheckFlg = true;
                $joinType = 'inner';
            } else {
                if ($flashError) {
                    session()->flash(__('demand.errorCommissionSelect'));
                }
                return [];
            }
        }
        $qBuilder = $this->buildQueryCorpList($data, $joinType);
        if ($targetCheckFlg) {
            if (empty($check)) {
                $limit = 1500;
                $conditions[] = ['AffiliationAreaStat.commission_count_category', '>=', 5];
            } else {
                $limit = 2000;
                $conditions[] = ['AffiliationAreaStat.commission_count_category', '<', 5];
            }
            $qBuilder = $this->updateBuilderByTargetCheckFlg($qBuilder, $data);
        } else {
            $qBuilder->orderByRaw('"AffiliationInfo"."commission_unit_price" IS NULL ASC')
                ->orderByDesc('AffiliationInfo.commission_unit_price')
                ->orderByDesc('AffiliationInfo.commission_count');
        }
        $qBuilder->addSelect(DB::raw($fields))
            ->addSelect(DB::raw($fieldsCommissionUnitPrice))
            ->addSelect(DB::raw($fieldsHoliday))
            ->where($conditions)
            ->whereNull('AffiliationSubs.affiliation_id')
            ->whereNull('AffiliationSubs.item_id')
            ->whereNotIn('MCorp.commission_accept_flg', [0, 3])
            ->whereRaw('coalesce("MCorp"."corp_commission_status", 0) not in (1,2,4,5)');
        return $qBuilder->limit($limit)->get();
    }
    /**
     * @param $data
     * @return array
     */
    public function updateCommission(&$data)
    {
        $errorNo = [];
        /* If supplier information is not entered, nothing is done */
        Log::Debug('___ Start update commission ________');
        /* If there is no transaction type = "contract base" it does nothing */
        $commissionInfo = array_filter($data['commissionInfo'], function ($item) {
            return $item['commission_type'] != 1 && !empty($item['corp_id']);
        });
        if ($this->checkConditionUpdateCommission($data, $commissionInfo)) {
            return [];
        }
        /* If the visit date is not entered, I do nothing */
        $visittimeData = $this->getVisittime($data);
        /* Registration of destination information */
        $cnt = 0;
        $commissionInsertData = [];
        $commissionUpdateData = [];
        $dateInsert = date('Y-m-d H:i:s');
        foreach ($commissionInfo as $val) {
            $cnt++;
            $lostFlg = false;
            $corp = $this->mCorpRepo->getFirstById($val['corp_id']);
            /*If the company name is for lost information, create an order status with a missing order*/
            if ($corp->corp_name == config('rits.lost_corp_name')) {
                $lostFlg = true;
            }
            /* It checks whether the fax number of the company master and PC mail are set
            refactor*/
            $this->checkFaxNumberAndPcMail($data, $corp, $cnt, $errorNo);
            /* Update or registration judgment*/

            if (isset($val['id']) && !empty($val['id'])) {
                $commission = $this->createCommissionDataWhenUpdate($data, $val, $dateInsert, $visittimeData, $lostFlg);
                if (isset($val['id_staff'])) {
                    $commission['id_staff'] = $val['id_staff'];
                }
                $commissionUpdateData[] = $commission;
            } else {
                $commission = $this->createCommissionDataWhenInsert($data, $val, $dateInsert, $visittimeData, $lostFlg);
                if (isset($val['id_staff'])) {
                    $commission['id_staff'] = $val['id_staff'];
                }
                $commissionInsertData[] = $commission;
            }
        }
        if (count($errorNo) > 0) {
            Log::Debug('___ commission error no: ' . count($errorNo) . '  ________');
            return $errorNo;
        }
        $commissionData = $this->commissionInfoRepo->insertCommission($commissionInsertData);
        $this->commissionInfoRepo->multipleUpdate($commissionUpdateData);
        $data['commissionInserted'] = array_merge($commissionUpdateData, $commissionData);
        return [];
    }
    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function updateIntroduce(&$data)
    {
        $introduceCommission = [];
        Log::debug("________1@call __update_introduce()");
        /* If there is no transaction type = "referral base", I do nothing*/
        $introduceInfo = array_filter($data['commissionInfo'], function ($item) {
            return $item['commission_type'] == 1;
        });
        if ($this->checkConditionUpdateIntroduce($data, $introduceInfo)) {
            return [];
        }
        /* Newly registered company ID list (for billing information registration)*/
        $inserted = [];
        /* Registered company ID list (deletion of billing data)*/
        $insertedDel = [];
        /*Retrieve deal ID*/
        $demandId = (array_key_exists('id', $data['demandInfo'])) ? $data['demandInfo']['id'] : null;
        /* Registration of introduction destination information*/
        $introduceData = [];
        foreach ($introduceInfo as $key => $val) {
            /* Confirm existence of registered data with case ID and company ID*/
            $currentData = $this->commissionInfoRepo->findByDemandIdCorpAndType($demandId, $val['corp_id'], getDivValue('commission_type', 'package_estimate'));
            /* Update or registration judgment*/
            if (!empty($currentData)) {
                Log::debug("2@call 更新");
                /* When updating*/
                $commissionIntro = $this->createIntroduceDataWhenUpdate($val, $currentData, $demandId, $inserted, $insertedDel);
                if (isset($val['id_staff'])) {
                    $commissionIntro['id_staff'] = $val['id_staff'];
                }
                $introduceData[$key] = $commissionIntro;
            } else {
                Log::debug("_________2@call 新規");
                /* In case of new registration*/
                $commissionIntro = $this->createIntroduceDataWhenInsert($val, $data, $demandId, $inserted);
                if (isset($val['id_staff'])) {
                    $commissionIntro['id_staff'] = $val['id_staff'];
                }
                $introduceData[$key] = $commissionIntro;
            }
            /* When individual transmission is performed*/
            if (in_array($data['demandInfo']['demand_status'], [4, 5]) && $data['not-send'] == 1) {
                $introduceData[$key]['send_mail_fax_othersend'] = 1;
            }
        }
        /* Refresh introduction destination information returned.*/
        $introduceCommission = $this->updateMultipleIntroduceData($introduceData, $data['demandInfo']['id']);
        /* Lilo · Creca deal*/
        $this->updateBillWhenUpdateIntroduce($data, $inserted, $insertedDel, $demandId);
        return $introduceCommission;
    }

    /**
     * @param $demandId
     * @param $categoryId
     * @param $insData
     * @return mixed
     */
    public function commissionUpdateBill($demandId, $categoryId, $insData)
    {
        /* Acquisition of consumption tax rate*/
        $taxRateObj = $this->mTaxRateRepo->findByDate(date('Y/m/d'));
        $taxRate = $taxRateObj ? $taxRateObj->tax_rate : 1;
        $defaultFee = $this->mCategoryRepo->getDefaultFee($categoryId);
        /* Create billing information*/
        $saveData = [];
        foreach ($insData as $key => $val) {
            /*Acquiring agency information*/
            $currentData = $this->commissionInfoRepo->findByDemandIdCorpAndType($demandId, $val, getDivValue('commission_type', 'package_estimate'));
            /* Acquisition of referral fee*/
            $introduceFee = $this->mCorpCategoryRepo->getIntroduceFee($val, $categoryId, $defaultFee);
            /* Assignment ID*/
            $saveData[$key]['commission_id'] = $currentData['commissionInfo']['id'];
            /* Opportunity ID*/
            $saveData[$key]['demand_id'] = $demandId;
            /* Billing status: 1 (not issued)*/
            $saveData[$key]['bill_status'] = getDivValue('bill_status', 'not_issue');
            /* Commitment commission rate: 100*/
            $saveData[$key]['comfirmed_fee_rate'] = 100;
            /* Commissionable amount: Compatible genre master by company. Referral fee*/
            $saveData[$key]['fee_target_price'] = $introduceFee;
            /* Commission fee: (Final commission rate / 100) * Commissionable amount*/
            $saveData[$key]['fee_tax_exclude'] = ($saveData[$key]['comfirmed_fee_rate'] / 100) * $introduceFee;
            /* Consumption tax: commission * consumption tax rate*/
            $saveData[$key]['tax'] = floor($saveData[$key]['fee_tax_exclude'] * $taxRate);
            /* Total charge amount: commission + consumption tax*/
            $saveData[$key]['total_bill_price'] = $saveData[$key]['fee_tax_exclude'] + $saveData[$key]['tax'];
            /* Commission payment amount*/
            $saveData[$key]['fee_payment_price'] = 0;
            /* Total charge amount: commission + consumption tax*/
            $saveData[$key]['fee_payment_balance'] = $saveData[$key]['fee_tax_exclude'] + $saveData[$key]['tax'];
        }
        return $this->billRepo->insert($saveData);
    }

    /**
     * @param $demandId
     * @param $listCorpNoStaff
     * @throws \Exception
     */
    public function sendNotify($demandId, $listCorpNoStaff)
    {
        /* Get record of commission_infos from demand_id*/
        $commissionInfosDatas = $this->commissionInfoRepo->getListByDemandId($demandId, true);
        /*Send when the push notification flag is 0 and when the company's disbursement method is 6 or 7*/
        $arrIdPush = [];
        foreach ($commissionInfosDatas as $commissionInfosData) {
            if (!isset($commissionInfosData->mCorp->coordination_method)) {
                continue;
            }
            if ($commissionInfosData->app_push_flg == 0
                && ($commissionInfosData->mCorp->coordination_method == 6 || $commissionInfosData->mCorp->coordination_method == 7)
                && in_array($commissionInfosData->corp_id, $listCorpNoStaff)
            ) {
                 $extendData = [
                    'url_redirect' => route('commission.detail', ['id' => $commissionInfosData->id])
                ];
                $corpId = $commissionInfosData->corp_id;
                /*Acquire the user ID associated with the member store ID*/
                $users = $this->mUserRepo->getUserByAffiliationId($corpId);
                foreach ($users as $user) {
                    /*Send notification*/
                    $pushMessage = '新しい案件があります。';
                    $this->snsService->publish($user->user_id, $pushMessage, $extendData);
                    Log::info('____SNS___: push notify ____________');
                }
                /*After sending app_push_flg is set to 1*/
                $arrIdPush[] = $commissionInfosData->id;
            }
        }
        if (!empty($arrIdPush)) {
            $this->commissionInfoRepo->updateAppPushFlg($arrIdPush);
        }
    }
    /**
     * @param $autoCommissions
     * @param $defaultFee
     * @param $commissionInfos
     */
    public function buildCommissionData($autoCommissions, $defaultFee, $commissionInfos)
    {
        $isSelected = 0;
        foreach ($autoCommissions as $val) {
            if ($val['mCorpCategory']['corp_commission_type'] != 2) {
                /* Conclusion base*/
                $orderFee = $val['mCorpCategory']['order_fee'];
                $orderFeeUnit = $val['mCorpCategory']['order_fee_unit'];
                $commissionStatus = getDivValue('construction_status', 'progression');
                $commissionType = getDivValue('commission_type', 'normal_commission');
            } else {
                //Introduction base
                $orderFee = $val['mCorpCategory']['introduce_fee'];
                $orderFeeUnit = 0;
                $commissionStatus = getDivValue('construction_status', 'introduction');
                $commissionType = getDivValue('commission_type', 'package_estimate');
            }
            $orderFee = !empty($orderFee) ? $orderFee : $defaultFee->category_default_fee;
            $orderFeeUnit = !empty($orderFee) ? $orderFeeUnit : $defaultFee->category_default_fee_unit;
            /* If an automatically selected supplier has already been registered, do not register*/
            $hasCommissions = array_filter($commissionInfos, function ($v) use ($val) {
                return $v['corp_id'] == $val['mCorp']['id'];
            });
            if (!empty($hasCommissions)) {
                continue;
            }
            $commissionInfos[] = [
                'corp_id' => $val['mCorp']['id'], // corp ID
                'first_commission' => 0, // Initial check check
                'unit_price_calc_exclude' => 0, // Not covered by contract price
                'commit_flg' => 0, // Confirm
                'lost_flg' => 0, //Prior to ordering
                'corp_fee' => $orderFeeUnit == 0 ? $orderFee : null, // Brokerage fee
                'commission_fee_rate' => $orderFeeUnit == 0 ? null : $orderFee, // Commission rate at commission
                'select_commission_unit_price_rank' => $val['affiliationAreaStat']['commission_unit_price_rank'], // Unit price rank
                'select_commission_unit_price' => $val['affiliationAreaStat']['commission_unit_price_category'], // Unit price per contract
                'order_fee_unit' => $orderFeeUnit,
                'appointers' => auth()->id(), // Selector
                'corp_claim_flg' => null, //Agency complaint
                'commission_note_send_datetime' => null, //Date and time of agency sent
                'commission_note_sender' => null, // Mail order sender
                'del_flg' => 0, // Delete
                'created_user_id' => 'AutomaticAuction',
                'modified_user_id' => 'AutomaticAuction',
                'commission_status' => $commissionStatus,
                'commission_type' => $commissionType,
            ];
            $isSelected = 1;
        }
        return ['commissionInfos' => $commissionInfos, 'isSelected' => $isSelected];
    }
    /**
     * @param $data
     * @return bool
     */
    public function updateCommissionSendMailFax($data)
    {
        /* If supplier information is not entered, nothing is done*/
        if (!isset($data['commissionInfo']) && !isset($data['introduceInfo'])) {
            return true;
        }
        /* Retrieve deal ID*/
        $commissionInfoData = $this->commissionInfoRepo->getAllCommissionByDemandId($data['demandInfo']['id']);
        $commissionData = [];
        foreach ($commissionInfoData as $key => $val) {
            if ($this->checkConditionUpdateSendMailFax($data, $val)) {
                $corpInfo = $this->mCorpRepo->getFirstById($val->corp_id);
                /* When the customer information contacting means of the member store information is "Mail + FAX", "Mail", "FAX"*/
                $commissionData = $this->fillCommissionDataWhenUpdateSendMailFax($commissionData, $corpInfo, $key, $val);
            }
        }
        if (!empty($commissionData)) {
            Log::debug('_______ start udpate commission after send mail _____');
            $this->commissionInfoRepo->multipleUpdate($commissionData);
            Log::debug('_______ end udpate commission after send mail _____');
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function checkCommissionFlgCount($data)
    {
        $commissionFlgCount = 0;
        if (isset($data['send_commission_info'])) {
            foreach ($data['commissionInfo'] as $commission) {
                if (!empty($commission['corp_id']) && isset($commission['commit_flg']) && $commission['commit_flg']) {
                    $commissionFlgCount = $commissionFlgCount + 1;
                }
            }
            if ($commissionFlgCount == 0) {
                return false;
            }
        }
        return true;
    }
}
