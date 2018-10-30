<div class="modal modal-global fade sub-win-modal" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="text-center mb-3" id="show">
                <button type="button" class="btn btn--gradient-gray" data-dismiss="modal">@lang('demand_detail.close_up')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-global fade sub-win-modal" id="modal-popup-corps" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span id="modal-title"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="text-center mb-3" id="show">
                <button type="button" class="btn btn--gradient-gray" data-dismiss="modal">@lang('demand_detail.close_up')</button>
            </div>
        </div>
    </div>
</div>


<div class="modal modal-global fade" id="modal-small-popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center text-danger">
                @lang('demand_detail.mistransmission')
            </div>
            <div class="modal-body">
                <div class="text-left">
                    @lang('demand_detail.thefollowing-suppliers-are-confirmed')<br/><br/>
                    @lang('demand_detail.e-mail-sent-finalized-supplier-destination')<br/><br/>
                    <div id="send_mail_affiliations">
                        <div>  @lang('demand_detail.member-shop-sent')</div><br/><br/>
                        <div class="a_name"> @lang('demand_detail.akahige-lock')</div>
                    </div>
                </div>
                <div class="form-row align-items-center text-center">

                    @lang('demand_detail.is-it-ok')
                    <div class="custom-control custom-radio mr-2">
                        <input class="custom-control-input" id="send_confirm_check_0" name="send_confirm_check" checked=false
                               type="radio" value=0 >
                        <label class="custom-control-label" for="send_confirm_check_0">@lang('demand_detail.no_modal')</label>
                    </div>

                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" id="send_confirm_check_1" checked=false
                               name="send_confirm_check" type="radio" value=1 aria-invalid="false">
                        <label class="custom-control-label" for="send_confirm_check_1">@lang('demand_detail.yes_modal')</label>
                    </div>
                </div>

            </div>
            <div class="mb-3 text-center">
                <button type="button" class="btn btn--gradient-gray" id="cancel-send" data-dismiss="modal">@lang('demand_detail.cancel')</button>
                <button id="acceptOK" type="button" class="btn btn--gradient-green" disabled="true">@lang('demand_detail.ok')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-global fade sub-win-modal" id="m-corp-detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span id="title-m-corp-detail"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
