$(document).ready(function () {
    AgreementAdminCategories.init();
    $('input.filter-control').bind("keypress", function(e) {
        if (e.keyCode == 13 && !$(document.activeElement).is('textarea')) {               
            e.preventDefault();
            return false;
        }
    });
});

var AgreementAdminCategories = function () {

    var table;

    var progressBlock = $('.progress-block');
    var progressEl = $('.progress');
    var successMessageDiv = $('#success-message-alert');
    var errorMessageDiv = $('#error-message-alert');
    var addDialog = $('#add-dialog');
    var detailDialog = $('#detail-dialog');
    var addLicenseName = $('#add-license-name');
    var addCertificateRequiredFlag = $('#add-certificate-required-flag');
    var updateDialog = $('#update-dialog');
    var updateLicenseName = $('#update-license-name');
    var updateCertificateRequiredFlag = $('#update-certificate-required-flag');

    var licenseDetailUrl;
    var licenseDeleteUrl;
    var licenseId;
    var licenseName;
    var licenseHaveTo;


    var init = function () {
        setAutofocus();
        initTable();
        initDoubleClickToShowDetailEvent();
        initAddFlow();
        initUpdateFlow();
    };

    var setAutofocus = function () {
        addDialog.on('shown.bs.modal', function () {
            addLicenseName.focus();
        });
        updateDialog.on('shown.bs.modal', function () {
            updateLicenseName.focus();
        })
    };

    var initTable = function () {
        $.fn.dataTable.ext.errMode = 'none';
        table = $('#datalist').DataTable({
            tabIndex: -1,
            order: [],
            processing: true,
            serverSide: true,
            ajax: {
                url: LICENSE_DATA,
                dataType: "json",
                type: "GET",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            },
            dom: '<"top"Bf<"d-flex justify-content-center dataTables_pagination"ipl><"clear">>rt<"bottom"<"d-flex justify-content-center dataTables_pagination"ipl><"clear">>',
            lengthMenu: [15, 30, 50, 75, 100],
            select: {
                info: false,
                style: 'single',
                className: 'bg-info'
            },
            pagingType: "full",
            infoCallback: function (settings, start, end, max, total, pre) {
                var api = this.api();
                var pageInfo = api.page.info();

                var start = pageInfo.start + 1;
                var end = pageInfo.end;
                if (end == 0) start = 0;
                var page = pageInfo.page + 1;
                var pages = pageInfo.pages;
                if (pages == 0) pages = 1;
                var strResultRange = EXPRESS + '：' + start + '-' + end;
                var strPaginateInfo = PAGE + '：' + page + '/' + pages;

                $("#datalist_paginate, .dataTables_length").addClass('d-inline-flex');

                $(".pagination").addClass('justify-content-center m-0');
                $("#datalist_filter").addClass('text-center');
                $("<li class='paginate_button page-item paginate_info disabled'><span class='page-link'>" + strPaginateInfo + "</span></li>").insertAfter(".paginate_button.previous");
                $("<li class='paginate_button page-item result_range disabled'><span class='page-link'>" + strResultRange + "</span></li>").insertAfter(".paginate_button.previous");
                $(".dataTables_length .form-control").removeClass('form-control form-control-sm').addClass('ml-3');
                $(".dataTables_length >label").addClass('m-0');
                $("#datalist_filter .form-control").removeClass('form-control-sm').addClass('form-control-lg');

                $('.data-table-paging-text').remove();
                $(".dataTables_length").append("<div class='paginate_button disabled data-table-paging-text'><span>" + DISPLAY_BY_ITEM + "</span></div>");

                return "";
            },
            language: {
                paginate: {
                    first: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
                    last: '<span class="btn-custom"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>'
                },
                search: "_INPUT_",
                searchPlaceholder: KEY_WORD_SEARCH_FROM_THE_WHOLE,
                lengthMenu: "_MENU_",
                zeroRecords: ZERO_RECORDS,
                processing: PROCESSING
            },
            columns: [
                {data: "id", name: 'id'},
                {data: "name", name: 'name'},
                {data: "certificate_required_flag_converted", name: 'certificate_required_flag_converted'},
            ],
            columnDefs: [
                {
                    className: "text-center",
                    targets: 0
                },
                {
                    className: "text-center",
                    targets: 2
                },
            ],
            buttons: [
                {
                    text: '<span class="fa fa-plus"></span>   ' + REGISTRATION,
                    "filter": "applied", page: 'all',
                    action: function () {
                        // reset data
                        addLicenseName.val('');
                        addCertificateRequiredFlag.prop('checked', false);
                        $('#add-license-button').prop('disabled', false);

                        $('#add-license-form').validate().destroy();
                        FormUtil.validate('#add-license-form');
                        showPopup(addDialog);
                    }
                },
                {
                    text: '<span class="fa fa-search"></span>   ' + REFERENCE,
                    extend: 'selectedSingle',
                    action: function () {
                        showDetailDialog();
                    }
                },
                {
                    text: '<span class="fa fa-refresh"></span>   ' + UPDATED,
                    extend: 'selectedSingle',
                    action: function () {
                        $('#update-license-id').val(licenseId);
                        updateLicenseName.val(licenseName);
                        updateCertificateRequiredFlag.prop("checked", licenseHaveTo);
                        $('#update-license-button').prop('disabled', false);

                        $('#update-license-form').validate().destroy();
                        FormUtil.validate('#update-license-form');
                        showPopup(updateDialog);
                    }
                },
                {
                    text: '<span class="fa fa-minus"></span>   ' + DELETE,
                    extend: 'selectedSingle',
                    action: function () {
                        var confirmPopup = new popupCommon(1, {msg: CONFIRM_DELETE_CONTENT, close: NO, confirm: YES});
                        var confirmPopupHtml = confirmPopup.renderView();
                        var $ppConfirm = $(confirmPopupHtml);
                        showPopup($ppConfirm);
                        $ppConfirm.find('.st-pp-confirm').one('click', function () {
                            hidePopup($ppConfirm);
                            deleteLicense();
                        })
                    }
                },
            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    $('input', this.header()).on('keyup change', function () {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
                });
            }
        });

        $('.filter-control').on('click', function (e) {
            e.stopPropagation();
        });

        // Get mCategoriesDetailUrl & mCategoriesUpdateUrl of the current selected row
        table.on('select', function (e, dt, type, indexes) {
            if (type === 'row') {
                licenseDetailUrl = table.row(indexes).data().detail_url;
                licenseDeleteUrl = table.row(indexes).data().delete_url;

                licenseId = table.row(indexes).data().id;
                licenseName = table.row(indexes).data().name;
                licenseHaveTo = table.row(indexes).data().certificate_required_flag_converted === HAVE_TO ? true : false;
            }
        });

    };

    var initDoubleClickToShowDetailEvent = function () {
        $('#datalist tbody').on('dblclick', 'tr', function () {
            table.row( this ).select();
            $('#update-license-button').prop('disabled', false);
            showDetailDialog();
        });
    };

    var deleteLicense = function () {
        $.ajax({
            url: licenseDeleteUrl,
            type: 'DELETE',
            success: function (response) {
                if (response.type === 'SUCCESS') {
                    showSuccessMessage(response.content);
                } else {
                    showErrorMessage();
                }

                table.ajax.reload(null, false);
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
        })
    };

    var showDetailDialog = function () {
        $.ajax({
            url: licenseDetailUrl,
            success: function (response) {
                $('#detail-license-id').val(response.id);
                $('#detail-license-name').val(response.name);
                $('#detail-certificate-required-flag').val(response.certificate_required_flag_converted);
                $('#detail-update-date').val(response.update_date);
                $('#detail-update-user-id').val(response.user_name);

                showPopup(detailDialog);
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
    };

    var initAddFlow = function () {
        $('#add-license-button').click(function () {
            if ($('#add-license-form').valid()) {
                var confirmPopup = new popupCommon(1, {
                    msg: ARE_YOU_SURE_YOU_WANT_TO_REGISTER,
                    close: NO,
                    confirm: YES
                });
                var confirmPopupHtml = confirmPopup.renderView();
                var $ppConfirm = $(confirmPopupHtml);
                showPopup($ppConfirm);
                $ppConfirm.find('.st-pp-confirm').one('click', function () {
                    hidePopup($ppConfirm);
                    $('#add-license-button').prop('disabled', true);

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var data = {
                        name: addLicenseName.val(),
                        certificate_required_flag: addCertificateRequiredFlag.prop('checked')
                    };

                    $.ajaxSetup({
                        headers: {'X-CSRF-Token': CSRF_TOKEN}
                    });
                    $.ajax({
                        url: ADD_URL,
                        type: 'POST',
                        data: data,
                        success: function (response) {
                            if (response.type === 'SUCCESS') {
                                hidePopup(addDialog);
                                showSuccessMessage(response.content);
                            } else {
                                showErrorMessage();
                            }

                            table.ajax.reload(null, false);
                            $('#add-license-button').prop('disabled', false);
                        },
                        error: function (response) {
                            console.log(response);
                            $('#add-license-button').prop('disabled', false);
                        }
                    })
                })
            }
        })
    };

    var initUpdateFlow = function () {
        $('#update-license-button').click(function () {
            if ($('#update-license-form').valid()) {
                var confirmPopup = new popupCommon(1, {
                    msg: ARE_YOU_SURE_YOU_WANT_TO_REGISTER,
                    close: NO,
                    confirm: YES
                });
                var confirmPopupHtml = confirmPopup.renderView();
                var $ppConfirm = $(confirmPopupHtml);
                showPopup($ppConfirm);
                $ppConfirm.find('.st-pp-confirm').one('click', function () {
                    hidePopup($ppConfirm);
                    $('#update-license-button').prop('disabled', true);

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var data = {
                        id: licenseId,
                        name: updateLicenseName.val(),
                        certificate_required_flag: updateCertificateRequiredFlag.prop('checked')
                    };

                    $.ajaxSetup({
                        headers: {'X-CSRF-Token': CSRF_TOKEN}
                    });
                    $.ajax({
                        url: UPDATE_URL,
                        type: 'PUT',
                        data: data,
                        success: function (response) {
                            if (response.type === 'SUCCESS') {
                                hidePopup(updateDialog);
                                showSuccessMessage(response.content);
                            } else {
                                showErrorMessage();
                            }

                            table.ajax.reload(null, false);
                            $('#update-license-button').prop('disabled', false);
                        },
                        error: function (response) {
                            console.log(response);
                            $('#update-license-button').prop('disabled', false);
                        }
                    })
                })
            }
        })
    };

    var showPopup = function (popup) {
        popup.modal('show');
    };

    var hidePopup = function (popup) {
        popup.modal('hide');
    };

    var showSuccessMessage = function (detail) {
        errorMessageDiv.switchClass('d-block', 'd-none');
        successMessageDiv.html(detail);
        successMessageDiv.switchClass('d-none', 'd-block');
    };

    var showErrorMessage = function () {
        successMessageDiv.switchClass('d-block', 'd-none');
        errorMessageDiv.switchClass('d-none', 'd-block');
    };

    return {
        init: init
    }

}();
