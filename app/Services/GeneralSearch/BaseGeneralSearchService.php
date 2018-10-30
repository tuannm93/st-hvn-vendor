<?php

namespace App\Services\GeneralSearch;

use App\Services\BaseService;

class BaseGeneralSearchService extends BaseService
{
    /**
     * @var mixed
     */
    protected $corpCommissionTypeList;
    /**
     * @var mixed
     */
    protected $demandInfoSelectionSystem;
    /**
     * @var mixed
     */
    protected $corpsAuctionMasking;
    /**
     * @var mixed
     */
    protected $corpsCommissionAcceptFlg;
    /**
     * @var mixed
     */
    protected $corpsSupportLanguageEn;
    /**
     * @var mixed
     */
    protected $corpsSupportLanguageZh;
    /**
     * @var mixed
     */
    protected $affiliationListedKind;
    /**
     * @var mixed
     */
    protected $affiliationDefaultTax;
    /**
     * @var mixed
     */
    protected $corpAffiliationStatusList;
    /**
     * @var mixed
     */
    protected $targetAreasList;
    /**
     * @var mixed
     */
    protected $corpCategoriesOrderFeeUnitList;
    /**
     * @var mixed
     */
    protected $demandInfoPriorityList;
    /**
     * @var mixed
     */
    protected $corpsAuctionStatusList;
    /**
     * @var mixed
     */
    protected $reformUpSellIcList;

    /**
     * initDataFields
     */
    public function initDataFields()
    {

        $this->corpAffiliationStatusList = $this->generateAffiliationStatus();
        $this->targetAreasList = getDivList('datacustom.prefecture_div');
        $this->corpCategoriesOrderFeeUnitList = $this->generateOrderFeeList();
        $this->demandInfoPriorityList = $this->generatePriorityList();
        $this->corpsAuctionStatusList = $this->generateAuctionStatus();
        $this->reformUpSellIcList = $this->generateReformUpSellIcList();

        $this->corpsAuctionMasking = getDivList('datacustom.auction_masking', 'demandlist');
        $this->corpsCommissionAcceptFlg = [0 => '未契約', 1 => '契約完了', 2 => '契約未更新', 3 => '未更新STOP'];
        $this->corpsSupportLanguageEn = [0 => '未対応', 1 => '対応'];
        $this->corpsSupportLanguageZh = [0 => '未対応', 1 => '対応'];
        $this->affiliationListedKind = ['listed' => '上場', 'unlisted' => '非上場'];
        $this->affiliationDefaultTax = ['NULL' => '', '0' => '滞納なし', '1' => '滞納あり'];
        $corpCommissionType = getDivList('datacustom.corp_commission_type');
        $this->corpCommissionTypeList = array_merge(['0' => ''], $corpCommissionType);
        $this->demandInfoSelectionSystem = getDivList('datacustom.selection_type', 'demandlist');
    }

    /**
     * generate fields for demand_info
     * @return array
    */
    private function generateFieldsForDemandInfo()
    {
        return [
            'id',
            'follow_date',
            'demand_status',
            'order_fail_reason',
            'reservation_demand',
            'mail_demand',
            'nighttime_takeover',
            'low_accuracy',
            'remand',
            'auction',
            'priority',
            'immediately',
            'corp_change',
            'receive_datetime',
            'site_id',
            'genre_id',
            'category_id',
            'cross_sell_source_site',
            'cross_sell_source_genre',
            'cross_sell_source_category',
            'source_demand_id',
            'receptionist',
            'customer_name',
            'customer_tel',
            'customer_mailaddress',
            'postcode',
            'address1',
            'address2',
            'address3',
            'address4',
            'building',
            'room',
            'tel1',
            'tel2',
            'contents',
            'contact_desired_time',
            'selection_system',
            'pet_tombstone_demand',
            'sms_demand',
            'jbr_order_no',
            'jbr_work_contents',
            'jbr_category',
            'jbr_receipt_price',
            'mail',
            'order_date',
            'complete_date',
            'order_fail_date',
            'jbr_estimate_status',
            'jbr_receipt_status',
            'development_request',
            'share_notice',
            'del_flg',
            'riro_kureka',
            'modified_user_id',
            'modified',
            'created_user_id',
            'created',
            'order_no_marriage',
            'order_loss',
            'same_customer_demand_url',
            'upload_estimate_file_name',
            'upload_receipt_file_name',
            'cost_customer_question',
            'cost_from',
            'cost_to',
            'special_measures',
            'cost_customer_answer',
            'call_back_time',
            'acceptance_status',
            'cross_sell_implement',
            'cross_sell_call',
            'commission_limitover_time',
            'sms_reorder',
            'business_trip_amount',
            'auction_deadline_time',
            'auction_start_time',
            'construction_class',
            'contact_desired_time_from',
            'contact_desired_time_to',
            'customer_corp_name',
            'nitoryu_flg',
            'calendar_flg'
        ];
    }

    /**
     * generate fields for commission_info
     * @return array
     */
    private function generateFieldsForCommissionInfo()
    {
        return [
            'id',
            'demand_id',
            'corp_id',
            'commit_flg',
            'commission_type',
            'lost_flg',
            'appointers',
            'first_commission',
            'corp_fee',
            'waste_collect_oath',
            'attention',
            'commission_dial',
            'tel_commission_datetime',
            'tel_commission_person',
            'commission_fee_rate',
            'commission_note_send_datetime',
            'commission_note_sender',
            'commission_status',
            'commission_order_fail_reason',
            'complete_date',
            'order_fail_date',
            'estimate_price_tax_exclude',
            'construction_price_tax_exclude',
            'construction_price_tax_include',
            'deduction_tax_include',
            'deduction_tax_exclude',
            'confirmd_fee_rate',
            'unit_price_calc_exclude',
            'introduction_free',
            'report_note',
            'del_flg',
            'checked_flg',
            'irregular_fee_rate',
            'irregular_fee',
            'irregular_reason',
            'falsity',
            'follow_date',
            'introduction_not',
            'lock_status',
            'commission_status_last_updated',
            'progress_reported',
            'progress_report_datetime',
            'modified_user_id',
            'modified',
            'created_user_id',
            'created',
            'send_mail_fax',
            'send_mail_fax_datetime',
            'select_commission_unit_price_rank',
            'reform_upsell_ic',
            'remand_flg',
            're_commission_exclusion_status',
            're_commission_exclusion_user_id',
            're_commission_exclusion_datetime',
            'send_mail_fax_sender',
            'business_trip_amount',
            'tel_support',
            'visit_support',
            'order_support',
            'remand_reason',
            'remand_correspond_person',
            'visit_desired_time',
            'order_respond_datetime',
            'select_commission_unit_price',
            'send_mail_fax_othersend',
            'ac_commission_exclusion_flg',
            'order_fee_unit',
            'fee_billing_date',
        ];
    }

    /**
     * generate fields for bill_info
     * @return array
     */
    private function generateFieldsForBillInfo()
    {
        return [
            'id',
            'demand_id',
            'bill_status',
            'irregular_fee_rate',
            'irregular_fee',
            'deduction_tax_include',
            'deduction_tax_exclude',
            'indivisual_billing',
            'comfirmed_fee_rate',
            'fee_target_price',
            'fee_tax_exclude',
            'tax',
            'insurance_price',
            'total_bill_price',
            'fee_billing_date',
            'fee_payment_date',
            'fee_payment_price',
            'fee_payment_balance',
            'report_note',
            'commission_id',
            'modified_user_id',
            'modified',
            'created_user_id',
            'created',
            'business_trip_amount',
        ];
    }

    /**
     * generate fields for m_corp
     * @return array
     */
    private function generateFieldsForCorp()
    {
        return [
            'id',
            'corp_name',
            'corp_name_kana',
            'official_corp_name',
            'affiliation_status',
            'responsibility',
            'postcode',
            'address1',
            'address2',
            'address3',
            'address4',
            'building',
            'room',
            'trade_name1',
            'trade_name2',
            'commission_dial',
            'tel1',
            'tel2',
            'mobile_tel',
            'fax',
            'mailaddress_pc',
            'mailaddress_mobile',
            'url',
            'target_range',
            'available_time',
            'support24hour',
            'contactable_time',
            'free_estimate',
            'portalsite',
            'reg_send_date',
            'reg_collect_date',
            'ps_app_send_date',
            'ps_app_collect_date',
            'coordination_method',
            'prog_send_method',
            'prog_send_mail_address',
            'prog_send_fax',
            'prog_irregular',
            'bill_send_method',
            'bill_send_address',
            'bill_irregular',
            'special_agreement',
            'contract_date',
            'order_fail_date',
            'commission_ng_date',
            'note',
            'document_send_request_date',
            'follow_person',
            'advertising_status',
            'advertising_send_date',
            'progress_check_tel',
            'progress_check_person',
            'payment_site',
            'del_flg',
            'rits_person',
            'reg_send_method',
            'geocode_lat',
            'geocode_long',
            'follow_date',
            'corp_status',
            'order_fail_reason',
            'corp_commission_status',
            'listed_media',
            'jbr_available_status',
            'mailaddress_auction',
            'auction_status',
            'corp_commission_type',
            'corp_person',
            'available_time_from',
            'available_time_to',
            'contactable_time_from',
            'contactable_time_to',
            'contactable_support24hour',
            'contactable_time_other',
            'available_time_other',
            'seikatsu110_id',
            'mobile_mail_none',
            'mobile_tel_type',
            'auction_masking',
            'commission_accept_flg',
            'commission_accept_date',
            'commission_accept_user_id',
            'representative_postcode',
            'representative_address1',
            'representative_address2',
            'representative_address3',
            'refund_bank_name',
            'refund_branch_name',
            'refund_account_type',
            'refund_account',
            'support_language_en',
            'support_language_zh',
            'support_language_employees',
            'auto_call_flag',
        ];
    }

    /**
     * generate fields for affiliatio_info
     * @return array
     */
    private function generateFieldsForAffiliationInfo()
    {
        return [
            'id',
            'corp_id',
            'employees',
            'max_commission',
            'collection_method',
            'collection_method_others',
            'liability_insurance',
            'reg_follow_date1',
            'reg_follow_date2',
            'reg_follow_date3',
            'waste_collect_oath',
            'transfer_name',
            'claim_count',
            'claim_history',
            'commission_count',
            'weekly_commission_count',
            'orders_count',
            'orders_rate',
            'construction_cost',
            'fee',
            'bill_price',
            'payment_price',
            'balance',
            'construction_unit_price',
            'commission_unit_price',
            'sf_construction_unit_price',
            'sf_construction_count',
            'reg_info',
            'reg_pdf_path',
            'attention',
            'corp_id',
            'modified_user_id',
            'modified',
            'created_user_id',
            'created',
            'credit_limit',
            'listed_kind',
            'default_tax',
            'capital_stock',
            'virtual_account',
            'add_month_credit',
        ];
    }

    /**
     * generate fields for table
     *
     * @return array
     */
    public function generateFieldsForFunctionTables()
    {
        return [
            [
                'demand_infos' => $this->generateFieldsForDemandInfo(),
                'visit_times' => [
                    'visit_time',
                ],
            ],
            [
                'commission_infos' => $this->generateFieldsForCommissionInfo(),
                'bill_infos' => [
                    'auction_id',
                ],
                'demand_infos' => [
                    'contact_desired_time',
                ],
                'commission_tel_supports' => [
                    'correspond_status',
                    'order_fail_reason',
                ],
                'visit_times' => [
                    'visit_time',
                ],
                'commission_visit_supports' => [
                    'correspond_status',
                    'order_fail_reason',
                ],
                'commission_order_supports' => [
                    'correspond_datetime',
                    'correspond_status',
                    'order_fail_reason',
                ],
            ],
            [
                'bill_infos' => $this->generateFieldsForBillInfo(),
            ],
            [
                'm_corps' => $this->generateFieldsForCorp(),
                'affiliation_infos' => $this->generateFieldsForAffiliationInfo(),
                'm_corp_categories' => [
                    'genre_id',
                    'category_id',
                    'order_fee',
                    'order_fee_unit',
                    'introduce_fee',
                    'introduce_fee_unit',
                    'note',
                    'modified_user_id',
                    'modified',
                    'created_user_id',
                    'created',
                    'corp_commission_type',
                ],
                'm_target_areas' => [
                    'jis_cd',
                    'address1_cd',
                ],
            ],
        ];
    }

    /**
     * generate columns
     *
     * @return array
     */
    public function generateColumns()
    {
        return [
            1 => [
                'demand_infos.contact_desired_time' => '[電話対応]初回連絡希望日時',
                'commission_tel_supports.correspond_status' => '[電話対応]最新状況',
                'commission_tel_supports.order_fail_reason' => '[電話対応]失注理由',
                'visit_times.visit_time' => '[訪問対応]訪問日時',
                'commission_visit_supports.correspond_status' => '[訪問対応]最新状況',
                'commission_visit_supports.order_fail_reason' => '[訪問対応]失注理由',
                'commission_order_supports.correspond_datetime' => '[受注対応]受注対応日時',
                'commission_order_supports.correspond_status' => '[受注対応]最新状況',
                'commission_order_supports.order_fail_reason' => '[受注対応]失注理由',
                'bill_infos.auction_id' => '入札手数料',
                'commission_infos.fee_billing_date' => 'お試し手数料請求日',
            ],
        ];
    }

    /**
     * generate fields for reference table
     *
     * @return array
     */
    public function generateReferenceTable()
    {
        return [
            'demand_infos_demand_status' => ['v' => 'itemDemandStatusList', 's' => 'initializeCorpService'],
            'demand_infos_order_fail_reason' => ['v' => 'itemDemandOrderFailReasonList', 's' => 'initializeCorpService'],
            'demand_infos_site_id' => ['v' => 'siteList', 's' => 'initializeGeneralService'],
            'demand_infos_genre_id' => ['v' => 'genreList', 's' => 'initializeGeneralService'],
            'demand_infos_receptionist' => ['v' => 'userList', 's' => 'initializeGeneralService'],
            'demand_infos_category_id' => ['v' => 'categoryList', 's' => 'initializeGeneralService'],
            'demand_infos_cross_sell_source_site' => ['v' => 'siteList', 's' => 'initializeGeneralService'],
            'demand_infos_cross_sell_source_genre' => ['v' => 'genreList', 's' => 'initializeGeneralService'],
            'demand_infos_cross_sell_source_category' => ['v' => 'categoryList', 's' => 'initializeGeneralService'],
            'demand_infos_jbr_work_contents' => ['v' => 'jbrWordcontentsList', 's' => 'initializeCorpService'],
            'demand_infos_jbr_category' => ['v' => 'itemJbrCategoryList', 's' => 'initializeCorpService'],
            'demand_infos_jbr_estimate_status' => ['v' => 'itemJbrEstimateList', 's' => 'initializeItemService'],
            'demand_infos_jbr_receipt_status' => ['v' => 'itemJbrReceiptList', 's' => 'initializeItemService'],
            'demand_corresponds_responders' => ['v' => 'userList', 's' => 'initializeGeneralService'],
            'demand_infos_modified_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'demand_infos_created_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'demand_infos_address1' => ['v' => 'address1List', 's' => 'initializeGeneralService'],
            'demand_infos_pet_tombstone_demand' => ['v' => 'itemPetTombstoneDemandList', 's' => 'initializeItemService'],
            'demand_infos_sms_demand' => ['v' => 'itemSmsDemandList', 's' => 'initializeCorpService'],
            'demand_infos_special_measures' => ['v' => 'itemSpecialMeasuresList', 's' => 'initializeItemService'],
            'demand_infos_acceptance_status' => ['v' => 'itemAcceptanceStatusList', 's' => 'initializeItemService'],
            'demand_infos_priority' => ['v' => 'demandInfoPriorityList', 's' => ''],
            'commission_infos_commission_type' => ['v' => 'commissionTypeList', 's' => 'initializeCommissionService'],
            'commission_infos_appointers' => ['v' => 'userList', 's' => 'initializeGeneralService'],
            'commission_infos_tel_commission_person' => ['v' => 'userList', 's' => 'initializeGeneralService'],
            'commission_infos_commission_note_sender' => ['v' => 'userList', 's' => 'initializeGeneralService'],
            'commission_infos_commission_status' => ['v' => 'itemCommissionStatusList', 's' => 'initializeCommissionService'],
            'commission_infos_commission_order_fail_reason' => ['v' => 'corpOrderFailReasonList', 's' => 'initializeCorpService'],
            'commission_infos_modified_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'commission_infos_created_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'bill_infos_bill_status' => ['v' => 'itemBillStatusList', 's' => 'initializeItemService'],
            'bill_infos_modified_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'bill_infos_created_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'm_corps_address1' => ['v' => 'address1List', 's' => 'initializeGeneralService'],
            'm_corps_coordination_method' => ['v' => 'itemCoordinationMethodList', 's' => 'initializeItemService'],
            'm_corps_prog_send_method' => ['v' => 'itemProgSendMethodList', 's' => 'initializeItemService'],
            'm_corps_bill_send_method' => ['v' => 'itemBillSendMethodList', 's' => 'initializeItemService'],
            'm_corps_follow_person' => ['v' => 'userList', 's' => 'initializeGeneralService'],
            'm_corps_advertising_status' => ['v' => 'itemAdvertisingStatusList', 's' => 'initializeItemService'],
            'm_corps_payment_site' => ['v' => 'itemPaymentSiteList', 's' => 'initializeCorpService'],
            'm_corps_rits_person' => ['v' => 'userList', 's' => 'initializeGeneralService'],
            'm_corps_corp_status' => ['v' => 'corpStatusList', 's' => 'initializeCorpService'],
            'm_corps_corp_commission_status' => ['v' => 'corpCommissionStatusList', 's' => 'initializeCorpService'],
            'm_corps_affiliation_status' => ['v' => 'corpAffiliationStatusList', 's' => ''],
            'm_corps_reg_send_method' => ['v' => 'itemRegSendMethodList', 's' => 'initializeItemService'],
            'm_corps_order_fail_reason' => ['v' => 'corpOrderFailReasonList', 's' => 'initializeCorpService'],
            'affiliation_infos_modified_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'affiliation_infos_created_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'affiliation_infos_liability_insurance' => ['v' => 'itemLiabilityInsurance', 's' => 'initializeItemService'],
            'affiliation_infos_waste_collect_oath' => ['v' => 'itemWasteCollectOath', 's' => 'initializeItemService'],
            'm_corp_categories_genre_id' => ['v' => 'genreList', 's' => 'initializeGeneralService'],
            'm_corp_categories_category_id' => ['v' => 'categoryList', 's' => 'initializeGeneralService'],
            'm_corp_categories_modified_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'm_corp_categories_created_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'm_corp_categories_order_fee_unit' => ['v' => 'corpCategoriesOrderFeeUnitList', 's' => ''],
            'm_corp_categories_corp_commission_type' => ['v' => 'corpCommissionTypeList', 's' => ''],
            'm_target_areas_jis_cd' => ['v' => 'targetAreasList', 's' => ''],
            'm_corps_auction_status' => ['v' => 'corpsAuctionStatusList', 's' => ''],
            'm_corps_corp_commission_type' => ['v' => 'corpsCorpCommissionType', 's' => 'initializeCorpService'],
            'commission_infos_reform_upsell_ic' => ['v' => 'reformUpSellIcList', 's' => ''],
            'commission_infos_re_commission_exclusion_status' => ['v' => 'reExclusionStatus', 's' => 'initializeCommissionService'],
            'commission_infos_re_commission_exclusion_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'commission_infos_send_mail_fax_sender' => ['v' => 'userList', 's' => 'initializeGeneralService'],
            'commission_tel_supports_correspond_status' => ['v' => 'telSupportsCorrespondStatus', 's' => 'initializeCommissionService'],
            'commission_visit_supports_correspond_status' => ['v' => 'visitSupportsCorrespondStatus', 's' => 'initializeCommissionService'],
            'commission_order_supports_correspond_status' => ['v' => 'orderSupportsCorrespondStatus', 's' => 'initializeCommissionService'],
            'commission_tel_supports_order_fail_reason' => ['v' => 'telSupportsOrderFailReason', 's' => 'initializeCommissionService'],
            'commission_visit_supports_order_fail_reason' => ['v' => 'telSupportsOrderFailReason', 's' => 'initializeCommissionService'],
            'commission_order_supports_order_fail_reason' => ['v' => 'orderSupportsOrderFailReason', 's' => 'initializeCommissionService'],
            'commission_infos_irregular_reason' => ['v' => 'irregularReasonList', 's' => 'initializeCommissionService'],
            'm_corps_jbr_available_status' => ['v' => 'corpsJbrAvailableStatus', 's' => 'initializeCorpService'],
            'demand_infos_construction_class' => ['v' => 'demandInfoConstructionClass', 's' => 'initializeCommissionService'],
            'm_corps_mobile_tel_type' => ['v' => 'corpsMobileTelType', 's' => 'initializeCorpService'],
            'm_corps_auction_masking' => ['v' => 'corpsAuctionMasking', 's' => ''],
            'm_corps_commission_accept_flg' => ['v' => 'corpsCommissionAcceptFlg', 's' => ''],
            'm_corps_commission_accept_user_id' => ['v' => 'userList2', 's' => 'initializeGeneralService'],
            'm_corps_support_language_en' => ['v' => 'corpsSupportLanguageEn', 's' => ''],
            'm_corps_support_language_zh' => ['v' => 'corpsSupportLanguageZh', 's' => ''],
            'm_corps_representative_address1' => ['v' => 'address1List', 's' => 'initializeGeneralService'],
            'affiliation_infos_listed_kind' => ['v' => 'affiliationListedKind', 's' => ''],
            'affiliation_infos_default_tax' => ['v' => 'affiliationDefaultTax', 's' => ''],
            'commission_infos_order_fee_unit' => ['v' => 'corpCategoriesOrderFeeUnitList', 's' => ''],
            'demand_infos_selection_system' => ['v' => 'demandInfoSelectionSystem', 's' => ''],
            'm_corps_auto_call_flag' => ['v' => 'corpsAutoCallFlag', 's' => 'initializeCorpService'],
        ];
    }

    /**
     * generate fields for order
     *
     * @return array
     */
    public function generateFieldsForOrder()
    {
        return [
            'demand_infos',
            'visit_times',
            'commission_infos',
            'commission_tel_supports',
            'commission_visit_supports',
            'commission_order_supports',
            'bill_infos',
            'm_corps',
            'affiliation_infos',
            'm_corp_categories',
            'm_target_areas',
        ];
    }

    /**
     * generate fields for rules
     *
     * @return array
     */
    public function generateRules()
    {
        return [
            'demand_infos' => ['cond' => null],
            'visit_times' => [
                'cond' => 'demand_infos.id = visit_times.demand_id',
            ],
            'commission_infos' => [
                'cond' => 'demand_infos.id = commission_infos.demand_id',
            ],
            'bill_infos' => [
                'cond' => 'demand_infos.id = bill_infos.demand_id and commission_infos.id = bill_infos.commission_id and bill_infos.auction_id IS NULL',
            ],
            'm_corps' => [
                'cond' => 'commission_infos.corp_id = m_corps.id',
            ],
            'affiliation_infos' => [
                'cond' => 'm_corps.id = affiliation_infos.corp_id',
            ],
            'm_corp_categories' => [
                'cond' => 'm_corps.id = m_corp_categories.corp_id',
            ],
            'm_target_areas' => [
                'cond' => 'm_corp_categories.id = m_target_areas.corp_category_id',
            ],
            'commission_tel_supports' => [
                'cond' => "commission_infos.id = commission_tel_supports.commission_id
				AND commission_tel_supports.id =
				(select id from commission_tel_supports ct where ct.commission_id = commission_infos.id order by ct.created desc limit 1)",
            ],
            'commission_visit_supports' => [
                'cond' => "commission_infos.id = commission_visit_supports.commission_id
				AND commission_visit_supports.id =
				(select id from commission_visit_supports cv where cv.commission_id = commission_infos.id order by cv.created desc limit 1)",
            ],
            'commission_order_supports' => [
                'cond' => "commission_infos.id = commission_order_supports.commission_id
				 AND commission_order_supports.id =
				(select id from commission_order_supports co where co.commission_id = commission_infos.id order by co.created desc limit 1)",
            ],
        ];
    }

    /**
     * generate fields for affiliation status
     *
     * @return array
    */
    public function generateAffiliationStatus()
    {
        return ['0' => '未加盟', '1' => '加盟', '-1' => '解約'];
    }

    /**
     * generate fields for order fee
     *
     * @return array
     */
    public function generateOrderFeeList()
    {
        return ['0' => '円', '1' => '％'];
    }

    /**
     * generate fields for priority
     *
     * @return array
     */
    public function generatePriorityList()
    {
        return ['0' => '-', '1' => '大至急', '2' => '至急', '3' => '通常'];
    }

    /**
     * generate fields for auction status
     *
     * @return array
     */
    public function generateAuctionStatus()
    {
        return ['1' => '通常選定＋入札式選定', '2' => '通常選定のみ', '3' => '入札式選定のみ'];
    }

    /**
     * generate fields for reform up sellIc
     *
     * @return array
     */
    public function generateReformUpSellIcList()
    {
        return ['1' => '申請', '2' => '認証', '3' => '非認証'];
    }

    /**
     * generate fields for re exclusion status
     *
     * @return array
     */
    public function generateReExclusionStatus()
    {
        return ['0' => '', '1' => '成功', '2' => '失敗'];
    }
}
