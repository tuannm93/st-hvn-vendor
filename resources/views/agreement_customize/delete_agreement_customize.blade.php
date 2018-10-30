<div class="modal modal-form-border modal-global fade" id="deleteAgreementCustomizeDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteAgreementCustomizeForm">
                <div class="modal-header">
                    <h4 class="modal-title">
                        @lang('agreement_admin.title_delete_agreement_customize')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body p-4">
                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="delete-officialCorpName">
                                @lang('agreement_admin.company_name')
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input class="form-control" disabled id="delete-officialCorpName" type="text"
                                   name="officialCorpName">
                        </div>
                    </div>
                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="delete-tableKind">
                                @lang('agreement_admin.table_kind')
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input class="form-control" disabled id="delete-tableKind" type="text" name="tableKind">
                        </div>
                    </div>
                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="delete-editKind">
                                @lang('agreement_admin.edit_kind')
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input class="form-control" disabled id="delete-editKind" type="text" name="editKind">
                        </div>
                    </div>
                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="delete-content">
                                @lang('agreement_admin.content')
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <textarea class="form-control" id="delete-content" type="text"
                                      name="content" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group row m-0">
                        <div class="col-sm-3 p-3">
                            <label class="m-0" for="delete-sortNo">
                                @lang('agreement_admin.sort_no') *
                            </label>
                        </div>
                        <div class="col-sm-9 p-2">
                            <input class="form-control" id="delete-sortNo" name="delete-sortNo"
                                   data-rule-required="true" data-rule-numberAllSize="true"
                                   data-rule-minallsize="-2147483648" data-rule-maxallsize="2147483647">
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center p-4">
                    <div>
                        <button type="button"
                                class="btn btn--gradient-green w-45 text-white"
                                id="delete-agreement-customize-button">
                            @lang('agreement_admin.btn_delete')
                        </button>
                        <button type="button"
                                class="btn btn--gradient-default border w-45 text-secondary"
                                data-dismiss="modal">
                            @lang('agreement_admin.btn_cancel')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
