var DemandInfo = function() {
    var tablet_width = 678;
    initPseudoScrollBar();

    function fixDemandStatusOnlyAgencyBefore() {
        if ($("#b_check").prop('checked')) {
            $("#demand_status").multiselect('uncheckAll');
            $("#ui-multiselect-demand_status-option-2").trigger('click');
            $("#demand_status").multiselect('update');
        }
    }

    function init() {
        $("#b_check").change(function() {
            fixDemandStatusOnlyAgencyBefore();
        });
        //pagination
        var url = jQuery('.demand_info_table').attr('data-url');
        var triggerSearch = jQuery('.demand_info_table').attr('data-trigger');
        var controlEl = {
            nextPage: '.next',
            prevPage: '.previous',
            resultArea: '.demand_info_table',
            searchEl: '#search',
            formId: '#demand_info',
            sorts: ['.sort-idUp', '.sort-idDown', '.sort-immediatelyUp', '.sort-immediatelyDown',
                '.sort-demandstatusUp', '.sort-demandstatusDown', '.sort-customernameUp', '.sort-customernameDown',
                '.sort-customercorpnameUp', '.sort-customercorpnameDown', '.sort-sitenameUp', '.sort-sitenameDowm',
                '.sort-categorynameUp', '.sort-categorynameDown', '.sort-jbrordernoUp', '.sort-jbrordernoDown',
                '.sort-receivedatetimeUp', '.sort-receivedatetimeDown', '.sort-contactdesiredtimeUp',
                '.sort-contactdesiredtimeDown', '.sort-selectionsystemUp', '.sort-selectionsystemDown'
            ]
        };
        var widthScreen = $(window).width();
        if (widthScreen < 767) {
            if (window.history.state && window.history.state.dataBack) {
                triggerSearch = 0;
                $('.demand_info_table').html(window.history.state.dataBack);
                jQuery('#demand_csv').removeClass('d-none');
            }
        }
        ajaxCommon.search(url, controlEl, triggerSearch);
        $('#id, #corp_id').keypress(function(e) {
            if (e.keyCode < 48 || e.keyCode > 57) {
                return false;
            }
        });
    }
    function initPseudoScrollBar() {
        if ($('.custom-scroll-x').length) {
            var table_scroll_width = $('.add-pseudo-scroll-bar').width();
            var table_offset_top = $('.custom-scroll-x').offset().top;
            var width_scroll = $('.custom-scroll-x').width();

            $('.scroll-bar').css('width', table_scroll_width);
            $('.pseudo-scroll-bar').css({ 'width': width_scroll, 'bottom': 0 });
            $('.pseudo-scroll-bar').scroll(function() {
                var left = Number($('.pseudo-scroll-bar').scrollLeft());
                $('.custom-scroll-x').scrollLeft(left);
            });
            $('.custom-scroll-x').scroll(function() {
                var left = Number($('.custom-scroll-x').scrollLeft());
                $('.pseudo-scroll-bar').scrollLeft(left);
            });
            $(window).on("scroll", function() {
                var display = $('.pseudo-scroll-bar').attr('data-display');
                if ($(window).width() < tablet_width) {
                    if ($(window).scrollTop() + $(window).height() > table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'true' || $(window).scrollTop() + $(window).height() < table_offset_top + 50 && display == 'true') {
                        $('.pseudo-scroll-bar').hide().attr('data-display', false);
                    } else if ($(window).scrollTop() + $(window).height() < table_offset_top + $('.add-pseudo-scroll-bar').height() && $(window).scrollTop() + $(window).height() > table_offset_top + 50 && display == 'false') {
                        $('.pseudo-scroll-bar').css('bottom', $('.fixed-button').outerHeight());
                        $('.pseudo-scroll-bar').show().attr('data-display', true);
                    }
                } else {
                    if ($(window).scrollTop() + $(window).height() > table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'true') {
                        $('.pseudo-scroll-bar').hide().attr('data-display', false);
                    } else if ($(window).scrollTop() + $(window).height() < table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'false') {
                        $('.pseudo-scroll-bar').show().attr('data-display', true);
                    }
                }
            });
        }
    }
    return {
        init: init
    }
}();
$(document).ready(function() {
    DemandInfo.init();
});
window.onbeforeunload = function() {
    window.scrollTo(0, 0);
};
