<div class="table-block">
    <div class="sort-key-block row">
        @php
            $sort = $dataSort['sort'];
            $order = $dataSort['order'];
        @endphp
        <div class="sort-link ml-2">
            <a href="{{URL::current().'?sort=status&order='.($sort == 'status' && $order == 'desc' ? 'asc' : 'desc')}}"
               class="{{($sort == 'status' && $order == 'asc' ? 'up' : 'down')}}">@lang('commissioninfos.lbl.status')</a>
            {{ ($sort == 'status') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
        </div>
        <div class="sort-link">
            <a href="{{URL::current().'?sort=receive_datetime&order='.($sort == 'receive_datetime' && $order == 'desc' ? 'asc' : 'desc')}}"
               class="{{($sort == 'receive_datetime' && $order == 'asc' ? 'up' : 'down')}}">@lang('commissioninfos.lbl.receive_datetime') </a>
            {{ ($sort == 'receive_datetime') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
        </div>
        <div class="sort-link">
            <a href="{{URL::current().'?sort=visit_time_min&order='.($sort == 'visit_time_min' && $order == 'desc' ? 'asc' : 'desc')}}"
               class="{{($sort == 'visit_time_min' && $order == 'asc' ? 'up' : 'down')}}">@lang('commissioninfos.lbl.visit_time_min') </a>
            {{ ($sort == 'visit_time_min') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
        </div>
        <div class="sort-link">
            <a href="{{URL::current().'?sort=contact_desired_time&order='.($sort == 'contact_desired_time' && $order == 'desc' ? 'asc' : 'desc')}}"
               class="{{($sort == 'contact_desired_time' && $order == 'asc' ? 'up' : 'down')}}">@lang('commissioninfos.lbl.contact_desired_time_header') </a>
            {{ ($sort == 'contact_desired_time') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
        </div>
    </div>
    <div class="list-item">
        @foreach($results as $item)
            @php
                if ($item->commission_infos_app_notread == 1) {
                    $totalItemNotRead++;
                }
            @endphp
            <div class="commission-item">
                <div class="commission_info_title mt-2 d-flex align-items-center p-2">
                    <div class="w-80">
                        @lang('commissioninfos.lbl.demand_id')&nbsp;{{$item->demand_infos_id}}&nbsp;
                        @lang('commissioninfos.lbl.item_name')：
                        <span class="{{$item->commission_infos_app_notread == 1 ? 'app_not_read' : ''}}">{{$item->m_items_item_name}}
                            &nbsp;</span>
                    </div>
                    <div class=" w-20 {{$item->status == 1 ? 'has_problem' : ''}} problem_box">
                        {{ $item->status == 1 ? trans('commissioninfos.status.has_problem') : trans('commissioninfos.status.no_problem') }}
                    </div>
                </div>
                <div class="detail_box">
                    <div class="ml-4">
                        <div class="detail_item_title pl-2">
                            @lang('commissioninfos.lbl.site_name')
                        </div>
                        <div class=" detail_item_value">
                            <a href="{{(strpos($item->m_sites_url, 'http') !== false ? $item->m_sites_site_url : 'http://' . $item->m_sites_site_url)}}"
                               target="_blank">{{$item->m_sites_site_name}}
                            </a>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="detail_item_title pl-2">
                            @lang('commissioninfos.lbl.receive_datetime')
                        </div>
                        <div class=" detail_item_value">
                            {!! dateTimeWeek($item->commission_infos_commission_note_send_datetime,'%Y/%m/%d(%a)%R') !!}
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="detail_item_title pl-2">
                            @lang('commissioninfos.lbl.visit_time_min')
                        </div>
                        <div class=" detail_item_value">
                            @if(isset($item->visit_time_view_visit_time_to))
                                {!! dateTimeWeek($item->visit_time_view_visit_time, '%Y/%m/%d(%a)%R') !!}
                                <br>{{trans('common.wavy_seal')}}<br>
                                {!! dateTimeWeek($item->visit_time_view_visit_time_to, '%Y/%m/%d(%a)%R') !!}
                            @elseif(isset($item->visit_time_view_visit_time))
                                {!! dateTimeWeek($item->visit_time_view_visit_time, '%Y/%m/%d(%a)%R') !!}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="detail_item_title pl-2">
                            @lang('commissioninfos.lbl.contact_desired_time_header')
                        </div>
                        <div class=" detail_item_value">
                            {!! getContactDesiredTime2($item, '<br>～<br>', '%Y/%m/%d(%a)%R') !!}
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="detail_item_title pl-2">
                            @lang('commissioninfos.lbl.corp_name')
                        </div>
                        <div class=" detail_item_value">
                            {{mb_strimwidth($item->demand_infos_customer_name, 0, 24, "...")}}
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="detail_item_title pl-2">
                            @lang('commissioninfos.lbl.tel1')
                        </div>
                        <div class=" detail_item_value">
                            @php
                                $tel_col = '';
                                if ($isRoleAffiliation
                                || ($item->demand_infos_selection_system != getDivValue('selection_type', 'auction_selection')
                                && $item->demand_infos_selection_system != getDivValue('selection_type', 'automatic_auction_selection'))
                                || $item->demand_infos_contact_desired_time != ''
                                || $item->demand_infos_contact_desired_time_from != '') {
                                $tel_col = '<a href="' .checkDevice(). $item->demand_infos_tel1 . '">' . $item->demand_infos_tel1 . '</a>';
                                } else {
                                $hour = 0;
                                $minute = 0;
                                if (isset($tel_disclosure[$item->demand_infos_demand_priority]['item_hour_date'])) {
                                $hour = $tel_disclosure[$item->demand_infos_demand_priority]['item_hour_date'];
                                }
                                if (isset($tel_disclosure[$item->demand_infos_demand_priority]['item_minute_date'])) {
                                $minute = $tel_disclosure[$item->demand_infos_demand_priority]['item_minute_date'];
                                }
                                if (!empty($hour)) {
                                $hour = $hour * 60;
                                }
                                $num = $hour + $minute;

                                if (isset($item->visit_time_view_visit_time)) {
                                $visittime = $item->visit_time_view_visit_time;
                                }
                                if (empty($visittime)) {
                                if (isset($item->demand_infos_contact_desired_time)) {
                                $visittime = $item->demand_infos_contact_desired_time;
                                } else {
                                $visittime = $item->demand_infos_contact_desired_time_from;
                                }
                                }
                                $target_date = date("Y-m-d H:i:s", strtotime($visittime . "-" . $num . " minute"));
                                if (strtotime($target_date) <= strtotime(date("Y-m-d H:i:s")) ||
                                $item->m_corps_auction_masking != getDivValue("auction_masking", "without") ||
                                isset($item->visit_time_view_visit_adjust_time)) {
                                $tel_col = '<a href="' .checkDevice(). $item->demand_infos_tel1 . '">' . $item->demand_infos_tel1 . '</a>';
                                } else {
                                $tel_col = !empty($item->demand_infos_tel1) ? substr_replace($item->demand_infos_tel1, "******", -6, 6) : '';
                                }
                                }
                            @endphp
                            {!! $tel_col !!}
                        </div>
                    </div>
                </div>
                <div class="button_block d-flex justify-content-center">
                    <a href="{{route('commission.detail',['id' => $item->commission_infos_id])}}"
                       target="_blank"
                       class="btn btn--gradient-orange">@lang('commissioninfos.lbl.btn_show')</a>
                    @if($isRoleAffiliation)
                        @if($item->commission_infos_lock_status != 1)
                            <a href="{{route('addition.index',['demandId' => $item->demand_infos_id])}}"
                               target="_blank"
                               class="btn btn--gradient-green ml-2">@lang('commissioninfos.lbl.btn_addition')</a>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
<div id="list-items-footer" class="list-items-footer text-center mr-3">
    {{$results->links('demand_list.demandlist_pagination')}}
</div>
@section('style')
    <style>
        .sort-key-block {
            padding-top: 10px;
            font-size: 12px;
        }

        .sort-link {
            display: inline-block;
            padding: 5px 12px;
            border: 1px solid rgb(188, 189, 190);
            border-radius: 2rem;
            color: black;
            text-decoration: none;
            margin-bottom: 10px;
            background: rgb(255, 255, 255);;
        }

        .sort-link a {
            color: #000;
            text-decoration: none;
        }

        .sort-link a:hover {
            text-decoration: none;
        }
    </style>
@endsection
