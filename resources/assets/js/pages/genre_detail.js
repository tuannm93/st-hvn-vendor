var GenreDetail = function() {
    function display() {
        var normal1 = $('#normal1').val();
        var normal2 = $('#normal2').val();
        var immediately_small = $('#immediately_small').val();
        $('.normal1_dis').html(normal1);
        $('.normal2_dis').html(normal2);
        $('.immediately_small_dis').html(immediately_small);
    }

    function exclusion_pattern_dis() {
        var exclusion_pattern = $('#exclusion_pattern').val();
        var url  = $('#exclusion_pattern_dis').attr("data-url");
        if(exclusion_pattern != ''){
            $.get(url+'/'+ exclusion_pattern, function(data) {
                $('#exclusion_pattern_dis').html(data['result']);
            });

        } else {
            $('#exclusion_pattern_dis').html('');
        }
    }
    function init() {
        display();
        exclusion_pattern_dis();

        $('#normal1').change(function() {
            display();
        });

        $('#normal2').change(function() {
            display();
        });

        $('#exclusion_pattern').change(function() {
            exclusion_pattern_dis();
        });
    }
    return {
        init: init
    }
}();
jQuery(document).ready(function () {
    GenreDetail.init();
});