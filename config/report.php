<?php
return [
    'support_kind_label' => ['order'=>'受注', 'tel' => '電話', 'visit' => '訪問'],
    'commissionSortOption' => [
        0 => '',
        'demand_follow_date' => '後追い日',
        'detect_contact_desired_time' => '連絡希望日時',
        'visit_time' => '訪問日時',
        'corp_name' => '取次先１',
        'commission_rank' => 'ジャンルランク',
        'site_id' => 'サイト名',
        'customer_name' => 'お客様名',
        'address' => '都道府県', //Sort by CommandInfo.address1
        'contactable' => '連絡可能時間',
        'holiday' => '休業日',
        'first_commission' => '初取次チェック',
        'user_name' => '最終履歴更新者',
        'modified' => '履歴更新時間',
        'auction' => '入札落ち',
    ],

    'sortOptions' => [
        'demand_follow_date' => '後追い日',
        'detect_contact_desired_time' => '連絡希望日時',
        'visit_time' => '訪問日時',
        'corp_name' => '取次先１',
        'commission_rank' => 'ジャンルランク',
        'site_id' => 'サイト名',
        'customer_name' => 'お客様名',
        'address1' => '都道府県',
        'contactable' => '連絡可能時間',
        'holiday' => '休業日',
        'first_commission' => '初取次チェック',
        'user_name' => '最終履歴更新者',
        'modified' => '履歴更新時間',
        'auction' => '入札落ち',
        'cross_sell_implement' => 'クロスセル獲得'
    ],

    'filterOptions' => [1 => 'なし', 2 => 'あり'],

    'contactRequest' => [1 => '当日', 2 => '明日', 3 => 'それ以降'],

    'genreRank' => [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6'],

    'dayOfTheWeek' => [
        '2' => '月曜日',
        '3' => '火曜日',
        '4' => '水曜日',
        '5' => '木曜日',
        '6' => '金曜日',
        '7' => '土曜日',
        '8' => '日曜日',
    ],

    'historyUpdate' => [1 => '1時間以内', 2 => '1時間以降']
];
