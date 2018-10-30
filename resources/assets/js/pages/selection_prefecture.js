var $selectAll = $('#select_all');
var $listSelect = $(".checkbox-selection");
var $checkboxSelection = $('.checkbox-selection');
var $formLabel= $('.form-check-label');

var SelectionPrefecture = function () {

    function checkAll() {
        $selectAll.on("click", function(){
            $listSelect.prop('checked', $selectAll.prop('checked'));
        });
    }

    $checkboxSelection.on('click', function() {
        $formLabel.addClass('fix-color-label-selection');
    });

    /**
     * Set function
     */
    function init() {
        checkAll();
    }

    return {
        init: init
    }
}();