@extends('layouts.app')
@section('content')
    <div class="container" id="admin-index">
        <div class="row">
            <div class="list-group col-md-3">
                <div class="group">
                    <h5 class="list-group-item list-group-item-info">@lang('admin.master_maintenance')</h5>
                    <a class="list-group-item list-group-item-warning" href="{{route('user.index')}}">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.user_master')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="{{route('genre.index')}}">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.genre_master')
                    </a>
                    @php
                        // 2018.04.30 hao.nguyenhuu@nt CHG (S)
                        // move route
                    @endphp
                    <a class="list-group-item list-group-item-warning" href="{{ route('get.target.demand.flag')}} ">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.sales_supported')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.site_master')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.hearing_item_master')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.answer_item_master')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.compatible_genres_master_by_company')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        @lang('admin.item_master')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="{{route('selection.index')}}">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.setting_by_genre_selection_method')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="{{route('auction_setting.index')}}">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        @lang('admin.bidding_ceremony_selection_setting')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="{{route('daily_list.index')}}">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.daily_list')
                    </a>
                    <a class="list-group-item list-group-item-warning" href="{{route('not_correspond.index')}}">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.neighbor_construction_area_setting')
                    </a>
                    {{--<a class="list-group-item list-group-item-warning" href="{{route('not_correspond.mail_setting')}}">--}}
                        {{--<i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.auto_explorer_setting_mail_setting')--}}
                    {{--</a>--}}
                    @if(in_array($auth,['system','admin']))
                        <a class="list-group-item list-group-item-warning" href="{{route('autoCommissionCorp.index')}}">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>{{ trans('admin.automatic_agent') }}
                        </a>
                    @endif
                    <a class="list-group-item list-group-item-warning" href="{{route('autocall.index')}}">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.auto_call_setting')
                    </a>
                    {{--<a class="list-group-item list-group-item-warning" href="{{ url('csv_import') }}">--}}
                        {{--<i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('admin.csv_import')}}--}}
                    {{--</a>--}}
                    @if(in_array($auth,['system','admin']))
                        <a class="list-group-item list-group-item-warning" href="{{route('vacation_edit.index')}}">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('admin.long_term_vacation_setting')}}
                        </a>
                    @endif
                </div>
                <div class="group">
                    <h5 class="list-group-item list-group-item-info">@lang('admin.external_system_cooperation')</h5>
                    <a class="list-group-item list-group-item-warning" href="">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.sales_force')
                    </a>
                </div>
                <div class="group">
                    <h5 class="list-group-item list-group-item-info">@lang('admin.bulletin_board')</h5>
                    <a class="list-group-item list-group-item-warning" href="{{route('notice_info.index')}}">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>@lang('admin.bulletin_board_management')
                    </a>
                </div>

                {{-- agreement-admin --}}
                @if(Auth::user()['auth'] == 'system')
                    <div class="group">
                        <h5 class="list-group-item list-group-item-info">{{trans('report_index.contract_management') }}</h5>
                        <a class="list-group-item list-group-item-warning" href="{{route('agreement.dashboard')}}">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('agreement_admin_dashboard.contractStatusList') }}
                        </a>
                        <a class="list-group-item list-group-item-warning" href="{{route('agreement.provisions')}}">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('agreement_admin.agreement_provisions_management') }}
                        </a>
                        <a class="list-group-item list-group-item-warning" href="{{route('agreement.customize')}}">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('agreement_admin_customize.specialManagement') }}
                        </a>
                        <a class="list-group-item list-group-item-warning" href="{{route('agreement.admin.categories')}}">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('agreement_admin.category_management') }}
                        </a>
                        <a class="list-group-item list-group-item-warning" href="{{route('agreement.admin.license')}}">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('agreement_admin.license_management') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
