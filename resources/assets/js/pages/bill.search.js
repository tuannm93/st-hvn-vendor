var OnClick = function () {
    return {
        init: function (url_money_correspond, url_bill_download, url_bill_save) {
            if(window.history.state && window.history.state.dataBack) {
                $('.searchResult').html(window.history.state.dataBack);
            }
            $(document).on('click', '#history', function () {
                window.open(url_money_correspond, '_blank', 'width=800, height=500, menubar=no, toolbar=no, scrollbars=yes, left=' + (screen.availWidth - 800))
            });
            var billSearch = $('#bill-search');
            var feeTarget = '.fee_target_checkbox';

            $(document).on('click', '#fee_all_check', function () {
                var validCheckbox = billSearch.find('#valid-checkbox-table');
                validCheckbox.html('');
                if ($(this).prop("checked") === true) {
                    $("input:checkbox[class='fee_target_checkbox']").prop('checked', true);
                    checkAllFee();
                    $("input:checkbox[id='all_check']").prop('checked', true);
                    checkAll();
                    var count = $("#count").val();
                    for (var i = 0; i <= count; i = i + 1) {
                        checkValue(i);
                    }
                } else {
                    checkAllFee();
                    $("input:checkbox[id='all_check']").prop('checked', false);
                    checkAll();
                    var count = $("#count").val();
                    for (var i = 0; i <= count; i = i + 1) {
                        checkValue(i);
                    }
                }
            });
            $(document).on('click', '#all_check', function () {
                var validCheckbox = billSearch.find('#valid-checkbox-table');
                validCheckbox.html('');
                checkAll();
            });

            function checkAllFee() {
                var selector = '#tbBillSearch input:checkbox[name="checkbox[]"]';
                var els = billSearch.find(selector);
                var feeCheckAll = billSearch.find('#fee_all_check');
                if (feeCheckAll.is(':checked')) {
                    els.each(function () {
                        this.checked = true;
                    });
                } else {
                    els.each(function () {
                        this.checked = false;
                    })
                }
            }

            function checkAll() {
                var selector = '#tbBillSearch input:checkbox[name="target[]"]';
                var els = billSearch.find(selector);
                var allCheck = billSearch.find('#all_check');
                if (allCheck.prop("checked") === true) {
                    els.each(function () {
                        this.checked = true;
                    });
                } else {
                    els.each(function () {
                        this.checked = false;
                    })
                }
            }

            function checkValue(num) {
                if ($("#checkbox" + num).prop("checked") === true) {
                    var total_bill_price = $("#total_bill_price" + num).val();
                    $("#fee_payment_price" + num).val(total_bill_price);
                } else {
                    $("#fee_payment_price" + num).val(0);
                }
                changeValue(num);
            }

            function changeValue(num) {
                var total_bill_price = $("#total_bill_price" + num).val();
                var fee_payment_price = $("#fee_payment_price" + num).val();
                if (fee_payment_price.match(/^[0-9]+$/)) {
                    var fee_payment_balance = Number(total_bill_price) - Number(fee_payment_price);
                    $("#fee_payment_balance" + num).val(fee_payment_balance);
                    $("#fee_payment_balance_display" + num).html(separate(fee_payment_balance) + '円');
                    $("#target" + num).attr("checked", true);
                    aggregate();
                }

                $("input:checkbox[id='target" + num + "']").attr('checked', true);
            }

            function aggregate() {
                var count = $("#count").val();
                var i;
                var fee_payment_price;
                var fee_payment_balance;
                var all_fee_payment_price = 0;
                var all_fee_payment_balance = 0;
                for (i = 0; i <= count; i++) {
                    fee_payment_price = $("#fee_payment_price" + i).val();
                    fee_payment_balance = $("#fee_payment_balance" + i).val();
                    if (fee_payment_price.match(/^[0-9]+$/)) {
                        all_fee_payment_price = Number(all_fee_payment_price) + Number(fee_payment_price);
                    }
                    if (fee_payment_balance.match(/^[0-9]+$/)) {
                        all_fee_payment_balance = Number(all_fee_payment_balance) + Number(fee_payment_balance);
                    }
                }
                $("#all_fee_payment_price_display").html(separate(all_fee_payment_price) + '円');
                $("#all_fee_payment_balance_display").html(separate(all_fee_payment_balance) + '円');
            }

            $(document).on('click', feeTarget, function () {
                var count = $(this).val();
                $('input:checkbox[id="target' + count + '"]').prop('checked', true);
                checkValue(count)
            });
            $(document).on('change', '.fee_target_input', function () {
                changeValue($(this).attr('data-fee'))
            });

            $(document).on('click', feeTarget, function () {
                var validCheckbox = billSearch.find('#valid-checkbox-table');
                validCheckbox.html('');
            });

            $(document).on('click', '.target_checkbox', function () {
                var validCheckbox = billSearch.find('#valid-checkbox-table');
                validCheckbox.html('');
            });

            function checkDownload(list) {
                var isChecked = false;
                $.each(list, function (index, el) {
                    if ($(el).prop('checked') !== false) {
                        isChecked = true;
                    }
                });
                return isChecked;
            };

            $(document).on('keyup', '.fee_target_input', function () {
                var validCheckbox = billSearch.find('#valid-checkbox-table');
                validCheckbox.html('');
            });

            $(document).on('click', '.target_checkbox', function () {
                $(this).css('outline', 'none');
            });

            $(document).on('click', '#all_check', function () {
                if($(this).prop('checked') === true) {
                    $(this).removeClass('out_line');
                }
            });

            $(document).on('click', '#bill_save', function (e) {
                var selector = '#tbBillSearch .target_checkbox';
                var validCheckbox = billSearch.find('#valid-checkbox-table');
                var els = billSearch.find(selector);
                var isChecked = checkDownload(els);
                if (isChecked === false) {
                    validCheckbox.html(errorsMesseg);
                    $('#fee_payment_price0').focus();
                    return false;
                }
            });

            $(document).on('click', '#bill_download', function (e) {
                var selector = '#tbBillSearch .target_checkbox';
                var validCheckbox = billSearch.find('#valid-checkbox-table');
                var els = billSearch.find(selector);
                var isChecked = checkDownload(els);
                var allCheck = billSearch.find('#tbBillSearch #all_check');
                if (isChecked === false) {
                    validCheckbox.html(errorsMesseg);
                    allCheck.focus().addClass('out_line');
                }
                else {
                    e.preventDefault();
                    $(controlEl.formId).attr('action', url_bill_download).submit();
                    $(controlEl.formId).attr('action', url_bill_save);
                }
            });
            function separate(num) {
                return String(num).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
            }
        }
    }
}();
