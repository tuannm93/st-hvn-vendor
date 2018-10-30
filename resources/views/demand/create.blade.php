@extends('layouts.app')
@section('style')
    <style>
        .custom-control-input{
            z-index: 1;left: 2px;top: 7px;
        }
        .ui-multiselect {
            padding: 5px 0 5px 4px;
        }
        .ui-icon-triangle-2-n-s{
            background-position: -131px -14px;
        }
        a.text--orange {
            color: #f27b07 !important;
            text-decoration: underline;
        }
        #partner_commission_info label.align-items-center > label:hover{
          background-color: #ffcccc;
        }
    </style>
@endsection
@php
    $frmAction = '';
    if (isset($copy)) {
        $frmAction = 'copy';
    }
    elseif (isset($cross)) {
        $frmAction = 'cross';
    }
@endphp
@section('content')

    {!! Form::open(['route' => ['demand.regist'], 'name' => 'f1','method' => 'post', 'id' => 'demand_detail_form', 'autocomplete' => 'off', 'autocorrect' =>"off", "autocapitalize" =>"off"]) !!}

    <input type="hidden" value="" name="commissions" id="commissions" />
    <input type="hidden" value="0" name="current_index" id="current_index" />

    {!! Form::hidden('telephone_already', getDivValue('demand_status','telephone_already'), ['id' => 'telephone_already']) !!}
    {!! Form::hidden('demand_status', getDivValue('demand_status','telephone_already'), ['id' => 'information_sent']) !!}
    {!! Form::hidden('auction_selection_limit', '', ['id' => 'auction_selection_limit']) !!}
    {!! Form::hidden('manual_selection_limit', '', ['id' => 'manual_selection_limit']) !!}
    {!! Form::hidden('frm_action', $frmAction, ['id' => 'frm_action']) !!}
    <section class="content container demand-detail my-4 pt-3" data-url="{{ route('ajax.get.now.view') }}">
        @include('demand.create.action_header')
        <div class="form-category mb-4">
            <label class="form-category__label">@lang('demand_detail.basic_information')</label>
            <div class="form-category__body clearfix">
                <div class="row">
                    @include('demand.create.reception_information')
                    {!! Form::hidden('display_auto_commission_message', 0, ['id' => 'display_auto_commission_message']) !!}
                    {!! Form::hidden('demandInfo[commission_type_div]', '', ['id' => 'commission_type_div']) !!}
                    {!! Form::hidden('demandInfo[commission_limitover_time]', ($ctiDemand) ? $ctiDemand['site_id'] : '', ['id' => 'commission_limitover_time']) !!}
                    {!! Form::hidden('send_commission_info', 0, ['id' => 'send_commission_info']) !!}
                    <div class="col-12 mb-4">
                        <h6 class="form-note align-items-center d-flex font-weight-bold mt-0 mb-3">
                            @lang('demand_detail.hearing_information')
                            {{--<button class="ml-4 btn btn--gradient-default">同顧客案件</button>--}}
                        </h6>
                        @include('demand.create.hearing_information')
                    </div>
                    <div class="col-12 my-4">
                        <h6 class="form-note align-items-center d-flex font-weight-bold mt-0 mb-3">
                            @lang('demand_detail.supplementary_information')
                        </h6>
                        @include('demand.create.additination_information')
                    </div>

                </div>
            </div>
        </div>

        @include('demand.create.partner_information')

        @include('demand.create.jbr_information')
        @include('demand.create.proposal_status')
        @include('demand.create.mail_transaction')
        @include('demand.create.history_information')
        @include('demand.create.submit_partial')
        @include('demand.create.modal')
        @include('demand.create.dropdown_demand_detail')
        @include('demand.create.dialog')
    </section>
    {!! Form::close() !!}
    <input type="hidden" value="{!! (old('demandInfo')) ? old('demandInfo')['contents'] : '' !!}" id="hid_oldDemandContent">
@endsection


@section('script')
    <script>

        var apiRoutes = {
            getInquiryItemDataUrl: "{{ route('ajax.get_inquiry_item_data') }}",
            getCategoryListByGenreIdUrl: "{{ route('ajax.get_category_by_genre_id') }}",
            getGenreListBySiteIdUrl: "{{ route('ajax.get_genre_list_by_site_id') }}",
            getSiteDataUrl: "{{ route('ajax.demand.site_data') }}",
            getSelectionSystemListUrl: "{{ route('ajax.demand.selection_system_list') }}",
            getAddressByZipUrl: "{{ route('ajax.searchAddressByZip') }}",
            getBusinessTripAmountUrl: "{{ route('ajax.travel_expenses') }}",
            getUserListUrl: "{{ route('demand.get_user_list') }}",
            getDefaultFeeUrl: "{{ route('demand.get_default_fee') }}",
            getCrossSourceSiteUrl: "{{ route('ajax.demand.category_list2') }}",
            getCommissionMaxLimitUrl: "{{ route('ajax.get_commission_max_limit') }}",
            currentUserId: "{{ Auth::user()->id }}",
            getExistAutoCommissionCorpUrl: "{{ route('ajax.exists_auto_commission_corps') }}",
            getAttentionDataUrl: "{{ route('ajax.demand.attention_data') }}",
            getCommissonChangeUrl: "{{ route('ajax.demand.commission_change') }}"
        }

        var validateFail = false;
        var selectionSystemValue = null;
        var oldDemandContent = '';

        @if(old('demandInfo'))
            validateFail = true;
            selectionSystemValue = "{{ old('demandInfo')['selection_system'] ?? '' }}";
            oldDemandContent  = $('#hid_oldDemandContent').val();
            crossSellSourceGenre = "{{ old('demandInfo')['cross_sell_source_genre'] ?? '' }}";
        @endif

        var hasCommission = false, hasChangeDemandContent = false;
        @if(old('commissionInfo'))
            hasCommission = true;
        @endif
        var hasChange = false;
    </script>

    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/demands/template.js') }}"></script>
    <script>
        var isRegist = true;
        var initTime = function(){
            $(".multiple_check_filter").multiselect({
                minWidth:200,
                selectedList: 50,
                checkAllText: "全選択",
                uncheckAllText: "選択解除",
                noneSelectedText: "--なし--",
                multiple: false,
            }).multiselectfilter({
                label:'',
                width:95
            });
        };
        initTime();
        Datetime.initForDateTimepicker();
        Datetime.initForTimepicker();
        Datetime.initForDatepicker();
        var ctiDemand = {{ empty($ctiDemand) ? 'false' : 'true' }};
        var ctiGenreId = '';
        if(ctiDemand){
            ctiGenreId = "{{ old('demandInfo')['genre_id'] }}";
        }
    </script>

    <script src="{{ mix('js/pages/demands/count_affiliation.js')}}"></script>
    <script src="{{ mix('js/pages/demands/demand.js') }}"></script>
    <script src="{{ mix('js/pages/demands/demand_validation.js') }}"></script>

    <script>
        (function ($) {
            // var currentSession = parseInt(localStorage.getItem('currentSession') || 0);
            {{--@if(!old('demandInfo') && !old('commissionInfo') && !old('visitTime'))--}}
                {{--localStorage.setItem('currentSession', currentSession + 1);--}}
            {{--@endif--}}

            demandModule.init();

            $(window).on('load', function() {
                demandModule.windowLoad();
                CountAff.hiddenCountData();
                CountAff.addressChange();
                CountAff.runDetail();
                CountAff.pasteDate();
            });

            var formCreate = '#demand_detail_form';
            demandValidationModule.validate(formCreate, false);

        })(jQuery);

        $('.form-control').on('change', function(e){
            hasChange = true;
        });

        $('button').on('click', function(){
            hasChange = false;
        });

        $('a').on('click', function(){
            var href = $(this).attr('href');
            if(href.indexOf('callto') !== -1){
                hasChange = false;
            }else{
                hasChange = true;
            }

        });

        window.onbeforeunload = function (e) {
            if(hasChange){
                return '';
            }
        };
        $( "input").each(function(k, v){
            $(v).attr('autocomplete', 'nope');
        });
    </script>
    <script type="text/javascript">
        @if (isset($ctiCustomerTel) && !empty($ctiCustomerTel) && !$errors->any())
        $(function() {
            var url = window.location.origin+'/demand_list?customer_tel='+'{{ $ctiCustomerTel }}';
            window.open(url, '_blank', 'width=1150, height=600, menubar=no, toolbar=no, scrollbars=yes , location=no, left=' + (screen.availWidth - 200));
        });
        @endif
    </script>
    <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
@stop
