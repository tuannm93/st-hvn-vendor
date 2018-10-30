var AffDemandDetail = function() {
    var tablet_width = 678;
    var resultDiv = $('.content-ajax-tbl');
    var progress = new progressCommon();
    var datePickerLimit = $('.datepicker_limit');
    var tax = $('#consTax').val();
    var page = $('#page');

    function getData() {
        var currentPage = 1;
        $(document).on('click', '.submitForm', function(e) {
            e.preventDefault();

            if (checkRequireAll() && checkDecimal() && checkRequireRadio()&& checkLastCheckBox()) {
                // $('#demandDetailForm').submit();
                redirectUpdateConfirm(currentPage, $('#demandDetailForm').serialize(), urlRedirect)
            }
        });

        $(document).on('click', '.next', function(e) {
            $('.text-danger').css('display', 'block');

            if (checkRequireAll() && checkDecimal()) {
                e.preventDefault();
                ++currentPage;
                getPosts(currentPage, $('#demandDetailForm').serialize(), urlDemandDetail);
            } else {
                e.preventDefault();
            }
        });

        $(document).on('click', '.previous', function(e) {
            $('.text-danger').css('display', 'block');

            e.preventDefault();
            --currentPage;
            getPosts(currentPage, $('#demandDetailForm').serialize() + '&submitBack=back', urlDemandDetail);
        });

        $(document).on('click', '.submitSession', function(e) {
            e.preventDefault();
            getPosts(currentPage, $('#demandDetailForm').serialize() + '&submitSession=btnSave', urlSaveSession);
            $('.text-danger').css('display', 'none');
        });
    }

    function redirectUpdateConfirm(currentPage, data = null, url){
        page = currentPage;

        if (typeof page != 'undefined' && page > 1) {
            url = url + '?page=' + page;
        }

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            processData: false,
            xhr: function() {
                return progress.createXHR();
            },
            beforeSend: function() {
                progress.controlProgress(true);
            },
            complete: function() {

            },
            success: function(file_id) {
                progress.controlProgress(false);
                if ($.isNumeric(file_id)) {
                    window.location.replace(urlUpdateConfirm + '/' + file_id);
                }
            },
            error: function(err) {}
        });
    }

    function getPosts(currentPage, data = null, url) {
        page = currentPage;

        if (typeof page != 'undefined' && page > 1) {
            url = url + '?page=' + page;
        }

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            processData: false,
            xhr: function() {
                return progress.createXHR();
            },
            beforeSend: function() {
                progress.controlProgress(true);
            },
            complete: function() {
                progress.controlProgress(false);
            },
            success: function(data) {
                if (data != '') {
                    resultDiv.html(data);
                }
                loadTotalCostAfterRenderView();
                loadDiffAfterRenderView();
                dateTimeChecker();
                checkRequireOne();
                $(window).scrollTop(0);
                initPseudoScrollBar();
                loadStatus();
                recalNokori();
            },
            error: function(err) {}
        });
    }

    function loadTotalCostAfterRenderView() {
        $('body').find('.totalCost').each(function() {
            var exclude = $(this).val();
            var td = $(this).parents("td");
            if ($.isNumeric(exclude)) {
                var include = parseInt(exclude) + Math.round((exclude * tax) / 100);
                include = include.toLocaleString();
                $(td).find(".totalCostTaxInclude").val(include);
            }

            var tr = $(this).parents("tr");
            $(tr).find('select.update.status').trigger('change', [true]);
        });
    }

    function loadDiffAfterRenderView() {
        $('body').find('.diff').each(function() {
            var tr = $(this).parents("tr");
            // $(this).trigger('change', [true]);
            $(tr).find("select.failReason").attr('disabled', true);
            if ($(this).val() == 1) {
                $(this).addClass('field-require');
                $(tr).find('.completeDate').addClass('field-require');
                $(tr).find('.totalCost').addClass('field-require');
            } else if ($(this).val() == '2') {
                $(tr).find(".update.failReason option:selected").attr("selected", false);
                $(tr).find(".update.status option:selected").attr("selected", false);
                $(tr).find(".update.status").val($('#orgStatus').val());
                $(tr).find(".update.status option").each(function(i, elem) {
                    if ($(elem).val() == $(tr).find('#orgStatus').val()) {
                        $(elem).attr('selected', true);
                        $(tr).find("select.update.status").val($(elem).val());
                        return false;
                    }
                });

                $(tr).find(".update").attr('disabled', true);
                $(tr).find("input[disabled]").val('');
                $(tr).find(".totalCostTaxInclude").val('');
                $(tr).find('.field-require').each(function(k, v) {
                    $(v).removeClass('field-require');
                });
                $(this).removeClass('field-require');
            } else if ($(this).val() == '3') {
                $(tr).find(".update").attr('disabled', false);
                $(tr).find(".update").each(function(i, e) {
                    if ($(e).val() == '' && !$(e).hasClass('totalCostTaxInclude')) {
                        $(e).addClass('field-require');
                    } else {
                        $(e).removeClass('field-require');
                    }
                    if ($(e).hasClass('totalCostTaxInclude')) {
                        $(e).attr('disabled', 'disabled');
                    }
                });
                $(tr).find('select.update.status').trigger('change', [true]);
            }
        });
        $('#ProgDemandInfoOther_agree_flag_lastCheck').change(function() {
            $('.err-last-checkbox').prop('hidden', true);
        })
    }

    function dateTimeChecker() {
        $('.datepicker_limit').datepicker({
            maxDate: 0
        });

        $('.datepicker_limit').keydown(function (event) {
            $(this).attr('readonly', true);
            return false;
        });

        $('.datepicker_limit').keyup(function (event) {
            $(this).removeAttr('readonly');
            return false;
        });

        $('body').on('change', '.datepicker_limit', function(i, e) {
            var tr = $(this).parents("tr");
            $(tr).find('select.update.status').trigger('change', [true]);
            recalNokori();
        });
    }

    function formFormat() {
        $('.totalCost').keypress(function(e) {
            if (e.keyCode < 48 && e.keyCode != 46 || e.keyCode > 57 && e.keyCode != 46) {
                return false;
            }
            if (e.keyCode == 46 && $(this).val().includes('.')) {
                return false;
            }
        })
        $('body').on('keyup', '.totalCost', function() {
            var exclude = $(this).val();
            var td = $(this).parents("td");
            if ($.isNumeric(exclude)) {
                var include = parseInt(exclude) + Math.round((exclude * tax) / 100);
                include = include.toLocaleString();
                $(td).find(".totalCostTaxInclude").val(include);
            } else {
                $(td).find(".totalCostTaxInclude").val('');
            }
        });

        $('body').on('change', '.totalCost', function(a, flg) {
            var exclude = $(this).val();
            var td = $(this).parents("td");
            if ($.isNumeric(exclude)) {
                var include = parseInt(exclude) + Math.round((exclude * tax) / 100);
                include = include.toLocaleString();
                $(td).find(".totalCostTaxInclude").val(include);
            } else {
                if ($(this).val() !== '') {
                    alert('施工金額(税抜)には半角数字を入力してください');
                    $(this).val('');
                    $(td).find(".totalCostTaxInclude").val('');
                }
            }

            var tr = $(this).parents("tr");
            $(tr).find('select.update.status').trigger('change', [true]);
        });

        loadTotalCostAfterRenderView();

        $('body').on('change', '.addDemandId', function() {
            if (!$.isNumeric($(this).val())) {
                alert('案件番号には半角数字を入力してください');
                $(this).val('');
            }
        });

        $('body').on('change', '.diff', function() {
            var tr = $(this).parents("tr");
            if ($(this).val() == '1') {
                $(this).addClass('field-require');
            }
            if ($(this).val() == '2') {
                $(tr).find(".update.failReason option:selected").attr("selected", false);
                $(tr).find(".update.status option:selected").attr("selected", false);
                $(tr).find(".update.status").val($('#orgStatus').val());
                $(tr).find(".update.status option").each(function(i, elem) {
                    if ($(elem).val() == $(tr).find('#orgStatus').val()) {
                        $(elem).attr('selected', true);
                        $(tr).find("select.update.status").val($(elem).val());
                        return false;
                    }
                });

                $(tr).find(".update").attr('disabled', true);
                $(tr).find("input[disabled]").val('');
                $(tr).find(".totalCostTaxInclude").val('');
                $(tr).find('.field-require').each(function(k, v) {
                    $(v).removeClass('field-require');
                });
                $(this).removeClass('field-require');
            } else if ($(this).val() == '3') {
                $(tr).find(".update").attr('disabled', false);
                $(tr).find(".update").each(function(i, e) {
                    $(e).removeClass('field-require');
                    if ($(e).val() == '' && !$(e).hasClass('totalCostTaxInclude')) {
                        $(e).addClass('field-require');
                    } else {
                        $(e).removeClass('field-require');
                    }
                    if ($(e).hasClass('totalCostTaxInclude')) {
                        $(e).attr('disabled', 'disabled');
                    }
                });

                var orgStatus = $(tr).find('#orgStatus').val();

                $(tr).find("select.update.status").find("option").each(function(i2, e2) {
                    if ($(e2).val() == orgStatus || $(e2).val() == 0) {
                        if (orgStatus != 3) $(e2).attr('disabled', 'disabled');
                    }

                    if ($(e2).attr('selected') == 'selected') $(e2).attr('selected', false);

                    if (orgStatus == 3) {
                        if ($(e2).val() == 4) {
                            $(e2).attr('selected', true);
                            $(tr).find("select.update.status").val(4);
                            // $(tr).find("select.update.status").css({backgroundColor: "#FFF"});
                        }

                    } else {
                        if ($(e2).val() == 3) {
                            $(e2).attr('selected', true);
                            $(tr).find("select.update.status").val(3);
                            // $(tr).find("select.update.status").css({backgroundColor: "#FFF"});
                        }
                    }
                });
                $(tr).find('select.update.status').trigger('change', [true]);
                $(this).removeClass('field-require');
            } else if ($(this).val() == '1') {
                $(tr).find(".update").attr('disabled', false);
                $(tr).find(".totalCostTaxInclude").attr('disabled', true);
            }
            recalNokori();
        });

        loadDiffAfterRenderView();

        $('body').on('change', 'select.update.failReason', function() {
            var status = $(this).parents("tr").find("select.update.status").val();

            if (status == 4) {
                if ($(this).val() != '') {
                    $(this).removeClass('field-require');
                } else {
                    $(this).addClass('field-require');
                }
            }
            recalNokori();
        });

        $('body').on('change', 'select.update.status', function(a, flg) {
            var tr = $(this).parents("tr");
            if ($(this).val() == 1 || $(this).val() == 2) {
                $(tr).find(".update.failReason option:selected").attr("selected", false);
                $(tr).find("input.update").attr('disabled', true);
                $(tr).find("input.update").removeClass('field-require');
                $(tr).find('select.failReason').attr('disabled', true);
                $(tr).find('select.failReason').removeClass('field-require');

                $(tr).find("input[disabled]").val('');
                $(tr).find(".totalCostTaxInclude").val('');
                $(tr).find("select.failReason").val('');

            } else if ($(this).val() == 3) {
                if ($(tr).find(".totalCost").val() == '') {
                    $(tr).find(".totalCost").addClass('field-require');
                } else {
                    $(tr).find(".totalCost").removeClass('field-require');
                }

                $(tr).find(".totalCost").attr("disabled", false);

                if ($(tr).find(".datepicker_limit").val() == '') {
                    $(tr).find(".datepicker_limit").addClass('field-require');
                } else {
                    $(tr).find(".datepicker_limit").removeClass('field-require');
                }

                $(tr).find(".datepicker_limit").attr("disabled", false);
                $(tr).find(".update.failReason option:selected").attr("selected", false);
                $(tr).find("select.failReason").attr('disabled', true);
                $(tr).find("select.failReason").removeClass('field-require');
                $(tr).find("select.failReason").val('');

            } else if ($(this).val() == 4) {
                $(tr).find(".totalCost").attr("disabled", true);
                $(tr).find(".totalCost").removeClass('field-require');
                $(tr).find(".totalCost").val('');
                $(tr).find(".totalCostTaxInclude").val('');

                if ($(tr).find(".datepicker_limit").val() == '') {
                    $(tr).find(".datepicker_limit").addClass('field-require');
                } else {
                    $(tr).find(".datepicker_limit").removeClass('field-require');
                }

                $(tr).find(".datepicker_limit").attr("disabled", false);
                $(tr).find("select.failReason").attr('disabled', false);

                if ($(tr).find("select.failReason").val() == ''
                    || $(tr).find("select.failReason").val() == null
                    || $(tr).find("select.failReason").val() == 0) {
                    $(tr).find('select.failReason').addClass('field-require');
                } else {
                    $(tr).find('select.failReason').removeClass('field-require');
                }

            } else if ($(this).val() == 0) {
                $(tr).find(".datepicker_limit").removeClass('field-require');
                $(tr).find(".totalCost").removeClass('field-require');
                $(tr).find("select.failReason").removeClass('field-require');

                $(tr).find(".update").attr("disabled", false);
                $(this).addClass('field-require');
            }

            recalNokori();
        });

        // $('body').on('change', '.diff', function() {
        //     if ($(this).val() != $(this).attr('old')) {
        //         $(this).parents('tr').addClass('changed');
        //     }
        //
        //     if ($(this).val() != '' && $(this).hasClass('field-require')) {
        //         if ($(this).hasClass('diff')) {
        //             if ($(this).val() != 1) {
        //                 $(this).removeClass('field-require');
        //             }
        //         } else {
        //             $(this).removeClass('field-require');
        //         }
        //     }
        // });

        $('body').on('change', '.update', function() {
            if ($(this).val() != $(this).attr('old')) {
                $(this).parents('tr').addClass('changed');
            }

            if ($(this).val() != '' && $(this).hasClass('field-require')) {
                if ($(this).hasClass('diff')) {
                    if ($(this).val() != 1) {
                        $(this).removeClass('field-require');
                    }
                } else {
                    if ($(this).hasClass('csu') && $(this).val() == 0) {
                        return;
                    }
                    $(this).removeClass('field-require');
                }
            }
        });

        $('body').on('change', '.txtupdate', function() {
            if ($(this).val() != $(this).attr('old')) {
                $(this).parents('tr').addClass('changed');
            }

            if ($(this).val() != '' && $(this).hasClass('field-require')) {
                if ($(this).hasClass('diff')) {
                    if ($(this).val() != 1) {
                        $(this).removeClass('field-require');
                    }
                } else {
                    $(this).removeClass('field-require');
                }
            }
        });
    }

    function addFormAction() {
        $('body').on('click', '#addDemandButton', function() {
            $('body').find('.add_demand_detail').each(function(i, elem) {
                if ($(elem).css('display') === 'none') {
                    $(elem).css('display', '');
                    $(elem).find('.disp_val').val(1);
                    return false;
                }
            });

            $('body').find('#addTable_body').find('tr.addRow').each(function(i, elem) {
                if ($(elem).css('display') === 'none') {
                    $(elem).css('display', '');
                    return false;
                }
            });
        });

        $('body').on('click', '#removeDemandButton', function() {
            var addRow = $('body').find('.add_demand_detail').get().reverse();

            $.each(addRow, function(i, elem) {
                if ($(elem).css('display') === 'table-row') {
                    $(elem).css('display', 'none');
                    $(elem).find('input[type="text"]').val('');
                    $(elem).find('.disp_val').val('');
                    $(elem).find('.first-cmn-row').val(1);
                    $(elem).find('option:selected').attr('selected', false);
                    $(elem).find('.addComment').val('');
                    $(elem).find('.addDemandType').prop('checked', false);
                    return false;
                }
            });
        });

        $('body').find('.add_flg').each(function() {
            if ($(this).is(':checked')) {
                if ($(this).val() == 2) {
                    $('body').find('#addBlock').css('display', 'none');
                } else if ($(this).val() == 1) {
                    $('body').find('#addBlock').css('display', 'block');
                }
            }
        });

        $('body').on('change', '.add_flg', function() {
            if ($(this).val() == 2) {
                $('body').find('#addBlock').css('display', 'none');
            } else if ($(this).val() == 1) {
                $('body').find('#addBlock').css('display', 'block');
            }
        });
    }

    function recalNokori() {
        var nokori = 0;
        $('body').find('.formRow').each(function(index, element) {
            if ($(this).find("[class*='field-require']").length > 0) {
                nokori = nokori + 1;
            }
        });
        $('body').find('#nokoriInput').text(nokori);
    }

    function checkRequireAll() {
        var arr_list_require = [];
        $('.field-require').each(function() {
            arr_list_require.push($(this));
        })
        for (var i = 0; i < arr_list_require.length; i++) {
            if (arr_list_require[i].next('.text-danger').length) {
                arr_list_require[i].next('.text-danger').prop('hidden', false);
            } else {
                arr_list_require[i].parent().nextAll('.text-danger').prop('hidden', false);
            }
        }
        if (arr_list_require.length) {
            $('.field-require').first().focus();
            return false;
        } else {
            return true;
        }
    }

    function checkRequireOne() {
        $('.check-require').change(function() {
            var parent_field = $(this);
            parent_field.find('.text-danger, .err-decimal').each(function() {
                $(this).prop('hidden', true);
            });
            setTimeout(function() {
                var arr_list_require = [];
                parent_field.find('.field-require').each(function() {
                    arr_list_require.push($(this));
                });
                for (var i = 0; i < arr_list_require.length; i++) {
                    if (arr_list_require[i].next('.text-danger').length) {
                        arr_list_require[i].next('.text-danger').prop('hidden', false);
                    } else {
                        arr_list_require[i].parent().nextAll('.text-danger').prop('hidden', false);
                    }
                }
            }, 50);
        })
    }

    function checkDecimal() {
        var count_decimal = 0;
        var arr_list_decimal = [];
        $('.totalCost').removeClass('.hasDecimal');
        $('.check-require').find('.err-decimal').each(function() {
            $(this).prop('hidden', true);
        });
        $('.totalCost').each(function() {
            arr_list_decimal.push($(this));
        })
        for (var i = 0; i < arr_list_decimal.length; i++) {
            if (arr_list_decimal[i].val().includes('.')) {
                arr_list_decimal[i].addClass('hasDecimal');
                arr_list_decimal[i].parent().nextAll('.err-decimal').prop('hidden', false);
                count_decimal++;
            }
        }
        if (count_decimal != 0) {
            $('.hasDecimal').first().focus();
            return false;
        } else if (count_decimal == 0) {
            return true;
        }
    }

    function checkRequireRadio() {
        var count_checked = 0;
        var arr_list_radio_group = [];
        $('.err-radio-group').each(function() {
            $(this).prop('hidden', true);
        });
        $('.radio-group').each(function() {
            if ($(this).parent('.add_demand_detail').css('display') == 'table-row') {
                arr_list_radio_group.push($(this));
            }
        });
        for (var i = 0; i < arr_list_radio_group.length; i++) {
            var radio_name = arr_list_radio_group[i].find('input:radio').attr('name');
            if ($('input[name="' + radio_name + '"]:checked').length <= 0) {
                var tr = $('input[name="' + radio_name + '"]').first().parent().parent().parent();

                if(tr.is(':visible')){
                    if(tr.find($("input[name='ProgAddDemandInfo["+i+"][demand_id_update]']")).val() != ''
                        || tr.find($("input[name='ProgAddDemandInfo["+i+"][customer_name_update]']")).val() != ''
                        || tr.find($("input[name='ProgAddDemandInfo["+i+"][category_name_update]']")).val() != ''
                        || tr.find($("select[name='ProgAddDemandInfo["+i+"][commission_status_update]']")).val() != ''
                        || tr.find($("input[name='ProgAddDemandInfo["+i+"][complete_date_update]']")).val() != ''
                        || tr.find($("input[name='ProgAddDemandInfo["+i+"][construction_price_tax_exclude_update]']")).val() != ''
                        || tr.find($("textarea[name='ProgAddDemandInfo["+i+"][comment_update]']")).val() != ''){
                        $('input[name="' + radio_name + '"]').addClass('invalid-radio-group');
                        arr_list_radio_group[i].find('.err-radio-group').prop('hidden', false);
                        count_checked++;
                    }
                }
            }
        }
        if (count_checked != 0) {
            $('.invalid-radio-group').first().focus();
            return false;
        } else if (count_checked == 0) {
            return true;
        }
    }

    function initPseudoScrollBar() {
        if ($('.custom-scroll-x').length) {
            var table_scroll_width = $('.add-pseudo-scroll-bar').width();
            var table_offset_top = $('.custom-scroll-x').offset().top;
            var width_scroll = $('.custom-scroll-x').width();

            $('.scroll-bar').css('width', table_scroll_width);
            if ($(window).width() < tablet_width && $(window).scrollTop() + $(window).height() > table_offset_top) {
                $('.pseudo-scroll-bar').css('bottom', $('.fixed-button').outerHeight());
            } else {
                $('.pseudo-scroll-bar').css('bottom', 0);
            }
            $('.pseudo-scroll-bar').css('width', width_scroll).show();
            $('.pseudo-scroll-bar').scroll(function() {
                var left = Number($('.pseudo-scroll-bar').scrollLeft());
                $('.custom-scroll-x').scrollLeft(left);
            });
            $('.custom-scroll-x').scroll(function() {
                var left = Number($('.custom-scroll-x').scrollLeft());
                $('.pseudo-scroll-bar').scrollLeft(left);
            });

            $(window).on("scroll", function() {
                var display = $('.pseudo-scroll-bar').attr('data-display');
                if ($(window).width() < tablet_width) {
                    if ($(window).scrollTop() + $(window).height() > table_offset_top + $('.add-pseudo-scroll-bar').height() + $('.fixed-button').outerHeight() && display == 'true' || $(window).scrollTop() + $(window).height() < table_offset_top + $('.fixed-button').outerHeight() && display == 'true') {
                        $('.pseudo-scroll-bar').hide().attr('data-display', false);
                    } else if ($(window).scrollTop() + $(window).height() < table_offset_top + $('.add-pseudo-scroll-bar').height() + $('.fixed-button').outerHeight() && $(window).scrollTop() + $(window).height() > table_offset_top + $('.fixed-button').outerHeight() && display == 'false') {
                        $('.pseudo-scroll-bar').css('bottom', $('.fixed-button').outerHeight());
                        $('.pseudo-scroll-bar').show().attr('data-display', true);
                    }
                } else {
                    if ($(window).scrollTop() + $(window).height() > table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'true') {
                        $('.pseudo-scroll-bar').hide().attr('data-display', false);
                    } else if ($(window).scrollTop() + $(window).height() < table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'false') {
                        $('.pseudo-scroll-bar').show().attr('data-display', true);
                    }
                }
            });
        }
    }

    function checkLastCheckBox() {
        if ($('input[name="ProgDemandInfoOther[agree_flag]"]:checked').length <= 0) {
            $('.err-last-checkbox').prop('hidden', false);
            $('input[name="ProgDemandInfoOther[agree_flag]"]').focus();
            return false;
        }
        return true;
    }
    function loadStatus() {
        $('body').find('select.update.status').each(function () {
            var tr = $(this).parents("tr");
            if ($(this).val() == 1 || $(this).val() == 2) {
                $(tr).find(".update.failReason option:selected").attr("selected", false);
                $(tr).find("input.update").attr('disabled', true);
                $(tr).find("input.update").removeClass('field-require');
                $(tr).find('select.failReason').attr('disabled', true);
                $(tr).find('select.failReason').removeClass('field-require');

                $(tr).find("input[disabled]").val('');
                $(tr).find(".totalCostTaxInclude").val('');
                $(tr).find("select.failReason").val('');
            }
            else if ($(this).val() == 3) {
                // if ($(tr).find(".totalCost").val() == '') {
                //     $(tr).find(".totalCost").addClass('field-require');
                // } else {
                //     $(tr).find(".totalCost").removeClass('field-require');
                // }
                //
                // $(tr).find(".totalCost").attr("disabled", false);
                //
                // if ($(tr).find(".datepicker_limit").val() == '') {
                //     $(tr).find(".datepicker_limit").addClass('field-require');
                // } else {
                //     $(tr).find(".datepicker_limit").removeClass('field-require');
                // }
                //
                // $(tr).find(".datepicker_limit").attr("disabled", false);
                // $(tr).find(".update.failReason option:selected").attr("selected", false);
                $(tr).find("select.failReason").attr('disabled', true);
                $(tr).find("select.failReason").removeClass('field-require');
                $(tr).find("select.failReason").val('');
            }
            else if ($(this).val() == 4 && $(tr).find('.diff').val() != 2) {
                $(tr).find(".totalCost").attr("disabled", true);
                $(tr).find(".totalCost").removeClass('field-require');
                $(tr).find(".totalCost").val('');
                $(tr).find(".totalCostTaxInclude").val('');

                if ($(tr).find(".datepicker_limit").val() == '') {
                    $(tr).find(".datepicker_limit").addClass('field-require');
                } else {
                    $(tr).find(".datepicker_limit").removeClass('field-require');
                }

                $(tr).find(".datepicker_limit").attr("disabled", false);
                $(tr).find("select.failReason").attr('disabled', false);

                if ($(tr).find("select.failReason").val() == '' || $(tr).find("select.failReason").val() == null || $(tr).find("select.failReason").val() == 0) {
                    $(tr).find('select.failReason').addClass('field-require');
                } else {
                    $(tr).find('select.failReason').removeClass('field-require');
                }
            } else if ($(this).val() == 0) {
                $(this).addClass('field-require');
                $(tr).find(".datepicker_limit").removeClass('field-require');
                $(tr).find(".totalCost").removeClass('field-require');
                $(tr).find("select.failReason").removeClass('field-require');
                $(tr).find(".update").attr("disabled", false);
            }
        });
    }
    window.onbeforeunload = function() {
        window.scrollTo(0, 0);
    }
    $(document).ready(function() {
        $('.progress-management-demand-detail').parent().addClass('container-fluid').removeClass('container');
        initPseudoScrollBar();
    })
    return {
        init: function() {
            getData();
            dateTimeChecker();
            formFormat();
            addFormAction();
            checkRequireOne();
            loadStatus();
            recalNokori();
        }
    }
}();