<?php

namespace App\Repositories\Eloquent;

use App\Models\MItem;
use App\Repositories\MItemRepositoryInterface;

class MItemRepository extends SingleKeyModelRepository implements MItemRepositoryInterface
{
    const LIST_MOBILE_PHONE_TYPES = '携帯電話タイプ';
    const CUSTOMER_INFORMATION_CONTACT = '携帯電話タイプ';
    const HOLIDAYS = '休業日';
    const LONG_HOLIDAYS = '長期休業日';
    const PROPOSAL_STATUS = '案件状況';
    const CUSTOMER_INFORMATION_CONTACT_METHOD = '顧客情報連絡手段';
    const CATEGORY = '請求状況';
    const ITEM_CATEGORY = '取次状況';
    const ITEM_CATEGORY_BILL_LIST = '取次形態';
    const COMMISSION_STATUS = '取次状況';
    const CORP_STATUS = '開拓状況';
    const CONTRACT_STATUS = '開拓取次状況';
    const DEV_REACTION = '開拓時の反応';
    const STOP_CATEGORY = '取次STOPカテゴリ';
    const FREE_ESTIMATE = '無料見積対応';
    const PORTAL_SITE = 'ポータルサイト掲載';
    const REG_SEND_METHOD = '登録書発送方法';
    const COORDINATION_METHOD = '顧客情報連絡手段';
    const PROG_SEND_METHOD = '進捗表送付方法';
    const BILL_SEND_METHOD = '請求書送付方法';
    const COLLECTION_METHOD = '代金徴収方法';
    const LIABILITY_INSURANCE = '賠償責任保険';
    const WASTE_COLLECT_OATH = '不用品回収誓約書';
    const CLAIM_COUNT = '顧客クレーム回数';
    const JBR_STATUS = 'JBR対応状況';
    const PAYMENT_SITE = '支払サイト';
    const PARTIAL_APPROVAL_OR_REJECTION = '一部承認or却下';
    const EXPLOITATION_CATEGORY = '開拓区分';
    const BILLING_STATUS = '請求状況';
    const CATE_CONTACT_TYPE = '進捗表_送付方法';
    const CATE_NOT_REPLY = '進捗表_未返信理由';
    const CATE_PROGRESS = '進捗表状況';
    const REASON_FOR_LOSING_CONSENT = '取次失注理由';
    const APPLICATION = '申請';
    const IRREGULAR_REASON = 'イレギュラー理由';
    const REASON_FOR_LOST_NOTE = '案件失注理由';
    const PET_TOMBSTONE_DEMAND= 'ペット墓石案内';
    const SMS_DEMAND = 'SMS案内';
    const JBR_WORK_CONTENTS = '[JBR様]作業内容';
    const JBR_CATEGORY = '[JBR様]カテゴリ';
    const JBR_ESTIMATE_STATUS = '[JBR様]見積書状況';
    const JBR_RECEIPT_STATUS = '[JBR様]領収書状況';
    const ADVERTISEMENT_TYPE_SITE_SITUATION = '出稿型サイト状況';
    const LOSS_OF_DEVELOPMENT = '開拓失注理由';
    const PROJECT_SPECIAL_MEASURES = '案件特別施策';
    const ACCEPTANCE_STATUS = '受付ステータス';
    const CORPORATE_BROKERAGE_FORM = '企業取次形態';
    const TELEPHONE_SUPPORT_STATUS = '電話対応状況';
    const VISIT_SUPPORT_STATUS = '訪問対応状況';
    const ORDER_SUPPORT_STATUS = '受注対応状況';
    const COMMISSION_TEL_SUPPORTS_ORDER_FAIL_REASON = '電話対応失注理由';
    const COMMISSION_VISIT_SUPPORTS_ORDER_FAIL_REASON = '訪問対応失注理由';
    const COMMISSION_ORDER_SUPPORTS_ORDER_FAIL_REASON = '受注対応失注理由';
    const BUILDING_TYPE = '建物種別';
    const AUTO_CALL_CLASSIFICATION = 'オートコール区分';
    const UNKNOWN = '不明';
    const EXCEL_URL = 'views/excel_template/';
    const AT_YOUR_COMPANY = '御社にて';
    const EXCEL_DESCRIPTION = '月末までに施工いただきましたお客様の弊社手数料請求書を';
    const DEPOSIT = 'ご入金は';
    const EXCEL2 = '月25日までに下記口座へお振込みください。';
    const EXCEL3 = '尚、下記記載の御社企業コードをご依頼人名に必ず入力願います。';
    const EXCEL4 = '御社企業コード：';
    const BILLING_INFORMATION = '請求情報_';
    const REFORM_UP_CELL_IC = 'リフォームアップセルIC';
    const LOSS_SUPPORT = '失注';
    const CANCEL_SUPPORT = 'キャンセル';

    /**
     * @var MItem
     */
    protected $model;

    /**
     * MItemRepository constructor.
     *
     * @param MItem $model
     */
    public function __construct(MItem $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MItem|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MItem();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }


    /**
     * @param \App\Models\Base $data
     * @return \App\Models\Base|bool|void
     */
    public function save($data)
    {
    }

    /**
     * get list by item_category and enabled_start or enabled_end
     *
     * @param  string $category
     * @return array
     */
    public function getListByCategoryItem($category)
    {
        $results = $this->model->select('item_id AS id', 'item_name AS category_name')
            ->where("item_category", $category)
            ->where("enabled_start", "<=", date("Y/m/d"))
            ->where(
                function ($query) {
                    $query->where("enabled_end", ">=", date("Y/m/d"))
                        ->orWhere("enabled_end", null);
                }
            )->orderBy("sort_order", "asc")->get();
        return !empty($results) ? $results->toArray() : [];
    }

    /**
     * get first list by item_id and item_category
     *
     * @param  string  $category
     * @param  integer $itemId
     * @return mixed
     */
    public function getFirstOldList($category, $itemId = null)
    {
        return $this->model->where(
            [
            ['item_category', $category],
            ['item_id', $itemId]
            ]
        )->orderBy('sort_order', 'asc')->first();
    }

    /**
     * find item by category and item id
     *
     * @param  string  $category
     * @param  integer $itemId
     * @return object
     */
    public function findItemByCategoryAndItemId($category, $itemId)
    {
        return $this->model
            ->select('item_name')
            ->where('item_category', $category)
            ->where('item_id', $itemId)
            ->orderBy('sort_order', 'desc')
            ->first();
    }

    /**
     * get list by query function
     *
     * @param  string $query
     * @return object
     */
    public function scopeGetList($query)
    {
        return $query->where('id', '>', 100);
    }

    /**
     * get list genres function
     *
     * @param  string  $category
     * @param  integer $itemId
     * @return array
     */
    public function getList($category, $itemId = null)
    {
        $list = MItem::where(
            [
            ['enabled_start', '<=', date('Y/m/d')],
            ['item_category', '=', $category],
            ]
        )->orwhere(
            [
                ['enabled_end', '>=', date('Y/m/d')],
                ['enabled_end', '=', null],
                ]
        )->orderBy('item_id', 'asc')->get()->toarray();
        $results = [];
        foreach ($list as $val) {
            $results[$val['item_id']] = $val['item_name'];
        }
        return $results;
    }

    /**
     * get list genres item name function
     *
     * @param  string  $category
     * @param  integer $value
     * @return string
     */
    public function getListText($category, $value)
    {
        $results = MItem::where('item_category', '=', $category)
            ->where('item_id', '=', $value)
            ->orderBy('item_id', 'asc')->get()->toarray();
        if (isset($results[0]['item_name'])) {
            return $results[0]['item_name'];
        } else {
            return "";
        }
    }

    /**
     * @param integer $mCorpId
     * @return mixed
     */
    public function listHoliday($mCorpId)
    {
        $rawQuery = 'SELECT ARRAY_TO_STRING(ARRAY( SELECT item_name FROM m_items INNER JOIN m_corp_subs';
        $rawQuery .= ' ON  m_corp_subs.item_category = m_items.item_category';
        $rawQuery .= ' AND m_corp_subs.item_id = m_items.item_id';
        $rawQuery .= ' WHERE m_corp_subs.item_category = \'' . self::HOLIDAYS . '\'';
        $rawQuery .= ' AND m_corp_subs.corp_id = ' . $mCorpId;
        $rawQuery .= ' ORDER BY m_items.sort_order ASC ),\'｜\')';
        return DB::select($rawQuery);
    }

    /**
     * @param string $itemCategory
     * @param string $date
     * @return array|mixed
     */
    public function getMItemList($itemCategory, $date)
    {
        return $this->model->select('item_id', 'item_name')
            ->where('item_category', $itemCategory)
            ->where('enabled_start', '<=', $date)
            ->where(
                function ($or) use ($date) {
                    $or->where('enabled_end', '>=', $date)->orWhere('enabled_end', null);
                }
            )->orderBy('sort_order', 'ASC')->pluck('item_name', 'item_id')->toArray();
    }

    /**
     * @param string $itemCategory
     * @return array|mixed
     */
    public function getMItemListByItemCategory($itemCategory)
    {
        $date = date('Y/m/d');
        return ['' => '--なし--'] + $this->getMItemList($itemCategory, $date);
    }

    /**
     * @param array $categories
     * @return array|mixed
     */
    public function getByCategory($categories)
    {
        $query = $this->model->from('m_items AS MItem')
            ->whereIn('MItem.item_category', $categories)
            ->orderBy('MItem.sort_order', 'ASC')
            ->select(
                'MItem.item_id AS MItem__item_id',
                'MItem.item_name AS MItem__item_name',
                'MItem.item_category AS MItem__item_category'
            );

        $results = $query->get()->toArray();

        return $results;
    }

    /**
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getByLongHoliday()
    {
        return $this->model->where("item_category", self::LONG_HOLIDAYS)
            ->where("enabled_start", "<=", date("Y/m/d"))
            ->where(
                function ($query) {
                    $query->where("enabled_end", ">=", date("Y/m/d"))
                        ->orWhere("enabled_end", null);
                }
            )->orderBy("sort_order", "asc")->get();
    }

    /**
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function deleteByLongHoliday()
    {
        return $this->model->where("item_category", self::LONG_HOLIDAYS)->delete();
    }
}
