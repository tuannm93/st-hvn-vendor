var AffTargetArea = function() {
    var progress = new progressCommon();

    function modalInit() {
        $('#all_check').click(function() {
            $(".check_group").prop('checked', true);
        });

        $('#all_release').click(function() {
            $(".check_group").prop('checked', false);
        });
    };

    function val_check_button(txt, urlAjax) {
        if (txt != '') {
            $('#address1_text').val(txt);

            $.ajaxSetup({
                cache: false,
            });

            var url = urlAjax;
            $.ajax({
                type: "GET",
                url: url,
                xhr: function() {
                    return progress.createXHR();
                },
                beforeSend: function(xhr) {
                    progress.controlProgress(true);
                },
                complete: function() {
                    progress.controlProgress(false);
                },
                success: function(data) {
                    $("#display_modal_area").html(data);
                    $('#area_check').modal('show');
                    modalInit();
                },
                error: function() {
                    console.log("Error!");
                }
            });
        } else {
            $("#display_modal_area").html('');
        }
    }

    function init() {
        jQuery('.val_check_button').click(function() {
            var name = jQuery(this).attr('data-name');
            var url = jQuery(this).attr('data-url');
            val_check_button(name, url);
        });
    }
    return {
        init: init
    }
}();
$(document).ready(function() {
    AffTargetArea.init();
});