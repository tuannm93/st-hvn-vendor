@php
    $content['work_status'] = __('cyzen_notifications.message_status_end_work_delay');
@endphp
<p>{{empty($content['kameiten_name']) ? '' : $content['kameiten_name']}}様</p>

<p>いつもお世話になっております。</p>
<p>シェアリングテクノロジー株式会社です。</p>

<p>作業終了時間が過ぎましたが、終了の確認が取れていないことをご報告致します。</p>
<br/>
@include('email_template.cyzen_mail_content')
<br/>
<p>よろしくお願い致します。</p>
