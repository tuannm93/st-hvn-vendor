<div style="width: 1122px;">
    <div style="font-weight: bold">
        <p style="font-size: 16px">【送付状】</p>
        <div style="font-size: 11px">
            <span>{!! $upText !!}</span>
        </div>
        <div style="font-weight: bold">
            <div style="text-align: center">
                <p style="font-size: 16px">【記入例】</p>
            </div>
            <div style="font-size: 14px">
                <span>&#x203B;送付しました書面のうち、送付状以外を記入後ご返送いただきますよう宜しくお願い致します。&#x203B;太枠内をご記入ください。</span>
            </div>
            <table style="text-align: center; border: none; border-collapse: collapse; font-weight: bold">
                <tbody style="font-size: 13px">
                    <tr>
                        <td colspan="7" style="border: 1px solid">弊社管理状況【以下には記載不要】</td>
                        <td style="border: 1px solid">必須項目</td>
                        <td colspan="4" style="border: 1px solid">変更はないですか？との問いに「変更がある」と答えた場合記入</td>
                    </tr>
                    <tr>
                        <td style="min-width: 33px; border: 1px solid">日付</td>
                        <td style="min-width: 57px; border: 1px solid">作業内容</td>
                        <td style="min-width: 81px; border: 1px solid">手数料率<br>(手数料金額)</td>
                        <td style="border: 1px solid">案件番号<br>お客様名</td>
                        <td style="width: 60px; border: 1px solid">施工完了日<br>失注日</td>
                        <td style="width: 90px; border: 1px solid">施工金額(税抜)<br>手数料対象金額</td>
                        <td style="width: 58px; border: 1px solid">状況</td>
                        <td style="border: 1px solid">弊社管理状況に<br>変更はないですか？<br>
                            <span class="fs-8">(どちらかに○を付けてください)</span>
                        </td>
                        <td style="border: 1px solid">現在の状況</td>
                        <td style="border: 1px solid">施工完了日<br>失注日</td>
                        <td style="min-width: 110px; border: 1px solid">施工金額(税抜)<br>(完了時のみ記入)</td>
                        <td style="min-width: 120px; border: 1px solid">備考欄<br>
                            <span class="fs-8">（失注の場合は理由の記載を必ずお願い致します。）</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid">例１</td>
                        <td style="border: 1px solid">遺品整理</td>
                        <td style="border: 1px solid; text-align: right;">30％</td>
                        <td style="border: 1px solid">200000<br/>山田太郎</td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid">進行中</td>
                        <td style="border: 1px solid">
                            <img src="{{ public_path('images/progress_management/henko_ng.png') }}" style="width:135px;"/>
                        </td>
                        <td style="border: 1px solid">
                            <img src="{{ public_path('images/progress_management/jyokyo_text.png') }}" style="width:135px;"/>
                        </td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid"></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid">例２</td>
                        <td style="border: 1px solid">害虫駆除</td>
                        <td style="border: 1px solid; text-align: right;">30％</td>
                        <td style="border: 1px solid">200001<br/>山田次郎</td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid">失注</td>
                        <td style="border: 1px solid">
                            <img src="{{ public_path('images/progress_management/henko_ng.png') }}" style="width:135px;"/>
                        </td>
                        <td style="border: 1px solid">
                            <img src="{{ public_path('images/progress_management/jyokyo_text.png') }}" style="width:135px;"/>
                        </td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid"></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid">例３</td>
                        <td style="border: 1px solid">遺品整理</td>
                        <td style="border: 1px solid; text-align: right;">30％</td>
                        <td style="border: 1px solid">200003<br/>山田三郎</td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid">進行中</td>
                        <td style="border: 1px solid">
                            <img src="{{ public_path('images/progress_management/henko_ok.png') }}" style="width:135px;"/>
                        </td>
                        <td style="border: 1px solid">
                            <img src="{{ public_path('images/progress_management/jyokyo_03.png') }}" style="width:135px;"/>
                        </td>
                        <td style="border: 1px solid">2014/7/18</td>
                        <td style="border: 1px solid"></td>
                        <td style="border: 1px solid">お客さま自己解決</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid">例４</td>
                        <td style="border: 1px solid">害虫駆除</td>
                        <td style="border: 1px solid; text-align: right;">30％</td>
                        <td style="border: 1px solid">200003<br/>山田四郎</td>
                        <td style="border: 1px solid">2014/7/21</td>
                        <td style="border: 1px solid">8700<br>8700</td>
                        <td style="border: 1px solid">施工完了</td>
                        <td style="border: 1px solid">
                            <img src="{{ public_path('images/progress_management/henko_ok.png') }}" style="width:135px;"/>
                        </td>
                        <td style="border: 1px solid">
                            <img src="{{ public_path('images/progress_management/jyokyo_02.png') }}" style="width:135px;"/>
                        </td>
                        <td style="border: 1px solid">2014/7/21</td>
                        <td style="border: 1px solid">15000</td>
                        <td style="border: 1px solid">金額訂正</td>
                    </tr>
                </tbody>
            </table>
            <div style="font-size: 16px">
                <span>【追加施工】&#x203B;必須項目</span>
            </div>
            <div style="font-size: 12px">
                <span> 弊社から{{ $progCorp->mCorp->official_corp_name }}様への取次案件に関して、追加施工が発生した案件はございますでしょうか。</span>
            </div>
            <div>
                <img src="{{ public_path('images/progress_management/add_img3.png') }}" style="width:400px;" />
            </div>
            <table style="border: none; border-collapse: collapse; font-weight: bold">
                <tbody style="font-size: 13px">
                    <tr>
                        <td style="width: 105px; border: 2px solid; text-align: center">受注No.</td>
                        <td style="width: 155px; border: 2px solid; text-align: center">お客様名</td>
                        <td style="width: 105px; border: 2px solid; text-align: center">作業内容</td>
                        <td style="width: 155px; border: 2px solid; text-align: center">施工完了日</td>
                        <td style="width: 105px; border: 2px solid; text-align: center">施工金額(税抜)</td>
                        <td style="width: 421px; border: 2px solid; text-align: center">備考</td>
                    </tr>
                    <tr>
                        <td style="border: 2px solid; text-align: center">199999</td>
                        <td style="border: 2px solid">山田五郎</td>
                        <td style="border: 2px solid">草刈り</td>
                        <td style="border: 2px solid; text-align: center">7月15日</td>
                        <td style="border: 2px solid; text-align: right">20000</td>
                        <td style="border: 2px solid">草刈り20坪</td>
                    </tr>
                    <tr>
                        <td style="height: 22px; border: 2px solid"></td>
                        <td style="height: 22px; border: 2px solid"></td>
                        <td style="height: 22px; border: 2px solid"></td>
                        <td style="height: 22px; border: 2px solid"></td>
                        <td style="height: 22px; border: 2px solid"></td>
                        <td style="height: 22px; border: 2px solid"></td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 10px; font-size: 13px">
                <div style="float: left; width: 68%; padding-right: 5px">
                    <p>{{ $progCorp->mCorp->official_corp_name }} 御中</p>
                    <p>{{ $pmCaution1.$progCorp->mCorp->official_corp_name . $pmCaution2.$progCorp->mCorp->official_corp_name . $pmCaution3 }}</p>
                    <p style="margin-bottom: 5px; margin-top: 5px; font-size: 14px">平成　　○○　年　　△△　月　　××　日</p>
                    <div style="border-bottom: 2px solid; font-size: 14px; padding-bottom: 10px">
                        <div style="float: left; width: 70%">{{ $progCorp->mCorp->official_corp_name }}様 責任者様サイン</div>
                        <div style="float: left; width: 30%">【例】田中　一郎</div>
                    </div>
                    <div>
                        <p>【お知らせ】</p>
                        <span>{!! $downText !!}</span>
                    </div>
                </div>
                <div style="float: right; width: 30%; font-weight: bold; margin-top: 10px;">
                    <p style="text-align: center; font-size: 30px; margin-top: 45px;">返送期日：当月{{ $editDate }}日</p>
                    <p style="text-align: center; font-size: 18px">※次ページ以降全て御返送下さい</p>
                    <div style="text-align: right; font-size: 11px">
                        <p>シェアリングテクノロジー株式会社</p>
                        <p>〒450-6319</p>
                        <p>愛知県名古屋市中村区名駅1-1-1</p>
                        <p>JPタワー名古屋19F</p>
                    </div>
                </div>
                <div style="clear: both"></div>
            </div>
            <div style="font-weight: bold"><span>※大変お手数おかけしますが、ご記入の上　052-526-1600　宛にFAXにて返送願います。尚、この書面に関するお問い合わせは0120-949-092までお願い致します。</span></div>
        </div>
    </div>
</div>