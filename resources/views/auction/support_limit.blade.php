@extends('layouts.app')

@section('content')
    <div class="auction-support text-center pt-2">
        <p class="mb-0">@lang('support.memberStore')</p>
        <p class="mb-0">@lang('support.beenDecide')</p>
        <p class="mb-0">@lang('support.contactDirectly')</p>
        <div class="pt-3">
            <button type="button" class="btn btn--gradient-gray">@lang('support.closeBtn')</button>
        </div>
    </div>
@endsection
