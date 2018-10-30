<div class="modal modal-global modal-form-border fade" id="add-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    @lang('agreement_admin.register_license_information')
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <form id="add-license-form">
                <div class="modal-body p-4">
                    <div class="form-group row m-0 border-bottom-0">
                        <div class="col-sm-4 p-3">
                            <label for="add-license-name" class="m-0">
                                @lang('agreement_admin.license_name') *
                            </label>
                        </div>
                        <div class="col-sm-8 p-2">
                            <input class="form-control" type="text" id="add-license-name" name="add-license-name"
                                   data-rule-required="true" data-rule-maxlength="200"/>
                        </div>
                    </div>
                    <div class="form-group row m-0">
                        <div class="col-sm-4 p-3">
                            <label for="add-certificate-required-flag" class="m-0">
                                @lang('agreement_admin.certificate_required_flag')
                            </label>
                        </div>
                        <div class="col-sm-8 p-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="add-certificate-required-flag">
                                <label class="custom-control-label" for="add-certificate-required-flag"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal-footer justify-content-center p-4">
                <button type="button"
                        class="btn btn--gradient-green w-45 text-white"
                        id="add-license-button">
                    @lang('agreement_admin.btn_update')
                </button>
                <button type="button"
                        class="btn btn--gradient-default border w-45 text-secondary"
                        data-dismiss="modal">
                    @lang('agreement_admin.btn_cancel')
                </button>
            </div>

        </div>
    </div>
</div>
