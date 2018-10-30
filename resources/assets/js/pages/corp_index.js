$(document).ready(function(){
    Datetime.initForDateTimepicker();
    Datetime.initForDatepicker();

    var progressBlock = $('.progress-block'),
        progressBar = $('.progress');

    var indexUpdateButtonEl = $('.indexUpdateButton'),
        indexMailButtonEl =  $('.indexMailButton'),
        indexFaxButtonEl = $('.indexFaxButton'),
        indexMailFaxButtonEl = $('.indexMailFaxButton'),
        indexAllMailButtonEl = $('#indexAllMailButton'),
        indexAllFaxButtonEL = $('#indexAllFaxButton'),
        indexAllMailFaxButtonEl = $('#indexAllMailFaxButton'),
        selectAllCheckEl = $("#selectAllCheck"),
        resetButtonEl = $('#resetButton');

    var inputFaxEl = $(".inputFax"),
        displayResultEl = $('#display_result'),
        limitEl = $('#limit'),
        corpSearchFormEl = $("#frmCorpSearch"),
        updateEl = $('.update'),
        outCsvEl = $('.outcsv'),
        outPdfEl = $('.outpdf');

    var alertPopupType = 0,
        confirmPopupType = 1;

    // init popup html
    var initPopupHtml = function (popupType, info) {
        var corpPopup = new popupCommon(popupType, info);
        var popupHtml = corpPopup.renderView();
        return popupHtml;
    };

    var createConfirmPopup = function (msg) {
        var confirmPopup = $(initPopupHtml(confirmPopupType, {close: 'キャンセル', confirm: 'OK', msg: msg}));
        return confirmPopup;
    };

    var createAlertPopup = function (msg) {
        var alertPopup = $(initPopupHtml(alertPopupType, {close: 'OK', msg: msg}));
        return alertPopup;
    };

    var controlPopup = function (popup, isShow) {
        if (isShow) {
            popup.modal('show');
        } else {
            popup.modal('hide')
        }
    };


     var createXHR = function () {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function (evt) {
            var percentComplete = evt.loaded / evt.total;
            progressBar.css({
                width: percentComplete * 100 + "%"
            });
        }, false);
        xhr.addEventListener("progress", function (evt) {
            var percentComplete = evt.loaded / evt.total;
            progressBar.css({
                width: percentComplete * 100 + "%"
            });
        }, false);
        return xhr;
    };

    displayResultEl.on('change', function(){
        limitEl.val($(this).val());
        corpSearchFormEl.submit();
    });

    indexUpdateButtonEl.on('click', function(){

        var corpId = $(this).attr('pCorpId');
        var form = $('#frmPCorp' + corpId );

        var corpName = $('#corp_name' + $(this).attr('pCorpId')).val();

        var $confirmUpdatePopup = createConfirmPopup(corpName + confirm_update_msg);
        controlPopup($confirmUpdatePopup, true);

        $confirmUpdatePopup.find('.st-pp-confirm').one('click', function(e) {
            // close popup
            controlPopup($confirmUpdatePopup, false);

            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                success: function( response ) {
                    window.location.reload();
                }
            });

            return true;
        });

        $confirmUpdatePopup.on('hidden.bs.modal', function (e) {
            $confirmUpdatePopup.remove();
        });

        return false;
    });

    inputFaxEl.blur(function(){
        var val = $(this).val().trim();
        if(val != "" && !val.match(/^[0-9\-\;]+$/)){
            var alertInputFaxPopup = createAlertPopup(faxNumberMsg);
            controlPopup(alertInputFaxPopup, true);
            return false;
        }
    });

    $("input[name='mail_address']").blur(function(){
        var emails = $(this).val().trim().split(';'),
            mailValids = [];

        $.each(emails, function(k, v) {
            if (v != '') {
                if (!v.match(/^[\x20-\x7e]*$/) || !v.match(/@/)) {
                    var alertEmailPopup = createAlertPopup(validateEmail);
                    controlPopup(alertEmailPopup, true);
                    return false;
                }
            }
        });
        return false;
    });

    selectAllCheckEl.click(function(e){
        if($(this).prop("checked") === true){
            $("input.progCheck").each(function(index){
                $(this).prop("checked" , true);
            });
        }else {
            $("input.progCheck").each(function(index){
                $(this).prop("checked" , false);
            });
        }
    });

    resetButtonEl.on('click', function(){
        var form = $(this).parents("form");
        $(form).find('input.param, select.param').val('');
        return false;
    });

    indexMailButtonEl.on('click', function(event){
        var corpId = $(this).attr('pCorpId');
        var emails = $('#mail_address' + $(this).attr('pCorpId')).val().trim(),
            emailChk = emails.split(';'),
            corpName = $('#corp_name' + $(this).attr('pCorpId')).val().trim(),
            valid = true;
        if (emails == "") {
            var alertEmailBlank = createAlertPopup(mailEmptyMsg);
            controlPopup(alertEmailBlank, true);
            valid = false;
            return false;
        }
        $.each(emailChk, function(k, v) {
            if (v != '') {
                if (!v.match(/^[\x20-\x7e]*$/) || !v.match(/@/)) {
                    var alertIndexEmailPopup = createAlertPopup(validateEmail);
                    controlPopup(alertIndexEmailPopup, true);
                    valid = false;
                    return false;
                }
            }
        });
        if (!valid) {
            return false;
        }
        event.preventDefault();
        $(this).prop('disabled', true);

        var $confirmMailButtonPopup = createConfirmPopup(corpName + willSendEmailMsg);
        controlPopup($confirmMailButtonPopup, true);

        $confirmMailButtonPopup.find('.st-pp-confirm').one('click', function(e) {
            // close popup
            controlPopup($confirmMailButtonPopup, false);
            progressBlock.show();
            progressBar.show();
            $.ajax({
                xhr: function () {
                    return createXHR();
                },
                url: $('#indexMailUrl'+ corpId).val(),
                method: 'POST',
                data: {name: corpName, emails: emails}
            }).done(function(data){
                $(window).scrollTop(0);
            }).always(function () { window.location.reload() });
            return false;
        });

        $confirmMailButtonPopup.find('.st-pp-close').one('click', function(e) {
            indexMailButtonEl.prop('disabled', false);
        });

        $confirmMailButtonPopup.on('hidden.bs.modal', function (e) {
            $confirmMailButtonPopup.remove();
        });

        return false;
    });

    indexFaxButtonEl.on('click', function(event){
        var corpId = $(this).attr('pCorpId');
        var faxVal = $('#fax' + $(this).attr('pCorpId')).val().trim();
        if (faxVal == "") {
            var alertIndexFaxPopup = createAlertPopup(faxEmptyMsg);
            controlPopup(alertIndexFaxPopup, true);
            return false;
        }
        else {
            if(!faxVal.match(/^[0-9\-\;]+$/)){
                var alertIndexFaxPopup = createAlertPopup(faxNumberMsg);
                controlPopup(alertIndexFaxPopup, true);
                return false;
            }
        }
        event.preventDefault();
        var faxs = faxVal;
        var corpName = $('#corp_name' + $(this).attr('pCorpId')).val();
        $(this).prop('disabled', true);

        var $confirmFaxButtonPopup = createConfirmPopup(corpName + willSendFaxMsg);
        controlPopup($confirmFaxButtonPopup, true);

        $confirmFaxButtonPopup.find('.st-pp-confirm').one('click', function(e) {
            // close popup
            controlPopup($confirmFaxButtonPopup, false);

            progressBlock.show();
            progressBar.show();
            $.ajax({
                xhr: function () {
                    return createXHR();
                },
                url:$('#indexFaxButton'+ corpId).val(),
                method: 'POST',
                data: {name: corpName, faxs: faxs}
            }).done(function(data){
                $(window).scrollTop(0);
                $(indexFaxButtonEl).prop('disabled', false);
            }).always(function () { window.location.reload() });

            return false;
        });

        $confirmFaxButtonPopup.find('.st-pp-close').one('click', function(e) {
            indexFaxButtonEl.prop('disabled', false);
        });

        $confirmFaxButtonPopup.on('hidden.bs.modal', function (e) {
            $confirmFaxButtonPopup.remove();
        });

        return false;
    });

    indexMailFaxButtonEl.on('click', function(event){
        var corpId = $(this).attr('pCorpId');
        var emails = $('#mail_address' + $(this).attr('pCorpId')).val().trim(),
            corpName = $('#corp_name' + $(this).attr('pCorpId')).val().trim(),
            faxs = $('#fax' + $(this).attr('pCorpId')).val().trim(),
            emailChk = emails.split(';')

        if(emails == ""){
            var alertIndexMailFaxPopup = createAlertPopup(mailEmptyMsg);
            controlPopup(alertIndexMailFaxPopup, true);
            return false;
        }
        if(faxs == ""){
            var alertIndexMailFaxPopup = createAlertPopup(faxEmptyMsg);
            controlPopup(alertIndexMailFaxPopup, true);
            return false;
        }
        var valid = true;
        $.each(emailChk, function(k, v) {
            if (v != '') {
                if (!v.match(/^[\x20-\x7e]*$/) || !v.match(/@/)) {
                    var alertIndexMailsPopup = createAlertPopup(validateEmail);
                    controlPopup(alertIndexMailsPopup, true);
                    valid = false;
                    return false;
                }
            }
        });
        if(!faxs.match(/^[0-9\-\;]+$/)){
            var alertIndexFaxsPopup = createAlertPopup(faxNumberMsg);
            controlPopup(alertIndexFaxsPopup, true);
            return false;
        }
        if (!valid) {
            return false;
        }
        event.preventDefault();
        $(this).prop('disabled', true);

        var $confirmMailFaxButtonPopup = createConfirmPopup(corpName + willSendFaxMail);
        controlPopup($confirmMailFaxButtonPopup, true);

        $confirmMailFaxButtonPopup.find('.st-pp-confirm').one('click', function(e) {
            // close popup
            controlPopup($confirmMailFaxButtonPopup, false);

            progressBlock.show();
            progressBar.show();
            $.ajax({
                xhr: function () {
                    return createXHR();
                },
                url:$('#indexMailFaxButton'+ corpId).val(),
                method: 'POST',
                data: {name: corpName, faxs: faxs, emails: emails}
            }).done(function(data){
                $(window).scrollTop(0);
            }).always(function () { window.location.reload() });

            return false;
        });

        $confirmMailFaxButtonPopup.find('.st-pp-close').one('click', function(e) {
            indexMailFaxButtonEl.prop('disabled', false);
        });

        $confirmMailFaxButtonPopup.on('hidden.bs.modal', function (e) {
            $confirmMailFaxButtonPopup.remove();
        });

        return false;
    });

    indexAllMailButtonEl.on('click', function(event){
        event.preventDefault();
        var corpId = $(this).attr('pCorpId');
        var dataMail = [];
        if ($(".progCheck:checked").length == 0) {
            var indexAllMailButtonPopup = createAlertPopup(noChecked);
            controlPopup(indexAllMailButtonPopup, true);
            return false;
        }
        else {
            var rtn = true;
            var valid = true;
            $(this).prop('disabled', true);
            $(".progCheck:checked").each(function(){
                var emails = $('#mail_address' + $(this).attr('pcorpid')).val().trim(),
                    emailChk = emails.split(';'),
                    corpName = $('#corp_name' + $(this).attr('pcorpid')).val().trim(),
                    pCorpId = $(this).attr('pCorpId');

                var tmp = {};
                if (emails == "") {
                    var progCheckPopup = createAlertPopup(corpName + emailNotEnter);
                    controlPopup(progCheckPopup, true);
                    rtn = false
                    return false;
                }
                else {
                    $.each(emailChk, function(k, v) {
                        if (v != '') {
                            if (!v.match(/^[\x20-\x7e]*$/) || !v.match(/@/)) {
                                valid = false;
                                return false;
                            }
                        }
                    });
                    if (valid) {
                        tmp.emails = emails;
                        tmp.name = corpName;
                        tmp.pCorpId = pCorpId;
                        dataMail.push(tmp);
                    }
                    else {
                        $(this).prop('disabled', true);
                        var progCheckPopup = createAlertPopup(corpName + validateEmail);
                        controlPopup(progCheckPopup, true);
                        return false;
                    }
                }
            });
            if(!rtn || !valid){
                $(this).prop('disabled', false);
                return false;
            }

            var $confirmAllMailButtonPopup = createConfirmPopup(sendBulkMail);
            controlPopup($confirmAllMailButtonPopup, true);

            $confirmAllMailButtonPopup.find('.st-pp-confirm').one('click', function(e) {
                // close popup
                controlPopup($confirmAllMailButtonPopup, false);

                progressBlock.show();
                progressBar.show();
                $.ajax({
                    xhr: function () {
                        return createXHR();
                    },
                    url: $('#hidIndexAllUrl').val(),
                    method: 'POST',
                    data: {data: dataMail}
                }).done(function(data){
                    $(window).scrollTop(0);
                }).always(function () { window.location.reload(); });

                return false;
            });

            $confirmAllMailButtonPopup.find('.st-pp-close').one('click', function(e) {
                indexAllMailButtonEl.prop('disabled', false);
            });

            $confirmAllMailButtonPopup.on('hidden.bs.modal', function (e) {
                $confirmAllMailButtonPopup.remove();
            });

            return false;
        }
    });

    indexAllFaxButtonEL.on('click', function(event){
        event.preventDefault();
        var dataFax = [];
        if ($(".progCheck:checked").length == 0) {
            var indexAllMailButtonPopup = createAlertPopup(noChecked);
            controlPopup(indexAllMailButtonPopup, true);
            return false;
        }
        else {
            var rtn = true,
                valid = true;
            $(this).prop('disabled', true);
            $(".progCheck:checked").each(function(){
                var faxs = $('#fax' + $(this).attr('pcorpid')).val().trim(),
                    corpName = $('#corp_name' + $(this).attr('pcorpid')).val().trim(),
                    pCorpId = $(this).attr('pCorpId');
                var tmp = {};
                if (faxs == "") {
                    var progCheckPopup = createAlertPopup(corpName + faxNotEnter);
                    controlPopup(progCheckPopup, true);
                    rtn = false
                    return false;
                }
                else {
                    if (!faxs.match(/^[0-9\-\;]+$/)) {
                        valid = false;
                        var progCheckPopup = createAlertPopup(corpName + faxNumberMsg);
                        controlPopup(progCheckPopup, true);
                    }
                    else {
                        tmp.faxs = faxs;
                        tmp.name = corpName;
                        tmp.pCorpId = pCorpId;
                        dataFax.push(tmp);
                    }
                }
            });
            if(!rtn || !valid){
                $(this).prop('disabled', false);
                return false;
            }

            var $confirmAllFaxButtonPopup = createConfirmPopup(sendBulkFax);
            controlPopup($confirmAllFaxButtonPopup, true);

            $confirmAllFaxButtonPopup.find('.st-pp-confirm').one('click', function(e) {
                // close popup
                controlPopup($confirmAllFaxButtonPopup, false);

                progressBlock.show();
                progressBar.show();
                $.ajax({
                    xhr: function () {
                        return createXHR();
                    },
                    url: $('#hidIndexAllFaxUrl').val(),
                    method: 'POST',
                    data: {data: dataFax}
                }).done(function(data){
                    $(window).scrollTop(0);
                }).always(function () { window.location.reload(); });

                return false;
            });
            $confirmAllFaxButtonPopup.find('.st-pp-close').one('click', function(e) {
                indexAllFaxButtonEL.prop('disabled', false);
            });

            $confirmAllFaxButtonPopup.on('hidden.bs.modal', function (e) {
                $confirmAllFaxButtonPopup.remove();
            });

            return false;
        }
    });

    indexAllMailFaxButtonEl.on('click', function(){
        event.preventDefault();
        var dataFax = [];
        var dataMail = [];
        if ($(".progCheck:checked").length == 0) {
            var progCheckPopup = createAlertPopup(noChecked);
            controlPopup(progCheckPopup, true);
            return false;
        }
        else {
            $(this).prop('disabled', true);
            var rtn = true,
                valid = true;
            $(".progCheck:checked").each(function(){
                var emails = $('#mail_address' + $(this).attr('pcorpid')).val().trim(),
                    emailChk = emails.split(';'),
                    corpName = $('#corp_name' + $(this).attr('pcorpid')).val().trim(),
                    pCorpId = $(this).attr('pCorpId'),
                    faxs = $('#fax' + $(this).attr('pcorpid')).val().trim();

                if(emails == ""){
                    var progCheckPopup = createAlertPopup(corpName + emailNotEnter);
                    controlPopup(progCheckPopup, true);
                    rtn = false
                    return false;
                }
                else {
                    $.each(emailChk, function(k, v) {
                        if (!v.match(/^[\x20-\x7e]*$/) || !v.match(/@/)) {
                            valid = false;
                            var progCheckPopup = createAlertPopup(corpName + validateEmail);
                            controlPopup(progCheckPopup, true);
                            return false;
                        }
                    });
                    if(valid) {
                        var tmp = {};
                        tmp.emails = emails;
                        tmp.name = corpName;
                        tmp.pCorpId = pCorpId;
                        dataMail.push(tmp);
                    }
                    else {
                        return false;
                    }
                }
                if (faxs == "") {
                    var faxPopup = createAlertPopup(corpName + faxNotEnter);
                    controlPopup(faxPopup, true);
                    rtn = false
                    return false;
                }
                else {
                    var tmp = {};
                     if (!faxs.match(/^[0-9\-\;]+$/)) {
                        valid = false;
                         var faxsPopup = createAlertPopup(corpName + faxNumberMsg);
                         controlPopup(faxsPopup, true);
                        return false;
                    }
                    else {
                        tmp.faxs = faxs;
                        tmp.name = corpName;
                        tmp.pCorpId = pCorpId;
                        dataFax.push(tmp);
                    }
                }
            });
            if(!rtn || !valid){
                $(this).prop('disabled', false);
                return false;
            }

            var $confirmAllMailFaxButtonPopup = createConfirmPopup(bulkMailFax);
            controlPopup($confirmAllMailFaxButtonPopup, true);

            $confirmAllMailFaxButtonPopup.find('.st-pp-confirm').one('click', function(e) {
                // close popup
                controlPopup($confirmAllMailFaxButtonPopup, false);

                progressBlock.show();
                progressBar.show();
                $.ajax({
                    xhr: function () {
                        return createXHR();
                    },
                    url:$('#hidIndexAllMailFaxUrl').val(),
                    method: 'POST',
                    data: {faxs: dataFax, mails: dataMail}
                }).done(function(data){
                    $(window).scrollTop(0);
                }).always(function () { window.location.reload(); });

                return false;
            });
            $confirmAllMailFaxButtonPopup.find('.st-pp-close').one('click', function(e) {
                indexAllMailFaxButtonEl.prop('disabled', false);
            });

            $confirmAllMailFaxButtonPopup.on('hidden.bs.modal', function (e) {
                $confirmAllMailFaxButtonPopup.remove();
            });

            return false;
        }
    });

    updateEl.on('change', function(){
        $('#btn_update' + $(this).attr('pCorpId')).removeClass('btn--gradient-orange');
        $('#btn_update' + $(this).attr('pCorpId')).addClass('btn--gradient-green');
    });

    outCsvEl.on('click', function(event){
        event.preventDefault();
        var url = $('#hidOutcsvUrl' + $(this).attr('pCorpId')).val();
        window.open(url);
    });

    outPdfEl.on('click', function(){
        event.preventDefault();
        var url = $('#hidOutPdfUrl' + $(this).attr('pCorpId')).val();
        window.open(url);
    });
});
