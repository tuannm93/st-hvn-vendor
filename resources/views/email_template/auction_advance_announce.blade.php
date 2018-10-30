{{ $data['corp_name'] }}様<br><br>
いつもお世話になっております。<br>
シェアリングテクノロジー株式会社です。<br>
訪問案件の訪問日時が間近になりました。<br><br>
案件内容を再度ご確認頂き、<br>
ご対応いただきますようお願い致します。<br>
&#60;&#60;入札案件一覧URL&#62;&#62;<br>
<a href="{{ env('APP_URL', config('datacustom.DOMAIN_NAME')) }}/rits/auction">{{ env('APP_URL', config('datacustom.DOMAIN_NAME')) }}/rits/auction</a><br><br>
&#60;&#60;案件詳細&#62;&#62;<br>
◇案件番号：{{ $data['demand_id'] }}<br>
◇問合せ元サイト名：{{ $data['site_name'] }}<br>
◇ジャンル：{{ $data['genre_name'] }}<br>
◇名前：{{ $data['customer_name'] }}<br>
◇住所：{{ $data['address'] }}<br>
◇建物種別：{{ $data['construction_class'] }}<br>
◇電話番号１：{{ $data['tel1'] }}<br>
◇電話番号２：{{ $data['tel2'] }}<br>
◇相談内容：<br>
{!! $data['contents'] !!}<br><br><br>
ご不明点が御座いましたらお気軽にお電話下さい。<br>
その際は案件番号を受付オペレーターに伝えていただければ<br>
話がスムーズです。<br><br>
弊社コールセンター：050-5893-9634<br><br>
よろしくお願い致します。<br><br><br>
------------------------------------------------------------<br><br>
シェアリングテクノロジー株式会社<br><br>
〒450-6319<br>
愛知県名古屋市中村区名駅1-1-1<br>
JPタワー名古屋19F<br>
TEL：050-5893-9634　　FAX:052-526-1600<br><br>
------------------------------------------------------------<br>
