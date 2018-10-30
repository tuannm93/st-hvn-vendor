@extends('layouts.app')
@section('content')
    <div class="autocommission-index">
        <div class="container">
            @foreach (['error', 'success'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <p class="box__mess alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                @endif
            @endforeach
            <a class="link-orange" href="{{ URL::route('autoCommissionCorp.getAdd') }}">{{ trans('auto_commission_corp.link_add_auto_commission_corp') }}</a>
            <label class="form-category__label fs-13 mt-2">{{ trans('auto_commission_corp.label_automatic_agent_group') }}</label>
            <div class="row mb-3">
                <div class="col-md-6">
                    <span class="text-danger">{{ trans('auto_commission_corp.auto_commission_text_1') }}</span>
                    <span>{{ trans('auto_commission_corp.auto_commission_text_2') }}</span>
                </div>
                <div class="col-md-6">
                    <span class="text-primary">{{ trans('auto_commission_corp.auto_selection_text_1') }}</span>
                    <span>{{ trans('auto_commission_corp.auto_selection_text_2') }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-form-label font-weight-bold col-sm-3 col-xl-1">{{ trans('auto_commission_corp.genre_id_select_label') }}</label>
                <div class="col-sm-6 col-md-4 d-flex align-items-center">
                    <select id="genre_id" name="genre_id[]" multiple="multiple">
                        @foreach($genreCategoryList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3 col-xl-1 mt-2 mt-sm-0">
                    <button class="btn btn--gradient-orange col-12" type="button" id="seach_genre_button">{{ trans('auto_commission_corp.search_button') }}</button>
                </div>
            </div>
            <div>
                <label id="ajax_message">{{ trans('auto_commission_corp.select_genre_required') }}</label>
            </div>
        </div>
        <div class="custom-table mx-2">
            <div class="mark-up-header"></div>
            <div class="pseudo-border-left"></div>
            <div class="pseudo-border-right"></div>
            <div class="header-table d-flex">
                <div class="header-left bg-header">
                    <div class="p-2 colspan-3 border-bottom-0 border-right-0">
                        <span class="p-2 opacity-0">{{ trans('auto_commission_corp.genre_th') }}</span>
                    </div>
                    <div class="d-flex">
                        <span class="p-2 fix-w-50 fix-w-sm-100 item border-right-0">{{ trans('auto_commission_corp.genre_th') }}</span>
                        <span class="p-2 fix-w-50 fix-w-sm-100 item border-right-0">{{ trans('auto_commission_corp.category_label') }}</span>
                        <span class="p-2 fix-w-50 fix-w-sm-100 item border-right-0">{{ trans('auto_commission_corp.selection_method_label') }}</span>
                    </div>
                </div>
                <div class="header-right bg-header" data-colspan-rest="{{ count($prefectureList) }}">
                    <div class="p-2 colspan-rest font-weight-bold text-center border-left-0 border-bottom-0">{{ trans('auto_commission_corp.prefectures_label') }}</div>
                    <div class="d-flex">
                        @foreach ($prefectureList as $prepKey => $prefVal)
                            <span class="p-2 fix-w-50 fix-w-sm-100 item"><?php echo $prefVal; ?></span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="body-table d-flex">
                <div class="body-table-content"></div>
            </div>
            <div class="fix-header-sidebar-left bg-header z-index-1002">
                <div class="p-2 colspan-3 border-bottom-0">
                    <span class="p-2 opacity-0">{{ trans('auto_commission_corp.genre_th') }}</span>
                </div>
                <div class="d-flex">
                    <span class="p-2 fix-w-50 fix-w-sm-100 item">{{ trans('auto_commission_corp.genre_th') }}</span>
                    <span class="p-2 fix-w-50 fix-w-sm-100 item border-left-0">{{ trans('auto_commission_corp.category_label') }}</span>
                    <span class="p-2 fix-w-50 fix-w-sm-100 item border-left-0">{{ trans('auto_commission_corp.selection_method_label') }}</span>
                </div>
            </div>
            <div class="fix-header-sidebar-right bg-header z-index-1001" data-colspan-rest="{{ count($prefectureList) }}">
                <div class="p-2 colspan-rest font-weight-bold text-center border-left-0 border-bottom-0">{{ trans('auto_commission_corp.prefectures_label') }}</div>
                <div class="d-flex">
                    @foreach ($prefectureList as $prepKey => $prefVal)
                        <span class="p-2 fix-w-50 fix-w-sm-100 item"><?php echo $prefVal; ?></span>
                    @endforeach
                </div>
            </div>
            <div class="fix-body-sidebar-right"></div>
            <div class="autocommission-loading">
                <div class="d-flex">
                    <span class="p-2 fix-w-50 fix-w-sm-100 item">-</span>
                    <span class="p-2 fix-w-50 fix-w-sm-100 item">-</span>
                    <span class="p-2 fix-w-50 fix-w-sm-100 item">-</span>
                    <span class="colspan-rest item text-center align-items-center p-2">{{ trans('auto_commission_corp.select_genre_required') }}</span>
                </div>
            </div>
            <div class="autocommission-loading-fail">
                <div class="d-flex">
                    <span class="p-2 fix-w-50 fix-w-sm-100 item">-</span>
                    <span class="p-2 fix-w-50 fix-w-sm-100 item">-</span>
                    <span class="p-2 fix-w-50 fix-w-sm-100 item">-</span>
                    <span class="colspan-rest item"></span>
                </div>
            </div>
        </div>
        <div class="pseudo-table-scroll-bar z-index-1002">
            <div class="horizontal-scroll-bar"></div>
        </div>
    </div>
    <div id="page-data"
            data-url-search="{{ URL::route('ajax.search.auto.commission.corp') }}"
            data-url-all="{{ URL::route('auto.commission.corp.all') }}">
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/pages/auto_commission_corp_index.js') }}"></script>
    <script>
            var un_select = '@lang('auto_commission_corp.none')';
            var check_all = '@lang('auto_commission_corp.check_all')';
            var un_check_all = '@lang('auto_commission_corp.un_check_all')';
            var ajax_message_loading = '@lang('auto_commission_corp.ajax_message_loading')';
            var ajax_message_success = '@lang('auto_commission_corp.ajax_message_success')';
            var ajax_message_loading_fail = '@lang('auto_commission_corp.ajax_message_loading_fail')';
            AutoCommissionCorpIndex.init();
    </script>
@endsection
