<div class="modal modal-global modal-form-border fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    @lang('agreement_admin.reference_license_information')
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body p-4">
                <div class="form-group row m-0 border-bottom-0">
                    <div class="col-sm-4 p-3">
                        <label for="detail-license-id" class="m-0">
                            @lang('agreement_admin.license_id')
                        </label>
                    </div>
                    <div class="col-sm-8 p-2">
                        <input class="form-control" disabled id="detail-license-id"
                               type="text" name="detail-license-id">
                    </div>
                </div>
                <div class="form-group row m-0 border-bottom-0">
                    <div class="col-sm-4 p-3">
                        <label for="detail-license-name" class="m-0">
                            @lang('agreement_admin.license_name')
                        </label>
                    </div>
                    <div class="col-sm-8 p-2">
                        <input class="form-control" disabled id="detail-license-name"
                               type="text" name="detail-license-name">
                    </div>
                </div>
                <div class="form-group row m-0 border-bottom-0">
                    <div class="col-sm-4 p-3">
                        <label for="detail-certificate-required-flag" class="m-0">
                            @lang('agreement_admin.certificate_required_flag')
                        </label>
                    </div>
                    <div class="col-sm-8 p-2">
                        <input class="form-control" disabled id="detail-certificate-required-flag"
                               type="text" name="detail-certificate-required-flag">
                    </div>
                </div>
                <div class="form-group row m-0 border-bottom-0">
                    <div class="col-sm-4 p-3">
                        <label for="detail-update-date" class="m-0">
                            @lang('agreement_admin.update_date')
                        </label>
                    </div>
                    <div class="col-sm-8 p-2">
                        <input class="form-control" disabled id="detail-update-date"
                               type="text" name="detail-update-date">
                    </div>
                </div>
                <div class="form-group row m-0">
                    <div class="col-sm-4 p-3">
                        <label for="detail-update-user-id" class="m-0">
                            @lang('agreement_admin.update_user_id')
                        </label>
                    </div>
                    <div class="col-sm-8 p-2">
                        <input class="form-control" disabled id="detail-update-user-id"
                               type="text" name="detail-update-user-id">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
