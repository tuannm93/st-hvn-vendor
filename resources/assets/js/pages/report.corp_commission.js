'use strict';

var pageData = $('#page-data');
var url_report_search = pageData.data('url-search');
var progress = new progressCommon();

var controlEl = {
    isInitSearch: true,
    searchEl: '#searchItems',
    sort: [],
    formId: '#searchForm',
    resultArea: '.searchResult',
    nextPage: '.next',
    prevPage: '.previous',
    scrollBar: 'show-report-commission'
};

var controlEl2 = {
    searchEl: '#orderSearch',
    sort: [],
    formId: '#searchForm',
    resultArea: '.searchResult',
    nextPage: '.next',
    prevPage: '.previous'
};

var ReportCorpCommission = function () {
    var resetOrder = $('#resetOrder'),
        defaultOrder = JSON.parse($("#defaultOrder").val()),
        clearFilter = $('#clearFilter'),
        order = $('.order'),
        direction = $('.direction'),
        followDate = $('#follow_date'),
        detectContactDesiredTime = $('#detect_contact_desired_time'),
        commissionRank = $('#commission_rank'),
        genreSelectAnker = $('#genreSelectAnker'),
        siteName = $('#site_name'),
        corpName = $('#corp_name'),
        holiday = $('#holiday'),
        dayOfTheWeekSelectAnker = $('#dayOfTheWeekSelectAnker'),
        firstCommission = $('#first_commission'),
        username = $('#user_name'),
        modified = $('#modified'),
        auction = $('#auction'),
        crossSellImplement = $('#cross_sell_implement'),
        searchForm = $("#searchForm"),
        searchResult = $('.searchResult'),
        submitOrder1 = $('#submit_order1'),
        submitDirection1 = $('#submit_direction1'),
        submitOrder = $('.submit_order'),
        submitDirection = $('.submit_direction'),
        urlCountBrowse = pageData.data('url-count-browser'),
        curPage = 0;

    //Multiselect for a
    commissionRank.multiselect({
        checkAllText: check_all,
        uncheckAllText: un_check_all,
        selectedList: 5,
        classes: 'fix-w-300',
        noneSelectedText: un_select,
        create: function create() {
            commissionRank.next().addClass('d-none btn-commission-rank');
        },
        beforeopen: function beforeopen(event, ui) {
            commissionRank.next().toggleClass('d-block d-none');
        },
        close: function close(event, ui) {
            var generalSelected = '';

            if (commissionRank.multiselect("getChecked").length == 0) {
                genreSelectAnker.html(un_select);
            } else {
                commissionRank.multiselect('getChecked').each(function (index, value) {
                    if (parseInt(index) == 0) {
                        generalSelected = $(value).val();
                    } else {
                        generalSelected = generalSelected + ',' + $(value).val();
                    }

                    genreSelectAnker.html(generalSelected);
                });
            }
            commissionRank.next().toggleClass('d-block d-none');
        }
    });

    genreSelectAnker.on('click', function () {
        commissionRank.multiselect('open');
    });

    holiday.multiselect({
        checkAllText: check_all,
        uncheckAllText: un_check_all,
        selectedList: 5,
        classes: 'fix-w-300',
        noneSelectedText: un_select,
        create: function create() {
            holiday.next().addClass('d-none btn-commission-rank');
        },
        beforeopen: function beforeopen(event, ui) {
            holiday.next().toggleClass('d-none d-block');
        },
        close: function close() {
            var generalHoliday = '';
            if (holiday.multiselect('getChecked').length == 0) {
                dayOfTheWeekSelectAnker.html(un_select);
            } else {
                holiday.multiselect('getChecked').each(function (index, value) {
                    if (parseInt(index) == 0) {
                        generalHoliday = $(value).attr('title').slice(0, 1);
                    } else {
                        generalHoliday = generalHoliday + ', ' + $(value).attr('title').slice(0, 1);
                    }
                    dayOfTheWeekSelectAnker.html(generalHoliday);
                });
            }
            holiday.next().toggleClass('d-block d-none');
        }
    });
    dayOfTheWeekSelectAnker.on('click', function () {
        holiday.multiselect('open');
    });

    /**
     * Init multi select
     * @param dataShow
     * @param multiTable
     */
    function initMultiselect(dataShow, multiTable) {
        var textShow = '';
        if (multiTable.multiselect('getChecked').length !== 0) {
            multiTable.multiselect('getChecked').each(function (index, value) {
                if (parseInt(index) === 0) {
                    textShow = $(value).attr('title').slice(0, 1);
                } else {
                    textShow = textShow + ', ' + $(value).attr('title').slice(0, 1);
                }
                dataShow.html(textShow);
            });
        }
    }

    /**
     * List function for action click reset button
     */
    function buttonClick() {
        // Reset to initial order value
        resetOrder.on('click', function (event) {
            var $form = $('#selectOrderForm');
            $.each($form.find("select"), function (key, value) {
                var index = key + 1;
                var selectedValue = defaultOrder.order[index];
                var checkedValue = defaultOrder.direction[index];
                $(value).val(selectedValue);
                $('#submit_order' + index).val(selectedValue);
                // radio
                var $radio = $form.find("input[name='direction" + index + "']");
                $.each($radio, function (key, value) {
                    $(value).prop('checked', false);
                    if ($(value).val() == checkedValue) {
                        $(value).prop('checked', true);
                        $('#submit_direction' + index).val($(value).val());
                    }
                });
            });
        });

        // Clear filter data
        clearFilter.on('click', function () {
            commissionRank.multiselect('uncheckAll');
            holiday.multiselect('uncheckAll');
            followDate.each(function () {
                this.selectedIndex = 0;
            });
            detectContactDesiredTime.each(function () {
                this.selectedIndex = 0;
            });
            commissionRank.find('option:selected').removeAttr("selected");
            genreSelectAnker.text('--なし--');
            siteName.val('');
            corpName.val('');
            holiday.find('option:selected').removeAttr("selected");
            dayOfTheWeekSelectAnker.text('--なし--');
            firstCommission.each(function () {
                this.selectedIndex = 0;
            });
            username.val('');
            modified.each(function () {
                this.selectedIndex = 0;
            });
            auction.each(function () {
                this.selectedIndex = 0;
            });
            crossSellImplement.each(function () {
                this.selectedIndex = 0;
            });
        });

        // Setup order value to search
        order.on('change', function () {
            $('#submit_' + this.name).val($('#' + this.name).val());
        });

        // Setup order direction to search
        direction.click(function () {
            $('#submit_' + this.name).val(this.value);
        });
    }

    function eventSortInTable() {
        $('body').on('click', '.order-sort', function (e) {
            var typeSort = $('#direction_label_' + $(this).data('val'));
            var thisOrder = $(this).data('val');
            if (typeSort.text() === '') {
                submitDirection.val('');
                submitDirection1.val('desc');
            } else if (typeSort.text() === '▲') {
                submitDirection.val('');
                submitDirection1.val('desc');
            } else {
                submitDirection.val('');
                submitDirection1.val('asc');
            }
            submitOrder.val('');
            submitOrder1.val(thisOrder);
            var data = searchForm.serialize();
            $.ajax({
                type: "GET",
                dataType: 'json',
                data: data,
                url: url_report_search,
                xhr: function xhr() {
                    return progress.createXHR();
                },
                beforeSend: function beforeSend(xhr) {
                    progress.controlProgress(true);
                },
                complete: function complete() {
                    progress.controlProgress(false);
                },
                success: function success(data) {
                    if (data.length) {
                        searchResult.html(data);
                        if (submitDirection1.val() === 'asc') {
                            $('#direction_label_' + thisOrder).text('▲');
                        } else {
                            $('#direction_label_' + thisOrder).text('▼');
                        }
                        hashPage(curPage, controlEl);
                        $('.pseudo-scroll-bar').scrollLeft(0);
                    }
                },
                error: function error() {
                    console.log("Error!");
                }
            });
        });
    }

    var hashPage = function hashPage(page, controlEl) {
        if (page > 1) {
            location.hash = page;
        }
        if (page == 0)++page;

        setPage(page, controlEl);
    };

    var setPage = function setPage(page, controlEl) {
        if (controlEl.hasOwnProperty('nextPage')) {
            $(controlEl.nextPage).attr('data-cur-page', page);
        }
        if (controlEl.hasOwnProperty('prevPage')) {
            $(controlEl.prevPage).attr('data-cur-page', page);
        }

        if (controlEl.hasOwnProperty('sorts')) {
            // set attribute data-cur-page for all sort item
            $.each(controlEl.sorts, function (index, el) {
                $(el).attr('data-cur-page', page);
            });
        }
    };

    function countBrowse() {
        var dataList = $('#list_data_id').val();
        $.ajax({
            url: urlCountBrowse,
            type: "POST",
            dataType: 'JSON',
            data: { id: dataList }
        }).done(function (data) {
            $(data).each(function (k, v) {
                $('#td_' + v['demand_id']).text(v['count']);
                if (v['count'] > 0) {
                    $('#td_' + v['demand_id']).css('font-weight', 'bold');
                    $('#td_' + v['demand_id']).css('font-size', '16px');
                    $('#td_' + v['demand_id']).css('color', 'red');
                } else {
                    $('#td_' + v['demand_id']).css('font-weight', '');
                    $('#td_' + v['demand_id']).css('font-size', '');
                    $('#td_' + v['demand_id']).css('color', '');
                }
            });
        });
    }

    function countBrowseTimes() {
        var time = 0;
        var timer;

        timer = setInterval(function () {
            countBrowse();
            if (++time > 900) {
                clearTimeout(timer);
            }
        }, 5000);
    }

    /**
     * Set function
     */
    function init() {
        buttonClick();
        eventSortInTable();
        countBrowse();
        countBrowseTimes();
        initMultiselect(dayOfTheWeekSelectAnker, holiday);
        initMultiselect(genreSelectAnker, commissionRank);
    }

    return {
        init: init
    };
}();

$(document).ready(function () {
    ReportCorpCommission.init();
    ajaxCommon.search(url_report_search, controlEl);
    ajaxCommon.search(url_report_search, controlEl2);
});
