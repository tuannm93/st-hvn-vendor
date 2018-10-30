var AuctionExclusion = function() {
    function insertSettingHoliday() {
        $('#addVacation').on('click', function() {
            console.log(forCount)
            $('#group-vacation').find('input:not([type=hidden])').each(function(key, element) {
                var number = forCount + key;
                var name = $(element).attr("name", "holiday_date[" + number + "]");
            });
            forCount += 5;
            var groupVacation = $('#group-vacation').html();
            $('#setting-vacation').append(groupVacation);
            Datetime.initForDatepicker();
        });
    }

    function initDate() {
        Datetime.initForDatepicker();
        Datetime.initForTimepicker();
    }

    function init() {
        insertSettingHoliday();
        initDate();
    }

    return {
        init: init
    }
}();
$(document).ready(function() {
    AuctionExclusion.init();
    FormUtil.validate('#form-auction-exclusion');
});
