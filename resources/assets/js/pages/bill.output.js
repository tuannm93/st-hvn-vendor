var BillOutput = function () {
    function initDatePicker() {
        Datetime.initForDatepicker();
    }

    return {
        init: initDatePicker
    }
}();

$(document).ready(function () {
    BillOutput.init();
});
