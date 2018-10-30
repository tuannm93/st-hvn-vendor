var ImportCommissionInfo = function () {

    var $pageData = $('#page-data'),
        $noticeForm = $('#commission_info_form'),
        $btnImport = $('#btn-import'),
        alertPopupType = 0,
        confirmPopupType = 1;
    // init popup html
    var initPopupHtml = function (popupType, info) {
        var corpPopup = new popupCommon(popupType, info);
        var popupHtml = corpPopup.renderView();
        return popupHtml;
    };

    var createAlertPopup = function (msg) {
        var alertPopup = $(initPopupHtml(alertPopupType, {close: 'キャンセル', msg: msg}));
        return alertPopup;
    };

    var createConfirmPopup = function (msg) {
        var confirmPopup = $(initPopupHtml(confirmPopupType, {close: 'キャンセル', confirm: 'OK', msg: msg}));
        return confirmPopup;
    };

    var controlPopup = function (popup, isShow) {
        if (isShow) {
            popup.modal('show');
        } else {
            popup.modal('hide')
        }
    };


    var init = function () {
        $btnImport.on('click', function () {
            var msgConfirm = $pageData.data('confirm-import');
            var $confirmPopup = createConfirmPopup(msgConfirm);
            controlPopup($confirmPopup, true);
            $confirmPopup.find('.st-pp-confirm').one('click', function () {
                controlPopup($confirmPopup, false);
                $noticeForm.submit();
                return true;
            });

            $confirmPopup.on('hidden.bs.modal', function () {
                $confirmPopup.remove();
            });
            return false;
        });
    };

    return {
        init: init
    }
}();
