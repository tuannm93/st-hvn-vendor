@extends('layouts.app')
@section('style')
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content py-4">
                    <p class="font-weight-bold mb-0">{{ trans('report_auction_fall.list_of_vendors_for_selection') }}</p>
                    @if(isset($results) && count($results) > 0)
                        {{ trans('report_auction_fall.the_total_number_of_cases').$results->total().trans('report_auction_fall.matter') }}
                    @else
                        {{ trans('report_auction_fall.the_total_number_of_cases').'0'.trans('report_auction_fall.matter') }}
                    @endif
                    <div class="table-responsive" id="table-report-auction-fall" data-url="{{ route('report.auctionfalltable') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/pages/report_auction_fall.js') }}"></script>
@endsection