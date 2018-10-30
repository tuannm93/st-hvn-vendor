@extends('layouts.app')
@section('style')
    <style>
        .custom-control-input{
            z-index: 1;left: 2px;top: 7px;
        }
        .ui-icon-triangle-2-n-s{
            background-position: -131px -14px;
        }
        a.text--orange {
            color: #f27b07 !important;
            text-decoration: underline;
        }
        .text--info{
            color: #0000ff;
        }
        .ui-multiselect{
            padding: 5px 0 6px 4px;
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
    {!! Form::open(['route' => ['demand.register'], 'name' => 'f1', 'enctype' => 'multipart/form-data', 'method' => 'post', 'id' => 'demand_detail_form', 'autocomplete' => 'off', 'autocorrect' =>"off", "autocapitalize" =>"off"]) !!}
    <input type="hidden" value="" name="commissions" id="commissions" />
    <input type="hidden" value="0" name="current_index" id="current_index" />
    {!! Form::hidden('demandInfo[id]', isset($demand->id) ? $demand->id : '', ['id' => 'demand_id']) !!}
    {!! Form::hidden('demandInfo[modified]', $demand->modified) !!}
    {!! Form::hidden('send_commission_info', 0, ['id' => 'sendCommissionInfo']) !!}
    {!! Form::hidden('telephone_already', getDivValue('demand_status','telephone_already'), ['id' => 'telephone_already']) !!}
    {!! Form::hidden('demand_status', getDivValue('demand_status','telephone_already'), ['id' => 'information_sent']) !!}
    {!! Form::hidden('demand_status_before', $demand->demand_status, ['id' => 'demand_status_before']) !!}
    {!! Form::hidden('auction_selection_limit', '', ['id' => 'auction_selection_limit']) !!}
    {!! Form::hidden('manual_selection_limit', '', ['id' => 'manual_selection_limit']) !!}
    {!! Form::hidden('frm_action', $frmAction, ['id' => 'frm_action']) !!}
    {!! Form::hidden('demandInfo[push_stop_flg]', $demand->push_stop_flg, ['id' => 'push_stop_flg']) !!}

    {!! Form::hidden('demand_contents', isset($demand) ? $demand->contents : '', ['id' => 'demand_contents']) !!}
    <section class="content container demand-detail my-4 pt-3" data-url="{{ route('ajax.get.now.view') }}">
        @include('demand.partials.action_header')
        {{--Category 1--}}
        <div class="form-category mb-4">
            <label class="form-category__label"> @lang('demand_detail.basic_information')</label>
            <div class="form-category__body clearfix">
                <div class="row">
                    @include('demand.partials.reception_information')
                    {!! Form::hidden('display_auto_commission_message', 0, ['id' => 'display_auto_commission_message']) !!}
                    {!! Form::hidden('demandInfo[commission_type_div]', '', ['id' => 'commission_type_div']) !!}
                    {!! Form::hidden('demandInfo[commission_limitover_time]', '', ['id' => 'commission_limitover_time']) !!}

                    <div class="col-12 mb-4">
                        <h6 class="form-note align-items-center d-flex font-weight-bold mt-0 mb-3">
                            @lang('demand_detail.hearing_information')
                            @if($customerTel > 1 && ctype_digit($demand->customer_tel) && !isset($copy) && !isset($cross))
                                <a class="ml-4 btn btn--gradient-default" target="_blank"
                                   href="{{ route('demandlist.search') . '?customer_tel=' . $demand->customer_tel }}"> @lang('demand_detail.thesame_case')
                                </a>
                            @endif
                        </h6>
                        @include('demand.partials.hearing_information')
                    </div>
                    <div class="col-12 my-4">
                        <h6 class="form-note align-items-center d-flex font-weight-bold mt-0 mb-3">
                            @lang('demand_detail.supplementary_information')
                        </h6>
                        @include('demand.partials.additination_information')
                    </div>
                    @include('demand.partials.attached_files')
                </div>
            </div>
        </div>

        {{--Category 2--}}
        @include('demand.partials.partner_information')

        {{--Category 3--}}
        @include('demand.partials.jbr_information')

        {{--Category 4--}}
        @include('demand.partials.proposal_status')
        {{--Category 5--}}

        @include('demand.partials.mail_transaction')
        {{--Category 6--}}
        @include('demand.partials.history_information')
        @include('demand.partials.submit_partial')
        @include('demand.partials.modal')
        @include('demand.partials.dropdown_demand_detail')
        @include('demand.partials.dialog')
        <div class="row">
            <div class="col-12">
                <span class="font-weight-bold font-large pull-right">@lang('demand_detail.view_count')： <span class="total-current-views">0</span>件</span>
            </div>
        </div>
        <div id="page-data"
             data-date-picker-on-select="CountAff.onSelect" >
        </div>
        <div id="count-data"
             data-count-aff="CountAff.ajaxCountAff" >
        </div>
        <div class="count-affiliation" countAffiliation="{{route('ajax.affiliation.count')}}"></div>
    </section>
    {!! Form::hidden('demandInfo[lat]', $demandExtenInfoData['lat'] ?? '', ['class' => 'form-control', 'id' => 'latitude', 'data-rules' => 'not-empty', 'maxlength' => 20]) !!}
    {!! Form::hidden('demandInfo[lng]', $demandExtenInfoData['lng'] ?? '', ['class' => 'form-control', 'id' => 'longitude', 'data-rules' => 'not-empty', 'maxlength' => 20]) !!}
    {!! Form::close() !!}
    <input type="hidden" value="{!! (old('demandInfo')) ? old('demandInfo')['contents'] : ''!!}" id="hid_oldDemandContent">
    <div id="page-data" data-url-inquiry-list="{{route('ajax.demand.inquiry_list')}}"
         data-token="{{csrf_token()}}}"></div>
    @include('demand.partials.delete_modal')
@endsection

@section('script')
    <script>
        let noFileChoosen = "{{__('demand.no_file_chosen')}}";

        let categoryId = "{{ old('demandInfo') ? old('demandInfo')['category_id'] : $demand->category_id }}";
        let demandId = "{{ isset($demand->id) ? $demand->id : null }}";
        let id = false;
        let hasChangeDemandContent = false;
        @if(isset($id)) id = "{{ $id }}"; @endif
        let copy = false;
        let defaultCrossGenreId = false;
        let cross = false;
        @if(isset($cross))
            cross = true;
        @endif
        @if(isset($copy))
            defaultCrossGenreId = "{{ $demand->getOriginal('genre_id') }}";
            copy = true;
        @endif
    </script>
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>

    <script>
        var hasCommission = false;
        @if(old('commissionInfo'))
            hasCommission = true;
        @endif
        var isRegist = false;
        var crossSellSourceGenre = "{{ $demand ? $demand->cross_sell_source_genre : '' }}";
        var demandSelectionSystem = "{{ $demand ? $demand->selection_system : '' }}";
        var demandStatus = "{{ $demand ? $demand->demand_status : '' }}";
        var demandGenreId = "{{ $demand ? $demand->genre_id : '' }}";
        let initTime = function(){
            $(".multiple_check_filter").multiselect({
                minWidth:200,
                selectedList: 50,
                checkAllText: "全選択",
                uncheckAllText: "選択解除",
                noneSelectedText: "--なし--",
                multiple: false,
                format:'DD/MM/YYYY HH:mm',
            }).multiselectfilter({
                label:'',
                width:95
            });
        };
        initTime();
        Datetime.initForDateTimepicker();
        Datetime.initForTimepicker();
        Datetime.initForDatepicker();
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
            getCommissonChangeUrl: "{{ route('ajax.demand.commission_change') }}",
            //Demand detail
            uploadAttachedFileUrl : "{{ route('demand.upload_attached_file', $demand->id) }}",
            deleteAttachFileUrl : "{{ route('demand.delete_attached_file') }}",
            getWriteBrowseUrl : "{{ route('ajax.demand.write_browse', $demand ? $demand->id : '') }}",
            postCountBrowseUrl : "{{ route('ajax.demand.count_browse', $demand ? $demand->id : '') }}"
        }


        var validateFail = false;
        var selectionSystemValue = false;
        var oldDemandContent = '';

        @if(old('demandInfo') || (isset($cross) && $cross))
            validateFail = true;
            selectionSystemValue = "{{ old('demandInfo')['selection_system'] }}";
            oldDemandContent  = $('#hid_oldDemandContent').val();
            crossSellSourceGenre = "{{old('demandInfo')['cross_sell_source_genre'] ?? ''}}";
        @endif

        @if(isset($cross))
            selectionSystemValue = "{{ $demand->selection_system }}";
        @endif

    </script>

    <script src="{{ mix('js/pages/demands/count_affiliation.js')}}"></script>
    <script src="{{ mix('js/pages/demands/template.js') }}"></script>
    <script src="{{ mix('js/pages/demand_detail_header.js') }}"></script>
    <script src="{{ mix('js/pages/demands/demand_detail.js') }}"></script>
    <script src="{{ mix('js/pages/demands/demand_validation.js') }}"></script>

    <script>
        function getCommissionCommitArr(){
            var corpNames = [];
            $('.commission-table').each(function(){
                if(!$(this).find("[id^='del_flg']").is(':checked') && $(this).find("[id^='commit_flg']").is(':checked')){
                    corpNames.push($(this).find('input[id^="corp_name"]').val());
                }
            });
            return corpNames;
        }
        var $sendCommissionInfoBtn = $('#send_commission_info_btn'),
            $inputSendConfirmCheck = $('input[name="send_confirm_check"]'),
            $sendMailAffiliations = $('#send_mail_affiliations'),
            $acceptOk = $('#acceptOK'),
            $sendCommissionInfo = $('#sendCommissionInfo'),
            $demandStatus = $('#demand_status'),
            $cancelSend = $('#cancel-send'),
            $modalSmallPopup = $('#modal-small-popup');
        var sendCommissionInfo = function(){
            var demandStatus = parseInt($demandStatus.val());
            if([4, 5].indexOf(demandStatus) !== -1){
                $modalSmallPopup.find('input[type="radio"]').prop('checked', false);
                $acceptOk.prop('disabled', true);
                $sendMailAffiliations.find('.a_name').remove();
                var arr = getCommissionCommitArr();
                arr.forEach(function(corpName){
                    $sendMailAffiliations.append($('<p class="a_name" style="margin-left:16px;">').text(corpName));
                });
                $modalSmallPopup.modal('show');
                return false;
            }
            $('#demand_detail_form').submit();
        };
        $sendCommissionInfoBtn.on('click', function(e){
            e.preventDefault();
            $('#demandCorrespondContent').addClass('ignore');
            $sendCommissionInfo.val(1);
            sendCommissionInfo();
        });
        $inputSendConfirmCheck.on('change', function(){
            $('#acceptOK').prop('disabled', false);
        });
        $acceptOk.on('click', function(){
            $('#acceptOK').prop('disabled', true);
            var sendConfirmCheckValue = $modalSmallPopup.find('input[type="radio"]:checked').val();
            if(sendConfirmCheckValue == 0){
                $('.commission-table').each(function(){
                    $(this).find("[id^='commit_flg']").prop('checked', false);
                });
                $demandStatus.val($('#demand_status_before').val());
                $sendCommissionInfo.val(0);
                $modalSmallPopup.modal('hide');
                return;
            }

            $('#demand_detail_form').submit();
        });
        $modalSmallPopup.on('hidden.bs.modal', function(){
            $('#demandCorrespondContent').removeClass('ignore');
            $sendCommissionInfo.val(0);
        });
    </script>
    <script>
        $(document).ready(function () {
            CountAff.hiddenCountData();
            CountAff.addressChange();
            CountAff.pasteDate();
            CountAff.runDetail();
            demandModule.init();
            $sendCommissionInfo.val(0);
            var formCreate = '#demand_detail_form';
            demandValidationModule.validate(formCreate, copy);

            var initProgress = function () {
                return new progressCommon();
            };
            var progress = initProgress();
            var $modalPopup = $('#modal-popup');
            var loadModal = function(url_display_commission) {
                $.ajax({
                    type: "GET",
                    url: url_display_commission,
                    xhr: function () {
                        return progress.createXHR();
                    },
                    beforeSend: function (xhr) {
                        progress.controlProgress(true);
                    },
                    complete: function () {
                        progress.controlProgress(false);
                    },
                    success: function (data) {
                        $modalPopup.children().children().find('.modal-body').html(data);
                        $modalPopup.modal('show');
                    },
                    error: function () {
                        console.log("Error!");
                    }
                });
            };
            $('#get-auction-detail').on('click', function(){
                var urlData = $(this).data('url_data');
                loadModal(urlData);
            });
            var notShow = true;
            $('.popupHistory').click(function(e){
                e.preventDefault();
                openPopup(this);
            });
            function openPopup(element){
                let urlData = $(element).data('url_data') ;
                if(notShow) {
                    $('#show').addClass('d-none');
                }
                loadModal(urlData);
            }
        });


        var hasChange = false;


        $('.form-control').on('change', function(e){
            hasChange = true;
        });

        $('button').add('a#btn-copy').not('[type="submit"]').on('click', function(){
            hasChange = false;
        });

        $('a').on('click', function(){
            var href = $(this).attr('href');
            var data_url = $(this).attr('data-url_data');
            var check = href || data_url;
            if(check && check.indexOf('callto') !== -1){
                hasChange = false;
            }else{
                if ($(this).closest('.navbar').length == 0) {
                    hasChange = false;
                } else {
                    hasChange = true;
                }
            }

        });
        @if(isset($copy) || isset($cross))
        window.onbeforeunload = function (e) {
            e.preventDefault();
            if(hasChange){
                return '';
            }
            return;
        };
        @endif
        $( "input").each(function(k, v){
            $(v).attr('autocomplete', 'nope');
        });
        $(document).on('change', '#customControlInline', function() {
            if(this.checked) {
                $('#send_commission_info_btn').addClass('d-none');
            } else {
                $('#send_commission_info_btn').removeClass('d-none');
            }
        });


        $(window).on('load', function() {
            demandModule.windowLoad();
        });
    </script>
@stop
