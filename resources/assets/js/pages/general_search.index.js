var GeneralSearch = function() {
    var alertPopupType = 0,
        confirmPopupType = 1;
    var progress = new progressCommon();
    //create popup
    var initPopupHtml = function(popupType, info) {
        var corpPopup = new popupCommon(popupType, info);
        var popupHtml = corpPopup.renderView();
        return popupHtml;
    };

    var controlPopup = function(popup, isShow) {
        if (isShow) {
            popup.modal('show');
        } else {
            popup.modal('hide')
        }
    };

    var createConfirmPopup = function(msg) {
        var confirmPopup = $(initPopupHtml(confirmPopupType, { close: 'キャンセル', confirm: 'OK', msg: msg }));
        return confirmPopup;
    };

    var createAlertPopup = function(msg) {
        var alertPopup = $(initPopupHtml(alertPopupType, { close: 'OK', msg: msg }));
        return alertPopup;
    };

    var createAlertPopupOk = function(msg) {
        var alertPopup = $(initPopupHtml(alertPopupType, { close: 'OK', msg: msg }));
        return alertPopup;
    };

    function getSelectListOption(v) {
        var function_id_list = document.getElementById('GeneralSearchItemFunctionId');
        for (var function_id = 0; function_id < data_source.length; function_id++) {
            for (var i = 0; i < data_source[function_id].length; i++) {
                var function_name = "";
                for (var j = 1; j < function_id_list.length; j++) {
                    if (function_id_list.options[j].value == function_id) {
                        function_name = function_id_list.options[j].text;
                        break;
                    }
                }
                if (v == data_source[function_id][i][0]) {
                    var option = document.createElement('option');
                    option.value = v;
                    option.appendChild(document.createTextNode(function_name + "." + data_source[function_id][i][1]));

                    return option;
                }
            }
        }

        return null;
    }

    function search() {
        var url = $('#search').attr('data-url');
        $.ajaxSetup({
            cache: false
        });
        $.ajax({
            type: "GET",
            url: url,
            xhr: function() {
                return progress.createXHR();
            },
            beforeSend: function(xhr) {
                progress.controlProgress(true);
            },
            complete: function() {
                progress.controlProgress(false);
            },
            success: function(data) {
                $("#save_list tr").each(function() {
                    $(this).remove();
                });
                if (data.length == 0) {
                    addSaveListNoData();
                } else {
                    addSaveList(JSON.parse(data));
                }

                $("#savedList").modal('show');
            },
            error: function() {
                console.log("Error!");
            }
        });
    }

    function addSaveList(datas) {
        var function_list = $("select[name='data[GeneralSearchItem][function_id]']");
        var rowkey = "";
        var url_genre = jQuery('.url_genre').attr('data-url');
        for (var i = 0; i < datas.length; i++) {
            var tr = document.createElement('tr');
            var td_no = document.createElement('td');
            var att_no = document.createAttribute("class");
            att_no.value = "p-1 align-middle fix-w-100 text-wrap text-center";
            td_no.setAttributeNode(att_no);
            td_no.appendChild(document.createTextNode(datas[i].id));
            tr.appendChild(td_no);
            rowkey += datas[i].id;
            var td_name = document.createElement('td');
            var att_name = document.createAttribute("class");
            att_name.value = "p-1 align-middle fix-w-200 text-wrap";
            td_name.setAttributeNode(att_name);
            td_name.appendChild(document.createTextNode(datas[i].definition_name));
            tr.appendChild(td_name);
            rowkey += datas[i].definition_name;
            var td_o_range = document.createElement('td');
            var att_o_range = document.createAttribute("class");
            att_o_range.value = "p-1 align-middle fix-w-100 text-wrap";
            td_o_range.setAttributeNode(att_o_range);
            var range = "";
            range = (datas[i].auth_popular == 1) ? document.createTextNode("一般") : null;
            if (range) td_o_range.appendChild(range);
            range = (datas[i].auth_admin == 1) ? document.createTextNode("管理者") : null;
            if (range) {
                if (td_o_range.childNodes.length > 0) td_o_range.appendChild(document.createElement('br'));
                td_o_range.appendChild(range);
            }
            range = (datas[i].auth_accounting_admin == 1) ? document.createTextNode("経理管理") : null;
            if (range) {
                if (td_o_range.childNodes.length > 0) td_o_range.appendChild(document.createElement('br'));
                td_o_range.appendChild(range);
            }
            tr.appendChild(td_o_range);
            range = (datas[i].auth_accounting == 1) ? document.createTextNode("経理一般") : null;
            if (range) {
                if (td_o_range.childNodes.length > 0) td_o_range.appendChild(document.createElement('br'));
                td_o_range.appendChild(range);
            }
            tr.appendChild(td_o_range);
            var td_date = document.createElement('td');
            var att_date = document.createAttribute("class");
            att_date.value = "p-1 align-middle fix-w-100 text-wrap text-center";
            td_date.setAttributeNode(att_date);
            td_date.appendChild(document.createTextNode(datas[i].created));
            tr.appendChild(td_date);
            rowkey += datas[i].created;
            var user_name = (datas[i].user_name == null) ? "" : datas[i].user_name;
            var td_cuser = document.createElement('td');
            var att_cuser = document.createAttribute("class");
            att_cuser.value = "p-1 align-middle fix-w-100 text-wrap";
            td_cuser.setAttributeNode(att_cuser);
            td_cuser.appendChild(document.createTextNode(datas[i].user_name));
            tr.appendChild(td_cuser);
            rowkey += datas[i].user_name;
            var td_button = document.createElement('td');
            var att_button = document.createAttribute("class");
            att_button.value = "p-1 align-middle fix-w-100 text-wrap text-center";
            td_button.setAttributeNode(att_button);
            var url = url_genre + '/' + datas[i].id;
            var link = document.createElement('a');
            link.setAttribute('class', 'btn btn--gradient-orange');
            link.setAttribute('id', 'select-save-data');
            link.setAttribute('href', url);
            link.appendChild(document.createTextNode('選択'));

            td_button.appendChild(link);
            tr.setAttribute('data-key', rowkey);
            rowkey = "";
            tr.appendChild(td_button);

            document.getElementById('save_list').appendChild(tr);
        }
    }

    function addSaveListNoData() {
        var tr = document.createElement('tr');
        var td = document.createElement('td');
        td.setAttribute('colspan', '5');
        td.style.textAlign = 'center';
        var text = document.createTextNode('データがありません。');
        td.appendChild(text);
        tr.appendChild(td);
        document.getElementById('save_list').appendChild(tr);
    }

    function selectSaveData(id) {
        location.href = _ROOT_ + "general_search/index/" + id;
    }

    function searchGenre(o) {
        var optionList = document.getElementById("genre_list");
        optionList.length = 0;

        if (o == null) return false;
        var genres = document.getElementsByName("multiselect_genre_id");
        regexp = new RegExp(o.value);
        for (var i = 0; i < genres.length; i++) {
            if (genres[i].getAttribute('title').match(regexp)) {
                var option = document.createElement('option');
                option.value = genres[i].value;
                option.setAttribute('data-target', genres[i].id);
                var option_text = document.createTextNode(genres[i].getAttribute('title'));
                option.appendChild(option_text);
                optionList.appendChild(option);
            }
        }
    }

    function transferSelected() {
        var list = document.getElementById('genre_list');
        for (var i = 0; i < list.length; i++) {
            if (list.options[i].selected)
                if (document.getElementById(list.options[i].getAttribute('data-target')).checked == false)
                    $("#" + list.options[i].getAttribute('data-target')).trigger('click');
        }
    }

    function deleteConfirm(event) {
        event.preventDefault();
        if ($("input[name='data[MGeneralSearch][id]']").val() == "") {
            var alertDeleteConfirm = createAlertPopup('保存したレポートを選択してください。');
            controlPopup(alertDeleteConfirm, true);
            return false;
        }

        var confirmPopup = createConfirmPopup('データを削除します。よろしいですか。');
        controlPopup(confirmPopup, true);

        confirmPopup.find('.st-pp-confirm').one('click', function(e) {
            var action = jQuery('#delete-confirm').attr('formaction');
            jQuery('#general_search_form').attr('action', action).trigger('submit');
            return true;
        });

        confirmPopup.on('hidden.bs.modal', function(e) {
            confirmPopup.remove();
        });
    }

    function registConfirm(event) {
        if ($('#definition_name').valid()) {
            $(".box--error").hide();
            if (document.getElementById("auth_popular").checked == false &&
                document.getElementById("auth_admin").checked == false &&
                document.getElementById("auth_accounting").checked == false &&
                document.getElementById("auth_accounting_admin").checked == false) {
                $(".box--error").text("必ず公開範囲を一つは選択して下さい。").show();
                event.preventDefault();
                return false;
            }

            var list = document.getElementById('select_list');
            for (var i = 0; i < list.length; i++) {
                var elem = document.createElement('input')
                elem.setAttribute('type', 'hidden');
                elem.setAttribute('name', 'data[GeneralSearchItem][item][' + i + ']');
                elem.value = list.options[i].value;
                document.forms['general_search_form'].appendChild(elem);
            }
            var actionRegist = jQuery('#regist-confirm').attr('formaction');
            jQuery('#general_search_form').attr('action', actionRegist).trigger('submit');
            return true;
        }
    }

    function makeCsv(event) {
        event.preventDefault();
        if ($("input[name='data[MGeneralSearch][id]']").val() == "") {
            var alertMakeCsv = createAlertPopupOk('保存したレポートを選択してください。');
            controlPopup(alertMakeCsv, true);
            return false;
        }
        var actionMakeCsv = jQuery('#make-csv').attr('formaction');
        jQuery('#general_search_form').attr('action', actionMakeCsv).trigger('submit');
        return true;
    }

    function resetSelectListRelation() {
        if ($("#GeneralSearchItemFunctionId").val() == "") return;

        $("#candidate_list option").each(function() {
            $(this).remove();
        });
        var suggest_text = document.getElementById("column_suggest").value;
        var suggest_re = (suggest_text.length > 0) ? new RegExp(".*" + suggest_text + ".*") : null;

        var select_list = document.getElementById('select_list');
        for (var i = 0; i < data_source[$("#GeneralSearchItemFunctionId").val()].length; i++) {
            if (suggest_re)
                if (!data_source[$("#GeneralSearchItemFunctionId").val()][i][1].match(suggest_re)) continue;
            var option = document.createElement('option');
            option.value = data_source[$("#GeneralSearchItemFunctionId").val()][i][0];
            option.appendChild(document.createTextNode(data_source[$("#GeneralSearchItemFunctionId").val()][i][1]));
            document.getElementById('candidate_list').appendChild(option);
            for (var j = 0; j < select_list.length; j++) {
                if (select_list.options[j].value == data_source[$("#GeneralSearchItemFunctionId").val()][i][0]) {
                    document.getElementById("candidate_list").removeChild(option);
                }
            }
        }
    }

    function makeResult(event) {
        $('#definition_name').addClass('ignore').removeClass('is-invalid');
        if ($("#general_search_form").valid()) {
            var list = document.getElementById('select_list');
            if (list.length == 0) {
                event.preventDefault();
                var alertMakeResult = createAlertPopup('抽出項目は必ず選択してください。');
                controlPopup(alertMakeResult, true);
                return false;
            }

            for (var i = 0; i < list.length; i++) {
                var elem = document.createElement('input')
                elem.setAttribute('type', 'hidden');
                elem.setAttribute('name', 'data[GeneralSearchItem][item][' + i + ']');
                elem.value = list.options[i].value;
                document.forms['general_search_form'].appendChild(elem);
            }

            $.ajaxSetup({
                cache: false,
            });

            var url = $("#make-result").attr('data-url');
            var form = $("#general_search_form");
            $.ajax({
                url: url,
                type: "post",
                data: form.serialize(),
                xhr: function() {
                    return progress.createXHR();
                },
                beforeSend: function(xhr) {
                    progress.controlProgress(true);
                },
                complete: function() {
                    progress.controlProgress(false);
                },
                success: function(data) {
                    var data = JSON.parse(data);
                    setResultCondition(data.conditions);
                    setResultHeader(data.headers);
                    setResultData(data.datas);
                    $('#resultList').modal();
                    $('#definition_name').removeClass('ignore');
                },
                error: function() {
                    var alertMakeResultAjax = createAlertPopup('通信に問題が発生したため、処理を中止します。');
                    controlPopup(alertMakeResultAjax, true);
                }
            });

            return false;
        }
    }

    function setResultCondition(conditions) {
        $('#result_condition').empty();
        var body = document.getElementById('result_condition');

        for (var c_cnt = 0; c_cnt < conditions.length; c_cnt++) {
            var tr = document.createElement('tr');
            var title_td = document.createElement('td');
            var att_title_td = document.createAttribute("class");
            att_title_td.value = "p-1 align-middle text-wrap";
            title_td.setAttributeNode(att_title_td);
            title_td.appendChild(document.createTextNode(conditions[c_cnt].title));
            tr.appendChild(title_td);
            var value_td = document.createElement('td');
            var att_value_td = document.createAttribute("class");
            att_value_td.value = "p-1 align-middle text-wrap";
            value_td.setAttributeNode(att_value_td);
            value_td.appendChild(document.createTextNode(conditions[c_cnt].value));
            tr.appendChild(value_td);
            body.appendChild(tr);
        }
    }

    function setResultHeader(header) {

        $("#result_header tr").each(function() {
            $(this).remove();
        });

        var thead = document.getElementById('result_header');

        var tr = document.createElement('tr');
        var th = document.createElement('th');
        var att_th = document.createAttribute("class");
        att_th.value = "p-1 align-middle fix-w-100 text-wrap";
        th.setAttributeNode(att_th);
        th.appendChild(document.createTextNode('No'));
        tr.appendChild(th);

        for (var h_cnt = 0; h_cnt < header.length; h_cnt++) {
            var th = document.createElement('th');
            var att_th = document.createAttribute("class");
            att_th.value = "p-1 align-middle fix-w-100 text-wrap";
            th.setAttributeNode(att_th);
            th.appendChild(document.createTextNode(header[h_cnt]));

            tr.appendChild(th);
        }

        thead.appendChild(tr);

    }

    function setResultData(datas) {
        $("#result_list tr").each(function() {
            $(this).remove();
        });

        var tbody = document.getElementById('result_list');
        for (var d_cnt = 0; d_cnt < datas.length; d_cnt++) {
            var tr = document.createElement('tr');

            for (var r_cnt = -1; r_cnt < datas[d_cnt].length; r_cnt++) {
                var val = (r_cnt < 0) ? (d_cnt + 1) : datas[d_cnt][r_cnt];
                val = (val == null) ? "" : val;
                var td = document.createElement('td');
                var att_td = document.createAttribute("class");
                att_td.value = "p-1 align-middle fix-w-100 text-center text-wrap";
                td.setAttributeNode(att_td);
                td.appendChild(document.createTextNode(val));
                tr.appendChild(td);
            }

            tbody.appendChild(tr);
        }
    }

    function reset_send_mail_fax() {
        document.getElementById("send_mail_fax0").checked = false;
        document.getElementById("send_mail_fax1").checked = false;
    }

    function init() {
        Datetime.initForDatepicker();
        Datetime.initForDateTimepicker();

        if (selected_item.length > 0) {
            var candidate_list = document.getElementById('candidate_list');
            var selected_list = document.getElementById('select_list');
            for (var i = 0; i < selected_item.length; i++) {
                var option = getSelectListOption(selected_item[i]);
                selected_list.appendChild(option);
                for (var j = 0; j < candidate_list.length; j++) {
                    if (selected_item[i] == candidate_list.options[j].value) {
                        candidate_list.options[j].style.display = "none";
                        break;
                    }
                }
            }
        }
        $('#tab-search-column').on('click', function() {
            $('#tab-search-condition').removeClass('bg-box');
            $('#tab-search-column').addClass('bg-box');
            $('#content-search-column').show();
            $('#content-search-condition').hide();
        });
        $('#tab-search-condition').on('click', function() {
            $('#tab-search-column').removeClass('bg-box');
            $('#tab-search-condition').addClass('bg-box');
            $('#content-search-condition').show();
            $('#content-search-column').hide();
        });
        if ($('#tab-search-column').hasClass('bg-box')) {
            $('#content-search-column').show();
            $('#content-search-condition').hide();
        }
        if ($('#tab-search-condition').hasClass('bg-box')) {
            $('#content-search-condition').show();
            $('#content-search-column').hide();
        }
        $("#site-id").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        }).multiselectfilter({
            label: ''
        });
        $("#GeneralSearchCondition3DemandInfos-demandStatus").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        });
        $("#demand-genre-id").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        }).multiselectfilter({
            label: ''
        });
        $("#corp-status").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        });
        $("#follow-person").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        });
        $("#GeneralSearchCondition9MTargetAreas-jisCd").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        });
        $("#corp-commission-status").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        });
        $("#GeneralSearchCondition3CommissionInfos-commissionStatus").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        });
        $("#GeneralSearchCondition4MCorps-address1").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        });
        $("#genre-id").multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        }).multiselectfilter({
            label: ''
        });

        $('.ui-multiselect-menu, .ui-state-default').css('width', width_ui_state_default);

        $("select[name='data[GeneralSearchItem][function_id]']").change(function() {
            resetSelectListRelation();
        });

        $("#candidate_list").dblclick(function() {
            if ($("#candidate_list").prop("selectedIndex") > -1) {
                var function_text = $("select[name='data[GeneralSearchItem][function_id]'] option:selected").text();
                var v = $("#candidate_list option:selected").val();
                var t = $("#candidate_list option:selected").text();
                $("#select_list").append($("<option>").val(v).text(function_text + "." + t));
                $("#candidate_list option:selected").remove();
                $("#candidate_list").prop("selectedIndex", -1);
            }

            return false;
        });

        $("#move_right").click(function() {
            if ($("#candidate_list").prop("selectedIndex") > -1) {
                var function_text = $("select[name='data[GeneralSearchItem][function_id]'] option:selected").text();
                var candidate_list = document.getElementById('candidate_list');
                $("#candidate_list option:selected").each(function() {
                    var v = $(this).val();
                    var t = $(this).text();
                    $("#select_list").append($("<option>").val(v).text(function_text + "." + t));
                    $(this).remove();
                });
                $("#candidate_list").prop("selectedIndex", -1);
            }

            return false;
        });

        $("#select_list").dblclick(function() {
            if ($("#select_list").prop("selectedIndex") > -1) {
                $("#select_list option:selected").remove();
                resetSelectListRelation();
            }

            return false;
        });

        $("#move_left").click(function() {
            if ($("#select_list").prop("selectedIndex") > -1) {
                $("#select_list option:selected").remove();
                resetSelectListRelation();
            }

            return false;
        });

        $("#move_up").click(function() {
            if ($("#select_list").prop("selectedIndex") > 0) {
                var list = document.getElementById('select_list');

                for (var i = 0; i < list.length; i++) {
                    if (list.options[i].value == $("#select_list option:selected").val()) {
                        list.insertBefore(list.options[i], list.options[i - 1]);
                        break;
                    }
                }

            }

            return false;
        });

        $("#move_down").click(function() {
            var list = document.getElementById('select_list');
            if ($("#select_list").prop("selectedIndex") < list.length - 1) {
                for (var i = 0; i < list.length; i++) {
                    if (list.options[i].value == $("#select_list option:selected").val()) {
                        list.insertBefore(list.options[i], list.options[i + 1].nextSibling);

                        break;
                    }
                }
            }

            return false;
        });

        $("#call_suggest_genre").click(function() {
            $('#txt_search').value = '';
            searchGenre(null);
            $("#modal_genre_search").modal();
        });

        $("#list_search_keyword").keyup(function() {
            var search_value = $(this).val();
            var re = new RegExp(search_value);

            var table_rows = document.getElementById('save_list').rows;
            for (var i = 0; i < table_rows.length; i++) {
                var key = table_rows[i].getAttribute('data-key');
                if (search_value.length == 0 || re.test(key)) {
                    table_rows[i].style.display = "";
                } else {
                    table_rows[i].style.display = "none";
                }
            }
        });

        $("#search").click(function() {
            search();
        });

        $("#make-result").click(function(event) {
            return makeResult(event);
        });

        $("#delete-confirm").click(function(event) {
            return deleteConfirm(event);
        });

        $("#regist-confirm").click(function(event) {
            registConfirm(event);
        });

        $("#make-csv").click(function(event) {
            return makeCsv(event);
        });

        $("#transfer-selected").click(function() {
            transferSelected();
        });

        $("#reset-send-mail-fax").click(function() {
            reset_send_mail_fax();
        });

        $("#select-save-data").click(function() {
            selectSaveData();
        });

        $("#column_suggest").keyup(function() {
            resetSelectListRelation();
        });
    }

    return {
        init: init
    }
}();
$(document).ready(function() {
    GeneralSearch.init();
});
