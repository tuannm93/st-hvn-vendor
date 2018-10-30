$(document).ready(function () {
    AgreementProvision.init();
});

var AgreementProvision = function () {

    var deleteUrl;
    var provisionUpdateId;
    var itemUpdateId;
    var progressBlock = $('.progress-block');
    var progressEl = $('.progress');

    var successMessageDiv = $('#success-alert');

    var init = function () {
        setAutofocus();
        versionUp();
        openPopupCreateAgreementProvision();
        onCreateAgreementProvision();
        openPopupCreateAgreementProvisionItem();
        onCreateAgreementProvisionItem();

        bindEditEvent();
        onUpdateAgreementProvision();
        onUpdateAgreementProvisionItem();
        onDeleteEvent();
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

    var versionUp = function () {
        $('#version-up-button').click( function () {
            var confirmPopup = new popupCommon(1, {msg: WOULD_YOU_PLEASE_REVISE_THE_CONTRACT, close: NO, confirm: YES});
            var confirmPopupHtml = confirmPopup.renderView();
            var $ppConfirm = $(confirmPopupHtml);
            $ppConfirm.modal('show');
            $ppConfirm.find('.st-pp-confirm').one('click', function () {
                $ppConfirm.modal('hide');
                $.ajax({
                    url: urlVersionUp,
                    success: function (response) {
                        setSuccessMessage(response.content);
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
                        console.log(err.message);
                    }
                });
            })
        })
    };

    var openPopupCreateAgreementProvision = function () {
        $('#open-popup-create-agreement-provision-button').click( function () {
            $('#provisions').val("");
            $('#sortNo').val(0);
            $('#create-agreement-provision-button').prop('disabled', false);
            $('#createAgreementProvisionFormId').validate().destroy();
            FormUtil.validate('#createAgreementProvisionFormId');

            $('#createAgreementProvisionId').modal('show');
        });
    };

    var onCreateAgreementProvision = function () {
        $('#create-agreement-provision-button').click(function () {
            if ($('#createAgreementProvisionFormId').valid()) {
                $('#create-agreement-provision-button').prop('disabled', true);
                var agreementProvision = new Object();
                agreementProvision['provisions'] = $('#provisions').val();
                agreementProvision['sort_no'] = FormUtil.convertToHalfWidth($('#sortNo').val());
                $.ajax({
                    url: urlCreateAgreementProvision,
                    type: 'POST',
                    data: {agreementProvision: agreementProvision},
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.type === 'SUCCESS') {
                            $('#createAgreementProvisionId').modal('hide');
                            setSuccessMessage(response.content);
                            loadAgreementProvisionHtml();
                        }
                        $('#create-agreement-provision-button').prop('disabled', false);
                    },
                    error: function (response) {
                        if (response.status == 422) {
                            var errors = response.responseJSON.errors;
                            var message = '';
                            for (var error in errors) {
                                message += errors[error] + '</br>';
                            }
                            console.log(message);
                        }
                        $('#create-agreement-provision-button').prop('disabled', false);
                    }
                });
            }
        });
    };

    var openPopupCreateAgreementProvisionItem = function () {
        $('#open-popup-create-agreement-provision-item-button').click( function () {
            var provisionItem = $('#provision_item');

            $.ajax({
                url: urlProvisionData,
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

    var onCreateAgreementProvisionItem = function () {
        $('#create-agreement-provision-item-button').click(function () {
            if ($('#createAgreementProvisionItemFormId').valid()) {
                $('#create-agreement-provision-item-button').prop('disabled', true);
                var agreementProvisionItem = new Object();
                agreementProvisionItem['agreement_provisions_id'] = $('#provision_item').val();
                agreementProvisionItem['item'] = $('#item').val();
                agreementProvisionItem['sort_no'] = FormUtil.convertToHalfWidth($('#sortNoItem').val());
                $.ajax({
                    url: urlCreateAgreementProvisionItem,
                    type: 'POST',
                    data: {agreementProvisionItem: agreementProvisionItem},
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.type === 'SUCCESS') {
                            $('#createAgreementProvisionItemId').modal('hide');
                            setSuccessMessage(response.content);
                            loadAgreementProvisionHtml();
                        }
                        $('#create-agreement-provision-item-button').prop('disabled', false);
                    },
                    error: function (response) {
                        if (response.status == 422) {
                            var errors = response.responseJSON.errors;
                            var message = '';
                            for (var error in errors) {
                                message += errors[error] + '</br>';
                            }
                            console.log(message);
                        }
                        $('#create-agreement-provision-item-button').prop('disabled', false);
                    }
                });
            }
        });
    };

    var loadAgreementProvisionHtml = function () {
        $.ajax({
            url: urlGetAgreementProvisionData,
            type: 'GET',
            success: function (response) {
                $('#agreementProvisionDataId').html(response);
                bindEditEvent();
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
                console.log(err.message);
            }
        });
    };


    var onUpdateAgreementProvision = function () {
        $('#update-agreement-provision_update-agreement-provision-button').click(function () {
            if ($("#update-agreement-provision-form").valid()) {
                var agreementProvision = new Object();
                agreementProvision['id'] = provisionUpdateId;
                agreementProvision['provisions'] = $('#update-agreement-provision_new-provision').val();
                agreementProvision['sort_no'] = FormUtil.convertToHalfWidth($('#update-agreement-provision_new-sort-no').val());
                $('#update-agreement-provision_update-agreement-provision-button').prop('disabled', true);
                $.ajax({
                    url: urlCreateAgreementProvision,
                    type: 'POST',
                    data: {agreementProvision: agreementProvision},
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.type === 'SUCCESS') {
                            $('#update-agreement-provision-dialog').modal('hide');
                            setSuccessMessage(response.content);
                            loadAgreementProvisionHtml();
                        }
                        $('#update-agreement-provision_update-agreement-provision-button').prop('disabled', false);
                    },
                    error: function (response) {
                        if (response.status == 422) {
                            var errors = response.responseJSON.errors;
                            var message = '';
                            for (var error in errors) {
                                message += errors[error] + '</br>';
                            }
                            console.log(message);
                        }
                        $('#update-agreement-provision_update-agreement-provision-button').prop('disabled', false);
                    }
                });
            }
        });
    };

    var onUpdateAgreementProvisionItem = function () {
        $('#update-agreement-provision-item_update-agreement-provision-button').click(function () {
            if ($("#update-agreement-provision-item-form").valid()) {
                var agreementProvisionItem = new Object();
                agreementProvisionItem['id'] = itemUpdateId;
                agreementProvisionItem['item'] = $('#update-agreement-provision-item_new-item').val();
                agreementProvisionItem['sort_no'] = FormUtil.convertToHalfWidth($('#update-agreement-provision-item_new-sort-no').val());
                $('#update-agreement-provision-item_update-agreement-provision-button').prop('disabled', true);

                $.ajax({
                    url: urlCreateAgreementProvisionItem,
                    type: 'POST',
                    data: {agreementProvisionItem: agreementProvisionItem},
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.type === 'SUCCESS') {
                            $('#update-agreement-provision-item-dialog').modal('hide');
                            setSuccessMessage(response.content);
                            loadAgreementProvisionHtml();
                        }
                        $('#update-agreement-provision-item_update-agreement-provision-button').prop('disabled', false);
                    },
                    error: function (response) {
                        if (response.status == 422) {
                            var errors = response.responseJSON.errors;
                            var message = '';
                            for (var error in errors) {
                                message += errors[error] + '</br>';
                            }
                            console.log(message);
                        }
                        $('#update-agreement-provision-item_update-agreement-provision-button').prop('disabled', false);
                    }
                });
            }
        });
    };

    var onDeleteEvent = function () {
        $('#update-agreement-provision_delete-agreement-provision-button').click(function () {
            deleteAjax();
        });

        $('#update-agreement-provision-item_delete-agreement-provision-button').click(function () {
            deleteAjax();
        });
    };

    var deleteAjax = function () {
        var confirmPopup = new popupCommon(1, {msg: CONTENT_CONFIRM_DELETE, close: NO, confirm: YES});
        var confirmPopupHtml = confirmPopup.renderView();
        var $ppConfirm = $(confirmPopupHtml);
        $ppConfirm.modal('show');
        $ppConfirm.find('.st-pp-confirm').one('click', function () {
            $ppConfirm.modal('hide');
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                success: function (response) {
                    setSuccessMessage(response.content);
                    $('#update-agreement-provision-dialog').modal('hide');
                    $('#update-agreement-provision-item-dialog').modal('hide');
                    loadAgreementProvisionHtml();
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
                    console.log(err.message);
                }
            });
        })
    };

    var bindEditEvent = function () {
        $('.open-edit-provision-dialog').on('click', function () {
            provisionUpdateId = getDataValue(this, 'id');
            deleteUrl = getDataValue(this, 'delete-url');
            $('#update-agreement-provision_provision-before-change').val(getDataValue(this, 'provision'));
            $('#update-agreement-provision_new-provision').val(getDataValue(this, 'provision'));
            $('#update-agreement-provision_sort-no-before-change').val(getDataValue(this, 'sort-no'));
            $('#update-agreement-provision_new-sort-no').val(getDataValue(this, 'sort-no'));

            $("#update-agreement-provision-form").validate().destroy();
            FormUtil.validate('#update-agreement-provision-form');

            $('#update-agreement-provision_update-agreement-provision-button').prop('disabled', false);
            $('#update-agreement-provision-dialog').modal('show');
        });

        $('.open-edit-item-dialog').on('click', function () {
            itemUpdateId = getDataValue(this, 'id');
            deleteUrl = getDataValue(this, 'delete-url');
            $('#update-agreement-provision-item_provision').val(getDataValue(this, 'provision'));
            $('#update-agreement-provision-item_item-before-change').html(getDataValue(this, 'item'));
            $('#update-agreement-provision-item_new-item').val(getDataValue(this, 'item'));
            $('#update-agreement-provision-item_sort-no-before-change').val(getDataValue(this, 'sort-no'));
            $('#update-agreement-provision-item_new-sort-no').val(getDataValue(this, 'sort-no'));

            $("#update-agreement-provision-item-form").validate().destroy();
            FormUtil.validate('#update-agreement-provision-item-form');

            $('#update-agreement-provision-item_update-agreement-provision-button').prop('disabled', false);
            $('#update-agreement-provision-item-dialog').modal('show');
        });
    };

    var getDataValue = function (thisPointer, valueName) {
        return $(thisPointer).data(valueName);
    };

    var setSuccessMessage = function (content) {
        successMessageDiv.html(content);
        successMessageDiv.switchClass('d-none', 'd-block');
    };


    return {
        init: init,
    }
}();
