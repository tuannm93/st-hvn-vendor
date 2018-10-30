var Datetime = function () {
    function initForTimepicker() {
        $('.timepicker').timepicker({
            controlType: detectDevice() ? 'slider' : 'select',
            oneLine: detectDevice() ? false : true,
            addSliderAccess: detectDevice() ? true : false,
            sliderAccessArgs: detectDevice() ? {touchonly: false} : null,
            timeOnlyTitle: '時刻を選択',
            timeText: '時間',
            hourText: '時',
            minuteText: '分',
            closeText: '閉じる',
            currentText: '現時刻',
            onClose: function () {
                $(this).trigger('blur');
            },
        });

        initDatepickerInput();
    }

    function initForDatepicker() {
        var idname;

        $('.datepicker').click(function () {
            idname = $(this).attr("id");
        });

        $('.datepicker').focus(function () {
            idname = $(this).attr("id");
        });

        var showAdditionalButton = function (input) {
            setTimeout(function () {
                var buttonPane = $(input).datepicker("widget").find(".ui-datepicker-buttonpane");
                var btn = $('<button class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" type="button">2ヶ月後</button>');
                btn.unbind("click").bind("click", function (id) {
                    var date = new Date();
                    var after_two_months = date.getFullYear() + "/" + (date.getMonth() + 3) + "/" + date.getDate();
                    $('#' + idname).datepicker("setDate", after_two_months);
                    $(input).datepicker("hide");
                });
                btn.appendTo(buttonPane);
            }, 1);
        };

        $('.datepicker').datepicker({
            showButtonPanel: true,
            beforeShow: showAdditionalButton,
            onChangeMonthYear: showAdditionalButton,
            onclick: showAdditionalButton,
            onClose: function () {
                $(this).trigger('blur');
            }
        });

        initDatepickerInput();
    }

    function initForDatepickerLimit() {
        $('.datepicker_limit').datepicker({
            showButtonPanel: true,
            maxDate: 0,
            onClose: function () {
                $(this).trigger('blur');
            }
        });

        initDatepickerInput();
    }

    function initForDateTimepicker() {
        $('.datetimepicker, .datetimepickerCustom').datetimepicker({
            controlType: detectDevice() ? 'slider' : 'select',
            oneLine: detectDevice() ? false : true,
            addSliderAccess: detectDevice() ? true : false,
            sliderAccessArgs: detectDevice() ? {touchonly: false} : null,
            timeText: '時間',
            hourText: '時',
            minuteText: '分',
            currentText: '現時刻',
            closeText: '閉じる',
            locale: 'ja',
            onClose: function () {
                $(this).trigger('blur');
            },
            onSelect: function () {
                var datepickerOnSelectCallBack = $('#page-data').data('date-picker-on-select');

                if (typeof datepickerOnSelectCallBack !== "undefined" && datepickerOnSelectCallBack && $(this).hasClass('count')) {
                    eval(datepickerOnSelectCallBack + "()");
                }
            }
        });

        initDatepickerInput();
    }

    function initDatepickerInput() {
        if (detectDevice()) { 
            $('.hasDatepicker').prop("readonly", true).addClass("only-ios");
        }

        $('.hasDatepicker').on('focus', function () {
            var rect = $(this)[0].getBoundingClientRect();

            if ($('.ui-datepicker').css('position') === 'fixed') {
                $('.ui-datepicker').css('top', rect.bottom + 'px');
            }
        })
    }

    function detectDevice() {
        var userAgent = window.navigator.userAgent.toLowerCase();
        var widthScreen = $(window).width();
        if (/iphone|ipod|ipad|android/.test(userAgent) && widthScreen < 767) {
            return true;
        }

        return false;
    }


    // Alter datetime picker today button
    var old_goToToday = $.datepicker._gotoToday;

    $.datepicker._gotoToday = function (id) {
        old_goToToday.call(this, id);
        this._selectDate(id);
    }

    return {
        initForTimepicker: initForTimepicker,
        initForDatepicker: initForDatepicker,
        initForDateTimepicker: initForDateTimepicker,
        initForDatepickerLimit: initForDatepickerLimit,
    };
}();
