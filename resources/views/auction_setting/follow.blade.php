@extends('layouts.app')

@section('content')
<div class="auction-setting-follow">
    @include('auction_setting.components.follow')
</div>
@endsection

@section('script')
    <script>
    	var urlGetAuctionSettingFollow = '{{ route('ajax.auction.setting.follow') }}';
    </script>
    <script src="{{ mix('js/pages/auction.setting.follow.js') }}"></script>
@endsection
