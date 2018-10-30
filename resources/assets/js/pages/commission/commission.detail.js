var CommissionDetail = function () {
    /**
     * List function for action date picker
     */
    var width_ui_state_default = $('.width-ui-state-default').width();

    $('.ui-multiselect-menu, .ui-state-default').css('min-width', width_ui_state_default);

    function registTelSupports() {
        if ($('#tel_responders').val() == '') {
            $('#error_message').html('対応者欄が未入力です。');
            errorCheckOpen('#error_check_dialog');
            return;
        }

        if ($('#tel_correspond_datetime').val() == '') {
            $('#error_message').html('電話連絡日時が未入力です。');
            errorCheckOpen('#error_check_dialog');
            return;
        }

        if ($('#tel_correspond_status').val() == 6) {
            errorCheckOpen('#visit_hope_date_dialog');
            return;
        }

        if ($('#tel_correspond_status').val() == 7 && $('#tel_order_fail_reason').val() == '') {
            $('#error_message').html('失注理由を選択してください。');
            errorCheckOpen('#error_check_dialog');
            return;
        }

        var tel_correspond_datetime = $('#tel_correspond_datetime').val();
        var created = new Date();
        var created2 = new Date(tel_correspond_datetime);
        var timeoff = ((created.getTime() - created2.getTime()) / (3600 * 1000));

        if (timeoff > 1) {
            $('#error_message_1').html('案件の情報更新は1時間以内に<br>実施して下さい');

            $('#error_message_1_close_btn').unbind('click').bind('click', function () {
                errorCheckClose('#error_check_dialog_1');
                ajax_regist_tel_supports();
            });

            errorCheckOpen('#error_check_dialog_1');
        } else {
            ajax_regist_tel_supports();
        }
    }

    function registVisitSupports() {
        if ($('#visit_responders').val() == '') {
            $('#error_message').html('対応者欄が未入力です。');
            errorCheckOpen('#error_check_dialog');
            return;
        }
        if ($('#visit_correspond_datetime').val() == '') {
            $('#error_message').html('訪問連絡日時が未入力です。');
            errorCheckOpen('#error_check_dialog');
            return;
        }
        if ($('#visit_correspond_status').val() == 6) {
            orderSupportOpen();
            return;
        }

        if ($('#visit_correspond_status').val() == 7 && $('#visit_order_fail_reason').val() == '') {
            $('#error_message').html('失注理由を選択してください。');
            errorCheckOpen('#error_check_dialog');
            return;
        }

        var visit_correspond_datetime = $('#visit_correspond_datetime').val();
        var created = new Date();
        var created2 = new Date(visit_correspond_datetime);
        var timeoff = ((created.getTime() - created2.getTime()) / (3600 * 1000));

        if (timeoff > 1) {
            $('#error_message_1').html('案件の情報更新は1時間以内に<br>実施して下さい');

            $('#error_message_1_close_btn').unbind('click').bind('click', function () {
                errorCheckClose('#error_check_dialog_1');
                ajax_regist_visit_supports();
            });

            errorCheckOpen('#error_check_dialog_1');
        } else {
            ajax_regist_visit_supports();
        }
    }

    function registOrderSupports() {
        if ($('#order_responders').val() == '') {
            $('#error_message').html('対応者欄が未入力です。');
            errorCheckOpen('#error_check_dialog');
            return;
        }

        if ($('#order_correspond_datetime').val() == '') {
            $('#error_message').html('受注対応日時が未入力です。');
            errorCheckOpen('#error_check_dialog');
            return;
        }

        if ($('#order_correspond_status').val() == 2 || $('#order_correspond_status').val() == 3) {
            orderCompletionOpen();
            return;
        }

        if ($('#order_correspond_status').val() == 4 && $('#order_order_fail_reason').val() == '') {
            $('#error_message').html('失注理由を選択してください。');
            errorCheckOpen('#error_check_dialog');
            return;
        }

        var order_correspond_datetime = $('#order_correspond_datetime').val();
        var created = new Date();
        var created2 = new Date(order_correspond_datetime);
        var timeoff = ((created.getTime() - created2.getTime()) / (3600 * 1000));

        if (timeoff > 1) {
            $('#error_message_1').html('案件の情報更新は1時間以内に<br>実施して下さい');
            $('#error_message_1_close_btn').unbind('click').bind('click', function () {
                errorCheckClose('#error_check_dialog_1');
                ajax_regist_order_supports();
            });

            errorCheckOpen('#error_check_dialog_1');
        } else {
            ajax_regist_order_supports();
        }
    }

    function errorCheckOpen(id) {
        if (id == '#visit_hope_date_dialog') {
            $('#visit_hope_error_message').hide();
            $('#visit_hope_datetime').val('');
        } else if (id == '#visit_hope_date_dialog') {
            $('#visit_hope_error_message').hide();
            $('#visit_hope_datetime').val('');
        }

        $(id).modal('show');
    }

    function errorCheckClose(id) {
        $(id).modal('hide');
    }

    function orderCompletionOpen() {
        $('#order_completion_error_message').hide();
        $('#order_completion_datetime').val('');
        $('#order_construction_price_tax_exclude').val('');
        $('#order_construction_price_tax_include').val('');
        $('#order_construction_price_tax_exclude').attr('disabled', 'disabled');
        $('#order_construction_price_tax_include').attr('disabled', 'disabled');
        $('#order_completion_dialog').modal('show');
    }

    function visitHopeDateOk() {
        if ($('#visit_hope_datetime').val() == '') {
            $('#visit_hope_error_message').html('日時が未入力です。');
            $('#visit_hope_error_message').show();
            return;
        }

        $('#visit_hope_date_dialog').modal('hide');

        ajax_regist_tel_supports();
    }

    function orderCompletionOk() {
        if ($('#order_completion_datetime').val() == '') {
            $('#order_completion_error_message').html('日時が未入力です。');
            $('#order_completion_error_message').show();
            return;
        }

        if ($('#order_construction_price_tax_exclude').val() == '') {
            $('#order_completion_error_message').html('施工金額(税抜)が未入力です。');
            $('#order_completion_error_message').show();
            return;
        }

        if ($('#order_construction_price_tax_include').val() == '') {
            $('#order_completion_error_message').html('施工金額(税込)が未入力です。');
            $('#order_completion_error_message').show();
            return;
        }

        $('#order_completion_dialog').modal('hide');

        // 受注対応登録処理
        ajax_regist_order_supports();
    }

    // 受注対応日時登録ボタン処理
    function orderSupportOk() {
        if ($('#order_support_datetime').val() == "") {
            $('#order_support_error_message').html('日時が未入力です。');
            $('#order_support_error_message').show();
            return;
        }
        // ダイアログの非表示
        $('#order_support_dialog').modal('hide');

        // 訪問対応登録処理
        ajax_regist_visit_supports();
    }

    function orderSupportOpen() {
        $('#order_support_error_message').hide();
        $('#order_support_datetime').val(''); // 受注対応日時
        $('#order_support_dialog').modal('show');
    }

    function ajax_regist_tel_supports() {
        $('#regist_tel_supports_button').attr('disabled', 'disabled');

        var commissioninfo_id = $('#commissioninfo_id').val().replace(/\u002f/g, "_-_-"); // 取次ID
        var tel_correspond_datetime = $('#tel_correspond_datetime').val().replace(/\u002f/g, "_-_-"); // 電話連絡日時
        var tel_correspond_status = $('#tel_correspond_status').val().replace(/\u002f/g, "_-_-"); // 状況
        var tel_responders = $('#tel_responders').val().replace(/\u002f/g, "_-_-"); // 対応者
        var tel_order_fail_reason = $('#tel_order_fail_reason').val().replace(/\u002f/g, "_-_-"); // 失注理由
        var tel_corresponding_contens = $('#tel_corresponding_contens').val().replace(/\u002f/g, "_-_-"); // テキスト入力欄
        var visit_hope_datetime = $('#visit_hope_datetime').val().replace(/\u002f/g, "_-_-"); // 訪問希望日時

        commissioninfo_id = commissioninfo_id.replace(/:/g, "-_-_"); // 取次ID
        tel_correspond_datetime = tel_correspond_datetime.replace(/:/g, "-_-_"); // 電話連絡日時
        tel_correspond_status = tel_correspond_status.replace(/:/g, "-_-_"); // 状況
        tel_responders = tel_responders.replace(/:/g, "-_-_"); // 対応者
        tel_order_fail_reason = tel_order_fail_reason.replace(/:/g, "-_-_"); // 失注理由
        tel_corresponding_contens = tel_corresponding_contens.replace(/:/g, "-_-_"); // テキスト入力欄
        visit_hope_datetime = visit_hope_datetime.replace(/:/g, "-_-_"); // 訪問希望日時

        if (tel_correspond_datetime == '') {
            tel_correspond_datetime = '_99999999_';
        }
        if (tel_correspond_status == '') {
            tel_correspond_status = '_99999999_';
        }
        if (tel_responders == '') {
            tel_responders = '_99999999_';
        }
        if (tel_order_fail_reason == '') {
            tel_order_fail_reason = "_99999999_";
        }
        if (tel_corresponding_contens == '') {
            tel_corresponding_contens = ' ';
        }
        if (visit_hope_datetime == '') {
            visit_hope_datetime = '_99999999_';
        }

        // Ajax処理
        var url = '/commission/regist_tel_supports/' + commissioninfo_id + '/' + tel_correspond_datetime + '/' + tel_correspond_status + '/' + tel_responders + '/' + tel_order_fail_reason + '/' + tel_corresponding_contens + '/' + visit_hope_datetime + '/';

        $.get(url, function (data) {
            $("#TelSupportsList").html(data);

            $('#tel_correspond_datetime').val(''); // 電話連絡日時
            $('#tel_correspond_status option:first').prop('selected', true); // 状況

            if ($('#user_auth').val() == 'affiliation') {
                $('#tel_responders').val(''); // 対応者
            } else {
                $('#tel_responders option:first').prop('selected', true); // 対応者
            }

            $('#tel_order_fail_reason option:first').prop('selected', true); // 失注理由
            $('#tel_corresponding_contens').val(''); // テキスト入力欄
            $('#visit_hope_datetime').val(''); // 訪問希望日時
            $('#regist_tel_supports_button').removeAttr('disabled');

            location.reload();
        }).always(function () {
            $('#regist_tel_supports_button').removeAttr('disabled');
        });

    }

    function ajax_regist_visit_supports() {
        $('#regist_visit_supports_button').attr('disabled', 'disabled');

        var commissioninfo_id = $('#commissioninfo_id').val().replace(/\u002f/g, "_-_-"); // 取次ID
        var visit_correspond_datetime = $('#visit_correspond_datetime').val().replace(/\u002f/g, "_-_-"); // 電話連絡日時
        var visit_correspond_status = $('#visit_correspond_status').val().replace(/\u002f/g, "_-_-"); // 状況
        var visit_responders = $('#visit_responders').val().replace(/\u002f/g, "_-_-"); // 対応者
        var visit_order_fail_reason = $('#visit_order_fail_reason').val().replace(/\u002f/g, "_-_-"); // 失注理由
        var visit_corresponding_contens = $('#visit_corresponding_contens').val().replace(/\u002f/g, "_-_-"); // テキスト入力欄
        var order_support_datetime = $('#order_support_datetime').val().replace(/\u002f/g, "_-_-"); // 受注対応日時

        commissioninfo_id = commissioninfo_id.replace(/:/g, "-_-_"); // 取次ID
        visit_correspond_datetime = visit_correspond_datetime.replace(/:/g, "-_-_"); // 電話連絡日時
        visit_correspond_status = visit_correspond_status.replace(/:/g, "-_-_"); // 状況
        visit_responders = visit_responders.replace(/:/g, "-_-_"); // 対応者
        visit_order_fail_reason = visit_order_fail_reason.replace(/:/g, "-_-_"); // 失注理由
        visit_corresponding_contens = visit_corresponding_contens.replace(/:/g, "-_-_"); // テキスト入力欄
        order_support_datetime = order_support_datetime.replace(/:/g, "-_-_"); // 受注対応日時

        if (visit_correspond_datetime == "") {
            visit_correspond_datetime = "_99999999_";
        }
        if (visit_correspond_status == "") {
            visit_correspond_status = "_99999999_";
        }
        if (visit_responders == "") {
            visit_responders = "_99999999_";
        }
        if (visit_order_fail_reason == "") {
            visit_order_fail_reason = "_99999999_";
        }
        if (visit_corresponding_contens == "") {
            visit_corresponding_contens = " ";
        }
        if (order_support_datetime == "") {
            order_support_datetime = "_99999999_";
        }

        // Ajax処理
        var url = '/commission/regist_visit_supports/' + commissioninfo_id + '/' + visit_correspond_datetime + '/' + visit_correspond_status + '/' + visit_responders + '/' + visit_order_fail_reason + '/' + visit_corresponding_contens + '/' + order_support_datetime + '/';

        $.get(url, function (data) {
            $('#VisitSupportsList').html(data);

            $('#visit_correspond_datetime').val(''); // 電話連絡日時
            $('#visit_correspond_status option:first').prop('selected', true); // 状況

            if ($('#user_auth').val() == 'affiliation') {
                $('#visit_responders').val(''); // 対応者
            } else {
                $('#visit_responders option:first').prop('selected', true); // 対応者
            }

            $('#visit_order_fail_reason option:first').prop('selected', true); // 失注理由
            $('#visit_corresponding_contens').val(''); // テキスト入力欄
            $('#order_support_datetime').val(''); // 受注対応日時

            $('#regist_visit_supports_button').removeAttr('disabled');

            //ページリロード
            location.reload();
        }).always(function () {
            $('#regist_visit_supports_button').removeAttr('disabled');
        });
    }

    function ajax_regist_order_supports() {
        $('#regist_order_supports_button').attr('disabled', 'disabled');

        var inputData = {
            commission_id: $('#commissioninfo_id').val(),
            correspond_datetime: $('#order_correspond_datetime').val(),
            correspond_status: $('#order_correspond_status').val(),
            responders: $('#order_responders').val(),
            order_fail_reason: $('#order_order_fail_reason').val(),
            corresponding_contens: $('#order_corresponding_contens').val(),
            completion_datetime: $('#order_completion_datetime').val(),
            construction_price_tax_exclude: $('#order_construction_price_tax_exclude').val(),
            construction_price_tax_include: $('#order_construction_price_tax_include').val()
        };

        // Ajax処理
        var url = '/commission/regist_order_supports/';
        $.get(url, inputData, function (data) {
            $('#OrderSupportsList').html(data);

            $('#regist_order_supports_button').removeAttr('disabled');

            var correspond_status = $('#order_correspond_status').val();

            if (correspond_status == 2 || correspond_status == 3) {
                location.href = location.href + '?force_submit=true';
            } else {
                location.reload();
            }
        }).always(function () {
            $('#regist_order_supports_button').removeAttr('disabled');
        });
    }

    function loadProgressInit() {
        var commissioninfo_id = $('#commissioninfo_id').val(); // 取次ID

        var urlTel = '/commission/list_supports/' + commissioninfo_id + '/tel/';
        $.get(urlTel, function (data) {
            $('#TelSupportsList').html(data);
        });

        var urlVisit = '/commission/list_supports/' + commissioninfo_id + '/visit/';
        $.get(urlVisit, function (data) {
            $('#VisitSupportsList').html(data);
        });

        var urlOrder = '/commission/list_supports/' + commissioninfo_id + '/order/';
        $.get(urlOrder, function (data) {
            $('#OrderSupportsList').html(data);
        });
    }

    function displayHistoryInput(historyId) {
        var url = '/commission/history_input/' + historyId;

        $.get(url, function (data) {
            $('#display_history_input').html(data);
            $('#history_input_dialog').modal('show');
            FormUtil.validate('#commission-history-input');
            Datetime.initForDateTimepicker();

            $('#edit').on('click', function (ev) {
                if ($('#commission-history-input').valid()) {
                    var data = $('#main').find('select, textarea, input').serialize();

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function (res) {
                            $('#history_input_dialog').modal('hide');
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            var errors = JSON.parse(xhr.responseText).errors;
                            $('#contents').find('p.form-control-feedback').remove();
                            $('#contents').find('p.box__mess').remove();

                            $.ajax({
                                url: '/ajax/get_ms_text',
                                type: 'get',
                                data: $.param(errors),
                                success: function (response) {
                                    var html_fail = '<p class="box__mess box--error">{{error}}</p>';
                                    var html_error = '<p class="form-control-feedback text-danger my-2">{{error}}</p>';

                                    $(html_fail.replace('{{error}}', response.message_failure)).insertBefore('#main');

                                    if (typeof (response.rits_responders) != 'undefined' && typeof (response.rits_responders.commissioncorresponds_msg_error_required) != "undefined") {
                                        $(html_error.replace('{{error}}', response.rits_responders.commissioncorresponds_msg_error_required)).insertAfter('#main select#rits_responders');
                                    }

                                    if (typeof (response.corresponding_contens) != 'undefined' && typeof (response.corresponding_contens.commissioncorresponds_msg_error_corresponding_contens_max) != "undefined") {
                                        $(html_error.replace('{{error}}', response.corresponding_contens.commissioncorresponds_msg_error_corresponding_contens_max)).insertAfter('#main textarea#CommissionCorrespondCorrespondingContens');
                                    }

                                    if (typeof (response.corresponding_contens) != 'undefined' && typeof (response.corresponding_contens.commissioncorresponds_msg_error_required) != "undefined") {
                                        $(html_error.replace('{{error}}', response.corresponding_contens.commissioncorresponds_msg_error_required)).insertAfter('#main textarea#CommissionCorrespondCorrespondingContens');
                                    }
                                }
                            });
                        }
                    });

                    return false;
                }
            });

            $('#cancel').click(function () {
                $('#history_input_dialog').modal('hide');
            });
            $('#correspond_datetime_popup').click(function() {
                $('#ui-datepicker-div').css('top', 50);
            })
        });
    }

    function openedAnswer() {
        if ($.trim($('#order_respond_bar').text()) != '-') {
            $('#collapseThree').addClass('show');
        } else if ($.trim($('#visit_respond_bar').text()) != '-') {
            $('#collapseTwo').addClass('show');
        } else if ($.trim($('#contact_respond_bar').text()) != '-') {
            $('#collapseOne').addClass('show');
        }
    }

    function checkOpen() {
        $('.card-header--bg-1').click(function() {
            if (jQuery.trim($("#contact_respond_bar").text()) == '-') {
                return false;
            }
        });
        $('.card-header--bg-2').click(function() {
            if (jQuery.trim($("#visit_respond_bar").text()) == '-') {
                return false;
            }
        });
        $('.card-header--bg-3').click(function() {
            if (jQuery.trim($("#order_respond_bar").text()) == '-') {
                return false;
            }
        });
    }

    function submitFormIfCorrespondingStatusIsFixed() {
        var url = location.href;
        var params1  = url.split('?');
        if(typeof params1[1] === 'undefined'){
            return;
        }
        var params2  = params1[1].split('&');
        var paramArray = [];

        for(var i = 0; i < params2.length; i++){
            var param_tmp = params2[i].split('=');
            paramArray.push(param_tmp[0]);
            paramArray[param_tmp[0]] = param_tmp[1];
        }

        if('force_submit' in paramArray){
            if (paramArray['force_submit'] == 'true'){
                $(document).ready(function() {
                    if($('input[name="data[CommissionCorrespond][responders]"]')[0]){
                        $('input[name="data[CommissionCorrespond][responders]"]').val('System');
                    }

                    $('textarea[name="data[CommissionCorrespond][corresponding_contens]"]').val('受注対応登録時保存');
                    $('<input>').attr({type: 'hidden', name: 'data[CommissionInfo][support]', value: '登録'}).appendTo('form#detail-commision');
                    $('#hidden_last_updated').val('');
                    $('#regist').trigger('click');
                });
            }
        }
    }

    /**
     * Set function
     */
    function init() {
        loadProgressInit();
        openedAnswer();
        checkOpen();
        submitFormIfCorrespondingStatusIsFixed();

        $('#site_launch_details_open, #site_launch_details_open_mobile').click(function () {
            errorCheckOpen('#site_launch_details_dialog');
        });

        $('#site_launch_details_close').click(function () {
            errorCheckClose('#site_launch_details_dialog');
        });

        $('#regist_tel_supports_button').click(function () {
            registTelSupports();
        });

        $('#error_check_close').click(function () {
            errorCheckClose('#error_check_dialog');
        });

        $('#regist_visit_supports_button').click(function () {
            registVisitSupports();
        });

        $('#regist_order_supports_button').click(function () {
            registOrderSupports();
        });

        $('#tel_support_sample').click(function () {
            errorCheckOpen('#tel_support_sample_dialog');
        });

        $('#visit_support_sample').click(function () {
            errorCheckOpen('#visit_support_sample_dialog');
        });

        $('#order_support_sample').click(function () {
            errorCheckOpen('#order_support_sample_dialog');
        });

        $('#visit_hope_date_ok').click(function () {
            visitHopeDateOk();
        });

        $('#order_completion_ok').click(function () {
            orderCompletionOk();
        });

        $('#order_support_ok').click(function () {
            orderSupportOk();
        });

        $('.history-input').click(function () {
            var historyId = $(this).attr('history_id');
            displayHistoryInput(historyId);
        });

        //val_check();
        order_fail_reason_check();

        $('.value-change').change(function () {
            var form = $('form');

            $.ajaxSetup({
                cache: false,
            });

            var datePart = $('#complete_date').val().split("/");
            var formattedValue = datePart[0] + '/' + toDoubleDigits(datePart[1]) + '/' + toDoubleDigits(datePart[2]);
            var url = '/ajax/search_tax_rate/' + formattedValue.split('/').join('-');

            $.get(url, function (data) {
                $('#tax_rate_list').html(data);
                val_check();
            });
        });
    }

    return {
        init: init
    }
}();

$(document).ready(function () {
    CommissionDetail.init();
});
