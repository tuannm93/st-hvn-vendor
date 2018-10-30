$(document).ready(function () {
    AgreementAdminDashboard.init();
    $('input.filter-control').bind("keypress", function(e) {
        if (e.keyCode == 13 && !$(document.activeElement).is('textarea')) {               
            e.preventDefault();
            return false;
        }
    });
});

var AgreementAdminDashboard = function () {

    var agreementCustomizeWithCorpUrl;

    var init = function () {
        initDatatable();
    };

    var initDatatable = function () {
        $.fn.dataTable.ext.errMode = 'none';
        var table = $('#datalist').DataTable({
            order: [],
            tabIndex: -1,
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
                "paginate": {
                    first: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                    next: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
                    last: '<span class="btn-custom"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>'
                },
                search: "_INPUT_",
                searchPlaceholder: KEY_WORD_SEARCH_FROM_THE_WHOLE,
                lengthMenu: "_MENU_",
                "zeroRecords": ZERO_RECORDS,
                "processing": PROCESSING
            },
            columns: [
                {data: "corp_id", name: 'corp_id', width: "5%"},
                {data: "customize_label", name: 'customize_label', width: "10%"},
                {data: "corp_kind", name: 'corp_kind', width: "10%"},
                {data: "agreement_status", name: 'agreement_status', width: "10%"},
                {data: "official_corp_name", name: 'official_corp_name', width: "10%"},
                {data: "corp_name", name: 'corp_name', width: "10%"},
                {data: "listed_kind", name: 'listed_kind', width: "10%"},
                {data: "capital_stock", name: 'capital_stock', width: "10%"},
                {data: "hansha_check_user_name", name: 'hansha_check_user_name', width: "10%"},
                {data: "transactions_law_user_name", name: 'transactions_law_user_name', width: "10%"},
                {data: "affilication_detail_url", sortable: false, searchable: false, width: "5%"}
            ],
            columnDefs: [
                {
                    className: "text-center",
                    targets: [0, 2, 6, 9]
                },
                {
                    render: function (data) {
                        if (data != null) {
                            return '<p class="text-danger">' + data + '</p>'
                        } else {
                            return "";
                        }
                    },
                    className: "text-center",
                    targets: 1
                },
                {
                    render: function (data, type, row) {
                        return '<p class="text-muted">' + data + '</p>';
                    },
                    className: "text-center",
                    targets: 3
                },
                {
                    render: function (data) {
                        if (data != null && data != '') {
                            return Currency.formatNumberToCurrency(data);
                        }
                        return '';
                    },
                    className: "text-right text-nowrap",
                    targets: 7
                },
                {
                    render: function (data, type, row) {
                        var content = "";
                        if (row.corp_agreement_id == null || row.hansha_check == null || row.hansha_check == NONE) {
                            content = NONE_STATUS;
                        } else if (row.hansha_check == OK) {
                            content = OK_STATUS;
                        } else if (row.hansha_check == NG) {
                            content = NG_STATUS;
                        } else if (row.hansha_check == INADEQUATE) {
                            content = INADEQUATE_STATUS;
                        }
                        if (row.hansha_check_user_name != null) {
                            content = content + '<br/>' + row.hansha_check_user_name;
                        }
                        if (row.hansha_check_date != null) {
                            content = content + '<br/>' + row.hansha_check_date;
                        }
                        return content;
                    },
                    className: "text-center",
                    targets: 8
                },
                {
                    render: function (data, type, row) {
                        var content = data;
                        if (row.transactions_law_date != null) {
                            content = data + '<br/>' + row.transactions_law_date;
                        }
                        return content;
                    },
                    className: "text-center",
                    targets: 9
                },
                {
                    render: function (data, type, row) {
                        var content = '<a href="' + data + '" title="詳細">詳細</a>';
                        return content;
                    },
                    className: "text-center",
                    targets: 10
                },
            ],
            buttons: [
                {
                    text: '<span class="fa fa-plus"></span>   ' + CREATE_RIDER,
                    extend: 'selectedSingle',
                    action: function () {
                        window.location.href = agreementCustomizeWithCorpUrl;
                    }
                },
                {
                    text: '<span class="fa fa-file-excel-o"></span>   ' + EXPORT_EXCEL,
                    filter: "applied", page: 'all',
                    action: function () {
                        var params = this.ajax.params();
                        exportFile(params, EXPORT_EXCEL_URL);
                    }
                },
                {
                    text: '<span class="fa fa-file-text-o"></span>   ' + EXPORT_CSV,
                    filter: "applied", page: 'all',
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
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
                });
            }
        });

        // Get ID of the current selected row
        table.on('select', function (e, dt, type, indexes) {
            if (type === 'row') {
                agreementCustomizeWithCorpUrl = table.row(indexes).data().agreement_customize_with_corp_url;
            }
        });

        $('.filter-control').on('click', function (e) {
            e.stopPropagation();
        });
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
                    var newPrefix = ""
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

    return {
        init: init,
    }

}();