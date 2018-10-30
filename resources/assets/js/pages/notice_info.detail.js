var NoticeInfoDetail = function() {
    return {
        init: function() {
            var answerValue = "";

            $('.button-answer').on('click', function() {
                $('#confirm_answer_value').html($(this).text());
                answerValue = $(this).text();
                $('#confirm-answer').modal('show');
            });

            $('#btn-confirm-answer').on('click', function() {
                $('#answer').val(answerValue);
                $('#submit-answer').submit();
            });

            $(document).ready(function() {
                $('.back-to-index').on('click', function() {
                    let url = $(this).attr('data-url');
                    window.location = url;
                })
            });
        }
    }
}();