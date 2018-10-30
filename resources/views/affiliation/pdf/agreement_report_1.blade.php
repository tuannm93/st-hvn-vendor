<!DOCTYPE html>

    <htmlpageheader name="firstpage" style="display:none">
        <h4 style="text-align: center; font-size: 18px;">契約書(契約約款)</h4>
        <div style="margin-bottom: 70px;">
            <div style="width: 50%; float: left;">
                <strong style="float: left; font-size: 13px;">企業ID：{{ $corpId }}</strong>
            </div>
            <div style="width: 50%; float: right; text-align: right;">
                <strong style="float: right; font-size: 13px;">ページ：{{$pageNumber}} </strong>
            </div>
        </div>
        <div style="clear: both;"></div>
    </htmlpageheader>
    <htmlpageheader name="otherpages" style="display:none">
        <div style="margin-bottom: 70px;">
            <div style="width: 50%; float: left;">
                <strong style="font-size: 13px;">企業ID：{{ $corpId }}</strong>
            </div>
            <div style="width: 50%; float: right; text-align: right;">
                <strong style="font-size: 13px;">ページ：{{$pageNumber}} </strong>
            </div>
        </div>
        <div style="clear: both;"></div>
    </htmlpageheader>

    <sethtmlpageheader name="firstpage" value="on" show-this-page="1" />
    <sethtmlpageheader name="otherpages" value="on" />

    <div>
        <div style="padding-top: 40px;">
            <div style="float: left; width: 50%;">
                <div style="text-align: left; font-size: 13px;">住所：{{ $address }}</div>
                <div style="text-align: left; font-size: 13px;">企業名：{{ $officialCorpName }}</div>
                <div style="text-align: left; font-size: 13px;">責任者：{{ $responsibility }}</div>
            </div>
            <div style="float: right; width: 50%;">
                <div style="text-align: right; font-size: 13px;">愛知県名古屋市中区丸の内3-23-20KHF 桜通ビルディング2F</div>
                <div style="text-align: right; font-size: 13px;">シェアリングテクノロジー株式会社</div>
                <div style="text-align: right; font-size: 13px;">代表取締役　引字 圭祐</div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div style="margin-top: 20px; margin-bottom: 20px;">
            <div style="width: 40%; float: right;">
                <table style="border-collapse: collapse;">
                    <tbody>
                        <tr style="background-color: #e1dfdf">
                            <th style="width: 150px; height: 20px; border: 2px solid black; font-size: 12px;">審査</th>
                            <th style="width: 150px; height: 20px; border: 2px solid black; font-size: 12px;">承認・契約締結日</th>
                        </tr>
                        <tr>
                            <td style="width: 150px; height: 30px; border: 2px solid black; font-size: 12px;">{{ $hanszxyaDate }}</td>
                            <td style="width: 150px; height: 30px; border: 2px solid black; font-size: 12px;">{{ $completeDate }}</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; height: 30px; border: 2px solid black; font-size: 12px;">{{ $hanszxyaAppName }}</td>
                            <td style="width: 150px; height: 30px; border: 2px solid black; font-size: 12px;">{{ $completeName }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div>
            @foreach($arrayProvision as $provision)
                <p>
                    {{$provision}}
                </p>
            @endforeach
        </div>
    </div>

</html>
