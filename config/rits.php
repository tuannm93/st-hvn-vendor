<?php

return [
    'prefecture_div' => [
        '1' => 'hokkaido',
        '2' => 'aomori',
        '3' => 'iwate',
        '4' => 'miyagi',
        '5' => 'akita',
        '6' => 'yamagata',
        '7' => 'hukushima',
        '8' => 'ibaragi',
        '9' => 'tochigi',
        '10' => 'gunma',
        '11' => 'saitama',
        '12' => 'chiba',
        '13' => 'tokyo',
        '14' => 'kanagawa',
        '15' => 'niigata',
        '16' => 'toyama',
        '17' => 'ishikawa',
        '18' => 'hukui',
        '19' => 'yamanashi',
        '20' => 'nagano',
        '21' => 'gihu',
        '22' => 'sizuoka',
        '23' => 'aichi',
        '24' => 'mie',
        '25' => 'shiga',
        '26' => 'kyoto',
        '27' => 'osaka',
        '28' => 'hyogo',
        '29' => 'nara',
        '30' => 'wakayama',
        '31' => 'tottori',
        '32' => 'shimane',
        '33' => 'okayama',
        '34' => 'hiroshima',
        '35' => 'yamaguchi',
        '36' => 'tokushima',
        '37' => 'kagawa',
        '38' => 'ehime',
        '39' => 'kouchi',
        '40' => 'hukuoka',
        '41' => 'saga',
        '42' => 'nagasaki',
        '43' => 'kumamoto',
        '44' => 'ooita',
        '45' => 'miyazaki',
        '46' => 'kagoshima',
        '47' => 'okinawa',
        '99' => 'humei'
    ],
    'auction_delivery_status' => [
        '1' => 'delivery',
        '2' => 'ng',
        '3' => 'deny'
    ],
    'credit_check_exclusion_site_id' => [
        0 => 889,
        1 => 1312,
        2 => 1313,
        3 => 1314,
    ],
    'coordination_method' => [
        1 => 'mail_fax',
        2 => 'mail',
        3 => 'fax',
        4 => 'form',
        5 => 'other',
        6 => 'mail_app',
        7 => 'mail_fax_app'
    ],
    'CREDIT_NORMAL' => 'normal',
    'CREDIT_WARNING' => 'warning',
    'CREDIT_DANGER' => 'danger',
    'CREDIT_EXCLUSION_SITE_ID' => 889,
    'WARNING_CREDIT_RATE' => 0.8,
    'bill_status' => [
        '1' => 'not_issue',
        '2' => 'issue',
        '3' => 'payment'
    ],
    'mobile_limit_time' => 15,
    'selection_type' => [
        '0' => 'ManualSelection',
        '2' => 'AuctionSelection',
        '3' => 'AutomaticAuctionSelection',
        '4' => 'AutoSelection'
    ],
    'list_limit' => 100,
    'mobile_limit' => 10,
    'agreement_grace_date' => '2016/4/20',
    'priority' => [
        '1' => 'asap',
        '2' => 'immediately',
        '3' => 'normal'
    ],
    'auction_masking' => [
        '1' => 'without',
        '2' => 'tel_exclusion',
        '3' => 'all_exclusion'
    ],
    'agreement_alert_mail_setting' => [
        'from_address' => env('ST_MAIL_FROM', 'mailback@rits-c.jp'),
        'title' => '【重要】　取次開始のご案内'
    ],
    'notice_info_important_ids' => explode(",", env('NOTICE_INFO_IMPORTANT_IDS')),
    'FORECAST_CATEGORY_ID' => [
        490, 519, 551
    ],
    'report_list_limit' => 200,
    'construction_status' => [
        '1' => 'progression',
        '2' => 'received',
        '3' => 'construction',
        '4' => 'order_fail',
        '5' => 'introduction'
    ],
    'demand_status' => [
        '1'=>'no_selection',
        '2'=>'no_guest',
        '3'=>'agency_before',
        '4'=>'telephone_already',
        '5'=>'information_sent',
        '6'=>'order_fail',
        '7'=>'demand_development',
        '8'=>'need_hearing'
    ],
    'affiliation_csv_list_kind' => [
        'listed' => '上場',
        'unlisted' => '非上場'
    ],
    'affiliation_csv_corp_kind' => [
        'Corp' => '法人',
        'Person' => '個人'
    ],
    'affiliation_csv_commission_accept_flag' => [
        '0' => '未契約',
        '1' => '契約完了',
        '2' => '契約未更新',
        '3' => '未更新STOP'
    ],
    'PM_COMMISSION_STATUS' => [
        1=>'進行中',
        2 => '受注',
        3 => '施工完了',
        4 => '失注'
    ],
    'commission_order_fail_reason' => '取次失注理由',
    'PM_DIFF_LIST' => [1=> '--', 2 => '変更はない', 3 => '変更がある'],
    'agress_list' => [0 => 'なし', 1 => 'あり'],
    'CATE_CONTACT_TYPE' => '進捗表_送付方法',
    'CATE_NOT_REPLY' => '進捗表_未返信理由',
    'CATE_PROGRESS' => '進捗表状況',
    'PM_CAUTION1' => '以上の進捗状況と過去施工状況の報告内容に虚偽がないものとする。なお本報告に虚偽の内容があった場合、シェアリングテクノロジー株式会社は',
    'PM_CAUTION2' => 'へ紹介した過去全案件の調査を行い、その結果虚偽報告が有り、且つ悪質であると判断した場合、',
    'PM_CAUTION3' => 'は違約金をシェアリングテクノロジー株式会社に支払うものとする。※残念なことですが、過去に加盟店様からの報告に虚偽が判明した経緯があったため、大変恐縮ではございますが、月次で最終確認をさせていただいて おります。ご理解の程、何卒よろしくお願いいたします。',
    'ITEM_NOTICE_INFO' => '企業取次形態',
    'fail_update_commission' => 'ERROR: 【進捗システム】取次情報 更新失敗',
    'construct_status' => '■取次状況：',
    'change' => '■変更：',
    'jp_arrow' => '⇒',
    'construction_complete_date' => '■施工完了日：',
    'none' => 'なし',
    'constructin_amount' => '■施工金額：',
    'missing_date' => '■失注日：',
    'demand_type_list' => [
        1 =>'復活案件',
        2 =>'追加施工',
        3 =>'その他'
    ],
    // When cross_site_flg = 0 with the cross-cell automatic interruption stop function, automatic interruption stops
    'arrSiteId' => ['647', '953'],
    'coordination_method_category' => '顧客情報連絡手段',
    'lost_corp_name' => '【SF用】取次前失注用(質問のみ等)',
    'bcc_address' => env('BCC_MAIL_TO', 'orange@rits-c.jp'),
    'demand_max_commission' => 30,
];
