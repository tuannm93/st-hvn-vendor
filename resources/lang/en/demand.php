<?php
return [
    "errorCommissionSelect" => "Category, prefecture, city, town, village are missing or input is incorrect.",
    "copy_label" => "Copy",
    "cross_label" => "Cross sell",
    "notEmptyIntroduceInfo" => "It is an introduction base case. Since the status of the case is [in progress], please select the referral.",
   "modifiedNotCheck" => "It has been updated by another user. Please start over from the beginning.",
    "reBiddingSelect" => 'When changing the flow of bidding flow to bid selection, please change the check of "re-bidding" and the status of the case to "unselected".
',
    'commitFlgLimit' => 'It exceeds the maximum number that can be confirmed',
    'reBiddingNotSelect' => 'When changing from manual selection to bidding ceremony selection, please change the check on "re-bidding" and the case situation to "not selected".',
    'unSelected' => 'In case of automatic selection, please change the case situation to "unselected".',
    'dataInconsistency' => "Data dataInconsistency",
    'start_time_error' => 'Update is completed. Contact date and time is past.',
    'auction_ng_update' => 'Update is completed. Because we could not find a franchise targeted for bidding, please do manual selection.',
    'msg_success' => 'Update is completed.',
    'adjust_visit_time' => 'Please select the time adjustment and enter the adjustment time for the visit date when entering the period.',
    'send_error' => 'メール/FAX送信に失敗しました。',
    'validation_error' => [
        'check_follow_date' => '過去日付を入力されています。',
        'check_demand_status' => '取次先選定又は選定紹介先選定が未選定です。【未選定】に変更してください。',
        'check_demand_status_advance' => '案件状況が【進行中】のため、取次先企業の「確定」又は、「取次前失注」が必須です。',
        'check_demand_status_introduce' => '加盟店が選定されているので、【未選定】を選択できません。',
        'check_demand_status_introduce_email' => 'メール/FAX情報に情報が入っているので【加盟店確認中】を選択できません。',
        'check_demand_status_selection_type' => '入札式選定を行なう場合、案件状況を未選定にしてください。',
        'check_demand_status_confirm' => '確定チェックが入っている場合は、案件状況に【進行中】か【失注】を選択して下さい。',
        'check_order_fail_reason' => '案件状況が【失注】のため、必須入力です。',
        'not_empty' => '必須入力です。',
        'check_customer_tel' => '半角数字又は「非通知」で入力してください。',
        'check_tel1' => '案件状況が【失注】以外のため、必須入力です。',
        'check_contents_string' => '×,→,←,↓,↑,⇒は使用しないでください。',
        'past_date_time' => '過去の日時が入力されています。',
        'past_date_time_2' => '開始日時より過去の日時が入力されています。',
        'check_require_to' => '開始日時を入力した時は終了日時も必須入力です。',
        'check_required_from' => '終了日時を入力した時は開始日時も必須入力です。',
        'check_pet_tombstone_demand_not_empty' => 'ジャンルが【ペット葬儀】のため、必須入力です。',
        'check_jbr_not_empty' => 'サイト名がJBR生活救急車のため、必須入力です。',
        'check_jbr_category_not_empty' => 'JBRの作業内容が害虫駆除のため、必須入力です。',
        'check_order_fail_date' => '案件状況が【受注】受注確定(施工まだ) 又は 【受注】施工完了 又は【失注】のため、必須入力です。',
        'check_selection_system' => '指定のジャンル又は都道府県では入札式選定が設定されていません。',
        'check_do_auction' => '入札を行なう場合、選定方式は入札式選定を選択してください。',
        'check_staff_in_corp' => '１加盟店はスタッフを一人しか選定できません',
        'check_date_format' => '日付形式(yyyy/mm/dd)で入力してください。',
        'max_error' => ':max文字以下にしてください。',
        'numeric_error' => '半角数字で入力してください。',
        'email_error' => '半角、Eメール形式で入力してください。',
        'date_error' => '日時形式で入力してください。',
        'corresponding_contens' => '対応履歴を登録する際には、対応者・対応内容は必須入力です。',
        'max_20' => '20文字以内で設定してください。',
        'max_1000' => '1000文字以内で設定してください。',
        'mail_not_select' => 'メール/FAX情報に情報が入ってないので【進行中】を選択できません。',
        'the_file_field_is_required' => 'アップロード対象ファイルが選択されていません。',
        'the_file_must_be_a_file_of_type_jpeg, bmp, png, pdf' => 'アップロードするファイルの形式が不正です。',
        'wrong_format_file' => 'アップロードするファイルのサイズが大きすぎます。',
        'the_file_may_not_be_greater_than_20_MB' => 'アップロードするファイルのサイズが大きすぎます。',
        'send_error' => 'メール/FAX送信に失敗しました。',
    ]
];
