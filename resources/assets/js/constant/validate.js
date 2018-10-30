var Validate = function () {
    return {
        message: function () {
            return {
                required: '必須入力です。',
                maxLenght: function (number) { // data-max='20'
                    return number + '文字以内で設定してください。'
                },
                minLenght: function (number) { // data-min='8'
                    return number + '文字以上を設定してください。'
                },
                lenght: function (min, max) { // data-lenght='8-20'
                    return min + '~' + max + '文字以内に設定してください。'
                },
                formatNumber: '正しい番号の形式を入力してください。', // data-type='number'
                formatEmail: '正しいメール形式を入力してください。', // data-type='email'
                formatPhone: '有効な電話番号形式を入力してください。', // data-type='phone'
                formatName: '英字のみを入力してください。' // data-type='name'
            }
        },
        regex: function () {
            return {
                required: new RegExp("([^\s])"),
                maxLenght: function (number) {
                    return new RegExp("^.{0," + number + "}$")
                },
                minLenght: function (number) {
                    return new RegExp("^.{" + number + ",}$")
                },
                lenght: function (min, max) {
                    return new RegExp("^.{" + min + "," + max + "}$")
                },
                formatNumber: new RegExp("^-?[0-9]+\.?[0-9]*$"),
                formatEmail: new RegExp("^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$"),
                formatPhone: new RegExp("^[0-9]{10,12}$"),
                formatName: new RegExp("^[a-zA-Z][a-zA-Z ]+$")
            }
        }
    }
}();