var ProgressManagementAdminDemandDetail = function() {
    var createMessageBlock = function (msg) {
        var html = '<div class="font-weight-bold rounded text--white bar-success px-3 py-2 mb-4" role="alert" id="success-bar-2">';
        html += msg;
        html += "</div>";
        return html;
    };
    return {
        //main function to initiate the module
        init: function() {
            var lockId = "lockId";
            // 画面操作を無効する
            initPseudoScrollBar();
            lockScreen(lockId);

            $('.datepicker_limit').datepicker({
                maxDate: 0
            });
            $('.totalCost').on({
                'keyup': function() {
                    var exclude = $(this).val();
                    var td = $(this).parents("td");
                    if ($.isNumeric(exclude)) {
                        var tax = $('#consTax').val(); // 消費税率

                        var include = parseInt(exclude) + Math.round((exclude * tax) / 100);

                        $(td).find(".totalCostTaxInclude").val(include);
                    } else {
                        $(td).find(".totalCostTaxInclude").val('');
                    }
                },
                'change': function(a, flg) {
                    var exclude = $(this).val();
                    var td = $(this).parents("td");
                    if ($.isNumeric(exclude)) {
                        var tax = $('#consTax').val(); // 消費税率

                        var include = parseInt(exclude) + Math.round((exclude * tax) / 100);
                        $(td).find(".totalCostTaxInclude").val(include);

                    } else {
                        if ($(this).val() != "") {
                            alert('施工金額(税抜)には半角数字を入力してください');
                            $(this).val("");
                            $(td).find(".totalCostTaxInclude").val('');


                        }
                    }

                    var tr = $(this).parents("tr");
                    $(tr).find('select.update.status').trigger('change', [true]);
                }
            });

            //案件ID変更
            $(".addDemandId").change(function() {
                if (!$.isNumeric($(this).val())) {
                    alert('案件番号には半角数字を入力してください');
                    $(this).val("");
                }
            });

            //変更フラグ変更
            $('.diff').change(function() {
                var tr = $(this).parents("tr");
                if ($(this).val() == '1') {
                    $(this).addClass('field-require');
                }
                if ($(this).val() == "2") {
                    $(tr).find(".update.failReason option:selected").attr("selected", false);
                    $(tr).find(".update.status option:selected").attr("selected", false);
                    $(tr).find(".update.status").val($(tr).attr('orgStatus'));
                    $(tr).find(".update.status option").each(function(i, elem) {
                        if ($(elem).val() == $(tr).attr('orgStatus')) {
                            $(elem).attr('selected', true);
                            $(tr).find("select.update.status").val($(elem).val());
                            return false;
                        }
                    })

                    $(tr).find(".update").attr('disabled', true);
                    $(tr).find('input[disabled]').css({ background: "#EEE", border: "solid 1px #aaa" });
                    $(tr).find('select.update').css({ background: "#EEE" });
                    $(tr).find("input[disabled]").val("");
                    $(tr).find(".totalCostTaxInclude").val("");
                    $(this).css({ backgroundColor: "#FFF" });
                    $(tr).find('.field-require').each(function(k, v) {
                        $(v).removeClass('field-require');
                    });
                } else if ($(this).val() == "3") {
                    $(tr).find(".update").attr('disabled', false);
                    $(tr).find(".update").each(function(i, e) {
                        if ($(e).val() == "" && !$(e).hasClass('totalCostTaxInclude')) {
                            $(e).css({ background: "rgb(255, 204, 204)", border: "solid 1px #aaa" });
                            $(e).addClass('field-require');
                        } else {
                            $(e).css({ background: "#FFF" });
                            $(e).removeClass('field-require');
                        }
                        if ($(e).hasClass('totalCostTaxInclude')) {
                            $(e).attr('disabled', 'disabled');
                            $(e).css({ background: "#EEE", border: "solid 1px #aaa" });
                        }
                    });

                    var orgStatus = $(tr).attr('orgStatus');

                    $(tr).find("select.update.status").find("option").each(function(i2, e2) {
                        if ($(e2).val() == orgStatus || $(e2).val() == "") {
                            if(orgStatus != 3)
                            $(e2).attr('disabled', 'disabled');
                        }
                        if ($(e2).attr('selected') == 'selected') $(e2).attr('selected', false);

                        if (orgStatus == 3) {
                            var a = $(e2).val();
                            //失注
                            if ($(e2).val() == 4) {
                                $(e2).attr('selected', true);
                                $(tr).find("select.update.status").val(4);
                                $(tr).find("select.update.status").css({ backgroundColor: "#FFF" });
                                //$(tr).find('select.update.status').trigger('change',[true]);
                            }

                        } else {
                            //施工完了
                            if ($(e2).val() == 3) {
                                $(e2).attr('selected', true);
                                $(tr).find("select.update.status").val(3);
                                $(tr).find("select.update.status").css({ backgroundColor: "#FFF" });
                                //$(tr).find('select.update.status').trigger('change',[true]);
                            }
                        }
                    });
                    $(tr).find('select.update.status').trigger('change', [true]);
                    $(this).css({ backgroundColor: "#FFF" });

                } else if ($(this).val() == "1") {
                    $(tr).find(".update").attr('disabled', false);
                    $(tr).find(".totalCostTaxInclude").attr('disabled', true);
                    $(tr).find(".update").css({ background: "#FFF" });
                    $(this).css({ backgroundColor: "rgb(255, 204, 204)" });
                }

            });

            //失注理由変更
            $("select.update.failReason").change(function() {
                var status = $(this).parents("tr").find("select.update.status").val()
                if (status == "4") {
                    if ($(this).val() != "") {
                        $(this).css({ background: "#FFF" });
                    } else {
                        $(this).css({ background: "rgb(255, 204, 204)" });
                    }
                }
            });

            //案件追加ボタンクリック
            $('#addDemandButton').click(function() {

                $('#addTable_body tr.addRow').each(function(i, elem) {
                    if ($(elem).css('display') == 'none') {
                        $(elem).css('display', '');
                        return false;
                    }
                });
            });

            //案件削除ボタンクリック
            $('#removeDemandButton').click(function() {

                var addRow = $('#addTable_body tr.addRow').get().reverse();

                $.each(addRow, function(i, elem) {
                    if ($(elem).css('display') == "table-row") {
                        //非表示と初期化
                        $(elem).css('display', 'none');
                        $(elem).find("input[type='text']").val('');
                        $(elem).find("option:selected").attr("selected", false);
                        $(elem).find('.addComment').val('');
                        $(elem).find('.addDemandType').prop('checked', false);
                        return false;
                    }
                });
            });
            // 施工完了日のキーボード入力不可
            $('.datepicker_limit').keydown(function(event) {
                return false;
            });


            //施工完了日の変更
            $('.datepicker_limit').change(function(i, e) {
                var tr = $(this).parents("tr");
                $(tr).find('select.update.status').trigger('change', [true]);
            });

            //取次ステータス変更処理
            $('select.update.status').change(function(a, flg) {

                var tr = $(this).parents("tr");
                if ($(this).val() == 1 || $(this).val() == 2) {
                    //進行中
                    $(tr).find(".update.failReason option:selected").attr("selected", false);
                    $(tr).find("input.update").attr('disabled', true);
                    $(tr).find("input.update").removeClass('field-require');;
                    $(tr).find('input[disabled]').css({ background: "#EEE", border: "solid 1px #aaa" });
                    $(tr).find('select.failReason').css({ background: "#EEE" });
                    $(tr).find('select.failReason').attr('disabled', true);
                    $(tr).find('select.failReason').removeClass('field-require');

                    $(tr).find("input[disabled]").val("");
                    $(tr).find(".totalCostTaxInclude").val("");
                    $(tr).find("select.failReason").val('');

                } else if ($(this).val() == 3) {
                    //施工完了
                    if ($(tr).find(".totalCost").val() == "") {
                        $(tr).find(".totalCost").css({ background: "rgb(255, 204, 204)", border: "solid 1px #aaa" });
                        $(tr).find(".totalCost").addClass('field-require');
                    } else {
                        $(tr).find(".totalCost").css({ background: "#FFF" });
                        $(tr).find(".totalCost").removeClass('field-require');
                    }

                    $(tr).find(".totalCost").attr("disabled", false);
                    //$(tr).find(".totalCostTaxInclude").css({background: "#EEE"});
                    $(tr).find('input[disabled]').css({ background: "#EEE", border: "solid 1px #aaa" });

                    if ($(tr).find(".datepicker_limit").val() == "") {
                        $(tr).find(".datepicker_limit").css({ background: "rgb(255, 204, 204)", border: "solid 1px #aaa" });
                        $(tr).find(".datepicker_limit").addClass('field-require');
                    } else {
                        $(tr).find(".datepicker_limit").css({ background: "#FFF" });
                        $(tr).find(".datepicker_limit").removeClass('field-require');
                    }

                    $(tr).find(".datepicker_limit").attr("disabled", false);
                    $(tr).find(".update.failReason option:selected").attr("selected", false);
                    $(tr).find("select.failReason").attr('disabled', true);
                    $(tr).find("select.failReason").removeClass('field-require');
                    $(tr).find('select.failReason').css({ background: "#EEE" });
                    $(tr).find("select.failReason").val('');

                } else if ($(this).val() == 4) {
                    //失注
                    //$(tr).find(".totalCost").css({background: "#EEE"});
                    $(tr).find(".totalCost").attr("disabled", true);
                    $(tr).find(".totalCost").removeClass('field-require');;
                    $(tr).find(".totalCost").val("");
                    $(tr).find(".totalCostTaxInclude").val("");
                    $(tr).find('input[disabled]').css({ background: "#EEE", border: "solid 1px #aaa" });

                    if ($(tr).find(".datepicker_limit").val() == "") {
                        $(tr).find(".datepicker_limit").css({ background: "rgb(255, 204, 204)", border: "solid 1px #aaa" });
                        $(tr).find(".datepicker_limit").addClass('field-require');
                    } else {
                        $(tr).find(".datepicker_limit").css({ background: "#FFF" });
                        $(tr).find(".datepicker_limit").removeClass('field-require');
                    }

                    $(tr).find(".datepicker_limit").attr("disabled", false);
                    //$(tr).find(".update.failReason option:selected").attr("selected",false);
                    $(tr).find("select.failReason").attr('disabled', false);

                    if ($(tr).find("select.failReason").val() == "" || $(tr).find("select.failReason").val() == null) {
                        $(tr).find('select.failReason').css({ background: "rgb(255, 204, 204)", border: "solid 1px #aaa" });
                        $(tr).find('select.failReason').addClass('field-require');
                    } else {
                        $(tr).find('select.failReason').css({ background: "#FFF" });
                        $(tr).find('select.failReason').removeClass('field-require');
                    }

                } else if ($(this).val() == "") {
                    $(tr).find(".update").css({ background: "#FFF" });
                    $(tr).find(".update").attr("disabled", false);
                    $(this).addClass('field-require');

                }
            });

            //新規案件送信ボタンクリック
            $('#addDemandSubmit').click(function() {
                let pData = [];
                let ret = true;
                $('tr.addRow:visible').each(function(i) {
                    let tmpObj = {};
                    if (hiddenStatus == '') {

                        if ($(this).find('#demand_id_update' + i).val() != '' ||
                            $(this).find('#customer_update' + i).val() != '' ||
                            $(this).find('#category_name_update' + i).val() != '' ||
                            $(this).find('#commission_status_update' + i).val() != '' ||
                            $(this).find('#complete_date_update' + i).val() != '' ||
                            $(this).find('#construction_price_tax_exclude_update' + i).val() != '' ||
                            $(this).find('#comment_update' + i).val() != '') {

                            tmpObj.demand_id_update = $(this).find('#demand_id_update' + i).val();
                            tmpObj.customer_name_update = $(this).find('#customer_update' + i).val();
                            tmpObj.category_name_update = $(this).find('#category_name_update' + i).val();
                            tmpObj.commission_status_update = $(this).find('#commission_status_update' + i).val();
                            tmpObj.complete_date_update = $(this).find('#complete_date_update' + i).val();
                            tmpObj.construction_price_tax_exclude_update = $(this).find('#construction_price_tax_exclude_update' + i).val();
                            tmpObj.demand_type_update = $(this).find('.addDemandType:checked').val();
                            tmpObj.comment_update = $(this).find('#comment_update' + i).val();
                            tmpObj.sequence = i;
                            tmpObj.display = 1;
                            if ($(this).attr('addDemanId').length > 0) {
                                tmpObj.id = $(this).attr('addDemanId');
                            }
                            pData.push(tmpObj);

                            let demand_type = $(this).find('#demand_type_update' + i);
                            if (!demand_type.length) {
                                if ($(this).find('.addDemandType:checked').val() == undefined) {
                                    ret = false;
                                    return false;
                                }
                            }
                        }
                    } else {
                        ret = true;
                        if ($(this).attr('dId').length > 0) {
                            tmpObj.demand_id_update = $(this).attr('dId');
                            tmpObj.comment_update = $(this).find('#comment_update' + i).val();
                            tmpObj.display = 1;
                            if ($(this).attr('addDemanId').length > 0) {
                                tmpObj.id = $(this).attr('addDemanId');
                            }
                            pData.push(tmpObj);
                        }
                    }


                });
                if (!ret) {
                    alert('未選択の案件属性がございます。すべての案件属性を選択してください。');
                    return;
                }
                //submit data
                $.ajax({
                    url: window.location.href,
                    data: { pData },
                    type: 'POST',
                    success: function(data) {
                        $(window).scrollTop(0);
                        window.location.reload();
                    },
                    error: function() {
                        $(window).scrollTop(0);
                        window.location.reload();
                    }
                })
            });

            //既存案件一括更新ボタンクリック
            $('#updateAllButton').click(function() {
                if ($('.field-require').length > 0) {
                    alert('未選択の変更がございます。すべての変更を選択してください');
                    return;
                }

                let pData = [];
                let btn = $(this);
                btn.prop('disabled', true);
                $('.pDemanInfoRow').each(function(k, v) {
                    let pDemanInfoId = $(v).attr('dataid');
                    let idata = {};
                    idata.pId = pDemanInfoId;
                    idata.diff_flg = $('#diff_flg_' + pDemanInfoId).val();
                    idata.commission_status_update = $('#commission_status_update_' + pDemanInfoId).val();
                    idata.complete_date_update = $('#complete_date_update_' + pDemanInfoId).val();
                    idata.construction_price_tax_exclude_update = $('#construction_price_tax_exclude_update_' + pDemanInfoId).val();
                    //idata.construction_price_tax_include_update = $('#construction_price_tax_include_update_' + pDemanInfoId).val();
                    idata.commission_order_fail_reason_update = $('#commission_order_fail_reason_update_' + pDemanInfoId).val();
                    idata.comment_update = $('#comment_update_' + pDemanInfoId).val();

                    pData.push(idata);

                });
                if (pData.length > 0) {
                    $.ajax({
                        url: window.location.origin + '/progress_management/progress_demand_info/pDemand/multipleUpdate',
                        type: 'POST',
                        data: { pData },
                        success: function(data) {
                            $(window).scrollTop(0);
                            window.location.reload();
                        },
                        error: function() {
                            $(window).scrollTop(0);
                            window.location.reload();
                        }
                    })
                    return;
                } else {
                    $(document).find("div#success-bar-2").remove();
                    $('#barSuccess').after(createMessageBlock('案件の更新に成功しました'));
                    $(window).scrollTop(0);
                    btn.prop('disabled', false);
                    return;
                }

            });

            $('.updateButton').click(function() {

                var tr = $(this).parents("tr");
                if ($(tr).find('.field-require').length > 0) {
                    alert('未選択の変更がございます。すべての変更を選択してください');
                    return;
                }

                let idata = {};
                let pDemanInfoId = $(this).attr('dId');
                let btn = $(this);
                btn.prop('disabled', true);
                idata.diff_flg = $('#diff_flg_' + pDemanInfoId).val();
                idata.commission_status_update = $('#commission_status_update_' + pDemanInfoId).val();
                idata.complete_date_update = $('#complete_date_update_' + pDemanInfoId).val();
                idata.construction_price_tax_exclude_update = $('#construction_price_tax_exclude_update_' + pDemanInfoId).val();
                //idata.construction_price_tax_include_update = $('#construction_price_tax_include_update_' + pDemanInfoId).val();
                idata.commission_order_fail_reason_update = $('#commission_order_fail_reason_update_' + pDemanInfoId).val();
                idata.comment_update = $('#comment_update_' + pDemanInfoId).val();
                $.ajax({
                    url: '/progress_management/progress_demand_info/' + pDemanInfoId,
                    type: 'PUT',
                    data: idata,
                    success: function(data) {
                        $(window).scrollTop(0);
                        window.location.reload();

                    },
                    error: function() {
                        $(window).scrollTop(0);
                        window.location.reload();
                    }
                });
            });

            $('.reacquisitionButton').click(function() {
                $(this).prop('disabled', true);
                $.ajax({
                    url: '/progress_management/reacquisition/' + $(this).attr('dId'),
                    type: 'PUT',
                    data: {},
                    success: function(data) {
                        $(window).scrollTop(0);
                        window.location.reload();
                        $(this).removeAttr('disabled');
                    },
                    error: function() {
                        $(window).scrollTop(0);
                        $(this).removeAttr('disabled');
                    }
                })
            });


            $('.diff, .update, .txtupdate').on('change', function() {
                if ($(this).val() != $(this).attr('old')) {
                    $(this).parents('tr').addClass('changed');
                };
                if ($(this).val() != '' && $(this).hasClass('field-require')) {
                    if ($(this).hasClass('diff')) {
                        if ($(this).val() != 1) {
                            $(this).removeClass('field-require');
                        }
                    } else {
                        $(this).removeClass('field-require');
                    }
                };
            });
            $('.diff').each(function() {
                var tr = $(this).parents("tr");
                var orgStatus = $(tr).attr('orgStatus');
                $(tr).find("select.update.status").find("option").each(function(i2, e2) {
                    if ($(e2).val() == orgStatus || $(e2).val() == "") {
                        if(orgStatus != 3)
                        $(e2).attr('disabled', 'disabled');
                    }
                });
                if ($(this).val() == 3) {
                    $(this).css({ background: '#FFF' });
                    var tr = $(this).parents("tr");
                    $(tr).find(".update.status").change();
                } else {
                    $(this).trigger('change', [true]);
                }
            });

            // 画面操作を有効にする
            unlockScreen(lockId);
            /*
             * 画面操作を無効にする
             */
        }


    }
}();

function lockScreen(id) {

    /*
     * 現在画面を覆い隠すためのDIVタグを作成する
     */
    var divTag = $('<div />').attr("id", id);

    /*
     * スタイルを設定
     */
    divTag.css("z-index", "999")
        .css("position", "absolute")
        .css("top", "0px")
        .css("left", "0px")
        .css("right", "0px")
        .css("bottom", "0px")
        .css("background-color", "gray")
        .css("opacity", "0.8");

    /*
     * BODYタグに作成したDIVタグを追加
     */
    $('body').append(divTag);
}

/*
 * 画面操作無効を解除する
 */
function unlockScreen(id) {

    /*
     * 画面を覆っているタグを削除する
     */
    $("#" + id).remove();
}

function initPseudoScrollBar() {
    if ($('.custom-scroll-x').length) {
        var table_scroll_width = $('.add-pseudo-scroll-bar').width();
        var width_scroll = $('.custom-scroll-x').width();
        var table_offset_top = $('.custom-scroll-x').offset().top;

        $('.scroll-bar').css('width', table_scroll_width);
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
            if ($(window).scrollTop() + $(window).height() > table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'true') {
                $('.pseudo-scroll-bar').hide().attr('data-display', false);
            } else if ($(window).scrollTop() + $(window).height() < table_offset_top + $('.add-pseudo-scroll-bar').height() && display == 'false') {
                $('.pseudo-scroll-bar').show().attr('data-display', true);
            }
        });
    }
}
window.onbeforeunload = function() {
    window.scrollTo(0, 0);
}
