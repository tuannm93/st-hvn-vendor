var BillDetail = function () {
    function redirectToBillList(selector) {
        var mcorpId = $(selector).data('mcorpid');
        $(selector).click(function () {
            window.location.href = mcorpId;
        });
    }

    function valueChange(selector) {
        $(selector).change(function () {
            var totalBillPrice = $("#total_bill_price").val();
            var feePaymentPrice = $("#fee_payment_price").val();
            if (feePaymentPrice.match(/^[0-9]+$/)) {
                var feePaymentBalance = Number(totalBillPrice) - Number(feePaymentPrice);
                $("#fee_payment_balance").val(feePaymentBalance);
                $("#fee_payment_balance_display").html(separate(feePaymentBalance) + "円");
            }
        });
    }

    function separate(num) {
        return String(num).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
    }

    function init() {
        redirectToBillList(".redirectToBillList");
        valueChange(".value-change");
    }

    return {
        init: init
    }
}();

