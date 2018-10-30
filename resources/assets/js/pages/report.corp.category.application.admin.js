$(document).ready(function () {
    ReportCorpCateApp.init();
});

var ReportCorpCateApp = function () {
    return {
        init: function () {
            $("button.checkAll").click(function () {
                var $this = $(this);
                var mode = ($this.data('mode') + 1) % 2;
                $('[name="check[]"]').prop('checked', (mode === 1)).trigger('change');
                $this.text(BTN_CHECK_ALL_TEXTS[mode]).data('mode', mode);
            });
        }
    }
}();
