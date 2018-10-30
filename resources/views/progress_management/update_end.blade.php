@extends('layouts.app')
@section('style')

@endsection
@section('content')
    <h2 class="title_update_end">{{ $pageTitle }} @lang('pm_update_confirm.done_transmission_complete')</h2>
    <div class="box_center_update_end">
        <div class="message">
            @lang('pm_update_confirm.confirm_success')
        </div>
    </div>
@endsection
