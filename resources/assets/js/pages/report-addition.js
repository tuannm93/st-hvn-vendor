var ReportAddition = function() {
    initPseudoScrollBar();

    function search() {
        $('#searchBtn').on('click', function() {
            var demandFlg = $('input[name=demand_flg]').is(':checked');
            if (demandFlg === true) {
                window.location.href = CURRENT_URL + '?check_demand_flg=true';
            } else {
                window.location.href = CURRENT_URL;
            }
        });

    }

    function csvExport() {
        $('#csvExportBtn').on('click', function() {
            window.location.href = EXPORT_URL;
        });
    }
    /**
     * Set function
     */
    function init() {
        search();
        csvExport();
    }
    function initPseudoScrollBar() {
        if ($('.custom-scroll-x').length) {
            var table_scroll_width = $('.add-pseudo-scroll-bar').width();
            var width_scroll = $('.custom-scroll-x').width();
            var table_offset_top = $('.custom-scroll-x').offset().top;

            $('.scroll-bar').css('width', table_scroll_width);
            $('.pseudo-scroll-bar').css({ 'width': width_scroll, 'bottom': 0 }).show();
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
                if ($(window).scrollTop() + $(window).height() > table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'true') {
                    $('.pseudo-scroll-bar').hide().attr('data-display', false);
                } else if ($(window).scrollTop() + $(window).height() < table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'false') {
                    $('.pseudo-scroll-bar').show().attr('data-display', true);
                }
            });
        }
    }
    return {
        init: init
    }
}();
window.onbeforeunload = function() {
    window.scrollTo(0, 0);
};