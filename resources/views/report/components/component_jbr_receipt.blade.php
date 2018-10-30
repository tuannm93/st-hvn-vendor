<p id="span_number_corp">{{ $numberCorp }}</p>
<p id="span_number_record">@lang('report_jbr.list_jbr_1'){{ $results->total() }}@lang('report_jbr.list_jbr_2')</p>
<div class="custom-scroll-x">
    <table class="table custom-border add-pseudo-scroll-bar">
        <thead>
            <tr class="text-center bg-yellow-light">
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.demand_id_tbl')
                    <i class="triangle-up mr-1 sort" data-sort="demand_id-asc" aria-hidden="true"></i><i class="triangle-down sort" data-sort="demand_id-desc" aria-hidden="true"></i>
                </th>
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.official_corp_name')
                    <i class="triangle-up mr-1 sort" data-sort="official_corp_name-asc" aria-hidden="true"></i><i class="triangle-down sort" data-sort="official_corp_name-desc" aria-hidden="true"></i>
                </th>
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.genre_name')
                    <i class="triangle-up mr-1 sort" data-sort="genre_name-asc" aria-hidden="true"></i><i class="triangle-down sort" data-sort="genre_name-desc" aria-hidden="true"></i>
                </th>
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.commission_id_tbl')
                    <i class="triangle-up mr-1 sort" data-sort="commission_id-asc" aria-hidden="true"></i><i class="triangle-down sort" data-sort="commission_id-desc" aria-hidden="true"></i>
                </th>
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.jbr_order_no')
                    <i class="triangle-up mr-1 sort" data-sort="jbr_order_no-asc" aria-hidden="true"></i><i class="triangle-down sort" data-sort="jbr_order_no-desc" aria-hidden="true"></i>
                </th>
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.customer_name')</th>
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.complete_date')
                    <i class="triangle-up mr-1 sort" data-sort="complete_date-asc" aria-hidden="true"></i><i class="triangle-down sort" data-sort="complete_date-desc" aria-hidden="true"></i>
                </th>
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.construction_price_tax_include')</th>
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.MItem_item_name_tbl')</th>
                <th class="p-1 fix-w-100 align-middle">@lang('jbr_receipt_follow.MItem2_item_name_tbl')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($results as $item)
            <tr class="text-center">
                <td class="p-1 fix-w-100 text-wrap align-middle"><a href="{{ route('demand.detail', ['id' => $item->demand_id]) }}" class="highlight-link">{{ $item->demand_id }}</a></td>
                <td class="p-1 fix-w-100 text-wrap align-middle text-left"><a href="{{ route('affiliation.detail.edit', ['id' => $item->m_corps_id]) }}" class="highlight-link">{{ $item->official_corp_name }}</a></td>
                <td class="p-1 fix-w-100 text-wrap align-middle text-left">{{ $item->genre_name }}</td>
                <td class="p-1 fix-w-100 text-wrap align-middle"><a href="{{ route('commission.detail', ['id' => $item->commission_id]) }}" class="highlight-link">{{ $item->commission_id }}</a></td>
                <td class="p-1 fix-w-100 text-wrap align-middle">{{ $item->jbr_order_no }}</td>
                <td class="p-1 fix-w-100 text-wrap align-middle text-left">{{ $item->customer_name }}</td>
                <td class="p-1 fix-w-100 text-wrap align-middle">{{ $item->complete_date }}</td>
                <td class="p-1 fix-w-100 text-wrap align-middle text-right">{{ yenFormatJbr($item->construction_price_tax_include) }}</td>
                <td class="p-1 fix-w-100 text-wrap align-middle text-left">
                    @if ( empty($item['MItem_item_name']) )
                        @switch ($item['genre_id'])
                            @case(676)
                                {{ getDropText( trans('report_jbr.JBR_ESTIMATE_STATUS'), 1) }}
                            @break
                                @case(679)
                                {{ '' }}
                            @break
                                @default
                                {{ '' }}
                            @break
                        @endswitch
                    @else
                        {{ $item['MItem_item_name'] }}
                    @endif
                </td>
                <td class="p-1 fix-w-100 text-wrap align-middle text-left">
                    @if ( empty($item['MItem2_item_name']) )
                        @switch ($item['genre_id'])
                            @case(676)
                                {{ getDropText(trans('report_jbr.JBR_RECEIPT_STATUS'), 1) }}
                                @break
                            @case(679)
                                {{ '' }}
                                @break
                            @default
                                {{ getDropText(trans('report_jbr.JBR_RECEIPT_STATUS'), 1) }}
                            @break
                        @endswitch
                    @else
                        {{ $item['MItem2_item_name'] }}
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
<div class="pseudo-scroll-bar" data-display="false">
    <div class="scroll-bar"></div>
</div>
@if ($results->count())
    {{ $results->links('report.components.component_paginate_jbr_receipt') }}
@endif
