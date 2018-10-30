var AffiliationSearch = function () {
    var bodyPage = $('body');
    var token = $('#csrf-token').val();
    var pageData = $('#page-data');
    var selectCorpPref = $('#list_pref');
    var selectCorpStatus = $('#list_status');
    var selectPerson = $('#list_rits_person');
    var selectGenre = $('#list_genre');
    var selectAvailablePref = $('#list_avail_pref');
    var selectContractStatus = $('#list_contract_status');
    var modalGenrePopup = $('#genreSearchModal');
    var txtSearchEl = $('#txtSearch');
    var genreListModal = $('#genreListSearchModal');
    var currentPage = 1;
    var orderBy = 'id';
    var direction = 'asc';
    var progress = new progressCommon();

    function checkBackBrowser()
    {
        if(window.history.state && window.history.state.dataBack) {
            $('#viewResult').html(window.history.state.dataBack).promise().done(function(){
                var total = $('#dataTotalRow').data('total');
                if (total > 0) {
                    $('#btnExportCsv').show();
                }
            });
        }
    }
    function initForPathWithParameter() {
        var pathCurUrl = window.location.pathname.split('/');
        if (pathCurUrl.length === 4) {
            if (pathCurUrl[1] === 'affiliation' && pathCurUrl[2].length > 0 && isNumeric(pathCurUrl[2]) &&
                pathCurUrl[3].length > 0 && isNumeric(pathCurUrl[3])) {
                currentPage = 1;
                orderBy = 'id';
                direction = 'asc';
                progessSearchPagination(orderBy, direction, currentPage);
            }
        }
    }

    function initSearchForBackFromDetail() {
        var bInstantSearch = $('#instant_search').val();
        if (parseInt(bInstantSearch) === 1) {
            currentPage = 1;
            orderBy = 'id';
            direction = 'asc';
            progessSearchPagination(orderBy, direction, currentPage);
        }
    }

    function isNumeric(value) {
        return /^-{0,1}\d+$/.test(value);
    }

    function initSelector() {
        if (selectGenre.length) {
            selectGenre.multiselect({
                checkAllText: pageData.data('text-selectall'),
                uncheckAllText: pageData.data('text-unselectall'),
                selectedList: 5,
                noneSelectedText: pageData.data('text-none-select')
            }).multiselectfilter({
                label: ''
            });
        }
        if (selectCorpPref.length) {
            selectCorpPref.multiselect({
                checkAllText: pageData.data('text-selectall'),
                uncheckAllText: pageData.data('text-unselectall'),
                selectedList: 5,
                noneSelectedText: pageData.data('text-none-select')
            }).multiselectfilter({
                label: ''
            });
        }
        if (selectPerson.length) {
            selectPerson.multiselect({
                checkAllText: pageData.data('text-selectall'),
                uncheckAllText: pageData.data('text-unselectall'),
                selectedList: 5,
                noneSelectedText: pageData.data('text-none-select')
            });
        }
        if (selectAvailablePref.length) {
            selectAvailablePref.multiselect({
                checkAllText: pageData.data('text-selectall'),
                uncheckAllText: pageData.data('text-unselectall'),
                selectedList: 5,
                noneSelectedText: pageData.data('text-none-select')
            }).multiselectfilter({
                label: ''
            });
        }
        if (selectCorpStatus.length) {
            selectCorpStatus.multiselect({
                checkAllText: pageData.data('text-selectall'),
                uncheckAllText: pageData.data('text-unselectall'),
                selectedList: 5,
                noneSelectedText: pageData.data('text-none-select')
            });
        }
        if (selectContractStatus.length) {
            selectContractStatus.multiselect({
                checkAllText: pageData.data('text-selectall'),
                uncheckAllText: pageData.data('text-unselectall'),
                selectedList: 5,
                noneSelectedText: pageData.data('text-none-select')
            });
        }

        bodyPage.on('keyup keypress', 'form input[type="text"]', function (e) {
            if (e.keyCode === 13 || e.keyCode === 169) {
                return false;
            }
        });
    }

    function progessSearchPagination(orderBy, direction, page) {
        var url = pageData.data('url-search');
        $.ajax({
            type: 'post',
            data: $('#formDataSearchAffiliation').serialize() + '&' + $.param({
                page: page,
                order: orderBy,
                direct: direction
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
            if (smartDevice.checkMobile()) {
                window.history.replaceState({"dataBack": data}, "", "");
            }
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

    function eventClickOnTable() {
        bodyPage.on('click', '#btnNextListAffiliation', function (e) {
            var pagInfo = $('#dataPagInfo');
            var curPage = pagInfo.data('cur');
            var totalPage = pagInfo.data('total');
            if (curPage < totalPage) {
                curPage += 1;
            }
            progessSearchPagination(orderBy, direction, curPage);
            e.preventDefault();
        });
        bodyPage.on('click', '#btnPreviousListAffiliation', function (e) {
            var curPage = $('#dataPagInfo').data('cur');
            if (curPage > 1) {
                curPage -= 1;
            }
            progessSearchPagination(orderBy, direction, curPage);
            e.preventDefault();
        });
        bodyPage.on('click', '.up-priority', function (e) {
            currentPage = 1;
            orderBy = $(this).closest('span').data('col-sort');
            direction = 'asc';
            progessSearchPagination(orderBy, direction, currentPage);
            e.preventDefault();
        });
        bodyPage.on('click', '.down-priority', function (e) {
            currentPage = 1;
            orderBy = $(this).closest('span').data('col-sort');
            direction = 'desc';
            progessSearchPagination(orderBy, direction, currentPage);
            e.preventDefault();
        });
    }

    function eventClick() {
        bodyPage.on('click', '#btnBackToSignUp', function (e) {
            location.href = pageData.data('url-back');
            e.preventDefault();
        });

        bodyPage.on('click', '#btnResetCheckWork24h', function (e) {
            $('input[name=support_24h]').prop('checked', false);
            e.preventDefault();
        });

        bodyPage.on('click', '#searchGenre', function (e) {
            txtSearchEl.val('');
            searchGenre(null);
            modalGenrePopup.modal('show');
            e.preventDefault();
        });

        bodyPage.on('click', '#btnSearch', function (e) {
            if ($('#formDataSearchAffiliation').valid()) {
                currentPage = 1;
                orderBy = 'id';
                direction = 'asc';
                progessSearchPagination(orderBy, direction, currentPage);
                e.preventDefault();
            }
        });
    }

    function searchGenreByModal() {
        bodyPage.on('keyup', '#txtSearch', function (e) {
            searchGenre($(this).val());
        });

        bodyPage.on('click', '#btnModalDecide', function (e) {
            transferDataToSelect();
            e.preventDefault();
        })
    }

    function searchGenre(textSearch) {
        genreListModal.length = 0;
        genreListModal.empty();
        if (textSearch === null) return false;
        var genres = $('[name=multiselect_list_genre]');
        for (var i = 0; i < genres.length; i++) {
            var title = $(genres[i]).attr('title');
            if (title.includes(textSearch)) {
                var option = $('<option>', {
                    'value': genres[i].value,
                    'text': title,
                    'data-target': genres[i].id
                });
                option.appendTo('#genreListSearchModal');
            }
        }
    }

    function transferDataToSelect() {
        var listSelected = $('option:selected', '#genreListSearchModal');
        listSelected.each(function (e) {
            var id = $(this).data('target');
            var eleId = $('#' + id);
            var bChecked = eleId.is(':checked');
            if (!bChecked) {
                eleId.trigger('click');
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

    function workFollow() {
        initForPathWithParameter();
        initSearchForBackFromDetail();
        initSelector();
        Datetime.initForDateTimepicker();
        eventClick();
        eventClickOnTable();
        searchGenreByModal();
        checkBackBrowser();
    }

    return {
        init: workFollow
    }
}();

$(document).ready(function () {
    AffiliationSearch.init();
});
window.onbeforeunload = function() {
    window.scrollTo(0, 0);
}
