@extends('layouts.app')

@section('content')
    @component('auction_setting.components.tabs')
    @endcomponent
    <div class="auction-genre pt-2">
        <small>{!! trans('auction_settings.note_genre') !!}</small>
        <div class="form-category pt-4">
            <label class="form-category__label">{{__('auction_settings.title_list_genres')}}</label>
            <div class="container-fluid ml-4">
                <div class="row">
                    @foreach($listGenre as $chunk)
                        <div class="link-genre col-12 col-sm-6 col-md-4 mb-2">
                            <a href="{{action('Auction\AuctionSettingController@genreDetail', $chunk['id'])}}">
                                {{$chunk['name']}}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
