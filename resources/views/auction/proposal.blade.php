@extends('layouts.app')
@section('content')
<div class="form-category auction-proposal">
    <label class="form-category__label">@lang('auction_proposal.deal_details')</label>
    <div class="form-category__body pl-4">
        <div class="form-group row">
            <label class="col-form-label col-3 col-sm-2 col-md-2 text-center my-auto">@lang('auction_proposal.content_of_consultation')</label>
            <label class="col-form-label col-9 col-sm-10 col-md-10 my-auto">
            	{!! !empty($demandInfo->contents) ? $demandInfo->contents : '' !!}
            </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 text-center pb-2">
            <button type="button" class="btn btn-outline-secondary btn-close" id="close">@lang('auction_proposal.close_up')</button>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ mix('js/pages/auction.proposal.js') }}"></script>
@endsection