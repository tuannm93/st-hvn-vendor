var AffiliationDetail = function () {
    var clickedSubmit = false;
    var pageData = $("#page-data");
    var postcodeFill = $("#postcode_fill");
    var representativePostcodeFill = $("#representative_postcode_fill");
    var commissionCategoryFilter = $("#commission-category-filter");
    var rightButton = $("input[name=right]");
    var leftButton = $("input[name=left]");
    var s1Area = $("#s1");
    var s2Area = $("#s2");
    var affiliationHistoryInput = $(".affiliation_history_input");
    var $historyPopup = $("#historyModal");
    var $areaPopup = $("#targertAreaModal");
    var formAffiliationHistory = $("#formAffiliationHistory");
    var popupResponders = $("#popup-responders");
    var popupContent = $("#popup-content");
    var popupDatetime = $("#popup-datetime");
    var cancelPopup = $("#cancel-history");
    var buttonArea = $("#area-modal");
    var progress = new progressCommon();
    var registerButton = $("#register_button");
    var formAffiliation = $("#formAffiliation");
    var returnLink = $('.return-link');
    var confirmPopup = new popupCommon(1, {
        msg: msg,
        confirm: confirm,
        close: cancel
    });
    var confirmPopupHtml = confirmPopup.renderView();
    var $ppConfirm = $(confirmPopupHtml);
    var closeArea = $("#close-area");

    function deleteConfirm() {
        $('#deleteButton').click(function () {
            $ppConfirm.modal('show');
        });

        $ppConfirm.find('.st-pp-confirm').on('click', function (e) {
            $('#delete-affiliation').submit();
        });
    }

    var controlPopup = function (popup, isShow) {
        if (isShow) {
            popup.modal('show');
        } else {
            popup.modal('hide');
        }
    };

    /**
     * List function for action set stop_category
     */

    function rightClick() {
        rightButton.on('click', function () {
            move("s1", "s2");
            s2Area.html(
                $('#s2 > option').sort(function (a, b) {
                    return parseInt($(a).attr('value'), 10) - parseInt($(b).attr('value'), 10);
                })
            );
            // setMutilSelect();
        });
    }

    function leftClick() {
        leftButton.on('click', function () {
            move("s2", "s1");
            s1Area.html(
                $('#s1 > option').sort(function (a, b) {
                    return parseInt($(a).attr('value'), 10) - parseInt($(b).attr('value'), 10);
                })
            );
            // setMutilSelect();
        });
    }

    function move(_this, target) {
        $("select[id=" + _this + "] option:selected").each(
            function () {
                $("select[id=" + target + "]").append($(this).clone());
                $(this).remove();
            }
        );
    }

    function setMutilSelect() {
        $('#s2 option').prop("selected", true);
    }

    function refineCommissionCategoryList() {
        var baseList = [];

        var listElement = s1Area.children();

        for (var i = 0; i < listElement.length; i++) {
            baseList[listElement.eq(i).val()] = listElement.eq(i).text();
        }

        commissionCategoryFilter.keyup(function () {
            var selectboxHtml = "";

            var suggestText = $(this).val();

            var selectedValues = [];
            var listSelectedElement = s2Area.children();
            for (var i = 0; i < listSelectedElement.length; i++) {
                selectedValues.push(listSelectedElement.eq(i).val());
            }

            if (suggestText) {
                var suggestRe = new RegExp(".*" + suggestText + ".*");
                for (var key in baseList) {
                    var isSelectedValue1 = $.inArray(key, selectedValues);
                    if (baseList[key].match(suggestRe) && isSelectedValue1 < 0) {
                        selectboxHtml += '<option value="' + key + '">' + baseList[key] + '</option>'
                    }
                }
            } else {
                for (var key in baseList) {
                    var isSelectedValue2 = $.inArray(key, selectedValues);
                    if (isSelectedValue2 < 0) {
                        selectboxHtml += '<option value="' + key + '">' + baseList[key] + '</option>'
                    }
                }
            }

            s1Area.html(selectboxHtml);
        });

    }

    /**
     * List function for action set postcode filter
     */

    function fillPostcode() {
        postcodeFill.on('click', function () {
            setAddress('postcode', 'address1', 'address2', 'address3');
        });
    }

    function fillRepresentativePostcode() {
        representativePostcodeFill.on('click', function () {
            setAddress('representative_postcode', 'representative_address1', 'representative_address2', 'representative_address3')
        });
    }

    function setAddress(zipForm, prefForm, addressForm1, addressForm2) {

        var zipCode = $("#" + zipForm).val();
        if (zipCode.length === 0) {
            return false;
        }

        var url = pageData.data("url-search-address") + "?zip=" + encodeURI(zipCode);

        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            xhr: function () {
                return progress.createXHR();
            },
            beforeSend: function () {
                progress.controlProgress(true);
            },
            complete: function () {
                progress.controlProgress(false);
            }
        }).done(function (json) {
            if (!$.isEmptyObject(json)) {
                $("#" + prefForm).val(parseInt(json.m_posts_jis_cd)).change();
                $("#" + addressForm1).val(json.address2);
                $("#" + addressForm2).val(json.address3);
            }
        }).fail(function (jXHR) {
            console.log(jXHR);
            progress.controlProgress(false);
        });
    }

    /**
     * List function for action affiliation history input
     */

    function getAffiliationHistoryInput() {
        affiliationHistoryInput.on('click', function () {
            var getHistoryUrl = $(this).data('get-url');
            var postHistoryUrl = $(this).data('post-url');

            $.ajax({
                type: "GET",
                url: getHistoryUrl,
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function (xhr) {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (data) {
                    popupResponders.val(data.responders);
                    popupContent.val(data.corresponding_contens);
                    popupDatetime.val(data.correspond_datetime);
                    formAffiliationHistory.attr('action', getHistoryUrl);
                    controlPopup($historyPopup, true);
                },
                error: function () {
                    console.log("Error!");
                }
            });
        });

        cancelPopup.on('click', function () {
            controlPopup($historyPopup, false);
        });

        buttonArea.on('click', function () {
            controlPopup($areaPopup, true);
        });
        closeArea.on('click', function () {
            controlPopup($areaPopup, false);
        });
    }

    function submitForm() {
        registerButton.on('click', function () {
            setMutilSelect();
            clickedSubmit = true;
            handleCoordination();
            if ($('#formAffiliation').valid()) {
                formAffiliation.submit();
            }
        })
    }

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
        $('#contactable_support24hour').click(function () {
            if ($("#contactable_support24hour").prop('checked')) {
                $("#contactable_time_other").prop('checked', false);
                $('#contactable_time_from').val('').prop('disabled', true);
                $('#contactable_time_to').val('').prop('disabled', true);
            }
        });

        $("#contactable_time_other").click(function () {
            if ($("#contactable_time_other").prop('checked')) {
                $("#contactable_support24hour").prop('checked', false);
                $('#contactable_time_from').prop('disabled', false);
                $('#contactable_time_to').prop('disabled', false);
            }
        });

        $("#support24hour").click(function () {
            if ($("#support24hour").prop('checked')) {
                $("#available_time_other").prop('checked', false);
                $('#available_time_from').val('').prop('disabled', true);
                $('#available_time_to').val('').prop('disabled', true);
            }
        });

        $("#available_time_other").click(function () {
            if ($("#available_time_other").prop('checked')) {
                $("#support24hour").prop('checked', false);
                $('#available_time_from').val('').prop('disabled', false);
                $('#available_time_to').val('').prop('disabled', false);
            }
        });
    }

    /**
     * Ajax back link
     */

    function backToAffiliationSearch() {
        returnLink.on('click', function () {
            var urlIndex = returnLink.data("url");
            var urlSession = returnLink.data("session");

            $.ajax({
                type: "GET",
                url: urlSession,
                cache: false,
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function (xhr) {
                    progress.controlProgress(true);
                },
            }).done(function (data) {
                if (data['result'] === true) {
                    $(location).attr('href', urlIndex);
                }
            }).fail(function (jXHR, textStatus) {
                progress.controlProgress(false);
                console.log(jXHR);
            });
        });
    }

    function handleCoordination() {
        var selectedText = $('#coordination_method option:selected').text(),
            mailToggle = $('#mcorp-mail-toggle'),
            fax = $('#mcorps_fax'),
            selectMail = selectedText.indexOf('メール') === -1 ? false : true,
            selectFax = selectedText.indexOf('FAX') === -1 ? false : true;

        if (selectMail && selectFax) {
            mailToggle.attr('data-group-fill-required', true);
            $('#mailaddress_pc, #mailaddress_mobile').data('rule-required', 'true');

            fax.data('rule-required', 'true');
            fax.rules( "add", {
                required: true
            });
        } else if (selectMail) {
            mailToggle.attr('data-group-fill-required', true);
            $('#mailaddress_pc, #mailaddress_mobile').data('rule-required', 'true');
            fax.data('rule-required', 'false');
            fax.rules("remove", "required");
            fax.removeClass('is-invalid');
            if (clickedSubmit) {
                fax.addClass('is-valid');
            }
        } else if (selectFax) {
            mailToggle.removeAttr('data-group-fill-required');
            $('#mailaddress_pc, #mailaddress_mobile').data('rule-required', 'false');
            $('#mailaddress_pc, #mailaddress_mobile').removeClass('is-invalid');
            if (clickedSubmit) {
                $('#mailaddress_pc, #mailaddress_mobile').addClass('is-valid');
            }
            $('#mcorp-mail-toggle-error-container').empty();

            fax.data('rule-required', 'true');
            fax.rules( "add", {
                required: true
            });
        } else {
            if ($('#coordination_method').val() == '') {
                mailToggle.attr('data-group-fill-required', true);
                $('#mailaddress_pc, #mailaddress_mobile').data('rule-required', 'true');

                fax.data('rule-required', 'true');
                fax.rules( "add", {
                    required: true
                });
            } else {
                mailToggle.removeAttr('data-group-fill-required');
                $('#mailaddress_pc, #mailaddress_mobile').data('rule-required', 'false');
                $('#mailaddress_pc, #mailaddress_mobile').removeClass('is-invalid');
                if (clickedSubmit) {
                    $('#mailaddress_pc, #mailaddress_mobile').addClass('is-valid');
                }
                $('#mcorp-mail-toggle-error-container').empty();
                fax.data('rule-required', 'false');
                fax.rules("remove", "required");
                fax.removeClass('is-invalid');
                if (clickedSubmit) {
                    fax.addClass('is-valid');
                }
            }
        }
    }

    function readyCoordinationValidation() {
        $(document).ready(handleCoordination());
        $(document).on('change', '#coordination_method', function () {
            handleCoordination();
        });
    }

    $('#data_m_corps_responsibility_sei').on('keyup blur', function(){
        let selected = $(this);
        setTimeout(function() {
            if (selected.hasClass('is-invalid')) {
                $('#pseudo-el-data_m_corps_responsibility_mei').removeClass('d-none');
            } else {
                $('#pseudo-el-data_m_corps_responsibility_mei').addClass('d-none');
            }
        }, 10);
    });
    /**
     * Set function
     */
    function init() {
        refineCommissionCategoryList();
        rightClick();
        leftClick();
        fillPostcode();
        fillRepresentativePostcode();
        getAffiliationHistoryInput();
        submitForm();
        holidayInit();
        contactTimeInit();
        backToAffiliationSearch();
        deleteConfirm();
        readyCoordinationValidation();
    }

    return {
        init: init
    }
}();
