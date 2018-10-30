var Exclusion = function () {
    function init() {
        Datetime.initForDatepicker();
        Datetime.initForTimepicker();
    }
    return {
        init: init
    }
}();

$(document).ready(function () {
    Exclusion.init();
});
