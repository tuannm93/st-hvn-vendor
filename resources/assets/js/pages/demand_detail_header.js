var DemandDetailHeader = function() {
    function init() {
        var demandId = $('#demand_id').val();

        $('#demand_delete').click(function() {
            $('#delete_confirm_dialog').modal('show');
        });
        
        $('#delete_confirm_close').click(function() {
            $('#delete_confirm_dialog').modal('hide');
        });
        
        // $('#delete_confirm_approved').click(function() {
        //     if (demandId) {
        //         $('form#demand_detail_form').attr('action', '/demand/delete/' + demandId).submit();
        //     }
        // });

        $('#commission_print').click(function() {
            $('#commission_print_dialog').modal('show');
        });
        
        $('#commission_print_close').click(function() {
            $('#commission_print_dialog').modal('hide');
        });
        
        if (demandId) {
            var url = '/commission_print/' + demandId;
            $.get(url, function(res) {
                $('#commission_print_dialog #display_modal_area').html(res);
            });
        }
    }
    
    return {
        init: init
    }
}();

$(document).ready(function () {
    DemandDetailHeader.init();
});