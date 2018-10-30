<?php
namespace App\Services;

use App\Repositories\ProgCorpRepositoryInterface;
use App\Repositories\ProgDemandInfoRepositoryInterface;
use App\Repositories\ProgImportFilesRepositoryInterface;
use DB;

class ProgImportService
{
    /**
     * @var string
     */
    private static $user = 'system';

    /**
     * @var ProgImportFilesRepositoryInterface
     */
    protected $progImportFilesRepo;
    /**
     * @var ProgCorpRepositoryInterface
     */
    protected $progCorpRepo;
    /**
     * @var ProgDemandInfoRepositoryInterface
     */
    protected $progDemandInfoRepo;

    /**
     * ProgImportService constructor.
     * @param ProgImportFilesRepositoryInterface $progImportFilesRepo
     * @param ProgCorpRepositoryInterface $progCorpRepo
     * @param ProgDemandInfoRepositoryInterface $progDemandInfoRepo
     */
    public function __construct(
        ProgImportFilesRepositoryInterface $progImportFilesRepo,
        ProgCorpRepositoryInterface $progCorpRepo,
        ProgDemandInfoRepositoryInterface $progDemandInfoRepo
    ) {
        $this->progImportFilesRepo = $progImportFilesRepo;
        $this->progCorpRepo = $progCorpRepo;
        $this->progDemandInfoRepo = $progDemandInfoRepo;
    }

    /**
     * @param $argument
     * @param bool $flag
     * @return mixed
     */
    public function insertProgImportFiles($argument, $flag = false)
    {
        $title = "進捗表（末日締め）".date('Y')."年".date('m')."月分";
        if ($flag) {
            $title = "進捗表（20日締め）".date('Y')."年".date('m')."月分";
        }
        if (!empty($argument['args'][1])) {
            $title = $argument['args'][1];
        }
        $lockFlag = 0;
        $releaseFlag = 0;
        if (!empty($argument['args'][0])) {
            if ($argument['args'][0] === 'lock') {
                $lockFlag = 1;
                $releaseFlag = 1;
            }
        }
        $saveData = [
            'file_name' => $title,
            'original_file_name' => $title,
            'import_date' => date("Y-m-d H:i:s"),
            'delete_flag' => 0,
            'lock_flag' => $lockFlag,
            'release_flag' => $releaseFlag,
            'created_user_id' => 'system',
            'modified_user_id' => self::$user,
            'created' => date("Y-m-d H:i:s"),
            'modified' => date("Y-m-d H:i:s")
        ];
        return $this->progImportFilesRepo->updateProgImportFile($saveData);
    }

    /**
     * @param $pmNotCorp
     * @param $excludeCorpIdQueryPhrase
     * @return string
     */
    private function findCommissionInforsFirstQuery($pmNotCorp, $excludeCorpIdQueryPhrase)
    {
        return "SELECT commission_infos.id as commission_infos_id, demand_infos.id as demand_infos_id,
				commission_infos.commission_status as commission_status,
				demand_infos.receive_datetime as receive_datetime,
				demand_infos.customer_name as customer_name,
				m_corps.id as corp_id,
				m_corps.corp_name as corp_name,
				m_corps.official_corp_name as official_corp_name,
				affiliation_infos.construction_unit_price,
				demand_infos.customer_tel,
				demand_infos.customer_mailaddress,
				m_categories.category_name,
				commission_infos.commission_status,
				commission_infos.commission_order_fail_reason,
				commission_infos.complete_date,
				commission_infos.order_fail_date,
				commission_infos.construction_price_tax_exclude,
				commission_infos.construction_price_tax_include,
				m_corps.fax,
				m_corps.prog_send_method,
				m_corps.prog_send_mail_address,
				m_corps.prog_send_fax,
				m_corps.prog_irregular,
				m_corps.commission_dial,
				commission_infos.report_note,
				m_corps.mailaddress_pc,
				m_corps.bill_send_method,
				bill_infos.fee_billing_date,
				commission_infos.order_fee_unit,
				commission_infos.irregular_fee,
				commission_infos.irregular_fee_rate,
				commission_infos.commission_fee_rate,
				commission_infos.corp_fee,
				bill_infos.fee_target_price,
				m_genres.genre_name,
				demand_infos.tel1
			FROM
				commission_infos
			inner JOIN
				m_corps ON (commission_infos.corp_id = m_corps.id AND m_corps.del_flg = 0".$excludeCorpIdQueryPhrase.")
			inner JOIN
				demand_infos ON (demand_infos.id = commission_infos.demand_id AND demand_infos.del_flg != 1)
			left JOIN
				m_categories ON (m_categories.id = demand_infos.category_id)
			left JOIN
				m_items ON (m_items.item_category = '取次状況' AND m_items.item_id = commission_infos.commission_status)
			LEFT JOIN
				affiliation_infos ON affiliation_infos.corp_id = m_corps.id
			LEFT JOIN
				bill_infos ON bill_infos.demand_id = demand_infos.id AND bill_infos.commission_id = commission_infos.id AND bill_infos.auction_id is null
			LEFT JOIN
				m_corp_categories ON m_corp_categories.corp_id = commission_infos.corp_id AND m_corp_categories.category_id=demand_infos.category_id
			LEFT JOIN
				m_genres ON m_genres.id = demand_infos.genre_id
			WHERE
				(demand_infos.demand_status=4 OR demand_infos.demand_status=5)
				AND
					commission_infos.commission_type=0
				AND
					commission_infos.lost_flg=0
				AND
					demand_infos.del_flg=0
				AND
					commission_infos.del_flg=0
				AND
					commission_infos.commission_status IN (3,4)
				AND
					(commission_infos.progress_reported != 1  OR commission_infos.progress_reported IS NULL)
					".$pmNotCorp."
				AND
					(commission_note_send_datetime < '".date('Y-m-d')."' OR commission_note_send_datetime IS NULL)";
    }

    /**
     * @param $pmNotCorp
     * @return string
     */
    private function findCommissionInforsSecondQuery($pmNotCorp, $excludeCorpIdQueryPhrase)
    {
        return "SELECT
				commission_infos.id as commission_infos_id,
				demand_infos.id as demand_infos_id,
				commission_infos.commission_status as commission_status,
				demand_infos.receive_datetime as receive_datetime,
				demand_infos.customer_name as customer_name,
				m_corps.id as corp_id,
				m_corps.corp_name as corp_name,
				m_corps.official_corp_name as official_corp_name,
				affiliation_infos.construction_unit_price,
				demand_infos.customer_tel,
				demand_infos.customer_mailaddress,
				m_categories.category_name,
				commission_infos.commission_status,
				commission_infos.commission_order_fail_reason,
				commission_infos.complete_date,
				commission_infos.order_fail_date,
				commission_infos.construction_price_tax_exclude,
				commission_infos.construction_price_tax_include,
				m_corps.fax,
				m_corps.prog_send_method,
				m_corps.prog_send_mail_address,
				m_corps.prog_send_fax,
				m_corps.prog_irregular,
				m_corps.commission_dial,
				commission_infos.report_note,
				m_corps.mailaddress_pc,
				m_corps.bill_send_method,
				bill_infos.fee_billing_date,
				commission_infos.order_fee_unit,
				commission_infos.irregular_fee,
				commission_infos.irregular_fee_rate,
				commission_infos.commission_fee_rate,
				commission_infos.corp_fee,
				bill_infos.fee_target_price,
				m_genres.genre_name,
				demand_infos.tel1
			FROM
				commission_infos
			inner JOIN
				m_corps ON (commission_infos.corp_id = m_corps.id AND m_corps.del_flg = 0".$excludeCorpIdQueryPhrase.")
			inner JOIN
				demand_infos ON (demand_infos.id = commission_infos.demand_id AND demand_infos.del_flg != 1)
			left JOIN
				m_categories ON (m_categories.id = demand_infos.category_id)
			left JOIN
				m_items ON (m_items.item_category = '取次状況' AND m_items.item_id = commission_infos.commission_status)
			LEFT JOIN
				affiliation_infos ON affiliation_infos.corp_id = m_corps.id
			LEFT JOIN
				bill_infos ON bill_infos.demand_id = demand_infos.id AND bill_infos.commission_id = commission_infos.id AND bill_infos.auction_id is null
			LEFT JOIN
				m_corp_categories ON m_corp_categories.corp_id = commission_infos.corp_id AND m_corp_categories.category_id=demand_infos.category_id
			LEFT JOIN
				m_genres ON m_genres.id = demand_infos.genre_id
			WHERE
            (demand_infos.demand_status=4 OR demand_infos.demand_status=5)
            AND
            commission_infos.commission_type=0
            AND
            commission_infos.lost_flg=0
            AND
            demand_infos.del_flg=0
            AND
            commission_infos.del_flg=0
            AND
            commission_infos.commission_status IN (1,2)
            ".$pmNotCorp."
        AND
        (commission_note_send_datetime < '".date('Y-m-d')."' OR commission_note_send_datetime IS NULL)";
    }

    /**
     * @param bool $flag
     * @return mixed
     */
    public function findCommissionInfos($flag = false)
    {
        if ($flag) {
            $excludeCorpIds = config('datacustom.PM_CLOSING_DATE_20_CORP_IDS');
            $excludeCorpIdQueryPhrase = '';
            if (count($excludeCorpIds)) {
                $excludeCorpIdQueryPhrase = ' AND m_corps.id in (' . implode(',', $excludeCorpIds) . ')';
            }
            $sql = $this->findCommissionInforsFirstQuery("", $excludeCorpIdQueryPhrase) . " UNION " . $this->findCommissionInforsSecondQuery("", $excludeCorpIdQueryPhrase);
        } else {
            $pmNotCorp = config('datacustom.PM_NOT_CORP');
            $sql = $this->findCommissionInforsFirstQuery($pmNotCorp, "") . " UNION " . $this->findCommissionInforsSecondQuery($pmNotCorp, "");
        }
        return DB::select($sql);
    }

    /**
     * @param $commissionInfo
     * @param $importFileId
     * @return mixed
     */
    public function insertProgCorp($commissionInfo, $importFileId)
    {
        $partnerData = $this->progCorpRepo->findFirstByCorpIdAndFileId($commissionInfo->corp_id, $importFileId);
        if (empty($partnerData)) {
            $insert = [];
            $insert['corp_id'] = $commissionInfo->corp_id;
            $insert['progress_flag'] = "1";
            $insert['mail_last_send_date'] = null;
            $insert['collect_date'] = null;
            $insert['sf_register_date'] = null;
            $insert['call_back_phone_flag'] = '1';
            $insert['note'] = '';
            $insert['unit_cost'] = $commissionInfo->construction_unit_price;
            $insert['mail_count'] = 0;
            $insert['call_back_phone_date'] = null;
            $insert['fax_count'] = 0;
            $insert['fax_last_send_date'] = null;
            $insert['prog_import_file_id'] = $importFileId;
            $insert['contact_type'] = $commissionInfo->prog_send_method;
            $insert['fax'] = $commissionInfo->prog_send_fax;
            $insert['mail_address'] = $commissionInfo->prog_send_mail_address;
            $insert['irregular_method'] = $commissionInfo->prog_irregular;
            $insert['created_user_id'] = self::$user;
            $insert['modified_user_id'] = self::$user;
            $insert['created'] = date('Y-m-d H:i:s');
            $insert['modified'] = date('Y-m-d H:i:s');
            return $this->progCorpRepo->updateProgCorp($insert);
        } else {
            return $partnerData;
        }
    }

    /**
     * @param $commissionInfo
     * @param $insert
     */
    private function setDataOrderFeeUnitIs0(&$commissionInfo, &$insert)
    {
        if ($commissionInfo->irregular_fee != "" && $commissionInfo->irregular_fee != 0) {
            $insert['fee'] = $commissionInfo->irregular_fee;
        } else {
            $insert['fee'] = $commissionInfo->corp_fee;
        }
    }

    /**
     * @param $commissionInfo
     * @param $insert
     */
    private function setDataOrderFeeUnitIs1(&$commissionInfo, &$insert)
    {
        if ($commissionInfo->irregular_fee != "" && $commissionInfo->irregular_fee != 0) {
            $insert['fee'] = $commissionInfo->irregular_fee;
        } elseif ($commissionInfo->irregular_fee_rate != "" && $commissionInfo->irregular_fee_rate != 0) {
            $insert['fee_rate'] = $commissionInfo->irregular_fee_rate;
        } else {
            $insert['fee_rate'] = $commissionInfo->commission_fee_rate;
        }
    }

    /**
     * @param $commissionInfo
     * @param $insert
     */
    private function setDataOrderFeeUnitIsNull(&$commissionInfo, &$insert)
    {
        if ($commissionInfo->irregular_fee != "" && $commissionInfo->irregular_fee != 0) {
            $insert['fee'] = $commissionInfo->irregular_fee;
        } elseif ($commissionInfo->irregular_fee_rate != "" && $commissionInfo->irregular_fee_rate != 0) {
            $insert['fee_rate'] = $commissionInfo->irregular_fee_rate;
        } elseif ($commissionInfo->commission_fee_rate != "" && $commissionInfo->commission_fee_rate != 0) {
            $insert['fee_rate'] = $commissionInfo->commission_fee_rate;
        } elseif ($commissionInfo->corp_fee != "" && $commissionInfo->corp_fee != 0) {
            $insert['fee'] = $commissionInfo->corp_fee;
        }
    }

    /**
     * @param $commissionInfo
     * @param $importFileId
     * @param $progressCorpId
     */
    public function insertProgDemandInfo($commissionInfo, $importFileId, $progressCorpId, $lockFlag)
    {
        $condition = [
            'corp_id' => isset($commissionInfo->corp_id) ? $commissionInfo->corp_id : null,
            'commission_infos_id' => isset($commissionInfo->commission_infos_id) ? $commissionInfo->commission_infos_id : null
        ];
        $csvData = $this->progDemandInfoRepo->findByMulticondition($condition, $importFileId);
        if (empty($csvData)) {
            $insert = [];
            $insert['corp_id'] = $commissionInfo->corp_id;
            $insert['demand_id'] = $commissionInfo->demand_infos_id;
            $insert['genre_name'] = $commissionInfo->genre_name;
            $insert['category_name'] = $commissionInfo->category_name;
            $insert['customer_name'] = $commissionInfo->customer_name;
            $insert['commission_status_update'] = '0';
            $insert['diff_flg'] = 0;
            $insert['complete_date_update'] = "";
            $insert['construction_price_tax_exclude_update'] = "";
            $insert['construction_price_tax_include_update'] = "";
            $insert['comment_update'] = "";
            $insert['prog_import_file_id'] = $importFileId;
            $insert['prog_corp_id'] = $progressCorpId;
            $insert['commission_status'] = $commissionInfo->commission_status;
            $insert['commission_order_fail_reason'] = $commissionInfo->commission_order_fail_reason;
            if ($commissionInfo->commission_status == 3) {
                $insert['complete_date'] = $commissionInfo->complete_date;
            } elseif ($commissionInfo->commission_status == 4) {
                $insert['complete_date'] = $commissionInfo->order_fail_date;
            }
            $insert['construction_price_tax_exclude'] = $commissionInfo->construction_price_tax_exclude;
            $insert['construction_price_tax_include'] = $commissionInfo->construction_price_tax_include;
            $insert['commission_id'] = $commissionInfo->commission_infos_id;
            $insert['agree_flag'] = "0";
            $insert['receive_datetime'] = $commissionInfo->receive_datetime;

            if ($commissionInfo->order_fee_unit == "0") {
                $this->setDataOrderFeeUnitIs0($commissionInfo, $insert);
            } elseif ($commissionInfo->order_fee_unit == "1") {
                $this->setDataOrderFeeUnitIs1($commissionInfo, $insert);
            } elseif ($commissionInfo->order_fee_unit == null) {
                $this->setDataOrderFeeUnitIsNull($commissionInfo, $insert);
            }
            $insert['fee_target_price'] = $commissionInfo->fee_target_price;
            $insert['fee_billing_date'] = $commissionInfo->fee_billing_date;
            $insert['created_user_id'] = self::$user;
            $insert['modified_user_id'] = self::$user;
            $insert['created'] = date('Y-m-d H:i:s');
            $insert['modified'] = date('Y-m-d H:i:s');
            $this->progDemandInfoRepo->updateProgDemandInfo($insert);
            if (!empty($lockFlag)) {
                DB::select("SELECT set_lock_status(" . $commissionInfo->commission_infos_id . ", 1);");
            }
        }
    }
}
