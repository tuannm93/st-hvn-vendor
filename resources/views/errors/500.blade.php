<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="page-error">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light page-error__header"></nav>
        </header>
        <div class="page-error__body">
            <div class="error-detail pb-2">
                <h5 class="font-weight-bold text-left">{{ __('custom_error.title_401') }}</h5>
                <p class="text-left mb-0">{{ __('custom_error.content_401') }}</p>
            </div>
            <a class="link-back" href="/">{{ __('custom_error.btnBack') }}</a>
        </div>
        <footer class="clearfix">
            <div class="page-error__footer"></div>
        </footer>
    </div>
</body>

</html>
