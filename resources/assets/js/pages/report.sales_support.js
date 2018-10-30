var ReportSaleSupport = function () {
    function sortSubmit(sort, direction) {
        $('#sale_support_search_form').append('<input type="hidden" name="data[sort]" value="'+sort+'">');
        $('#sale_support_search_form').append('<input type="hidden" name="data[direction]" value="'+direction+'">');
        $('#sale_support_search_form').submit();
    }
 
    /**
     * Set function
     */
    function init() {
        jQuery(".sort").click( function () {
            var sort = $(this).attr('data-sort');
            var direction = $(this).attr('data-direction');
            sortSubmit(sort, direction);
        });

        $('#report_btn_search').click(function() {
            if ($('#last_step_status_').length == 0) {
                $('#sale_support_search_form').append('<input name="last_step_status" value="" id="last_step_status_" type="hidden">');
            }
        });
    }

    return {
        init: init
    }
}();

$(document).ready(function () {
    ReportSaleSupport.init();
});