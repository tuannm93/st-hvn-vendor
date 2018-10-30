<?php

return [
    'demand_type_update' => [
        '1' => '復活案件（過去に失注になったお客様より再度問い合わせがあった場合）',
        '2' => '追加施工（問い合わせのあったジャンルと同時に、別ジャンルの受注をした場合）',
        '3' => 'その他',
    ],
    'demand_type' => [
        '1' => '復活案件',
        '2' => '追加施工',
        '3' => 'その他',
    ],
    'state_list' => [
        '01' => '北海道',
        '02' => '青森県',
        '03' => '岩手県',
        '04' => '宮城県',
        '05' => '秋田県',
        '06' => '山形県',
        '07' => '福島県',
        '08' => '茨城県',
        '09' => '栃木県',
        '10' => '群馬県',
        '11' => '埼玉県',
        '12' => '千葉県',
        '13' => '東京都',
        '14' => '神奈川県',
        '15' => '新潟県',
        '16' => '富山県',
        '17' => '石川県',
        '18' => '福井県',
        '19' => '山梨県',
        '20' => '長野県',
        '21' => '岐阜県',
        '22' => '静岡県',
        '23' => '愛知県',
        '24' => '三重県',
        '25' => '滋賀県',
        '26' => '京都府',
        '27' => '大阪府',
        '28' => '兵庫県',
        '29' => '奈良県',
        '30' => '和歌山県',
        '31' => '鳥取県',
        '32' => '島根県',
        '33' => '岡山県',
        '34' => '広島県',
        '35' => '山口県',
        '36' => '徳島県',
        '37' => '香川県',
        '38' => '愛媛県',
        '39' => '高知県',
        '40' => '福岡県',
        '41' => '佐賀県',
        '42' => '長崎県',
        '43' => '熊本県',
        '44' => '大分県',
        '45' => '宮崎県',
        '46' => '鹿児島県',
        '47' => '沖縄県'
    ],
    'selection_type' => [
        '0' => 'ManualSelection',
        '3' => 'AuctionSelection'
    ],
    'FullDateTimeFormat' => 'Y/m/d H:i:s',
    'DateTimeFormat' => 'Y/m/d H:i',
    'DateFormat' => 'Y/m/d',
    'refusal' => [
        'support_limit' => 0,
        'deal_already' => 1,
        'support_already' => 2,
        'complete' => 3,
        'support_past_time' => 17,
        'update_fail'=> 99
    ],
    'datetime_format_jp' => 'Y年m月d日 H時i分',
    'affiliation_status' => [
        '0' => '未加盟',
        '1' => '加盟',
        '-1' => '解約'
    ],
    'holiday' => '休業日',
    'development_reaction' => '開拓時の反応',
    'stop_category' => '取次STOPカテゴリ',
    'defaultOption' => ['' => '--なし--'],
    'demand_attached_file_path' => 'rits-files',
    'PM_ADMIN_MAIL_TO' => env('PM_ADMIN_MAIL_TO', 'progress@rits-c.jp'),
    'PM_ADMIN_MAIL_FROM' => env('PM_ADMIN_MAIL_FROM', 'progress@rits-c.jp'),
    'PM_FAX_MAIL_TO' => env('PM_FAX_MAIL_TO', 'sendfax@mail01.lcloud.jp'),

    'PM_SUBJECT_PREFIX' =>'',
    'PAGINATION' => 25,
    'PM_RELEASE' => true,
    'EXTERNAL_DATE_BODER' => 7,
    'START_WEEK' => 0,
    'END_WEEK' => 6,
    'PROG_IMPORT_LOG' => 'prog_import',
    'ajax' => [
        'TEL_STATUS_NOT_CORRESPOND' => 1,
        'TEL_STATUS_ABSENCE' => 2,
        'TEL_STATUS_CONSIDERATION_WITH_SERVICE' => 3,
        'TEL_STATUS_CONSIDERATION_ADJUSTMENT' => 4,
        'TEL_STATUS_EXPECTED_FIX' => 5,
        'TEL_STATUS_FIX' => 6,
        'TEL_STATUS_LOST' => 7,
        'TEL_STATUS_OTHER' => 8,
        'TEL_STATUS_CONSIDERATION_AFFILIATION' => 9,
        'TEL_STATUS_CONSIDERATION_SUPPORT' => 10,

        'TEL_LOST_REASON_NOT_CONTACT' => 1,
        'TEL_LOST_REASON_OWN_SOLUTION' => 2,
        'TEL_LOST_REASON_ONLY_QUESTION_WITH_FAIR' => 3,
        'TEL_LOST_REASON_ONLY_QUESTION_WITHOUT_FAIR' => 4,
        'TEL_LOST_REASON_DELAY' => 5,
        'TEL_LOST_REASON_NOT_ADJUSTMENT' => 6,
        'TEL_LOST_REASON_NEGATIVE' => 7,
        'TEL_LOST_REASON_OTHER' => 8,
        'TEL_LOST_REASON_MEETING_ESTIMATE' => 9,

        'COMMISSION_STATUS_IN_PROGRESS' => 1,
        'COMMISSION_STATUS_RECEIPT_ORDER' => 2,
        'COMMISSION_STATUS_COMPLETION' => 3,
        'COMMISSION_STATUS_LOST_ORDER' => 4,
        'COMMISSION_LOST_REASON_OWN_SOLUTION' => 1,
        'COMMISSION_LOST_REASON_NO_CONTACT' => 2,
        'COMMISSION_LOST_REASON_WITHOUT_SCHEDULE' => 3,
        'COMMISSION_LOST_REASON_MEETING_ESTIMATE' => 4,
        'COMMISSION_LOST_REASON_LACK_BUDGET' => 5,
        'COMMISSION_LOST_REASON_DELAY' => 6,

        'ORDER_STATUS_NOT_CORRESPOND' => 1,
        'ORDER_STATUS_FIX' => 2,
        'ORDER_STATUS_FIX_AND_MORE' => 3,
        'ORDER_STATUS_CANCEL' => 4,
        'ORDER_STATUS_OTHER' => 5,
        'ORDER_LOST_REASON_OWN_SOLUTION' => 1,
        'ORDER_LOST_REASON_MEETING_ESTIMATE' => 2,
        'ORDER_LOST_REASON_DELAY' => 3,
        'ORDER_LOST_REASON_OTHER' => 4,

        'PARAM_NO_VALUE' => '_99999999_',
        'FORMAT_DATETIME' => 'Y_-_-m_-_-d H-_-_i',
        'FORMAT_DATE' => 'Y_-_-m_-_-d',
        'SEARCH' => ['_-_-', '-_-_'],
        'REPLACE' => ['/', ':'],

        'VISIT_STATUS_NOT_CORRESPOND'  => 1,
        'VISIT_STATUS_ABSENCE'  => 2,
        'VISIT_STATUS_CONSIDERATION_WITH_SERVICE'  => 3,
        'VISIT_STATUS_CONSIDERATION_ADJUSTMENT'  => 4,
        'VISIT_STATUS_EXPECTED_FIX'  => 5,
        'VISIT_STATUS_FIX'  => 6,
        'VISIT_STATUS_LOST'  => 7,
        'VISIT_STATUS_OTHER'  => 8,
        'VISIT_STATUS_CONSIDERATION_AFFILIATION'  => 9,
        'VISIT_STATUS_CONSIDERATION_SUPPORT'  => 10,
        'VISIT_LOST_REASON_OWN_SOLUTION'  => 1,
        'VISIT_LOST_REASON_LACK_BUDGET'  => 2,
        'VISIT_LOST_REASON_MEETING_ESTIMATE'  => 3,
        'VISIT_LOST_REASON_DELAY'  => 4,
        'VISIT_LOST_REASON_NOT_ADJUSTMENT'  => 5,
        'VISIT_LOST_REASON_NEGATIVE'  => 6,
        'VISIT_LOST_REASON_OTHER'  => 7
    ],
    'MITEM' => [
        'LIST_MOBILE_PHONE_TYPES' => '携帯電話タイプ',
        'CUSTOMER_INFORMATION_CONTACT' => '携帯電話タイプ',
        'HOLIDAYS' => '休業日',
        'LONG_HOLIDAYS' => '長期休業日',
        'PROPOSAL_STATUS' => '案件状況',
        'CUSTOMER_INFORMATION_CONTACT_METHOD' => '顧客情報連絡手段',
        'CATEGORY' => '請求状況',
        'ITEM_CATEGORY' => '取次状況',
        'CORP_STATUS' => '開拓状況',
        'CONTRACT_STATUS' => '開拓取次状況',
        'DEV_REACTION' => '開拓時の反応',
        'STOP_CATEGORY' => '取次STOPカテゴリ',
        'FREE_ESTIMATE' => '無料見積対応',
        'PORTAL_SITE' => 'ポータルサイト掲載',
        'REG_SEND_METHOD' => '登録書発送方法',
        'COORDINATION_METHOD' => '顧客情報連絡手段',
        'PROG_SEND_METHOD' => '進捗表送付方法',
        'BILL_SEND_METHOD' => '請求書送付方法',
        'COLLECTION_METHOD' => '代金徴収方法',
        'LIABILITY_INSURANCE' => '賠償責任保険',
        'WASTE_COLLECT_OATH' => '不用品回収誓約書',
        'CLAIM_COUNT' => '顧客クレーム回数',
        'JBR_STATUS' => 'JBR対応状況',
        'PAYMENT_SITE' => '支払サイト',
        'EXPLOITATION_CATEGORY' => '開拓区分',
        'BILLING_STATUS' => '請求状況',
        'CATE_CONTACT_TYPE' => '進捗表_送付方法',
        'CATE_NOT_REPLY' => '進捗表_未返信理由',
        'CATE_PROGRESS' => '進捗表状況',
        'REASON_FOR_LOSING_CONSENT' => '取次失注理由',
        'APPLICATION' => '申請',
        'IRREGULAR_REASON' => 'イレギュラー理由',
    ],
    'CREDIT_NORMAL' => 'normal',
    'CREDIT_WARNING' => 'warning',
    'CREDIT_DANGER' => 'danger',
    'DOWNLOAD_CSV' => 'CSVダウンロード',
    'FAX_SUBJECT' => '000020003',
    'M_ITEM' => [
        'TELEPHONE_SUPPORT_STATUS' => '電話対応状況',
        'VISIT_SUPPORT_STATUS' => '訪問対応状況',
        'ORDER_SUPPORT_STATUS' => '受注対応状況',
        'BUILDING_TYPE' => '建物種別',
        'PROPOSAL_STATUS' => '案件状況',
        'REASON_FOR_LOST_NOTE' => '案件失注理由',
        'JBR_WORK_CONTENTS' => '[JBR様]作業内容',
        'JBR_CATEGORY' => '[JBR様]カテゴリ',
        'JBR_ESTIMATE_STATUS' => '[JBR様]見積書状況',
        'JBR_RECEIPT_STATUS' => '[JBR様]領収書状況',
        'PET_TOMBSTONE_DEMAND' => 'ペット墓石案内',
        'SMS_DEMAND' => 'SMS案内',
        'PROJECT_SPECIAL_MEASURES' => '案件特別施策',
        'ACCEPTANCE_STATUS' => '受付ステータス',
        'IRREGULAR_REASON' => 'イレギュラー理由',
        'ITEM_CATEGORY' => '取次状況',
        'REASON_FOR_LOSING_CONSENT' => '取次失注理由',
        'LOSS_SUPPORT' => '失注',
        'CANCEL_SUPPORT' => 'キャンセル',
        'REFORM_UP_CELL_IC' => 'リフォームアップセルIC',
        'COMMISSION_TEL_SUPPORTS_ORDER_FAIL_REASON' => '電話対応失注理由',
        'COMMISSION_VISIT_SUPPORTS_ORDER_FAIL_REASON' => '訪問対応失注理由',
        'COMMISSION_ORDER_SUPPORTS_ORDER_FAIL_REASON' => '受注対応失注理由'
    ],
    'max_integer' => '2147483647',
    'diffFlag' => '追加施工案件'
];
