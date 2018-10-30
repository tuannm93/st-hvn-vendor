@if(isset($demandInfos) && count($demandInfos) > 0)
    <p class="mt-2 mb-0">{{ trans('antisocial_follow.total_number').$demandInfos->total().trans('antisocial_follow.matter') }}</p>
@else
    <p class="mt-2 mb-0">{{ trans('demandlist.not_data') }}</p>
@endif
@if(isset($demandInfos) && count($demandInfos) > 0)
<table class="table custom-border add-pseudo-scroll-bar mb-2" id='table-demandlist'
       data-url='{{ Route::current()->parameter('id') }}' data-csvShow="{{ $showCSV }}" data-corp-name="{{ isset($conditions['corp_name']) ? $conditions['corp_name'] : '' }}"
       data-corp-name-kana="{{ isset($conditions['corp_name_kana']) ? $conditions['corp_name_kana'] : '' }}" data-corp-id="{{ isset($conditions['corp_id']) ? $conditions['corp_id'] : '' }}"
    >
    <thead>
    <tr class="text-center bg-yellow-light">
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.opportunity_id') }}
            <div class="sortInner">
                <i class="triangle-up up sort-idUp mx-1" aria-hidden="true" data-sort-name="demand_infos.id"
                   data-order-by="asc"></i>
                <i class="triangle-down down sort-idDown" aria-hidden="true" data-sort-name="demand_infos.id"
                   data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.urgent') }}
            <div class="sortInner">
                <i class="triangle-up up sort-immediatelyUp mx-1" aria-hidden="true"
                   data-sort-name="demand_infos.immediately" data-order-by="asc"></i>
                <i class="triangle-down down sort-immediatelyDown" aria-hidden="true"
                   data-sort-name="demand_infos.immediately" data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.demand_status') }}
            <div class="sortInner">
                <i class="triangle-up up sort-demandstatusUp mx-1" aria-hidden="true"
                   data-sort-name="demand_infos.demand_status" data-order-by="asc"></i>
                <i class="triangle-down down sort-demandstatusDown" aria-hidden="true"
                   data-sort-name="demand_infos.demand_status" data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.customer_name') }}
            <div class="sortInner">
                <i class="triangle-up up sort-customernameUp mx-1" aria-hidden="true"
                   data-sort-name="demand_infos.customer_name" data-order-by="asc"></i>
                <i class="triangle-down down sort-customernameDown" aria-hidden="true"
                   data-sort-name="demand_infos.customer_name" data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.corporate_name') }}
            <div class="sortInner">
                <i class="triangle-up up sort-customercorpnameUp mx-1" aria-hidden="true"
                   data-sort-name="demand_infos.customer_corp_name" data-order-by="asc"></i>
                <i class="triangle-down down sort-customercorpnameDown" aria-hidden="true"
                   data-sort-name="demand_infos.customer_corp_name" data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.site_name') }}
            <div class="sortInner">
                <i class="triangle-up up sort-sitenameUp mx-1" aria-hidden="true" data-sort-name="demand_infos.site_id"
                   data-order-by="asc"></i>
                <i class="triangle-down down sort-sitenameDowm" aria-hidden="true" data-sort-name="demand_infos.site_id"
                   data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.category') }}
            <div class="sortInner">
                <i class="triangle-up up sort-categorynameUp mx-1" aria-hidden="true"
                   data-sort-name="demand_infos.category_id" data-order-by="asc"></i>
                <i class="triangle-down down sort-categorynameDown" aria-hidden="true"
                   data-sort-name="demand_infos.category_id" data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.JBR_reception_like_no') }}
            <div class="sortInner">
                <i class="triangle-up up sort-jbrordernoUp mx-1" aria-hidden="true"
                   data-sort-name="demand_infos.jbr_order_no" data-order-by="asc"></i>
                <i class="triangle-down down sort-jbrordernoDown" aria-hidden="true"
                   data-sort-name="demand_infos.jbr_order_no" data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.power_receiving') }}
            <div class="sortInner">
                <i class="triangle-up up sort-receivedatetimeUp mx-1" aria-hidden="true"
                   data-sort-name="demand_infos.receive_datetime" data-order-by="asc"></i>
                <i class="triangle-down down sort-receivedatetimeDown" aria-hidden="true"
                   data-sort-name="demand_infos.receive_datetime" data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.contact_deadline_date_and_time') }}
            <div class="sortInner">
                <i class="triangle-up up sort-contactdesiredtimeUp mx-1" aria-hidden="true"
                   data-sort-name="demand_infos.contact_desired_time" data-order-by="asc"></i>
                <i class="triangle-down down sort-contactdesiredtimeDown" aria-hidden="true"
                   data-sort-name="demand_infos.contact_desired_time" data-order-by="desc"></i>
            </div>
        </th>
        <th class="p-1 align-middle fix-w-100">{{ trans('demandlist.selection_method') }}
            <div class="sortInner">
                <i class="triangle-up up sort-selectionsystemUp mx-1" aria-hidden="true"
                   data-sort-name="demand_infos.selection_system" data-order-by="asc"></i>
                <i class="triangle-down down sort-selectionsystemDown" aria-hidden="true"
                   data-sort-name="demand_infos.selection_system" data-order-by="desc"></i>
            </div>
        </th>
    </tr>
    </thead>
    @if(isset($demandInfos) && count($demandInfos) > 0)
        @foreach($demandInfos as $demandInfo)
            <tr @if(isset($demandInfo->DemandInfo__CommissionRank) && $demandInfo->DemandInfo__CommissionRank == 1) class="bg-condition" @endif>
                <td class="p-1 align-middle fix-w-100 text-wrap text-center">
                    <a class="highlight-link" target="_blank"
                       href="{{ route('demand.detail', ['id' => $demandInfo->id]) }}">
                        {{ $demandInfo->id }}
                    </a>
                </td>
                <td class="p-1 align-middle fix-w-100 text-wrap">
                    @if (!empty($demandInfo->immediately))
                        {{ trans('demandlist'.'.'.getDivTextJP('checkbox_div', $demandInfo->immediately)) }}
                    @endif
                </td>
                <td class="p-1 align-middle fix-w-100 text-wrap">{{ getDropText(\App\Repositories\Eloquent\MItemRepository::PROPOSAL_STATUS, $demandInfo->demand_status) }}</td>
                <td class="p-1 align-middle fix-w-100 text-wrap">{{ $demandInfo->customer_name }}</td>
                <td class="p-1 align-middle fix-w-100 text-wrap">{{ $demandInfo->customer_corp_name }}</td>
                <td class="p-1 align-middle fix-w-100 text-wrap">{{ $demandInfo->site_name }}</td>
                <td class="p-1 align-middle fix-w-100 text-wrap">{{ $demandInfo->category_name }}</td>
                <td class="p-1 align-middle fix-w-100 text-wrap">{{ $demandInfo->jbr_order_no }}</td>
                <td class="p-1 align-middle fix-w-100 text-wrap text-center">{{ dateTimeFormat($demandInfo->receive_datetime) }}</td>
                <td class="p-1 align-middle fix-w-100 text-wrap text-center">{!! getContactDesiredTime($demandInfo) !!}</td>
                <td class="p-1 align-middle fix-w-100 text-wrap">
                    @php
                        if ($demandInfo->selection_system == 2) {
                            echo trans('demandlist.bidding_ceremony_manual');
                        }elseif ($demandInfo->selection_system == 3){
                            echo trans('demandlist.bidding_ceremony_automatic');
                        }elseif ($demandInfo->selection_system == 4) {
                            echo trans('demandlist.automatic');
                        }else {
                            echo trans('demandlist.manual');
                        }
                    @endphp
                </td>
            </tr>
        @endforeach
    @endif
</table>
<div class="pseudo-scroll-bar" data-display="false">
    <div class="scroll-bar"></div>
</div>
@endif
@if(!empty($demandInfos) && $demandInfos->lastPage() > 1)
        {{ $demandInfos->links('pagination.nextprevajax') }}
@endif