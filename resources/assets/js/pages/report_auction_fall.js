var ReportAuctionFall = function() {
    var progressBlock = $('.progress-block'),
        progressEl = $('.progress'),
        currentSort = '',
        currentDirection = '';
    function getDataTable(url, sort, direction) {
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
                progressBlock.show();
                progressEl.show();
            },
            complete: function () {
                progressBlock.hide();
                progressEl.hide();
            },
            success: function (data) {
                jQuery('#table-report-auction-fall').html(data);
                if (jQuery('.next').length && sort != undefined){
                    var href = jQuery('.next').attr('data-url')+'&sort='+sort+'&direction='+direction;
                    jQuery('.next').attr('data-url', href);
                }
                if (jQuery('.previous').length && sort != undefined){
                    var href = jQuery('.previous').attr('data-url')+'&sort='+sort+'&direction='+direction;
                    jQuery('.previous').attr('data-url', href);
                }
            },
            error: function () {
                console.log('問題がありました。');
            }
        });
    }
    function init() {
        getDataTable(jQuery('#table-report-auction-fall').attr('data-url'));
        $(document).on("click",".sort",function(e){
            var url = jQuery(this).attr('data-url');
            currentSort = jQuery(this).attr('data-sort');
            currentDirection = jQuery(this).attr('data-direction');
            getDataTable(url, jQuery(this).attr('data-sort'), jQuery(this).attr('data-direction'));
        });
        $(document).on("click",".previous",function(e){
            var url = jQuery(this).attr('data-url');
            getDataTable(url, currentSort, currentDirection);
        });
        $(document).on("click",".next",function(e){
            var url = jQuery(this).attr('data-url');
            getDataTable(url, currentSort, currentDirection);
        });
    }
    return {
        init: init
    }
}();
jQuery(document).ready(function () {
    ReportAuctionFall.init();
});