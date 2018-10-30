var count_click_getCalendar = 0;
var count_click_collapse = 0;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    FormUtil.validate('#form-auction-refusal');
    AuctionRefusal.init();
    if(window.history.state && window.history.state.dataBack) {
        $('#results-search').html(window.history.state.dataBack);
    }
    $("#auction-search-1").multiselect({
        multiple: true,
        selectedList: 5,
        checkAllText: check_all,
        uncheckAllText: un_check_all,
        noneSelectedText: un_select,
    });
    $("#auction-search-2").multiselect({
        multiple: true,
        selectedList: 5,
        checkAllText: check_all,
        uncheckAllText: un_check_all,
        noneSelectedText: un_select,
    }).multiselectfilter({
        label: ''
    });
    var btns = document.getElementsByClassName("switch-btn");
    var width_ui_state_default = $('.width-ui-state-default').width();

    $(".switch-btn").click(function() {
        $(".switch-btn").removeClass("activated");
        $(this).addClass("activated");
        $('.switch-btn').each(function(index, el) {
            $(this).find('input').removeAttr('checked');
        });
        $(this).find('input').attr({checked: 'checked'});

    });

    $(document).on('click', '.sort-item', function (e) {
        e.preventDefault();
        var detailSort = $(this).data('sort').split('-');
        orderBy = detailSort[0];
        sortType = detailSort[1];
        searchAuction(detailSort);
    });
    $('#search-btn-first, #search-btn-second').click(function(event) {
        searchAuction();
    });
    $(document).on('click', '.sort-item-for-kameiten', function (e) {
        e.preventDefault();
        var detailSort = $(this).data('sort').split('-');
        orderBy = detailSort[0];
        sortType = detailSort[1];
        sortAuctionForKameiten(detailSort);
    });

    $('.ui-multiselect-menu, .ui-state-default').css('width', width_ui_state_default);

    $('#txt-lbl').on('click', function() {
        if ($('#affFirstClick').val() == '0') {
            initCalendar();
            $('#affFirstClick').val(1);
        }
        setTimeout(function() {
            if ($('#txt-lbl').attr("aria-expanded") == "false") {
                $('#txt-lbl').html("入札済案件を表示\&\#x226b;");
            } else {
                $('#txt-lbl').html("入札済案件を非表示\&\#x226b;");
            }
        }, 100);
    });

    $(document).on('click', '.confirm-deal-details-btn', function (e) {
        var url = $(this).data('url');
        $.getJSON(url, function(data) {
            $('#auctionDetailModal .auction-support-content').html(data.contents);
            $("#auctionDetailModal").modal('show');
        });
    });

    $(".list").find(".btnAnkenDetail").each(function() {
        var demandInfoId = $(this).prop("id").split("-")[1];
        var targetDetailTextElement = ".ankenDetailText-" + demandInfoId;
        if (!$(targetDetailTextElement).text()) {
            var ajaxURL = urlProposalJson + "/" + demandInfoId;
            $.getJSON(ajaxURL, function(data) {
                var targetDetailTextElement = ".ankenDetailText-" + data.id;
                $(targetDetailTextElement).text(data.contents);
            });
        }
        $(targetDetailTextElement).show();
        $(".ankenDetail-" + demandInfoId).show();
    });

    $(document).on('click', '.supportButton', function (e) {
        var url = $(this).data('url');
        e.preventDefault();
        $.ajax({
            type: 'get',
            url: url,
        }).done(function(data) {
            $('#supportModal').find('.auction-support-content').html(data.view);
            $("#supportModal").modal('show');
            if (!$('#confirmation').length && !$('#lostOrder').length) {
                $("#kihon_info").show();
            }
            FormUtil.validate('#supportForm');
        });
    });
    $('.getCalendar').on('click', function(){
        getOneCalendar($(this).attr('data-next'));
    });
    $('.collapse-label-mobi').click(function() {
        count_click_collapse++;
        if (count_click_collapse > 1) {
            return false;
        };
        if ($(this).children().hasClass('fa-chevron-down')) {
            $(this).find('.fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        } else if($(this).children().hasClass('fa-chevron-up')) {
            $(this).find('.fa-chevron-up').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
        setTimeout(function() {
            count_click_collapse = 0;
        }, 500);
    })
});

function getOneCalendar(next) {
    count_click_getCalendar++;
    if (count_click_getCalendar != 1) {
        return false;
    }
    styleCalenderMobi();
    if (next == "1") {
        let currentVisible = $("#table-calendar").find('div:visible').last();
        let elNext = currentVisible.next();
        if (elNext.length > 0 && elNext.is('div')) {
            $("#table-calendar").find('div:visible').first().css('display', 'none');
            elNext.css('display', 'block');
            count_click_getCalendar = 0;;
        } else {
            let month = parseInt(currentVisible.attr('data-month'));
            let year = parseInt(currentVisible.attr('data-year'));
            if (month == 12) {
                month = 1;
                year = year + 1;
            } else {
                month = month + 1;
            }
            let calendar = getCalendar(month, year);
            calendar.then(data => {
                $("#table-calendar").find('div:visible').first().css('display', 'none');
                currentVisible.after(data);
                styleCalenderMobi();
                var $objTr = $("#table-calendar").find('div:visible').last();
                checkHasEvent($objTr);
                count_click_getCalendar = 0;
            })
        }
    } else if (next == "0") {
        let currentVisible = $("#table-calendar").find('div:visible').first();
        let elPrev = currentVisible.prev();
        if (elPrev.length > 0 && elPrev.is('div')) {
            styleCalenderMobi();
            $("#table-calendar").find('div:visible').last().css('display', 'none');
            elPrev.css('display', 'block');
            count_click_getCalendar = 0;
        } else {
            let month = parseInt(currentVisible.attr('data-month'));
            let year = parseInt(currentVisible.attr('data-year'));
            if (month == 1) {
                month = 12;
                year = year - 1;
            } else {
                month = month - 1;
            }
            let calendar = getCalendar(month, year);
            calendar.then(data => {
                $("#table-calendar").find('div:visible').last().css('display', 'none');
                currentVisible.before(data);
                styleCalenderMobi();
                var $objTr = $("#table-calendar").find('div:visible').first();
                checkHasEvent($objTr);
                count_click_getCalendar = 0;
            })
        }
    }

}

function initCalendar() {
    var tablet_width = 768;
    let month2;
    let year2;
    let month3;
    let year3;

    if (currentMonth == 1) {
        month2 = 12;
        year2 = currentYear - 1;

        month3 = currentMonth + 1;
        year3 = currentYear;
    } else if (currentMonth == 12) {
        month2 = currentMonth - 1;
        year2 = currentYear;

        month3 = 1;
        year3 = currentYear + 1;
    } else {
        month2 = currentMonth - 1;
        year2 = currentYear;

        month3 = currentMonth + 1;
        year3 = currentYear;
    }
    let calendar1 = getCalendar(month2, year2);
    let calendar2 = getCalendar(currentMonth, currentYear);
    let calendar3 = getCalendar(month3, year3);
    Promise.all([calendar1, calendar2, calendar3]).then(calendarData=>{
        $('#table-calendar').addClass('d-flex').append(calendarData[0]); // caledndar1
        var $objTr = $("#table-calendar").find('div:visible').last();
        checkHasEvent($objTr);
        $('#table-calendar').append(calendarData[1]); // caledndar2
        $objTr = $("#table-calendar").find('div:visible').last();
        checkHasEvent($objTr);
        $('#table-calendar').append(calendarData[2]);  // caledndar3
        $objTr = $("#table-calendar").find('div:visible').last();
        checkHasEvent($objTr);
        if ($(window).width() < tablet_width) {
            $("#table-calendar").find('div').addClass('mx-4').css('display', 'none');
            $('#table-calendar div:nth-child(4)').css('display', 'inline');
        }
    });
}

function getCalendar(month, year) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: urlGetCalenderView,
            data: { 'year': year, 'month': month },
            type: 'GET'
        }).done(function(data) {
            resolve(data);
        });
    });
}

function seikatu_info_dialog_close() {
    $('#seikatu_info_dialog').modal('hide');
};

/*---------カレンダー関連 ---------*/
function setCalenderView(targetDate) {
    // 引数に指定された日付の1月前を取得
    targetDate.setMonth(targetDate.getMonth() - 1);

    for (var i = 0; i < cals_id.length; i++) {
        createCalender(cals_id[i], targetDate);
        targetDate.setMonth(targetDate.getMonth() + 1);
    }

}

function createCalender(id, date) {
    var year = date.getFullYear();
    var month = ("0" + (date.getMonth() + 1)).slice(-2); // 2桁表示にする
    var jsonData = { "year": year, "month": month, "data": calendarEventData };

    $.ajax({
        type: 'POST',
        url: urlGetCalenderView,
        dataType: 'html',
        data: JSON.stringify(jsonData),
        // data: ,
        success: function(data) {
            var cal = document.getElementById(id);
            if (cal.hasChildNodes()) {
                cal.removeChild(cal.firstChild);
            }
            var div = document.createElement("div");
            div.innerHTML = data;
            cal.appendChild(div);
            addCalenderClickEvent();
        },
        error: function() {
            console.log('問題がありました。');
        }
    });
}

function createListDetail(id, indexes) {
    var div = $(id);
    for (var i = 0; i < indexes.length; i++)
        div.append(createDetailLine(indexes[i]));
}

function createDetailLine(elem) {
    //tag of main
    var div = document.createElement('div');
    div.style.marginTop = "5px";
    div.style.borderStyle = "solid";
    div.style.borderColor = "#FFFFFF";
    //tag of line-label
    var lb = document.createElement('label');
    lb.style.fontSize = "1.0em";
    div.appendChild(lb);
    //tag of radio
    var radio = document.createElement('input');
    radio.setAttribute('type', 'radio');
    radio.setAttribute('name', 'select_demand');
    radio.value = getLinkUrl(elem.commission_id);
    lb.appendChild(radio);
    //span of demand_id
    var span_demand = document.createElement('span');
    span_demand.style.marginLeft = "10px";
    span_demand.appendChild(document.createTextNode('案件番号：' + elem.demand_id));
    lb.appendChild(span_demand);
    //span of date of hope
    var span_date = document.createElement('span');
    span_date.style.marginLeft = "10px";
    span_date.appendChild(document.createTextNode(elem.dialog_display_date));
    lb.appendChild(span_date);
    //span of name customer
    var span_name = document.createElement('span');
    span_name.style.marginLeft = "10px";
    span_name.appendChild(document.createTextNode('お客様名：' + elem.customer_name));
    lb.appendChild(span_name);
    //span of name site
    var span_site = document.createElement('span');
    span_site.style.marginLeft = "10px";
    span_site.appendChild(document.createTextNode('サイト名：' + elem.site_name));
    lb.appendChild(span_site);

    return div;
}

function calsPrevMonth() {
    currentDate.setMonth(currentDate.getMonth() - 3);
    setCalenderView(currentDate);
}

function calsNextMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    setCalenderView(currentDate);
}

function goCommissionDetail(link) {
    var link = null;
    var radios = document.getElementsByName('select_demand');
    for (var i = 0; i < radios.length; i++)
        if (radios[i].checked) { link = radios[i].value; break; };
    if (!link) return;
    window.open().location.href = link;
}

function getLinkUrl(commissionId) {
    if (!commissionId) return "";
    return window.location.origin + "/commission/detail/" + commissionId;
}

function addCalenderClickEvent() {
    // tableのtd要素を全て取得
    $("table.cal_table td").each(function(i) {
        // tdのクラス名に「hasEvents」が付いている場合は対象の日にちか判定を行う
        if ($(this).attr("class").indexOf("hasEvents", 0) != -1) {
            // tdのdata属性にセットされている日付を取得
            var targetDate = $(this).data("date");
            $(this).unbind(); // 一旦クリックイベントを削除
            $(this).click(function() {

                // クリック可能な日付を検索
                for (var i in calendarEventData) {
                    for (var j in calendarEventData[i]) {
                        if (targetDate == calendarEventData[i][j].display_date) {
                            // 登録されているデータが1件しか無い
                            if (calendarEventData[i].length == 1) {
                                window.open().location.href = getLinkUrl(calendarEventData[i][j].commission_id);
                                return;
                            } else {
                                document.getElementById('list_demands').innerHTML = "";
                                createListDetail('list_demands', calendarEventData[i]);
                                $("#list_event_dialog").modal();
                            }
                        }
                    }
                }
            });
        }
    });
}

function checkHasEvent(obj) {
    var $ym = obj.attr('data-year') + '-' + obj.attr('data-month') + '-';
    obj.find('td').each(function(k, td) {
        var $day = $(td).text();
        var $ymd;
        if ($day < 10) {
            $ymd = $ym + '0' + $day;
        } else {

            $ymd = $ym + $day;
        }
        if (!$(td).hasClass('out-date') && calendarEventData[$ymd]) {
            $(td).addClass('has-events');
            $(td).attr('data-ymd', $ymd);
            $(td).unbind();
            $(td).click(function() {
                let dataYmd = $(this).attr('data-ymd');
                if (calendarEventData[dataYmd].length == 1) {
                    let $commissionId = calendarEventData[dataYmd][0]['commission_id'];
                    window.open().location.href = getLinkUrl($commissionId);
                } else {
                    $('#list_demands').html('');
                    createListDetail('#list_demands', calendarEventData[dataYmd]);
                    $("#list_event_dialog").modal('show');
                }
            });
        }
    });
}

function styleCalenderMobi() {
    $("#table-calendar").find('div').removeClass('mx-4').addClass('mx-4');
}

function searchAuction(dataSort)
{
    var url = $('#search-form').attr('action');
    var data = $('#search-form').serializeArray();
    if (typeof dataSort != 'undefined') {
        data.push({name: 'sort', value: dataSort[0]});
        data.push({name: 'order', value: dataSort[1]});
    }
    var resultSearch = $('#results-search');
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
            resultSearch.html(data);
            if (smartDevice.checkMobile()) {
                window.history.replaceState({"dataBack": data}, "", "");
            }
            $(".list").find(".btnAnkenDetail").each(function() {
                var demandInfoId = $(this).prop("id").split("-")[1];
                var targetDetailTextElement = ".ankenDetailText-" + demandInfoId;
                if (!$(targetDetailTextElement).text()) {
                    var ajaxURL = urlProposalJson + "/" + demandInfoId;
                    $.getJSON(ajaxURL, function(data) {
                        var targetDetailTextElement = ".ankenDetailText-" + data.id;
                        $(targetDetailTextElement).text(data.contents);
                    });
                }
                $(targetDetailTextElement).show();
                $(".ankenDetail-" + demandInfoId).show();
            });
        },
        error: function (err) {
        }
    });
}

function sortAuctionForKameiten(dataSort)
{
    var data = [];
    data.push({name: 'sort', value: dataSort[0]});
    data.push({name: 'order', value: dataSort[1]});
    var resultSearch = $('#result-kameiten');
    var progressBlock = $('.progress-block');
    var progressEl = $('.progress');
    $.ajax({
        type: 'post',
        url: urlSortForKameiten,
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
            resultSearch.html(data);
            $('#txt-lbl').click();
        },
        error: function (err) {
        }
    });
}
