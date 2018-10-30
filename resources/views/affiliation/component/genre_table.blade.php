@if(count($genreAreaList) == 0)
<p class="border border-thick border-note bg-note text--orange-light p-2">
    {{ __('affiliation.no_applicable_genre_category') }}
</p>
@else
    <table class="table custom-border">
        <thead class="bg-yellow-light">
        <tr class="text-center">
            <th class="align-middle p-1">
                {{ __('affiliation.genre') }}
            </th>
            <th class="align-middle p-1">{{ __('affiliation.category') }}</th>
            <th class="align-middle p-1">
                {!! trans('affiliation.corresponding_area_edit_by_category') !!}
            </th>
            @if ( $userAuth != 'affiliation' || $mCorpsCorpCommissionType != 2 )
                <th class="align-middle p-1">{{ __('affiliation.expertise') }}</th>
            @endif

            <th class="align-middle p-1">{{ __('affiliation.fee') }}</th>
            <th class="align-middle p-1">{{ __('affiliation.unit') }}</th>
            <th class="align-middle p-1">{{ __('affiliation.note') }}</th>
            @if ( $userAuth != 'affiliation' )
            <th class="align-middle p-1"> {{ __('affiliation.intermediary_method') }}</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php $introAutoFlg = false; @endphp
        @if (count($genreAreaList))
            @foreach ($genreAreaList as $genre) 
                @if ($genre['disable_flg'] == false)
                <tr>
                    <td data-label="{{ __('affiliation.genre') }}" class="align-middle p-1">
                        <p class="m-0">
                            {{ $genre['genre_name'] }} @if ( $userAuth == 'affiliation' && $genre['m_sites_commission_type'] == 2 )
                            <span class="text-danger">{{trans('common.asterisk')}}1</span>
                            @php $introAutoFlg = true; @endphp @endif
                        </p>
                        <div class="text-right d-md-none">
                            <a target="_blank" href="{{ action('Affiliation\AffiliationTargetAreaController@targetArea', ['id' => $genre['id']]) }}"
                                class="btn btn--gradient-gray btn-sm border">
                                <strong>{{ __('common.edit') }}</strong>
                            </a>
                        </div>
                    </td>
                    <td data-label="{{ __('affiliation.category') }}" class="align-middle p-1">{{ $genre['category_name'] }}</td>
                    <td class="text-center d-none d-md-table-cell align-middle p-1" data-label="{{ __('affiliation.corresponding_area_edit_by_category') }}">
                        <a href="{{ action('Affiliation\AffiliationTargetAreaController@targetArea', ['id' => $genre['id']]) }}" target="_blank"
                            class="btn btn--gradient-default btn-sm border">
                            <strong>{{ __('common.edit') }}</strong>
                        </a>
                    </td>
                    @if ( $userAuth != 'affiliation' || $mCorpsCorpCommissionType != 2 )
                    <td class="text-md-center align-middle p-1" data-label="{{ __('affiliation.expertise') }}">
                        {{ $genre['select_list'] }}
                    </td>
                    @endif
                    <td class="text-md-center align-middle p-1" data-label="{{ __('affiliation.fee') }}">
                        {{--Category A show order_fee, B show introduce_fee--}} @if($category == 'A') {{ $genre['order_fee'] }} @elseif ($category
                        == 'B') {{ $genre['introduce_fee'] }} @endif
                    </td>
                    <td class="text-md-center align-middle p-1" data-label="{{ __('affiliation.unit') }}">
                        @if($category == 'A') @if(isset($genre['order_fee_unit'])) @if($genre['order_fee_unit'] == 0) {{ __('affiliation.yen') }}
                        @else {{ __('affiliation.percent') }} @endif @endif @elseif ($category == 'B') {{ __('affiliation.yen') }}
                        @endif
                    </td>
                    <td class="text-md-center align-middle p-1" data-label="{{ __('affiliation.note') }}">
                        {{ $genre['note'] }}
                    </td>
                    @if ( $userAuth != 'affiliation' )
                    <td class="d-none d-md-table-cell align-middle p-1" data-label="{{ __('affiliation.intermediary_method') }}">
                        <form action="{{ route('affiliation.updateStatusMCorpCategory', ['id' => $genre['id']]) }}" id="updateStatus_{{ $genre['id'] }}"
                        method="post">
                            <select name="auction_status" class="custom-select custom-select-sm">
                                <option value="0">{{ __('affiliation.not_set') }}</option>
                                @foreach($auctionDeliveryStatusList as $auctionDeliveryStatusKey => $auctionDeliveryStatusValue) @php $selected = ''; @endphp
                                @if($auctionDeliveryStatusKey == $genre['auction_status']) @php $selected = 'selected="selected"';
                                @endphp @endif
                                <option value="{{ $auctionDeliveryStatusKey }}" {{ $selected }}>{{ $auctionDeliveryStatusValue }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="corpId" value="{{ $corpId }}"> {{ csrf_field() }}
                            <button form="updateStatus_{{ $genre['id'] }}" class="mt-1 btn btn--gradient-green btn-sm" type="submit">
                                {{ __('affiliation.change') }}
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                <tr class="d-md-none row-divider">
                </tr>
                @endif
            @endforeach
        @else
        <tr>
            <td data-label="{{ __('affiliation.genre') }}" class="align-middle p-1"></td>
            <td data-label="{{ __('affiliation.category') }}" class="align-middle p-1"></td>
            <td class="text-center d-none d-md-table-cell align-middle p-1" data-label="{{ __('affiliation.corresponding_area_edit_by_category') }}"></td>
            @if ( $userAuth != 'affiliation' || $mCorpsCorpCommissionType != 2 )
            <td class="text-md-center align-middle p-1" data-label="{{ __('affiliation.expertise') }}"></td>
            @endif
            <td class="text-md-center align-middle p-1" data-label="{{ __('affiliation.fee') }}"></td>
            <td class="text-md-center align-middle p-1" data-label="{{ __('affiliation.unit') }}"></td>
            <td class="text-md-center align-middle p-1" data-label="{{ __('affiliation.note') }}">
                {{ $genre['note'] }}
            </td>
            @if ( $userAuth != 'affiliation' )
            <td class="d-none d-md-table-cell align-middle p-1" data-label="{{ __('affiliation.intermediary_method') }}">
                <form action="{{ route('affiliation.updateStatusMCorpCategory', ['id' => $genre['id']]) }}" id="updateStatus_{{ $genre['id'] }}"
                    method="post">
                </form>
            </td>
            @endif
        </tr>
        @endif
    </tbody>
</table>
@if ($introAutoFlg)
<p class="text-danger">{{ __('affiliation.intro_auto_message') }}</p>
@endif @endif
