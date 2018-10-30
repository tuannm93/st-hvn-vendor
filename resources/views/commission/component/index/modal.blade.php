{{-- Modal --}}
<div class="commission-detail-popup">
    <div class="modal" id="list_event_dialog" tabindex="-1" role="dialog" aria-labelledby="list_event_dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                >
                    <p class="text-right m-0 p-0">
                        <a class="close" href="#"
                            data-dismiss="modal"
                        >
                            x
                        </a>
                    </p>

                    <div class="modal-body">
                        <div class="main-title">@lang('commissioninfos.lbl.modal_title')</div>
                        <div id="list_demands" class="mt-2"></div>
                        
                    </div>
                    <p class="footer-button-wrap">
                        <button type="button" class="green-button-s btnOver btn btn--gradient-green"
                                id="btnDirectCommission"
                        >@lang('commissioninfos.lbl.modal_close')</button>
                    </p>
            </div>
        </div>
    </div>
</div>
