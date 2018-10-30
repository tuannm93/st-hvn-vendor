{{-- Form select filter --}}
<form id="searchForm" action="#" class="fieldset-custom">
    <fieldset>
        <legend>{{ __('report_corp_commission.filter_criteria') }}</legend>
        <div class="bg-update-box border-update-box p-2">
            {{--<div class="form-row">--}}
                {{--<div class="col-lg-2">--}}
                    {{--<button class="btn btn--gradient-default border w-100" type="button" name="search" id="searchReport">@lang('report_corp_commission.enter_name')</button>--}}
                {{--</div>--}}
                {{--<div class="col-lg-6 mt-1 mt-lg-0">--}}
                    {{--<input class="form-control" type="text" size="50" maxlength="50" id="commission_search_name">--}}
                {{--</div>--}}
                {{--<div class="col-lg-3 d-flex flex-column flex-md-row align-self-md-start">--}}
                    {{--<button class="btn btn--gradient-green border mt-1 mt-lg-0" type="button" name="regist" id="registerReport">@lang('report_corp_commission.btn_save')</button>--}}
                    {{--<button class="btn btn--gradient-default border ml-md-1 mt-1 mt-lg-0" type="button" name="delete" id="deleteReport">@lang('report_corp_commission.btn_del')</button>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="d-flex flex-column flex-lg-row mt-2 infor-select">
                <div class="form-group">
                    <div class="box fix-white-space-box">{{ __('report_corp_commission.follow_date') }}</div>
                    <select name="filter.demand_follow_date" id="follow_date" class="form-control w-auto">
                        <option value=""></option>
                        @if(!empty($filterOptions))
                            @foreach($filterOptions as $key => $value)
                                <option value="{{ $key }}" @if(isset($session['filter_demand_follow_date']) && $session['filter_demand_follow_date'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group ml-lg-1">
                    <div class="box fix-white-space-box">{{ __('report_corp_commission.detect_contact_desired_time') }}</div>
                    <select name="filter.detect_contact_desired_time" id="detect_contact_desired_time" class="form-control w-auto">
                        <option value=""></option>
                        @if(!empty($contactRequest))
                            @foreach($contactRequest as $key => $value)
                                <option value="{{ $key }}" @if(isset($session['filter_detect_contact_desired_time']) && $session['filter_detect_contact_desired_time'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group ml-lg-1 d-flex flex-column select-btn-fix">
                    <div class="box fix-white-space-box">
                        {{ __('report_corp_commission.commission_rank') }}
                    </div>
                    <div class="mt-2">
                        <a class="highlight-link fix-width-a" id="genreSelectAnker" href="javascript:void(0)">@lang('report_corp_commission.select_anker')</a>
                    </div>
                    <select name="filter.commission_rank[]" id="commission_rank" multiple="multiple">
                        @if(!empty($genreRank))
                            @foreach($genreRank as $key => $value)
                                <option value="{{ $key }}" @if(isset($session['filter_commission_rank']) && in_array($key, $session['filter_commission_rank'])) selected @endif>{{ $value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group ml-lg-1">
                    <div class="box fix-white-space-box">{{ __('report_corp_commission.site_name') }}</div>
                    <input type="text" name="filter.site_name" id="site_name" class="form-control" value="{{ $session['filter_site_name'] or '' }}">
                </div>
                <div class="form-group ml-lg-1">
                    <div class="box fix-white-space-box">{{ __('report_corp_commission.corp_name') }}</div>
                    <input type="text" name="filter.corp_name" id="corp_name" class="form-control" value="{{ $session['filter_corp_name'] or '' }}">
                </div>
                <div class="form-group ml-lg-1 fix-white-space-box d-flex flex-column select-btn-fix fix-width-a">
                    <div class="box">
                        {{ __('report_corp_commission.holiday') }}
                    </div>
                    <div class="mt-2">
                        <a class="highlight-link" id="dayOfTheWeekSelectAnker" href="javascript:void(0)">@lang('report_corp_commission.select_anker')</a>
                    </div>
                    <select name="filter.holiday[]" id="holiday" multiple="multiple">
                        @if(!empty($dayOfTheWeek))
                            @foreach($dayOfTheWeek as $key => $value)
                                <option value="{{ $key }}"  @if(isset($session['filter_holiday']) && in_array($key, $session['filter_holiday'])) selected @endif>{{ $value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group ml-lg-1">
                    <div class="box fix-white-space-box">{{ __('report_corp_commission.first_commission') }}</div>
                    <select name="filter.first_commission" id="first_commission" class="form-control">
                        <option value=""></option>
                        @if(!empty($filterOptions))
                            @foreach($filterOptions as $key => $value)
                                <option value="{{ $key }}" @if(isset($session['filter_first_commission']) && $session['filter_first_commission'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group ml-lg-1">
                    <div class="box fix-white-space-box">{{ __('report_corp_commission.user_name') }}</div>
                    <input type="text" name="filter.user_name" id="user_name" class="form-control" value="{{ $session['filter_user_name'] or '' }}">
                </div>
                <div class="form-group ml-lg-1">
                    <div class="box fix-white-space-box">{{ __('report_corp_commission.last_update_time') }}</div>
                    <select name="filter.modified" id="modified" class="form-control w-auto">
                        <option value=""></option>
                        @if(!empty($historyUpdate))
                            @foreach($historyUpdate as $key => $value)
                                <option value="{{ $key }}" @if(isset($session['filter_modified']) && $session['filter_modified'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group ml-lg-1 fix-width-selec">
                    <div class="box fix-white-space-box">{{ __('report_corp_commission.auction') }}</div>
                    <select name="filter.auction" id="auction" class="form-control w-auto">
                        <option value=""></option>
                        @if(!empty($filterOptions))
                            @foreach($filterOptions as $key => $value)
                                <option value="{{ $key }}" @if(isset($session['filter_auction']) && $session['filter_auction'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group ml-lg-1">
                    <div class="box fix-white-space-box">{{ __('report_corp_commission.cross_sell_implement') }}</div>
                    <select name="filter.cross_sell_implement" id="cross_sell_implement" class="form-control">
                        <option value=""></option>
                        @if(!empty($filterOptions))
                            @foreach($filterOptions as $key => $value)
                                <option value="{{ $key }}" @if(isset($session['filter_cross_sell_implement']) && $session['filter_cross_sell_implement'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <input type="hidden" value="{{ $session['order1'] or '' }}" class="submit_order" name="order1" id="submit_order1">
            <input type="hidden" value="{{ $session['order2'] or '' }}" class="submit_order" name="order2" id="submit_order2">
            <input type="hidden" value="{{ $session['order3'] or '' }}" class="submit_order" name="order3" id="submit_order3">
            <input type="hidden" value="{{ $session['order4'] or '' }}" class="submit_order" name="order4" id="submit_order4">
            <input type="hidden" value="{{ $session['direction1'] or '' }}" class="submit_direction" name="direction1" id="submit_direction1">
            <input type="hidden" value="{{ $session['direction2'] or '' }}" class="submit_direction" name="direction2" id="submit_direction2">
            <input type="hidden" value="{{ $session['direction3'] or '' }}" class="submit_direction" name="direction3" id="submit_direction3">
            <input type="hidden" value="{{ $session['direction4'] or '' }}" class="submit_direction" name="direction4" id="submit_direction4">

            <div class="d-flex flex-column flex-lg-row justify-content-lg-end">
                <button type="button" class="btn btn--gradient-orange border " name="searchItems" id="searchItems"> {{ __('report_corp_commission.search') }} </button>
                <button type="button" class="btn btn--gradient-default border ml-lg-1 mt-1 mt-lg-0" name="clearFilter" id="clearFilter"> {{ __('report_corp_commission.clear') }} </button>
            </div>
        </div>
    </fieldset>
</form>
