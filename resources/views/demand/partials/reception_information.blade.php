<div class="col-12 col-lg-6 mb-4">
    <h6 class="form-note font-weight-bold mt-0 mb-3">
            @lang('demand_detail.reception_information')
    </h6>

    <div class="form-box bg-yellow p-4 border">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">  @lang('demand_detail.reception')</label>
            <div class="col-sm-10">
                {!! Form::select('demandInfo[receptionist]', $userDropDownList, $demand->receptionist ?? Auth::user()->id, ['class' => 'form-control']) !!}
                @if ($errors->has('demandInfo.receptionist'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.receptionist')}}</label>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.site_name')</label>
            <div class="col-sm-10">
                @if(isset($copy))
                    {!! Form::select('demandInfo[site_id]', $mSiteDropDownList, old('demandInfo[site_id]') ?? $demand->site_id, ['class' => 'form-control is-required', 'id' => 'site_id', 'data-rules' => 'not-empty']) !!}
                @else
                    <span style="line-height: 30px">{{ isset($demand->m_site_name) ? $demand->m_site_name : '' }}</span>
                    {!! Form::hidden('demandInfo[site_id]', isset($demand->site_id ) ? $demand->site_id : '', ['id' => 'site_id']) !!}
                @endif
                @if ($errors->has('demandInfo.site_id'))
                        <label class="invalid-feedback d-block">{{$errors->first('demandInfo.site_id')}}</label>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.genre')</label>
            <div class="col-sm-10" style="">
                @if(!isset($cross))
                    @if(!in_array($demand->demand_status, [4, 5]))
                        {!! Form::select('demandInfo[genre_id]', $genresDropDownList, $demand->genre_id, ['class' => 'form-control  multiple_check_filter w-100', 'id' => 'genre_id', 'style' => 'display: none']) !!}
                    @else
                        {{ $genresDropDownList[$demand->genre_id] }}
                        <input type="hidden" name = 'demandInfo[genre_id]' value="{{ $demand->genre_id }}" id = 'genre_id' />
                    @endif
                    <span style="line-height: 30px;">
                        @if(isset($rankList[$demand->genre_id]))
                            {{ "\t" . $genresDropDownList[$demand->genre_id] }}  {{__('demand_detail.assignment_rank') . ' '. $rankList[$demand->genre_id] }}
                            <input type="hidden" name = 'demandInfo[commission_rank]' value="{{ $demand->commission_rank }}" id = 'commission_rank' />
                        @else
                            -
                        @endif
                    </span>
                @else
                    {!! Form::select('demandInfo[genre_id]', $genresDropDownList, $demand->genre_id, ['class' => 'form-control  multiple_check_filter w-100', 'id' => 'genre_id', 'style' => 'display: none']) !!}
                @endif

                @if ($errors->has('demandInfo.genre_id'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.genre_id')}}</label>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.category')</label>
            <div class="col-sm-10">
                @if(!in_array($demand->demand_status, [getDivValue('demand_status', 'telephone_already'), getDivValue('demand_status', 'information_sent')]))
                    {!! Form::select('demandInfo[category_id]', $categoriesDropDownList, $demand->category_id,
                    ['class' => 'form-control', 'id' => 'category_id']) !!}
                @else
                    {!! Form::hidden('demandInfo[category_id]', $demand->category_id, ['id' => 'category_id']) !!}
                    <span style="line-height: 30px;">{{ in_array($demand->category_id, array_keys($categoriesDropDownList)) ? $categoriesDropDownList[$demand->category_id] : '' }}</span>
                @endif
                @if ($errors->has('demandInfo.category_id'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.category_id')}}</label>
                @endif
                {!! Form::hidden('demandInfo[commission_type_div]', '', ['class' => 'commission_type_div']) !!}
                <div><a href="#" target="_blank" class="text--orange" id="site_url"></a></div>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.order_form')</label>
            <div class="col-sm-10">
                <span style="line-height: 30px;" id="commission_type_data"></span>
            </div>
        </div>
        {!! Form::hidden('demandInfo[before_demandinfo_genre_id]', isset($demand->genre_id) ? $demand->genre_id : 0, ['id' => 'before_demandinfo_genre_id']) !!}
    </div>
</div>
<div class="col-12 col-lg-6 mb-4">
    <h6 class="form-note font-weight-bold mt-0 mb-3">
            @lang('demand_detail.additional_construction') @lang('demand_detail.crosssell_information')
    </h6>
    <div class="form-box bg-yellow p-4 border">

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label"> @lang('demand_detail.originall_site') </label>
            <div class="col-sm-10">
                {!! Form::select("demandInfo[cross_sell_source_site]", $mSiteDropDownList, old('cross_sell_source_site') ? old('cross_sell_source_site') : $demand->cross_sell_source_site, ['disabled' => !($enableSiteId || isset($cross)), 'class' => 'form-control', 'id' => 'cross_sell_source_site']) !!}
                @if (Session::has('demand_errors.check_cross_sell_site_not_empty'))
                    <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_cross_sell_site_not_empty')}}</label>
                @elseif ($errors->has('demandInfo.cross_sell_source_site'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.cross_sell_source_site')}}</label>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.originall_genre')</label>
            <div class="col-sm-10">
                {!! Form::select("demandInfo[cross_sell_source_genre]", $mSiteGenresDropDownList, old('cross_sell_source_genre') ? old('cross_sell_source_genre') : $demand->cross_sell_source_genre, ['disabled' => !($enableSiteId || isset($cross)), 'class' => 'form-control', 'id' => 'cross_sell_source_genre']) !!}
                @if (Session::has('demand_errors.check_cross_sell_genre_not_empty'))
                    <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_cross_sell_genre_not_empty')}}</label>
                @elseif ($errors->has('demandInfo.cross_sell_source_genre'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.cross_sell_source_genre')}}</label>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.original_proposal_number')</label>
            <div class="col-sm-10">
                {!! Form::text('demandInfo[source_demand_id]', $demand->source_demand_id, ['class' => 'form-control', 'id' => 'source_demand_id']) !!}
                @if (Session::has('demand_errors.check_source_demand_id_not_empty'))
                    <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_source_demand_id_not_empty')}}</label>
                @elseif ($errors->has('demandInfo.source_demand_id'))
                    <label class="invalid-feedback d-block">{{$errors->first('demandInfo.source_demand_id')}}</label>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.identical_customer_case')@lang('demand_detail.url')</label>
            <div class="col-sm-10">
                @if(!isset($cross))
                    {!! Form::text('demandInfo[same_customer_demand_url]', $demand->same_customer_demand_url, ['class' => 'form-control is-required', 'data-rules' => 'valid-url']) !!}
                @else
                    <a class="text--orange ml-2 w-50" href="{{ $demand->same_customer_demand_url }}">{{ $demand->same_customer_demand_url }}</a>
                @endif
            </div>
        </div>

    </div>
</div>

