<div class="modal modal-form-border modal-global fade" id="createAgreementProvisionItemId" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="createAgreementProvisionItemFormId">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="modalHeaderId">@lang('agreement_admin.item_registration')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="provision_item">
                                @lang('agreement_admin.provision_item') *
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <select class="custom-select" id="provision_item" name="provision_item">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="item">
                                @lang('agreement_admin.item') *
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <textarea id="item" type="text" class="form-control"
                                      name="item" data-rule-required="true" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group row m-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="sortNoItem">@lang('agreement_admin.sort_no') *</label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input class="form-control" id="sortNoItem" type="text" name="sortNo"
                                   data-rule-numberAllSize="true"
                                   data-rule-minallsize="-2147483648"
                                   data-rule-maxallsize="2147483647"
                                   data-rule-required="true">
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center p-4">
                        <button type="button" class="btn btn--gradient-green w-45 text-white"
                                id="create-agreement-provision-item-button">@lang('agreement_admin.btn_create')</button>
                        <button type="button" class="btn btn--gradient-default border w-45 text-secondary"
                                data-dismiss="modal">@lang('agreement_admin.btn_cancel')</button>
                </div>
            </form>
        </div>
    </div>
</div>
