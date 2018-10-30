var Currency = function() {

    const YEN = '円';
    const EN_LOCALES = 'en-EN';

    function formatNumberToCurrency(number) {
        var result = '';
        if (number != null && !isNaN(number.toString())) {
            result = parseFloat(number).toLocaleString(EN_LOCALES) + YEN;
        }
        return result;
    }

    return {
        formatNumberToCurrency: formatNumberToCurrency
    }

}();