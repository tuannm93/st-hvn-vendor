var AutoCommissionCorpSelect = function() {
    var bodyPage = $('body');
    var token = $('#csrf-token').val();
    var pageData = $('#page-data');
    var listGenreControl = $('#genre_id');
    var listCategoryControl = $('#category_id');
    var listPrefControl = $('#perf_id');
    var listCorpSelected = $('#list_corp_selected');
    var listCorpUnselected = $('#list_corp_unselected');
    var listCorpCommission = $('#list_corp_automatic');
    var btnGetCorp = $('#btn_get_corp');
    var corpSearchMessage = $('#corp_search_message');

    function initSelector() {
        listGenreControl.multiselect({
            multiple: false,
            selectedList: 5,
            noneSelectedText: pageData.data('text-none-select')
        }).multiselectfilter({
            label: ''
        });
        listCategoryControl.multiselect({
            checkAllText: pageData.data('text-selectall'),
            uncheckAllText: pageData.data('text-unselectall'),
            selectedList: 5,
            noneSelectedText: pageData.data('text-none-select')
        }).multiselectfilter({
            label: ''
        });
        listPrefControl.multiselect({
            checkAllText: pageData.data('text-selectall'),
            uncheckAllText: pageData.data('text-unselectall'),
            selectedList: 5,
            noneSelectedText: pageData.data('text-none-select')
        }).multiselectfilter({
            label: ''
        });
        bodyPage.on('change', '#genre_id', function(e) {
            var idSelected = $(this).val();
            if (idSelected !== -1) {
                $.ajax({
                    type: 'post',
                    data: {
                        'idGenre': idSelected
                    },
                    url: pageData.data('url-category')
                }).done(function(data) {
                    listCategoryControl.children().remove();
                    var dataArray = Object.keys(data).map(function(value) {
                        return { name: data[value], id: value };
                    });
                    dataArray.forEach(function(obj) {
                        listCategoryControl.append($('<option>').text(obj.name).attr('value', obj.id));
                    });
                    listCategoryControl.multiselect('refresh');
                }).fail(function(jXHR, textStatus) {
                    console.log('FAIL ' + textStatus + " ### " + JSON.stringify(jXHR));
                });
            }
            e.preventDefault();
        });

        listCategoryControl.on('multiselectclose', function(e) {
            getListCorpByGenreCatePref();
            e.preventDefault();
        });

        listPrefControl.on('multiselectclose', function(e) {
            getListCorpByGenreCatePref();
            e.preventDefault();
        });
    }

    function getListCorpByGenreCatePref() {
        var idGenre = listGenreControl.val();
        var idCategory = listCategoryControl.val();
        var idPref = listPrefControl.val();
        if (idGenre !== -1 && idGenre && idCategory && idCategory.length > 0 && idPref && idPref.length > 0) {
            $.ajax({
                type: 'post',
                data: {
                    genre: idGenre,
                    cate: idCategory,
                    pref: idPref
                },
                url: pageData.data('url-listcorp'),
                beforeSend: function() {}
            }).done(function(data) {
                listCorpCommission.html('');
                listCorpSelected.html('');
                listCorpUnselected.html('');
                if (data instanceof Array && data.length > 0) {
                    var sHtmlCommission = '';
                    var sHtmlSelect = '';
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].process_type === 2)
                            sHtmlCommission += '<option value="' + data[i].idCorp + '">' + data[i].nameCorp + '</option>';
                        if (data[i].process_type === 1)
                            sHtmlSelect += '<option value="' + data[i].idCorp + '">' + data[i].nameCorp + '</option>';
                    }
                    listCorpCommission.html(sHtmlCommission);
                    listCorpSelected.html(sHtmlSelect);
                }
                console.log(data);
            }).fail(function(jXHR, textStatus) {
                console.log('FAIL ' + textStatus + " ### " + JSON.stringify(jXHR));
            });
        }
    }

    function clickEventChangeDataPosition() {
        bodyPage.on('click', '#commission_to_selected', function(e) {
            $('option:selected', '#list_corp_automatic').remove().appendTo(listCorpSelected);
        });

        bodyPage.on('click', '#commission_to_unselected', function(e) {
            $('option:selected', '#list_corp_automatic').remove().appendTo(listCorpUnselected);
        });

        bodyPage.on('click', '#selected_to_commission', function(e) {
            $('option:selected', '#list_corp_selected').remove().appendTo(listCorpCommission);
        });

        bodyPage.on('click', '#selected_to_unselected', function(e) {
            $('option:selected', '#list_corp_selected').remove().appendTo(listCorpUnselected);
        });

        bodyPage.on('click', '#unselected_to_commission', function(e) {
            $('option:selected', '#list_corp_unselected').remove().appendTo(listCorpCommission);
        });

        bodyPage.on('click', '#unselected_to_selected', function(e) {
            $('option:selected', '#list_corp_unselected').remove().appendTo(listCorpSelected);
        });

        bodyPage.on('click', '.up-priority', function(e) {
            var selectBox = $(this).closest('.p-2').find('select')[0];
            var optionList = selectBox.getElementsByTagName('option');
            for (var i = 0; i < optionList.length; i++) {
                if (optionList[i].selected) {
                    if (i > 0 && !optionList[i - 1].selected) {
                        selectBox.insertBefore(optionList[i], optionList[i - 1]);
                    }
                }
            }
            selectBox.focus();
        });

        bodyPage.on('click', '.down-priority', function(e) {
            var selectBox = $(this).closest('.p-2').find('select')[0];
            var optionList = selectBox.getElementsByTagName('option');
            for (var i = optionList.length - 1; i >= 0; i--) {
                if (optionList[i].selected) {
                    if (i < optionList.length - 1 && !optionList[i + 1].selected) {
                        selectBox.insertBefore(optionList[i + 1], optionList[i]);
                    }
                }
            }
            selectBox.focus();
        });
    }

    function mainEvent() {
        bodyPage.on('click', '#btnBackIndex', function(e) {
            location.href = pageData.data('url-back');
            e.preventDefault();
        });

        bodyPage.on('click', '#btn_get_corp', function(e) {
            $('option', '#list_corp_automatic').prop('selected', true);
            $('option', '#list_corp_selected').prop('selected', true);
            var listPref = listPrefControl.val();
            var listCate = listCategoryControl.val();
            var typeSearch = $('option:selected', '#select_search_type').val();
            var textSearch = $('#text_search_input').val();
            var listComissionedCorp = listCorpCommission.val();
            var listCorpSelect = listCorpSelected.val();
            var listCorp = [];
            if (listComissionedCorp instanceof Array && listComissionedCorp.length > 0) {
                listCorp = listComissionedCorp;
                if (listCorpSelect instanceof Array && listCorpSelect.length > 0) {
                    listCorp = listComissionedCorp.concat(listCorpSelect);
                }
            } else {
                if (listCorpSelect instanceof Array && listCorpSelect.length > 0) {
                    listCorp = listCorpSelect;
                }
            }
            $.ajax({
                type: 'post',
                data: {
                    listPref: listPref,
                    listCate: listCate,
                    listCorp: listCorp,
                    type: typeSearch,
                    search: textSearch
                },
                url: pageData.data('url-searchcorp'),
                beforeSend: function() {
                    btnGetCorp.prop('disabled', true);
                    $('#corp_search_message').find('span')
                        .text(pageData.data('ajax-load'));
                }
            }).done(function(data) {
                btnGetCorp.prop('disabled', false);
                corpSearchMessage.find('span').text(pageData.data('ajax-success'));
                listCorpUnselected.html('');
                if (data.length > 0) {
                    data.forEach(function(obj) {
                        listCorpUnselected.append($('<option>').text(obj.name).attr('value', obj.id));
                    });
                } else {
                    corpSearchMessage.find('span').text(pageData.data('ajax-fail'));
                }
            }).fail(function(jXHR, textStatus) {
                console.log('FAIL ' + textStatus + " ### " + JSON.stringify(jXHR));
                btnGetCorp.prop('disabled', false);
                corpSearchMessage.find('span').text(pageData.data('ajax-fail'));
            });
            e.preventDefault();
        });

        bodyPage.on('click', '#btn_register_corp', function(e) {
            $('option', '#list_corp_automatic').prop('selected', true);
            $('option', '#list_corp_selected').prop('selected', true);
            var url = $(this).data('url-editcrop');
            var listPref = listPrefControl.val();
            var listCate = listCategoryControl.val();
            var listComissionedCorp = listCorpCommission.val();
            var listCorpSelect = listCorpSelected.val();
            $.ajax({
                type: 'post',
                data: {
                    pref: listPref,
                    cate: listCate,
                    commissionCorp: listComissionedCorp,
                    selectedCorp: listCorpSelect
                },
                url: url,
                beforeSend: function() {}
            }).done(function(data) {
                // console.log(data);
                location.href = pageData.data('url-back');
            }).fail(function(jXHR, textStatus) {
                console.log('FAIL ' + textStatus + " ### " + JSON.stringify(jXHR));
            });
        });
    }

    function workFollow() {
        initSelector();
        clickEventChangeDataPosition();
        mainEvent()
    }

    return {
        init: workFollow
    }
}();

$(document).ready(function() {
    AutoCommissionCorpSelect.init();
});