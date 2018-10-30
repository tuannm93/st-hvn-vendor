var demandModule = (function () {
    var $agencyInfoSection = $('#agency_info'),
        $normalInfoSection = $('.normal_info'),
        $bidInfoSection = $('#bid_infos'),
        $radioAbsoluteTimeSection = $('.absolute_time'),
        $radioRangeTimeSection = $('.range_time'),
        $maxLimitNumMessageEl = $('#MaxLimitNumMessage'),
        $autoCommissionMessageEl = $('#auto_commission_message'),
        $displayAutoCommissionMessageEl = $('#display_auto_commission_message'),
        $selectionSystemDivSection = $('#selection-system-div'),
        $demandStatusEl = $("#demand_status"),
        $orderFailReasonEl = $('#order_fail_reason'),
        $orderFailDateEl = $('#order_fail_date'),
        $crossSellSourceSiteEl = $('#cross_sell_source_site'),
        $crossSellSourceGenreEl = $('#cross_sell_source_genre'),
        $crossSellSourceCategoryEl = $('#cross_sell_source_category'),
        $attentionEl = $("#attention"),
        $sendCommissionInfoBtn = $('#sendCommissionInfoBtn'),
        $sendIntroduceInfoBtn = $('#sendIntroduceInfoBtn'),
        $sendCommissionInfoEl = $('#sendCommissionInfo'),
        $contactDesiredTimeEl = $("#contact_desired_time"),
        $notSendEl = $('#notSend'),
        $commissionTypeSection = $('.commission_type'),
        $totalCurrentViewSection = $('.total-current-views'),
        $beforeDemandInfoGenreId = $('#before_demandinfo_genre_id'),
        $demandContentEl = $('#demand-content'),
        $businessTripAmountEl = $('#business-trip-mount'),
        $middleNightCheckbox = $('#nighttime_takeover'),
        $visitTimeSection = $('.visit-time-div'),
        $hiddenTimeSection = $('.hidden-time'),
        $demandCommissionInfoSection = $('#partner_commission_info'),
        $count_aff = $('#count-data').data('count-aff');

    var $siteEl = $('#site_id'),
        $genreEl = $('#genre_id'),
        $categoryEl = $('#category_id'),
        $address1El = $('#address1'),
        $address2El = $('#address2'),
        $address3El = $('#address3'),
        $postcodeEl = $('#postcode'),
        $introduceInfoCountEl = $('#introduce_info_count'),
        $introductionEL = $('#introduction'),
        $selectionSystemEl = $('#selection_system'),
        $maxIndexEl = $('#max_index'),
        $loadMCorpSection = $('#load_m_corps'),
        $maxLimitNumText = $('#max_limit_num'),
        $visitTimeDiv = $('.visitTimeDiv'),
        $lat = $('#latitude'),
        $lng = $('#longitude');

    var $modalPopup = $('#modal-popup'),
        $modalDialog = $('#modal-dialog');

    var $searchAddressByZipButton = $('#search-address-by-zip'),
        $resetRadioButton = $('#reset-radio'),
        $plus15MinutesButton = $('#plus-15-minus'),
        $copyButton = $('#demand_copy'),
        $crossButton = $('#demand_cross'),
        $destinationCompanyButton = $('#destination-company');

    var defineListSiteIds = [861, 889, 890, 910, 1312, 1313, 1314, 647, 953];
    var listSiteId = [861, 863, 889, 890, 1312, 1313, 1314];
    var crossSellDisabledList = ['861', '863', '889', '890', '1312', '1313', '1314'];
    var tablet_width = 768;

    // var localStorage = window.localStorage;

    var initProgress = function () {
        return new progressCommon();
    };

    var progress = initProgress();

    var copyDemand = function (url) {
        $copyButton.on('click', function (e) {
            window.open().location.href = url;
        });
    };

    var crossDemand = function (url) {
        $crossButton.on('click', function (e) {
            window.open().location.href = url;
        });
    };

    var demandObj = {
        limit: {},
        writeBrowse: function () {
            $.ajax({
                type: 'GET',
                url: apiRoutes.getWriteBrowseUrl,
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function () {
                    demandObj.countBrowse();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        countBrowse: function () {
            $.ajax({
                type: 'POST',
                url: apiRoutes.postCountBrowseUrl,
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    $totalCurrentViewSection.html(res.data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        renderHtml: function (items, isDefaultOption, defaultSelected) {
            if (typeof isDefaultOption == 'undefined' || isDefaultOption == null)
                isDefaultOption = true;
            if (typeof defaultSelected == 'undefined' || defaultSelected == null)
                defaultSelected = '';

            var html = '';

            if (isDefaultOption)
                html += '<option value>--なし--</option>';

            for (var i in items) {
                var category = items[i];
                html += defaultSelected == i ? `<option selected="selected" value=${i}>${category}</option> ` : `<option value=${i}>${category}</option>`;
            }
            return html;
        },
        triggerFile: function (section) {
            section.find('input[type="file"]').trigger('click');
        },
        submitUploadAttachFile: function (btn, url) {
            $(btn).parents('form').attr('action', url).submit();
        },
        deleteAttachedFile: function (btnDelete, url) {
            $(btnDelete).parents('form').attr('action', url + '/' + $(btnDelete).data('attached_id')).submit();
        },
        resetAttachedFileValue: function (btnReset) {
            var parent = $(btnReset).parent().prev('.trigger-section-file');
            var file = parent.find('input[type="file"]');
            file.replaceWith(file.val('')).clone(true);
            parent.find('.reset-file-name').html('No file choosen');
        },
        setDemandStatus: function (isSelected) {
            $demandStatusEl.find('option:selected').removeAttr('selected');

            if (isSelected)
                $demandStatusEl.val($demandStatusEl.find('option').eq(1).val());

        },
        controlSelectionSystem: function (id, arr) {
            if (jQuery.inArray(parseInt(id), arr) !== -1) {
                // move item
                $selectionSystemEl.find('option').each(function (i, opt) {
                    if ($(opt).is(':selected')) {
                        $(opt).removeAttr('selected');
                    }
                    if ($(opt).text() == '入札式+自動') {
                        $(opt).css('display', 'none');
                    }
                });
                demandObj.setDemandStatus(true);
                demandObj.changeModeOrderFailReason();
            } else {
                $selectionSystemEl.find('option').each(function (i, opt) {
                    if ($(opt).css('display') == 'none') {
                        $(opt).removeAttr('style');
                    }
                });
                demandObj.setDemandStatus(false);
                demandObj.changeModeOrderFailReason();
            }
        },
        controlSection: function (section, isShow) {
            if (isShow)
                section.show();
            else
                section.hide();
        },
        controlDisabled: function (el, isDisabled) {
            if (isDisabled)
                el.attr('disabled', true);
            else
                el.removeAttr('disabled');
        },
        addMinutes: function (minutes) {
            if (typeof minutes == 'undefined' || minutes == null)
                minutes = 15;

            var now = new Date();

            now.setMinutes(now.getMinutes() + minutes);
            var hours = (now.getHours() < 10 ? '0' : '') + now.getHours();
            var min = (now.getMinutes() < 10 ? '0' : '') + now.getMinutes();
            var time = demandObj.dateNowFormat() + ' ' + hours + ':' + min;
            $contactDesiredTimeEl.val(time);

        },
        middleNightCheckbox: function (checkbox) {
            var hours = (new Date()).getHours();
            if (hours >= 21 || hours < 7) {
                $(checkbox).prop('checked', true);
            }
        },
        getUserList: function () {
            $.ajax({
                type: 'GET',
                url: apiRoutes.getUserListUrl,
                data: {},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    var comissionNoteSenderHtml = demandObj.renderHtml(res.data);
                    var apointerHtml = demandObj.renderHtml(res.data, true, apiRoutes.currentUserId);
                    $(document).find('.commission_note_sender').html(comissionNoteSenderHtml);
                    $(document).find('.appointers').html(apointerHtml);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        changeCategorySelection: function () {
            var categoryId = $categoryEl.find('option:selected').val();
            demandObj.changeCategoryCommissionData(categoryId);
        },
        changeCategoryCommissionData: function (categoryId) {

            for (var i = 0; i <= 19; i++) {
                var corpId = $('#CommissionInfo' + i + 'CorpId').val();

                if (corpId != '' && categoryId != '') {
                    if (isNaN(corpId) == false) {
                        $.ajax({
                            type: 'GET',
                            url: apiRoutes.getCommissonChangeUrl,
                            data: {num: i, category_id: categoryId, corp_id: corpId},
                            xhr: function () {
                                return progress.createXHR();
                            },
                            beforeSend: function () {
                                progress.controlProgress(true);
                            },
                            complete: function () {
                                progress.controlProgress(false);
                            },
                            success: function (res) {
                                var data = response.data;
                                var dt = data.split(",");
                                var order_fee_dis = '';
                                var unit_dis = '取次時手数料率';
                                var commission_inp_dis = '<input name="data[CommissionInfo][' + dt[0] + '][commission_fee_rate]" id="commission_fee_rate' + dt[0] + '" style="width:100px" size="20" maxlength="10" type="text"/> ％';
                                if (dt[1] != '' && dt[2] != '') {
                                    if (dt[2] == '0') {
                                        order_fee_dis = dt[1].toString().replace(/(\d)(?=(\d\d\d)+$)/g, '$1,') + '円';
                                        unit_dis = '取次先手数料';
                                        commission_inp_dis = '<input name="data[CommissionInfo][' + dt[0] + '][corp_fee]" id="corp_fee' + dt[0] + '" style="width:100px" size="20" maxlength="10" type="text"/> 円';
                                    } else {
                                        order_fee_dis = dt[1] + '％';
                                        unit_dis = '取次時手数料率';
                                        commission_inp_dis = '<input name="data[CommissionInfo][' + dt[0] + '][commission_fee_rate]" id="commission_fee_rate' + dt[0] + '" style="width:100px" size="20" maxlength="10" type="text"/> ％';
                                    }
                                }
                                // 表示用
                                $('#order_fee_display' + dt[0]).html(order_fee_dis);
                                $('#m_corp_category_note_display' + dt[0]).html(dt[3]);
                                // hidden用
                                $('#order_fee' + dt[0]).val(dt[1]);
                                $('#order_fee_unit' + dt[0]).val(dt[2]);
                                $('#commission_order_fee_unit' + dt[0]).val(dt[2]);
                                $('#m_corp_category_note' + dt[0]).val(dt[3]);
                                // inputタグ用
                                $("#unit_display" + dt[0]).html(unit_dis);
                                $("#commission_inp_display" + dt[0]).html(commission_inp_dis);
                            },
                            error: function (err) {
                                console.log(err);
                            }
                        });
                    }
                }
            }
        },
        changeSiteId: function () {
            var siteId = $siteEl.val();
            $siteEl.attr('disabled', 'true');
            $.ajax({
                type: 'GET',
                url: apiRoutes.getGenreListBySiteIdUrl,
                data: {site_id: siteId},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    $siteEl.removeAttr('disabled');
                    $siteEl.focus();
                    var genresList = res.data;
                    var genreHtml = '';
                    if (ctiDemand) {
                        genreHtml = demandObj.renderHtml(genresList, true, ctiGenreId);
                    } else {
                        genreHtml = demandObj.renderHtml(genresList, true);
                    }
                    $genreEl.html(genreHtml);
                    initTime();

                    $visitTimeSection.css({display: 'block'});
                    $hiddenTimeSection.css({display: 'block'});
                    demandObj.controlDisabled($commissionTypeSection, false);

                    if (listSiteId.indexOf(parseInt(siteId)) !== -1) {
                        demandObj.controlDisabled($crossSellSourceSiteEl, false);
                        demandObj.controlDisabled($crossSellSourceGenreEl, false);
                    } else {
                        demandObj.controlDisabled($crossSellSourceSiteEl, true);
                        demandObj.controlDisabled($crossSellSourceGenreEl, true);
                    }
                },
                error: function (err) {
                    $siteEl.removeAttr('disabled');
                    $siteEl.focus();
                    console.log(err);
                }
            });

            demandObj.getCategoryList();
            demandObj.getSiteData(siteId);
            demandObj.crossSellDisabled(siteId);
            demandObj.controlSelectionSystem(siteId, defineListSiteIds);
            $selectionSystemEl.val('');
        },
        crossSellDisabled: function (siteId) {
            if (crossSellDisabledList.indexOf(siteId) != -1) {
                demandObj.controlDisabled($crossSellSourceSiteEl, false);
                demandObj.controlDisabled($crossSellSourceGenreEl, false);
                demandObj.controlDisabled($crossSellSourceCategoryEl, false);
                $selectionSystemEl.val(0);
            } else {
                demandObj.controlDisabled($crossSellSourceSiteEl, true);
                demandObj.controlDisabled($crossSellSourceGenreEl, true);
                demandObj.controlDisabled($crossSellSourceCategoryEl, true);
            }
        },
        getCategoryList: function () {
            $.ajax({
                type: 'GET',
                url: apiRoutes.getCategoryListByGenreIdUrl,
                data: {genreId: null},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    var categories = res.data;
                    var categoryHtml = demandObj.renderHtml(categories);
                    $categoryEl.html(categoryHtml);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        getCategory: function (genreId, el) {
            if (typeof genreId == 'undefined' || genreId == '')
                genreId = null;
            $.ajax({
                type: 'GET',
                url: apiRoutes.getCategoryListByGenreIdUrl,
                data: {genreId: genreId},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    var categories = res.data;

                    if (!isRegist) {
                        var categoryHtml = demandObj.renderHtml(categories, true, parseInt(categoryId));
                    } else {
                        var categoryHtml = demandObj.renderHtml(categories);
                    }
                    el.html(categoryHtml);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        getInquiryItem: function (genreId, displayContent = true) {
            $.ajax({
                type: 'GET',
                url: apiRoutes.getInquiryItemDataUrl,
                data: {genreId: genreId},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    if(displayContent && !hasChangeDemandContent){
                        if(validateFail) $demandContentEl.val(oldDemandContent);
                        else $demandContentEl.val(res.data ? res.data.inquiry_item : '');
                        hasChangeDemandContent = true;
                    }

                    $attentionEl.html( `<pre class="fs-13"> ${res.data && res.data.attention !== null ? res.data.attention : ''}</pre>`);
                    $beforeDemandInfoGenreId.val(genreId);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        changeGenreSelection: function (genreId, renderElm = $categoryEl, displayContent = true) {
            var genreSelected = $genreEl.find('option:selected');
            var genreId = genreSelected.val();
            var siteId = $siteEl.val();

            demandObj.getCategory(genreId, renderElm);

            var beforeDemandInfoGenreIdVal = $beforeDemandInfoGenreId.val();

            if (siteId == 861) {
                if (beforeDemandInfoGenreIdVal == "" && genreId != "") {
                    demandObj.getInquiryItem(genreId, displayContent);
                    $beforeDemandInfoGenreId.val(genreId);
                }
            } else if (beforeDemandInfoGenreIdVal == "" && genreId != "") {
                $beforeDemandInfoGenreId.val(genreId, displayContent);
            }

            if (genreId != "") {
                demandObj.getInquiryItem(genreId, displayContent);
            }
        },
        resetRadio: function (btnRest) {
            btnRest.parents('.group-radio-pet-tomb').find('input[type="radio"]').prop('checked', false);
        },
        enableTextBox: function (radioElm, enableElm, disabledElm) {
            if ($(radioElm).is(':checked')) {
                var parent = $(radioElm).parents('.py-2');
                parent.find('.' + enableElm).val('').attr('disabled', false);
                parent.find('.' + disabledElm).val('').attr('disabled', true);
            }
        },
        getSelectionSystemList: function (genreId, address1, default_value = false) {
            if($genreEl.val() != '') {
                $.ajax({
                    type: 'GET',
                    url: apiRoutes.getSelectionSystemListUrl,
                    data: {genre_id: genreId, address1: address1},
                    xhr: function () {
                        return progress.createXHR();
                    },
                    beforeSend: function () {
                        progress.controlProgress(true);
                    },
                    complete: function () {
                        progress.controlProgress(false);
                    },
                    success: function (res) {
                        var selectionType = res.data.selection_system;
                        var defaultValue = !default_value ? res.data.default_value : default_value;

                        var selectionSystemHtml = demandObj.renderHtml(selectionType, false, defaultValue);
                        $selectionSystemEl.html(selectionSystemHtml);
                        $selectionSystemEl.trigger('change');

                        var siteId = $siteEl.val();
                        var selectSystemValue = $selectionSystemEl.val();

                        if (siteId === 861) {
                            if (selectSystemValue.size() > 0) {
                                $selectionSystemEl.val(selectSystemValue);
                            }
                        }

                        if (defineListSiteIds.indexOf(parseInt(siteId)) !== -1) {
                            $selectionSystemEl.find('option').each(function (index, opt) {
                                if ($(opt).is(':selected')) {
                                    $(opt).removeAttr('selected');
                                }
                                if ($(opt).text() === '入札式+自動') {
                                    $(opt).css('display', 'none');
                                }
                            });
                        }
                        demandObj.selectionSystemAgencyInfoDis(false);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
        },
        getSiteDataByContent: function (siteId, contentType) {
            $.ajax({
                type: 'GET',
                url: apiRoutes.getSiteDataUrl,
                data: {site_id: siteId, content: contentType},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    if (contentType == 1) {
                        var url = res.data ? res.data.site_url : '';
                        $('#site_url').html(url).attr('href', 'http://' + url);
                        $('#hidSiteUrl').val('http://' + url);
                    } else if (contentType == 2) {
                        if (res.data) {
                            var commissionTypeName = res.data.m_commission_type ? res.data.m_commission_type.commission_type_name : '';
                            $('#commission_type_data').html(commissionTypeName);
                            $('#commission_type_data_hidden').val(commissionTypeName);
                            $('#commission_type_div').val(res.data.m_commission_type.commission_type_div);
                            demandObj.controlSection($commissionTypeSection, true);
                            var displayData = res.data.m_commission_type.commission_type_div;
                            switch (displayData) {
                                case 1:
                                case 2:
                                    demandObj.controlSection($agencyInfoSection, true);
                                    demandObj.controlSection($normalInfoSection, true);

                                    demandObj.selectionSystemAgencyInfoDis(true);
                                    demandObj.clearIntroduceInfo();
                                    break;
                                default:
                                    demandObj.controlSection($agencyInfoSection, true);
                                    demandObj.controlSection($normalInfoSection, true);

                                    demandObj.selectionSystemAgencyInfoDis(true);
                                    demandObj.clearIntroduceInfo();
                                    break;

                            }
                        }
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        getSiteData: function (siteId) {
            if (siteId != "") {
                demandObj.getSiteDataByContent(siteId, 1);
                demandObj.getSiteDataByContent(siteId, 2);
            } else {
                demandObj.controlSection($normalInfoSection, false);
                demandObj.controlSection($commissionTypeSection, false);
            }
        },
        selectionSystemAgencyInfoDis: function (isChanged) {
            if (!isChanged)
                demandObj.controlSection($agencyInfoSection, true);
            demandObj.controlSection($bidInfoSection, true);

            var selectionSystem = $selectionSystemEl.val();
            if (selectionSystem == "2" || selectionSystem == "3") {

                if ($('.corp_id').length === 0) {
                    demandObj.controlSection($agencyInfoSection, false);
                } else {
                    demandObj.controlSection($bidInfoSection, false);
                }
            }

            if(selectionSystem == "0"){
                $('#contact_estimated_time_from').attr('disabled', false);
                $('#contact_estimated_time_to').attr('disabled', false);
            }else{
                $('#contact_estimated_time_from').val('').attr('disabled', true);
                $('#contact_estimated_time_to').val('').attr('disabled', true);
            }

        },
        clearIntroduceInfo: function () {
            $introductionEL.text('');
            $introduceInfoCountEl.val(0);
        },
        getBusinessTripAmount: function (genreId, address1) {

            if (address1 != "" && genreId != "") {
                demandObj.travelExpress(genreId, address1);
            }
        },
        existsAutoCommissionCorp: function () {
            var siteId = $siteEl.val(),
                categoryId = $categoryEl.find('option:selected').val(),
                genreId = $genreEl.find('option:selected').val(),
                address1 = $address1El.find('option:selected').val();

            if (siteId != "" && categoryId != "" && genreId != "" && address1 != "") {
                $.ajax({
                    type: 'GET',
                    url: apiRoutes.getExistAutoCommissionCorpUrl,
                    data: {genre_id: genreId, prefecture_code: address1, site_id: siteId, category_id: categoryId},
                    xhr: function () {
                        return progress.createXHR();
                    },
                    beforeSend: function () {
                        progress.controlProgress(true);
                    },
                    complete: function () {
                        progress.controlProgress(false);
                    },
                    success: function (res) {
                        if (res.data == 1) {
                            $autoCommissionMessageEl.html("現在のカテゴリ、都道府県には自動選定対象加盟店があります。<br>自動で選定を実行する場合は案件状況を未選定に設定し、登録ボタンを押してください。");
                            $autoCommissionMessageEl.css({'color': '#rgb(0, 0, 255)'});
                            $autoCommissionMessageEl.val("1");
                        }
                        else if (res.data == 2) {
                            $autoCommissionMessageEl.html("現在のカテゴリ、都道府県には自動取次対象加盟店があります。<br>自動で取次を実行する場合は案件状況を未選定に設定し、登録ボタンを押してください。");
                            $autoCommissionMessageEl.css({'color': '#CC0000'});
                            $displayAutoCommissionMessageEl.val("1");
                        }
                        else {
                            $autoCommissionMessageEl.html("");
                            $displayAutoCommissionMessageEl.val("0");
                        }
                        $('.cyzen_commission_corp').val(res.data);
                        CountAff.runDetail();
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
            else {
                $autoCommissionMessageEl.html("");
                $displayAutoCommissionMessageEl.val("0");
            }
        },
        changeModeOrderFailReason: function () {
            var demandStatusVal = parseInt($demandStatusEl.val());
            var genreId = $genreEl.find('option:selected').val();
            var address1 = $address1El.find('option:selected').val();

            if (demandStatusVal == 6 || demandStatusVal == 9) {
                $selectionSystemEl.val('0');
                demandObj.getBusinessTripAmount(genreId, address1);
                demandObj.selectionSystemAgencyInfoDis(false);
            }

            if (demandStatusVal == 4) {
                demandObj.controlDisabled($orderFailReasonEl, false);

                var orderFailDateVal = $orderFailDateEl.val();
                if (orderFailDateVal == '') {
                    $orderFailDateEl.val(demandObj.dateNowFormat());
                }
            } else {
                demandObj.controlDisabled($orderFailReasonEl, true);
                $orderFailDateEl.val('');
                if(ctiDemand){
                    $selectionSystemEl.val('0');
                }
            }
        },
        controlContactTimeEntryFields: function () {
            var turnOnOff = function (flg) {
                var option = {
                    controlType: 'select',
                    oneLine: true,
                    timeText: '時間',
                    hourText: '時',
                    minuteText: '分',
                    currentText: '現時刻',
                    closeText: '閉じる',
                };

                if (flg) {
                    $("#DemandInfoContactDesiredTimeFrom, #DemandInfoContactDesiredTimeTo")
                        .prop('readonly', true)
                        .val("").addClass("readonly").datetimepicker("destroy");
                    $contactDesiredTimeEl.prop('readonly', false).removeClass("readonly").datetimepicker(option);
                } else {
                    $("#DemandInfoContactDesiredTimeFrom, #DemandInfoContactDesiredTimeTo")
                        .prop('readonly', false).removeClass("readonly").datetimepicker(option);
                    $contactDesiredTimeEl.prop('readonly', true).val("").addClass("readonly").datetimepicker("destroy");
                }
            }
            if (document.getElementById("DemandInfoIsContactTimeRangeFlg0").checked) {
                turnOnOff(true);
                return;
            }
            if (document.getElementById("DemandInfoIsContactTimeRangeFlg1").checked) {
                turnOnOff(false);
                return;
            }
        },
        controlVisitTimeEntryFields: function (num) {
            var turnOnOff = function (flg) {
                var option = {
                    controlType: 'select',
                    oneLine: true,
                    timeText: '時間',
                    hourText: '時',
                    minuteText: '分',
                    currentText: '現時刻',
                    closeText: '閉じる',
                };
                var vistTimeNum = "#VisitTime" + num.toString();
                var selector = vistTimeNum + "VisitTimeFrom," + vistTimeNum + "VisitTimeTo," + vistTimeNum + "VisitAdjustTime";
                if (flg) {
                    $(selector).prop('readonly', true)
                        .val("")
                        .addClass("readonly")
                        .datetimepicker("destroy");
                    $("#visit_time" + num.toString()).prop('readonly', false)
                        .removeClass("readonly")
                        .datetimepicker(option);
                } else {
                    $(selector).prop('readonly', false)
                        .removeClass("readonly")
                        .datetimepicker(option);
                    $("#visit_time" + num.toString()).prop('readonly', true)
                        .val("")
                        .addClass("readonly")
                        .datetimepicker("destroy");
                }
            }
            if (document.getElementById("VisitTime" + num.toString() + "IsVisitTimeRangeFlg0").checked) {
                turnOnOff(true);
                return;
            }
            if (document.getElementById("VisitTime" + num.toString() + "IsVisitTimeRangeFlg1").checked) {
                turnOnOff(false);
                return;
            }
        },
        travelExpress: function (genreId, address) {
            $.ajax({
                type: 'GET',
                url: apiRoutes.getBusinessTripAmountUrl,
                data: {genre_id: genreId, address1: address},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    $businessTripAmountEl.val(res.data.business_trip_amount);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        searchAddressByZip: function (zip) {
            $.ajax({
                type: 'GET',
                url: apiRoutes.getAddressByZipUrl,
                data: {zip: zip},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    if (!$.isEmptyObject(res)) {

                        var genreId = $genreEl.val(),
                            address = $address1El.val();

                        demandObj.travelExpress(genreId, address);
                        $address1El.val(parseInt(res.m_posts_jis_cd));
                        $address1El.trigger('change');
                        $address2El.val(res.address2);
                        $address3El.val(res.address3);

                        if (typeof $count_aff !== "undefined" && $count_aff) {
                            eval($count_aff + "()");
                        }

                        if ($businessTripAmountEl.val() == '') return;

                        var address1 = $address1El.val();
                        if (genreId !== '' && address1 !== '') {
                            demandObj.travelExpress(genreId, address1);
                        }

                        if ($selectionSystemEl.val() == '') return;
                        demandObj.getSelectionSystemList(genreId, address1);
                        console.log(res);
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        changeOrderFailReason: function (statusObj) {
            var demandStatus = parseInt($(statusObj).val());
            if ([6, 9].indexOf(demandStatus) !== -1) {
                $selectionSystemEl.val(0);
                var genreId = $genreEl.find('option:selected').val(),
                    address1 = $address1El.val();
                demandObj.travelExpress(genreId, address1);
            }

            if (demandStatus == 6) {
                $('#order-fail-reason').attr('disabled', false);
                $('.order-fail-date').val('' + demandObj.dateNowFormat() + '');
            } else {
                $('#order-fail-reason').attr('disabled', true);
                $('.order-fail-date').val('');
            }
        },
        dateNowFormat() {
            var date = new Date();
            var dd = date.getDate();
            var mm = date.getMonth() + 1;
            var yyyy = date.getFullYear();
            dd = dd < 10 ? '0' + dd : dd;
            mm = mm < 10 ? '0' + mm : mm;
            return yyyy + '/' + mm + '/' + dd;
        },
        showDialog: function (header, content) {
            $modalDialog.find('h3').html(header);
            $modalDialog.find('.modal-body').html(content);
            $modalDialog.modal({show: true});
        },
        getCrossSellSiteSelection: function (siteId) {
            if (siteId != "") {
                $.ajax({
                    type: 'GET',
                    url: apiRoutes.getCrossSourceSiteUrl,
                    data: {site_id: siteId},
                    xhr: function () {
                        return progress.createXHR();
                    },
                    beforeSend: function () {
                        progress.controlProgress(true);
                    },
                    complete: function () {
                        progress.controlProgress(false);
                    },
                    success: function (res) {
                        var defaultValue = typeof crossSellSourceGenre != "undefined" ? crossSellSourceGenre : '';
                        var crossSourceSiteHtml = demandObj.renderHtml(res.data.category_list, true, defaultValue);
                        $crossSellSourceGenreEl.html(crossSourceSiteHtml);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }
        },
        getCommissionMaxLimit: function (siteId) {
            $.ajax({
                type: 'GET',
                url: apiRoutes.getCommissionMaxLimitUrl,
                data: {m_site_id: siteId},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    $('#auction_selection_limit').val(res.data.auction_selection_limit);
                    $('#manual_selection_limit').val(res.data.manual_selection_limit);
                    demandObj.showLimitText($selectionSystemEl.val(), res.data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        showLimitText: function (selectionSystem, limit) {
            demandObj.limit = limit;
            if (typeof limit == 'undefined' || limit == null || limit == '')
                limit = {};
            if (selectionSystem != null){
                var maxLimit = 1;
                if(["0", "4"].indexOf(selectionSystem) != -1){
                    maxLimit = limit.manual_selection_limit;
                }else{
                    maxLimit = limit.auction_selection_limit;
                }

                var text = '確定可能な取次先は' + maxLimit + ' 社です';
                $maxLimitNumText.html(text);
            }
        },
        updateCalendarCheckboxes: function (checkedId) {
            $('.calendar_check').each(function(i) {
                if (checkedId == null) {
                    $('#' + $(this).attr('id') + '_').val(0);
                    $(this).prop('checked', false);
                } else if ($(this).attr('id') != checkedId) {
                    $('#' + $(this).attr('id') + '_').val(1);
                    $(this).prop('checked', true);
                }
            });
        },
        checkCalendarCheckboxes: function() {
            $('.calendar_check').each(function(i) {
                if (i == 0) {
                    var checkedId = null;
                    if ($(this).prop('checked') == true) {
                        checkedId = $(this).attr('id');
                    }
                    demandObj.updateCalendarCheckboxes(checkedId);
                }
            });
            $('.calendar_check').unbind('change').on('change', function() {
                var checkedId = null;
                if ($(this).prop('checked') == true) {
                    checkedId = $(this).attr('id');
                }
                demandObj.updateCalendarCheckboxes(checkedId);
            });
        }
    };
    var detectCommissionMaxLimit = {
        limit: {},
        setLimitData: function (siteId) {
            return $.ajax({
                type: 'GET',
                url: apiRoutes.getCommissionMaxLimitUrl,
                data: {m_site_id: siteId},
                xhr: function () {
                    return progress.createXHR();
                },
                beforeSend: function () {
                    progress.controlProgress(true);
                },
                complete: function () {
                    progress.controlProgress(false);
                },
                success: function (res) {
                    detectCommissionMaxLimit.limit = res.data;
                    demandObj.limit = res.data;
                    $('#auction_selection_limit').val(res.data.auction_selection_limit);
                    $('#manual_selection_limit').val(res.data.manual_selection_limit);
                    detectCommissionMaxLimit.showLimitNum($selectionSystemEl.val());
                    detectCommissionMaxLimit.disableVisitTime($selectionSystemEl.val());
                },
                error: function (err) {
                    console.log(err);
                }
            });
        },
        showLimitNum: function (selectionVal) {
            if (selectionVal == '0' || selectionVal == '4') {
                $maxLimitNumMessageEl.html('確定可能な取次先は' + detectCommissionMaxLimit.limit.manual_selection_limit + '社です');
            }
            if (selectionVal == '2' || selectionVal == '3') {
                $maxLimitNumMessageEl.html('確定可能な取次先は' + detectCommissionMaxLimit.limit.auction_selection_limit + '社です');
            }
            var text = '確定可能な取次先は' + demandObj.limit.manual_selection_limit + ' 社です';
            $maxLimitNumText.html(text);
        },
        onChangeSelection: function (selectionVal) {
            detectCommissionMaxLimit.showLimitNum(selectionVal);
            detectCommissionMaxLimit.disableVisitTime(selectionVal);
            CountAff.postApi();
        },
        onChangeSiteID: function (siteId) {
            detectCommissionMaxLimit.setLimitData(siteId);
        },
        disableVisitTime: function (selectionVal) {
            var datetimeOption = {
                controlType: 'select',
                oneLine: true,
                timeText: '時間',
                hourText: '時',
                minuteText: '分',
                currentText: '現時刻',
                closeText: '閉じる'
            };

            var enable = function ($element) {
                if ($element.attr('type') == 'text') {
                    $element.prop('readonly', false).datetimepicker(datetimeOption);
                }
                if ($element.attr('type') == 'radio') {
                    $element.prop('disabled', false);
                }
                $element.removeClass("readonly");
            };
            var disable = function ($element) {
                if ($element.attr('type') == 'text') {
                    $element.prop('readonly', true).datetimepicker("destroy");
                }
                if ($element.attr('type') == 'radio') {
                    $element.prop('disabled', true);
                    $($element.get(0)).prop('checked', true);
                }
                $element.val("").addClass("readonly");
            };

            var $visitTimeRange1 = $("input[type='radio'][name=data\\[VisitTime\\]\\[0\\]\\[is_visit_time_range_flg\\]]");
            var $visitTimeRange2 = $("input[type='radio'][name=data\\[VisitTime\\]\\[1\\]\\[is_visit_time_range_flg\\]]");
            var $visitTimeRange3 = $("input[type='radio'][name=data\\[VisitTime\\]\\[2\\]\\[is_visit_time_range_flg\\]]");
            var $visitTime1 = $("input[name=data\\[VisitTime\\]\\[0\\]\\[visit_time\\]]");
            var $visitTime1From = $("input[name=data\\[VisitTime\\]\\[0\\]\\[visit_time_from\\]]");
            var $visitTime1To = $("input[name=data\\[VisitTime\\]\\[0\\]\\[visit_time_to\\]]");
            var $visitTime1Adjust = $("input[name=data\\[VisitTime\\]\\[0\\]\\[visit_adjust_time\\]]");
            var $visitTime2 = $("input[name=data\\[VisitTime\\]\\[1\\]\\[visit_time\\]]");
            var $visitTime2From = $("input[name=data\\[VisitTime\\]\\[1\\]\\[visit_time_from\\]]");
            var $visitTime2To = $("input[name=data\\[VisitTime\\]\\[1\\]\\[visit_time_to\\]]");
            var $visitTime2Adjust = $("input[name=data\\[VisitTime\\]\\[1\\]\\[visit_adjust_time\\]]");
            var $visitTime3 = $("input[name=data\\[VisitTime\\]\\[2\\]\\[visit_time\\]]");
            var $visitTime3From = $("input[name=data\\[VisitTime\\]\\[2\\]\\[visit_time_from\\]]");
            var $visitTime3To = $("input[name=data\\[VisitTime\\]\\[2\\]\\[visit_time_to\\]]");
            var $visitTime3Adjust = $("input[name=data\\[VisitTime\\]\\[2\\]\\[visit_adjust_time\\]]");

            // 無効
            if ((selectionVal == '2' || selectionVal == '3') && detectCommissionMaxLimit.limit.auction_selection_limit >= 2) {
                disable($visitTimeRange1);
                disable($visitTime1);
                disable($visitTime1From);
                disable($visitTime1To);
                disable($visitTime1Adjust);
                disable($visitTimeRange2);
                disable($visitTime2);
                disable($visitTime2From);
                disable($visitTime2To);
                disable($visitTime2Adjust);
                disable($visitTimeRange3);
                disable($visitTime3);
                disable($visitTime3From);
                disable($visitTime3To);
                disable($visitTime3Adjust);
                // 有効
            } else {
                enable($visitTimeRange1);
                enable($visitTime1);
                enable($visitTimeRange2);
                enable($visitTime2);
                enable($visitTimeRange3);
                enable($visitTime3);
            }
        },
        initialize: function () {
            var siteId = $siteEl.val();

            if (siteId) {
                detectCommissionMaxLimit.setLimitData(siteId);
            }
        }
    };

    var loadModal = function (url_display_commission) {
        $.ajax({
            type: "GET",
            url: url_display_commission,
            xhr: function () {
                return progress.createXHR();
            },
            beforeSend: function (xhr) {
                progress.controlProgress(true);
            },
            complete: function () {
                progress.controlProgress(false);
            },
            success: function (data) {
                $modalPopup.children().children().find('.modal-body').html(data);
                $modalPopup.modal('show');
            },
            error: function () {
                console.log("Error!");
            }
        });
    };

    function getCorpIds(){
        var corpIds = [];

        $('.corp_id').each(function(){
            corpIds.push($(this).val());
        });

        return corpIds;
    }

    function getStaffIds(){
        var staffIds = [];
        $('.staff_id').each(function(){
            var val = $(this).val();
            staffIds.push(val);
        });

        return staffIds;
    }

    function loadCommission(tempIndex, mCorp){
        $loadMCorpSection.append(template(tempIndex, mCorp));
        demandObj.getUserList();
        demandObj.checkCalendarCheckboxes();
        Datetime.initForDateTimepicker();
    }

    function loadCommissionInfo(commissions) {
        $demandCommissionInfoSection.css({display: 'block'});

        var commissions = $.makeArray(JSON.parse(commissions));
        var commissionTable = $('.commission-table');

        var maxIndex = $maxIndexEl.length > 0 ? parseInt($maxIndexEl.data('max')) : 0;
        var corpIds = getCorpIds();
        var staffIds = getStaffIds();
        if(commissions.length > 0){
            $.each(commissions, function (index, data) {
                var tempIndex = parseInt(maxIndex + index + 1);
                var mCorp = JSON.parse(data);
                var staffId = mCorp.list_staff[0].id_staff;
                var corpId = mCorp.corp_id.toString();

                if(staffId == '' || typeof staffId == "undefined"){
                    if(corpIds.indexOf(corpId) == -1){
                        loadCommission(tempIndex, mCorp);
                    }
                } else {
                    if(staffIds.indexOf(staffId) == -1){
                        loadCommission(tempIndex, mCorp);
                    }
                }

            });
        }
    };

    var init = function () {
        // if (!hasCommission) {
        //     localStorage.removeItem('m_corps');
        //     localStorage.removeItem('currentIndex');
        //     localStorage.removeItem('close_modal');
        // }

        $('.m-corps-detail').on('click', function (e) {
            e.preventDefault();
            // var urlData = $(this).data('url_data');
            var urlData = $(this).data('url_data') + '?fee_data=' + $(this).parents('.form-table').find('.get-fee-data').text();
            loadModal(urlData);
        });

        detectCommissionMaxLimit.initialize();
        var $demandIdHidden = $('#demand_id');
        var demandId = $demandIdHidden.val();

        // make sure that this is detail page
        if (!isRegist) {
            var genreId = $genreEl.val();
            demandObj.changeGenreSelection(genreId, $categoryEl, false);
        }

        $crossSellSourceSiteEl.on('change', function () {
            var crossSellSourceSite = $crossSellSourceSiteEl.val();
            if(crossSellSourceSite != ''){
                $(this).parent().find('.invalid-feedback').remove();
            }
            demandObj.getCrossSellSiteSelection(crossSellSourceSite);

            return false;
        });

        $crossSellSourceGenreEl.on('change', function(){
            if($(this).val() != ''){
                $(this).parent().find('.invalid-feedback').remove();
            }
        });

        demandObj.middleNightCheckbox($middleNightCheckbox);

        $destinationCompanyButton.on('click', function (e) {
            e.preventDefault();

            // localStorage.setItem('currentIndex', (parseInt(localStorage.getItem('currentIndex') || 0) + 1).toString());

            var urlData = $(this).data('url_data').split('?')[0];
            // var currentSession = parseInt(localStorage.getItem('currentSession'));
            var mCorpsStorage = $('#commissions').val();
            // var mCorpsArr = mCorpsStorage.indexOf(currentSession) !== -1 ? mCorpsStorage[currentSession].flatten() : [];
            // var m = $.map(mCorpsStorage, function (m_corp, index) {
            //     return JSON.parse(m_corp).corp_id;
            // });

            var m = $.map($('.corp_id'), function(m_corp, index){
                return m_corp.corp_id;
            });

            var staffIdArr = [];
            $.each($('.staff_id'), function () {
                var staffId = $(this).val();
                if (staffId !== null && staffId !== '') staffIdArr.push($(this).val());
            });


            var staffIds = m.concat(staffIdArr).join(','),
                specifyTimeTo = $("input[name='demandInfo[contact_desired_time]'][type!=hidden]").val(),
                timeAdjustmentFrom = $("input[name='demandInfo[contact_desired_time_from]'][type!=hidden]").val(),
                timeAdjustmentTo = $("input[name='demandInfo[contact_desired_time_to]'][type!=hidden]").val(),
                estimatedFrom = $("input[name='demandInfo[contact_estimated_time_from]'][type!=hidden]").val(),
                estimatedTo = $("input[name='demandInfo[contact_estimated_time_to]'][type!=hidden]").val(),
                time_from = '',
                time_to = '';
            var specifyTimeFrom = '',
                is_specifyTime = false,
                is_estimated = false;
            if (estimatedFrom !== '' || estimatedTo !== '') {
                time_from = estimatedFrom;
                time_to = estimatedTo;
                is_estimated = true;
            } else if (specifyTimeTo !== '') {
                time_to = specifyTimeTo;
                var timeMinus = Date.parse(specifyTimeTo);
                var Minus5Minutes = new Date(timeMinus - 300000);
                specifyTimeFrom = Minus5Minutes.getFullYear() + '/' + ('0' + (Minus5Minutes.getMonth() + 1)).slice(-2) + '/' +
                    Minus5Minutes.getDate() + ' ' + Minus5Minutes.getHours() + ':' + ('0' + (Minus5Minutes.getMinutes())).slice(-2);
                time_from = specifyTimeFrom;
                is_specifyTime = true;
            } else {
                time_from = timeAdjustmentFrom;
                time_to = timeAdjustmentTo;
            }

            var corpIdsArr = [];

            $.each($('.corp_id'), function () {
                var corp = $(this).parents('.form-table').find('.staff_id').val();
                if (corp === '' || corp === null || typeof corp === 'undefined') {
                    corpIdsArr.push($(this).val());
                }
            });

            var corpIds = corpIdsArr.join(',');

            var queryString = $.param({
                'data[no]': -1,
                'data[site_id]': $siteEl.val(),
                'data[category_id]': $categoryEl.val(),
                'data[postcode]': $postcodeEl.val(),
                'data[address1]': $address1El.val(),
                'data[address2]': $address2El.val(),
                'data[corp_name]': '',
                'data[commition_info_count]': 0,
                'data[exclude_staff_id]': staffIds,
                'data[exclude_corp_id]': corpIds,
                'data[genre_id]': $genreEl.find('option:selected').val(),
                'data[view]': true,
                'data[time_from]': time_from,
                'data[time_to]': time_to,
                'data[lat]': $lat.val(),
                'data[lng]': $lng.val(),
                'data[is_estimated]': is_estimated,
                'data[is_specifytime]' : is_specifyTime
            });

            $(this).data('url_data', [urlData, queryString].join('?'));
            var address1 = $address1El.val(), category = $categoryEl.val(), address2 = $address2El.val();
            if (address1 === '' || address1 === '--なし--' || category === '' || (address1 !== '不明' && address2 === '')) {
                demandObj.showDialog('', '都道府県、市区町村、カテゴリを選択してください。');
                return false;
            }
            $('#commissions').val('');
            var win = window.open([urlData, queryString].join('?'), '_blank', 'width=1150, height=800, menubar=no, toolbar=no, scrollbars=yes , location=no, left=' + (screen.availWidth - 800));
            var timer = setInterval(checkChild, 500);

            function checkChild() {
                if (win.closed) {
                    var commissions = $('#commissions').val();
                    if(commissions != ''){
                        var parseCommissions = JSON.parse(commissions);
                        var currentIndex = $('#current_index').val();

                        if(commissions.length > 0){
                            loadCommissionInfo(commissions, currentIndex);
                        }
                    }

                    clearInterval(timer);
                }
            }

            return false;
        });

        $plus15MinutesButton.on('click', function (e) {
            demandObj.addMinutes(15);
            CountAff.runDetail();
            return false;
        });

        $siteEl.on('change', function (e) {
            demandObj.changeSiteId();
            demandObj.getCommissionMaxLimit($(this).val());
            demandObj.controlSection($selectionSystemDivSection, true);
            demandObj.existsAutoCommissionCorp();
            CountAff.runDetail();
            return false;
        });
        demandObj.getSiteData($siteEl.val());

        $demandStatusEl.on('change', function (e) {
            demandObj.changeOrderFailReason(this);

            return false;
        });

        $searchAddressByZipButton.on('click', function (e) {
            var postcode = encodeURI($postcodeEl.val());
            demandObj.searchAddressByZip(postcode);
            CountAff.ajaxCountAff();
            return false;
        });

        $categoryEl.on('change', function (e) {
            demandObj.changeCategorySelection();
            demandObj.existsAutoCommissionCorp();
            CountAff.runDetail();
            return false;
        });

        $resetRadioButton.on('click', function () {
            demandObj.resetRadio($(this));

            return false;
        });



        $visitTimeDiv.first().find('input[type="text"]').on('change', function () {
            $visitTimeDiv.not(':first').find('input[type="text"]').val('');
        });

        $visitTimeDiv.not(':first').find('input[type="text"]').on('change', function () {
            $visitTimeDiv.first().find('input[type="text"]').val('');
        });

        $radioAbsoluteTimeSection.on('click', function (e) {
            var contactDesiredTimeVal = $contactDesiredTimeEl.val();
            demandObj.enableTextBox(this, 'txt_absolute_time', 'txt_range_time');
            if (contactDesiredTimeVal != '') {
                $contactDesiredTimeEl.val(contactDesiredTimeVal);
            }
        });

        $radioRangeTimeSection.on('click', function (e) {
            demandObj.enableTextBox(this, 'txt_range_time', 'txt_absolute_time');
        });

        $address1El.on('change', function (e) {

            var genreId = $genreEl.val(),
                address1 = $(this).val();

            demandObj.getBusinessTripAmount(genreId, address1);
            demandObj.getSelectionSystemList(genreId, address1);
            demandObj.existsAutoCommissionCorp();

            return false;
        });

        $demandContentEl.on('change', function(){
            hasChangeDemandContent = true;
        });

        $genreEl.on('change', function () {
            var genreId = $(this).val();
            var address1 = $address1El.val();
            demandObj.getBusinessTripAmount(genreId, address1);
            demandObj.changeGenreSelection(genreId, $categoryEl);
            demandObj.getSelectionSystemList(genreId, address1);
            demandObj.existsAutoCommissionCorp();

            return false;
        });

        $selectionSystemEl.on('change', function (e) {
            var genreId = $genreEl.val();
            var address1 = $address1El.val();
            demandObj.getBusinessTripAmount(genreId, address1);
            demandObj.selectionSystemAgencyInfoDis(false);
            detectCommissionMaxLimit.onChangeSelection($(this).val());

            return false;
        });

        $sendCommissionInfoBtn.on('click', function () {
            $sendCommissionInfoEl.val(1);

            return false;
        });

        $(document).on('change', '.now_date', function () {
            var url = $('.demand-detail').attr('data-url');
            var key = $(this).attr('data-key');
            var commission_note_sender_id = $('#commission_note_sender' + key + ' option:selected').val();
            if (commission_note_sender_id != "") {
                if ($(".commission_note_send_datetime" + key).val() == "") {
                    $.get(url, function (data) {
                        $(".commission_note_send_datetime" + key).val(data);
                    });
                }
            } else {
                $(".commission_note_send_datetime" + key).val("");
            }
            CountAff.runDetail();
        });

        $sendIntroduceInfoBtn.on('click', function () {
            $sendCommissionInfoEl.val(1);

            return false;
        });

        $('input[name="demandInfo[is_contact_time_range_flg]]"]:radio').on('change', function () {
            demandObj.controlContactTimeEntryFields();
            return false;
        });
        $('input[name="data[VisitTime][0][is_visit_time_range_flg]"]:radio').on('change', function () {
            demandObj.controlVisitTimeEntryFields(0);
            return false;
        });
        $('input[name="data[VisitTime][1][is_visit_time_range_flg]"]:radio').on('change', function () {
            demandObj.controlVisitTimeEntryFields(1);
            return false;
        });
        $('input[name="data[VisitTime][2][is_visit_time_range_flg]"]:radio').on('change', function () {
            demandObj.controlVisitTimeEntryFields(2);
            return false;
        });

        $("input[id^='commit_flg']").on('change', function () {
            var id = $(this).data('id');
            if ($(this).is(':checked')) {
                $('#corp_claim_flg' + id).prop('disabled', false);
                var maxLimit = 0;
                var selectionSystem = $selectionSystemEl.val();
                if (selectionSystem == '2' && selectionSystem == '3') {
                    maxLimit = $('#auction_selection_limit').val();
                } else {
                    maxLimit = $('#manual_selection_limit').val() || 0;
                }
                var commitedCount = 0;
                for (var i = 0; i < 30; i++) {
                    var del_flg = $("input[id='del_flg" + i + "']").is(':checked');
                    var commit_flg = $("input[id='commit_flg" + i + "']").is(':checked');
                    if ($("input[id='CommissionInfo" + i + "CorpId']").val()) {
                        if (!del_flg && commit_flg) {
                            commitedCount++;
                        }
                    }
                }
                if (maxLimit == commitedCount) {
                    for (var i = 0; i < 30; i++) {
                        var del_flg = $("input[id='del_flg" + i + "']").is(':checked');
                        var commit_flg = $("input[id='commit_flg" + i + "']").is(':checked');
                        if ($("input[id='CommissionInfo" + i + "CorpId']").val()) {
                            if (!del_flg && !commit_flg) {
                                $("input[id='lost_flg" + i + "']").prop('checked', true);
                            }
                        }
                    }
                }
            } else {
                $('#corp_claim_flg' + id).prop('checked', false).prop('disabled', true);
            }
            return false;
        });

        $notSendEl.on('change', function () {
            var val = $(this).find(':checked').val();
            if (val == 1) {
                demandObj.controlSection($sendIntroduceInfoBtn, false);
                demandObj.controlSection($sendCommissionInfoBtn, false);
            } else {
                demandObj.controlSection($sendIntroduceInfoBtn, true);
                demandObj.controlSection($sendCommissionInfoBtn, true);
            }
            return false;
        });

        // get list button which clear attached file
        var lstFileClear = $('input[id^=file_clear_]');
        $.map(lstFileClear, function (index, el) {
            $(el).on('click', function () {
                var id = $(this).attr('data-id');
                if (id) {
                    $('#' + id).val('');
                }
            });
        });
        if ($(window).width() > tablet_width) {
            $attentionEl.css({'width': 250, 'height': 205});
        } else {
            $attentionEl.css({
                'min-width': $demandContentEl.parent().width(),
                'min-height': $demandContentEl.parent().height()
            });
        }

        $(".txt_range_time").change(function() {
            var selected = $(this).parent();
            var items = selected.parent().children('.col-5');
            var items_full = selected.parent().children('.col-lg-3');
            var middle_elements = selected.parent().children('.col-auto');
            setTimeout(function() {
                var check_point = selected.parent().find('.invalid-feedback');
                if (check_point.length != 0) {
                    items.each(function() {
                        $(this).addClass('invalid-height-date-time-field');
                    })
                    middle_elements.each(function() {
                        $(this).addClass('invalid-height-sup-field');
                    })
                    items_full.addClass('invalid-height-date-time-field');
                } else {
                    items.each(function() {
                        $(this).removeClass('err-date-time invalid-height-date-time-field');
                    })
                    middle_elements.each(function() {
                        $(this).removeClass('err-sup invalid-height-sup-field');
                    })
                    items_full.removeClass('err-date-time invalid-height-date-time-field');
                }
            }, 10)
        });
        $('.date-time-group').each(function(){
            var invalid_date_time = $(this).find('.invalid-time-from-to');
            var invalid_time = $(this).find('.invalid-time');
            if (invalid_date_time.length != 0 || invalid_time.length != 0) {
                $(this).find('.date-time-item').each(function(){
                    $(this).addClass('err-date-time');
                });
                $(this).find('.date-time-sup').addClass('err-sup');
                if ($(window).width() > tablet_width) {
                    $(this).find('.date-time-sup-d').addClass('err-sup');
                }
            }
        })


        demandObj.checkCalendarCheckboxes();
    };

    var windowLoad = function () {
        var siteId = $siteEl.find('option:selected').val();
        demandObj.changeModeOrderFailReason();
        demandObj.crossSellDisabled(siteId);
    };

    if (validateFail) {
        var genreId = $genreEl.val(), address1 = $address1El.val();
        demandObj.getSelectionSystemList(genreId, address1, selectionSystemValue);
        demandObj.existsAutoCommissionCorp();
        demandObj.getCommissionMaxLimit($siteEl.val());
        demandObj.getInquiryItem(genreId);
        demandObj.getCommissionMaxLimit($siteEl.val());

        var crossSellSourceSite = $crossSellSourceSiteEl.val();
        demandObj.getCrossSellSiteSelection(crossSellSourceSite);
    } else if(ctiDemand){
        detectCommissionMaxLimit.initialize();
        demandObj.changeSiteId();

    }
    return {
        init: init,
        copyDemand: copyDemand,
        crossDemand: crossDemand,
        windowLoad: windowLoad
    }

})();
