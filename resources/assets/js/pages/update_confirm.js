var UpdateConfirm = function () {
    var init = function () {
        $(document).on('click', '#back', function (e) {
            var url = $(this).data("url");
            window.location.href = url;
        });
    };

    return {
        init: init
    };
}();
jQuery(document).ready(function () {
    UpdateConfirm.init();
});
