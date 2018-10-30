var CorpTargetArea = function() {
    return {
        init: function() {
            $(".corpAreaBtn").on('click', function(e) {
                e.stopImmediatePropagation();
                e.preventDefault();
                var url = $(this).data('url');
                $.ajax({
                    type: 'get',
                    url: url
                }).done(function(data) {
                    $("#agreementPreview").modal('show');
                    $('#agreementPreview').find('.agreement-support-content').html(data);
                });
            });
            $(".closeBtn").on('click', function() {
                $("#agreementPreview").modal('hide');
            });
        }
    }
}();
$(document).ready(function() {
    CorpTargetArea.init();
});