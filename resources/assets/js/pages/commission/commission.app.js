var CommissionApp = function() {
    function errorCheckClose(id) {
        $(id).modal('hide');
    }

    function errorCheckOpen(id) {
        $(id).modal('show');
    }

    function applicationValueChange() {
        $('input.irc').change(function() {
            var check_count = $('input.irc:checked').length;

            if(check_count>0){
                $('#ir').prop('disabled', false);
            }else{
                $('#ir').prop('disabled', true);
            }
        });

        $('#chk1').change(function() {
            if ($(this).is(':checked')) {
                $('#deduction_tax_include').removeAttr('disabled').focus();
            } else {
                $('#deduction_tax_include').attr('disabled','disabled');
            }
        });

        $('#chk2').change(function() {
            if ($(this).is(':checked')) {
                $('#irregular_fee_rate').removeAttr('disabled').focus();
            } else {
                $('#irregular_fee_rate').attr('disabled','disabled');
            }
        });

        $('#chk3').change(function() {
            if ($(this).is(':checked')) {
                $('#irregular_fee').removeAttr('disabled').focus();
            } else {
                $('#irregular_fee').attr('disabled','disabled');
            }
        });

        $('#chk4').change(function() {
            if ($(this).is(':checked')) {
                $('#introduction_free').removeAttr('disabled').focus();
            } else {
                $('#introduction_free').attr('disabled','disabled');
            }
        });

        $('#chk5').change(function() {
            if ($(this).is(':checked')) {
                $('#ac_commission_exclusion_flg').removeAttr('disabled').focus();
            } else {
                $('#ac_commission_exclusion_flg').attr('disabled','disabled');
            }
        });

        $('#chk6').change(function() {
            if ($(this).is(':checked')) {
                $('#introduction_not').removeAttr('disabled').focus();
            } else {
                $('#introduction_not').attr('disabled','disabled');
            }
        });
    }

    function submitApplication(event) {
        if ($('#application_dialog textarea#application_reason').val() != '') {
            event.preventDefault();

            var data = $('#application_dialog').find('select, textarea, input').serialize();
            $.ajax({
                url: '/commission/application',
                type: 'POST',
                data: data,
                success: function (res) {
                    location.reload();
                },
                error: function (xhr, status, error) {}
            });

            return false;
        } else {
            $('#application_dialog textarea#application_reason').focus();
        }
    }

    /**
     * Set function
     */
    function init() {
        applicationValueChange();

        $('#approval_app_btn').click(function() {
            errorCheckOpen('#application_dialog');
        });

        $('#application_close').click(function() {
            errorCheckClose('#application_dialog');
        });

        $('#btn_back').on('click', function (event) {
            if (smartDevice.checkMobile()) {
                event.preventDefault();
                window.history.back();
            }
        })

        $('#application_submit').click(function(event) {
            $('#application_dialog textarea#application_reason').attr('required', true);
            submitApplication(event);
        });

        $('#regist').click(function(event) {
            $('#application_dialog textarea#application_reason').attr('required', false);
        });

        $('button.app_sm').click(function(event) {
            var id = $(this).attr('id');
            var name = $(this).attr('name');
            $('form#approval_form #id').val(id);
            $('form#approval_form #action').val(name);
            $('form#approval_form').submit();
        });
    }

    return {
        init: init
    }
}();

$(document).ready(function () {
    CommissionApp.init();
});
