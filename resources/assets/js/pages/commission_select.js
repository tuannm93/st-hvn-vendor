$(document).ready(function () {

    if($('#target_check').is(":checked")){
        $("[name=select_order]").val(1);
    }
});

var opts2html = function(opts){
    return $.map(opts, function(n,i){return n[0].outerHTML}).join("");
};

var opts = [];
opts.push($("<option>").val(""))
for(var i = 1; i <= 30; i++){
    opts.push($("<option>").val(i).html(i))
}
$("[name=select_order]").html(opts2html(opts))




function fixed_setting(no) {
    document.getElementById("target_check").checked = true;
    switch(no) {
        case 1:
            document.getElementById("corp_name").value = "【SF用】取次前失注用";
            break;
        case 2:
            document.getElementById("corp_name").value = "【要ﾋｱﾘﾝｸﾞor連絡待ち】";
            break;
        case 3:
            document.getElementById("corp_name").value = "【開拓依頼中】";
            break;
    }
    document.getElementById("fixed_val").value = "1";
}