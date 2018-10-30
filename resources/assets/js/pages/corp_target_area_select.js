var CorpTargetAreaSelect = function() {
    var progressBlock = $('.progress-block'),
        confirmPopupType = 1,
        progressEl = $('.progress');
    function getCallAjaxUrl(url, flag) {
        $.ajax({
            type: 'post',
            url: url,
            data: jQuery('#corp-target-area-select').serialize(),
            processData: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    var percentComplete = evt.loaded / evt.total;
                    progressEl.css({
                        width: percentComplete * 100 + "%"
                    });
                }, false);
                xhr.addEventListener("progress", function (evt) {
                    var percentComplete = evt.loaded / evt.total;
                    progressEl.css({
                        width: percentComplete * 100 + "%"
                    });
                }, false);
                return xhr;
            },
            beforeSend: function () {
                jQuery('.alert-success').hide();
                jQuery('.alert-danger').hide();
                progressBlock.show();
                progressEl.show();
            },
            complete: function () {
                progressBlock.hide();
                progressEl.hide();
            },
            success: function (data) {
                if(data.session == 'success'){
                    jQuery('.alert-success').show();
                    jQuery('.alert-danger').hide();
                    jQuery('.alert-success').html(data.result);
                }else {
                    jQuery('.alert-success').hide();
                    jQuery('.alert-danger').show();
                    jQuery('.alert-danger').html(data.result);
                }
                if(data.view != undefined){
                    $("#display_area").html(data.view);
                }
                if (flag != 1) {
                    jQuery('#regist').removeAttr('hidden');
                    jQuery('#registjsc').attr('hidden', 'false');
                }
            },
            error: function (err) {
            }
        });
    }
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

    var messages = {
        registMsg: "エリアが一括で登録されます。よろしいですか？",
        confirmMsg: "本当によろしいですか？"
    };

    function init() {
        jQuery(document).on('click', '.detail', function () {
            jQuery('.alert-success').hide();
            jQuery('.alert-danger').hide();
            var url = jQuery(this).attr('data-url'),
                txt = jQuery(this).attr('data-txt'),
                num = jQuery(this).attr('data-num');
            if(txt != ''){
                if($("#address1_" + num).prop('checked')) {
                    url = url + '?checked=true';
                }
                $('#address1_text').val(txt);
                $.ajaxSetup({
                    cache: false,
                });
                $.get(url, function(data) {
                    $(".pref-list").html(data);
                    $("#message").html('');
                    jQuery('#regist').attr('hidden', 'true');
                    jQuery('#registjsc').removeAttr('hidden');
                    jQuery('#back_modal').removeAttr('hidden');
                    jQuery('.pref-title').attr('hidden', 'true');
                });
            }else{
                $("#display_area").html('');
            }
        });
        jQuery(document).on('click', '#all_regist', function() {
            var url = jQuery(this).attr('data-url'),
            myRet = createConfirmPopup(messages.registMsg);
            controlPopup(myRet, true);
            myRet.find('.st-pp-confirm').one('click', function(e) {
                controlPopup(myRet, false);
                var myRetRure = createConfirmPopup(messages.confirmMsg);
                controlPopup(myRetRure, true);
                myRetRure.find('.st-pp-confirm').one('click', function(e) {
                    controlPopup(myRetRure, false);
                    getCallAjaxUrl(url, 0);
                    jQuery('#back_modal').attr('hidden', 'true');
                });
                myRetRure.on('hidden.bs.modal', function (e) {
                    myRetRure.remove();
                });
            });
            myRet.on('hidden.bs.modal', function (e) {
                myRet.remove();
            });
        });
        jQuery(document).on('click', '#all_remove', function() {
            var url = jQuery(this).attr('data-url'),
            myRet = createConfirmPopup(messages.registMsg);
            controlPopup(myRet, true);
            myRet.find('.st-pp-confirm').one('click', function(e) {
                // close popup
                controlPopup(myRet, false);
                getCallAjaxUrl(url, 0);
                jQuery('#back_modal').attr('hidden', 'true');
            });

            myRet.on('hidden.bs.modal', function (e) {
                myRet.remove();
            });
        });
        jQuery(document).on('click', '#regist', function () {
            var url = jQuery(this).attr('data-url');
            getCallAjaxUrl(url, 0);
        });
        jQuery(document).on('click', '#registjsc', function () {
            var url = jQuery(this).attr('data-url');
            getCallAjaxUrl(url, 1);
        });
        jQuery(document).on('click', '.show-corp-target-area-select', function () {
            var url = jQuery(this).attr('data-url');
            $.ajax({
                type: 'get',
                url: url,
                processData: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        var percentComplete = evt.loaded / evt.total;
                        progressEl.css({
                            width: percentComplete * 100 + "%"
                        });
                    }, false);
                    xhr.addEventListener("progress", function (evt) {
                        var percentComplete = evt.loaded / evt.total;
                        progressEl.css({
                            width: percentComplete * 100 + "%"
                        });
                    }, false);
                    return xhr;
                },
                beforeSend: function () {
                    jQuery('.alert-success').hide();
                    jQuery('.alert-danger').hide();
                    progressBlock.show();
                    progressEl.show();
                },
                complete: function () {
                    progressBlock.hide();
                    progressEl.hide();
                },
                success: function (data) {
                    jQuery('#corpModal .modal-body').html(data);
                    jQuery('#corpModal').modal('show');
                },
                error: function (err) {
                }
            });
        });
        jQuery(document).on('click', '#close_modal', function () {
            jQuery('#corpModal').modal('hide');
        });
        jQuery(document).on('click', '.check_group', function (){
            var name = jQuery(this).val();
            if (this.checked == true) {
                jQuery(this).prev().val('');
            }else{
                jQuery(this).prev().val(name);
            }
        });

        jQuery(document).on('click', '#target-area-modal', function () {
            var url = jQuery(this).attr('data-url');
            $.ajax({
                type: 'get',
                url: url,
                processData: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        var percentComplete = evt.loaded / evt.total;
                        progressEl.css({
                            width: percentComplete * 100 + "%"
                        });
                    }, false);
                    xhr.addEventListener("progress", function (evt) {
                        var percentComplete = evt.loaded / evt.total;
                        progressEl.css({
                            width: percentComplete * 100 + "%"
                        });
                    }, false);
                    return xhr;
                },
                beforeSend: function () {
                    jQuery('.alert-success').hide();
                    jQuery('.alert-danger').hide();
                    progressBlock.show();
                    progressEl.show();
                },
                complete: function () {
                    progressBlock.hide();
                    progressEl.hide();
                },
                success: function (data) {
                    jQuery('#targertAreaModal .modal-body').html(data);
                    jQuery('#targertAreaModal').modal('show');
                },
                error: function (err) {
                }
            });
        });
        jQuery(document).on('click', '#close_modal', function () {
            jQuery('#targertAreaModal').modal('hide');
        });
        $(document).on('hidden.bs.modal', function() {
            if ($('.modal.show').length) {
                $('body').addClass('modal-open');
            }
        });
    }
    return {
        init: init
    }
}();
jQuery(document).ready(function () {
    CorpTargetAreaSelect.init();
});
