var AffiliationGenreResign = function () {
    var bodyPage = $('body');
    var pageData = $('#pageData');
    var token = $('#csrf-token').val();
    var bReconfirmFax = false;
    var progress = new progressCommon();

    function eventClick(dialogConfirm) {
        bodyPage.on('click', '.btnResign', function (e) {
            $.ajax({
                type: 'post',
                data: getDataFromForm(),
                url: pageData.data('url-resign'),
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
                if (data.code === 'SUCCESS') {
                    var url = pageData.data('url-back');
                    location.href = url;
                } else {
                    location.reload();
                }
            }).fail(function (jXHR, textStatus) {
                console.log('FAIL ' + textStatus + " ### " + JSON.stringify(jXHR));
            });
            e.preventDefault();
        });

        bodyPage.on('click', '.btnReconfirm', function (e) {
            bReconfirmFax = 0;
            dialogConfirm.modal('show');
            e.preventDefault();
        });

        bodyPage.on('click', '.btnReconfirmFax', function (e) {
            bReconfirmFax = 1;
            dialogConfirm.modal('show');
            e.preventDefault();
        });

        dialogConfirm.find('.st-pp-confirm').on('click', function (e) {
            $.ajax({
                type: 'post',
                data: {idCorp: $('#corp_id').val(), isFax: bReconfirmFax},
                url: pageData.data('url-reconfirm'),
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
                dialogConfirm.modal('hide');
                var url = pageData.data('url-back');
                location.href = url;
            }).fail(function (jXHR, textStatus) {
                dialogConfirm.modal('hide');
                console.log('FAIL ' + textStatus + " ### " + JSON.stringify(jXHR));
            });
            e.preventDefault();
        });
    }

    function getDataFromForm() {
        var listData = [];
        $.each($('.dataForm').closest('tr'), function () {
            var obj = {};
            var inputs = $(this).find('input[type!=checkbox]');
            for (var x = 0; x < inputs.length; x++) {
                var input = inputs[x];
                obj[input.name] = input.value;
            }
            var checkBox = $(this).find('[name=check_box_commission_type]')[0];
            if (checkBox && checkBox.checked) {
                obj['category_id'] = checkBox.value;
            }
            var selectEl = $(this).find('[name=expertise_commission_type]')[0];
            if (selectEl)
                obj['selectOption'] = $('option:selected', selectEl).val();
            listData.push(obj);
        });
        var data = {
            'corpCommissionType': $('#corp_commission_type').val(),
            'idCorp': $('#corp_id').val(),
            'checkedCategory': $('#categoriesSelected').val(),
            'listCorpCategoryTemp': JSON.stringify(listData)
        };
        return data;
    }

    function workFollow() {
        var ppConfirm = initDialogConfirm();
        eventClick(ppConfirm);
    }

    function initDialogConfirm() {
        var ppConfirm;
        var content = pageData.data('title');
        var btnYes = pageData.data('btn-ok');
        var btnNo = pageData.data('btn-no');
        var confirmPopup = new popupCommon(1, {msg: content, close: btnNo, confirm: btnYes});
        var confirmPopupHtml = confirmPopup.renderView();
        ppConfirm = $(confirmPopupHtml);
        return ppConfirm;
    }

    return {
        init: workFollow
    }
}();
