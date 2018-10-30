@php
    $content['work_status'] = __('cyzen_notifications.message_status_create_demand');
@endphp
<p>{{empty($content['kameiten_name']) ? '' : $content['kameiten_name']}}様</p>
<br/>
<p>いつもお世話になっております。</p>
<p>シェアリングテクノロジー株式会社です。</p>

<p>下記案件についてお客様のご対応をお願いいたします。</p>

<p>案件詳細は下記URLからご確認ください。</p>
<p><a href="{{empty($content['commission_id']) ? '' : route('commission.detail', $content['commission_id'])}}">
        {{empty($content['commission_id']) ? '' : route('commission.detail', $content['commission_id'])}}</a></p>
<br/>

@include('email_template.cyzen_mail_content')

<br/>
<p>ご不明点が御座いましたらお気軽にお電話下さい。</p>
<p>その際は案件番号を受付オペレーターに伝えていただければ話がスムーズです。</p>
<br/>
<p>弊社コールセンター：{{env('SHARINGTECH_PHONE_OFFICER', '050-5893-9634')}}</p>
<br/>
<p>よろしくお願い致します。</p>
