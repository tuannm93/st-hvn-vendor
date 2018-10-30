var DeleteCommissionInfos = function () {

    var confirmPopup = new popupCommon(1, {msg: msg, confirm: confirm, close: cancel});
    var confirmPopupHtml = confirmPopup.renderView();
    var $ppConfirm = $(confirmPopupHtml);

    function deleteConfirm() {
        $('#deleteButton').click(function () {
            $ppConfirm.modal('show');
        });

        $ppConfirm.find('.st-pp-confirm').on('click', function(e) {
            $('#delete_commission_infos').submit();
        });
    }

    function init() {
        deleteConfirm();
    }

    return {
        //main function to initiate the module
        init: init
    };
}();

$(document).ready(function() {
    DeleteCommissionInfos.init();
});