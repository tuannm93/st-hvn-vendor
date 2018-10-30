$(document).ready(function() {
    $("#genres_id").multiselect({
        checkAllText: '全選択',
        uncheckAllText: '選択解除',
        selectedList: 5,
        noneSelectedText: "--なし--",
    }).multiselectfilter({
        label: ''
    });

    $('.tab-menu-custom').find('li').each(function(k, v) {
        $(v).removeClass('active');
    });
    $('.tab-menu-custom li:last-child').addClass('active');
});