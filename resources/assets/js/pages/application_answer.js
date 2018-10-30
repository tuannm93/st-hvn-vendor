var ApplicationAnswer = function() {
    function init() {
        var url = jQuery('.table-responsive').attr('data-url');
        var controlEl = {
            nextPage: '.next',
            prevPage: '.previous',
            resultArea: '.table-responsive',
            isInitSearch: true
        };
        ajaxCommon.search(url, controlEl);
    }
    return {
        init: init
    }
}();
