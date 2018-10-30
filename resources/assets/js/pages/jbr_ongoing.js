var JbrOngoing = function () {
    var resultDiv = $('.report-jbr-ongoing'),
        progressBlock = $('.progress-block'),
        progressEl = $('.progress'),
        orderBy = 'demand_infos.contact_desired_time',
        sortType = 'asc';

    var getPosts = function (urlGetJbrOngoing, page) {
        var url = urlGetJbrOngoing;
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

    return {
        init: function (urlGetJbrOngoing) {
            var currentPage = 1;
            $(window).on('hashchange', function () {
                if (window.location.hash) {
                    var page = window.location.hash.replace('#', '');
                    if ( page == Number.NaN || page <= 1) {
                        return false;
                    }
                }
            });

            $(document).on('click', '.sort', function (e) {
                e.preventDefault();
                var detailSort = $(this).data('sort').split('-');
                orderBy = detailSort[0];
                sortType = detailSort[1];
                getPosts(urlGetJbrOngoing, currentPage, orderBy, sortType);
            });
            $(document).on('click', '.next', function (e) {
                e.preventDefault();
                ++currentPage;
                getPosts(urlGetJbrOngoing, currentPage, orderBy, sortType);
            });
            $(document).on('click', '.previous', function (e) {
                e.preventDefault();
                --currentPage;
                getPosts(urlGetJbrOngoing, currentPage, orderBy, sortType);
            });
        }
    }
}();

$(document).ready(function() {
    JbrOngoing.init(urlGetJbrOngoing);
})