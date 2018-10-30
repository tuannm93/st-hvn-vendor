$(document).ready(function () {
    ContractTermsRevisionHistory.init();
    $('input.filter-control').bind("keypress", function(e) {
        if (e.keyCode == 13 && !$(document.activeElement).is('textarea')) {               
            e.preventDefault();
            return false;
        }
    });
});

var ContractTermsRevisionHistory = function() {

    var table;

    var init = function () {
        initTable();
        initShowDetailEvent();
    }

    var initTable = function () {
        $.fn.dataTable.ext.errMode = 'none';
        table = $('#datalist') .DataTable({
            tabIndex: -1,
            processing: true,
            serverSide: true,
            order: [[ 1, "desc" ]],
            ajax: {
                url: GET_DATA_URL,
                dataType: "json",
                type: "GET",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            },
            dom: '<"top"<"d-flex justify-content-center dataTables_pagination"ipl><"clear">>rt<"bottom"<"d-flex justify-content-center dataTables_pagination"ipl><"clear">>',
            select: {
                info: false,
                style: 'single',
                className: 'bg-info'
            },
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
                lengthMenu: "_MENU_",
                searchPlaceholder: KEY_WORD_SEARCH_FROM_THE_WHOLE,
                zeroRecords: ZERO_RECORDS,
                processing: PROCESSING
            },
            columns: [
                {data: "id"},
                {data: "created_date", name: 'created_date'},
                {data: "user_name", name: 'u.user_name'},
            ],
            columnDefs: [
                {
                    className: "text-center",
                    targets: 0
                }
            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    $('input', this.header()).on('keyup change', function () {
                        if (column.search() !== this.value) {
                            var searchValue = this.value;
                            column.search(searchValue).draw();
                        }
                    });
                });
            }
        });

        $('.filter-control').on('click', function (e) {
            e.stopPropagation();
        });

    }

    var initShowDetailEvent = function () {
        var progressBlock = $('.progress-block');
        var progressEl = $('.progress');

        $('#datalist tbody').on('dblclick', 'tr', function () {
            var data = table.row( this ).data();
            table.row( this ).select();
            $.ajax({
                url: data.detail_url,
                success: function (result) {
                    result = result[0];
                    $('#detail-id').html(result.id);
                    $('#detail-content').html(result.content);
                    $('#detail-created').html(result.created_date);
                    $('#detail-user-name').html(result.user_name);

                    $('#detail-dialog').modal('show');
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
        } );
    }


    return {
        init: init
    }

}();


