@extends('layouts.app')
@section('content')
    <div class="autocall-index">
        <div class="form-category">
            @php
                $isError = $errors->any()
            @endphp

            @if (Session::has('success'))
                <p class="box__mess box--success">
                    {{ __('autocall.message_successfully') }}
                </p>
            @endif
            @if ($isError)
                <p class="box__mess box--error">
                    {{ __('autocall.message_failure') }}
                </p>
            @endif
            <label class="form-category__label font-weight-light">{{ __('autocall.title_autocallsetting') }}</label>
            <label class="form-category__label">{{ __('autocall.title_autocalltime') }}</label>
            <div class="clearfix">
                <form id="autocall-form" class="form-horizontal" method="post" action="{{route('autocall.update')}}">
                    <input type="hidden" value="{{csrf_token()}}" name="_token">
                    <input id="id" name="id" type="hidden" value="{{ $item->id }}">
                    <div class="mx-0 row mb-1 {{$errors->has('asap') ? 'has-danger' : ''}}">
                        <label for="asap" class="col-form-label col-sm-3 col-md-2 col-lg-1 px-0 mr-lg-4">{{ __('autocall.lbl_asap')  }}</label>
                        <label class="col-form-label col-6 col-sm-4 col-md-3 col-lg-2 px-0">{{ __('autocall.lbl_notice') }}</label>
                        <div class="col-6 col-sm-5 d-flex px-0">
                            <input id="asap" data-rule-number="true" data-rule-min="0" data-rule-max="60" data-error-container="#asap-numeric-fail" name="asap" size="10" maxlength="2" type="text" class="form-control w-20 p-1 {{$errors->has('asap') ? 'form-control-danger' : ''}}" value="{{ $isError ? old('asap') : $item->asap }}">
                            <span class="input-group-text">{{ __('autocall.lbl_minute') }}</span>
                            @if ($errors->has('asap'))
                            <p class="form-control-feedback text-danger my-2 {{$errors->has('asap') ? 'has-danger' : ''}}">{{__($errors->first('asap'))}}</p>
                            @endif
                        </div>
                        <div id="asap-numeric-fail" class="offset-6 offset-lg-3 offset-md-5 offset-sm-7 pl-lg-4"></div>
                    </div>
                    <div class="mx-0 row mb-1 {{$errors->has('immediately') ? 'has-danger' : ''}}">
                        <label for="immediately" class="col-form-label col-sm-3 col-md-2 col-lg-1 px-0 mr-lg-4">{{ __('autocall.lbl_immediately') }}</label>
                        <label class="col-form-label col-6 col-sm-4 col-md-3 col-lg-2 px-0">{{ __('autocall.lbl_notice') }}</label>
                        <div class="col-6 col-sm-5 d-flex px-0">
                            <input id="immediately" data-rule-number="true" data-rule-min="0" data-rule-max="60" data-error-container="#immediately-numeric-fail" name="immediately" size="10" maxlength="2" type="text" class="form-control w-20 p-1" value="{{ $isError ? old('immediately') : $item->immediately }}">
                            <span class="input-group-text">{{ __('autocall.lbl_minute') }}</span>
                            @if ($errors->has('immediately'))
                            <p class="form-control-feedback text-danger my-2 {{$errors->has('immediately') ? 'has-danger' : ''}}">{{__($errors->first('immediately'))}}</p>
                            @endif
                        </div>
                        <div id="immediately-numeric-fail" class="offset-6 offset-lg-3 offset-md-5 offset-sm-7 pl-lg-4"></div>
                    </div>
                    <div class="mx-0 row mb-1 {{$errors->has('normal') ? 'has-danger' : ''}}">
                        <label for="normal" class="col-form-label col-sm-3 col-md-2 col-lg-1 px-0 mr-lg-4">{{ __('autocall.lbl_normal') }}</label>
                        <label class="col-form-label col-6 col-sm-4 col-md-3 col-lg-2 px-0">{{ __('autocall.lbl_notice') }}</label>
                        <div class="col-6 col-sm-5 d-flex px-0">
                            <input id="normal" data-rule-number="true" data-rule-min="0" data-rule-max="60" data-error-container="#normal-numeric-fail" name="normal" size="10" maxlength="2" type="text" class="form-control w-20 p-1" value="{{ $isError ? old('normal') : $item->normal }}">
                            <span class="input-group-text">{{ __('autocall.lbl_minute') }}</span>
                            @if ($errors->has('normal'))
                            <p class="form-control-feedback text-danger my-2 {{$errors->has('normal') ? 'has-danger' : ''}}">{{__($errors->first('normal'))}}</p>
                            @endif
                        </div>
                        <div id="normal-numeric-fail" class="offset-6 offset-lg-3 offset-md-5 offset-sm-7 pl-lg-4"></div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-xl-5 col-xl-7 text-center text-xl-left my-5">
                            <input type="submit" class="btn btn--gradient-green remove-effect-btn col-sm-3" value="{{ __('autocall.btn_regist') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>

    <script>
        FormUtil.validate('#autocall-form');
    </script>
@endsection