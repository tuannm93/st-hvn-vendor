var Addition = function () {

    return {
        //main function to initiate the module
        init: function () {
            $(".btn-del").click(function() {
                var additionId = $(this).attr('data-id');
                $("#addition_id").val(additionId);
            });

        }

    };

}();

$(document).ready(function() {
    Addition.init();
    Datetime.initForDatepickerLimit();
    FormUtil.validate('#form-addition');
});
