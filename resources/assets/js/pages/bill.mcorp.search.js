var Search = function () {
    var corpSearchEl = $('#corp_search'),
        searchBtn = $('#search'),
        searchResultDiv = $('.searchResult'),
        progressBlock = $('.progress-block'),
        progressEl = $('.progress');

    var getPosts = function (searchUrl, page) {
        var url = searchUrl;
        if (typeof page != 'undefined' && page > 1) {
            url = url + '?page=' + page;
        }
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: corpSearchEl.serialize(),
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
                searchResultDiv.html(data);
                if (page > 1)
                    location.hash = page;
            },
            error: function (err) {
                console.log (err.message);
            }
        });
    };

    return {
        init: function (searchUrl) {
            var currentPage = 1;
            $(window).on('hashchange', function () {
                if (window.location.hash) {
                    var page = window.location.hash.replace('#', '');
                    if ( page == Number.NaN || page <= 1) {
                        return false;
                    }
                }
            });

            searchBtn.on('click', function () {
                if ($('#corp_search').valid()) {
                    currentPage = currentPage > 0 ? 1 : currentPage;
                    getPosts(searchUrl, currentPage);
                }
            });
            $(document).on('click', '.next', function (e) {
                e.preventDefault();
                ++currentPage;
                getPosts(searchUrl, currentPage);
            });
            $(document).on('click', '.previous', function (e) {
                e.preventDefault();
                --currentPage;
                getPosts(searchUrl, currentPage);
            });

        }
    }
}();