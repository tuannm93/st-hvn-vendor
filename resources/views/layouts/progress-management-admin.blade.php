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
@php
$class = 'body-wrapper-default';
if (Route::current()->getName() == 'bill.moneyCorrespond') {
    $class = 'body-wrapper-custom-1';
} else if (Route::current()->getName() == 'auction.proposal') {
    $class = 'body-wrapper-custom-2';
}
@endphp
<body>
    <div id="app" class="header-progress-management-admin">

        {{--Header component--}}
        @include('partials.header-progress-management-admin')

        {{--Main content component--}}
        <div class="{{ $class }}">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        {{--Footer component--}}
        @include('partials.footer-progress-management-admin')
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ mix('js/lib/jquery-ui.min.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.ui.datepicker-ja.min.js') }}"></script>
    <script src="{{ mix('js/lib/jquery-ui-sliderAccess.js') }}"></script>
    <script src="{{ mix('js/lib/jquery-ui-timepicker-addon.js') }}"></script>
    <script src="{{ mix('js/pages/helpers/datetime.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    </script>
    @yield('script')
</body>
</html>
