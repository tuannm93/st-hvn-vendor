@php
    $content['work_status'] = __('cyzen_notifications.message_status_call_done');
@endphp
<p>{{empty($content['kameiten_name']) ? '' : $content['kameiten_name']}}様</p>

<p>いつもお世話になっております。</p>
<p>シェアリングテクノロジー株式会社です。</p>

<p>お客様へ架電されたことを確認致しました。</p>
<p>引き続きご対応いただきますようお願い致します。</p>
<br/>
@include('email_template.cyzen_mail_content')
<br/>
<p>よろしくお願い致します。</p>
