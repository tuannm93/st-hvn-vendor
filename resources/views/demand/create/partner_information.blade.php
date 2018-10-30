
<div class="form-category mb-4" id="agency_info" style="display: @if(!empty(old('commissionInfo'))) block @else none @endif">
    <div id = 'commissioninfo'>
        @include('demand.create.anchor_top')
        <label class="form-category__label">@lang('demand_detail.partner_information')</label>
        <span class="form-category__sub-label" style="margin-bottom: 0" id="max_limit_num"></span>
        @if($errors->has('error_commit_flg'))
            <div><label class="invalid-feedback d-block">{{ $errors->first('error_commit_flg') }}</label></div>
        @endif
        <span class="form-category__sub-label"></span>
        <div class="form-category__body clearfix" id="bid_infos">
            <button data-url_data="{{ route('commissionselect.index', [
                'data[no]' => -1,
                'data[site_id]' => 1379,
                'data[category_id]' => 500,
                'data[postcode]' => 4500003,
                'data[address1]' => 23,
                'data[address2]' => '名古屋市中村区',
                'data[corp_name]' => '',
                'data[commition_info_count]' => 1,
                'data[exclude_corp_id]' => '1,,,,,,,,,,,,,,',
                'data[genre_id]' => '',

            ]) }}"
                    data-toggle="modal"
                    {{--data-target=".sub-win-modal"--}}

                    id="destination-company"
                    class="btn btn--gradient-default btn--w-normal mb-4"
                    type="button">@lang('demand_detail.selection_destination')</button>
            <div id="auto_commission_message"></div>
        </div>

        <div id="auto_commission_message"></div>
    </div>

    <!--Commission info-->
    <div style="display: @if(old('commissionInfo')) block @else none @endif" id="partner_commission_info">

        @include('demand.create.partner_commission_infor')

    </div>
</div>



