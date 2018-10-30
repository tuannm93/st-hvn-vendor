@extends('layouts.app')
@php
    $isLogoutMobile = Session::has(\App\Http\Controllers\Auth\LoginController::LOGOUT_FOR_MOBILE);
    Session::remove(\App\Http\Controllers\Auth\LoginController::LOGOUT_FOR_MOBILE);
@endphp
@section('content')
<div>
    @if ($errors->has('user_id'))
        <div class="box__mess box--error">{{ $errors->first('user_id') }}</div>
    @endif
    @if ($errors->has('password'))
        <div class="box__mess box--error">{{ $errors->first('password') }}</div>
    @endif
    @if ($errors->has('GuideLineError'))
        <div class="box__mess box--error">{{ $errors->first('GuideLineError') }}</div>
    @endif
    @if($isLogoutMobile)
        <div id="user_logged_out"></div>
    @endif
</div>
<div class="pt-5">
    <div class="login mx-auto">
    <form class="form-login mx-auto" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }} row">
            <label for="user_id" class="col-sm-4 col-form-label text-sm-right">{{ trans('auth.user_id') }} :</label>
            <div class="col-sm-8">
                <input id="user_id" type="input" class="form-control" name="user_id" value="{{ old('user_id') }}" maxlength="200" required autofocus>
            </div>
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
            <label for="password" class="col-sm-4 col-form-label text-sm-right">{{ trans('auth.password') }} :</label>
            <div class="col-sm-8">
                <input id="password" type="password" class="form-control" name="password" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="offset-sm-4 col-sm-8">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="rememberLogin" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="rememberLogin">{{ trans('auth.remember_login') }}</label>
                </div>
            </div>
        </div>
        <div class="form-group row justify-content-center">
            <input type="submit" onclick="submitbtn()" class="btn--gradient-orange font-weight-bold" value="ログイン">
        </div>
        <div class="form-group row justify-content-center">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="guideline" id="sharingPlace">
                <label class="custom-control-label" for="sharingPlace">{{ trans('auth.agree_service') }}</label>
            </div>
        </div>
        <div class="form-group row justify-content-center">
            <a class="font-weight-bold" href="{{ url('guideline') }}" target="_blank">{{ trans('auth.terms_service') }}</a>
        </div>
    </form>
</div>
</div>
@endsection
@section('script')
    <script src="{{ mix('js/pages/login.js') }}"></script>
@endsection
