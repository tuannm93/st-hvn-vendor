<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
    {{$data["corpName"]}}様<br><br>
    いつもお世話になっております。<br>
    シェアリングテクノロジー株式会社です。<br>
    貴社が登録されているエリアとジャンルにマッチングした<br>
    新しい案件が入りました。<br><br>
    今直ぐに内容をご確認頂き、是非ご対応をお願い致します。<br><br>
    &lt;&lt;入札案件一覧URL&gt;&gt;<br>
        <a href="{{$data["auctionLink"]}}">{{$data["auctionLink"]}}</a><br><br>
        &lt;&lt;案件詳細&gt;&gt;<br>
            ◇案件番号：{{$data["demandInfoId"]}}<br>
            ◇問合せ元サイト名：{{$data["siteName"]}}<br>
            ◇ジャンル：{{$data["genreName"]}}<br>
            ◇名前：{{$data["demandInfoCustomer"]}}<br>
            ◇住所：{{$data["address"]}}<br>
            ◇建物種別：{{$data["constructionClass"]}}<br>
            ◇電話番号１：{{$data["tel1"]}}<br>
            ◇電話番号２：{{$data["tel2"]}}<br>
            ◇メールアドレス：{{$data["customerMailAddress"]}}<br>
            ◇相談内容：<br>
            {{$data["demandInfoContent"]}}<br><br>
            &lt;&lt;入札期限日時&gt;&gt;<br>
                {{$data["auctionDeadlineTime"]}}<br><br>
                ※エリアとジャンルの登録が誤っている場合、<br>
                　希望しない案件のご案内が配信されますので<br>
                　Moverの登録情報変更画面から修正をお願い致します。<br><br><br>
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
                <img src="{{$data["image"]}}">
    </body>
</html>