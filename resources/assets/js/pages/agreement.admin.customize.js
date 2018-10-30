$(document).ready(function () {
    AgreementCustomize.init();
    $('input.filter-control').bind("keypress", function(e) {
        if (e.keyCode == 13 && !$(document.activeElement).is('textarea')) {               
            e.preventDefault();
            return false;
        }
    });
});

var AgreementCustomize = function() {

    var actionUrl;
    var table;
    var updateAgreementCustomizeDialog = $('#updateAgreementCustomizeDialog');
    var deleteAgreementCustomizeDialog = $('#deleteAgreementCustomizeDialog');
    var updateAgreementCustomizeForm = $('#updateAgreementCustomizeForm');
    var updateAgreementCustomizeButton = $('#update-agreement-customize-button');
    var deleteAgreementCustomizeButton = $('#delete-agreement-customize-button');

    var updateContent = $('#update-content');
    var updateSortNo = $('#update-sortNo');
    var updateOfficialCorpName = $('#update-officialCorpName');
    var updateTableKind = $('#update-tableKind');

    var deleteOfficialCorpName = $('#delete-officialCorpName');
    var deleteTableKind = $('#delete-tableKind');
    var deleteContent = $('#delete-content');
    var deleteSortNo = $('#delete-sortNo');
    var deleteEditKind = $('#delete-editKind');

    var successAlert = $('#success-alert');

    /**
     * init elements, events on page
     *
     */
    var init = function () {
        setAutofocus();
        initTable();
        initDeleteFlow();
        initUpdateFlow();
    };

    var setAutofocus = function () {
        updateAgreementCustomizeDialog.on('shown.bs.modal', function () {
            $('#update-content').focus();
        });
        deleteAgreementCustomizeDialog.on('shown.bs.modal', function () {
            $('#delete-content').focus();
        });
    };

    /**
     * Init datatables
     *
     */
    var initTable = function () {
        $.fn.dataTable.ext.errMode = 'none';
        table = $('#datalist').DataTable({
            order: [],
            tabIndex: -1,
            processing: true,
            serverSide: true,
            ajax: {
                url: GET_DATA_URL,
                dataType: "json",
                type: "GET",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            },
            select: {
                info: false,
                style: 'single',
                className: 'bg-info'
            },
            dom: '<"top"f<"d-flex justify-content-center dataTables_pagination"ipl><"clear">>rt<"bottom"<"d-flex justify-content-center dataTables_pagination"ipl><"clear">>',
            lengthMenu: [15, 30, 50, 75, 100],
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

                $(".data-table-paging-text").remove();
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
                lengthMenu: "_MENU_",
                searchPlaceholder: KEY_WORD_SEARCH_FROM_THE_WHOLE,
                zeroRecords: ZERO_RECORDS,
                processing: PROCESSING
            },
            columns: [
                {data: "official_corp_name", name: 'official_corp_name', width: "20%"},
                {data: "table_kind", name: 'table_kind', width: "10%"},
                {data: "edit_kind", name: 'edit_kind', width: "10%"},
                {data: "content", name: 'content', width: "30%"},
                {data: "sort_no", name: 'sort_no', width: "10%"},
                {data: "id", searchable: false, sortable: false, width: "10%"},
                {data: "id", searchable: false, sortable: false, width: "10%"},
            ],
            columnDefs: [
                {
                    className: "text-center",
                    targets: 1
                },
                {
                    className: "text-center",
                    targets: 2
                },
                {
                    className: "text-center",
                    targets: 4
                },
                {
                    render: function (data, type, row) {
                        var buttonData = "data-update-url= '" + row.update_url
                            + "' data-official-corp-name= '" + row.official_corp_name
                            + "' data-table-kind= '" + row.table_kind
                            + "' data-content= '" + row.content
                            + "' data-sort-no= '" + row.sort_no + "'";
                        return '<button ' + buttonData + ' class="agreement-btn show-popup-update-agreement-customize-button">' +
                            '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>' +
                            '</button>';
                    },
                    className: "text-center",
                    targets: 5
                },
                {
                    render: function (data, type, row) {
                        var buttonData = "data-delete-url= '" + row.delete_url
                            + "' data-official-corp-name= '" + row.official_corp_name
                            + "' data-table-kind= '" + row.table_kind
                            + "' data-content= '" + row.content
                            + "' data-sort-no= '" + row.sort_no
                            + "' data-edit-kind= '" + row.edit_kind + "'";
                        return '<button ' + buttonData + ' class="agreement-btn show-popup-delete-agreement-customize-button">' +
                            '<i class="fa fa-minus" aria-hidden="true"></i>' +
                            '</button>';
                    },
                    className: "text-center",
                    targets: 6
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

        table.on( 'draw', function () {
            $('.show-popup-update-agreement-customize-button').on('click', function () {
                actionUrl = getDataValue(this, 'update-url');
                setValue(updateOfficialCorpName, getDataValue(this,'official-corp-name'));
                setValue(updateTableKind, getDataValue(this,'table-kind'));
                setValue(updateContent, getDataValue(this,'content'));
                setValue(updateSortNo, getDataValue(this,'sort-no'));

                $('#updateAgreementCustomizeForm').validate().destroy();
                FormUtil.validate('#updateAgreementCustomizeForm');
                updateAgreementCustomizeButton.prop('disabled', false);

                showPopup(updateAgreementCustomizeDialog);
            });

            $('.show-popup-delete-agreement-customize-button').on('click', function () {
                actionUrl = getDataValue(this, 'delete-url');
                setValue(deleteOfficialCorpName, getDataValue(this,'official-corp-name'));
                setValue(deleteTableKind, getDataValue(this,'table-kind'));
                setValue(deleteContent, getDataValue(this,'content'));
                setValue(deleteSortNo, getDataValue(this,'sort-no'));
                setValue(deleteEditKind, getDataValue(this, 'edit-kind'));

                $('#deleteAgreementCustomizeForm').validate().destroy();
                FormUtil.validate('#deleteAgreementCustomizeForm');
                deleteAgreementCustomizeButton.prop('disabled', false);

                showPopup(deleteAgreementCustomizeDialog);
            });

        });

        $('.filter-control').on('click', function (e) {
            e.stopPropagation();
        });

    };

    /**
     * Init delete flow include popup confirm and ajax action when click delete on confirm popup
     *
     */
    var initDeleteFlow = function () {
        deleteAgreementCustomizeButton.click(function() {
            if ($('#deleteAgreementCustomizeForm').valid()) {
                var confirmPopup = new popupCommon(1, {msg: CONFIRM_DELETE_CONTENT, close: NO, confirm: YES});
                var confirmPopupHtml = confirmPopup.renderView();
                var $ppConfirm = $(confirmPopupHtml);
                showPopup($ppConfirm);
                $ppConfirm.find('.st-pp-confirm').one('click', function () {
                    hidePopup($ppConfirm);
                    deleteAgreementCustomizeButton.prop('disabled', true);
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajaxSetup({
                        headers: {'X-CSRF-Token': CSRF_TOKEN}
                    });
                    $.ajax({
                        url: actionUrl,
                        type: 'DELETE',
                        dataType: 'JSON',
                        success: function (response) {
                            if (response.type === 'SUCCESS') {
                                hidePopup(deleteAgreementCustomizeDialog);
                                showSuccessAlert(response.content);
                            }
                            deleteAgreementCustomizeButton.prop('disabled', false);
                        },
                        error: function (err) {
                            console.log(err.message);
                            deleteAgreementCustomizeButton.prop('disabled', false);
                        }
                    });
                });
            }
        });
    };

    /**
     * Init update flow include popup confirm and ajax action when click update on confirm popup
     *
     */
    var initUpdateFlow = function () {
        updateAgreementCustomizeButton.click(function() {
            if (updateAgreementCustomizeForm.valid()) {
                var confirmPopup = new popupCommon(1, {msg: CONFIRM_UPDATE_CONTENT, close: NO, confirm: YES});
                var confirmPopupHtml = confirmPopup.renderView();
                var $ppConfirm = $(confirmPopupHtml);
                showPopup($ppConfirm);
                $ppConfirm.find('.st-pp-confirm').one('click', function () {
                    updateAgreementCustomizeButton.prop('disabled', true);
                    hidePopup($ppConfirm);
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var data = {
                        content: updateContent.val(),
                        sort_no: FormUtil.convertToHalfWidth(updateSortNo.val())
                    };

                    $.ajaxSetup({
                        headers: {'X-CSRF-Token': CSRF_TOKEN}
                    });
                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: data,
                        dataType: 'JSON',
                        success: function (response) {
                            if (response.type === 'SUCCESS') {
                                hidePopup(updateAgreementCustomizeDialog);
                                showSuccessAlert(response.content);
                            }
                            updateAgreementCustomizeButton.prop('disabled', false);
                        },
                        error: function (err) {
                            console.log(err.message);
                            updateAgreementCustomizeButton.prop('disabled', false);
                        }
                    });
                });
            }
        });
    };

    /**
     * show success message, and hide message after 3 seconds
     *
     * @param  content:  content of message need to show
     * @return      null
     */
    var showSuccessAlert = function (content) {
        successAlert.switchClass('d-none', 'd-block');
        successAlert.text(content);
        table.ajax.reload(null, false);
    };

    /**
     * show popup
     *
     * @param  popup:  popup element need to show
     * @return      null
     */
    var showPopup = function (popup) {
        popup.modal('show');
    };

    /**
     * hide popup
     *
     * @param  popup:  popup element need to hide
     * @return      null
     */
    var hidePopup = function (popup) {
        popup.modal('hide');
    };

    /**
     * set value for html element
     *
     * @param  element:  element need to set value
     * @param  value: value of element
     * @return      null
     */
    var setValue = function (element, value) {
        element.val(value);
    };

    /**
     * Returns data from html tag
     *
     * @param  thisPointer:  an absolute URL giving the base location of the image
     * @param  valueName: html tag key without 'data-' prefix. Ex: data-delete-url -> delete-url
     * @return      value of key
     */
    var getDataValue = function (thisPointer, valueName) {
        return $(thisPointer).data(valueName);
    };

    return {
        init: init
    }

}();
