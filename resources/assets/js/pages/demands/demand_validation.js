var demandValidationModule = (function() {
    var $topRegistButton = $('#top_regist'),
        $bottomRegistButton = $('#bottom_regist'),
        $demandCorrespondContent = $('#demandCorrespondContent'),
        $demandContentEl = $('#demand-content'),
        $quickOrderFailButton = $('#quick_order_fail');

    var $quickOrderFailReasonEl = $('#quick-order-fail-reason'),
        $genreEl = $('#genre_id'),
        $categoryEl = $('#category_id');

    var $modalDialog = $('#modal-dialog');

    var nonNotification = '非通知';

    var invalidCharacters  = ['×','→','⇒','←','↓','↑'];

    var messages = {
        notEmpty: "必須入力です。",
        validNumber: "半角数字で入力してください。",
        validEmail: "半角、Eメール形式で入力してください。",
        validDate: "日付形式で入力してください。",
        validUrl: "URL形式で入力してください",
        validContent: '対応履歴を登録する際には、対応者・対応内容は必須入力です。',
        validCustomerTel: "半角数字又は「非通知」で入力してください。",
        validDemandContent: "×,→,←,↓,↑,⇒は使用しないでください。",
        validMaxLengthContent: '1000文字以内で設定してください。'
    };

    var ruleNames = {
        notEmpty: 'not-empty',
        validNumber: 'valid-number',
        validEmail: 'valid-email',
        validDate: 'valid-date',
        validUrl: 'valid-url',
        validCustomerTel: 'valid-customer'
    };

    var getRequiredFields = function (form) {
        return form.find('.is-required');
    };

    var getRequiredFieldsByQuickOrderFail = function (form) {
        return form.find('.is-required.quick-order-fail');
    }

    var demandValidate = {
        showDialog: function(header, content){
            $modalDialog.find('h3').html(header);
            $modalDialog.find('.modal-body').html(content);
            $modalDialog.modal({show: true});
        },
        generateMessageSection: function (msg) {
            var html = '';
            html += '<label class="invalid-feedback d-block">';
            html += msg;
            html += '</label>';
            return html;
        },
        checkDisabled: function (el) {
            return el.is(':disabled');
        },
        controlDisabled: function (el, isDisabled) {
            hasChange = false;
            if (isDisabled) {
                el.attr('disabled', 'disabled');
            } else {
                el.removeAttr('disabled');
            }
        },
        addInvalidClass: function (el) {
            if (!el.hasClass('is-invalid'))
                el.addClass('is-invalid');
        },
        removeInvalidClass: function (el) {
            if (el.hasClass('is-invalid'))
                el.removeClass('is-invalid');
        },
        getMessageSection: function (el) {
            return el.find('label.invalid-feedback.d-block');
        },
        renderMessageSection: function (el, msg) {
            var section = demandValidate.getMessageSection(el);
            if (!section.length)
                el.append(demandValidate.generateMessageSection(msg));
            else
                $(section).html(msg);
        },
        removeMessageSection: function (el) {
            var section = demandValidate.getMessageSection(el);
            if (section.length) {
                $(section).remove();
            }
        },
        getRules: function (el) {
            var rules = [];
            var strRules = el.data('rules');
            if (typeof strRules != 'undefined' && strRules.indexOf(',') != -1)
                rules = strRules.split(',');
            else
                rules[0] = strRules;

            return rules;
        },
        convertToHalfSize: function (val) {
            return val.replace(/[\uFF10-\uFF19]/g, function(m) {
                return String.fromCharCode(m.charCodeAt(0) - 0xfee0);
            });
        },
        checkNumber: function (val) {
            return (/^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(val));
        },
        checkEmail: function (val) {
            var regEx = /^[\x20-\x7e]*$/;

            if (val.match(regEx) == null || val.match(/@/) == null)
                return false
            return true;
        },
        checkDate: function (val) {
            var cvrtDate = new Date(val);
            return !isNaN(cvrtDate);
        },
        checkUrl: function (val) {
            return (/^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/).test(val);
        },
        checkDemandContent: function (val) {
            var isValid = true;
            $.map(invalidCharacters, function(c) {
                if (val.indexOf(c.trim()) != -1) {
                    isValid = false;
                }
            });

            return isValid;
        },
        notEmpty: function (el) {
            var val = el.val();
            var parent = el.parent();
            if (val === '') {
                demandValidate.addInvalidClass(el);
                demandValidate.renderMessageSection($(parent), messages.notEmpty);
                return false;
            } else {
                demandValidate.removeInvalidClass(el);
                demandValidate.removeMessageSection($(parent));
                return true;
            }
        },
        validNumber: function (el) {
            var val = el.val();
            var parent = el.parent();
            var isValid = true;
            if (val != '') {
                if (!demandValidate.checkNumber(val)) {
                    demandValidate.addInvalidClass(el);
                    demandValidate.renderMessageSection($(parent), messages.validNumber);
                    isValid = false;
                } else {
                    demandValidate.removeInvalidClass(el);
                    demandValidate.removeMessageSection($(parent));
                    isValid = true;
                }
            }
            return isValid;
        },
        validCustomer: function (el) {
            var val = el.val();
            var parent = el.parent();
            var isValid = true;
            if (val != '') {
                if (val != nonNotification && !demandValidate.checkNumber(val)) {
                    demandValidate.addInvalidClass(el);
                    demandValidate.renderMessageSection($(parent), messages.validCustomerTel);
                    isValid = false;
                } else {
                    demandValidate.removeInvalidClass(el);
                    demandValidate.removeMessageSection($(parent));
                    isValid = true;
                }
            }
            return isValid;
        },
        validEmail: function (el) {
            var val = el.val();
            var parent = el.parent();
            var isValid = true;

            if (val != '') {
                if (!demandValidate.checkEmail(val)) {
                    demandValidate.addInvalidClass(el);
                    demandValidate.renderMessageSection($(parent), messages.validEmail);
                    isValid = false;
                } else {
                    demandValidate.removeInvalidClass(el);
                    demandValidate.removeMessageSection($(parent));
                    isValid = true;
                }
            }
            return isValid;
        },
        validDate: function(el) {
            var val = el.val();
            var parent = el.parent();
            var isValid = true;

            if(val != '') {
                if (!demandValidate.checkDate(val)) {
                    demandValidate.addInvalidClass(el);
                    demandValidate.renderMessageSection($(parent), messages.validDate);
                    isValid = false;
                } else {
                    demandValidate.removeInvalidClass(el);
                    demandValidate.removeMessageSection($(parent));
                    isValid = true;
                }
            } else {
                demandValidate.removeInvalidClass(el);
                demandValidate.removeMessageSection($(parent));
            }
            return isValid;
        },
        validUrl: function (el) {
            var val = el.val();
            var parent = el.parent();
            var isValid = true;

            if(val != '') {
                if (!demandValidate.checkUrl(val)) {
                    demandValidate.addInvalidClass(el);
                    demandValidate.renderMessageSection($(parent), messages.validUrl);
                    isValid = false;
                } else {
                    demandValidate.removeInvalidClass(el);
                    demandValidate.removeMessageSection($(parent));
                    isValid = true;
                }
            } else {
                demandValidate.removeInvalidClass(el);
                demandValidate.removeMessageSection($(parent));
            }

            return isValid;
        },
        validDemandContent: function (el) {
            var val = el.val();
            var parent = el.parent();
            var isValid = true;

            if(val != '') {
                if (!demandValidate.checkDemandContent(val)) {
                    demandValidate.addInvalidClass(el);
                    demandValidate.renderMessageSection($(parent), messages.validDemandContent);
                    isValid = false;
                } else {
                    demandValidate.removeInvalidClass(el);
                    demandValidate.removeMessageSection($(parent));
                    isValid = true;
                }
            } else {
                demandValidate.removeInvalidClass(el);
                demandValidate.removeMessageSection($(parent));
            }

            return isValid;
        },
        checkContentOfCorrespond: function () {
            var val = $demandCorrespondContent.val();
            var parent = $demandCorrespondContent.parent();

            if (val == '') {
                demandValidate.addInvalidClass($demandCorrespondContent);
                demandValidate.renderMessageSection($(parent), messages.validContent);
                return false;
            }  else if(val.length > 1000) {
                demandValidate.addInvalidClass($demandCorrespondContent);
                demandValidate.renderMessageSection($(parent), messages.validMaxLengthContent);
                return false;
            } else {
                demandValidate.removeInvalidClass($demandCorrespondContent);
                demandValidate.removeMessageSection($(parent));
                return true;
            }
        }
    };

    var validate = function (form, copy) {
        var requireFields = getRequiredFields($(form));
        var requireFieldsByQuickOrderFail = getRequiredFieldsByQuickOrderFail($(form));

        $.each(requireFields, function (i, el) {
            $(el).on('change', function (e) {
                var rules = demandValidate.getRules($(el));

                if (jQuery.inArray(ruleNames.notEmpty, rules) !== -1) {
                    demandValidate.notEmpty($(el));
                }

                if (jQuery.inArray(ruleNames.validNumber, rules) !== -1) {
                    demandValidate.validNumber($(el));
                }

                if (jQuery.inArray(ruleNames.validCustomerTel, rules) !== -1) {
                    demandValidate.validCustomer($(el));
                }

                if (jQuery.inArray(ruleNames.validEmail, rules) !== -1) {
                    demandValidate.validEmail($(el));
                }

                if (jQuery.inArray(ruleNames.validDate, rules) !== -1) {
                    demandValidate.validDate($(el));
                }

                if (jQuery.inArray(ruleNames.validUrl, rules) !== -1) {
                    demandValidate.validUrl($(el));
                }

                return false;
            }).on('blur', function (e) {
                var rules = demandValidate.getRules($(el));

                if (jQuery.inArray(ruleNames.notEmpty, rules) !== -1) {
                    demandValidate.notEmpty($(el));
                }

                if (jQuery.inArray(ruleNames.validNumber, rules) !== -1) {
                    demandValidate.validNumber($(el));
                }

                if (jQuery.inArray(ruleNames.validCustomerTel, rules) !== -1) {
                    demandValidate.validCustomer($(el));
                }

                if (jQuery.inArray(ruleNames.validEmail, rules) !== -1) {
                    demandValidate.validEmail($(el));
                }

                if (jQuery.inArray(ruleNames.validDate, rules) !== -1) {
                    demandValidate.validDate($(el));
                }

                if (jQuery.inArray(ruleNames.validUrl, rules) !== -1) {
                    demandValidate.validUrl($(el));
                }

                return false;
            });
        });

        $demandCorrespondContent.on('change', function () {
            demandValidate.checkContentOfCorrespond();
            return false;
        }).on('blur', function() {
            demandValidate.checkContentOfCorrespond();
            return false;
        });

        $demandContentEl.on('change', function () {
            demandValidate.validDemandContent($demandContentEl);
            return false;
        }).on('blur', function() {
            demandValidate.validDemandContent($demandContentEl);
            return false;
        });

        $topRegistButton.on('click', function () {
            // check if element is disabled. Return false, don't processing
            if (demandValidate.checkDisabled($topRegistButton))
                return false;

            demandValidate.controlDisabled($topRegistButton, true);
            demandValidate.controlDisabled($bottomRegistButton, true);
            demandValidate.controlDisabled($quickOrderFailButton, true);

            var isValid = true,
                isEmpty = true,
                isNumber = true,
                isCustomerTel = true,
                isEmail = true,
                isDate = true,
                isUrl = true;

            $.each(requireFields, function(idx, el) {
                var rules = demandValidate.getRules($(el));

                if (jQuery.inArray(ruleNames.notEmpty, rules) !== -1) {
                    isEmpty = demandValidate.notEmpty($(el));
                    if (!isEmpty)
                        isValid = isEmpty;

                }
                if (jQuery.inArray(ruleNames.validNumber, rules) !== -1 && isValid == true) {
                    isNumber = demandValidate.validNumber($(el));
                    if (!isNumber)
                        isValid = isNumber;
                }

                if (jQuery.inArray(ruleNames.validCustomerTel, rules) !== -1 && isValid == true) {
                    isCustomerTel = demandValidate.validCustomer($(el));
                    if (!isCustomerTel)
                        isValid = isCustomerTel;
                }

                if (jQuery.inArray(ruleNames.validEmail, rules) !== -1 && isValid == true) {
                    isEmail = demandValidate.validEmail($(el));
                    if (!isEmail)
                        isValid = isEmail;
                }
                if (jQuery.inArray(ruleNames.validDate, rules) !== -1 && isValid == true) {
                    isDate = demandValidate.validDate($(el));
                    if (!isDate)
                        isValid = isDate;
                }
                if (jQuery.inArray(ruleNames.validUrl, rules) !== -1 && isValid == true) {
                    isUrl = demandValidate.validUrl($(el));
                    if (!isUrl)
                        isValid = isUrl;
                }
            });

            var isValidDemandContent = demandValidate.validDemandContent($demandContentEl);

            if (!isValidDemandContent)
                isValid = isValidDemandContent;
            if (!copy || typeof copy == 'undefined') {

                var isContentEmpty = demandValidate.checkContentOfCorrespond();

                if (!isContentEmpty)
                    isValid = isContentEmpty;
            }

            if(isValid) {
                $(form).submit();
                return true;
            }

            // if have validations
            demandValidate.controlDisabled($topRegistButton, false);
            demandValidate.controlDisabled($bottomRegistButton, false);
            demandValidate.controlDisabled($quickOrderFailButton, false);

            return false;
        });

        $bottomRegistButton.on('click', function () {

            // if element is disabled, don't process
            if (demandValidate.checkDisabled($bottomRegistButton))
                return false;

            demandValidate.controlDisabled($bottomRegistButton, true);
            demandValidate.controlDisabled($quickOrderFailButton, true);
            demandValidate.controlDisabled($topRegistButton, true);

            var isValid = true,
                isEmpty = true,
                isNumber = true,
                isCustomerTel = true,
                isEmail = true,
                isDate = true,
                isUrl = true;

            $.each(requireFields, function(idx, el) {
                var rules = demandValidate.getRules($(el));

                if (jQuery.inArray(ruleNames.notEmpty, rules) !== -1) {
                    isEmpty = demandValidate.notEmpty($(el));

                    if (!isEmpty)
                        isValid = isEmpty;

                }
                if (jQuery.inArray(ruleNames.validNumber, rules) !== -1) {
                    isNumber = demandValidate.validNumber($(el));

                    if (!isNumber)
                        isValid = isNumber;
                }
                if (jQuery.inArray(ruleNames.validCustomerTel, rules) !== -1) {
                    isCustomerTel = demandValidate.validCustomer($(el));
                    if (!isCustomerTel)
                        isValid = isCustomerTel;
                }
                if (jQuery.inArray(ruleNames.validEmail, rules) !== -1) {
                    isEmail = demandValidate.validEmail($(el));
                    if (!isEmail)
                        isValid = isEmail;
                }
                if (jQuery.inArray(ruleNames.validDate, rules) !== -1) {
                    isDate = demandValidate.validDate($(el));
                    if (!isDate)
                        isValid = isDate;
                }
                if (jQuery.inArray(ruleNames.validUrl, rules) !== -1) {
                    isUrl = demandValidate.validUrl($(el));
                    if (!isUrl)
                        isValid = isUrl;
                }
            });
            var isValidDemandContent = demandValidate.validDemandContent($demandContentEl);

            if (!isValidDemandContent)
                isValid = isValidDemandContent;

            if (!copy || typeof copy == 'undefined') {
                var isContentEmpty = demandValidate.checkContentOfCorrespond();

                if (!isContentEmpty)
                    isValid = isContentEmpty;
            }


            if(isValid) {
                $(form).submit();
                return true;
            }


            // if have validations. Remove disabled attribute
            demandValidate.controlDisabled($bottomRegistButton, false);
            demandValidate.controlDisabled($quickOrderFailButton, false);
            demandValidate.controlDisabled($topRegistButton, false);

            return false;

        });

        $quickOrderFailButton.on('click', function () {

            if (demandValidate.checkDisabled($quickOrderFailButton))
                return false;

            demandValidate.controlDisabled($bottomRegistButton, true);
            demandValidate.controlDisabled($quickOrderFailButton, true);
            demandValidate.controlDisabled($topRegistButton, true);

            var quickOrderFail = $quickOrderFailReasonEl.val(),
                genreId = $genreEl.find('option:selected').val(),
                categoryId = $categoryEl.val();

            if(quickOrderFail == '' || genreId == '' || categoryId == ''){
                demandValidate.showDialog('入力エラー', 'ワンタッチ失注登録理由,ジャンル,カテゴリを選択して下さい。');

                // remove disabled attribute
                demandValidate.controlDisabled($bottomRegistButton, false);
                demandValidate.controlDisabled($quickOrderFailButton, false);
                demandValidate.controlDisabled($topRegistButton, false);

                return false;
            }
            $('#hidQuickOrderFail').val('1');

            var isValid = true,
                isEmpty = true,
                isNumber = true,
                isCustomerTel = true,
                isEmail = true,
                isDate = true,
                isUrl = true;

            $.each(requireFieldsByQuickOrderFail, function(idx, el) {
                var rules = demandValidate.getRules($(el));

                if (jQuery.inArray(ruleNames.notEmpty, rules) !== -1) {
                    isEmpty = demandValidate.notEmpty($(el));

                    if (!isEmpty)
                        isValid = isEmpty;

                }
                if (jQuery.inArray(ruleNames.validNumber, rules) !== -1) {
                    isNumber = demandValidate.validNumber($(el));

                    if (!isNumber)
                        isValid = isNumber;
                }
                if (jQuery.inArray(ruleNames.validCustomerTel, rules) !== -1) {
                    isCustomerTel = demandValidate.validCustomer($(el));
                    if (!isCustomerTel)
                        isValid = isCustomerTel;
                }
                if (jQuery.inArray(ruleNames.validEmail, rules) !== -1) {
                    isEmail = demandValidate.validEmail($(el));
                    if (!isEmail)
                        isValid = isEmail;
                }
                if (jQuery.inArray(ruleNames.validDate, rules) !== -1) {
                    isDate = demandValidate.validDate($(el));
                    if (!isDate)
                        isValid = isDate;
                }
                if (jQuery.inArray(ruleNames.validUrl, rules) !== -1) {
                    isUrl = demandValidate.validUrl($(el));
                    if (!isUrl)
                        isValid = isUrl;
                }
            });

            if(isValid) {
                $(form).submit();
                return true;
            }

            // remove disabled attribute
            demandValidate.controlDisabled($bottomRegistButton, false);
            demandValidate.controlDisabled($quickOrderFailButton, false);
            demandValidate.controlDisabled($topRegistButton, false);

            return false;
        });
    };

    return {
        validate: validate
    }
})();
