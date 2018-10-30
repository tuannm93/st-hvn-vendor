@extends('layouts.app')
@section('content')
    <div class="not-correspond-index">
        <label class="form-category__label font-weight-normal">@lang('not_correspond.page_title')</label>
        @if(Session::has('flash_message'))
            <p class="alert alert-{{ (Session::get('flash_message')['type'] == 'success') ? 'success' : 'error'}}">{{ Session::get('flash_message')['text'] }}</p>
        @endif
        {{Form::model($item, ['route' => ['not_correspond.update', $item->id], 'id'=>'form-not-correspond'])}}
            <label class="form-category__label">@lang('not_correspond.form_title')</label>
            <div class="mx-0 row mb-1">
                <label class="col-form-label col-2 col-md-2 col-xl-1 px-0 font-weight-bold">@lang('not_correspond.not_correspond_count_3')</label>
                <label class="col-form-label col-4 col-sm-4 col-md-2 col-xl-1 px-0">@lang('not_correspond.lbl_count_of_year_before')</label>
                <div class="col-6 col-sm-5 d-flex align-items-center px-0">
                    {{Form::text('small_lower_limit', $item->small_lower_limit, ['class'=>'form-control mx-1 p-1 w-60 w-sm-20', 'data-rule-required'=>'true', 'data-rule-number'=>'true', 'maxlength'=>'10', 'data-error-container'=>'#err-small-lower-limit'])}}
                    <span>@lang('not_correspond.lbl_count_of_year_after')</span>
                </div>
            </div>
            <div class="mx-0 row mb-1">
                <div id="err-small-lower-limit" class="offset-6 offset-sm-7 offset-md-5 offset-lg-2 pl-1"></div>
                @if($errors->has('small_lower_limit'))
                <div class="text-danger offset-6 offset-sm-7 offset-md-5 offset-lg-2 pl-1">{{$errors->get('small_lower_limit')[0]}}</div>
                @endif
            </div>
            <div class="mx-0 row mb-1">
                <label class="col-form-label col-2 col-md-2 col-xl-1 px-0 font-weight-bold text-orange">@lang('not_correspond.not_correspond_count_2')</label>
                <label class="col-form-label col-4 col-sm-4 col-md-2 col-xl-1 px-0">@lang('not_correspond.lbl_count_of_year_before')</label>
                <div class="col-6 col-sm-5 d-flex align-items-center px-0">
                    {{Form::text('midium_lower_limit', $item->midium_lower_limit, ['class'=>'form-control mx-1 p-1 w-60 w-sm-20', 'data-rule-required'=>'true', 'data-rule-number'=>'true', 'maxlength'=>'10', 'data-error-container'=>'#err-midium-lower-limit'])}}
                    <span>@lang('not_correspond.lbl_count_of_year_after')</span>
                </div>
            </div>
            <div class="mx-0 row mb-1">
                <div id="err-midium-lower-limit" class="offset-6 offset-sm-7 offset-md-5 offset-lg-2 pl-1"></div>
                @if($errors->has('midium_lower_limit'))
                <div class="text-danger offset-6 offset-sm-7 offset-md-5 offset-lg-2 pl-1">{{$errors->get('midium_lower_limit')[0]}}</div>
                @endif
            </div>
            <div class="mx-0 row mb-1">
                <label class="col-form-label col-2 col-md-2 col-xl-1 px-0 font-weight-bold text-red">@lang('not_correspond.not_correspond_count_1')</label>
                <label class="col-form-label col-4 col-sm-4 col-md-2 col-xl-1 px-0">@lang('not_correspond.lbl_count_of_year_before')</label>
                <div class="col-6 col-sm-5 d-flex align-items-center px-0">
                    {{Form::text('large_lower_limit', $item->large_lower_limit, ['class'=>'form-control mx-1 p-1 w-60 w-sm-20', 'data-rule-required'=>'true', 'data-rule-number'=>'true', 'maxlength'=>'10', 'data-error-container'=>'#err-large-lower-limit'])}}
                    <span>@lang('not_correspond.lbl_count_of_year_after')</span>
                </div>
            </div>
            <div class="mx-0 row mb-1">
                <div id="err-large-lower-limit" class="offset-6 offset-sm-7 offset-md-5 offset-lg-2 pl-1"></div>
                @if($errors->has('large_lower_limit'))
                <div class="text-danger offset-6 offset-sm-7 offset-md-5 offset-lg-2 pl-1">{{$errors->get('large_lower_limit')[0]}}</div>
                @endif
            </div>
            <div class="mx-0 row mb-1">
                <label class="col-form-label col-2 col-xl-1 px-0 font-weight-bold text-red">@lang('not_correspond.not_correspond_count_0')</label>
                <div class="col-10 col-sm-5 d-flex align-items-center px-0">
                    <label class="col-auto col-form-label px-0">@lang('not_correspond.lbl_date_of_immediate_first')</label>
                    {{Form::text('immediate_date', $item->immediate_date, ['class'=>'form-control mx-1 p-1 w-25 w-sm-20', 'data-rule-required'=>'true', 'data-rule-number'=>'true', 'maxlength'=>'10', 'data-error-container'=>'#err-immediate-date'])}}
                    <span>@lang('not_correspond.lbl_date_of_immediate_second')</span>
                    {{Form::text('immediate_lower_limit', $item->immediate_lower_limit, ['class'=>'form-control mx-1 p-1 w-25 w-sm-20', 'data-rule-required'=>'true', 'data-rule-number'=>'true', 'maxlength'=>'10', 'data-error-container'=>'#err-immediate-lower-limit'])}}
                    <span>@lang('not_correspond.lbl_count_of_year_after')</span>
                </div>
            </div>
            <div class="mx-0 row mb-1">
                <div class="offset-2 offset-xl-1 col-sm-6 col-lg-3 px-0">
                    <div class="row mx-0">
                        <div id="err-immediate-date" class="col-6 col-sm-6 px-0"></div>
                        <div id="err-immediate-lower-limit" class="col-5 col-sm-6 px-0"></div>
                    </div>
                </div>
                @if($errors->has('immediate_date'))
                    <div class="text-danger offset-6 offset-sm-7 offset-md-5 offset-lg-2 pl-1">{{$errors->get('immediate_date')[0]}}</div>
                @endif
                @if($errors->has('immediate_lower_limit'))
                    <div class="text-danger offset-6 offset-sm-7 offset-md-5 offset-lg-2 pl-1">{{$errors->get('immediate_lower_limit')[0]}}</div>
                @endif
            </div>
            <div class="row">
                <div class="offset-xl-5 col-xl-7 text-center text-xl-left mt-3 mb-5">
                {{Form::submit(trans('not_correspond.button_submit'),['class' => 'btn btn--gradient-green col-sm-3'])}}
                </div>
            </div>

        {!! Form::close() !!}
    </div>
@endsection
@section('script')
<script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
<script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
<script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
<script src="{{ mix('js/utilities/form.validate.js') }}"></script>

<script>
    FormUtil.validate('#form-not-correspond');
</script>
@endsection
