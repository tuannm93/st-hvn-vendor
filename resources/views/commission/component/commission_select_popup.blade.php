@php
    $indexColumn = 1;
@endphp
<table id="introduction" class="table custom-border">
    <thead>
    <tr class="text-center bg-yellow-light">
        <th colspan="2" class="align-middle fix-w-100"></th>
        <th class="align-middle fix-w-100">@lang('commissionselect.franchise_store')</th>
        <th class="align-middle fix-w-100">@lang('commissionselect.unit_price_per_contract')
            <br>(@lang('commissionselect.the_past_year'))
        </th>
        <th class="align-middle fix-w-50">@lang('commissionselect.unit_price')<br>@lang('commissionselect.rank')</th>
        <th class="align-middle fix-w-100">@lang('commissionselect.location')</th>
        <th class="align-middle fix-w-100">@lang('commissionselect.genre_intermediation')
            <br>(@lang('commissionselect.cumulative'))
        </th>
        <th class="align-middle fix-w-100">@lang('commissionselect.genre_closing_rate')
            <br>(@lang('commissionselect.cumulative'))
        </th>
        <th class="align-middle fix-w-50">@lang('commissionselect.expertise')</th>
        <th class="align-middle fs-11 fix-w-80">@lang('commissionselect.available_time')</th>
        <th class="align-middle fs-11 fix-w-80">@lang('commissionselect.available_time_to')</th>
        <th class="align-middle fix-w-50">@lang('commissionselect.holiday')</th>
        <th class="align-middle fix-w-70">@lang('commissionselect.pic_name')</th>
    </tr>
    </thead>
    <tbody>
    {{--LISTT 11111--}}

    @if(isset($new_list) && count($new_list) > 0)
        @foreach ($new_list as $key => $val)
            @php

                $bgColor = '';

                if (isset($val['is_gray']) && $val['is_gray'] == true) {
                    $bgColor = '#dddddd';
                } elseif (isset($val['all_condition']) && $val['all_condition'] == true) {
                    $bgColor = '#ffffc0';
                }
            @endphp
            <tr bgcolor="{{$bgColor}}">
                <td rowspan="3" class="p-1 align-middle text-wrap fix-w-60 text-center">
                    @include('commission.component.commission_hidden_input')
                    <select name="select_order" class="form-control p-1"></select>
                </td>
                @if($val['group_corp'] === 1)
                    <td rowspan="3" class="p-1 align-middle text-wrap fix-w-60 text-center">{{ $indexColumn }}</td>
                    @php
                        $indexColumn++
                    @endphp
                @endif
                @if($val['group_corp'] === 2)
                <td rowspan="3" class="p-1 align-middle text-wrap fix-w-60 text-center">@lang('commissionselect.first_time')</td>
                @endif
                <td class="p-1 align-middle text-wrap fix-w-100">{{ $val['corp_name'] }}</td>
                <td class="p-1 align-middle text-wrap fix-w-100 text-right">
                    @if(empty($data['target_check']))
                        {{ yenFormat2($val['commission_unit_price_category']) }}
                    @else
                        {{ yenFormat2($val['commission_unit_price']) }}
                    @endif
                </td>
                <td class="p-1 align-middle text-wrap fix-w-60 text-center">
                    @if(empty($data['target_check']))
                        {{ $val['commission_unit_price_rank_1'] }}
                    @else
                        {{ $val['commission_unit_price_rank_2'] }}
                    @endif
                </td>
                <td class="p-1 align-middle text-wrap fix-w-50">
                    {{ getDivTextJP('prefecture_div', $val['address1']) }} {{ $val['address2'] }}
                </td>
                <td class="p-1 align-middle text-wrap fix-w-100 text-right">
                    @if(!empty($val['commission_count_category_as']))
                        {{ $val['commission_count_category_as'] }}
                    @else
                        0
                    @endif
                </td>
                <td class="p-1 align-middle text-wrap fix-w-100 text-right">
                    @if(!empty($val['orders_count_category']) && !empty($val['commission_count_category_as']))
                        {{ round($val['orders_count_category'] / $val['commission_count_category_as'] * 100) }}
                        %
                    @else
                        0%
                    @endif
                </td>
                <td class="p-1 align-middle text-wrap fix-w-60 text-center">
                    {{ $val['select_list'] }}
                </td>
                <td class="p-1 align-middle text-wrap fix-w-100 text-center">
                    @if($val['contactable_support24hour'] == 0)
                        @if(!empty($val['contactable_time_from']) || !empty($val['contactable_time_to']) )
                            {{ $val['contactable_time_from'] }} - {{  $val['contactable_time_to'] }}
                        @else
                            {{ $val['contactable_time'] }}
                        @endif
                    @else
                        @lang('commissionselect.24h')
                    @endif
                </td>
                <td class="p-1 align-middle text-wrap fix-w-100 text-center">
                    @if($val['support24hour'] == 0)
                        @if(!empty($val['available_time_from']) || !empty($val['available_time_to']) )
                            {{ $val['available_time_from']}} - {{  $val['available_time_to'] }}
                        @else
                            {{ $val['available_time'] }}
                        @endif
                    @else
                        @lang('commissionselect.24h')
                    @endif
                </td>
                <td class="p-1 align-middle text-wrap fix-w-60 text-center">
                    {{ $val['holiday'] }}
                </td>
                <td class="align-middle fix-w-70 text-center text-wrap" rowspan="3">
                    @if(!empty($val['name_staff']))
                        {{ $val['name_staff'] ?? '' }}
                        @if(!empty($val['status_name']))
                            / {{$val['status_name'] ?? ''}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr bgcolor="{{$bgColor}}">
                <td colspan="10">
                    <span class="fs-11 text-gray" title="{{ $val['attention'] }}">
                    {{ mb_substr($val['attention'], 0, 80) }}
                        @if(mb_strlen($val['attention'])>= 80)
                            ...
                        @endif
                    </span>
                </td>
            </tr>
            <tr bgcolor="{{$bgColor}}">
                @if(isset($val['new_year_corp_id']))
                    <td colspan="10" class="p-0">
                        <table>
                            <tr class="text-center">
                                @php
                                    $numberVacation = count($vacation);
                                    for($i=1; $i<=$numberVacation; $i++){
                                        $key = 'label_' . sprintf('%02d',$i);
                                        echo '<td class="align-middle">'.$val[$key].'</td>';
                                    }
                                @endphp
                            </tr>
                            <tr>
                                @php
                                    for($i=1; $i<=$numberVacation; $i++){
                                        $key = 'status_' . sprintf('%02d',$i);
                                        if(!empty($val[$key])){
                                            echo '<td class="align-middle">'.$val[$key].'</td>';
                                        }else{
                                            echo '<td class="align-middle opacity-0">円</td>';
                                        }
                                    }
                                @endphp
                            </tr>
                            <tr>
                                <td class="align-middle">@lang('commissionselect.remark')</td>
                                <td colspan="<?php echo $numberVacation - 1; ?>"
                                    class="align-middle"><?php if (isset($val['note_new_year']))
                                        echo nl2br($val['note_new_year']); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                @endif
            </tr>
        @endforeach
    @endif
    </tbody>
</table>


