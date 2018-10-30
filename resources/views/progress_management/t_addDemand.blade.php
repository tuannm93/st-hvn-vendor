<div class="custom-scroll-x mt-5">
	<table id="addTable" class="table custom-table" id="addTable_body">
		<thead class="text-center">
			<tr>
				<th class="fix-w-100 p-1 border-bottom-bold border-top-0">@lang('progress_management.proposal_number')</th>
				<th class="fix-w-150 p-1 border-bottom-bold border-top-0">@lang('progress_management.custome_name')</th>
				<th class="fix-w-150 p-1 border-bottom-bold border-top-0">@lang('progress_management.content')</th>
				<th class="fix-w-100 p-1 border-bottom-bold border-top-0">@lang('progress_management.situation')</th>
				<th class="fix-w-100 p-1 border-bottom-bold border-top-0">@lang('progress_management.construction_completion_date')</th>
				<th class="fix-w-150 p-1 border-bottom-bold border-top-0">@lang('progress_management.construction_amount')(@lang('progress_management.tax_not_inc'))</th>
				<th class="fix-w-200 p-1 border-bottom-bold border-top-0">@lang('progress_management.opportunity_attributes')</th>
				<th class="fix-w-100 p-1 border-bottom-bold border-top-0">@lang('progress_management.remark_column')</th>
			</tr>
		</thead>
		<tbody id="addTable_body">
			@if(!($hidClass != '' && $addDemandData->count() == 0))
				@for($i = 0; $i < $limitAddDemand; $i ++)
					@php

						$display = (($i < $addDemandData->count() || $i == 0) ? '' : 'display:none;');
						$demandIdUpdate = isset($addDemandData[$i]) ? $addDemandData[$i]->demand_id_update : '';
						$customerUpdate = isset($addDemandData[$i]) ? $addDemandData[$i]->customer_name_update : '';
						$categoryNameUpdate = isset($addDemandData[$i]) ? $addDemandData[$i]->category_name_update : '';
						$commissionStatusUpdate = isset($addDemandData[$i]) ? $addDemandData[$i]->commission_status_update : null;
						$completeDateUpdate = isset($addDemandData[$i]) ? $addDemandData[$i]->complete_date_update : null;
						$taxUpdate = isset($addDemandData[$i]) ? $addDemandData[$i]->construction_price_tax_exclude_update : null;
						$demandTUpdate = isset($addDemandData[$i]) ? $addDemandData[$i]->demand_type_update : null;
						$commentUpdate = isset($addDemandData[$i]) ? $addDemandData[$i]->comment_update : '';
						$addDemanId = isset($addDemandData[$i]) ? $addDemandData[$i]['id'] : '';
					@endphp
					<tr id="tr_{{ $i }}" style="{{ $display }}" class="bg-yellow-light addRow" dId='{{ $demandIdUpdate }}' addDemanId="{{ $addDemanId }}">
						<td class="fix-w-100 p-1 border-top-0">
							@if(empty($hidClass))
							{{  Form::text('demand_id_update' . $i, $demandIdUpdate, ['id' => 'demand_id_update' . $i, 'class'=>'addDemandId form-control p-1 ']) }}
							@else
								{{ $demandIdUpdate }}
							@endif
						</td>
						<td class="fix-w-150 p-1 border-top-0">
							@if(empty($hidClass))
							{{  Form::text('customer_update' . $i, $customerUpdate, ['id' => 'customer_update' . $i, 'class'=>'form-control p-1  addCus']) }}
							@else
								{{ $customerUpdate }}
							@endif
						</td>
						<td class="fix-w-150 p-1 border-top-0">
							@if(empty($hidClass))
							{{  Form::text('category_name_update' . $i, $categoryNameUpdate, ['id' => 'category_name_update' . $i, 'class'=>'form-control p-1  addCate']) }}
							@else
								{{ $categoryNameUpdate }}
							@endif
						</td>
						<td class="fix-w-100 p-1 border-top-0">
							@if(empty($hidClass))
							{{
								Form::select('commission_status_update' . $i, $commissionStatus, $commissionStatusUpdate, ['id' => 'commission_status_update' . $i, 'class'=>'form-control p-1  addStatus'])
							}}
							@else
								{{
									!empty($pmCommissionStatus[$commissionStatusUpdate]) ?
									$pmCommissionStatus[$commissionStatusUpdate] : ''
								}}
							@endif
						</td>
						<td class="fix-w-100 p-1 border-top-0">
							@if(empty($hidClass))
							{{
								Form::text('complete_date_update' . $i, $completeDateUpdate,
								['class'=>'form-control p-1 datepicker_limit dateLimit  addDate' . $i, 'id' => 'complete_date_update' . $i, 'readonly'=>'true'])
							}}
							@else
								{{ $completeDateUpdate }}
							@endif
						</td>
						<td class="fix-w-150 p-1 border-top-0">
						<div class="align-items-center d-flex">
							@if(empty($hidClass))
							{{
								Form::text('construction_price_tax_exclude_update' . $i,$taxUpdate,
								['id' => 'construction_price_tax_exclude_update' . $i, 'class'=>'form-control w-90 p-1  addTax totalCost'])
							}}
							@else
								{{ $taxUpdate }}
							@endif
							<strong class="ml-1"> @lang('progress_management.yen')</strong>
						</div>
						</td>
						<td class="fix-w-200 p-1 border-top-0">

								@foreach($demandTypeUpdate as $key => $text)
								@php
									$selected = $demandTUpdate == $key ? true : false;
								@endphp
								<label>
									@if(empty($hidClass))
									{{
										Form::radio('demand_type_update' . $i, $key, $selected, ['class' => 'addRadio addDemandType '])
									}}
									{!! $text !!}
									@else
										@if($selected)
											{!! $text !!}
										@endif
									@endif
								</label>
								@endforeach
						</td>
						<td class="fix-w-100 p-1 border-top-0">
							{{
								Form::textarea('comment_update',$commentUpdate,['class'=>'form-control p-1 addComment ', 'rows' => 2, 'cols' => 8, 'id' => 'comment_update' . $i])
							}}
						</td>
					</tr>
				@endfor
			@endif

		</tbody>
	</table>
	{{ Form::button(__('progress_management.add_case'), ['class' => 'btn btn--gradient-default border--btn-gray ' . $hidClass, 'id' => 'addDemandButton']) }}
					{{ Form::button(__('progress_management.delete_item'), ['class' => 'btn btn--gradient-default border--btn-gray ' . $hidClass, 'id' => 'removeDemandButton']) }}
</div>
@if(!($hidClass != '' && $addDemandData->count() == 0))
<div class="text-center my-4">
	{{ Form::button(__('progress_management.add_the_above_case'), ['class' => 'btn btn--gradient-green col-sm-3 py-2 ' , 'id' => 'addDemandSubmit']) }}
</div>
@endif