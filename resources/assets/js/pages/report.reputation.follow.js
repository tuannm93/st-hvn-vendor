var ReportReputationFollow = function () {
    var bodyPage = $('body');
    var token = $('#csrf-token').val();
    var pageData = $('#page-data');
    var progress = new progressCommon();

    initPseudoScrollBar();
    function clickEvent() {
        bodyPage.on('click', '#btnSelectAll', function (e) {
            var textSelectAll = pageData.data('text-selectall');
            var textUnselectAll = pageData.data('text-unselectall');
            var curText = $(this).text();
            var listEleCheckbox = $('#viewResult').find('.idCorpCheck');
            if (curText.trim() === textSelectAll.trim()) {
                $.each(listEleCheckbox, function (index) {
                    $(this).prop('checked', true);
                });
                $(this).text(textUnselectAll);
                $('#btnUpdateDateTime').prop('disabled', false);
            }
            if (curText.trim() === textUnselectAll.trim()) {
                $.each(listEleCheckbox, function (index) {
                    $(this).prop('checked', false);
                });
                $(this).text(textSelectAll);
                $('#btnUpdateDateTime').prop('disabled', true);
            }
            e.preventDefault();
        });

        bodyPage.on('change', 'input[name=checkUpdate]', function (e) {
            var checkBox = $('.idCorpCheck');
            var hasOneChecked = checkBox.is(':checked');
            $('#btnUpdateDateTime').prop('disabled', !hasOneChecked);
            var row = checkBox.length;
            var bChecked = $('.idCorpCheck:checked').length;
            if (bChecked === row) {
                $('#btnSelectAll').text(pageData.data('text-unselectall'));
            }
            if (bChecked === 0) {
                $('#btnSelectAll').text(pageData.data('text-selectall'));
            }
        });

        bodyPage.on('click', '#btnUpdateDateTime', function (e) {
            var data = getDataToUpdate();
            $.ajax({
                type: 'post',
                data: { listId: data },
                url: pageData.data('url-update'),
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
                location.reload();
            }).fail(function (jXHR, textStatus) { });
            e.preventDefault();
        });

        bodyPage.on('click', '#btnPrevious', function (e) {
            var curPage = $('#dataPagInfo').data('cur');
            if (curPage > 1) {
                curPage -= 1;
            }
            progressPagination(curPage);
            e.preventDefault();
        });

        bodyPage.on('click', '#btnNext', function (e) {
            var pagInfo = $('#dataPagInfo');
            var curPage = pagInfo.data('cur');
            var totalPage = pagInfo.data('total');
            if (curPage < totalPage) {
                curPage += 1;
            }
            progressPagination(curPage);
            e.preventDefault();
        })
    }

    function progressPagination(page) {
        $.ajax({
            type: 'post',
            data: { page: page },
            url: pageData.data('url-search'),
            xhr: function () {
                return progress.createXHR();
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-CSRF-TOKEN", token);
                progress.controlProgress(true);
            },
            complete: function () {
                progress.controlProgress(false);
                window.scrollTo(0, 0);
            }
        }).done(function (data) {
            $('#viewResult').html('').html(data);
            $('#btnUpdateDateTime').prop('disabled', true);
            $('#btnSelectAll').text(pageData.data('text-selectall'));
        }).fail(function (jXHR, textStatus) { });
    }

    function getDataToUpdate() {
        var listData = [];
        $('.idCorpCheck:checked').each(function (e) {
            var idCorp = $(this).closest('td').find('.idCorp').val();
            listData.push(idCorp);
        });
        return listData;
    }

    function workFollow() {
        clickEvent();
    }
    function initPseudoScrollBar() {
        if ($('.custom-scroll-x').length) {
            var table_scroll_width = $('.add-pseudo-scroll-bar').width();
            var table_offset_top = $('.custom-scroll-x').offset().top;
            var width_scroll = $('.custom-scroll-x').width();

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

    return {
        init: workFollow
    }
}();

$(document).ready(function () {
    ReportReputationFollow.init();
});
window.onbeforeunload = function () {
    window.scrollTo(0, 0);
};