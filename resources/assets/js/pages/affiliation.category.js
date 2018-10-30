var AffiliationCategory = function () {

    var $contactableSupport24hourEl = $('#contactable_support24hour'),
        $contactableTimeFromEl =  $('#contactable_time_from'),
        $contactableTimeToEl = $('#contactable_time_to'),
        $contactableTimeOther = $('#contactable_time_other'),
        $support24hourEl = $("#support24hour"),
        $availableTimeFromEl = $('#available_time_from'),
        $availableTimeToEl = $('#available_time_to'),
        $availableTimeOtherEl = $("#available_time_other");

    var controlDisabled = function (el, isDisabled) {
        if (isDisabled) {
            el.attr('disabled', 'disabled');
        } else {
            if (el.is(':disabled')) {
                el.removeAttr('disabled');
            }
        }
    };

    var initialize = function () {
        if ($contactableSupport24hourEl.prop('checked')) {
            controlDisabled($contactableTimeFromEl, true);
            controlDisabled($contactableTimeToEl, true);
        }

        if ($support24hourEl.prop('checked')) {
            controlDisabled($availableTimeFromEl, true);
            controlDisabled($availableTimeToEl, true);
        }
    };

    function holidayInit() {
        $('.category-holiday').click(function () {
            $('.category-holiday-no-rest').prop('checked', false);
            $(this).prop('checked');
        });

        $('.category-holiday-no-rest').click(function () {
            $('.category-holiday').prop('checked', false);
            $(this).prop('checked');
        });
    }

    function contactTimeInit() {
        $contactableSupport24hourEl.click(function () {
            if ($contactableSupport24hourEl.prop('checked')) {
                $contactableTimeOther.prop('checked', false);
                $contactableTimeFromEl.val('');
                $contactableTimeToEl.val('');
                controlDisabled($contactableTimeFromEl, true);
                controlDisabled($contactableTimeToEl, true);
            } else { // remove disabled attribute
                controlDisabled($contactableTimeFromEl, false);
                controlDisabled($contactableTimeToEl, false);
            }
        });

        $contactableTimeOther.click(function(){
            if ($contactableTimeOther.prop('checked')) {
                $contactableSupport24hourEl.prop('checked', false);
                controlDisabled($contactableTimeFromEl, false);
                controlDisabled($contactableTimeToEl, false);
            }
        });

        $support24hourEl.click(function(){
            if ($support24hourEl.prop('checked')) {
                $availableTimeOtherEl.prop('checked', false);
                $availableTimeFromEl.val('');
                $availableTimeToEl.val('');
                controlDisabled($availableTimeFromEl, true);
                controlDisabled($availableTimeToEl, true);
            } else {
                controlDisabled($availableTimeFromEl, false);
                controlDisabled($availableTimeToEl, false);
            }
        });

        $availableTimeOtherEl.click(function(){
            if ($availableTimeOtherEl.prop('checked')) {
                $support24hourEl.prop('checked', false);
                controlDisabled($availableTimeFromEl, false);
                controlDisabled($availableTimeToEl, false);
            }
        });
    }

    function postcodeInit() {
        $("#address-search").click(function(){
            var url = $('#page-data').data('get-address-by-postcode-url');
            var zip = $('#postcode').val();
            if (typeof zip !== 'undefined' && zip !== '') {
                Address.getAddressByZipCode(url, zip, function ($data) {
                    if (!jQuery.isEmptyObject($data)) {
                        $('#address1').val(parseInt($data['m_posts_jis_cd']));
                        $('#address2').val($data['address2']);
                        $('#address3').val($data['address3']);
                    }
                });
            }
        });

        $("#representative-address-search").click(function(){
            var url = $('#page-data').data('get-address-by-postcode-url');
            var zip = $('#representative_postcode').val();
            if (typeof zip !== 'undefined' && zip !== '') {
                Address.getAddressByZipCode(url, zip, function ($data) {
                    if (!jQuery.isEmptyObject($data)) {
                        $('#representative_address1').val(parseInt($data['m_posts_jis_cd']));
                        $('#representative_address2').val($data['address2']);
                        $('#representative_address3').val($data['address3']);
                    }
                });
            }
        });
    }

    function mobileMailInit() {
        $("#mobile_mail_none").click(function(){

            if ($("#mobile_mail_none").prop('checked')) {
                $('#mobile_tel_type').removeClass('is-invalid');
                $('#mailaddress_mobile').removeClass('is-invalid');
                $('#mobile_tel_type').attr('disabled', 'disabled');
                $('#mailaddress_mobile').attr('disabled', 'disabled');
                $('#mobile_tel_type_hidden').removeAttr('disabled');
                $('#mailaddress_mobile_hidden').removeAttr('disabled');
            } else {
                $('#mobile_tel_type').removeAttr('disabled');
                $('#mailaddress_mobile').removeAttr('disabled');
                $('#mobile_tel_type_hidden').attr('disabled', 'disabled');
                $('#mailaddress_mobile_hidden').attr('disabled', 'disabled');
            }
        });
    }

    function initCoordinationMethod() {
        $('#coordination_method').on('change', function () {
            checkFAXisRequired();
            if ($(this).val() === "6") {
                $('#coordination_method_message_info').addClass('d-none');
            } else {
                $('#coordination_method_message_info').removeClass('d-none');
            }
        });
    }

    function checkFAXisRequired() {
        var coordinationText = $("#coordination_method option:selected").text();
        if(coordinationText.search(/fax/i) >= 0) {
            $('#fax_input').data('rule-required',true);
            $('#fax_input').focus();
            $('#coordination_method').focus();
        } else {
            $('#fax_input').data('rule-required',false);
            $('#fax_input').removeClass('is-invalid');
            $('#fax_input-error').hide();
        }
    }

    function setupValidation() {

        $contactableTimeFromEl.rules('add', {
            required: "#contactable_time_other:checked"
        });

        $contactableTimeToEl.rules('add', {
            required: "#contactable_time_other:checked"
        });

        $availableTimeFromEl.rules('add', {
            required: "#available_time_other:checked"
        });

        $availableTimeToEl.rules('add', {
            required: "#available_time_other:checked"
        });
    }

    function init() {
        initialize();
        holidayInit();
        contactTimeInit();
        Datetime.initForTimepicker();
        postcodeInit();
        mobileMailInit();
        initCoordinationMethod();
        FormUtil.validate('#updateCorp');
        setupValidation();

    }

    return {
        init: init
    }
}();
jQuery(document).ready(function () {
    AffiliationCategory.init();
});
