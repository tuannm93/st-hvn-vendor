var checkGet = function () {
    function validateGroupSelect(array) {
        array.forEach(function(element){
            addEventListen(element.item1, element.item2, false);
            addEventListen(element.item2, element.item1, true);
        })
    }

    function addEventListen(ele1, ele2, reverse) {
        processCheckVal(ele1, ele2, reverse);
        ['blur', 'change'].forEach(function(event){
            ele1.inputId.on(event,function(){
                processCheckVal(ele1, ele2, reverse, event);
            });
        });
    }

    function processCheckVal(ele1, ele2, reverse, event) {
        var regex = /^[^0\s][0-9]*$/;
        var eleRegex = reverse ? ele1 : ele2;
        var eleNotRegex = reverse ? ele2 : ele1;
        if (eleNotRegex.inputId.val() || regex.test(eleRegex.inputId.val())) {
            if (eleNotRegex.inputId.val()) {
                processIsValid(eleRegex.inputId, event);
            }
            if (regex.test(eleRegex.inputId.val())) {
                processIsValid(eleNotRegex.inputId, event);
            }
            ele2.feedbackId.empty();
            ele1.feedbackId.empty();
        } else {
            processIsInvalid(ele1.inputId);
            processIsInvalid(ele2.inputId);
        }
    }

    function processIsValid(element, event) {
        if (!element.hasClass('ignore')) {
            element.addClass('ignore');
        }
        if (element.hasClass('is-invalid')) {
            element.removeClass('is-invalid');
        }
        if (event === 'blur' && !element.hasClass('is-valid')) {
            element.addClass('is-valid');
        }
    }

    function processIsInvalid(element) {
        if (element.hasClass('ignore')) {
            element.removeClass('ignore');
        }
    }

    return {
        validateSelect: validateGroupSelect,
    }
}();


