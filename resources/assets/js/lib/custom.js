$(document).ready(function(){
    $(".multiple_check").multiselect({
        minWidth:300,
        selectedList: 5,
        checkAllText: "全選択",
        uncheckAllText: "選択解除",
        noneSelectedText: "--なし--",
    });
    $(".multiple_check_filter").multiselect({
        minWidth:300,
        selectedList: 5,
        checkAllText: "全選択",
        uncheckAllText: "選択解除",
        noneSelectedText: "--なし--",
    }).multiselectfilter({
        label:'',
        width:95
    });

    Datetime.initForDateTimepicker();
})
