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

    var licenseList;
    var categoriesList;
    var updateCheckedLicenses = new Set();
    var originalCheckedLicenses = new Set();

    var progressBlock = $('.progress-block');
    var progressEl = $('.progress');

    var categoryDetail = {};
    var categoryUpdateInfoUrl;
    var categoryUpdateActionUrl;
    var categoryName;
    var categoryLicenseConditionType;

    var init = function () {
        initTable();
        initLicenseList();
        initUpdateFlow();
    };

    var initTable = function () {
        $.fn.dataTable.ext.errMode = 'none';
        categoriesList = $('#datalist').DataTable({
            tabIndex: -1,
            order: [],
            processing: true,
            serverSide: true,
            ajax: {
                url: DATA_PROCESSING_URL,
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
                {data: "id", name: 'm_categories.id', width: "10%"},
                {data: "genre_name", name: 'm_genres.genre_name', width: "20%"},
                {data: "category_name", name: 'm_categories.category_name', width: "20%"},
                {data: "license_condition_type_converted", name: 'license_condition_type_converted', width: "15%"},
                {data: "license_name", name: 'license_name', width: "30%"},
                {data: "id", sortable: false, width: "5%"}
            ],
            columnDefs: [
                {
                    className: "text-center",
                    targets: 0
                },
                {
                    render: function (data, type, row) {
                        var buttonData = "data-update-info-url= '" + row.update_info_url
                            + "' data-update-action-url= '" + row.update_action_url
                            + "' data-category-name= '" + row.category_name
                            + "' data-license-condition-type= '" + row.license_condition_type_converted + "'";
                        return '<button ' + buttonData + ' class="agreement-btn show-popup-update-agreement-category-button">' +
                            '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>' +
                            '</button>';
                    },
                    className: "text-center",
                    targets: 5
                }
            ],
            buttons: [
                {
                    text: '<span class="fa fa-search"></span>   ' + REFERENCE,
                    extend: 'selectedSingle',
                    action: function () {
                        showDetail();
                    }
                },
                {
                    text: '<span class="fa fa-refresh"></span>   ' + SET_LICENSE,
                    extend: 'selectedSingle',
                    action: function () {
                        editCategory();
                    }
                },
                {
                    text: '<span class="fa fa-file-excel-o"></span>   ' + EXPORT_EXCEL,
                    "filter": "applied", page: 'all',
                    action: function () {
                        var params = this.ajax.params();
                        exportFile(params, EXPORT_EXCEL_URL);
                    }
                },
                {
                    text: '<span class="fa fa-file-text-o"></span>   ' + EXPORT_CSV,
                    "filter": "applied", page: 'all',
                    action: function () {
                        var params = this.ajax.params();
                        exportFile(params, EXPORT_CSV_URL);
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

                    $('select', this.header()).on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column.search(val, true, false).draw();
                    });
                });
            }
        });

        $('.filter-control').on('click', function (e) {
            e.stopPropagation();
        });

        // Get categoryUpdateUrl of the current selected row
        categoriesList.on('select', function (e, dt, type, indexes) {
            if (type === 'row') {
                categoryUpdateInfoUrl = categoriesList.row(indexes).data().update_info_url;
                categoryUpdateActionUrl = categoriesList.row(indexes).data().update_action_url;
                categoryName = categoriesList.row(indexes).data().category_name;
                categoryLicenseConditionType = categoriesList.row(indexes).data().license_condition_type_converted;
                categoryDetail.id = categoriesList.row(indexes).data().id;
                categoryDetail.genreName = categoriesList.row(indexes).data().genre_name;
                categoryDetail.licenseName = categoriesList.row(indexes).data().license_name;
            }
        }).on( 'draw', function () {
            $('.show-popup-update-agreement-category-button').on('click', function () {
                categoryUpdateInfoUrl = $(this).data('update-info-url');
                categoryUpdateActionUrl = $(this).data('update-action-url');
                categoryName = $(this).data('category-name');
                categoryLicenseConditionType = $(this).data('license-condition-type');
                editCategory();
            });
        });

        $('#datalist tbody').on('dblclick', 'tr', function () {
            var data = categoriesList.row( this ).data();
            categoriesList.row( this ).select();
            showDetail();
        });

    };

    var initLicenseList = function () {
        $.fn.dataTable.ext.errMode = 'none';
        licenseList = $('#license-list').DataTable({
            tabIndex: -1,
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: LICENSE_DATA,
                dataType: "json",
                type: "GET",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            },
            dom: 'r<"d-flex justify-content-center dataTables_pagination"ip>t',
            lengthMenu: 10,
            select: {
                selector: 'td',
                info: false,
                style: 'multi',
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

                $("<li class='paginate_button page-item paginate_info disabled'><span class='page-link'>" + strPaginateInfo + "</span></li>").insertAfter("#license-list_previous.paginate_button.previous");
                $("<li class='paginate_button page-item result_range disabled'><span class='page-link'>" + strResultRange + "</span></li>").insertAfter("#license-list_previous.paginate_button.previous");
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
                {data: "id", name: 'id', sortable: false, orderable: false},
                {data: "name", name: 'name'},
                {
                    data: "certificate_required_flag_converted",
                    name: 'certificate_required_flag_converted'
                },
            ],
            columnDefs: [
                {
                    render: function (data, type, row) {
                        return "";
                    },
                    orderable: false,
                    className: 'select-custom-checkbox',
                    targets: 0
                },{
                    className: "text-center",
                    targets: 2
                },
            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    $('input', this.header()).on('keyup change', function () {
                        if (column.search() !== this.value) {
                            getCheckedLicenseInTable();
                            column.search(this.value).draw();
                        }
                    });
                });
            },
            drawCallback: function( settings ) {
                $("th.select-custom-checkbox").removeClass("selected");
                $("th.select-custom-checkbox").parent().removeClass("selected");
                var tableRows = licenseList.rows()[0];
                if (tableRows.length == 0) {
                    $("th.select-custom-checkbox").addClass("select-custom-checkbox-disabled");
                } else {
                    $("th.select-custom-checkbox").removeClass("select-custom-checkbox-disabled");
                    for (var i in tableRows) {
                        if (updateCheckedLicenses.has(licenseList.row(tableRows[i]).data().id)) {
                            licenseList.row(tableRows[i]).select();
                        }
                    }
                }
            },
        });

        // set event for click all checkbox
        licenseList.on("click", "th.select-custom-checkbox", function() {
            if ($("th.select-custom-checkbox").hasClass("selected")) {
                licenseList.rows().deselect();
                $("th.select-custom-checkbox").removeClass("selected");
                $("th.select-custom-checkbox").parent().removeClass("selected");
            } else if (! $("th.select-custom-checkbox").hasClass("select-custom-checkbox-disabled")) {
                licenseList.rows().select();
                $("th.select-custom-checkbox").addClass("selected");
                $("th.select-custom-checkbox").parent().addClass("selected");
            }
        }).on("select deselect", function() {
            // Some selection or deselection going on
            if (licenseList.rows({selected: true}).count() !== licenseList.rows().count()) {
                $("th.select-custom-checkbox").removeClass("selected");
                $("th.select-custom-checkbox").parent().removeClass("selected");
            } else {
                $("th.select-custom-checkbox").addClass("selected");
                $("th.select-custom-checkbox").parent().addClass("selected");
            }
        });

        // set event for on change page
        licenseList.on( 'page.dt', function () {
            getCheckedLicenseInTable();
        } );
    };

    var getCheckedLicenseInTable = function () {
        var checkedArray = licenseList.rows({selected: true})[0];
        for (var i in checkedArray) {
            updateCheckedLicenses.add(licenseList.row(checkedArray[i]).data().id);
        }
        var uncheckedArray = licenseList.rows({selected: false})[0];
        for (var i in uncheckedArray) {
            updateCheckedLicenses.delete(licenseList.row(uncheckedArray[i]).data().id);
        }
    };

    var exportFile = function (params, url) {
        // delete length and start properties in params
        delete params.length;
        delete params.start;

        function writeUrl(object, prefix) {
            // write url according to process-data url form to use datatable query lib to get data
            var param = "";
            for (var key in object) {

                if (typeof object[key] == 'object') {
                    var newPrefix = "";
                    if (prefix == "") {
                        newPrefix = key;
                    } else {
                        newPrefix = prefix + "[" + key + "]";
                    }

                    param += writeUrl(object[key], newPrefix);
                } else {
                    if (prefix == "") {
                        param += key + "=" + object[key] + "&";
                    } else {
                        param += prefix + "[" + key + "]" + "=" + object[key] + "&";
                    }
                }
            }
            return param;
        }

        url += "?" + writeUrl(params, ""); // add params for url
        url = url.substr(0, url.length - 1);  // remove last '&'
        url = encodeURI(url);
        $('#download-file').attr('href', url);

        document.getElementById('download-file').click();
    };

    var showDetail = function () {
        $('#detail-category-id').html(categoryDetail.id);
        $('#detail-genre-name').html(categoryDetail.genreName);
        $('#detail-category-name').html(categoryName);
        $('#detail-license').html(categoryDetail.licenseName);

        showPopup($('#detail-dialog'));
    };

    var editCategory = function () {
        updateCheckedLicenses.clear();
        originalCheckedLicenses.clear();
        $('#update-popup-category-name').html(categoryName);
        $('#license-check-condition-select').val(categoryLicenseConditionType);
        $.ajax({
            url: categoryUpdateInfoUrl,
            type: 'GET',
            success: function (data) {
                for (var i in data) {
                    updateCheckedLicenses.add(data[i].license_id.toString());
                    originalCheckedLicenses.add(data[i].license_id.toString());
                }

                licenseList.ajax.reload(function () {
                    $('#update-category-button').prop('disabled', false);
                    showPopup($('#update-dialog'));
                    progressBlock.hide();
                    progressEl.hide();
                });
            },
            beforeSend: function () {
                progressBlock.show();
                progressEl.show();
            },
            complete: function () {},
            error: function (err) {
                console.log(err.message);
            }
        });
    };

    var initUpdateFlow = function () {
        $('#update-category-button').on('click', function () {
            var confirmPopup = new popupCommon(1, {
                msg: CONFIRM_UPDATE_CONTENT,
                close: NO,
                confirm: YES
            });
            var confirmPopupHtml = confirmPopup.renderView();
            var $ppConfirm = $(confirmPopupHtml);
            showPopup($ppConfirm);
            $ppConfirm.find('.st-pp-confirm').one('click', function () {
                hidePopup($ppConfirm);
                $('#update-category-button').prop('disabled', true);

                // update checked license
                getCheckedLicenseInTable();

                var data = {};

                // set license condition type
                if ($('#license-check-condition-select').val().indexOf("AND") >= 0) {
                    data.licenseConditionType = 1;
                } else {
                    data.licenseConditionType = 2;
                }

                // set deleted ids
                if (updateCheckedLicenses.size == 0) {
                    data.deletedIds = Array.from(originalCheckedLicenses);
                } else {
                    var deletedIds = [];
                    originalCheckedLicenses.forEach(function (e) {
                        if (!updateCheckedLicenses.has(e)) {
                            deletedIds.push(e);
                        }
                    });
                    data.deletedIds = deletedIds;
                }

                // set added ids
                if (originalCheckedLicenses.size == 0) {
                    data.addedIds = Array.from(updateCheckedLicenses);
                } else {
                    var addedIds = [];
                    updateCheckedLicenses.forEach(function (e) {
                        if (!originalCheckedLicenses.has(e)) {
                            addedIds.push(e);
                        }
                    });
                    data.addedIds = addedIds;
                }

                $.ajax({
                    url: categoryUpdateActionUrl,
                    data: data,
                    type: "PUT",
                    success: function (response) {
                        hidePopup($('#update-dialog'));
                        categoriesList.ajax.reload(null, false);
                        $('#update-category-button').prop('disabled', false);

                        $('#success-message-alert').html(response.content);
                        $('#success-message-alert').switchClass('d-none', 'd-block');
                    },
                    error: function (response) {
                        $('#update-category-button').prop('disabled', false);
                    }
                })
            });
        });
    };

    var showPopup = function (popup) {
        popup.modal('show');
    };

    var hidePopup = function (popup) {
        popup.modal('hide');
    };


    return {
        init: init
    }

}();
