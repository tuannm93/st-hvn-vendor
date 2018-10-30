var CommissionSelectMCorp = function () {
    var progress = new progressCommon(),
        progressBar = $('.progress');
    function unEscapeHTML(val) {
        return $('<div/>').html(val).text();
    }

    function selectItemInit() {
        $('.mCorpItem').click(function (e) {
            e.preventDefault();
            var corp_name = $(this).data('corp-name');
            var corp_id = $(this).data('corp-id');
            $(INPUT_CORP_NAME).val(unEscapeHTML(corp_name));
            $(INPUT_CORP_ID).val(unEscapeHTML(corp_id));
            $(MODAL).modal('hide');
        });
    }

    function renderList(listData, parentDiv) {
        var listItem = '';
        listData.forEach(function (data) {
            listItem += '<tr>\n' +
                '            <td>\n' +
                '                <a href="#"\n' +
                '                   id="mCorp' + data.id + '"\n' +
                '                   class="mCorpItem"\n' +
                '                   data-corp-name="' + data.official_corp_name + '"\n' +
                '                   data-corp-id="' + data.id + '">\n' +
                '                    ' + data.official_corp_name + '\n' +
                '                </a>\n' +
                '            </td>\n' +
                '            <td>(' + data.id + ')</td>\n' +
                '        </tr>';
        });
        $(parentDiv).empty();
        $(parentDiv).append(listItem);
    }

    function renderPagination(pagination, paginationDiv) {
        $(paginationDiv).empty();
        if (!(pagination.current_page == 1 && pagination.next_page_url == null)) {
            if (pagination.prev_page_url !== null) {
                $(paginationDiv).append('<li class="mr-4"><a href="' + pagination.prev_page_url + '"  class="active" rel="prev" role="pagination">'+PREV_TEXT+'</a></li>');
            } else {
                $(paginationDiv).append('<li class="mr-4"><a class="disabled">'+PREV_TEXT+'</a></li>');
            }
            if (pagination.next_page_url !== null) {
                $(paginationDiv).append('<li class="mf-4"><a href="' + pagination.next_page_url + '" class="active" rel="next" role="pagination">'+NEXT_TEXT+'</a></li>');
            } else {
                $(paginationDiv).append('<li class="mf-4"><a class="disabled">'+NEXT_TEXT+'</a></li>');
            }
            $('a[role=pagination]').click(function (e) {
                e.preventDefault();
                search($(this).attr('href'), $(FORM).serialize());
            });
        }
    }

    function search(url, data) {
        $(BTN_SEARCH).prop('disabled', true);
        if ($(LIST_DATA_SELECTOR).children().length == 0) {
            $(PAGINATE_SELECTOR).empty();
        }
        $.ajax({
            type: 'GET',
            url: url,
            data: data,
            xhr: function () {
                if (progress.createXHR().timeout == 0) {
                    progressBar.css({
                        width: 100 + "%"
                    });
                }
                return progress.createXHR();
            },
            beforeSend: function () {
                progress.controlProgress(true);
            },
            complete: function () {
                progress.controlProgress(false);
            },
            success: function (e) {
                var response = JSON.parse(e);
                var resData = response.data;
                renderList(resData, LIST_DATA_SELECTOR);
                renderPagination(response, PAGINATE_SELECTOR);
                selectItemInit();
                $(BTN_SEARCH).prop('disabled', false);
            },
            error: function () {

            }
        });
    }

    function searchOnShowModal() {
        $(BTN_TOGGLE_MODAL).click(function (e) {
            var keyword = $(INPUT_CORP_NAME).val();
            if (keyword !== '') {
                $(INPUT_SEARCH_CORP).val(keyword);
                $(LIST_DATA_SELECTOR).empty();
                search(SEARCH_URL, $(FORM).serialize());
            }
        });
    }
    function searchEventInit() {
        $(BTN_SEARCH).click(function (e) {
            e.preventDefault();
            search(SEARCH_URL, $(FORM).serialize());
        });
    }
    function init() {
        search(SEARCH_URL, $(FORM).serialize());
        searchOnShowModal();
        searchEventInit();
    }
    return {
        init: init
    }
}();
jQuery(document).ready(function () {
    CommissionSelectMCorp.init();
});


