var Step2AgreementSystem = function () {
    function eventBackButton() {
        $('#back_button').on('click', function () {
            window.location.href = urlBackStep2;
        });
    }

    function searchAddressInit() {
        $("#search_address").click(function () {
            var zip = $('#postcode').val();
            if (zip) {
                var url = $('#page-data').data('get-address-by-postcode-url');
                Address.getAddressByZipCode(url, zip, function ($data) {
                    if (!$data['m_posts_jis_cd']) {
                        return false;
                    }
                    $('#address1').val(Number($data['m_posts_jis_cd']));
                    $('#address2').val($data['address2']);
                    $('#address3').val($data['address3']);
                    if ($data['address2'] && $('#address2').hasClass('is-invalid')) {
                        $('#address2').addClass('is-valid').removeClass('is-invalid');
                    }
                    if ($data['address3'] && $('#address3').hasClass('is-invalid')) {
                        $('#address3').addClass('is-valid').removeClass('is-invalid');
                    }
                });
            } else {
                alert('郵便番号が正しく入力されていません。');
            }
        });

        $("#search_representative_address").click(function () {
            var zip = $('#representative_postcode').val();
            if (zip) {
                var url = $('#page-data').data('get-address-by-postcode-url');
                Address.getAddressByZipCode(url, zip, function ($data) {
                    if (!$data['m_posts_jis_cd']) {
                        return false;
                    }
                    $('#representative_address1').val(Number($data['m_posts_jis_cd']));
                    $('#representative_address2').val($data['address2']);
                    $('#representative_address3').val($data['address3']);
                    if ($data['address2'] && $('#representative_address2').hasClass('is-invalid')) {
                        $('#representative_address2').addClass('is-valid').removeClass('is-invalid');
                    }
                    if ($data['address3'] && $('#representative_address3').hasClass('is-invalid')) {
                        $('#representative_address3').addClass('is-valid').removeClass('is-invalid');
                    }
                });
            } else {
                alert('郵便番号が正しく入力されていません。');
            }
        });
    }

    function mobileMailInit() {
        if ($('#mobileMailNone').prop('checked')) {
            $('#mobileTelType').attr('disabled', 'disabled');
            $('#mailaddressMobile').attr('disabled', 'disabled');
        }
        $("#mobileMailNone").change(function () {
            if ($('#mobileMailNone').prop('checked')) {
                $('#mobileTelType').attr('disabled', 'disabled');
                $('#mailaddressMobile').attr('disabled', 'disabled');
                $('#mobileTelType, #mailaddressMobile').removeClass('is-valid is-invalid');
            } else {
                $('#mobileTelType').removeAttr('disabled');
                $('#mailaddressMobile').removeAttr('disabled');
            }
        });
    }

    function coordinationMethodInit() {
        $("#coordinationMethod").change(function () {
            toggleFaxRequired();
            if ($("#coordinationMethod").val() == 6) {
                $("#coordinationMethodNote").hide();
            } else {
                $("#coordinationMethodNote").show();
            }
        });
    }

    function contactTimeInit() {
        if ($("#support24hour").prop('checked')) {
            $("#availableTimeFrom").val("").prop('disabled', true);
            $("#availableTimeTo").val("").prop('disabled', true);
        }
        if ($("#contactableSupport24hour").prop('checked')) {
            $("#contactableTimeFrom").val("").prop('disabled', true);
            $("#contactableTimeTo").val("").prop('disabled', true);
        }
        if ($("#supportOther").prop('checked')) {
            $("#availableTimeFrom").attr("data-rule-required", true);
            $("#availableTimeTo").attr("data-rule-required", true);
        }

        if ($("#contactableSupportOther").prop('checked')) {
            $("#contactableTimeFrom").attr("data-rule-required", true);
            $("#contactableTimeTo").attr("data-rule-required", true);
        }

        $("#support24hour").click(function () {
            if ($("#support24hour").prop('checked')) {
                $("#supportOther").prop("checked", false);
                $("#availableTimeFrom").removeAttr("data-rule-required");
                $("#availableTimeTo").removeAttr("data-rule-required");
                $("#availableTimeFrom").val("").prop('disabled', true);
                $("#availableTimeTo").val("").prop('disabled', true);
            }
        });

        $("#supportOther").click(function () {
            if ($("#supportOther").prop('checked')) {
                $("#support24hour").prop("checked", false);
                $("#availableTimeFrom").attr("data-rule-required", true);
                $("#availableTimeTo").attr("data-rule-required", true);
                $("#availableTimeFrom").prop('disabled', false);
                $("#availableTimeTo").prop('disabled', false);
            }
        })

        $("#contactableSupport24hour").on('click', function () {
            if ($("#contactableSupport24hour").prop('checked')) {
                $("#contactableSupportOther").prop("checked", false);
                $("#contactableTimeFrom").removeAttr("data-rule-required");
                $("#contactableTimeTo").removeAttr("data-rule-required");
                $("#contactableTimeFrom").val("").prop('disabled', true);
                $("#contactableTimeTo").val("").prop('disabled', true);

            }
        });

        $("#contactableSupportOther").on('click', function () {
            if ($("#contactableSupportOther").prop('checked')) {
                $("#contactableSupport24hour").prop("checked", false);
                $("#contactableTimeFrom").attr("data-rule-required", true);
                $("#contactableTimeTo").attr("data-rule-required", true);
                $("#contactableTimeFrom").prop('disabled', false);
                $("#contactableTimeTo").prop('disabled', false);
            }
        });
    }

    function holidayInit() {
        $('.holiday').click(function () {
            $('.holidayNo').prop('checked', false);
            $(this).prop('checked');
        });

        $('.holidayNo').click(function () {
            $('.holiday').prop('checked', false);
            $(this).prop('checked');
        });
    }

    function toggleFaxRequired() {
        var val = parseInt($('#coordinationMethod').val());

        if (val != 1 && val != 7 && val != 3) {
            $('#mCorpFax').data('rule-required', false);
            $('#label-required-mCorpFax').hide();
        } else {
            $('#mCorpFax').data('rule-required', true);
            $('#label-required-mCorpFax').show();
        }
    }
    function setupValidation() {
        $('#mobileTelType').rules('add', {
            required: "#mobileMailNone:unchecked"
        });
        $('.group-listedFlag', '.group-companyKind', '.group-taxPayment').change(function(){
            if ($(this).find('.err-group-radioButton')) {
                $('.err-group-radioButton').remove();
            }
        })
    }
    $(document).on('click', 'form button[type=submit]', function(e) {
        var listedFlag_isValid = $('.group-listedFlag input:checked').length;
        var companyKind_isValid = $('.group-companyKind input:checked').length;
        var taxPayment_isValid = $('.group-taxPayment input:checked').length;
        var invalid_field = $('#agreementSystemStep2').find('.is-invalid');
        if(listedFlag_isValid == 0) {
            $('.group-listedFlag').find('.err-group-radioButton').removeAttr('hidden');
            $('#listedFlag_1').focus();
            e.preventDefault();
        }
        if(companyKind_isValid == 0) {
            $('.group-companyKind').find('.err-group-radioButton').removeAttr('hidden').focus();
            $('#companyKind_1').focus();
            e.preventDefault();
        }
        if(taxPayment_isValid == 0) {
            $('.group-taxPayment').find('.err-group-radioButton').removeAttr('hidden').focus();
            $('#taxPayment_1').focus();
            e.preventDefault();
        }
        if (invalid_field.attr('id')) {
            $('#'+invalid_field.attr('id')).focus();
            e.preventDefault();
        }
    });

    function init() {
        eventBackButton();
        $('#step2').addClass('active');
        searchAddressInit();
        mobileMailInit();
        contactTimeInit();
        Datetime.initForDatepicker();
        Datetime.initForTimepicker();
        holidayInit();
        toggleFaxRequired();
        coordinationMethodInit();
        FormUtil.validate("#agreementSystemStep2");
        setupValidation();
    }

    return {
        init: init
    }
}();
