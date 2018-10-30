<div class="form-category mb-4" id="agency_info" style="">
    <div id = 'commissioninfo'>
        @include('demand.create.anchor_top')
        <label class="form-category__label">  @lang('demand_detail.partner_information')
            @if($demand->commission_limitover_time >= 1) {{ $demand->commission_limitover_time_format }}リミット超過 @endif
        </label>
        <span class="form-category__sub-label" style="margin-bottom: 0" id="max_limit_num"></span>

        @if($errors->has('error_commit_flg'))
            <div><label class="invalid-feedback d-block">{{ $errors->first('error_commit_flg') }}</label></div>
        @endif

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
                    data-toggle="modal" id="destination-company"
                    class="btn btn--gradient-default btn--w-normal mb-4"
                    type="button">@lang('demand_detail.selection_destination')</button>
            <div id="auto_commission_message"></div>
        </div>


        <div style="display: block" id="partner_commission_info" class="load_m_corps">
            @if(!empty(old('commissionInfo')))
                <div id="max_index" data-max="{{ count(old('commissionInfo')) }}"></div>
                @foreach(old('commissionInfo') as $key => $commissionInfo)
                    @if(!empty($commissionInfo['corp_id']))
                        @include('demand.partials.old_commission')
                    @endif
                @endforeach
            @else
                <div id="max_index" data-max="{{ count($demand->commissionInfos) }}"></div>
                @forelse($demand->commissionInfos as $key => $commissionInfor)

                    @include('demand.partials.partner_commission_infor')

                @empty @endforelse
            @endif
        </div>
    </div>
    <div id="load_m_corps">

    </div>
</div>


