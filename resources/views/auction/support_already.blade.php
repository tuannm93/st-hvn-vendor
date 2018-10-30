@extends('layouts.app')

@section('content')
    <div class="auction-support text-center pt-2">
        <p class="mb-0 text-danger">@lang('support.couldNotBuy')</p>
        <p class="mb-0">{{ dateTimeFormat($results['created'], 'Y年m月d日 H時i分')}}@lang('support.into')</p>
        <p class="mb-0">@lang('support.otherCompanyBid')</p>
        <div class="pt-3">
            <button type="button" class="btn btn--gradient-gray">@lang('support.closeBtn')</button>
        </div>
    </div>
@endsection
