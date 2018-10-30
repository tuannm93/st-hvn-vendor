var storageCommon = (function () {

    var $window = $(window);
    var currentScreenKey = 'curScreenName';

    var getPathName = function () {
        return window.location.pathname;
    };

    var createContentOfStorage = function (data) {
        return {
            'data': data
        }
    };

    var currentScreenName = function () {
        if (localStorage.hasOwnProperty(currentScreenKey))
            return localStorage.getItem(currentScreenKey);
        else
            return '';
    };

    var setStorage = function (screenName, data) {
        localStorage.setItem(screenName, JSON.stringify(data));
    };

    var getStorage = function (screenName) {
        if (localStorage.hasOwnProperty(screenName))
            return JSON.parse(localStorage.getItem(screenName));
        else
            return {};
    }

    var removeAllStorage = function() {
        localStorage.clear();
    };

    var fillDataToForm = function (formData) {
        var formId = $(formData).attr('id');
        var inputs = $(formData).find('input').not(':input[type=button], :input[type=submit], :input[type=reset], :input[name=_token]');

        var form = document.getElementById(formId);

        $.each(inputs, function (i, el) {
            $(form).getElementById(el.attr('id')).val(el.val());
        });
    };

    var updateHistory = function () {
        history.replaceState(null, null, getPathName());
    };

    var goBack = function () {
        var curPathName = getPathName();

        var storage = getStorage(curPathName),
            data = storage.data;

        if (jQuery.isEmptyObject(storage) || jQuery.isEmptyObject(data))
            return false;

        if (typeof data.formData != 'undefined' || data.formData != '')
            fillDataToForm(data.formData);

        if (typeof data.hmtl != 'undefined' || data.html != '')
            $(data.areaHtml).html(data.html);
    };

    var loadData = function () {
        // $window.on('navigate', function (e, data) {
        //     var direction = data.state.direction;
        //
        //     // detect back button
        //     if (direction == 'back') {
        //         goBack();
        //     }
        //
        //     // if detect forward button
        //     //if (direction == 'forward') {}
        // });
        $window.on('hashchange', function (e) {
            console.log(e);
            var state = e.originalEvent.state;
            if (state != null) {
                goBack();
            }
        });
    };

    var autoRemove = function () {
        // each ten minutes, clear storage
        setTimeout(function () {
            removeAllStorage();
            }, 600000);
    };

    return {
        setStorage: setStorage,
        getStorage: getStorage,
        loadData: loadData,
        getPathName: getPathName,
        autoRemove: autoRemove,
        updateHistory: updateHistory
    };
})();
