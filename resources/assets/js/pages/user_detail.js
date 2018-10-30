var UserDetail = function () {

    function showModalUser (rowOfficial, selector) {
        $(rowOfficial).hide();
        $(selector).change(function () {
            if($(this).val() == 'affiliation') {
                changeDisplayTag(rowOfficial, 'on');
            }else {
                changeDisplayTag(rowOfficial, 'off');
            }
        })
    }

    function searchModalUser (selector, modal) {
        $(selector).click(function () {
            $(modal).modal('toggle');
        });
    }

    function showCustommer (rowOfficial, selector) {
        var selectUserDetail = $(selector).val();
        if(selectUserDetail == 'affiliation') {
            changeDisplayTag(rowOfficial, 'on');
        }
    }

    function changeDisplayTag(tag, task) {
        if (task == 'on') {
            $(tag).show();
            $('#official_corp_name').removeClass('ignore');
        } else { // off
            $(tag).hide();
            $('#official_corp_name').addClass('ignore');
        }
    }

    function redirectUserSearch (selector) {
        $(selector).click(function (){
            window.location.href = "/user/back";
        });
    }

    function init () {
        showModalUser('#row_official_corp_name', '#select_user_detail');
        searchModalUser('#customer_name_search', '#mCorpList');
        showCustommer('#row_official_corp_name', '#select_user_detail');
        redirectUserSearch('#btn_user_search');
    }

    return {
        init: init
    }
}();

$(document).ready(function () {
    FormUtil.validate('#form-user-detail');
    UserDetail.init();
});
