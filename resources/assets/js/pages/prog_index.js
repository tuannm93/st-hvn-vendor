$(document).ready(function(){
    var confirmPopupType = 1;
    var initPopupHtml = function (popupType, info) {
        var corpPopup = new popupCommon(popupType, info);
        var popupHtml = corpPopup.renderView();
        return popupHtml;
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
        return false;
    };

    $('.submitDeleteFile').on('click', function(event){
        let el = $(this);
        var $confirmDeletePopup = createConfirmPopup(confirmDelete);
        var submit = false;
        controlPopup($confirmDeletePopup, true);
        $confirmDeletePopup.find('.st-pp-confirm').one('click', function(e) {
            // close popup
            submit = true;
            controlPopup($confirmDeletePopup, false);
            let form = el.parent();
            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                method: 'POST'
            }).done(function(){
                window.location.reload();
            });

        });
        return false;
    });
});