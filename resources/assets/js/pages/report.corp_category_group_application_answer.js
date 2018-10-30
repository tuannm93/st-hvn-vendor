var ReportCorpCategoryGroupApplicationAnswer = function () {

    var pageData = $('#page-data');
    var exportUrl = pageData.data('export-csv-url');
    var token = $('#csrf-token').val();
    var progressBlock = $('.progress-block');
    var progressEl = $('.progress');

    function exportCsv() {
        $('#exportCsv').on('click',function(){
            $.ajax({
                type: 'post',
                data: {

                },
                url: exportUrl,
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
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("X-CSRF-TOKEN", token);
                    progressBlock.show();
                    progressEl.show();
                },
                complete: function () {
                    progressBlock.hide();
                    progressEl.hide();
                }
            }).done(function (data) {

            }).fail(function (jXHR, textStatus) {
            });
        });
    }

    /**
     * Set function
     */
    function init() {
        exportCsv();
    }

    return {
        init: init
    }
}();

$(document).ready(function () {
    ReportCorpCategoryGroupApplicationAnswer.init();
});