$(document).ready(function() {
    var tablet_width = 678;
    enable_upload_btn();
    initPseudoScrollBar();
});

function subWinPreview(url){
    window.open(url, '_blank', 'width=1115, height=800, menubar=no, toolbar=no, scrollbars=yes , location=no, left=' + (screen.availWidth - 975));
}
$('#update-corp-agreement').on('click', function () {
    if ($(this).data('requestRunning')) {
        return;
    }
    $(this).data('requestRunning', true);
    var ajaxFlg = $('#ajax-flg').val();
    if (ajaxFlg == 0) {
        return true;
    }

    var corpId = $('#corp-id').data('corp-id');

    $.ajax({
        type: 'get',
        url: urlCheckAutoCommission,
        data: {
            'corp_id': corpId
        },
    }).done(function(res, textStatus, jqXHR) {
        $('#update-corp-agreement').data('requestRunning', false);
        if (res) {
            $('#ajax-flg').val(0);
            $("#update-corp-agreement-form").submit();
        } else {
            $('#ajax-flg').val(1);
            $('#checkModal').modal('show');
        }
    });
    return false;
});

function enable_upload_btn() {
    var isDisable = true;
    $(".upload_file_path").each(function() {
        if ($(this).val()) {
            document.getElementById("upload-file").disabled = false;
            return false;
        } else {
            document.getElementById("upload-file").disabled = true;
        }
    });
}
function initPseudoScrollBar() {
    if ($('.custom-scroll-x').length) {
        if ($('.add-pseudo-scroll-bar-1').length) {
            var table_scroll_width_1 = $('.add-pseudo-scroll-bar-1').width();
            var width_scroll_1 = $('.custom-scroll-x-1').width();
            var table_offset_top_1 = $('.custom-scroll-x-1').offset().top;

            $('.scroll-bar-1').css('width', table_scroll_width_1);
            $('.pseudo-scroll-bar-1').css({ 'width': width_scroll_1, 'bottom': 0 });
            $('.pseudo-scroll-bar-1').scroll(function() {
                var left = Number($('.pseudo-scroll-bar-1').scrollLeft());
                $('.custom-scroll-x-1').scrollLeft(left);
            });
            $('.custom-scroll-x-1').scroll(function() {
                var left = Number($('.custom-scroll-x-1').scrollLeft());
                $('.pseudo-scroll-bar').scrollLeft(left);
            });
            $(window).on("scroll", function() {
                var display = $('.pseudo-scroll-bar-1').attr('data-display');
                if ($(window).scrollTop() + $(window).height() > table_offset_top_1 + $('.add-pseudo-scroll-bar-1').height() && display == 'true' || $(window).scrollTop() + $(window).height() < table_offset_top_1 && display == 'true') {
                    $('.pseudo-scroll-bar-1').hide().attr('data-display', false);
                } else if ($(window).scrollTop() + $(window).height() < table_offset_top_1 + $('.add-pseudo-scroll-bar-1').height() && table_offset_top_1 < $(window).scrollTop() + $(window).height() && display == 'false') {
                    $('.pseudo-scroll-bar-1').show().attr('data-display', true);
                }
            });
        }
        if ($('.add-pseudo-scroll-bar-2').length) {
            var table_scroll_width_2 = $('.add-pseudo-scroll-bar-2').width();
            var width_scroll_2 = $('.custom-scroll-x-2').width();
            var table_offset_top_2 = $('.custom-scroll-x-2').offset().top;

            $('.scroll-bar-2').css('width', table_scroll_width_2);
            $('.pseudo-scroll-bar-2').css({ 'width': width_scroll_2, 'bottom': 0 });
            $('.pseudo-scroll-bar-2').scroll(function() {
                var left = Number($('.pseudo-scroll-bar-2').scrollLeft());
                $('.custom-scroll-x-2').scrollLeft(left);
            });
            $('.custom-scroll-x-2').scroll(function() {
                var left = Number($('.custom-scroll-x-2').scrollLeft());
                $('.pseudo-scroll-bar').scrollLeft(left);
            });
            $(window).on("scroll", function() {
                var display = $('.pseudo-scroll-bar-2').attr('data-display');
                if ($(window).scrollTop() + $(window).height() > table_offset_top_2 + $('.add-pseudo-scroll-bar-2').height() && display == 'true' || $(window).scrollTop() + $(window).height() < table_offset_top_2 && display == 'true') {
                    $('.pseudo-scroll-bar-2').hide().attr('data-display', false);
                } else if ($(window).scrollTop() + $(window).height() < table_offset_top_2 + $('.add-pseudo-scroll-bar-2').height() && table_offset_top_2 < $(window).scrollTop() + $(window).height() && display == 'false') {
                    $('.pseudo-scroll-bar-2').show().attr('data-display', true);
                }
            });
        }
    }
}

$('.btn-delete-file').on('click', function() {
    if ($(this).data('requestRunning')) {
        return;
    }
    $(this).data('requestRunning', true);
    var fileId = $(this).data('id');
    $('#file-id').val(fileId);
    $("#upload-file-form").submit();
});

window.onbeforeunload = function() {
    window.scrollTo(0, 0);
}
