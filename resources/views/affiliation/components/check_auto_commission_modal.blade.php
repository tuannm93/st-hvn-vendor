<div class="modal fade" id="checkModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">@lang('agreement.title_modal_check_auto_commission')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
               <p>
                    @lang('agreement.content_modal_check_auto_commission')
                </p>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <div class="agreement-button">
                    <a href="{{ route('autoCommissionCorp.index') }}" target="_blank">
                        <button type="button" class="btn btn--gradient-green font-weight-bold" onclick='$("#checkModal").modal("hide");'>@lang('agreement.confirmation')</button>
                    </a>
                    <button type="button" class="btn btn--gradient-orange font-weight-bold" data-dismiss="modal">@lang('agreement.close')</button>
                </div>
            </div>
        </div>
    </div>
</div>
