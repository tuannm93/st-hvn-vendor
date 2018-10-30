@extends('layouts.app')

@section('content')
    @component('auction_setting.components.tabs')
    @endcomponent
    <div class="auction-genre">
        <div class="form-category pt-3">
            <label class="form-category__label">{{trans('auction_settings.title_list_prefectures')}}</label>
            <div class="container-fluid ml-4">
                <p>{{__('auction_settings.type_title')}}: {{$genreName}}</p>
                <div class="row">
                    @foreach($prefectureDiv as $key => $pre)
                        @if ($key == 99)
                            @continue;
                        @endif
                        <div class="link-genre col-12 col-sm-4 col-md-3 mb-2">
                            <a href="{{route("auction_setting.prefecture.detail", ["genreId" => $genreId, "prefCd" => $key])}}">
                                {{$pre}}
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="{{action('Auction\AuctionSettingController@genreDetail', ["genreId" => $genreId])}}"
                       class="btn btn--gradient-default">{{ trans('prefecture.back') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
