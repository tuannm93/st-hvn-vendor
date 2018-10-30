<div class="modal modal-form-border modal-global fade" id="createAgreementProvisionId" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="createAgreementProvisionFormId">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="modalHeaderId">@lang('agreement_admin.title_create_agreement_provision')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="provisions">@lang('agreement_admin.provision') *</label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input class="form-control" id="provisions" type="text" data-rule-required="true"
                                   name="provisions">
                        </div>
                    </div>
                    <div class="form-group row m-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="sortNo">@lang('agreement_admin.sort_no') *</label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input class="form-control" id="sortNo" type="text"
                                   data-rule-minallsize="-2147483648"
                                   data-rule-maxallsize="2147483647"
                                   data-rule-numberAllSize="true"
                                   data-rule-required="true" name="sortNo">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center p-4">
                    <button type="button" class="btn btn--gradient-green w-45 text-white"
                            id="create-agreement-provision-button">@lang('agreement_admin.btn_create')</button>
                    <button type="button" class="btn btn--gradient-default border w-45 text-secondary"
                            data-dismiss="modal">@lang('agreement_admin.btn_cancel')</button>
                </div>
            </form>
        </div>
    </div>
</div>
