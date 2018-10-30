<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @yield('style')
</head>
<body>
<div id="app">
    <form action="{{ route('commissionselect.display') }}" name="form1" accept-charset="utf-8" id="displayForm" method="post">
        {{ csrf_field() }}
        <input type="submit" value="Btn test" id="btn-open">
        <input type="hidden" name="data[no]" value="-1">
        <input type="hidden" name="data[site_id]" value="1379">
        <input type="hidden" name="data[category_id]" value="500">
        <input type="hidden" name="data[postcode]" value="4500003">
        <input type="hidden" name="data[address1]" value="23">
        <input type="hidden" name="data[address2]" value="名古屋市中村区">
        <input type="hidden" name="data[corp_name]" value="">
        <input type="hidden" name="data[commition_info_count]" value="1">
        <input type="hidden" name="data[exclude_corp_id]" value="1,,,,,,,,,,,,,,">
        <input type="hidden" name="data[genre_id]">

    </form>


</div>

<!-- Scripts -->

<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/lib/jquery-ui.min.js') }}"></script>

<script type="application/javascript">

</script>


</body>
</html>
