var ReportJBRCommission = function() {
    return {
        //main function to initiate the module
        init: function() {
            initPseudoScrollBar();
            $('button[type="reset"]').click(function(e){
                e.preventDefault();
                $('.radio-asc').prop('checked', true);
                $('.radio-desc').prop('checked', false);
                $('.radio-asc:last').prop('checked', false);
            
                $('select:eq(0)').val('corp_name');
                $('select:eq(1)').val('');
                $('select:eq(2)').val('commission_rank');
                $('select:eq(3)').val('');
            });
            
            if(checkOrderBy){
                $('input[type="radio"]').prop('checked', false);
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
        }
    }
}();

window.onbeforeunload = function() {
    window.scrollTo(0, 0);
}