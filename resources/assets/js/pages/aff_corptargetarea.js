var AffCorptargetarea = function() {
    var progress = new progressCommon();
    var count_click_collapse = 0;

    function modalInit() {
        $('#address1').change(function() {
            val_check();
        });

        $('#all_check').click(function() {
            $(".check_group").prop('checked', true);
        });

        $('#all_release').click(function() {
            $(".check_group").prop('checked', false);
        });
        $('#all_regist').click(function() {
            myRet = confirm(" エリアが一括で登録されます。よろしいですか？");
            if (myRet == true) {
                myRet = confirm("本当によろしいですか？");
                if (myRet == false) {
                    return false;
                }
            } else {
                return false;
            }
        });

        $('#all_remove').click(function() {
            myRet = confirm(" エリアが一括で削除されます。よろしいですか？");
            if (myRet == false) {
                return false;
            }
        });

        function val_check() {
            var txt = $('[id=address1] option:selected').text();
            var address = $("#address1").val();
            if (address != '') {
                $('#address1_text').val(txt);

                $.ajaxSetup({
                    cache: false,
                });

                var url = '<?php echo url("ajax/searchCorpTargetArea/");?>' + "/" + "<?php echo $id;?>/" + txt;

                $.get(url, function(data) {
                    $("#display_area").html(data);
                    $("#message").html('');
                });
            } else {
                $("#display_area").html('');
            }
        }
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
        if (jQuery('#init_pref').length > 0) {
            var name = jQuery('#init_pref').attr('data-name');
            var url = jQuery('#init_pref').attr('data-url');
            val_check_button(name, url);
        }
        jQuery('.val_check_button').click(function() {
            var name = jQuery(this).attr('data-name');
            var url = jQuery(this).attr('data-url');
            val_check_button(name, url);
        });
        $('.collapse-label-mobi').click(function() {
            count_click_collapse++;
            if (count_click_collapse > 1) {
                return false;
            };
            if ($(this).children().hasClass('fa-chevron-down')) {
                $(this).find('.fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            } else if($(this).children().hasClass('fa-chevron-up')) {
                $(this).find('.fa-chevron-up').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
            setTimeout(function() {
                count_click_collapse = 0;
            }, 500);
        })
    }
    return {
        init: init
    }
}();
$(document).ready(function() {
    AffCorptargetarea.init();
});