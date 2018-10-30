@foreach($data as $key => $listTemp)
    <h3 class="border_left_orange font-weight-bold">{{$key}}</h3>
    <div class="conatiner">
        <div class="row">
            @for($i = 0; $i < 3; $i++)
                <div class="col-md-4">
                    <table class="table list_table modern_table">
                        <thead>
                        <tr class="genre_table_th_tr">
                            <th class="genre_table_th_tr fix-w-92 text-center">{{__('affiliation_genre_resign.col_genre_name')}}</th>
                            <th class="genre_table_th_tr fix-w-92 text-center">{{__('affiliation_genre_resign.col_category_name')}}</th>
                            <th class="genre_table_th_tr fix-w-92 text-center">{{__('affiliation_genre_resign.col_compatible')}}</th>
                            @if($bShowLastColumn)
                                <th class="genre_table_th_tr2 fix-w-92 ">{{__('affiliation_genre_resign.col_expertise')}}
                                    <span>{{__('affiliation_genre_resign.col_expertise_required')}}</span>
                                </th>
                            @endif
                        </tr>
                        </thead>
                        @if(!empty($listTemp[$i]))
                            <tbody>
                            @foreach($listTemp[$i] as $obj)
                                <tr class="genre_table_tr_td {{isset($obj['init']) ? 'row-inited':''}}">
                                    <td class="genre_table_tr_td" valign="middle">
                                        <div class="dataForm">
                                            <input type="hidden" title="" name="id"
                                                   value="{{isset($obj['m_corp_categories_temp_id']) ?
                                           $obj['m_corp_categories_temp_id'] : ''}}"/>
                                            <input type="hidden" title="" name="modified"
                                                   value="{{isset($obj['m_corp_categories_temp_modified']) ?
                                           $obj['m_corp_categories_temp_modified'] : ""}}"/>
                                            <input type="hidden" title="" name="default_fee"
                                                   value="{{$obj['m_categories_category_default_fee']}}"/>
                                            <input type="hidden" title="" name="default_fee_unit"
                                                   value="{{$obj['m_categories_category_default_fee_unit']}}"/>
                                            <input type="hidden" title="" name="commission_type"
                                                   value="{{$obj['m_genres_commission_type']}}"/>
                                            <input type="hidden" title="" name="corp_commission_type"
                                                   value="{{isset($obj['m_corp_categories_temp_corp_commission_type']) ?
                                           ($obj['m_corp_categories_temp_corp_commission_type']) : $obj['m_genres_commission_type']}}"/>
                                            <input type="hidden" title="" name="defaultCheckValue"
                                                   value="{{$obj['chk_value']}}"/>
                                        </div>
                                        {{$obj['m_genres_genre_name']}}
                                        <a name="genre_key_{{$obj['m_genres_id']}}"></a>
                                    </td>
                                    <td class="genre_table_tr_td" valign="middle">
                                        {{$obj['m_categories_category_name']}}
                                    </td>
                                    <td class="genre_table_tr_td" align="center" valign="middle">
                                        {{Form::checkbox(
                                            'check_box_commission_type',
                                            $obj['chk_value'],
                                            isset($obj['init']) ? $obj['init'] : false,
                                            ['classs' => 'checkbox']
                                        )}}
                                    </td>
                                    @if($bShowLastColumn)
                                        <td class="genre_table_tr_td" align="center" valign="middle">
                                            {{Form::select(
                                                'expertise_commission_type',
                                                [
                                                    '' => __('affiliation_genre_resign.none'),
                                                    'A' => 'A',
                                                    'B' => 'B',
                                                    'C' => 'C'
                                                ],
                                                $obj['m_corp_categories_temp_select_list'],
                                                ['class' => 'select']
                                            )}}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
            @endfor
        </div>
    </div>
@endforeach
