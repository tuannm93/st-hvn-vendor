var ReportRealTime = function () {

    function reloadPage() {
        $('#reloadButton').on('click',function(){
            location.reload();
        });
    }

    /**
     * Set function
     */
    function init() {
        reloadPage();
    }

    return {
        init: init
    }
}();

$(document).ready(function () {
    ReportRealTime.init();
});