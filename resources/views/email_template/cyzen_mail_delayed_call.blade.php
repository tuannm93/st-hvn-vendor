@php
    $content['work_status'] = __('cyzen_notifications.message_status_call_delay');
@endphp
<p>{{empty($content['kameiten_name']) ? '' : $content['kameiten_name']}}様</p>

<p>いつもお世話になっております。</p>
<p>シェアリングテクノロジー株式会社です。</p>

<p>お客様への架電時間を過ぎましたが、架電の確認がとれておりません。</p>
<p>作業担当者の方へ状況のご確認をお願い致します。</p>
<br/>
@include('email_template.cyzen_mail_content')
<br/>
<p>よろしくお願い致します。</p>
