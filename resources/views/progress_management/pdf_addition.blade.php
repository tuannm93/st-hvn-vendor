<div style="width: 1122px;">
    <div style="font-weight: bold">
        <div style="font-size: 18px"><span>【進捗確認表】</span></div>
        <div style="font-size: 13px">
            <p>{{ $progCorp->mCorp->official_corp_name }} 御中</p>
            <p>※送付しました書面のうち、送付状以外を記入後ご返送いただきますよう宜しくお願い致します。※太枠内をご記入ください。</p>
        </div>
        <table style="border: none; border-collapse: collapse; font-weight: bold; font-size: 13px">
            <tbody>
                <tr>
                    <td colspan="12" style="border: 1px solid; text-align: center; font-size: 12px">案件数：{{ $progCorp->progDemandInfo->count() }}件</td>
                </tr>
                <tr>
                    <td colspan="7" style="border: 1px solid; text-align: center; font-size: 12px">弊社管理状況【以下には記載不要】</td>
                    <td style="border-left: 2px solid #666666; border-right: 2px solid #666666; border-top: 2px solid #666666; border-bottom: 1px solid; text-align: center; font-size: 12px">必須項目</td>
                    <td colspan="4" style="border: 1px solid; border-top: 2px solid #666666; border-right: 2px solid #666666; text-align: center; font-size: 12px">変更はないですか？との問いに「変更がある」と答えた場合記入</td>
                </tr>
                <tr>
                    <td style="width: 33px; border: 1px solid; text-align: center; font-size: 11px">日付</td>
                    <td style="width: 57px; border: 1px solid; text-align: center; font-size: 11px">作業内容</td>
                    <td style="width: 74px; border: 1px solid; text-align: center; font-size: 11px">手数料率<br>(手数料金額)</td>
                    <td style="width: 100px; border: 1px solid; text-align: center; font-size: 11px">案件番号<br>お客様名</td>
                    <td style="width: 60px; border: 1px solid; text-align: center; font-size: 11px">施工完了日<br>失注日</td>
                    <td style="width: 90px; border: 1px solid; text-align: center; font-size: 11px">施工金額(税抜)<br>手数料対象金額</td>
                    <td style="width: 58px;border: 1px solid; text-align: center; font-size: 11px">状況</td>
                    <td style="border-left: 2px solid #666666; border-right: 2px solid #666666; border-bottom: 1px solid; text-align: center; font-size: 11px">弊社管理状況に<br>変更はないですか？<br><span style="font-size: 8px">(どちらかに○を付けてください)</span></td>
                    <td style="border: 1px solid; text-align: center; font-size: 11px">現在の状況</td>
                    <td style="border: 1px solid; text-align: center; font-size: 11px">施工完了日<br>失注日</td>
                    <td style="border: 1px solid; text-align: center; font-size: 11px">施工金額(税抜)<br>(完了時のみ記入)</td>
                    <td style="border: 1px solid; border-right: 2px solid #666666; width: 132px; text-align: center; font-size: 11px">備考欄<br><span style="font-size: 8px">（失注の場合は理由の記載を必ずお願い致します。）</span></td>
                </tr>
                @php
                    $cnt = 1;
                    $bottomBorder = '';
                    foreach($progCorp->progDemandInfo as $key => $demanInfo) {
                        $csvDataGetDate = explode(" " ,$demanInfo->receive_datetime);
                        if ($progCorp->progDemandInfo->count() == $cnt) {
                            $bottomBorder = "border-bottom: 2px solid #666666";
                        }

                        $tesuryoritsu = '';
                        if (!empty($demanInfo->fee)) {
                            $tesuryoritsu = $demanInfo->fee."円";
                        }
                        elseif (!empty($demanInfo->fee_rate)) {
                            $tesuryoritsu = $demanInfo->fee_rate."%" ;
                        }
                @endphp
                <tr>
                    <td style="width: 33px; border: 1px solid; text-align: center">
                        {{ !empty($demanInfo->receive_datetime) ? date('Y/m/d', strtotime($demanInfo->receive_datetime)) : '' }}
                    </td>
                    <td style="width: 57px; border: 1px solid; text-align: center;">
                        {{ $demanInfo->category_name }}
                    </td>
                    <td style="width: 74px; border: 1px solid; text-align: right;">{{ $tesuryoritsu }}</td>
                    <td style="width: 100px; border: 1px solid">
                        {{ $demanInfo->demand_id }}<br />{{ $demanInfo->customer_name }}
                    </td>
                    <td style="width: 60px; border: 1px solid; text-align: center">{{ $demanInfo->complete_date }}</td>
                    <td style="width: 90px; border: 1px solid; text-align: center">{{ $demanInfo->construction_price_tax_exclude }}<br>
                        {{ $demanInfo->fee_target_price }}
                    </td>
                    <td style="width: 58px; border: 1px solid; text-align: center">
                        {{
                            isset($commissStatusList[$demanInfo->commission_status]) ? $commissStatusList[$demanInfo->commission_status] : ''
                        }}
                    </td>
                    <td style="border: 1px solid; {{ $bottomBorder }}; border-left: 2px solid #666666; border-right: 2px solid #666666; text-align: center">
                        <img src="{{ public_path('images/progress_management/henko_text.png') }}" style="width:120px"/>
                    </td>
                    <td style="border: 1px solid; {{ $bottomBorder }}; text-align: center">
                        <img src="{{ public_path('images/progress_management/jyokyo_text.png') }}" style="width:120px"/>
                    </td>
                    <td style="border: 1px solid; width: 64px; {{ $bottomBorder }}"></td>
                    <td style="border: 1px solid; width: 88px; {{ $bottomBorder }}"></td>
                    <td style="border: 1px solid; {{ $bottomBorder }}; border-right: 2px solid #666666; width: 132px"></td>
                </tr>
                @php
                    $pDate1 = $demanInfo->receive_datetime;
                    $pDate2 = date('Ymd h:i:s');;
                    $timeStamp1 = strtotime($pDate1);
                    $timeStamp2 = strtotime($pDate2);
                    $secondDiff = abs($timeStamp2 - $timeStamp1);
                    $DayDiff = round($secondDiff / (60 * 60 * 24));
                    if ($DayDiff >= 90 && $DayDiff <= 120 && $demanInfo->csv_data_condition == "1") {
                        @endphp
                        <tr>
                            <td colspan="11">↑ 長期進行中の案件ですので、現在の状況を備考欄にご記載ください。
                                <input type="hidden" name="longDate" class="longDate" value="1">
                            </td>
                        </tr>
                        @php
                    }
                    $cnt++;
                }
                @endphp
            </tbody>
        </table>
        <div style="font-size: 16px"><span>【追加施工】　※必須項目</span></div>
        <div style="font-size: 12px">
            <p>弊社から{{ $progCorp->mCorp->official_corp_name }}様への取次案件に関して、追加施工が発生した案件はございますでしょうか。</p>
            <img src="{{ public_path('images/progress_management/add_img4.png') }}" style="width:400px;" />
        </div>
        <table style="border: none; border-collapse: collapse; font-weight: bold; font-size: 13px">
            <tbody>
                <tr style="font-size: 13px">
                    <td style="width: 110px; border: 1px solid; text-align: center">受注No.</td>
                    <td style="width: 155px; border: 1px solid; text-align: center">お客様名</td>
                    <td style="width: 110px; border: 1px solid; text-align: center">作業内容</td>
                    <td style="width: 155px; border: 1px solid; text-align: center">施工完了日</td>
                    <td style="width: 110px; border: 1px solid; text-align: center">施工金額(税抜)</td>
                    <td style="width: 415px; border: 1px solid; text-align: center">備考</td>
                </tr>
                @for($i = 0; $i <= 2 ; $i++)
                    <tr>
                        <td style="height: 22px; border: 1px solid"></td>
                        <td style="height: 22px; border: 1px solid"></td>
                        <td style="height: 22px; border: 1px solid"></td>
                        <td style="height: 22px; border: 1px solid"></td>
                        <td style="height: 22px; border: 1px solid"></td>
                        <td style="height: 22px; border: 1px solid"></td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <div style="font-size: 13px">
            <div style="float: left; width: 68%; padding-right: 5px">
                <p>{{ $progCorp->mCorp->official_corp_name }} 御中</p>
                <p>{{ $pmCaution1 . $progCorp->mCorp->official_corp_name . $pmCaution2 . $progCorp->mCorp->official_corp_name . $pmCaution3 }}</p>
                <div style="font-size: 14px">
                    <p>平成　　　　　年　　　　　月　　　　　日</p>
                    <p style="border-bottom: 2px solid">{{ $progCorp->mCorp->official_corp_name }}様 責任者様サイン</p>
                </div>
                <div>
                    <p>【お知らせ】</p>
                    <span>{!! $downText !!}</span>
                </div>
            </div>
            <div style="float: right; width: 30%; padding-top: 13px">
                <p style="font-weight: bold; text-align: center; font-size: 30px; margin-top: 45px;">返送期日：当月{{ $editDate }}日</p>
                <div style="text-align: right; font-size: 11px; padding-top: 66px">
                    <p>シェアリングテクノロジー株式会社</p>
                    <p>〒450-6319</p>
                    <p>愛知県名古屋市中村区名駅1-1-1</p>
                    <p>JPタワー名古屋19F</p>
                </div>
            </div>
            <div style="clear: both"></div>
        </div>
        <div style="font-weight: bold; margin-top: 10px; font-size: 12px">
            <p>※大変お手数おかけしますが、ご記入の上　052-526-1600　宛にFAXにて返送願います。</p>
            <p>尚、この書面に関するお問い合わせは0120-949-092までお願い致します。</p>
        </div>
    </div>
</div>