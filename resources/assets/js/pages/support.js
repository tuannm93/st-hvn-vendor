var progressBlock = $('.progress-block'),
    progressEl    = $('.progress');

$(document).ready(function() {

    $("body").on('click', '#completion', function (e) {
        var url = $(this).data('url');
        if($('#supportForm').valid()){
            $('#completion').prop('disabled', true);
            $.ajax({
                type: 'get',
                url: url
            }).done(function (data) {
                if (data.canBid == true) {
                    getPosts($('#supportForm').serialize());
                }
                else {
                    $('#supportModal').find('.auction-support-content').html(data.view);
                }
            })
        }
    });

    $("body").on('click', '#understandBtn', function (e) {
        $.ajax({
            type: 'post',
            data: {
                corpId: $('#updateMcorpId').val()
            },
            url: urlUpdateJbrStatus,
            beforeSend: function() {

            },
            success: function(data) {
                var status = data.status;
                if(status == 200){
                    $("#confirmation").hide();
                    $("#kihon_info").show();
                }
            }
        });
    });

    $("body").on('click', '#notUnderstandBtn', function (e) {
        $("#confirmation").hide();
        $("#contact").show();
    });

    $("body").on('click', '.closeBtn', function (e) {
        $("#supportModal").modal('hide');
    });

    $("body").on('click', '#btnDone', function (e) {
        $.ajax({
            type: 'post',
            data: {
                popup_stop_flg: $('#popup_stop_flg').is(":checked") ? 1 : 0
            },
            url: urlComplete,
            beforeSend: function() {

            },
            success: function(data) {
                $("#supportModal").modal('hide');
                location.reload();
            }
        });
    });
});

function getPosts(data) {
    $.ajax({
        type: 'post',
        url: urlPostData,
        data: data,
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
            progressBlock.show();
            progressEl.show();
        },
        complete: function () {
            progressBlock.hide();
            progressEl.hide();
        },
        success: function (data) {
            if (!data) {
                $("#supportModal").modal('hide');
                location.reload();
            } else {
                $('.content-support').html(data);
            }
        },
        error: function (err) {
            console.log('error');
        }
    });
}
