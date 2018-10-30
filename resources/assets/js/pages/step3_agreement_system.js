var Step3AgreementSystem = function () {
    function eventBackButton() {
        $('#back_button').on('click', function () {
            window.location.href = urlBackStep3;
        });
    }

    function openPopupCategory() {
        $('#linkPopupCategory').on('click', function () {
            $.ajax({
                url: urlGetCategoryDialog,
                type: 'GET',
                data: {},
                success: function (response) {
                    $('#categoryDialogBodyId').html(response);
                    $('#popupCategoryId').modal('show');
                    selectCategory();
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });
    }

    function selectCategory() {
        $('.selectCategory').on('click', function () {
            var canForward = true;
            var checkLst = $('.genre_table_tr_td input:checked');
            $.each(checkLst, function (index, item) {
                var selectValue = $('#selectList_' + item.value);
                if (selectValue.val() === '') {
                    alert('専門性は必須です');
                    canForward = false;
                    return false;
                }
            });
            if (canForward === false) return;

            $("#categoryDialogFormId").submit();
        });
    }

    function openPopupArea() {
        $('#linkPopupArea').on('click', function () {
            $.ajax({
                url: urlGetAreaDialog,
                type: 'GET',
                data: {},
                success: function (response) {
                    $('#areaDialogBodyId').html(response);
                    $('#popupAreaId').modal('show');
                    onConfigurationArea();
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });
    }

    function onConfigurationArea() {
        $('.selectArea').on('click', function () {
            var addressCd = $(this).data('address');
            var address1 = $(this).data('city');
            $.ajax({
                url: urlPostAreaDialog,
                type: 'POST',
                data: {addressCd: addressCd, address1: address1},
                success: function (response) {
                    $('#areaDialogBodyId').html(response);
                    $('#popupAreaId').modal('show');
                    allCheckAndUncheck();
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });
    }

    function onViewConfigurationArea() {
        $('.viewSelectArea').on('click', function () {
            var addressCd = $(this).data('address');
            var address1 = $(this).data('city');
            $.ajax({
                url: urlViewAreaDialog,
                type: 'POST',
                data: {addressCd: addressCd, address1: address1},
                success: function (response) {
                    $('#areaDialogBodyId').html(response);
                    $('#popupAreaId').modal('show');
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });
    }

    function allCheckAndUncheck() {
        $('#all_check').on('click', function () {
            $(".postCheck").prop('checked', true);
        });

        $('#all_release').on('click', function () {
            $(".postCheck").prop('checked', false);
        });
    }

    function init() {
        eventBackButton();
        $('#step2').addClass('active');
        $('#step3').addClass('active');
        onViewConfigurationArea();
        openPopupCategory();
        openPopupArea();
    }

    return {
        init: init,
        onViewConfigurationArea: onViewConfigurationArea,
    }

}();

