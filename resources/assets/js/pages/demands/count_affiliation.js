var CountAff = function () {
    var specifyTime = $("input[name='demandInfo[contact_desired_time]'][type!=hidden]"),
        desiredTimeFrom = $("input[name='demandInfo[contact_desired_time_from]'][type!=hidden]"),
        desiredTimeTo = $("input[name='demandInfo[contact_desired_time_to]'][type!=hidden]"),
        estimatedTimeFrom = $("input[name='demandInfo[contact_estimated_time_from]'][type!=hidden]"),
        address1 = $('#address1'),
        address2 = $('#address2'),
        postcode = $('#postcode'),
        estimatedTimeTo = $("input[name='demandInfo[contact_estimated_time_to]'][type!=hidden]"),
        siteEl = $("#site_id"),
        genreEl = $("#genre_id"),
        categoryEl = $("#category_id"),
        lngEl = $('#longitude'),
        latEl = $('#latitude'),
        commissionCorp = $('.cyzen_commission_corp'),
        numberKamenten = $('.number-kameiten');
    var initProgress = function initProgress() {
        return new progressCommon();
    };
    var runDetail = function runDetail() {
        if (latEl.val() === '' && lngEl.val() === '') {
            if (address1.val() !== '' && address2.val() !== '') {
                ajaxCountAff();
            }
        } else {
            if (address1.val() !== '' && address2.val() !== '') {
                postApi();
            }
        }
        return false;
    };
    var hiddenCountData = function hiddenCountData() {
        var radioCheckTime = $("input[name='demandInfo[is_contact_time_range_flg]']");
        radioCheckTime.on('change', function () {
            if (estimatedTimeFrom.val() === ''
                || estimatedTimeTo.val() === '') {
                $('.number-kameiten').hide();
            } else {
                ajaxCountAff();
            }
        });
        var time = $(".count");
        time.on('change', function () {
            if (specifyTime.val() === '' && (estimatedTimeFrom.val() === '' || estimatedTimeTo.val() === '') && (desiredTimeFrom.val() === '' || desiredTimeTo.val() === '')) {
                $('.number-kameiten').hide();
            }
        });
        return false;
    };
    var onSelect = function onSelect() {
        if (siteEl.val() !== '' && genreEl.val() !== '' && categoryEl.val() !== '') {
            if (estimatedTimeFrom.val() !== '' && estimatedTimeTo.val() !== '') {
                postApi();
            }
            if (desiredTimeFrom.val() !== '' && desiredTimeTo.val() && (estimatedTimeFrom.val() === '' || estimatedTimeTo.val() === '')) {
                postApi();
            }
            if (specifyTime.val() !== '' && (estimatedTimeFrom.val() === '' || estimatedTimeTo.val() === '')) {
                postApi();
            }
        } else {
            $('.number-kameiten').hide();
        }
        return false;
    };
    var pasteDate = function pasteDate() {
        $('.count').bind("keyup paste", function (e) {
            setTimeout(function () {
                postApi();
            }, 1000);
        });
        return false;
    };
    var addressChange = function addressChange() {
        address2.on('change', function () {
            setTimeout(function () {
                if (address1.val() !== '') {
                    ajaxCountAff();
                }
            }, 1000);
            return false;
        });
    };
    var progress = initProgress();
    var ajaxCountAff = function ajaxCountAff() {
        if (address1.val() !== '') {
            var prefecture = $('#address1 option[value=' + $('#address1').val() + ']').text();
        } else {
            var prefecture = '';
        }
        var municipality = $("input[name='demandInfo[address2]']").val();
        var laterAddress = $("input[name=\"demandInfo[address3]\"]").val();
        var address = laterAddress + ' ' + municipality + ' ' + prefecture,
            apiKey = $('.number-kameiten').data('apikey');
        if (address !== '') {
            // delete X-CSRF-TOKEN
            delete $.ajaxSettings.headers["X-CSRF-TOKEN"];
            // get location in geocode google api;
            $.ajax({
                type: "GET",
                url: "https://maps.googleapis.com/maps/api/geocode/json",
                async: true,
                data: { 'address': address, 'key': apiKey },
                success: function success(response) {
                    try {
                        if(response.results[0]){
                            latEl.val(response.results[0].geometry.location.lat);
                            lngEl.val(response.results[0].geometry.location.lng);
                            postApi();
                        }
                    } catch (err) {
                        console.log(err);
                    }
                }
            });
        } else {
            $('.number-kameiten').hide();
        }
        $.ajaxSettings.headers["X-CSRF-TOKEN"] = $('meta[name="csrf-token"]').attr('content');
    };

    // post api for count kameiten
    var postApi = function postApi() {
        var specifyTimeTo = specifyTime.val(),
            timeAdjustmentFrom = desiredTimeFrom.val(),
            timeAdjustmentTo = desiredTimeTo.val(),
            estimatedFrom = estimatedTimeFrom.val(),
            estimatedTo = estimatedTimeTo.val(),
            timeFrom = '',
            timeTo = '',
            isEstimated = false,
            isSpecifyTime = false,
            specifyTimeFrom = '',
            selectionSystem = $('#selection_system').val();
        if (estimatedFrom !== '' || estimatedTo !== '') {
            timeFrom = estimatedFrom;
            timeTo = estimatedTo;
            isEstimated = true;
        } else if (specifyTimeTo !== '') {
            timeTo = specifyTimeTo;
            var timeMinus = Date.parse(specifyTimeTo);
            var minus5Minutes = new Date(timeMinus - 300000);
            specifyTimeFrom = minus5Minutes.getFullYear() + '/' + ('0' + (minus5Minutes.getMonth() + 1)).slice(-2) + '/' + minus5Minutes.getDate() + ' ' + minus5Minutes.getHours() + ':' + ('0' + minus5Minutes.getMinutes()).slice(-2);
            timeFrom = specifyTimeFrom;
            isSpecifyTime = true;
        } else {
            timeFrom = timeAdjustmentFrom;
            timeTo = timeAdjustmentTo;
        }
        var lat = latEl.val();
        var lng = lngEl.val();

        var condition = $.param({
            'data[no]': -1,
            'data[site_id]': siteEl.val(),
            'data[category_id]': categoryEl.val(),
            'data[postcode]': postcode.val(),
            'data[address1]': address1.val(),
            'data[address2]': address2.val(),
            'data[corp_name]': '',
            'data[exclude_corp_id]': '',
            'data[genre_id]': genreEl.val(),
            'data[view]': true,
            'data[time_from]': timeFrom,
            'data[time_to]': timeTo,
            'data[lat]': lat,
            'data[lng]': lng,
            'data[is_estimated]': isEstimated,
            'data[is_specifyTime]' : isSpecifyTime,
            'data[commition_info_count]': 0
        });
        var postData = JSON.stringify(condition);
        if (siteEl.val() !== '' && genreEl.val() !== '' && categoryEl.val() !== '') {
            if (specifyTime.val() !== ''
                || (estimatedTimeFrom.val() !== '' && estimatedTimeTo.val() !== '')
                || (desiredTimeFrom.val() !== '' && desiredTimeTo.val() !== '')
            ) {
                jQuery.ajax({
                    url: jQuery('.count-affiliation').attr('countaffiliation'),
                    type: 'post',
                    data: postData,
                    async: true,
                    dataType: 'json',
                    processData: false,
                    xhr: function xhr() {
                        return progress.createXHR();
                    },
                    complete: function complete() {
                        //return
                    },
                    success: function success(res) {
                        if (selectionSystem === "0" && commissionCorp.val() !== "2" && commissionCorp.val() !== "1" ) {
                            numberKamenten.html('<p class="font-weight-bold pl-lg-4 mb-0">GS対応可能加盟店 : ' + res + '</p>');
                            numberKamenten.show();
                        } else if (commissionCorp.val() === "2" ) {
                            numberKamenten.html('<p class="font-weight-bold text-danger pl-lg-4 mb-0">現在のカテゴリ、都道府県には自動取次対象加盟店があります。</p>');
                            numberKamenten.show();
                        } else if (commissionCorp.val() === "1" ) {
                            numberKamenten.html('<p class="font-weight-bold text--info pl-lg-4 mb-0">現在のカテゴリ、都道府県には自動選定対象加盟店があります。</p>');
                            numberKamenten.show();
                        } else {
                            numberKamenten.hide();
                        }
                    },
                    error: function error(err) {
                        console.log(err);
                    }
                });
            } else {
                $('.number-kameiten').hide();
            }
        }
        return false;
    };
    return {
        hiddenCountData: hiddenCountData,
        onSelect: onSelect,
        runDetail: runDetail,
        addressChange: addressChange,
        ajaxCountAff: ajaxCountAff,
        postApi: postApi,
        pasteDate: pasteDate
    };
}();