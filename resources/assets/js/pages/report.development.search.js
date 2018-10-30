$(document).ready(function() {
    ReportDevelopmentSearch.init();
});

var ReportDevelopmentSearch = function() {
    function init() {
        initTable();
    }

    function initTable() {
        var table = $('#datalist').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: GET_DATA_URL,
                data: DATA_URL,
                dataType: "json",
                type: "GET"
            },
            searching: false,
            "lengthMenu": [200],
            pagingType: "full",
            "dom": 'iftp',
            "order": [[ 0, 'asc' ]],
            "infoCallback": function(settings, start, end, max, total, pre) {
                if (DATA_URL.hasOwnProperty('status')) {
                    if (DATA_URL.status == '1') {
                        return "<span>" + TABLE_INFO_TOTAL + total + TABLE_INFO_RECORD +
                            "</span><span class='pl-4'>" + TABLE_INFO_NO_ATTACK +
                            "</span><span class='pl-4'></span>";
                    } else {
                        return "<span>" + TABLE_INFO_TOTAL + total + TABLE_INFO_RECORD +
                            "</span><span class='pl-4'></span><span class='pl-4'>" + TABLE_INFO_ADVANCE + "</span>";
                    }
                }
                return "<span>" + TABLE_INFO_TOTAL + total + TABLE_INFO_RECORD +
                    "</span><span class='pl-4'>" + TABLE_INFO_NO_ATTACK +
                    "</span><span class='pl-4'>" + TABLE_INFO_ADVANCE + "</span>";
            },
            language: {
                "paginate": {
                    first: '',
                    previous: PREV,
                    next: NEXT,
                    last: ''
                },
                "zeroRecords": ZERO_RECORDS,
                "processing": PROCESSING
            },
            "columns": [
                { "data": "id", name: 'id', searchable: false, "sortable": true, "visible": false},
                { "data": "prefecture", name: 'prefecture', searchable: false, "sortable": false },
                { "data": "user_name", name: 'user_name', searchable: false, "sortable": true },
                { "data": "official_corp_name", name: 'official_corp_name', searchable: false, "sortable": true },
                { "data": "item_name", name: 'item_name', searchable: false, "sortable": true },
            ],
            "columnDefs": [
                {"targets": 0, "visible": false}, {
                    "render": function(data, type, row) {
                        if (row.official_corp_name !== null && row.official_corp_name !== '') {
                            return '<a href="' + row.official_corp_link + '" class="highlight-link">' + row.official_corp_name + '</a>';
                        } else {
                            return '';
                        }
                    },
                    "targets": 3
                }, ],
            initComplete: function() {},
            "fnDrawCallback":function(settings){
                if(Math.ceil(settings._iRecordsTotal / settings._iDisplayLength) > 1) {
                    $('.dataTables_paginate').show();
                } else {
                    $('.dataTables_paginate').hide();
                }
            }
        });
        modifyDataTable();
    }

    function modifyDataTable() {
        $('#datalist_wrapper').addClass('table-responsive');
        $('#datalist_paginate').addClass('custom-pagination');
        $('#datalist').removeClass('dataTable').addClass('custom-border');
    }

    return {
        init: init
    }

}();