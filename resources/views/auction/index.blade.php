@inject('service', 'App\Services\Auction\AuctionService')

@php
    $auctionMaskingAll = $service->getDivValue("auction_masking" , "all_exclusion");
    $auctionMaskingWithout = $service->getDivValue("auction_masking" , "without");
@endphp

@extends('layouts.app')

@section('content')
    <div class="auction-search">
        {!! Form::open(['url' => route('auction.post.search'), 'id' => 'search-form']) !!}
        {!! Form::button(__('auction.update_info_new'), ['name' => 'Search', 'id' => 'search-btn-first', 'class' => 'btn btn--gradient-green font-weight-bold px-4 mt-2']); !!}
        <div class="form-category mt-5 d-none d-xl-block">
            <label class="form-category__label fs-15 font-weight-light">@lang('auction.list_of_biddable_items')</label>
        </div>
        <label class="collapse-label-mobi font-weight-bold p-2 w-100 mt-3 text--white d-block d-xl-none" data-toggle="collapse" data-target="#search-box-mobi" aria-expanded="false" aria-controls="search-box-mobi">@lang('auction.list_of_search_items')<i class="fa fa-chevron-down float-right" aria-hidden="true"></i></label>
        <div id="search-box-mobi" class="search-box row mx-0 collapse show">
            <div class="col-lg-3 px-0 mb-2 mb-md-0">
                <div>
                    <label class="mb-1">@lang('auction.proposal_status')</label>
                </div>
                <div class="d-flex fs-11">
                    <div class="switch-btn {{ $display ? 'activated' : '' }} left p-2" data-toggle="tooltip" title="Popover Header" data-placement="top">
                        {!! Form::radio("display", '0', $display, ['style'=>'display:none']) !!}@lang('auction.show_all')
                    </div>
                    <div class="switch-btn {{ !$display ? 'activated' : '' }} right py-2 px-3">
                        {!! Form::radio('display', '1', !$display, ['style'=>'display:none']) !!}
                        @lang('auction.excluding_competitors_already_bidding_deals')
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 px-0 px-md-2 mb-2 mb-md-0 width-ui-state-default">
                <div>
                    <label class="mb-1">@lang('auction.area')</label>
                </div>
                {!! Form::select('address1[]', config('datacustom.prefecture_div'), null, ['id'=>'auction-search-1', 'multiple'=>'true', 'hidden'=>'true']); !!}
            </div>
            <div class="col-md-4 col-lg-4 px-0 px-md-2 mb-2 mb-md-0">
                <div><label class="mb-1">@lang('auction.genre')</label></div>
                {!! Form::select('genre_id[]', $listMGeners, null, ['id'=>'auction-search-2', 'multiple'=>'true', 'hidden'=>'true']); !!}
            </div>
            <div class="col-md-3 col-lg-1 px-0">
                <div class="d-none mb-1 d-md-block opacity-0">pseudo</div>
                {!! Form::button(__('auction.search'), ['name' => 'Search', 'id' => 'search-btn-second', 'class' => 'btn btn--gradient-orange col-sm-3 col-md-12']); !!}
            </div>
        </div>
        {!! Form::close() !!}
        <label class="collapse-label-mobi font-weight-bold p-2 w-100 mt-3 text--white d-block d-xl-none" data-toggle="collapse" data-target="#table-group-mobi" aria-expanded="false" aria-controls="table-group-mobi">@lang('auction.list_of_biddable_items')<i class="fa fa-chevron-down float-right" aria-hidden="true"></i></label>
        <div id="results-search">
            @include('auction.search')
        </div>
        @if ($isRoleAffiliation)
        <div class="kameiten-account">
            <div class="collapse-link">
                <input type="hidden" value="0" id="affFirstClick"/>
                <p class="font-weight-bold border-top pt-4 pb-xl-4" id="txt-lbl" data-toggle="collapse"
                   href="#collapse-content">@lang('auction.show_bid_completed_case')≫</p>
            </div>
            <div id="result-kameiten">
                <div id="collapse-content" class="collapse">
                    @include('auction.kameiten')
                    <div id="table-calendar" class="justify-content-center flex-column flex-md-row p-3" hidden>
                        <span>
                            <i class="fa fa-chevron-left fa-4x getCalendar" aria-hidden="true"
                                data-next="0"></i>
                        </span>
                        <span>
                            <i class="fa fa-chevron-right fa-4x getCalendar" aria-hidden="true"
                                data-next="1"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div id="temp-refusal" data-id="1"></div>
    @component('auction.refusal')@endcomponent
    <div class="modal fade" id="seikatu_info_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="text-align:center;font-size:18px;font-weight: bold;">@lang('auction.what_is_a_living_ambulance_case')
                        <br/></p>
                    <p style="text-align:left;margin-left:40px;">
                        @lang('auction.company_info')<br>
                        @lang('auction.rushing_support_service')<br>
                        @lang('auction.unlike_company')<br>
                        @lang('auction.customers')<br><br>
                        @lang('auction.free')<br>
                        @lang('auction.text_respond')<br><br>
                        @lang('auction.correspondence')<br>
                        @lang('auction.i_will')<br><br>
                        @lang('auction.copy_of_the_receipt')<br>
                        @lang('auction.we_spend_it')<br>
                        @lang('auction.there_are_some_conditions_in_the_commission_rate_as_well')<br>
                        @lang('auction.please_contact_us_if_you_are_interested')<br>
                    </p>
                    <p style="margin-bottom:20px;padding:0;text-align:center;color:#ffffff;">
                        <button type="button" class="btn_green_s w200 over"
                                onclick="seikatu_info_dialog_close()">@lang('auction.close')</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @include('auction.component.modal-list-event')
    <!-- Modal -->
    <div class="modal fade" id="supportModal" tabindex="-1" role="dialog" aria-labelledby="refusalModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="auction-support-content">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="auctionDetailModal" tabindex="-1" role="dialog" aria-labelledby="auctionDetailModal" data-keyboard="false"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="mb-0">@lang('auction.deal_details')</h5>
                </div>
                <div class="modal-body">
                    <div class="auction-support-content">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--gradient-default col-12" data-dismiss="modal">@lang('auction.close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/rsvp.js') }}"></script>
    <script>
        window.Promise = window.Promise || RSVP.Promise;
    </script>
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/auction.refusal.js') }}"></script>
    <script src="{{ mix('js/pages/support.js') }}"></script>

    <script type="text/javascript">
        var d = new Date();
        var currentMonth = d.getMonth() + 1;
        var currentYear = d.getFullYear();
        var cals_id = ["cell-cal1", "cell-cal2", "cell-cal3"];
        var urlGetCalenderView = "{{ route('ajax.get.calender.view') }}";
        var currentDate = new Date();
        var calendarEventData = {!! (!isset($calendarEventData)) ? '[]' : $calendarEventData !!};
        var un_select = "@lang('auction.un_select')";
        var check_all = "@lang('auction.check_all')";
        var un_check_all = "@lang('auction.un_check_all')";
        var urlProposalJson = "{{ route('auction.proposal.json', ['demandId' => '']) }}";
        var isRoleAffiliation = '{{ $isRoleAffiliation ? true : false }}';
        var urlUpdateJbrStatus = "{{route('auction.support.updateJbrStatus')}}";
        var urlComplete = "{{route('auction.handle.complete')}}";
        var screen_complete = "{{config('constant.refusal.complete')}}";
        var urlPostData = "{{route('auction.handle.support')}}";
        var urlSortForKameiten = "{{route('auction.post.sort.kameiten')}}";
    </script>
    <script src="{{ mix('js/pages/auction_search.js') }}"></script>
@endsection
