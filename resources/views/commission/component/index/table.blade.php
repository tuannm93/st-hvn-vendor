<div class="custom-scroll-x">
    <table id="commisionTable" class="table custom-border mt-1">
        <thead>
            <tr class="text-center bg-yellow-light">
                @php
                    $sort = $dataSort['sort'];
                    $order = $dataSort['order'];
                @endphp
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=status&order='.($sort == 'status' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'status' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.status')</a>
                    {{ ($sort == 'status') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=selection_system&order='.($sort == 'selection_system' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'selection_system' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.selection_system')</a>
                    {{ ($sort == 'selection_system') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=demand_id&order='.($sort == 'demand_id' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'demand_id' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.demand_id') </a>
                    {{ ($sort == 'demand_id') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=visit_time_min&order='.($sort == 'visit_time_min' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'visit_time_min' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.visit_time_min') </a>
                    {{ ($sort == 'visit_time_min') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=contact_desired_time&order='.($sort == 'contact_desired_time' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'contact_desired_time' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.contact_desired_time_header') </a>
                    {{ ($sort == 'contact_desired_time') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=corp_name&order='.($sort == 'corp_name' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'corp_name' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.corp_name') </a>
                    {{ ($sort == 'corp_name') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=tel1&order='.($sort == 'tel1' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'tel1' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.tel1') </a>
                    {{ ($sort == 'tel1') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=address1&order='.($sort == 'address1' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'address1' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.address1') </a>
                    {{ ($sort == 'address1') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=site_name&order='.($sort == 'site_name' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'site_name' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.site_name') </a>
                    {{ ($sort == 'site_name') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=receive_datetime&order='.($sort == 'receive_datetime' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'receive_datetime' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.receive_datetime') </a>
                    {{ ($sort == 'receive_datetime') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    <a href="{{URL::current().'?sort=item_name&order='.($sort == 'item_name' && $order == 'desc' ? 'asc' : 'desc')}}"
                    class="{{($sort == 'item_name' && $order == 'asc' ? 'up' : 'down')}} text-dark">@lang('commissioninfos.lbl.item_name') </a>
                    {{ ($sort == 'item_name') ? ($order == 'asc' ? trans('common.asc') : trans('common.desc')) : ''}}
                </th>
                <th class="align-middle p-1">
                    @lang('commissioninfos.lbl.action')
                </th>
                @if($isRoleAffiliation)
                    <th class="align-middle p-1">

                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
        @foreach($results as $item)
            <tr class="{{ $item->commission_infos_app_notread == 1 ? 'app_notread' : '' }} text-left">
                <td class="align-middle p-1">
                    <span class="{{ $item->status == 1 ? 'has_problem' : '' }}" data-id="{{$item->commission_infos_id}}">
                        {{ $item->status == 1 ? trans('commissioninfos.status.has_problem') : trans('commissioninfos.status.no_problem') }}
                    </span>
                </td>
                <td class="align-middle p-1">
                    {{ strlen($item->demand_infos_selection_system)>0 ? trans('commissioninfos.selection_type.'.getDivTextJP('selection_type', $item->demand_infos_selection_system)) : ''}}
                </td>
                <td class="text-center align-middle p-1">
                    {{$item->demand_infos_id}}
                </td>
                <td class="text-center align-middle p-1">
                    {!! dateTimeWeek($item->visit_time_view_visit_time, '%Y/%m/%d(%a)<br>%R') !!}
                    @if(isset($item->visit_time_view_visit_time_to))
                        <br>{{trans('common.wavy_seal')}}<br>
                        {!! dateTimeWeek($item->visit_time_view_visit_time_to, '%Y/%m/%d(%a)<br>%R') !!}
                    @endif
                </td>
                <td class="text-center align-middle p-1">
                    {!! getContactDesiredTime2($item, '<br>～<br>', '%Y/%m/%d(%a)<br>%R') !!}
                </td>
                <td class="align-middle p-1">
                    {{mb_strimwidth($item->demand_infos_customer_name, 0, 24, "...")}}
                </td>
                <td class="align-middle p-1">
                    @php
                        $tel_col = '';
                        if ($isRoleAffiliation
                        || ($item->demand_infos_selection_system != getDivValue('selection_type', 'auction_selection')
                        && $item->demand_infos_selection_system != getDivValue('selection_type', 'automatic_auction_selection'))
                        || $item->demand_infos_contact_desired_time != ''
                        || $item->demand_infos_contact_desired_time_from != '') {
                        $tel_col = '<a href="' .checkDevice(). $item->demand_infos_tel1 . '" class="highlight-link text--underline">' . $item->demand_infos_tel1 . '</a>';
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
                        $tel_col = '<a href="'.checkDevice(). $item->demand_infos_tel1 . '" class="highlight-link text--underline">' . $item->demand_infos_tel1 . '</a>';
                        } else {
                        $tel_col = !empty($item->demand_infos_tel1) ? substr_replace($item->demand_infos_tel1, "******", -6, 6) : '';
                        }
                        }
                    @endphp
                    {!! $tel_col !!}
                </td>
                <td class="align-middle p-1">
                    {{getDivTextJP('prefecture_div', $item->demand_infos_address1)}}
                </td>
                <td class="align-middle p-1">
                    <a href="{{(strpos($item->m_sites_url, 'http') !== false ? $item->m_sites_site_url : 'http://' . $item->m_sites_site_url)}}"
                       target="_blank" class="highlight-link text--underline">{{$item->m_sites_site_name}}
                    </a>
                </td>
                <td class="text-center align-middle p-1">
                    {!! dateTimeWeek($item->commission_infos_commission_note_send_datetime,'%Y/%m/%d(%a)<br>%R') !!}
                </td>
                <td class="align-middle p-1">
                    {{$item->m_items_item_name}}
                </td>
                <td class="align-middle p-1">
                    <a href="{{route('commission.detail',['id' => $item->commission_infos_id])}}"
                       target="_blank"
                       class="btn-show btn btn--gradient-gray text-center">@lang('commissioninfos.lbl.btn_show')</a>

                    @if(!empty($item->demand_attached_files_demand_id))
                        <img src="{{asset('img/file_ico.jpg')}}"
                             alt="@lang('commissioninfos.lbl.display_file')"
                             title="@lang('commissioninfos.lbl.display_file')" width="15">
                    @endif
                </td>
                @if($isRoleAffiliation)
                    <td class="align-middle p-1">
                        @if($item->commission_infos_lock_status != 1)
                            <a href="{{route('addition.index',['demandId' => $item->demand_infos_id])}}"
                               target="_blank"
                               class="btn-add-addition btn btn--gradient-orange">@lang('commissioninfos.lbl.btn_addition')</a>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div id="list-items-footer" class="list-items-footer {{$isMobile ? 'text-center' : ''}}">
    {{$results->links('demand_list.demandlist_pagination')}}
</div>
