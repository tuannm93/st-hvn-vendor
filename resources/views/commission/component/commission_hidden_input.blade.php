@php
    $corp_id = $val['corp_id'];
    if(empty($val['id_staff'])){
        $staff_id =  random_int(1, 10000);
    }else{
        $staff_id = $val['id_staff'];
    };
    $corp_name = $val['corp_name'];
    $address = getDivTextJP('prefecture_div', $val['address1']).$val['address2'].$val['address3'];
    $commission_dial = str_replace("'", "\'", $val['commission_dial']);
    $coordination_method = $val['coordination_method'];
    $coordination_method_dis = $val['item_name'];
    $mailaddress_pc = $val['mailaddress_pc'];
    $fax = $val['fax'];
    if($val['contactable_support24hour']){
        $contactable_time = '24時間';
    }else {
        if(!empty($val['contactable_time_from']) || !empty($val['contactable_time_to']))
            $contactable_time = $val['contactable_time_from'].' - '.$val['contactable_time_to'];
        else
            $contactable_time = '';
    }
    $attention = str_replace(array("\r\n","\r","\n"), "<br>", $val['attention']);
    $holiday = $val['holiday'];
    $order_fee = $val['corp_commission_type'] != 2 ? $val['order_fee'] : $val['introduce_fee'];
    $m_corp_category_note = $val['note_mcorp_cate'];
    $commission_unit_price_rank = $val['commission_unit_price_rank_1'];
    if(empty($data['target_check'])){
        $commission_unit_price         = $val['commission_unit_price_category'];
        $commission_unit_price_display = yenFormat2($val['commission_unit_price_category']);
    }

    else{
        $commission_unit_price         = $val['commission_unit_price'];
        $commission_unit_price_display = yenFormat2($val['commission_unit_price']);
    }

    $order_fee_val = !empty($order_fee) ? $order_fee : $data['category_default_fee'];
    $order_fee_unit = $val['corp_commission_type'] != 2 ? $val['order_fee_unit'] : 0;
    $order_fee_unit = !is_null($order_fee_unit) ? $order_fee_unit : $data['category_default_fee_unit'];
    $corp_commission_type = !is_null($val['corp_commission_type']) ? $val['corp_commission_type'] : $data['category_default_commission_type'];
    $corp_commission_type_disp = $corp_commission_type != 2 ? '成約' : '紹介';
    $numberVacation = count($vacation);
    $long_vacations_items = [];

    foreach ($vacation as $oneVacation){
         $long_vacations_items[] = $oneVacation;
    }

    $long_vacations = [];
    for($i=1; $i<=$numberVacation; $i++){
        $key = 'status_' . sprintf('%02d',$i);
        $long_vacations[] = isset($val[$key]) ? $val[$key]: '';
    }
    $long_vacation_note = str_replace("\n", '', nl2br($val['note_new_year']));

    $one_staff = [
        'id_staff' => $val['id_staff'] ?? '',
        'name_staff' => $val['name_staff'] ?? '',
        'phone_staff' => $val['staff_phone'] ?? '',
        'status_name' => $val['status_name'] ?? '',
        'status_id' => $val['status_id'] ?? ''
    ];

    $list_staff = [];
    $list_staff[] = $one_staff;

$result = [];
$result['corp_id'] = $corp_id;
$result['corp_name'] = $corp_name;
$result['address'] = $address;
$result['commission_dial'] = $commission_dial;
$result['coordination_method'] = $coordination_method;
$result['coordination_method_dis'] = $coordination_method_dis;
$result['mailaddress_pc'] = $mailaddress_pc;
$result['fax'] = $fax;
$result['contactable_time'] = $contactable_time;
$result['attention'] = $attention;
$result['holiday'] = $holiday;
$result['order_fee'] = $order_fee;
$result['m_corp_category_note'] = $m_corp_category_note;
$result['commission_unit_price_rank'] = $commission_unit_price_rank;
$result['commission_unit_price'] = $commission_unit_price;
$result['commission_unit_price_display'] = $commission_unit_price_display;
$result['order_fee_val'] = $order_fee_val;
$result['order_fee_unit'] = $order_fee_unit;
$result['corp_commission_type'] = $corp_commission_type;
$result['corp_commission_type_disp'] = $corp_commission_type_disp;
$result['long_vacations_items'] = $long_vacations_items;
$result['long_vacations'] = $long_vacations;
$result['long_vacation_note'] = $long_vacation_note;
$result['list_staff'] = $list_staff;
$result['category_default_fee_unit'] = $data['category_default_fee_unit'];

@endphp
<input type="hidden" class="input_hidden_value" id="select_{{ $staff_id }}" value="{{ json_encode($result) }}">
<input type="hidden" name="select" value="{{ $staff_id }}">
