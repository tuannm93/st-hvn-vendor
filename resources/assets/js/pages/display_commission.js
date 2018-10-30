$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var _corp = [];
var corp = [];
var i = 0;
var date;
var COOKIE_KEY = "COMMISSION_SELECT";
var opts = [];
var has_show_message = false;

var resultDiv = $('.content-ajax'),
    progress = new progressCommon();

var opts2html = function(opts){
    return $.map(opts, function(n,i){return n[0].outerHTML}).join("");
};

var copyOpts = function(opts){
    var _opts = [];
    for(var i = 0; i < opts.length; i++){
        _opts.push(opts[i].clone())
    }
    return _opts
};

$(document).ready(function () {
    loadOptionList();
});

var changeSelectOrderOptions = function(){
    var _opts = copyOpts(opts)
    for(var i = 0; i < _corp.length; i++){
        var html = _opts[i].html().replace("×", "");
        if(_corp[i] != undefined){
            _opts[i].prop("disabled", true).html("×"+html);
        }else{
            _opts[i].prop("disabled", false).html(html);
        }
    }
    //未選択のセレクトボックスのoptionを一気に置き換える
    $("[name=select_order] option:selected[value='']").parent().html(opts2html(_opts))

    //選択済みのセレクトボックスは1つずつ属性を変更する
    $("[name=select_order] option:selected:not([value=''])").parent().each(function(){
        var val = $(this).val();
        $(this).find("option").each(function(){
            var opt_val = $(this).val();
            var html = $(this).html().replace("×", "");
            if(_corp[opt_val] == undefined || opt_val == val){
                $(this).prop("disabled", false).html(html);
            }else{
                $(this).prop("disabled", true).html("×"+html);
            }
        });
    });
};

$(document).on('change', "#introduction tr [name=select_order]", function (e) {
    var order = $(this).val();
    var corp_id = $(this).parent().find("[name=select]").val();
    //古いものを削除
    var index = _corp.indexOf(corp_id);
    if(index != -1){
        _corp[index] = undefined;
    }
    if(order != ""){
        //追加
        _corp[order] = corp_id;
    }
    //保存（保留）
    //$.cookie(COOKIE_KEY, JSON.stringify(_corp));
    setTimeout(function(){
        changeSelectOrderOptions();
    }, 1);
});


Array.prototype.unique = function() {
    var a = this.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
};

function addPotitionSelect(result, position){
    var a= JSON.parse(result);
    a['potition'] = parseInt(position);
    return JSON.stringify(a);
}

function sortByPosition(arr){
    arr.sort(function(a, b){
        var positionA = parseInt(JSON.parse(a).potition),positionB = parseInt(JSON.parse(b).potition);
        if(positionA === positionB) return 0;
        return positionA > positionB ? 1 : -1;
    });
    return arr;
}
$(document).on('click', "#decide", function (e) {
    $(this).attr('disabled', true);
    var list_choose = [];
    var result = '';
    $('select[name=select_order]').each(function () {
        if ($(this).val() !== '') {
            var id_select = '#select_' + $(this).parent().children('input[name=select]').val();
            result = $(id_select).val();
            result = addPotitionSelect(result, parseInt($(this).val()));
            list_choose.push(result);
        }
    });
    list_choose = sortByPosition(list_choose);
    showDialogDanger(list_choose);
});

function close_decide(list_choose){

    opener.document.f1.commissions.value = JSON.stringify(list_choose);
    opener.document.f1.current_index.value++;

    window.close();
};

function showDialogDanger(list_choose){
    var one_choose = '';
    var list_corp_id   = [];
    var list_corp_name = [];
    for (var i = 0; i < list_choose.length; i++) {
        one_choose = JSON.parse(list_choose[i]);
        list_corp_id.push(one_choose.corp_id);
        list_corp_name.push(one_choose.corp_name);
    }

    $.ajax({
        type: 'post',
        url: urlCheckCredit,
        data: {
            list_corp_id: list_corp_id,
            list_corp_name: list_corp_name,
            genre_id: $('#genre_id').val(),
            site_id: $('#site_id').val()
        },
        success: function success(data) {
            if(data.credit_count > 0){
                if(!has_show_message){
                    alert(data.credit_message + "\n上記の加盟店は与信限度額をオーバーするため選定できません");
                    has_show_message = true;
                }
                for (var j = data.list_position_danger.length -1; j >= 0; j--) {
                    list_choose.splice(data.list_position_danger[j], 1);
                }
            }
            close_decide(list_choose);
        },
        error: function error(error) {
            console.log(error);
        }
    });
}

function loadOptionList() {

    opts.push($("<option>").val(""))
    for(var i = 1; i <= 30; i++){
        opts.push($("<option>").val(i).html(i))
    }
    $("[name=select_order]").html(opts2html(opts))

    if(document.getElementById("fixed_val").value == '1'){
        $("[name=select_order]").val(1);
    }
}
$(document).on('click', "#clear_selection", function (e) {
    $("[name=select_order]").val("").html(opts2html(opts))
});

$(document).on('click', '.search', function (e) {
    e.preventDefault();
    hide_modal();
    if($(this).hasClass('search1')){
        fixed_setting('【SF用】取次前失注用');
    }else if($(this).hasClass('search2')){
        fixed_setting('【要ﾋｱﾘﾝｸﾞor連絡待ち】');
    }else if($(this).hasClass('search3')){
        fixed_setting('【開拓依頼中】');
    }else {
        getPosts();
        document.getElementById("fixed_val").value = "";
    }
});

function hide_modal() {
    $('#modal-popup').css('z-index',1);
}

function show_modal() {
    $('#modal-popup').css("z-index", '');
}

function fixed_setting(value) {
    document.getElementById("target_check").checked = true;
    document.getElementById("corp_name").value = value;
    getPosts();
    document.getElementById("fixed_val").value = "1";
}

function getPosts() {
    var url = urlCommissionSelect;

    $.ajax({
        type: 'post',
        url: url,
        data: $('#displayForm').serialize(),
        processData: false,
        xhr: function () {
            return progress.createXHR();
        },
        beforeSend: function () {
            progress.controlProgress(true);
        },
        complete: function () {
            show_modal();
            progress.controlProgress(false);
            _corp = [];
            opts = [];
            loadOptionList();
        },
        success: function (data) {
            resultDiv.html(data);
        },
        error: function (err) {
            console.log('error');
        }
    });

}
