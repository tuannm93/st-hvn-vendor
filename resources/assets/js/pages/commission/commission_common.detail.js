var toDoubleDigits = function(num) {
    num += '';

    if (num.length === 1) {
      num = '0' + num;
    }

   return num;
};

function val_check() {
    //As the controller side also requires commission calculation processing, it moves to calculation processing BillPriceUtilComponent
    //Acquire recalculation result with ajax
    $.ajax({
        type: 'POST',
        url: '/ajax/calculate_bill_info',
        data: {
            'CommissionInfo' : {
                'id' : $('#commissioninfo_id').val(),
                'commission_status' : $('#commission_status').val(),
                'complete_date' : $('#complete_date').val(),
                'construction_price_tax_exclude' : $('#construction_price_tax_exclude').val()
            }
        },
        success: function(data){
            var obj = data;//$.parseJSON(data);
            var tax_rate = obj.MTaxRate.tax_rate;
            var construction_price_tax_exclude = obj.CommissionInfo.construction_price_tax_exclude;
            var construction_price_tax_include = obj.CommissionInfo.construction_price_tax_include;
            var corp_fee = obj.CommissionInfo.corp_fee;
            var deduction_tax_exclude = obj.CommissionInfo.deduction_tax_exclude;
            var deduction_tax_include = obj.CommissionInfo.deduction_tax_include;
            var confirmd_fee_rate = obj.CommissionInfo.confirmd_fee_rate;
            var fee_target_price = obj.BillInfo.fee_target_price;
            var fee_tax_exclude = obj.BillInfo.fee_tax_exclude;
            var tax = obj.BillInfo.tax;
            var insurance_price = obj.BillInfo.insurance_price;
            var total_bill_price = obj.BillInfo.total_bill_price;

            var construction_price_tax_include_rep = construction_price_tax_include;

            if ($('#commission_status_flg').val() == '1') {

                // Display of construction amount (tax included)
                // If the construction amount (tax excluded) is blank, if you calculate unconditionally, '0' is set for tax, so calculate only when tax included is 0 or more
                if(construction_price_tax_include_rep == null || construction_price_tax_include_rep == '') {
                    construction_price_tax_include_rep = 0;
                }

                var construction_price_tax_include_dis = construction_price_tax_include_rep.toString().replace(/(\d)(?=(\d\d\d)+$)/g, '$1,');
                if (document.getElementById('construction_price_tax_include_display') != null) {
                    $('#construction_price_tax_include_display').html(construction_price_tax_include_dis + '円');
                }

                $('#construction_price_tax_include').val(construction_price_tax_include);

                if(deduction_tax_exclude != null){
                    var deduction_tax_exclude_dis = deduction_tax_exclude.toString().replace(/(\d)(?=(\d\d\d)+$)/g, '$1,');

                    if (document.getElementById('deduction_tax_exclude_display') != null) {
                        $('#deduction_tax_exclude_display').html(deduction_tax_exclude_dis + '円');
                    }

                    $('#deduction_tax_exclude').val(deduction_tax_exclude);
                }

                var fee_target_price_dis = fee_target_price.toString().replace(/(\d)(?=(\d\d\d)+$)/g, '$1,');

                if (document.getElementById('fee_target_price_display') != null) {
                    $('#fee_target_price_display').html(fee_target_price_dis + '円');
                }

                $('#fee_target_price').val(fee_target_price);

                if ($('#liability_insurance').val() == '1') {
                    var insurance_price_dis = insurance_price.toString().replace(/(\d)(?=(\d\d\d)+$)/g, '$1,');

                    if (document.getElementById('insurance_price_display') != null) {
                        $('#insurance_price_display').html(insurance_price_dis + '円');
                    }

                    $('#insurance_price').val(insurance_price);
                }

            }

            //Display commission rate
            if (document.getElementById('confirmd_fee_rate_display') != null) {
                $('#confirmd_fee_rate_display').html(confirmd_fee_rate+' ％');
            }
            $('#confirmd_fee_rate').val(confirmd_fee_rate);

            //Display of commission fee
            $('#corp_fee').val(corp_fee);

            //Display fee (excluding tax)
            var fee_tax_exclude_dis = fee_tax_exclude.toString().replace(/(\d)(?=(\d\d\d)+$)/g, '$1,');

            if (document.getElementById('fee_tax_exclude_display') != null) {
                $('#fee_tax_exclude_display').html(fee_tax_exclude_dis + '円');
            }

            $('#fee_tax_exclude').val(fee_tax_exclude);

            //Display of total fee
            if(total_bill_price != null){
                var total_bill_price_dis = total_bill_price.toString().replace(/(\d)(?=(\d\d\d)+$)/g, '$1,');
                $('#total_bill_price_display').html(total_bill_price_dis + '円');
            }else{
                $('#total_bill_price_display').html('0円');
            }

            //Display of consumption tax
            if ($('#construction_price_tax_exclude').val() != ''){
                var input = $('#tax_rate_list').find('input');
                var tax_dis = tax.toString().replace(/(\d)(?=(\d\d\d)+$)/g, '$1,');
                var spanText = $('#tax_rate_list').html('<span>' + tax_rate + '&nbsp;％<br>'+ tax_dis + '円&nbsp;</span>');
                $('#tax_rate_list').append(spanText);
                $('#tax_rate_list').append(input);
            } else {
                var input = $('#tax_rate_list').find('input');
                $('#tax_rate_list').html('');
                $('#tax_rate_list').append(input);
                $('#tax_rate_list').append($('<span>').html('&nbsp;'));
            }
            order_fail_reason_check();
        },
        error: function(data){
        }
    });

    order_fail_reason_check();
}

function order_fail_reason_check() {
    var commission_status = $('#commission_status').val();        // 取次状況

    if (commission_status == $('#construction_status_val').val()) {
        $('#commission_order_fail_reason').removeAttr('disabled');
    } else {
        $('#commission_order_fail_reason').val('');
        $('#commission_order_fail_reason option').attr('selected', false);
        $('#commission_order_fail_reason').attr('disabled', 'disabled');
        $('#order_fail_date').val('');
    }
}
