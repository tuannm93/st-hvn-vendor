var ApplicationAdmin = function () {
    return {
        //main function to initiate the module
        init: function () {
            $('.reload').click(function () {
                $("#" + $(this).data("content")).submit();
                setTimeout(function () {
                    location.reload();
                }, 600);
            });
        }
    };
}();

$(document).ready(function () {
    ApplicationAdmin.init();
});