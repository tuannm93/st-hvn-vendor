<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{asset('assets/img/favicon_m.ico')}}" type="image/x-icon" rel="icon">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('style')
</head>
@php
    $logined = Auth::user();
    $class = 'body-wrapper-default';
    if (Route::current()->getName() == 'bill.moneyCorrespond') {
        $class = 'body-wrapper-custom-1';
    } else if (Route::current()->getName() == 'auction.proposal') {
        $class = 'body-wrapper-custom-2';
    }
    $classContent = 'container';
    if (Route::current()->getName() == 'get.progress.management.admin_demand_detail') {
        $classContent = 'px-3';
    }
@endphp
<body>
<div class="progress-block">
    <div class="progress"></div>
</div>
<div id="top">
    {{--Header component--}}
    @if(isset($show_head) && !$show_head)
        @include('partials.header-progress-management-admin')
    @else
        @include('partials.header')
    @endif

    {{--Main content component--}}
    <div class="{{ $class }}">

        @if (!empty($logined) && strcmp($logined->auth, 'affiliation') === 0 && !isset($typeProject))
            <div class="kameiten-notice">
                <div class="container">
                    <a href="{{ route('notice_info.near') }}">【近隣施工エリア拡大のおねがい】</a>
                </div>
            </div>
        @endif
        <div class="{{ $classContent }}">
            @yield('content')
        </div>
    </div>
    @include('partials.footer-ios')
    {{--Footer component--}}
    @include('partials.footer')
</div>

@include('partials.global_modal')

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/lib/jquery-ui.min.js') }}"></script>
<script src="{{ mix('js/lib/jquery.ui.datepicker-ja.min.js') }}"></script>
<script src="{{ mix('js/lib/jquery-ui-sliderAccess.js') }}"></script>
<script src="{{ mix('js/lib/jquery-ui-timepicker-addon.min.js') }}"></script>
<script src="{{ mix('js/lib/jquery-ui-timepicker-ja.js') }}"></script>
<script src="{{ mix('js/pages/global.js') }}"></script>
<script src="{{ mix('js/utilities/st.storage.js') }}"></script>
<script src="{{ mix('js/pages/helpers/datetime.js') }}"></script>
<script>
    var footerIOSEl = $('#footer_ios');
    var footerDefault = $('#footer_default');
    var widthScreen = $(window).width();
    function goBack() {
        if (document.referrer) {
            window.history.back();
        }
    }

    $(document).ready(function () {
        var userAgent = window.navigator.userAgent.toLowerCase();
        if(/iphone|ipod|ipad/.test(userAgent) && widthScreen < 767) {
            footerDefault.addClass("on-ios");
            footerIOSEl.show();
        } else {
            footerDefault.removeClass("on-ios");
            footerIOSEl.hide();
        }

    });
</script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
    $( document ).ready(function() {
        $('form').bind("keypress", function(e) {
            if (e.keyCode == 13 && !$(document.activeElement).is('textarea')) {
                e.preventDefault();
                return false;
            }
        });
    });
    $(document).on("keydown", disableF5);
</script>
@yield('script')
</body>
</html>
