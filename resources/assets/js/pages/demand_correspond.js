var Demandcorrespons = function () {

    return {
        init: function () {
            $( "#responders" ).rules( "add", {
                required: function(element) {
                    return $("#CommissionCorrespondCorrespondingContens").val() != "";
                }
            });

            $( "#CommissionCorrespondCorrespondingContens" ).rules( "add", {
                required: function(element) {
                    return $("#responders").val() != "";
                }
            });
            $('#cancel').click(function(){
                $('.close').trigger('click');
            });

            var submit   = $("input[type='submit']");
            var id = $('#id').val();
            submit.click(function()
            {
                event.preventDefault();

                var url = urlPopupHistory;
                var data = $('#history-input-form').serialize();

                $.ajax({
                    url  : url,
                    data : data,
                    type : "post",
                    success: function (data) {
                        var modal = jQuery('#modal-popup');
                        modal.modal('hide');
                        let responders = $('#responders :selected').text();
                        let CommissionCorrespondCorrespondingContens = $('#CommissionCorrespondCorrespondingContens').val().replace(/\r?\n/g, '<br />');
                        let correspond_datetime = $('#correspond_datetime').val();
                        if(responders === "--なし--"){
                            $('#user-' + id ).html('');
                        } else {
                            $('#user-' + id ).html(responders);
                        }
                        $('#date-time-' + id ).html(correspond_datetime);
                        $('#content-' + id ).html(CommissionCorrespondCorrespondingContens);
                    },
                    error: function() {
                        console.log('error');
                    }
                });
            });

            $('#correspond_datetime').click(function() {
                $('#ui-datepicker-div').css('top', 100);     
            })
            $('#correspond_datetime').change(function() {
                $('#ui-datepicker-div').css('top', 100);     
            })
        }
    };
}();