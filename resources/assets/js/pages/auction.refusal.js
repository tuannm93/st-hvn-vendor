var AuctionRefusal = function () {

    var refusalBtn = '.refusalButton',
        $supportLimit = $('.support-limit'),
        $dealAlreadyNotSupport = $('.deal-already-not-support'),
        $dealAlreadySupport = $('.deal-already-support'),
        $formAuctionRefusal = $('#form-auction-refusal'),
        $dealALready = $('.deal-already'),
        $commissionEl = $('#commissionData'),
        $supportAlready = $('.support-already'),
        $refusalDataTemp = $('#temp-refusal'),
        $refusalPopup =$("#refusalModal");

    var controlPopup = function (popup, isShow) {
        if (isShow) {
            popup.modal('show');
        } else {
            popup.modal('hide');
        }
    };

    var getParameter = function (control) {
        var parameter = {id: 1, url: '', postUrl: ''};
        parameter.id = $(control).attr('data-id');
        parameter.url = $(control).attr('data-url-refusal');
        parameter.postUrl = $(control).attr('data-url-post-refusal');
        return parameter;
    };

    var renderRefusalView = function (control) {

        var param = getParameter(control);

        // create new progressCommon
        var progress = new progressCommon();

        $.ajax({
            type: 'get',
            url: param.url,
            xhr: function () {
                return progress.createXHR();
            },
            beforeSend: function () {
                progress.controlProgress(true);
            },
            complete: function () {
                progress.controlProgress(false);
            },
            success: function (data) {
                // support_limit
                if (data.screen == 0) {
                    $supportLimit.removeClass('d-none');
                }

                // deal_already
                if (data.screen == 1) {
                    if (data.demandStatus == 6 || data.demandStatus == 9) {
                        $dealAlreadyNotSupport.removeClass('d-none');
                    } else {
                        $dealAlreadySupport.removeClass('d-none');
                        $dealAlreadySupport.find('#refusal_modified').val(data.modified);
                        $formAuctionRefusal.attr('action', param.postUrl);
                    }
                    $dealALready.removeClass('d-none');
                }

                // support_already
                if (data.screen == 2) {
                    $commissionEl.text(data.commissionData);
                    $supportAlready.removeClass('d-none');
                }

                Datetime.initForDateTimepicker();

                // finally, show popup
                controlPopup($refusalPopup, true);
            },
            error: function (err) {
                console.log(err);
            }
        });
    };

    var resetRefusalPopup = function () {
        $refusalPopup.on('hidden.bs.modal', function (e) {
            if (!$supportLimit.hasClass('d-none'))
                $supportLimit.addClass('d-none');

            if (!$dealALready.hasClass('d-none')) {
                $dealALready.addClass('d-none');

                if (!$dealAlreadyNotSupport.hasClass('d-none'))
                    $dealAlreadyNotSupport.addClass('d-none');

                if (!$dealAlreadySupport.hasClass('d-none'))
                    $dealAlreadySupport.addClass('d-none');
            }

            if (!$supportAlready.hasClass('d-none'))
                $supportAlready.addClass('d-none');
        });
    };

    var init = function () {
        $(document).on('click', refusalBtn, function (e) {
            var id = $(this).data("id");
            $refusalDataTemp.data("id", id);
            // render view
            renderRefusalView(this);

            e.preventDefault();
        });

        // reset popup when closed
        resetRefusalPopup();
    };

    return {
        //main function to initiate the module
        init: init
    };
}();
