var AffiliationResign = function () {
    var bodyPage = $('body');
    var pageData = $('#pageData');
    var token = $('#csrf-token').val();
    var bReconfirmFax = false;
    var progress = new progressCommon();

    function eventClick(dialogConfirm) {
        $('body').on('click', '#btnUpdateResign', function (e) {
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
                location.reload();
            }).fail(function (jXHR, textStatus) {
            });
            e.preventDefault();
        });

        bodyPage.on('click', '#btnCheckAll', function (e) {
            var textSelectAll = pageData.data('text-check');
            var textUnselectAll = pageData.data('text-uncheck');
            var curText = $(this).text();
            var listEleCheckbox = $('.cbApp');
            if (curText.trim() === textSelectAll.trim()) {
                $.each(listEleCheckbox, function (index) {
                    $(this).prop('checked', true);
                });
                $(this).text(textUnselectAll);
            }
            if (curText.trim() === textUnselectAll.trim()) {
                $.each(listEleCheckbox, function (index) {
                    $(this).prop('checked', false);
                });
                $(this).text(textSelectAll);
            }
            e.preventDefault();
        });

        bodyPage.on('click', '#btnReconfirmResign', function (e) {
            bReconfirmFax = 0;
            dialogConfirm.modal('show');
            e.preventDefault();
        });

        bodyPage.on('click', '#btnReconfirmFaxResign', function (e) {
            bReconfirmFax = 1;
            dialogConfirm.modal('show');
            e.preventDefault();
        });

        dialogConfirm.find('.st-pp-confirm').on('click', function (e) {
            var idCorp = $('#corp_id').val();
            $.ajax({
                type: 'post',
                data: {corpId: idCorp, isFax: bReconfirmFax},
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
                location.reload();
            }).fail(function (jXHR, textStatus) {
                dialogConfirm.modal('hide');
                console.log('FAIL ' + textStatus + " ### " + JSON.stringify(jXHR));
            });
            e.preventDefault();
        });

        bodyPage.on('change', '.corp_commission_type', function (e) {
            var selectOrderUnit = $(this).closest('tr').find('select[name=order_fee_unit]')[0];
            var value = $('option:selected', this).val();
            if (parseInt(value) === 2) {
                $(selectOrderUnit).val(0);
                $(selectOrderUnit).prop('disabled', true);
            } else {
                $(selectOrderUnit).prop('disabled', false);
            }
            e.preventDefault();
        })
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
            var checkBox = $(this).find('[name=application_check]')[0];
            if (checkBox && checkBox.checked) {
                obj['application_check'] = checkBox.value;
            }
            var selects = $(this).find('select');
            for (var j = 0; j < selects.length; j++) {
                obj[selects[j].name] = $('option:selected', selects[j]).val();
            }
            var textArea = $(this).find('textarea');
            for (var i = 0; i < textArea.length; i++) {
                obj[textArea[i].name] = textArea[i].value;
            }
            listData.push(obj);
        });
        return {
            'corpId': $('#corp_id').val(),
            'tempId': $('#temp_id').val(),
            'listData': JSON.stringify(listData)
        };
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

    function workFollow() {
        var ppConfirm = initDialogConfirm();
        eventClick(ppConfirm);
    }

    return {
        init: workFollow
    }
}();
