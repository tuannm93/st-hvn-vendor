var CommissionOnChangeDetail = function() {

    function init() {
        // Incompletion cancellation is canceled once the order acceptance completion date and time is input
        // 施工完了日が変更された時
        $(document).on('change, focus, blur', '#order_completion_datetime', function () {
            var self = $(this);
            if (self.val() != '') {

                $('#order_construction_price_tax_exclude').prop('disabled', false);
                $('#order_construction_price_tax_include').prop('disabled', false);

                $.ajaxSetup({
                    cache: false,
                });

                var datePart = self.val().split('/');
                var formattedValue = datePart[0] + '/' + toDoubleDigits(datePart[1]) + '/' + toDoubleDigits(datePart[2]);
                self.val(formattedValue);
                var url = '/ajax/search_tax_rate_only/' + formattedValue.split('/').join('-').substr(0, 10);

                $.get(url, function (data) {
                    var tax_rate = data;
                    var order_construction_price_tax_exclude = $('#order_construction_price_tax_exclude').val();

                    if (order_construction_price_tax_exclude != 0 && String(tax_rate).match(/^[0-9]+$/) && order_construction_price_tax_exclude.match(/^[0-9]+$/)) {
                        var tax_rate_val = Number(tax_rate) * 0.01;
                        var order_construction_price_tax_include = Math.round(Number(order_construction_price_tax_exclude) * (1 + Number(tax_rate_val)));
                        if($('#user_auth').val() == 'affiliation') {
                            $('#order_construction_price_tax_include_display').html(order_construction_price_tax_include);
                            $('#order_construction_price_tax_include').val(order_construction_price_tax_include);
                        } else {
                            $('#order_construction_price_tax_include').val(order_construction_price_tax_include);
                        }
                    }
                });
            }
        });

        // 施工金額(税抜)が変更された時
        $('#order_construction_price_tax_exclude').change(function () {

            // 受注対応日時のセット
            $('#complete_date').val($('#order_completion_datetime').val().substr(0, 10));
            $.ajaxSetup({
                cache: false,
            });

            var datePart = $('#order_completion_datetime').val().split('/');
            var formattedValue = datePart[0] + '/' + toDoubleDigits(datePart[1]) + '/' + toDoubleDigits(datePart[2]);
            var url = '/ajax/search_tax_rate_only/' + formattedValue.split('/').join('-').substr(0, 10);

            $.get(url, function (data) {
                var tax_rate = data;
                var order_construction_price_tax_exclude = $('#order_construction_price_tax_exclude').val();

                if (order_construction_price_tax_exclude != 0 && String(tax_rate).match(/^[0-9]+$/) && order_construction_price_tax_exclude.match(/^[0-9]+$/)) {
                    var tax_rate_val = Number(tax_rate) * 0.01;
                    var order_construction_price_tax_include = Math.round(Number(order_construction_price_tax_exclude) * (1 + Number(tax_rate_val)));

                    if($('#user_auth').val() == 'affiliation') {
                        $('#order_construction_price_tax_include_display').html(order_construction_price_tax_include);
                        $('#order_construction_price_tax_include').val(order_construction_price_tax_include);
                    } else {
                        $('#order_construction_price_tax_include').val(order_construction_price_tax_include);
                    }
                }
            });
        });

        $('#complete_date').change(function () {
            if ($(this).val() != '') {
                $.ajaxSetup({
                    cache: false,
                });

                var datePart = $(this).val().split('/');
                var formattedValue = datePart[0] + '/' + toDoubleDigits(datePart[1]) + '/' + toDoubleDigits(datePart[2]);
                var url = '/ajax/search_tax_rate/' + formattedValue.split('/').join('-');

                $.get(url, function (data) {
                    $('#tax_rate_list').html(data);
                    val_check();
                });
            }
        });

        /*$('#regist').mousedown(function () {
            $.ajaxSetup({
                cache: false,
            });

            var datePart = $('#complete_date').val().split('/');
            var formattedValue = datePart[0] + '/' + toDoubleDigits(datePart[1]) + '/' + toDoubleDigits(datePart[2]);
            var url = '/ajax/search_tax_rate/' + formattedValue.split('/').join('-');

            $.get(url, function (data) {
                $('#tax_rate_list').html(data);
                val_check();
            });
        });*/

        commission_status_change(0);

        if ($('#commission_type_check').val() == 1) {
            $('#commission_status option').each(function () {
                if ($(this).text() == '紹介済') {
                    $(this).remove();
                }
            });
        }

        $('#commission_status').change(function() {
            commission_status_change(1);
        });

        // Changing phone correspondence status
        $('#tel_correspond_status').change(function(){
            if ($('#tel_correspond_status').val() != $('#tel_correspond_status_div_value').val()){
                $('#tel_order_fail_reason').attr('disabled', 'disabled');
            } else {
                $('#tel_order_fail_reason').removeAttr('disabled');
            }
        });
        // Changing the status of visiting correspondence
        $('#visit_correspond_status').change(function(){
            if ($('#visit_correspond_status').val() != $('#visit_correspond_status_div_value').val()){
                $('#visit_order_fail_reason').attr('disabled', 'disabled');
            } else {
                $('#visit_order_fail_reason').removeAttr('disabled');
            }
        });
        // Changing status of order acceptance
        $('#order_correspond_status').change(function(){
            if ($('#order_correspond_status').val() != $('#order_correspond_status_div_value').val()){
                $('#order_order_fail_reason').attr('disabled', 'disabled');
            } else {
                $('#order_order_fail_reason').removeAttr('disabled');
            }
        });

        // Telephone support status (initial display)
        if ($('#tel_correspond_status').val() != $('#tel_correspond_status_div_value').val()){
            $('#tel_order_fail_reason').attr('disabled', 'disabled');
        } else {
            $('#tel_order_fail_reason').removeAttr('disabled');
        }
        // Visit compliance status (initial display)
        if ($('#visit_correspond_status').val() != $('#visit_correspond_status_div_value').val()){
            $('#visit_order_fail_reason').attr('disabled', 'disabled');
        } else {
            $('#visit_order_fail_reason').removeAttr('disabled');
        }
        // Status of order acceptance (initial display)
        if ($('#order_correspond_status').val() != $('#order_correspond_status_div_value').val()){
            $('#order_order_fail_reason').attr('disabled', 'disabled');
        } else {
            $('#order_order_fail_reason').removeAttr('disabled');
        }

        $('input[type="file"]').on('change', function(e){
            e.preventDefault();
            let fileName = $(this).val().toString().split("\\").pop();
            $(this).next().next().html(fileName);
        });

        $('#progress_reported_check_cancel').click(function () {
            $('#commission_status').val($('#commission_status_before').val());
            setDisabled(1);
            $('#progress_reported_check').modal('hide');
        });

        $('#progress_reported_check_ok').click(function () {
            if ($('#user_auth').val() != 'affiliation') {
                $("#progress_reported").prop({'checked': false});
            } else {
                $("#progress_reported").val("0");
            }
            $("#progress_report_datetime").val("");

            setDisabled(1);
            $('#progress_reported_check').modal('hide');
        });
    }

    function commission_status_change(disable_flg)
    {
        $('#hidden_last_updated').val(1);
        var before_commission_status = $('#commission_status_before').val();

        if ((before_commission_status == $('#construction_div_value').val()
                && $('#commission_status').val() != before_commission_status)
            || (before_commission_status == $('#order_fail_div_value').val()
                && $('#commission_status').val() != before_commission_status)) {
            // Confirmation screen display
            if ($('#user_auth').val() != 'affiliation') {
                if ($('#progress_reported').prop('checked')) {
                    // Dialog display for progress table collection flag
                    $('#progress_reported_check').modal('show');
                    return;
                }
            } else {
                if ($('#progress_reported').val() == '1') {
                    // Dialog display for progress table collection flag
                    $('#progress_reported_check').modal('show');
                    return;
                }
            }
        }

        setDisabled(disable_flg);
    }

    function setDisabled(disable_flg) {
        if (disable_flg == 1) {
            // After displaying the screen, when changing the ordering situation, control items.
            switch ($('#commission_status').val()) {
                case '1' : 
                    // processing
                    $('#complete_date').prop('disabled', true);
                    $('#order_fail_date').prop('disabled', true);
                    $('#commission_order_fail_reason').prop('disabled', true);
                    $('#construction_price_tax_exclude').prop('disabled', true);
                    $('#complete_date').val('').removeClass('is-invalid').siblings('.invalid-feedback').remove();
                    $('#order_fail_date').val('').removeClass('is-invalid').siblings('.invalid-feedback').remove();
                    $('#construction_price_tax_exclude').val('').removeClass('is-invalid').siblings('.invalid-feedback').remove();
                    $('#construction_price_tax_include').val('');
                    $('#construction_price_tax_include').parent().parent().removeClass('is-invalid').siblings('.invalid-feedback').remove();
                    $('#commission_order_fail_reason').val('');
                    break;
                case '2' :        // Orders received
                    $('#complete_date').prop('disabled', true);
                    $('#order_fail_date').prop('disabled', true);
                    $('#commission_order_fail_reason').prop('disabled', true);
                    $('#construction_price_tax_exclude').prop('disabled', true);
                    $('#complete_date').val('').removeClass('is-invalid').siblings('.invalid-feedback').remove();
                    $('#order_fail_date').val('').removeClass('is-invalid').siblings('.invalid-feedback').remove();
                    $('#commission_order_fail_reason').val('');
                    $('#construction_price_tax_exclude').val('');
                    break;
                case '3' :        // Finished construction
                    $('#complete_date').prop('disabled', false);
                    $('#order_fail_date').prop('disabled', true).val('').removeClass('is-invalid').siblings('.invalid-feedback').remove();
                    $('#commission_order_fail_reason').prop('disabled', true);
                    $('#construction_price_tax_exclude').removeAttr('disabled');
                    break;
                case '4' :        // Loss
                    $('#order_fail_date').prop('disabled', false);
                    $('#commission_order_fail_reason').attr('disabled', false);
                    $('#order_fail_date').val($('#order_fail_date_before').val());
                    if ($('#commission_order_fail_reason_before').val() > 0) {
                        $('#commission_order_fail_reason').val($('#commission_order_fail_reason_before').val());
                    } else {
                        $('#commission_order_fail_reason:first').prop('selected', true);
                    }
                    $('#progress_report_datetime').val($('#progress_report_datetime_before').val());
                    $('#complete_date').prop('disabled', true).val('').removeClass('is-invalid').siblings('.invalid-feedback').remove();
                    $('#construction_price_tax_exclude').prop('disabled', true);
                    break;
                case '5' :        // Referred
                    $('#complete_date').removeAttr('disabled');
                    $('#order_fail_date').removeAttr('disabled');
                    $('#commission_order_fail_reason').removeAttr('disabled');
                    $('#construction_price_tax_exclude').removeAttr('disabled');
            }
        }

        // Coloring according to the order situation (required: yellow (# ffd 700) input possible: white (#ffffff) input disabled: gray (# c 0 c 0 c 0))
        switch ($('#commission_status').val()) {
            case '1' :        // processing
                $('#complete_date').css('background', '#c0c0c0');
                $('#order_fail_date').css('background', '#c0c0c0');
                $('#commission_order_fail_reason').css('background', '#c0c0c0');
                $('#construction_price_tax_exclude').css('background', '#c0c0c0');
                break;
            case '2' :        // Orders received
                $('#complete_date').css('background', '#c0c0c0');
                $('#order_fail_date').css('background', '#c0c0c0');
                $('#commission_order_fail_reason').css('background', '#c0c0c0');
                $('#construction_price_tax_exclude').css('background', '#c0c0c0');
                break;
            case '3' :        // Finished construction
                $('#complete_date').css('background', '#ffd700');
                $('#order_fail_date').prop('disabled', true);
                $('#order_fail_date').css('background', '#c0c0c0');
                $('#commission_order_fail_reason').css('background', '#c0c0c0');
                $('#construction_price_tax_exclude').css('background', '#ffd700');
                break;
            case '4' :        // Loss
                $('#complete_date').prop('disabled', true);
                $('#complete_date').css('background', '#c0c0c0');
                $('#order_fail_date').css('background', '#ffd700');
                $('#commission_order_fail_reason').css('background', '#ffd700');
                $('#construction_price_tax_exclude').prop('disabled', true);
                $('#construction_price_tax_exclude').css('background', '#c0c0c0');
                break;
            case '5' :        // Referred
                $('#complete_date').css('background', '#ffffff');
                $('#order_fail_date').css('background', '#ffffff');
                $('#commission_order_fail_reason').css('background', '#ffffff');
                $('#construction_price_tax_exclude').css('background', '#ffffff');
        }
    }

    return {
        init: init
    }
}();

$(document).ready(function () {
    CommissionOnChangeDetail.init();
});
