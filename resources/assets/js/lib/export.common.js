var exportCommon = function () {
    var progressBlock = $('.progress-block'),
        progressEl = $('.progress');
    var exportFile = function (url, formData) {
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: formData.serialize(),
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
            success: function () {
                window.open(url, '_blank');
            },
            error: function (err) {
            }
        });
    };
    var init = function (url, control, formData) {
        $(control).on('click', function () {
            exportFile(url, formData);
        });
    };
    return {
        init: init
    };
}();