var Global = function () {
    function initGlobalAntisocialFollowDialogModal() {
        var globalAntisocialFollowDialogModal = $('#globalAntisocialFollowDialogModal');
        if(globalAntisocialFollowDialogModal.length){
            globalAntisocialFollowDialogModal.modal();
            globalAntisocialFollowDialogModal.on('hidden.bs.modal', function (e) {
                initGlobalAgreementConfirmModal();
            });
        } else {
            initGlobalAgreementConfirmModal();
        }
    }

    function initGlobalAgreementConfirmModal() {
        var globalAgreementConfirmModal = $('#globalAgreementConfirmModal');
        if(globalAgreementConfirmModal.length){
            globalAgreementConfirmModal.modal();
            globalAgreementConfirmModal.on('hidden.bs.modal', function (e) {
                initGlobalProgDialogModal();
            })
        } else {
            initGlobalProgDialogModal();
        }
    }

    function initGlobalProgDialogModal() {
        var globalProgDialogModal = $('#globalProgDialogModal');
        if(globalProgDialogModal.length){
            globalProgDialogModal.modal();
            globalProgDialogModal.on('hidden.bs.modal', function (e) {
                initGlobalUnreadNoticeModal();
            })
        } else {
            initGlobalUnreadNoticeModal();
        }
    }

    function initGlobalUnreadNoticeModal() {
        var globalUnreadNoticeModal = $('#globalUnreadNoticeModal');
        if(globalUnreadNoticeModal.length){
            globalUnreadNoticeModal.modal();
            globalUnreadNoticeModal.on('hidden.bs.modal', function (e) {
                initGlobalCreditAlertDialogModal();
            })
        } else {
            initGlobalCreditAlertDialogModal();
        }
    }

    function initGlobalCreditAlertDialogModal() {
        var globalCreditAlertDialogModal = $('#globalCreditAlertDialogModal');
        if(globalCreditAlertDialogModal.length){
            globalCreditAlertDialogModal.modal();
        }
    }

    return {
        init: function () {
            initGlobalAntisocialFollowDialogModal();
        }
    };
}();

jQuery(document).ready(function () {
    Global.init()
});