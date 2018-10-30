$(document).ready(function() {
    AgreementAdminCustomizeCorp.init();
});

var AgreementAdminCustomizeCorp = function () {

    var provisionId;
    var itemId;
    var customizeFlag;
    var corpId = $('#agreement-customize-corp').data('corp-id');
    var provisionsUrl = $('#agreement-customize-corp').data('provisions-url');
    var updateDataUrl = $('#agreement-customize-corp').data('update-data-url');
    var successMessageDiv = $('#success-alert');

    var init = function () {
        setAutofocus();
        initCreatePopup();
        initUpdatePopup();
        initUpdateCustomizeProvision();
        initUpdateCustomizeItem();
        initDeleteAction();
    };

    var setAutofocus = function () {
        $('#createAgreementProvisionId').on('shown.bs.modal', function () {
            $('#provisions').focus();
        });
        $('#createAgreementProvisionItemId').on('shown.bs.modal', function () {
            $('#item').focus();
        });

        $('#update-agreement-provision-dialog').on('shown.bs.modal', function () {
            $('#update-agreement-provision_new-provision').focus();
        });
        $('#update-agreement-provision-item-dialog').on('shown.bs.modal', function () {
            $('#update-agreement-provision-item_new-item').focus();
        });
    };

    var initCreatePopup = function () {
        $('#show-create-customize-provison-popup').on('click', function () {
            provisionId = 0;
            customizeFlag = false;

            $('#provisions').val("");
            $('#sortNo').val(0);

            $('#createAgreementProvisionFormId').validate().destroy();
            FormUtil.validate('#createAgreementProvisionFormId');
            $('#create-agreement-provision-button').prop('disabled', false);
            $('#createAgreementProvisionId').modal('show');
        });

        $('#show-create-customize-provison-item-popup').on('click', function () {
            itemId = 0;
            provisionId = 0;
            customizeFlag = false;

            var progressBlock = $('.progress-block');
            var progressEl = $('.progress');
            var provisionItem = $('#provision_item');

            $.ajax({
                url: provisionsUrl,
                success: function (response) {
                    provisionItem.html('');
                    for (var i in response) {
                        provisionItem.append(new Option(response[i].provisions, response[i].id));
                    }

                    $('#item').val("");
                    $('#sortNoItem').val(0);
                    $('#create-agreement-provision-item-button').prop('disabled', false);
                    $('#createAgreementProvisionItemFormId').validate().destroy();
                    FormUtil.validate('#createAgreementProvisionItemFormId');
                    $('#create-agreement-provision-item-button').prop('disabled', false);
                    $('#createAgreementProvisionItemId').modal('show');
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        var percentComplete = evt.loaded / evt.total;
                        progressEl.css({
                            width: percentComplete * 100 + "%"
                        });
                    }, false);
                    xhr.addEventListener("progress", function (evt) {
                        var percentComplete = evt.loaded / evt.total;
                        progressEl.css({
                            width: percentComplete * 100 + "%"
                        });
                    }, false);
                    return xhr;
                },
                beforeSend: function () {
                    progressBlock.show();
                    progressEl.show();
                },
                complete: function () {
                    progressBlock.hide();
                    progressEl.hide();
                },
                error: function (err) {
                    console.log (err.message);
                }
            });

        });
    };

    var initUpdatePopup = function () {
        $('.edit-customize-provision').on('click', function() {
            provisionId = $(this).data("id");
            customizeFlag = $(this).data("customize-flag") == 1 ? true : false;

            $('#update-agreement-provision_provision-before-change').val($(this).data("content"));
            $('#update-agreement-provision_sort-no-before-change').val($(this).data("sort-no"));
            $('#update-agreement-provision_new-provision').val($(this).data("content"));
            $('#update-agreement-provision_new-sort-no').val($(this).data("sort-no"));

            $('#update-agreement-provision-form').validate().destroy();
            FormUtil.validate('#update-agreement-provision-form');
            $('#update-agreement-provision_update-agreement-provision-button').prop('disabled', false);
            $('#update-agreement-provision_delete-agreement-provision-button').prop('disabled', false);
            $('#update-agreement-provision-dialog').modal('show');
        });

        $('.edit-customize-provision-item').on('click', function () {
            provisionId = $(this).data("provision-id");
            itemId = $(this).data("item-id");
            customizeFlag = $(this).data("customize-flag") == 1 ? true : false;

            $('#update-agreement-provision-item_provision').val($(this).data('provision'));
            $('#update-agreement-provision-item_item-before-change').val($(this).data("content"));
            $('#update-agreement-provision-item_new-item').val($(this).data("content"));
            $('#update-agreement-provision-item_sort-no-before-change').val($(this).data("sort-no"));
            $('#update-agreement-provision-item_new-sort-no').val($(this).data("sort-no"));

            $('#update-agreement-provision-item-form').validate().destroy();
            FormUtil.validate('#update-agreement-provision-item-form');
            $('#update-agreement-provision-item_update-agreement-provision-button').prop('disabled', false);
            $('#update-agreement-provision-item_delete-agreement-provision-button').prop('disabled', false);
            $('#update-agreement-provision-item-dialog').modal('show');
        });
    };

    var initUpdateCustomizeProvision = function () {
        $('#create-agreement-provision-button').on('click', function () {
            if ($('#createAgreementProvisionFormId').valid()) {
                $('#create-agreement-provision-button').prop('disabled', true);
                var data = {
                    content: $('#provisions').val(),
                    sort_no: FormUtil.convertToHalfWidth($('#sortNo').val()),
                    corp_id: corpId,
                    edit_kind: ADD,
                    customize_flag: customizeFlag,
                    id: provisionId
                };
                updateAjax(data, UPDATE_PROVISION_URL, function () {
                    $('#create-agreement-provision-button').prop('disabled', false);
                    $('#createAgreementProvisionId').modal('hide');
                });
            }
        });

        $('#update-agreement-provision_update-agreement-provision-button').on('click', function () {
            if ($('#update-agreement-provision-form').valid()) {
                var confirmPopup = new popupCommon(1, {
                    msg: ARE_YOU_SURE_YOU_WANT_TO_REGISTER,
                    close: NO,
                    confirm: YES
                });
                var confirmPopupHtml = confirmPopup.renderView();
                var $ppConfirm = $(confirmPopupHtml);
                $ppConfirm.modal('show');
                $ppConfirm.find('.st-pp-confirm').one('click', function () {
                    $ppConfirm.modal('hide');
                    $('#update-agreement-provision_update-agreement-provision-button').prop('disabled', true);
                    var data = {
                        content: $('#update-agreement-provision_new-provision').val(),
                        sort_no: FormUtil.convertToHalfWidth($('#update-agreement-provision_new-sort-no').val()),
                        corp_id: corpId,
                        edit_kind: UPDATE,
                        customize_flag: customizeFlag,
                        id: provisionId
                    };
                    updateAjax(data, UPDATE_PROVISION_URL, function () {
                        $('#update-agreement-provision_update-agreement-provision-button').prop('disabled', false);
                        $('#update-agreement-provision-dialog').modal('hide');
                    });
                });
            }
        });
    };

    var initUpdateCustomizeItem = function () {
        $('#create-agreement-provision-item-button').on('click', function () {
            if ($('#createAgreementProvisionItemFormId').valid()) {
                $('#create-agreement-provision-item-button').prop('disabled', true);
                var data = {
                    provision_id: $('#provision_item').val(),
                    content: $('#item').val(),
                    sort_no: FormUtil.convertToHalfWidth($('#sortNoItem').val()),
                    corp_id: corpId,
                    edit_kind: ADD,
                    customize_flag: customizeFlag,
                    id: itemId
                };
                updateAjax(data, UPDATE_ITEM_URL, function () {
                    $('#create-agreement-provision-item-button').prop('disabled', false);
                    $('#createAgreementProvisionItemId').modal('hide');
                });
            }
        });

        $('#update-agreement-provision-item_update-agreement-provision-button').on('click', function () {
            if ($('#update-agreement-provision-item-form').valid()) {
                var confirmPopup = new popupCommon(1, {
                    msg: ARE_YOU_SURE_YOU_WANT_TO_REGISTER,
                    close: NO,
                    confirm: YES
                });
                var confirmPopupHtml = confirmPopup.renderView();
                var $ppConfirm = $(confirmPopupHtml);
                $ppConfirm.modal('show');
                $ppConfirm.find('.st-pp-confirm').one('click', function () {
                    $ppConfirm.modal('hide');
                    $('#update-agreement-provision-item_update-agreement-provision-button').prop('disabled', true);
                    var data = {
                        provision_id: provisionId,
                        content: $('#update-agreement-provision-item_new-item').val(),
                        sort_no: FormUtil.convertToHalfWidth($('#update-agreement-provision-item_new-sort-no').val()),
                        corp_id: corpId,
                        edit_kind: UPDATE,
                        customize_flag: customizeFlag,
                        id: itemId
                    };
                    updateAjax(data, UPDATE_ITEM_URL, function () {
                        $('#update-agreement-provision-item_update-agreement-provision-button').prop('disabled', false);
                        $('#update-agreement-provision-item-dialog').modal('hide');
                    });
                });
            }
        });
    };

    var initDeleteAction = function () {
        $('#update-agreement-provision_delete-agreement-provision-button').on('click', function () {
            var confirmPopup = new popupCommon(1, {
                msg: CONFIRM_DELETE_CONTENT,
                close: NO,
                confirm: YES
            });
            var confirmPopupHtml = confirmPopup.renderView();
            var $ppConfirm = $(confirmPopupHtml);
            $ppConfirm.modal('show');
            $ppConfirm.find('.st-pp-confirm').one('click', function () {
                $ppConfirm.modal('hide');
                $('#update-agreement-provision_delete-agreement-provision-button').prop('disabled', true);
                var data = {
                    corp_id: corpId,
                    customize_flag: customizeFlag,
                    id: provisionId,
                    sort_no: $('#update-agreement-provision_sort-no-before-change').val(),
                    content: $('#update-agreement-provision_provision-before-change').val()
                };
                updateAjax(data, DELETE_PROVISION_URL, function () {
                    $('#update-agreement-provision_delete-agreement-provision-button').prop('disabled', false);
                    $('#update-agreement-provision-dialog').modal('hide');
                });
            });
        });

        $('#update-agreement-provision-item_delete-agreement-provision-button').on('click', function () {
            var confirmPopup = new popupCommon(1, {
                msg: CONFIRM_DELETE_CONTENT,
                close: NO,
                confirm: YES
            });
            var confirmPopupHtml = confirmPopup.renderView();
            var $ppConfirm = $(confirmPopupHtml);
            $ppConfirm.modal('show');
            $ppConfirm.find('.st-pp-confirm').one('click', function () {
                $ppConfirm.modal('hide');
                $('#update-agreement-provision-item_delete-agreement-provision-button').prop('disabled', true);
                var data = {
                    corp_id: corpId,
                    customize_flag: customizeFlag,
                    id: itemId,
                    provision_id: provisionId,
                    sort_no: $('#update-agreement-provision-item_sort-no-before-change').val(),
                    content: $('#update-agreement-provision-item_item-before-change').val()
                };

                console.log(data);
                updateAjax(data, DELETE_ITEM_URL, function () {
                    $('#update-agreement-provision-item_delete-agreement-provision-button').prop('disabled', false);
                    $('#update-agreement-provision-item-dialog').modal('hide');
                });
            });
        });
    };

    var updateAjax = function(data, url, callback) {
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (response) {
                callback();
                setSuccessMessage(response.content);
                getUptodateData();
            }
        });
    };

    var getUptodateData = function () {
        $.ajax({
            url: updateDataUrl,
            type: 'GET',
            success: function (response) {
                $('#agreement-customize-data-div').html(response);
                initUpdatePopup();
            }
        });
    };

    var setSuccessMessage = function (content) {
        successMessageDiv.html(content);
        successMessageDiv.switchClass('d-none', 'd-block');
    };

    return {
        init: init
    }

}();