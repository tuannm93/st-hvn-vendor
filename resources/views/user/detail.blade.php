@extends('layouts.app')

@section('content')
    <div class="user-detail pt-3">
        @if(Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif
        <div class="d-flex justify-content-end pt-2">
            <span class="text-danger pr-1">*</span><span>(@lang('user.input_require'))</span>
        </div>
        <div class="form-category">
            <label class="form-category__label">@lang('user.info_user')</label>
            <div class="form-category__body pl-4 pr-4">
                @component("user.components._form_detail", ["authList" => $authList, "routeAction" => route('user.create')])
                @endcomponent
            </div>
        </div>
        @include('commission_select.m_corp_display')
    </div>
@endsection
