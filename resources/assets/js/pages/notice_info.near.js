var NoticeInfoNear = function() {
    return {
        init: function() {
            var registResigning = "input[name='regist-resigning']";
            var checkBoxResigning = "input[type='checkbox'].g_resigning:checked";
            var linkToAgreement = "/auth_infos/agreement_link";
            var initial_width_table = $('.table').width();
            var tablet_width = 678;
            var height_footer = 199;

            function showBtnResigning() {
                $(registResigning).hide();
                $("input[type='checkbox']").click(function() {
                    if ($(checkBoxResigning).length > 0) {
                        $(registResigning).show();
                    } else {
                        $(registResigning).hide();
                    }
                });
            }

            function redirectToAgreement(selector, linkDirect) {
                $(selector).click(function() {
                    window.open(linkDirect, '_blank'); 
                });
            }

            showBtnResigning();
            redirectToAgreement(registResigning, linkToAgreement);
            $('.open-collapse').on('click', function() {
                setTimeout(function() {
                    var current_width_table = $('.table').width();
                    if ($(window).width() < tablet_width && current_width_table > initial_width_table) {
                        $('.pseudo-scroll-bar').css({ 'width': initial_width_table, 'bottom': 0 });
                        $('.pseudo-scroll-bar').show().attr({'data-display': true, 'check-click': true});
                    } else {
                        $('.pseudo-scroll-bar').hide().attr({'data-display': false, 'check-click': false});
                    }
                }, 400);
            });
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
                var clicked = $('.pseudo-scroll-bar').attr('check-click');
                if ($(window).width() < tablet_width) {
                    if ($(window).scrollTop() + $(window).height() > $(document).height() - height_footer && display == 'true' && clicked == 'true') {
                        $('.pseudo-scroll-bar').hide().attr('data-display', false);
                    } else if ($(window).scrollTop() + $(window).height() < $(document).height() - height_footer && display == 'false' && clicked == 'true') {
                        $('.pseudo-scroll-bar').show().attr('data-display', true);
                    }
                }
            });
        }
    }
}();
window.onbeforeunload = function() {
    window.scrollTo(0, 0);
}