var StepConfirmAgreementSystem = function () {
    function eventBackButton() {
        $('#back_button').on('click', function () {
            window.location.href = urlBackConfirm;
        });
    }

    function acceptedCheckBoxInit() {
        $("#acceptedCheck").change(function () {
            if ($('#acceptedCheck').prop('checked')) {
                $('#btnApplicationId').removeClass('btn--gradient-default').addClass('btn--gradient-green');
            } else {
                $('#btnApplicationId').removeClass('btn--gradient-green').addClass('btn--gradient-default');
            }
        });
    }

    function btnApplicationInit() {
        $('#btnApplicationId').on('click', function () {
            if (!$('#acceptedCheck').prop('checked')) {
                alert(alertConfirmAgreement);
                return;
            }
            $('#confirmFormId').submit();
        });
    }

    function init() {
        eventBackButton();
        acceptedCheckBoxInit();
        btnApplicationInit();
    }

    return {
        init: init
    }
}();
