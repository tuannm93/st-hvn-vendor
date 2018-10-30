var UserSearch = function () {
    var bodyPage = $('body');
    var token = $('input[name="_token"]').val();
    var dataPage = $('#dataPage');
    var url = dataPage.data('url-search');
    var progress = new progressCommon();
    var checkBack = $('#checkBack');

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
                if ($(window).scrollTop() + $(window).height() > table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'true' || $(window).scrollTop() + $(window).height() < table_offset_top && display == 'true') {
                    $('.pseudo-scroll-bar').hide().attr('data-display', false);
                } else if ($(window).scrollTop() + $(window).height() < table_offset_top + $('.add-pseudo-scroll-bar').height() && table_offset_top < $(window).scrollTop() + $(window).height() && display == 'false') {
                    $('.pseudo-scroll-bar').show().attr('data-display', true);
                }
            });
        }
    }

    function loadPageSearch() {
        var dataForm = $('#UserSearch').serialize();
        if(checkBack.val()){
            callAjax(dataForm, url, token, 1);
        }
    }

    function callAjax(dataForm, url, token, page) {
        $.ajax({
            type: 'post',
            data: dataForm + '&' + $.param({
                page: page
            }),
            url: url,
            xhr: function () {
                return progress.createXHR();
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-CSRF-TOKEN", token);
                progress.controlProgress(true);
            },
            complete: function () {
                progress.controlProgress(false);
            }
        }).done(function (data) {
            $('#viewResult').html('').html(data);
            window.history.replaceState({"dataBack": data}, "", "");
            var total = $('#dataTotalRow').data('total');
            if (total > 0) {
                $('#btnExportCsv').show();
            } else {
                $('#btnExportCsv').hide();
            }
            setTimeout(function() {
                initPseudoScrollBar();
            }, 100);
        }).fail(function (jXHR, textStatus) {
            console.log(jXHR);
        });
    }

    function clickSearch(buttonSearch) {
        $(buttonSearch).on('click', function() {
            var dataForm = $('#UserSearch').serialize();
            callAjax(dataForm, url, token, 1);
            return false;
        })
    }

    function clickPaginate() {
        bodyPage.on('click', '#btnNextListUser', function (e) {
            var pagInfo = $('#dataPagInfo');
            var curPage = pagInfo.data('cur');
            var totalPage = pagInfo.data('total');
            if (curPage < totalPage) {
                curPage += 1;
            }
            var dataForm = $('#UserSearch').serialize();
            callAjax(dataForm, url, token, curPage);
            e.preventDefault();
        });
        bodyPage.on('click', '#btnPreviousListUser', function (e) {
            var curPage = $('#dataPagInfo').data('cur');
            if (curPage > 1) {
                curPage -= 1;
            }
            var dataForm = $('#UserSearch').serialize();
            callAjax(dataForm, url, token, curPage);
            e.preventDefault();
        });
    }

    function checkBackBrowser() {
        if (smartDevice.checkMobile()) {
            if (window.history.state && window.history.state.dataBack) {
                $('#viewResult').html(window.history.state.dataBack);
                $('#btnExportCsv').show();
            }
        }
    }

    function init() {
        $('#btnExportCsv').hide();
        clickSearch('#buttonSearchForm');
        clickPaginate();
        loadPageSearch();
        checkBackBrowser();
    }

    return {
        init: init
    };
}();

$(document).ready(function () {
    UserSearch.init();
});
window.onbeforeunload = function () {
    window.scrollTo(0, 0);
};
