<div class="custom-scroll-x">
    <table class="custom-table add-pseudo-scroll-bar">
        <thead class="text-center bg-yellow-light">
            <tr>
                <th rowspan="2" class="fix-w-100 border-top-bold border-bottom-bold p-1">@lang('progress_management.reception_date_and_time')</th>
                <th rowspan="2" class="fix-w-100 border-top-bold border-bottom-bold p-1">@lang('progress_management.proposal_number')<br>@lang('progress_management.custome_name')</th>
                <th rowspan="2" class="fix-w-50 border-top-bold border-bottom-bold p-1">@lang('progress_management.content')</th>
                <th rowspan="2" class="fix-w-50 border-top-bold border-bottom-bold p-1">@lang('progress_management.commission_rate')<br />
                    (@lang('progress_management.commission_amount'))
                </th>
                <th colspan="3" class="border-top-bold p-1">@lang('progress_management.management_status_of_our_company')</th>
                <th class="border-top-bold">@lang('progress_management.required_item')</th>
                <th rowspan="2" class="fix-w-100 border-top-bold border-bottom-bold p-1">@lang('progress_management.status_after_change')</th>
                <th rowspan="2" class="fix-w-100 border-top-bold border-bottom-bold p-1">@lang('progress_management.construction_completion_date')<br />
                    @lang('progress_management.missing_date')
                </th>
                <th rowspan="2" class="fix-w-100 border-top-bold border-bottom-bold p-1">
                    @lang('progress_management.construction_amount_tax_ex')
                    <br/>
                    @lang('progress_management.construction_amount_tax_in')
                </th>
                <th rowspan="2" class="fix-w-100 border-top-bold border-bottom-bold p-1">@lang('progress_management.result_losing')</th>
                <th rowspan="2" class="fix-w-50 border-top-bold border-bottom-bold p-1">@lang('progress_management.remark_column')</th>
                <th rowspan="2" class="border-top-bold border-bottom-bold p-1">@lang('progress_management.update')</th>
                <th rowspan="2" class="border-top-bold border-bottom-bold p-1">@lang('progress_management.reacquire')</th>
            </tr>
            <tr>
                <th class="fix-w-100 border-bottom-bold p-1 bg-white-light">
                    @lang('progress_management.construction_completion_date')<br>
                    @lang('progress_management.missing_date')
            </th>
                <th class="fix-w-100 border-bottom-bold p-1 bg-white-light">
                    @lang('progress_management.construction_amount')(@lang('progress_management.tax_exclude'))<br />
                    @lang('progress_management.construction_amount')(@lang('progress_management.tax_include'))<br />
                    @lang('progress_management.commission_amount_2')
                </th>
                <th class="fix-w-50 border-bottom-bold p-1 bg-white-light">@lang('progress_management.our_company')<br />@lang('progress_management.management_status')</th>
                <th class="fix-w-100 border-bottom-bold p-1">@lang('progress_management.in_our_management_situation')<br>@lang('progress_management.is_there_any_change')？</th>
            </tr>
            <tr style="display:none;"></tr>
        </thead>
        <tbody class="custom-scroll-y">
            @foreach($pDemandInfos as $pDemanInfo)
                <tr class='pDemanInfoRow' dataId="{{ $pDemanInfo->id }}" orgStatus="{{ $pDemanInfo->commission_status }}">
                    @if(!empty($hidClass))
                    {{ Form::hidden('diff_flg', $pDemanInfo->diff_flg, ['id'=>'diff_flg_' . $pDemanInfo->id]) }}
                    {{ Form::hidden('commission_status_update', $pDemanInfo->commission_status_update,
                        ['id'=>'commission_status_update_' .  $pDemanInfo->id]) }}
                    {{ Form::hidden('complete_date_update', $pDemanInfo->complete_date_update,
                        ['id'=>'complete_date_update_' .  $pDemanInfo->id]) }}
                    {{ Form::hidden('construction_price_tax_exclude_update', $pDemanInfo->construction_price_tax_exclude_update,
                        ['id'=>'construction_price_tax_exclude_update_' .  $pDemanInfo->id]) }}
                    {{ Form::hidden('construction_price_tax_include_update', $pDemanInfo->construction_price_tax_include_update,
                        ['id' => 'construction_price_tax_include_update_' .  $pDemanInfo->id]) }}
                    {{ Form::hidden('commission_order_fail_reason_update', $pDemanInfo->commission_order_fail_reason_update,
                        ['id'=>'commission_order_fail_reason_update_' .  $pDemanInfo->id]) }}
                    @endif
                    <td class="fix-w-100 p-1 border-bottom text-center">
                        {!! dateTimeWeek($pDemanInfo->receive_datetime, '%Y/%m/%d<br>%H:%M') !!}
                    </td>
                    <td class="fix-w-100 p-1 border-bottom text-wrap">
                        {{ $pDemanInfo->demand_id }}<br>
                        {{ $pDemanInfo->customer_name }}
                    </td>
                    <td class="fix-w-50 p-1 border-bottom">{{ $pDemanInfo->category_name }}</td>
                    <td class="fix-w-50 p-1 border-bottom text-center">
                        @if (!empty($pDemanInfo->fee))
                            {{ $pDemanInfo->fee }} @lang('progress_management.yen')
                        @elseif(!empty($pDemanInfo->fee_rate))
                            {{ $pDemanInfo->fee_rate }}%
                        @else
                        @endif
                    </td>
                    <td class="fix-w-100 p-1 border-bottom text-wrap">
                        {{ !empty($pDemanInfo->complete_date) ? date('Y/m/d', strtotime($pDemanInfo->complete_date)) : '' }}
                    </td>
                    <td class="fix-w-100 p-1 border-bottom text-right">
                        {{
                            !empty($pDemanInfo->construction_price_tax_exclude) ?
                            $pDemanInfo->construction_price_tax_exclude : '-'
                        }} @lang('progress_management.yen')<br>
                        {{
                            !empty($pDemanInfo->construction_price_tax_include) ?
                            $pDemanInfo->construction_price_tax_include : '-'
                        }} @lang('progress_management.yen')<br>
                        {{
                            !empty($pDemanInfo->fee_target_price) ?
                            $pDemanInfo->fee_target_price : '-'
                        }} @lang('progress_management.yen')
                    </td>
                    <td class="fix-w-50 p-1 border-bottom text-center">
                        {{ !empty($commissionStatus[$pDemanInfo->commission_status])?
                            $commissionStatus[$pDemanInfo->commission_status]:"" }}
                    </td>
                    <td class="fix-w-100 p-1 border-bottom">
                        @if(empty($hidClass))
                        {{ Form::select('diff_flg', $diffFllags, $pDemanInfo->diff_flg, ['id' => 'diff_flg_' . $pDemanInfo->id, 'class' => 'diff form-control p-1 ', 'dId' => $pDemanInfo->id, 'old' => $pDemanInfo->diff_flg]) }}
                        @else
                            {{ isset($diffFllags[$pDemanInfo->diff_flg]) ? $diffFllags[$pDemanInfo->diff_flg]: '' }}
                        @endif
                    </td>
                    <td class="fix-w-100 p-1 border-bottom">
                        @if(empty($hidClass))
                            {{ Form::select('commission_status_update', $commissionStatus, $pDemanInfo->commission_status_update, ['placeholder' => '', 'id' => 'commission_status_update_' .  $pDemanInfo->id, 'class' => 'update status form-control p-1 ' .  $hidClass, 'dId' => $pDemanInfo->id, 'first' => 'true', 'old' => $pDemanInfo->commission_status]) }}
                        @elseif(!empty($commissionStatus[$pDemanInfo->commission_status_update]))
                           {{ $commissionStatus[$pDemanInfo->commission_status_update] }}
                        @endif

                    </td>
                    <td class="fix-w-100 p-1 border-bottom">
                        @if(empty($hidClass))
                        {{
                            Form::text('complete_date_update', $pDemanInfo->complete_date_update,
                            ['class'=>'form-control p-1 datepicker_limit update ' . $hidClass, 'id' => 'complete_date_update_' .  $pDemanInfo->id, 'dId' => $pDemanInfo->id, 'old' => $pDemanInfo->complete_date_update, 'readonly'=>'true'])
                        }}
                        @else
                            {{ $pDemanInfo->complete_date_update }}
                        @endif
                    </td>
                    <td class="fix-w-100 p-1 border-bottom align-right">
                        <div class="align-items-center d-flex mb-1 text-right">
                            @if(empty($hidClass))
                            {{
                                Form::text('construction_price_tax_exclude_update', $pDemanInfo->construction_price_tax_exclude_update,
                                ['id' => 'construction_price_tax_exclude_update_' .  $pDemanInfo->id, 'class' => 'update form-control p-1 w-90 totalCost  ' . $hidClass, 'dId' => $pDemanInfo->id, 'old' => $pDemanInfo->construction_price_tax_exclude_update])
                            }}
                            @else
                                {{ $pDemanInfo->construction_price_tax_exclude_update }}
                            @endif
                            <strong class="ml-1">@lang('progress_management.yen')</strong>
                        </div>
                        <div class="align-items-center d-flex ">
                            @php
                                $totalCost = $pDemanInfo->construction_price_tax_exclude_update;
                                $totalCostTaxInclude = $totalCost?round($totalCost * (1 + $tax/100)):"";
                            @endphp
                            @if(empty($hidClass))
                            {{
                                Form::text('construction_price_tax_include_update',  $totalCostTaxInclude,
                                ['id' => 'construction_price_tax_include_update_' .  $pDemanInfo->id,
                                'class' =>'form-control p-1 w-90 totalCostTaxInclude' . $hidClass, '
                                    dId' => $pDemanInfo->id, 'disabled'=>'disabled'])
                            }}
                            @else
                                {{ $totalCostTaxInclude }}
                            @endif
                            <strong class="ml-1">@lang('progress_management.yen')</strong>
                        </div>
                    </td>
                    <td class="fix-w-100 p-1 border-bottom">
                        @if(empty($hidClass))
                        {{
                            Form::select('commission_order_fail_reason_update', $commissionOrderFailReasonUpdate, $pDemanInfo->commission_order_fail_reason_update, ['id' => 'commission_order_fail_reason_update_' .  $pDemanInfo->id, 'class' =>'update failReason form-control p-1 ' . $hidClass, 'dId' => $pDemanInfo->id, 'old' => $pDemanInfo->commission_order_fail_reason_update])
                        }}
                        @else
                        {{ isset($commissionOrderFailReasonUpdate[$pDemanInfo->commission_order_fail_reason_update]) ?
                            $commissionOrderFailReasonUpdate[$pDemanInfo->commission_order_fail_reason_update] : ''}}
                        @endif
                    </td>
                    <td class="fix-w-100 p-1 border-bottom">
                        {{
                            Form::textarea('comment_update',$pDemanInfo->comment_update,['rows' => 2, 'cols' => '', 'id' => 'comment_update_' .  $pDemanInfo->id, 'class' =>'cComment form-control p-1 txtupdate ', 'dId' => $pDemanInfo->id, 'old' => $pDemanInfo->comment_update])
                        }}
                    </td>
                    <td class="p-1 border-bottom text-center">
                        {{ Form::button(__('progress_management.update'), ['class' => 'btn btn--gradient-orange col-12 updateButton ', 'dId' => $pDemanInfo->id, 'id' =>'btnUpdateDemand' . $pDemanInfo->id]) }}
                    </td>
                    <td class="p-1 border-bottom text-center">
                        {{ Form::button(__('progress_management.reacquire'), ['class' => 'btn btn--gradient-orange col-12 reacquisitionButton ', 'dId' => $pDemanInfo->id, 'id' =>'btnreAcqui' . $pDemanInfo->id]) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="pseudo-scroll-bar" data-display="false">
    <div class="scroll-bar"></div>
</div>
<div class="my-4">
    @if($pDemandInfos->previousPageUrl())
        <a href="{{ $pDemandInfos->previousPageUrl() }}" class="highlight-link">< @lang('progress_management.prev_page')</a>
    @elseif($pDemandInfos->lastPage() != 1)
        <span><  @lang('progress_management.prev_page')</span>
    @endif
    &nbsp;
    @if($pDemandInfos->nextPageUrl())
        <a href="{{ $pDemandInfos->nextPageUrl() }}" class="highlight-link">@lang('progress_management.next_page') ></a>
    @elseif($pDemandInfos->lastPage() != 1)
        <span>@lang('progress_management.next_page') ></span>
    @endif
</div>
<div class="mb-4 text-center">
    {{ Form::button(__('progress_management.mass_update'), ['class' => 'btn btn--gradient-orange col-sm-3 py-2 ', 'id' => 'updateAllButton']) }}
</div>