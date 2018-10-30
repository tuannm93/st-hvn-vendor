var jbrReceiptFollow = function () {

    var resultDiv = $('.content-ajax'),
        orderBy = '',
        sortType = '',
        from_date = '',
        to_date = '';
    var progress = new progressCommon();

    function filterTable() {
        var currentPage = 1;
        $(window).on('hashchange', function () {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 1) {
                    return false;
                }
            }
        });

        $(document).on('click', '.sort', function (e) {
            e.preventDefault();
            var detailSort = $(this).data('sort').split('-');
            orderBy = detailSort[0];
            sortType = detailSort[1];
            getPosts(currentPage);
        });
        $(document).on('click', '.next', function (e) {
            e.preventDefault();
            ++currentPage;
            getPosts(currentPage);
        });
        $(document).on('click', '.previous', function (e) {
            e.preventDefault();
            --currentPage;
            getPosts(currentPage);
        });

        $(document).on('click', '#search', function (e) {
            if ($('#search-form').valid()) {
                e.preventDefault();
                from_date = $('#from_date').val();
                to_date = $('#to_date').val();
                getPosts(currentPage);
            }
        });
    }

    function getPosts(currentPage) {
        var url = urlGetJbrList;
        var page = currentPage;

        if (typeof page != 'undefined' && page > 1) {
            url = url + '?page=' + page + '&nameColumn=' + orderBy + '&order=' + sortType + '&from_date=' + from_date + '&to_date=' + to_date;
        } else {
            url = url + '?nameColumn=' + orderBy + '&order=' + sortType + '&from_date=' + from_date + '&to_date=' + to_date;
        }

        $.ajax({
            type: 'post',
            url: url,
            data: {},
            processData: false,
            xhr: function () {
                return progress.createXHR();
            },
            beforeSend: function () {
                progress.controlProgress(true);
            },
            complete: function () {
                progress.controlProgress(false);
            },
            success: function (data) {
                resultDiv.html(data);
                window.scrollTo(0, 0);
            },
            error: function (err) {
                console.log('error');
            }
        });

    }
    function initPseudoScrollBar() {
        if ($('.custom-scroll-x').length) {
            var table_scroll_width = $('.add-pseudo-scroll-bar').width();
            var width_scroll = $('.custom-scroll-x').width();
            var table_offset_top = $('.custom-scroll-x').offset().top;

            $('.scroll-bar').css('width', table_scroll_width);
            $('.pseudo-scroll-bar').css({ 'width': width_scroll, 'bottom': 0 }).show();
            $('.pseudo-scroll-bar').scroll(function () {
                var left = Number($('.pseudo-scroll-bar').scrollLeft());
                $('.custom-scroll-x').scrollLeft(left);
            });
            $('.custom-scroll-x').scroll(function () {
                var left = Number($('.custom-scroll-x').scrollLeft());
                $('.pseudo-scroll-bar').scrollLeft(left);
            });
            $(window).on("scroll", function () {
                var display = $('.pseudo-scroll-bar').attr('data-display');
                if ($(window).scrollTop() + $(window).height() > table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'true') {
                    $('.pseudo-scroll-bar').hide().attr('data-display', false);
                } else if ($(window).scrollTop() + $(window).height() < table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'false') {
                    $('.pseudo-scroll-bar').show().attr('data-display', true);
                }
            });
        }
    }
    function initBack() {
        if (smartDevice.checkMobile()) {
            $('#search').trigger('click');
        }
    }
    function init() {
        Datetime.initForDatepicker();
        filterTable();
        initPseudoScrollBar();
        initBack();
    }

    return {
        init: init
    }

}();

$(document).ready(function () {
    jbrReceiptFollow.init();
});
window.onbeforeunload = function () {
    window.scrollTo(0, 0);
}
