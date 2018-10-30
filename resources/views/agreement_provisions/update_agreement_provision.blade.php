<div class="modal modal-form-border modal-global fade" id="update-agreement-provision-dialog"
     tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="update-agreement-provision-form">
                <div class="modal-header">
                    <h4 class="modal-title">
                        @lang('agreement_admin.text_editing_screen')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="update-agreement-provision_provision-before-change">
                                @lang('agreement_admin.article_before_change')
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input disabled id="update-agreement-provision_provision-before-change" class="form-control"
                                 name="update-agreement-provision_provision-before-change" />

                        </div>
                    </div>

                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="update-agreement-provision_new-provision">
                                @lang('agreement_admin.provision') *
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <textarea id="update-agreement-provision_new-provision"
                                   name="update-agreement-provision_new-provision"
                                   class="form-control"  type="text" data-rule-required="true">
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group row m-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="update-agreement-provision_sort-no-before-change">
                                @lang('agreement_admin.sort_no_before_change')
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input class="form-control" disabled id="update-agreement-provision_sort-no-before-change"
                                 name="update-agreement-provision_sort-no-before-change" />
                        </div>
                    </div>

                    <div class="form-group row m-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="update-agreement-provision_new-sort-no">
                                @lang('agreement_admin.sort_no') *
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input id="update-agreement-provision_new-sort-no"
                                   name="update-agreement-provision_new-sort-no"
                                   class="form-control"  type="text"
                                   data-rule-numberAllSize="true"
                                   data-rule-minallsize="-2147483648"
                                   data-rule-maxallsize="2147483647"
                                   data-rule-required="true">
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center p-4">
                    <button id="update-agreement-provision_update-agreement-provision-button"
                            type="button" class="btn btn--gradient-green w-45 text-white">
                        @lang('agreement_admin.btn_update')
                    </button>
                    <button type="button" class="btn btn--gradient-default border w-45 text-secondary"
                            data-dismiss="modal">
                        @lang('agreement_admin.btn_cancel')
                    </button>
                    <button id="update-agreement-provision_delete-agreement-provision-button"
                            type="button" class="btn btn--gradient-default w-45 text-secondary">
                        @lang('agreement_admin.btn_delete')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
