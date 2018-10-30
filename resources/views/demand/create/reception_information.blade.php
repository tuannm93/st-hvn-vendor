<div class="col-12 col-lg-6 mb-4">
     <h6 class="form-note font-weight-bold mt-0 mb-3">
            受付情報
    </h6>
    <div class="form-box bg-yellow p-4 border">

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">受付者</label>
            <div class="col-sm-10">
                {!! Form::select('demandInfo[receptionist]', $userDropDownList, Auth::user()->id, ['class' => 'form-control']) !!}

                @if ($errors->has('demandInfo.receptionist'))
                <label class="invalid-feedback d-block">{{$errors->first('demandInfo.receptionist')}}</label>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.site_name')</label>
            <div class="col-sm-10">

                {!! Form::select('demandInfo[site_id]', $mSiteDropDownList, ($ctiDemand) ? $ctiDemand['site_id'] : old('demandInfo[site_id]'), ['class' => 'form-control is-required', 'id' => 'site_id', 'data-rules' => 'not-empty']) !!}
                @if ($errors->has('demandInfo.site_id'))
                <label class="invalid-feedback d-block">{{$errors->first('demandInfo.site_id')}}</label>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.genre')</label>
            <div class="col-sm-10 d-flex flex-column" style="display: inherit">
                {!! Form::select('demandInfo[genre_id]', !old('demandInfo')['site_id'] ? config('constant.defaultOption') : $genresDropDownList, old('demandInfo')['genre_id'], ['class' => 'form-control is-required multiple_check_filter w-100', 'id' => 'genre_id', 'data-rules' => 'not-empty']) !!}

                @if ($errors->has('demandInfo.genre_id'))
                <label class="invalid-feedback d-block">{{$errors->first('demandInfo.genre_id')}}</label>
                @endif
            </div>
        </div>
        {!! Form::hidden('before_demandinfo_genre_id', '', ['id' => 'before_demandinfo_genre_id']) !!}
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.category')</label>
            <div class="col-sm-10">
                {!! Form::select('demandInfo[category_id]', $categoriesDropDownList, old('demandInfo')['category_id'], ['class' => 'form-control is-required', 'id' => 'category_id', 'data-rules' => 'not-empty']) !!}

                @if ($errors->has('demandInfo.category_id'))
                <label class="invalid-feedback d-block">{{$errors->first('demandInfo.category_id')}}</label>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">&nbsp;</label>
            <div class="col-sm-10">
                <input type="hidden" id="hidSiteUrl" value="{{ old('hidSiteUrl') }}" name='hidSiteUrl' />
                <a href="#" target="_blank" class="text--orange" id="site_url">{{ old('hidSiteUrl') }}</a>
            </div>
        </div>
        <div class="form-group row commission_type" style="{{ old('demandInfo')['commission_type_data'] ? 'display: flex;' : 'display: none;'}}">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.order_form')</label>
            <div class="col-sm-10">
                {{--<input type="text" class="form-control-plaintext" id="commission_type_data" placeholder="">--}}
                <span id="commission_type_data" style="line-height: 30px">{{ old('demandInfo')['commission_type_data'] }}</span>
                {!! Form::hidden('demandInfo[commission_type_data]', '', ['id' => 'commission_type_data_hidden']) !!}
            </div>
        </div>
    </div>
</div>
<div class="col-12 col-lg-6 mb-4">
    <h6 class="form-note font-weight-bold mt-0 mb-3">
        @lang('demand_detail.cross_sel')
    </h6>
    <div class="form-box bg-yellow p-4 border">

        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.originall_site')</label>
            <div class="col-sm-10">
                {!! Form::select("demandInfo[cross_sell_source_site]", $mSiteDropDownList, '', ['disabled' => true, 'class' => 'form-control is-required', 'id' => 'cross_sell_source_site']) !!}

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
                {!! Form::select("demandInfo[cross_sell_source_genre]", config('constant.defaultOption'), old('demandInfo[cross_sell_source_genre]'), ['disabled' => true, 'class' => 'form-control is-required', 'id' => 'cross_sell_source_genre']) !!}

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
                {!! Form::text('demandInfo[source_demand_id]', old('demandInfo[source_demand_id]'), ['class' => 'form-control is-required', 'id' => 'source_demand_id', 'data-rules' => 'valid-number']) !!}

                @if (Session::has('demand_errors.check_source_demand_id_not_empty'))
                <label class="invalid-feedback d-block">{{Session::get('demand_errors.check_source_demand_id_not_empty')}}</label>
                @elseif ($errors->has('demandInfo.source_demand_id'))
                <label class="invalid-feedback d-block">{{$errors->first('demandInfo.source_demand_id')}}</label>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">@lang('demand_detail.custome_url')</label>
            <div class="col-sm-10">
                @if(!empty(old('demandInfo')['source_demand_id']))
                    <a href="{{ route('demand.detail', old('demandInfo')['source_demand_id']) }}" class="text--orange" target="_blank">
                        {{ route('homepage') . '/' . old('demandInfo')['source_demand_id'] }}
                    </a>
                @else
                    {!! Form::text('demandInfo[same_customer_demand_url]', '', ['class' => 'form-control is-required', 'data-rules' => 'valid-url']) !!}
                @endif

            </div>
        </div>

    </div>
</div>

