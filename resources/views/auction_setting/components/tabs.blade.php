@php
    $tab1Active = '';
    $tab2Active = '';
    $tab3Active = '';
    $tab4Active = '';
    $tab5Active = '';
    $pathCurrent = Route::current()->getName();
    switch ($pathCurrent){
        case 'auction_setting.index':
            $tab1Active = 'active';
            break;
        case 'AuctionSetting.exclusion':
            $tab2Active = 'active';
            break;
        case 'auction_setting.postExclusion':
            $tab2Active = 'active';
            break;
        case 'auction_setting.genre':
        case 'auction.setting.genre.detail':
        case 'auction_setting.prefecture':
        case 'auction_setting.prefecture.detail':
            $tab3Active = 'active';
            break;
        case 'auction_setting.ranking':
            $tab4Active = 'active';
            break;
        default:
            $tab1Active = 'active';
            break;
    }
@endphp
<div class="tab-menu-custom">
    <ul>
        <li class="{{$tab1Active}}">
            <a class="link" tabindex="-1" href="{{ route('auction_setting.index') }}">
                {{ __('auction_settings.tab_auction_setting')}}</a>
        </li>
        <li class="{{$tab2Active}}">
            <a class="link" tabindex="-1" href="{{ route('AuctionSetting.exclusion') }}"> {!! trans('auction_settings.tab_auction_setting_exclusion')!!} </a>
        </li>
        <li class="{{$tab3Active}}">
            <a class="link" tabindex="-1" href="{{route('auction_setting.genre')}}">
                {{ __('auction_settings.tab_auction_setting_genre')}}
            </a>
        </li>
        <li class="{{$tab4Active}}">
            <a class="link" tabindex="-1" href="{{route('auction_setting.ranking')}}">
                {!! trans('auction_settings.tab_auction_setting_ranking')!!}
            </a>
        </li>
    </ul>
</div>
