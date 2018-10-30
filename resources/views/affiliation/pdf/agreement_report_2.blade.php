<!DOCTYPE html>

    <div>
        <h4 style="font-size: 13px; margin-top: 70px; margin-bottom: 0;">■ 成約ベース（業務ジャンル{{$category}}）</h4>
        <h4 style="font-size: 13px; margin-top: 10px; margin-bottom: 0;">■ 基本対応エリアジャンルと手数料</h4>
        <div style="margin-top: 5px;">
            <table style="border-collapse: collapse; width: 100%;">
                <tbody>
                    <tr style="background-color: #e1dfdf">
                        <th style="width: 15%; height: 20px; border: 2px solid black; font-size: 13px; padding-top: 5px; padding-bottom: 5px;">ジャンル</th>
                        <th style="width: 15%; height: 20px; border: 2px solid black; font-size: 13px; padding-top: 5px; padding-bottom: 5px;">カテゴリ</th>
                        <th style="width: 10%; height: 20px; border: 2px solid black; font-size: 13px; padding-top: 5px; padding-bottom: 5px;">専門性</th>
                        <th style="width: 10%; height: 20px; border: 2px solid black; font-size: 13px; padding-top: 5px; padding-bottom: 5px;">手数料</th>
                        <th style="width: 10%; height: 20px; border: 2px solid black; font-size: 13px; padding-top: 5px; padding-bottom: 5px;">単位</th>
                        <th style="width: 40%; height: 20px; border: 2px solid black; font-size: 13px; padding-top: 5px; padding-bottom: 5px;">備考</th>
                    </tr>
                    @foreach($corpCategoryList as $genre)
                        @if(($category == 'A' && $genre['corp_commission_type'] != 2) || ($category == 'B' && $genre['corp_commission_type'] == 2))
                            <tr>
                                <td style="width: 15%; height: 30px; border: 2px solid black; font-size: 13px; text-align: left; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">{{ $genre['genre_name'] }}</td>
                                <td style="width: 15%; height: 30px; border: 2px solid black; font-size: 13px; text-align: left; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">{{ $genre['category_name'] }}</td>
                                <td style="width: 10%; height: 30px; border: 2px solid black; font-size: 13px; text-align: center; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">{{ $genre['select_list'] }}</td>
                                @if($category == 'A' && $genre['corp_commission_type'] != 2)
                                    <td style="width: 15%; height: 30px; border: 2px solid black; font-size: 13px; text-align: center; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">
                                        {{ $genre['order_fee'] }}
                                    </td>
                                    <td style="width: 15%; height: 30px; border: 2px solid black; font-size: 13px; text-align: center; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">
                                        @if(isset($genre['order_fee_unit']))
                                            @if($genre['order_fee_unit'] == 1)
                                                {{__('agreement_system.percent')}}
                                            @else
                                                {{__('agreement_system.yen')}}
                                            @endif
                                        @endif
                                    </td>
                                @else
                                    @if(isset($genre['introduce_fee']))
                                        <td style="width: 15%; height: 30px; border: 2px solid black; font-size: 13px; text-align: center; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">
                                            {{ $genre['introduce_fee'] }}
                                        </td>
                                        <td style="width: 15%; height: 30px; border: 2px solid black; font-size: 13px; text-align: center; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">
                                            {{__('agreement_system.yen')}}
                                        </td>
                                    @else
                                        <td style="width: 15%; height: 30px; border: 2px solid black; font-size: 13px; text-align: center; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">
                                            {{ $genre['order_fee'] }}
                                        </td>
                                        <td style="width: 15%; height: 30px; border: 2px solid black; font-size: 13px; text-align: center; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">
                                            @if(isset($genre['order_fee_unit']))
                                                @if($genre['order_fee_unit'] == 1)
                                                    {{__('agreement_system.percent')}}
                                                @else
                                                    {{__('agreement_system.yen')}}
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                @endif
                                <td style="width: 15%; height: 30px; border: 2px solid black; font-size: 13px; text-align: left; padding-top: 3px; padding-bottom: 3px; padding-left: 10px; padding-right: 10px;">
                                    {{ $genre['m_corp_categories_temp_note'] }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</html>