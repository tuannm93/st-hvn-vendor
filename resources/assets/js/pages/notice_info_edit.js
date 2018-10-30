var NoticeInfoEdit = function () {

    var countClick = 0,
        confirmPopupType = 1;

    // init popup html
    var initPopupHtml = function (popupType, info) {
        var corpPopup = new popupCommon(popupType, info);
        var popupHtml = corpPopup.renderView();
        return popupHtml;
    };

    var createConfirmPopup = function (msg) {
        var confirmPopup = $(initPopupHtml(confirmPopupType, {close: 'キャンセル', confirm: 'OK', msg: msg}));
        return confirmPopup;
    };

    var controlPopup = function (popup, isShow) {
        if (isShow) {
            popup.modal('show');
        } else {
            popup.modal('hide')
        }
    };

    function bindChangeTargetName() {
        $('[name=target]').change(function () {
            $('.tr-disp').hide();
            $('#tr-disp-' + $(this).val()).show();
        }).filter(':checked').trigger('change');
    }

    function bindChangeRequestAnswer() {
        $('[name=request-answer]').change(function () {
            if ($(this).val() == 0) {
                $('#input-request-answer').hide();
            } else {
                $('#input-request-answer').show();
            }
        }).filter(':checked').trigger('change');
    }

    function bindEventGetListAff() {
        $('#get_list_aff').click(function () {
            countClick++;
            if (countClick != 1) {
                return false;
            }
            var searchValue = $('#search_aff').val();
            $('#ajax-message').prev('.fa').remove();
            $('#ajax-message').html($('#page-data').data('loading-ajax-text')).removeClass().addClass('ajax-loading');
            $.ajax({
                url: $('#page-data').data('route-get-list-aff'),
                type: 'get',
                data: {
                    search_key: $('#search_aff_type').val(),
                    search_value: searchValue,
                    exlude_corp_ids: $('#choose-affs option').map(function (index, item) {
                        return $(item).val();
                    }).toArray()
                },
                success: function (data) {
                    $('#list-choose-affs').html('');
                    data.data.forEach(function (item) {
                        $('#list-choose-affs').append("<option value='" + item.id + "'>" + item.corp_name + "</option>");
                    });
                    $('#ajax-message').before("<i class='fa fa-check-circle text-success' aria-hidden='true'></i> ");
                    $('#ajax-message').html($('#page-data').data('loading-ajax-success'));
                    $('#total-count').html(data.count);
                    $('#result-count').html(data.data.length);
                    countClick = 0;
                },
                error: function (data) {
                    $('#ajax-message').before('<i class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i> ');
                    $('#ajax-message').html($('#page-data').data('loading-ajax-fail-text'));
                    countClick = 0;
                }
            })
        });
    }

    function bindEventMoveUp() {
        $('#btn-move-up').click(function () {
            $('#list-choose-affs :selected').each(function (index, item) {
                $('#choose-affs').append("<option value='" + $(item).val() + "'>" + $(item).text() + "</option>");
                $(item).remove();
            });
        });
    }

    function bindEventMoveDown() {
        $('#btn-move-down').click(function () {
            $('#choose-affs :selected').each(function (index, item) {
                $('#list-choose-affs').append("<option value='" + $(item).val() + "'>" + $(item).text() + "</option>");
                $(item).remove();
            });
        });
    }

    function bindBtnConfirmModal() {
        $('#btn-show-confirm').click(function () {
            if ($('[name=target]:checked').val() == 2) {
                if ($('#choose-affs option').length == 0) {
                    alert($('#page-data').data('alert-choose-corps'));
                    return;
                }

                $('#choose-affs option').prop('selected', true);
                $('#target_confirm_list').html('');
                $('#choose-affs option').each(function (index, option) {
                    $('#target_confirm_list').append('<li>' + $(option).html() + '</li>');
                });
                $('#target_confirm_count').html($('#choose-affs option').length);
                $('#target_confirm').modal('show');
            } else {
                $('#submit-form').click();
            }
        });
    }

    function bindBtnSubmit() {
        $('#submit-form').click(function () {
            $('#choose-affs option').prop('selected', true);
            $('#form-notice').submit();
        });
    }


    function bindBtnRemoveNotice() {
        $('#btn-remove-notice').click(function () {

            var msgConfirm = $('#page-data').data('confirm-del');

            var $confirmPopup = createConfirmPopup(msgConfirm);
            controlPopup($confirmPopup, true);

            $confirmPopup.find('.st-pp-confirm').one('click', function (e) {
                $('#del_flg').val(1);
                $('#choose-affs option').prop('selected', true);
                $('#form-notice').submit();
                controlPopup($confirmPopup, false);
                return true;
            });

            $confirmPopup.on('hidden.bs.modal', function (e) {
                $confirmPopup.remove();
            });
            return false;
        });
    }

    function bindBtnDownloadCsv() {
        $('#download-csv-answer').click(function () {
            window.location = $('#page-data').data('route-download-csv');
        });
    }

    function changePlaceHolder() {
        $("#search_aff_type").change(function () {
            var searchAff = $('#search_aff');
            var placeholderOld = searchAff.attr('placeholder');
            var placeholderNew = searchAff.data('placeholder-search-aff');
            searchAff.data('placeholder-search-aff', placeholderOld);
            searchAff.attr('placeholder', placeholderNew);
        });
    }

    function init() {
        bindChangeTargetName();
        bindChangeRequestAnswer();
        bindEventGetListAff();
        bindEventMoveUp();
        bindEventMoveDown();
        bindBtnConfirmModal();
        bindBtnSubmit();
        bindBtnRemoveNotice();
        bindBtnDownloadCsv();
        changePlaceHolder();
    }

    return {
        init: init
    }
}();

jQuery(document).ready(function () {
    NoticeInfoEdit.init();
    $('.back-to-index').on('click', function () {
        let url = $(this).attr('data-url');
        window.location = url;
    })
});
