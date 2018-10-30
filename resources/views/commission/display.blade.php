<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('assets/img/favicon_m.ico') }}" type="image/x-icon" rel="icon">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="progress-block">
    <div class="progress"></div>
</div>
<form class="form-commission-select" action="/commission_select/index/" name="form1" accept-charset="utf-8" id="displayForm" method="post">
        {{ csrf_field() }}
        <div style="display:none;">
            <input type="hidden" name="data[genre_id]" value="{{ $data['genre_id'] }}" id="genre_id">
            <input type="hidden" name="data[site_id]" value="{{ $data['site_id'] }}" id="site_id">
            <input type="hidden" name="data[exclude_staff_id]" value="{{ $data['exclude_staff_id'] }}" id="exclude_staff_id">
            <input type="hidden" name="data[lat]" value="{{ $data['lat'] }}" >
            <input type="hidden" name="data[lng]" value="{{ $data['lng'] }}">
            <input type="hidden" name="data[time_from]" value="{{ $data['time_from'] }}">
            <input type="hidden" name="data[time_to]" value="{{ $data['time_to'] }}">
            <input type="hidden" name="data[exclude_corp_id]" value="{{ $data['exclude_corp_id'] }}">
        </div>


    <div id="condition_info" class="condition_info">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9">
                <div class="infor-left">
                    <p>
                        @lang('commissionselect.address')：{{ getDivTextJP('prefecture_div', $data['address1']) }} {{ $data['address2'] }}
                    </p>
                    <input type="hidden" name="data[address1]" value="{{ $data['address1'] }}">
                    <input type="hidden" name="data[address2]" value="{{ $data['address2'] }}">

                    <p>
                        @lang('commissionselect.category_name')：{{ $data['category_name'] }}
                        <input type="hidden" name="data[category_id]" value="{{ $data['category_id'] }}" id="category_id">
                    </p>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div>
                    <p style="font-size:11px">
                        <span>@lang('commissionselect.commission_unit_price_category')：{{ yenFormat2($maxPrice['commission_unit_price_category']) }}</span>
                    </p>
                    <p style="font-size:11px">
                        <span>@lang('commissionselect.corp_name')：{{ $maxPrice['corp_name'] }} </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

        <br/>
        <label class="form-category__label">取次先候補</label>
        @if(isset($errorCommission))
            <div class="alert alert-error text-danger">
                {!! $errorCommission !!}
            </div>
        @endif
        <div id="introduce_info">
            <div class="orange_backcolor">
                <div class="form-group form-checkbox">
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-12 d-flex align-items-center">
                            <input type="hidden" name="data[target_check]" id="target_check_" value="0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="data[target_check]" value="1" id="target_check"
                                @if(isset($data['target_check']))
                                    checked
                                @endif>
                                <label class="custom-control-label" for="target_check">@lang('commissionselect.category_area_releasing_condition')</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-2 mb-sm-0">
                            <input type="hidden" name="data[fixed_val]" id="fixed_val">
                            <input name="search" class="btn btn--gradient-orange col-12 search search1" type="submit" value="@lang('commissionselect.before_ordering')">
                        </div>
                        <div class="col-md-4 col-lg-3 mb-2 mb-sm-0">
                            <input name="search" class="btn btn--gradient-orange col-12 search search2" type="submit" value="@lang('commissionselect.waiting_for_contact')">
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <input name="search" class="btn btn--gradient-orange col-12 search search3"  type="submit" value="@lang('commissionselect.pioneering_request')">
                        </div>
                        <input type="hidden" name="data[no]" value="{{ $data['no'] }}" id="no">
                        <input type="hidden" name="data[jis_cd]" value="{{ $data['jis_cd'] }}" id="jis_cd">
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-6 input-search">
                            <input name="data[corp_name]" id="corp_name" class="form-control" maxlength="40" type="text"
                            @if(isset($data['corp_name']))
                                value="{{$data['corp_name'] }}"
                            @else
                                value=""
                            @endif>
                        </div>
                        <div class="col-sm-4 col-lg-2 mb-2 mb-sm-0">
                            <input name="search" class="btn btn--gradient-orange col-12 search"  type="submit" value="@lang('commissionselect.search')">
                        </div>
                        <div class="col-sm-4 col-lg-2 mb-2 mb-sm-0">
                            <input type="hidden" name="data[commition_info_count]" id="commition_info_count" value="1">
                            <input type="button" value="@lang('commissionselect.decide')" class="btn btn--gradient-green col-12" id="decide">
                        </div>
                        <div class="col-4 col-lg-2">
                            <input type="button" value="@lang('commissionselect.clear_selection')" class="btn btn--gradient-green col-12 btn-clear" id="clear_selection">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="content-ajax mt-4 table-commission-select custom-scroll-x">
        @include('commission.component.commission_select_popup')
    </div>
    <div class="text-center">
        <button type="button" class="btn btn--gradient-gray mb-3 col-2" id="site_launch_details_close" onclick="javascript:window.close();">{!! trans('commission_detail.btn_close') !!}</button>
    </div>



    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ mix('js/lib/jquery-ui.min.js') }}"></script>
<script src="{{ mix('js/lib/jquery.ui.datepicker-ja.min.js') }}"></script>
<script src="{{ mix('js/pages/global.js') }}"></script>
<script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script>
        var urlCommissionSelect = '{{route('commissionselect.index')}}';
        var urlCheckCredit = '{{route('commissionselect.check_credit')}}';
    </script>
    <script type="text/javascript" src="{{ mix('js/pages/display_commission.js') }}"></script>
</body>
</html>