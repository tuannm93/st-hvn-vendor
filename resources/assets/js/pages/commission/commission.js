var genre_id =  $('#genre_id')
var Commission = function () {
    function multiSelectInit() {
        genre_id.multiselect({
            multiple: true,
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            noneSelectedText: un_select,
            selectedList: 5,
            classes: "commission-mobile"
        }).multiselectfilter({
            label: ''
        });
        $('#site_id').multiselect({
            multiple: true,
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            noneSelectedText: un_select,
            selectedList: 5
        }).multiselectfilter({
            label: ''
        });
    }

    function datetimePickerInit() {
        Datetime.initForDatepicker();
    }

    function setCalenderView(targetDate, calendarEventData) {
        targetDate.setMonth(targetDate.getMonth() - 1);
        for (var i = 0; i < CALS_ID.length; i++) {
            createCalender(CALS_ID[i], targetDate, calendarEventData);
            targetDate.setMonth(targetDate.getMonth() + 1);
        }
    }

    function createCalender(id, date, calendarEventData) {
        var year = date.getFullYear();
        var month = ("0" + (date.getMonth() + 1)).slice(-2);

        $.ajax({
            type: 'GET',
            url: AJAX_CALENDAR_URL,
            dataType: 'html',
            data: { "year": year, "month": month },
            beforeSend: function() {
                isHidden(true)
            },
            success: function success(data) {
                var cal = document.getElementById(id);
                if (cal.hasChildNodes()) {
                    cal.removeChild(cal.firstChild);
                }
                var div = document.createElement("div");
                div.innerHTML = data;
                cal.appendChild(div);
                addCalenderClassEvent(calendarEventData);
                addCalenderClickEvent(calendarEventData);
            },
            error: function () {
                console.log('error');
                isHidden(false);
            },
            complete: function() {
                isHidden(false)
            }
        });
    }

    function isHidden(hiddenValue) {
        var buttonPrev = $("#cal-go-prev");
        var buttonNext = $("#cal-go-next");
        if (hiddenValue === true) {
            buttonPrev.hide();
            buttonNext.hide();
        } else {
            buttonPrev.show();
            buttonNext.show();
        }
    }

    function createListDetail(id, indexes) {
        var div = document.getElementById(id);
        for (var i = 0; i < indexes.length; i++) {
            div.appendChild(createDetailLine(indexes[i]));
        }
    }

    function createDetailLine(elem) {
        var div = document.createElement('div');
        var lb = document.createElement('label');
        lb.classList.add('list_demands');
        div.appendChild(lb);

        //tag of radio
        var radio = document.createElement('input');
        radio.setAttribute('type', 'radio');
        radio.setAttribute('name', 'select_demand');
        radio.value = getLinkUrl(elem.commission_id);
        lb.appendChild(radio);
        //span of demand_id
        var span_demand = document.createElement('span');
        span_demand.appendChild(document.createTextNode(LBL_DEMAND_ID + '：' + elem.demand_id+ ' '));
        lb.appendChild(span_demand);
        //span of date of hope
        var span_date = document.createElement('span');
        span_date.appendChild(document.createTextNode(elem.dialog_display_date));
        lb.appendChild(span_date);
        //span of name customer
        var span_name = document.createElement('span');
        span_name.appendChild(document.createTextNode(LBL_CUSTOMER_NAME + '：' + elem.customer_name+ ' '));
        lb.appendChild(span_name);
        //span of name site
        var span_site = document.createElement('span');
        span_site.appendChild(document.createTextNode(LBL_SITE_ID + '：' + elem.site_name));
        lb.appendChild(span_site);

        return div;
    }

    function calendarDirectInit(eventData) {
        $('#cal-go-prev').click(function (e) {
            e.preventDefault();
            if (IS_MOBILE) {
                currentDate.setMonth(currentDate.getMonth() - 1);
            } else {
                currentDate.setMonth(currentDate.getMonth() - 3);
            }
            setCalenderView(currentDate, JSON.parse(eventData));
        });
        $('#cal-go-next').click(function (e) {
            e.preventDefault();
            if (IS_MOBILE) {
                currentDate.setMonth(currentDate.getMonth() + 1);
            } else {
                currentDate.setMonth(currentDate.getMonth() - 1);
            }
            setCalenderView(currentDate, JSON.parse(eventData));
        });
    }

    function goCommissionDetail() {
        var link = null;
        var radios = document.getElementsByName('select_demand');
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                link = radios[i].value;
                break;
            }
        }
        if (!link) return;
        window.open().location.href = link;
    }

    function getLinkUrl(commissionId) {
        if (!commissionId) return "";
        return COMMISSION_DETAIL_URL.replace('commission_id', commissionId);
    }

    function addCalenderClassEvent(calendarEventData) {
        $("table.mx-auto td").each(function (m) {
            var targetDate = $(this).data("date");
            for (var i in calendarEventData) {
                if (i == targetDate) {
                    if (!$(this).hasClass('out-date')) {
                        $(this).addClass('has-event');
                    }
                }
            }
        });
    }

    function addCalenderClickEvent(calendarEventData) {
        $("table.mx-auto td").unbind('click').bind('click', function (e) {
            e.preventDefault();
            var targetDate = $(this).data("date");
            for (var i in calendarEventData) {
                if (i == targetDate) {
                    if (calendarEventData[i].length == 1) {
                        window.open(getLinkUrl(calendarEventData[i][0].commission_id));
                        return;
                    } else {
                        document.getElementById('list_demands').innerHTML = "";
                        createListDetail('list_demands', calendarEventData[i]);
                        $("#list_event_dialog").modal();
                    }
                }
            }
            return false;
        });
    }

    function modalEventInit() {
        $('#btnDirectCommission').click(function (e) {
            e.preventDefault();
            $('#list_event_dialog').modal('hide');
            goCommissionDetail();
        });
    }

    function calendarInit(eventData) {
        if (document.getElementById("search-cal-box")) {
            if (IS_MOBILE) {
                currentDate.setMonth(currentDate.getMonth() + 1);
            }
            setCalenderView(currentDate, JSON.parse(eventData));
        }
    }

    function search() {
        $('.btnSearch').click(function(event) {
            var url = jQuery('#commissionTableSearch').attr('data-url');
            var data = $('#searchForm').serializeArray();
            var resultSearch = $('#commissionTableSearch');
            var progressBlock = $('.progress-block');
            var progressEl = $('.progress');
            $.ajax({
                type: 'post',
                url: url,
                data: data,
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
                success: function (data) {
                    currentDate = new Date();
                    resultSearch.html(data);
                    window.history.replaceState({"dataBack": data}, "", "");
                    $('#btnExport').removeClass('d-none');
                    var eventDataNew = JSON.stringify($('#data-event-calendar').data('event-calendar'));
                    calendarInit(eventDataNew);
                    modalEventInit();
                },
                error: function (err) {
                }
            });
        });
    }

    function triggerSearch() {
        if (smartDevice.checkMobile()) {
            if (window.history.state && window.history.state.dataBack) {
                $('#commissionTableSearch').html(window.history.state.dataBack);
            }
        }
    }

    function init() {
        multiSelectInit();
        datetimePickerInit();
        calendarInit(eventDate);
        modalEventInit();
        calendarDirectInit(eventDate);
        search();
        triggerSearch();
    }
    return {
        init: init
    }
}();
jQuery(document).ready(function () {
    Commission.init();
});
