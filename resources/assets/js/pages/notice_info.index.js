var NoticeInfoIndex = function () {
    var resultDiv = $('.notice-info-index'),
        progressBlock = $('.progress-block'),
        progressEl = $('.progress'),
        orderBy = 'notice_infos.id',
        sortType = 'desc';

    var getPosts = function (urlAjaxGetListNoticeInfo, page) {
        var url = urlAjaxGetListNoticeInfo;
        if (typeof page != 'undefined' && page > 1) {
            url = url + '?page=' + page + '&orderBy=' + orderBy + '&sort=' + sortType;
        } else {
            url = url + '?orderBy=' + orderBy + '&sort=' + sortType;
        }
        $.ajax({
            type: 'post',
            url: url,
            data: {},
            processData: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    var percentComplete = evt.loaded / evt.total;
                    progressEl.css({
                        width: percentComplete * 100 + "%"
                    });
                }, false);
                xhr.addEventListener("progress", function (evt) {
                    var percentComplete = evt.loaded / evt.total;
                    progressEl.css({
                        width: percentComplete * 100 + "%"
                    });
                }, false);
                return xhr;
            },
            beforeSend: function () {
                progressBlock.show();
                progressEl.show();
            },
            complete: function () {
                progressBlock.hide();
                progressEl.hide();
            },
            success: function (data) {
                resultDiv.html(data);
            },
            error: function (err) {
            }
        });
    };
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
        init: function (urlAjaxGetListNoticeInfo) {
            var currentPage = 1;
            $(window).on('hashchange', function () {
                if (window.location.hash) {
                    var page = window.location.hash.replace('#', '');
                    if ( page == Number.NaN || page <= 1) {
                        return false;
                    }
                }
            });

            $(document).on('click', '.sort-item', function (e) {
                e.preventDefault();
                var detailSort = $(this).find('span').data('sort').split('-');
                orderBy = detailSort[0];
                sortType = detailSort[1];
                getPosts(urlAjaxGetListNoticeInfo, currentPage, orderBy, sortType);
            });
            $(document).on('click', '.next', function (e) {
                e.preventDefault();
                ++currentPage;
                getPosts(urlAjaxGetListNoticeInfo, currentPage, orderBy, sortType);
            });
            $(document).on('click', '.previous', function (e) {
                e.preventDefault();
                --currentPage;
                getPosts(urlAjaxGetListNoticeInfo, currentPage, orderBy, sortType);
            });
            $(document).on('click', '#regist', function (e) {
                e.preventDefault();
                var url = $(this).data('url');
                window.location.href = url;
            });
            initPseudoScrollBar();
        }
    }
}();

$(document).ready(function() {
    NoticeInfoIndex.init(urlAjaxGetListNoticeInfo);
})
window.onbeforeunload = function() {
    window.scrollTo(0, 0);
}
 
