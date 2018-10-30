var AntisocialFollow = function() {
    function init() { 
        $(document).on('change', '[name="check[]"]', function () {
            $('#update').prop('disabled', !$('[name="check[]"]').is(':checked'));
        });
        $(document).on('click', '#checkAll', function () {
            var texts = ["全加盟店を選択", "全加盟店を選択解除"];
            var t = $(this);
            var mode = (t.data('mode') + 1) % 2;
            $('[name="check[]"]').prop('checked', (mode === 1)).trigger('change');
            t.val(texts[mode]);
            t.text(texts[mode]).data('mode', mode);
        });
        var url = jQuery('.antisocial-follow').attr('data-url');
        var controlEl = {
            nextPage: '.next',
            prevPage: '.previous',
            resultArea: '.antisocial-follow',
        };
        initPseudoScrollBar();
        ajaxCommon.search(url, controlEl);
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
}
$(document).ready(function () {
    AntisocialFollow.init();
});