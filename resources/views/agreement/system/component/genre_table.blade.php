<table class="table table-list table-content table-bordered">
    <thead class="text-center">
    <tr>
        <th>{{__('agreement_system.genre')}}</th>
        <th>{{__('agreement_system.category')}}</th>
        <th>{{__('agreement_system.expertise')}}</th>
        <th>{{__('agreement_system.fee')}}</th>
        <th>{{__('agreement_system.unit')}}</th>
        <th>{{__('agreement_system.note')}}</th>
    </tr>
    </thead>
    @if(count($corpCategoryList) != 0)
        <tbody>
        @foreach ($corpCategoryList as $genre)
            @if(($category == 'A' && $genre['corp_commission_type'] != 2) || ($category == 'B' && $genre['corp_commission_type'] == 2))
                <tr>
                    <td data-label="{{ __('agreement_system.genre') }}">
                        {{ $genre['genre_name'] }}
                    </td>
                    <td data-label="{{ __('agreement_system.category') }}">
                        {{ $genre['category_name'] }}
                    </td>
                    <td class="text-center" data-label="{{ __('agreement_system.expertise') }}">
                        {{ $genre['select_list'] }}
                    </td>
                    @if($category == 'A' && $genre['corp_commission_type'] != 2)
                        <td class="text-center" data-label="{{ __('agreement_system.fee') }}">
                            {{ $genre['order_fee'] }}
                        </td>
                        <td class="text-center" data-label="{{ __('agreement_system.unit') }}">
                            @if(isset($genre['order_fee_unit']))
                                @if($genre['order_fee_unit'] == 1)
                                    {{__('agreement_system.percent')}}
                                @else
                                    {{__('agreement_system.yen')}}
                                @endif
                            @endif
                        </td>
                    @else
                        @if(isset($genre['introduce_fee']))
                            <td class="text-center" data-label="{{ __('agreement_system.fee') }}">
                                {{ $genre['introduce_fee'] }}
                            </td>
                            <td class="text-center" data-label="{{ __('agreement_system.unit') }}">
                                {{__('agreement_system.yen')}}
                            </td>
                        @else
                            <td class="text-center" data-label="{{ __('agreement_system.fee') }}">
                                {{ $genre['order_fee'] }}
                            </td>
                            <td class="text-center" data-label="{{ __('agreement_system.unit') }}">
                                @if(isset($genre['order_fee_unit']))
                                    @if($genre['order_fee_unit'] == 1)
                                        {{__('agreement_system.percent')}}
                                    @else
                                        {{__('agreement_system.yen')}}
                                    @endif
                                @endif
                            </td>
                        @endif
                    @endif
                    <td data-label="{{ __('agreement_system.note') }}">
                        {{ $genre['m_corp_categories_temp_note'] }}
                    </td>
                </tr>
                <tr class="row-divider d-md-none"></tr>
            @endif
        @endforeach
        </tbody>
    @endif
</table>
