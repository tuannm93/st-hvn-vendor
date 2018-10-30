<html>
<body>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Total</th>
        <th>ID</th>
        <th>Name</th>
        <th>Total</th>
        <th>{{ date('Y/m/d') }}</th>
    </tr>
    <tr>
        <td  data-format="0,0.00"> {{ 2324.433434 }}</td>
        <td>{{ $mcorpData['official_corp_name'] }}</td>
        <td>{{ $mcorpData['past_bill_price'] }}</td>
        <td  data-format="0,0.00"> {{ 2324.433434 }}</td>
        <td>{{ $mcorpData['official_corp_name'] }}</td>
        <td>{{ $mcorpData['past_bill_price'] }}</td>
        <td></td>
    </tr>
</table>
</body>
</html>