
<div class="description bg-gray-light border-gray p-2 my-3">
    <p class="mb-2">{{ __('demand_detail.description') }}</p>
    <div class="mb-2">
        <div class="custom-control custom-radio custom-control-inline">
            <input class="add_flg custom-control-input" type="radio" id="add_flg_1" name="ProgDemandInfoOther[add_flg]"
                   @if(isset($data['ProgDemandInfoOther'][0]['add_flg']))
                       @if($data['ProgDemandInfoOther'][0]['add_flg'] != 2)
                       checked
                       @endif
                   @else
                       checked
                   @endif
                   value="1">
            <label class="custom-control-label" for="add_flg_1">{{ __('demand_detail.yes') }}</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            <input class="add_flg custom-control-input" type="radio" id="add_flg_2" name="ProgDemandInfoOther[add_flg]"
                   @if(isset($data['ProgDemandInfoOther'][0]['add_flg']))
                   @if($data['ProgDemandInfoOther'][0]['add_flg'] == 2) checked @endif
                   @endif
                   value="2">
            <label class="custom-control-label" for="add_flg_2">{{ __('demand_detail.no') }}</label>
        </div>
        <label class="mb-0 text-danger">{{ __('demand_detail.required_selection') }}</label>
    </div>
    <p class="mb-0">{{ __('demand_detail.describe_below_label') }}</p>
    <p class="mb-0">{{ __('demand_detail.description_label') }}</p>
</div>

<div id="addBlock" class="custom-scroll-x">
    <table class="table custom-table" id="table_add_demand_detail">
        <thead>
            <tr class="text-center bg-yellow-light">
                <th class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.proposal_number') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.customer_name') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.content') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.situation') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.completed_date') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.amount') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.attribute') }}</th>
                <th class="p-1 align-middle border-bottom-bold fix-w-100">{{ __('demand_detail.remarks') }}</th>
            </tr>
        </thead>
        <tbody id="addTable_body">
        @for($i = 0; $i < config('datacustom.limit_prog_add_demand_info'); $i++)
            <tr class="add_demand_detail" data-row="{{ $i }}" id="add_demand_detail_{{ $i }}"
                @if($i != 0)
                    @if(empty($data['ProgAddDemandInfo'][$i]['display'])) style="display: none" @endif
                @endif>
                <input type="hidden" name="ProgAddDemandInfo[{{ $i }}][display]"
                    @if($i == 0)
                        class="disp_val first-cmn-row"
                        value="1"
                    @else
                       class="disp_val"
                       @if(!empty($data['ProgAddDemandInfo'][$i]['display'])) value="1" @endif
                    @endif
                >
                <input type="hidden" name="ProgAddDemandInfo[{{ $i }}][sequence]"   value="{{ $i }}">
                <td class="p-1 align-middle border-bottom fix-w-100">
                    <input type="text" name="ProgAddDemandInfo[{{ $i }}][demand_id_update]" value="@if(isset($data['ProgAddDemandInfo'][$i]['demand_id_update'])){{ $data['ProgAddDemandInfo'][$i]['demand_id_update']}}@endif" class="p-1 form-control addDemandId">
                </td>
                <td class="p-1 align-middle border-bottom fix-w-100">
                    <input type="text" name="ProgAddDemandInfo[{{ $i }}][customer_name_update]" value="@if(isset($data['ProgAddDemandInfo'][$i]['customer_name_update'])){{ $data['ProgAddDemandInfo'][$i]['customer_name_update']}}@endif" class="p-1 form-control">
                </td>
                <td class="p-1 align-middle border-bottom fix-w-100">
                    <input type="text" name="ProgAddDemandInfo[{{ $i }}][category_name_update]" value="@if(isset($data['ProgAddDemandInfo'][$i]['category_name_update'])){{ $data['ProgAddDemandInfo'][$i]['category_name_update']}}@endif" class="p-1 form-control">
                </td>
                <td class="p-1 align-middle border-bottom fix-w-100">
                    <select name="ProgAddDemandInfo[{{ $i }}][commission_status_update]" class="p-1 form-control fix-height-select">
                        @foreach($commissionStatus as $key => $value)
                            <option @if($key != 0) value="{{ $key }}" @else value="" @endif
                                    @if(isset($data['ProgAddDemandInfo'][$i]['commission_status_update']))
                                    @if($data['ProgAddDemandInfo'][$i]['commission_status_update'] == $key) selected @endif
                                    @endif
                            >{{ $value }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="p-1 align-middle border-bottom fix-w-100">
                    <input  type="text" name="ProgAddDemandInfo[{{ $i }}][complete_date_update]" value="@if(isset($data['ProgAddDemandInfo'][$i]['complete_date_update'])){{$data['ProgAddDemandInfo'][$i]['complete_date_update']}}@endif" class="datepicker_limit p-1 form-control">
                </td>
                <td class="p-1 align-middle border-bottom fix-w-100">
                    <div class="align-items-center d-flex text-right">
                        <input maxlength="255" type="text" id="construction_price_tax_exclude_update" class="form-control p-1 totalCost" name="ProgAddDemandInfo[{{ $i }}][construction_price_tax_exclude_update]" value="@if(isset($data['ProgAddDemandInfo'][$i]['construction_price_tax_exclude_update'])){{$data['ProgAddDemandInfo'][$i]['construction_price_tax_exclude_update']}}@endif"/>
                        <strong class="ml-1">円</strong>
                    </div>
                </td>
                <td class="p-1 align-middle border-bottom fix-w-100 radio-group">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="ProgAddDemandInfo{{ $i }}_1" name="ProgAddDemandInfo[{{ $i }}][demand_type_update]" class="addDemandType custom-control-input" value="1"
                                @if(isset($data['ProgAddDemandInfo'][$i]['demand_type_update']))
                                    @if($data['ProgAddDemandInfo'][$i]['demand_type_update'] == 1) checked @endif
                                @endif>
                        <label class="custom-control-label" for="ProgAddDemandInfo{{ $i }}_1">{{ __('demand_detail.recovery_opportunity') }}</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="ProgAddDemandInfo{{ $i }}_2" name="ProgAddDemandInfo[{{ $i }}][demand_type_update]" class="addDemandType custom-control-input" value="2"
                                @if(isset($data['ProgAddDemandInfo'][$i]['demand_type_update']))
                                    @if($data['ProgAddDemandInfo'][$i]['demand_type_update'] == 2) checked @endif
                                @endif>
                        <label class="custom-control-label" for="ProgAddDemandInfo{{ $i }}_2">{{ __('demand_detail.additional_construction_long') }}</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="ProgAddDemandInfo{{ $i }}_3" name="ProgAddDemandInfo[{{ $i }}][demand_type_update]" class="addDemandType custom-control-input" value="3"
                                @if(isset($data['ProgAddDemandInfo'][$i]['demand_type_update']))
                                    @if($data['ProgAddDemandInfo'][$i]['demand_type_update'] == 3) checked @endif
                                @endif>
                        <label class="custom-control-label" for="ProgAddDemandInfo{{ $i }}_3">{{ __('demand_detail.other') }}</label>
                    </div>
                    <div class="err-radio-group" hidden>@lang('demand_detail.required_mess')</div>
                </td>
                <td class="p-1 align-middle border-bottom fix-w-100">
                    <textarea id="add_comment" class="p-1 form-control addComment" name="ProgAddDemandInfo[{{ $i }}][comment_update]">@if(isset($data['ProgAddDemandInfo'][$i]['comment_update'])){{$data['ProgAddDemandInfo'][$i]['comment_update']}}@endif</textarea>
                </td>
            </tr>
        @endfor
        </tbody>
    </table>
    <button type="button" class="btn btn--gradient-orange remove-effect-btn" id="addDemandButton">{{ __('demand_detail.add_demand_button') }}</button>
    <button type="button" class="btn btn--gradient-gray remove-effect-btn" id="removeDemandButton">{{ __('demand_detail.remove_demand_button') }}</button>
</div>
