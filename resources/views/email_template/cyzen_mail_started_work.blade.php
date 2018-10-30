@php
    $content['work_status'] = __('cyzen_notifications.message_status_started_work');
@endphp
<p>{{empty($content['kameiten_name']) ? '' : $content['kameiten_name']}}様</p>

<p>いつもお世話になっております。</p>
<p>シェアリングテクノロジー株式会社です。</p>

<p>下記案件の作業が開始されたことをご報告致します。</p>
<br/>
@include('email_template.cyzen_mail_content')
<br/>
<p>よろしくお願い致します。</p>
