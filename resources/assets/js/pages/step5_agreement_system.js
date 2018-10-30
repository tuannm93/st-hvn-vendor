var Step5AgreementSystem = function () {

    var $backButton = $('#back_button'),
        $fileUploadEl = $('#fileUpload'),
        $fileDeleteButton = $('.fileDelete'),
        $agreementSystemStep5El = $('#agreementSystemStep5'),
        $agreementAttachedFileIdEl = $("#agreementAttachedFileId");

    function eventBackButton() {
        $backButton.on('click', function () {
            window.location.href = agreementSystem.urlBackStep5;
        });
    }

    function uploadTemp() {
        $fileUploadEl.on('change', function () {
            $('#submit_step5').attr("disabled", true);
            $agreementSystemStep5El.attr("action", agreementSystem.urlUploadFile);
            $agreementSystemStep5El.submit();
        });
    }

    function deleteFile() {
        $fileDeleteButton.on('click', function () {
            var confirmPopup = new popupCommon(1, {msg: agreementSystem.confirmDeleteFile, close: NO, confirm: YES});
            var confirmPopupHtml = confirmPopup.renderView();
            var $ppConfirm = $(confirmPopupHtml);
            var fileId = $(this).data('file_id');
            $ppConfirm.modal('show');
            $ppConfirm.find('.st-pp-confirm').one('click', function () {
                $agreementSystemStep5El.attr("action", agreementSystem.urlDeleteFile);
                $agreementAttachedFileIdEl.val(fileId);
                $agreementSystemStep5El.submit();
                $ppConfirm.modal('hide');
            });
        });
    }

    var init = function () {
        eventBackButton();
        uploadTemp();
        deleteFile();
    };

    return {
        init: init
    }

}();
