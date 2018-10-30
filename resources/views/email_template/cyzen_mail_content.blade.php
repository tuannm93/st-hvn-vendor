<p>◇スケジュール日時　　：　{{empty($content['schedule']) ? '' : $content['schedule']}}</p>
<p>◇作業場所　　　　　　：　{{empty($content['location']) ? '' : $content['location']}}</p>
<p>◇案件番号　　　　　　：　{{empty($content['demand_id']) ? '' : $content['demand_id']}}</p>
<p>◇取次番号　　　　　　：　{{empty($content['commission_id']) ? '' : $content['commission_id']}}</p>
<p>◇対応状況　　　　　　：　{{empty($content['work_status']) ? '' : $content['work_status']}}</p>
<p>◇対象サイト　　　　　：　<a href="{{empty($content['site_name']) ? '' : $content['site_name']}}" target="_blank">
        {{empty($content['site_name']) ? '' : $content['site_name']}}</a></p>
<p>◇ジャンル　　　　　　：　{{empty($content['genre_name']) ? '' : $content['genre_name']}}</p>
<p>◇カテゴリ　　　　　　：　{{empty($content['category_name']) ? '' : $content['category_name']}}</p>
<p>◇お客様名　　　　　　：　{{empty($content['customer_name']) ? '' : $content['customer_name']}}</p>
<p>◇お客様電話番号　　　：　<a href="callto:{{empty($content['customer_phone']) ? '' : $content['customer_phone']}}">
        {{empty($content['customer_phone']) ? '' : $content['customer_phone']}}</a></p>
@if($content['isCallCenter'])
    <p>◇加盟店番号　　　　　：　<a href="{{empty($content['kameiten_id']) ?
        '' : route('affiliation.detail.edit', $content['kameiten_id'])}}" target="_blank">
            {{empty($content['kameiten_id']) ? '' : $content['kameiten_id']}}</a></p>
    <p>◇加盟店名　　　　　　：　{{empty($content['kameiten_name']) ? '' : $content['kameiten_name']}}</p>
    <p>◇加盟店電話番号　　　：　{{empty($content['kameiten_phone']) ? '' : $content['kameiten_phone']}}</p>
    <p>◇加盟店メール　　　　：　{{empty($content['kameiten_mail']) ? '' : $content['kameiten_mail']}}</p>
@endif
<p>◇作業担当者名　　　　： {{empty($content['staff_name']) ? '' : $content['staff_name']}}</p>
<p>◇作業担当者電話番号　：　<a href="callto:{{empty($content['staff_phone']) ? '' : $content['staff_phone']}}">
        {{empty($content['staff_phone']) ? '' : $content['staff_phone']}}</a></p>
