'use strict';

var FormUtil = function () {
    return {
        validate: function validate(formSelector) {
            $(document).find(formSelector).each(function () {
                $(this).validate({
                    ignore: ".ignore",
                    onchange: function onchange(element) {
                        this.element(element);
                        toggleValidityStateForRequiredGroup($(element).parents('[data-group-required="true"]'), false);
                        toggleValidityStateForRequiredGroupFill($(element).parents('[data-group-fill-required="true"]'));
                    },
                    onblur: function onblur(element) {
                        this.element(element);
                        toggleValidityStateForRequiredGroup($(element).parents('[data-group-required="true"]'), false);
                        toggleValidityStateForRequiredGroupFill($(element).parents('[data-group-fill-required="true"]'));
                    },
                    onfocusout: function onfocusout(element) {
                        this.element(element);
                        toggleValidityStateForRequiredGroup($(element).parents('[data-group-required="true"]'), false);
                        toggleValidityStateForRequiredGroupFill($(element).parents('[data-group-fill-required="true"]'));
                    },
                    onclick: function onclick(element) {
                        this.element(element);
                        toggleValidityStateForRequiredGroup($(element).parents('[data-group-required="true"]'), false);
                        toggleValidityStateForRequiredGroupFill($(element).parents('[data-group-fill-required="true"]'));
                    },
                    onkeyup: function onkeyup(element) {
                        this.element(element);
                        toggleValidityStateForRequiredGroup($(element).parents('[data-group-required="true"]'), false);
                        toggleValidityStateForRequiredGroupFill($(element).parents('[data-group-fill-required="true"]'));
                    },
                    submitHandler: function submitHandler(form) {
                        var validate = groupCheckedAtLeastOne(true) && groupFillAtLeastOne(true) && focusWhenSubmit(formSelector, false);
                        if (validate) {
                            form.submit();
                        } else {
                            focusWhenSubmit(formSelector, true);
                        }
                    },
                    invalidHandler: function invalidHandler() {
                        groupCheckedAtLeastOne(false);
                        groupFillAtLeastOne(false);
                        // focusWhenSubmit(formSelector, true);
                    },
                    errorClass: "invalid-feedback",
                    validClass: "valid-feedback",
                    errorPlacement: function errorPlacement(error, element) {
                        var errContainer = $(element).data('error-container');
                        if (errContainer) {
                            $(errContainer).append(error);
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    highlight: function highlight(element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function unhighlight(element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    }
                });
            });

            function focusWhenSubmit(form, isFocus) {
                var result = true;
                var itemFocus = 'nothing';
                $(form).find('input:not([type=hidden])').each(function (key, e) {
                    if ($(e).hasClass('is-invalid')) {
                        itemFocus = e;
                        result = false;
                        return false;
                    }
                });
                if (itemFocus != 'nothing' && isFocus) {
                    $(itemFocus).focus();
                }
                if (!isFocus) {
                    return result;
                }
            }

            function groupCheckedAtLeastOne(isReturn) {
                var checkGroup = true,
                    arrFailGroup = [];
                $(document).find('[data-group-required=true]').each(function () {
                    checkGroup = toggleValidityStateForRequiredGroup($(this));
                });

                if (isReturn) {
                    return checkGroup;
                }
                return false;
            }

            function groupFillAtLeastOne(isReturn) {
                var result = true,
                    itemFocus = 'nothing';
                $(document).find('[data-group-fill-required=true]').each(function () {
                    var checkGroup = toggleValidityStateForRequiredGroupFill($(this));

                    if (!checkGroup) {
                        result = false;
                    }
                });

                if (isReturn) {
                    return result;
                }
                return false;
            }

            function showFeedbackForRequiredOption(itemParent, item, isError, containerError, message, onlyFeedback) {
                if (!onlyFeedback) {
                    if (isError) {
                        item.setCustomValidity(' ');
                        $(item).addClass('is-invalid').removeClass('is-valid');
                    } else {
                        $(item).removeClass('is-invalid').addClass('is-valid');
                    }
                }
                var feedback = containerError.length ? $(item).data(containerError) : '',
                    error = isError ? '<label class="invalid-feedback d-block">' + message + '</label>' : '';
                if (feedback) {
                    $(feedback).empty();
                    if (isError && message) {
                        $(feedback).append(error);
                    }
                } else {
                    itemParent.children('.invalid-feedback').remove();
                    if (isError && message) {
                        itemParent.append(error);
                    }
                }
            }

            function toggleValidityStateForRequiredGroup(element, focus) {
                var result = true,
                    countChecked = element.find('input:checked').length;

                if (countChecked !== 0) {
                    element.find('input').each(function (e, val) {
                        val.setCustomValidity('');
                    });

                    element.children('.invalid-feedback').remove();
                    element.find('input').removeClass('is-invalid').addClass('is-valid');
                } else {

                    element.find('input').each(function (e, val) {
                        val.setCustomValidity(' ');
                    });
                    element.find('input').addClass('is-invalid').removeClass('is-valid');

                    // Handle error message position
                    var errContainer = element.data('error-container'),
                        error = '<label class="invalid-feedback d-block">' + $.validator.messages.requiredOne + '</label>';

                    element.children('.invalid-feedback').remove();

                    if (errContainer) {
                        $(errContainer).append(error);
                    } else {
                        element.append(error);
                    }

                    if (focus) {
                        element.find('input').first().focus();
                    }

                    result = false;
                }

                return result;
            }

            function toggleValidityStateForRequiredGroupFill(element) {
                var hasFilled = false;

                element.find('input:not([type=hidden]):not([type=checkbox]):not([type=radio])').each(function (key, e) {
                    var value = $(e).val().trim();
                    if (value != '') {
                        hasFilled = true;
                    }
                });

                if (hasFilled) {
                    element.find('input:not([type=hidden])').each(function (key, e) {
                        if ($(e).val().trim() == '') {
                            showFeedbackForRequiredOption($(e).parent(), e, false, '', '', false);
                        }
                    });

                    showFeedbackForRequiredOption($(element).parent(), element, false, 'error-container', '', true);
                } else {
                    element.find('input:not([type=hidden])').each(function (key, e) {
                        showFeedbackForRequiredOption($(e).parent(), e, true, '', '', false);
                    });
                    var msg = $(element).data('msg-group-fill-required');
                    showFeedbackForRequiredOption($(element).parent(), element, true, 'error-container', msg ? msg : $.validator.messages.requiredOne, true);
                }

                return hasFilled;
            }
        },

        convertToHalfWidth: function fullWidthNumConvert(fullWidthNum) {
            return fullWidthNum.replace(/[\uFF10-\uFF19]/g, function (m) {
                return String.fromCharCode(m.charCodeAt(0) - 0xfee0);
            });
        }
    };
}();

// Custom validator methods

// Required Pair
$.validator.addMethod("requiredPairTo", function (value, element, param) {
    var partner = $(param).val().trim();
    if (!value.trim().length && partner.length) {
        return false;
    }
    return true;
}, $.validator.messages.requiredOption('FROM'));

$.validator.addMethod("requiredPairFrom", function (value, element, param) {
    var partner = $(param).val().trim();
    if (!value.trim().length && partner.length) {
        return false;
    }
    return true;
}, $.validator.messages.requiredOption('TO'));

// Datetime picker
$.validator.addMethod("datetimeCheck", function (value) {
    var regEx = /^\d{4}\/\d{2}\/\d{2}(\s\d{2}:\d{2})?$/;

    if (value.length && value.match(regEx) == null) {
        return false;
    }

    return true;
}, $.validator.messages.dateTime);

//Time picker
$.validator.addMethod("timeCheck", function (value, element) {
    value = FormUtil.convertToHalfWidth(value);
    var checkAff = element.getAttribute('class').includes('timeAffPicker');
    var valueCut = value.trim();

    if (!valueCut.length && value.length != valueCut.length) {
        return false;
    }

    if (checkAff) {
        return true;
    } else {
        return this.optional(element) || /^([01]\d|2[0-3]|[0-9])(:[0-5]\d){1,2}$/.test(value);
    }

}, $.validator.messages.invalid_time);

//Date picker
$.validator.addMethod("dateCheck", function (value, element) {
    var regEx = /^\d{4}\/\d{2}\/\d{2}?$/;

    if (value.length && value.match(regEx) == null) {
        return false;
    }

    return this.optional(element) || !/Invalid|NaN/.test(new Date(value).toString());
}, $.validator.messages.date);

//Compare From with To
$.validator.addMethod("lessThanTime", function (value, element, param) {

    if ($(param).val()) {
        var target = $(param);

        if (this.settings.onfocusout && target.not(".validate-lessThanTime-blur").length) {
            target.addClass("validate-lessThanTime-blur").on("blur.validate-lessThanTime", function () {
                $(element).valid();
            });
        }
        return value <= target.val();
    }

    return true;
}, $.validator.messages.from_less_than_to);

// Multiple email validation
$.validator.addMethod("multipleEmailCheck", function (value, element) {
    var regEx = /^[\x20-\x7e]*$/,
        arrEmail = value.trim().split(';'),
        isValid = true;

    $.each(arrEmail, function (index, value) {
        if (value.match(regEx) == null || value.match(/@/) == null) {
            isValid = false;
            return;
        }
    });

    return this.optional(element) || isValid;
}, $.validator.messages.email);

// Half size number and text validation
$.validator.addMethod("halfSize", function (value, element) {
    return this.optional(element) || !/[\uff01-\uff5e]/g.test(value);
}, $.validator.messages.halfSize);

// Half size number validation
$.validator.addMethod("numberHalfSize", function (value, element) {
    return this.optional(element) || /[^\uff01-\uff5e]/g.test(value) && /^([0-9]*)?$/.test(value);
}, $.validator.messages.halfSize);

// Al size number validation
$.validator.addMethod("numberAllSize", function (value, element) {
    value = FormUtil.convertToHalfWidth(value);
    return this.optional(element) || /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(value);
}, $.validator.messages.number);

// Min all size number validation
$.validator.addMethod("minAllSize", function (value, element, param) {
    value = FormUtil.convertToHalfWidth(value);
    return this.optional(element) || value >= param;
}, $.validator.messages.min);

// Max all size number validation
$.validator.addMethod("maxAllSize", function (value, element, param) {
    value = FormUtil.convertToHalfWidth(value);
    return this.optional(element) || value <= param;
}, $.validator.messages.max);

$.validator.addClassRules({
    "datetimepicker": {
        datetimeCheck: true
    },
    "timepicker": {
        timeCheck: true
    },
    "datepicker": {
        dateCheck: true
    },
    "datepicker_limit": {
        dateCheck: true
    },
    "multiple-email-validation": {
        multipleEmailCheck: true
    }
});

// Extends default validation method

$.validator.methods.email = function (value, element) {
    var regEx = /^[\x20-\x7e]*$/,
        isValid = true;

    if (value.match(regEx) == null || value.match(/@/) == null) {
        isValid = false;
    }

    return this.optional(element) || isValid;
};

$.validator.methods.url = function (value, element) {
    return this.optional(element) || /^((http[s]?|ftp):\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,4}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)$/.test(value);
};

$.validator.methods.required = function (value, element, param) {
    value = value.trim();
    // Check if dependency is met
    if (!this.depend(param, element)) {
        return "dependency-mismatch";
    }
    if (element.nodeName.toLowerCase() === "select") {

        // Could be an array for select-multiple or a string, both are fine this way
        var val = $(element).val();
        return val && val.length > 0;
    }
    if (this.checkable(element)) {
        return this.getLength(value, element) > 0;
    }
    return value !== undefined && value !== null && value.length > 0;
};
