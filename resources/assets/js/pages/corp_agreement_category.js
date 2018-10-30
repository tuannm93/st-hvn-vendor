var CorpAgreementCategory = function() {
    function init() {
        var url = jQuery('.ajax-table').attr('data-url');
        var controlEl = {
            nextPage: '.next',
            prevPage: '.previous',
            resultArea: '.ajax-table',
            isInitSearch: true
        };
        ajaxCommon.search(url, controlEl);
    }
    return {
        init: init
    }
}();