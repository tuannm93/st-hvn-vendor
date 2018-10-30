let renderChainHtml = function (values, tag, commissionIndex, hiddenField = 'label_0') {
    let chainHtml = '';
    $.each(values, (index, value) => {
        chainHtml += `<${tag} class="text-center">
            ${value ? value : `&nbsp;`}
            <input type="hidden" value=${value} name="commissionInfo[${commissionIndex}][mCorpNewYear][${hiddenField}${index + 1}]"/>
            </${tag}>`;
    });
    return chainHtml;
};

let getCommissionString = function (commission, index) {
    var commissionString = '取次先手数料';

    if(commission.order_fee_unit != 0){
        commissionString = '取次時手数料率';
    }

    return `${commissionString}<input type="hidden" name="commissionInfo[${index}][commission_string]" value="${commissionString}" />`;
};

let getCommissionValue = function (commission, index) {
    let commissionInput = '';
    let result = '';
    if (commission.order_fee !== '') {
        commissionInput = `<input type="hidden" value="${commission.order_fee_val}" name="commissionInfo[${index}][corp_fee]" /> ${commission.order_fee_val} 円`;
        commissionInput += `<input type="hidden" value="${commission.order_fee_val + '円'}" name="commissionInfo[${index}][commission_fee_dis]" />`;
    }
    if (commission.order_fee_val !== '' && commission.order_fee_unit === 1) {
        commissionInput = `<input type="hidden" name="commissionInfo[${index}][commission_fee_rate]" value=${commission.order_fee_val || 0} />
                            ${commission.order_fee_val} %`;
        commissionInput += `<input type="hidden" value="${commission.order_fee_val + '%'}" name="commissionInfo[${index}][commission_fee_dis]" />`;
    }

    if (commission.corp_id !== '') {
        result = commission.corp_commission_type_disp + ' ' + commissionInput;
    }
    result += `<input type="hidden" value=${commission.corp_commission_type_disp} name="commissionInfo[${index}][corp_commission_type_disp]" />`;
    return `${result}<input type="hidden" name="commissionInfo[${index}][complete_date]" /> <input type="hidden" name="commissionInfo[${index}][commission_status]"/>`;
};

let $dropdownDemandDetail = $('#dropdown-detail');

function loadDropdown(url_display_commission, index, dropdown_position) {
    let dropdown = $dropdownDemandDetail;
    let initProgress = function () {
        return new progressCommon();
    };
    let progress = initProgress();
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
            dropdown.find('span#dropdown-title').html('取次先'+ index + '情報');
            dropdown.find('.dropdown-body').html(data);
            dropdown.css({'top': dropdown_position, 'display': 'block'});
            $('.close-dropdown').click(function() {
                dropdown.css('display', 'none');
            })
        },
        error: function () {
            console.log("Error!");
        }
    });
}

function loadMCorpDetail(element, index) {
    let dropdown_position = $(element).offset().top + $(element).outerHeight();
    let urlData = $(element).data('url_data');
    loadDropdown(urlData, index, dropdown_position);
};

function checkCommissionFlg(element) {
    var id = $(element).data('id');
    if ($(element).is(':checked')) {
        $('#corp_claim_flg' + id).prop('disabled', false);
        var maxLimit = 0;
        var selectionSystem = $('#selection_system').val();
        if (selectionSystem == '2' && selectionSystem == '3') {
            maxLimit = $('#auction_selection_limit').val();
        } else {
            maxLimit = $('#manual_selection_limit').val() || 0;
        }

        var count = 0;
        for (var i = 0; i < 30; i++) {
            var delFlg = $("input[id='del_flg" + i + "']").is(':checked');
            var commitFlg = $("input[id='commit_flg" + i + "']").is(':checked');
            if ($("input[id='CommissionInfo" + i + "CorpId']").val()) {
                if (!delFlg && commitFlg) {
                    count++;
                }
            }
        }

        if (maxLimit == count) {
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
}


function getMCorpDetailUrl(commission) {
    var commissionInput = '';
    if (commission.order_fee !== '') {
        commissionInput = `${commission.order_fee} 円`;
    }
    if (commission.order_fee_val !== '' && commission.order_fee_unit === 1) {
        commissionInput = `${commission.order_fee_val} %`;
    }
    return `${commission.corp_commission_type_disp}${commissionInput}`;
}

document.onclick = function(e) {
    if(!e.target.closest('#dropdown-detail')){
        $dropdownDemandDetail.css('display', 'none');
     }
};

Datetime.initForDateTimepicker();
let template = function (index, commission = {}) {
    // let currentIndex = index - 1 ;
    let currentIndex = $('.corp_id').length;

    let holidayLabels = renderChainHtml(commission.long_vacations_items, 'th', currentIndex);
    let holidayValues = renderChainHtml(commission.long_vacations, 'td', currentIndex, 'status_0');
    let commissionString = getCommissionString(commission, currentIndex);
    let commissionValue = getCommissionValue(commission, currentIndex);
    let categoryId = $('#category_id').val();
    let showStaff = ``;

    let commissionType = commission.corp_commission_type != 2 ? 0 : 1;
    let displayIntroduceNot = commissionType == '' || commissionType == 0 ? 'none' : 'display';
    if(commission['list_staff'][0].id_staff.trim().length > 0 && commission['list_staff'][0].name_staff.trim().length > 0) {
        showStaff =`<div class="row mx-0 form-table-cell">
                <div class="col-12 col-lg-6 row m-0 p-0">
                    <div class="col-12 col-lg-6 px-0">
                        <div class="form__label form__label--white-light p-3 h-100 ">
                            <label class="m-0">
                                <strong>作業担当者 </strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 py-2">
                        <div class="form-group d-flex mb-lg-0 p-3 h-100">
                            <input type="hidden" name="commissionInfo[${currentIndex}][staff_name]" value="${commission['list_staff'][0].name_staff || ''}" />
                            <input type="hidden" name="commissionInfo[${currentIndex}][status_name]" value="${commission['list_staff'][0].status_name || ''}" />
                            <input type="hidden" name="commissionInfo[${currentIndex}][status_id]" value="${commission['list_staff'][0].status_id || ''}" />
                            <input type="hidden" class="staff_id" name="commissionInfo[${currentIndex}][id_staff]" value="${commission['list_staff'][0].id_staff || ''}" />
                            <p>${commission['list_staff'][0].name_staff + '/' + commission['list_staff'][0].status_name|| ''}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">作業担当者ダイヤル</label>
                    <div class="col-lg-6 d-flex align-items-center py-2 ">
                        <input class="custom-control-input" id="commissionInfo${currentIndex}" type="hidden" name="commissionInfo[${currentIndex}][phone_staff]" type="hidden" value=${commission['list_staff'][0].phone_staff || ''} >
                        <a class="text--orange" for="commissionInfo${currentIndex}" href="callto:${commission['list_staff'][0].phone_staff || ''}">${commission['list_staff'][0].phone_staff || ''}</a>
                    </div>
                </div>
            </div>`;
    }
    return `<div class="form-table commission-table">
            <div class="row mx-0 form-table-cell">
                <div class="col-12 row m-0 bg-primary-lighter p-0">
                    <div class="col-12 col-lg-3 px-0">
                        <div class="form__label form__label--primary p-3 h-100">
                            <label class="m-0">
                                <strong>取次先<span class="max-index-currentIndex">${currentIndex + 1}</span></strong>
                            </label>
                            <button onClick="loadMCorpDetail(this, ${currentIndex + 1});return false;" data-key="${currentIndex + 1}"
                            data-url_data="/ajax/load_m_corp/${commission.corp_id}/${typeof categoryId !== "undefined" ? categoryId : ''}?fee_data=${getMCorpDetailUrl(commission)}"
                                data-toggle="modal" type="button" class="btn btn-sm btn--gradient-default m-corps-detail">
                                情報参照
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 py-3">
                        <div class="form-group w-100 mb-lg-0">
                            <input class="form-control" id="corp_name${currentIndex}" name="commissionInfo[${currentIndex}][mCorp][corp_name]" type="text" value="${commission.corp_name}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mx-0 form-table-cell">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">取次用ダイヤル</label>
                    <div class="col-lg-6 d-flex align-items-center py-2 ">
                        <a href="callto:${commission.commission_dial}" class="text--orange">${commission.commission_dial} </a>
                    </div>
                </div>
                <div class="col-12 col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">
                    <label for='del_flag_${currentIndex}'>削除</label>

                    </label>
                    <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 ">
                        <input type="hidden" value=0 name="commissionInfo[${currentIndex}][del_flg]" />
                        <input class="custom-control-input" id="del_flag_${currentIndex}" name="commissionInfo[${currentIndex}][del_flg]" type="checkbox" value=1 />
                        <label class="custom-control-label custome-label" for='del_flag_${currentIndex}'></label>
                    </div>
                </div>
            </div>
            <div class="row mx-0 form-table-cell">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">選定者</label>
                    <div class="col-lg-6 d-flex align-items-center py-2 ">
                        <select class="form-control appointers" name="commissionInfo[${currentIndex}][appointers]">
                            <option value="">--なし--</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 " >
                      <label for='first_commission_${currentIndex}'>初取次チェック</label>
                    </label>
                    <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 ">
                        <input type="hidden" name="commissionInfo[${currentIndex}][first_commission]" value=0 />
                        <input class="custom-control-input" id="first_commission_${currentIndex}" name="commissionInfo[${currentIndex}][first_commission]" type="checkbox" value=1 >
                        <label class="custom-control-label custome-label" for="first_commission_${currentIndex}"></label>
                    </div>
                </div>
            </div>
            <div class="row mx-0 form-table-cell">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">取次票送信者</label>
                    <div class="col-lg-6 d-flex align-items-center py-2 ">
                        <select class="form-control commission_note_sender now_date" id="commission_note_sender${currentIndex}" data-key="${currentIndex}" name="commissionInfo[${currentIndex}][commission_note_sender]">
                            <option value="">--なし--</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 " >
                      <label for='unit_price_calc_exclude_${currentIndex}'>取次単価対象外</label>
                    </label>
                    <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 ">
                        <input type="hidden" value="${commission.corp_id}" name="commissionInfo[${currentIndex}][corp_id]" id="CommissionInfo${currentIndex}CorpId" class="corp_id"/>
                        <input type="hidden" value=0 name="commissionInfo[${currentIndex}][unit_price_calc_exclude]"/>
                        <input class="custom-control-input" id="unit_price_calc_exclude_${currentIndex}" name="commissionInfo[${currentIndex}][unit_price_calc_exclude]" type="checkbox" value="1" >
                        <label class="custom-control-label custome-label" for="unit_price_calc_exclude_${currentIndex}"></label>
                    </div>
                </div>
            </div>
            <div class="row mx-0 form-table-cell">
                <div class="col-12 col-lg-6 row m-0 p-0">
                    <div class="col-12 col-lg-6 px-0 form__label form__label--white-light d-flex  align-items-center">
                        <label class="m-0 pl-3">
                            <strong>取次票送信日時</strong>
                        </label>
                    </div>
                    <div class="col-12 col-lg-6 py-2 d-flex align-items-center">
                        <div class="form-group d-flex justify-content-around mb-lg-0 w-100">
                            <input class="form-control date commission_note_send_datetime${currentIndex}" id="commission_note_send_datetime${currentIndex}" name="commissionInfo[${currentIndex}][commission_note_send_datetime]" type="text" value="">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 row m-0 p-0">
                    <div class="col-12 col-lg-6 px-0 form__label form__label--white-light font-weight-bold d-flex align-items-center ">
                        <label class="align-items-center m-0 pl-3">
                            <label for='commit_flg${currentIndex}'>確定</label>
                        </label>
                    </div>
                    <div class="col-12 col-lg-6 py-2">
                        <div class="d-inline-block align-middle custom-control custom-checkbox mr-sm-2">
                            <input type="hidden" name="commissionInfo[${currentIndex}][commit_flg]" value=0 />
                            <input data-id="${currentIndex}" onchange="checkCommissionFlg(this)" class="custom-control-input chk-commit-flg" id="commit_flg${currentIndex}" name="commissionInfo[${currentIndex}][commit_flg]" type="checkbox" value=1 />
                            <label class="custom-control-label" for="commissionInfo[${currentIndex}][commit_flg]">&nbsp;</label>
                        </div>
                        <div class="d-inline-block custom-control custom-checkbox demand-detail-custom-checkbox">
                            <span>(</span>
                            <input name="demandInfo[calendar_flg]" value="0" type="hidden" id="calendar_flg_${currentIndex}_">
                            <input class="custom-control-input calendar_check" id="calendar_flg_${currentIndex}" name="demandInfo[calendar_flg]" value="1" type="checkbox">
                            <label class="custom-control-label demand-detail-label-custom" for="calendar_flg_${currentIndex}">カレンダー使用:</label>
                            <span class="demand-detail-close">)</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mx-0 form-table-cell">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">メール/FAX送信</label>
                    <div class="col-lg-6 d-flex align-items-center py-2 ">
                         <p class="mb-0">
                            ${typeof commission.send_mail_fax_othersend !== "undefined" ? '個別送信' : (commission.send_mail_fax == 1 ? ('送信済み　' + commission.send_mail_fax_datetime) || '' : '')}
                            ${commission.send_mail_fax == 1 ? '送信済み　' + commission.send_mail_fax_datetime : ''}
                         </p>
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 " >
                      <label for='lost_flg${currentIndex}'>取次前失注</label>
                    </label>
                    <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 ">
                        <input type="hidden" value = 0 name="commissionInfo[${currentIndex}][lost_flg]"/>
                        <input class="custom-control-input" id="lost_flg${currentIndex}" name="commissionInfo[${currentIndex}][lost_flg]" type="checkbox" value=1 />
                        <label class="custom-control-label custome-label" for="lost_flg${currentIndex}"></label>
                    </div>
                </div>
            </div>
            <div class="row mx-0 form-table-cell">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">${commissionString}</label>
                    <div class="col-lg-6 d-flex align-items-center py-2 ">
                        <p class="mb-0">${commissionValue}</p>
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 " >
                      <label for='corp_claim_flg_${currentIndex}'>加盟店クレーム</label>
                    </label>
                    <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 ">
                        <input type="hidden" value=0 name="commissionInfo[${currentIndex}][corp_claim_flg]"/>
                        <input class="custom-control-input" id="corp_claim_flg_${currentIndex}" disabled name="commissionInfo[${currentIndex}][corp_claim_flg]" type="checkbox" value=1 >
                        <label class="custom-control-label custome-label" for="corp_claim_flg_${currentIndex}"></label>
                    </div>
                </div>
            </div>

            ` + showStaff + `

            <div class="row mx-0 form-table-cell">
                <div class="col-12 col-lg-6 row m-0 p-0">
                    <div class="col-12 col-lg-6 px-0 form__label form__label--white-light  d-flex align-items-center">
                        <label class="m-0 pl-3">
                            <strong>単価ランク</strong>
                        </label>
                    </div>
                    <div class="col-12 col-lg-6 py-2">
                        <div class="form-group d-flex  mb-lg-0 pt-3 pb-3 pr-3 pl-0 h-100">
                            <p class="m-0">${commission.commission_unit_price_rank} 取次単価 ${commission.commission_unit_price_display}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 row m-0 p-0">
                    <div class="col-12 col-lg-6 px-0 form__label form__label--white-light d-flex align-items-center ">
                        <label class="m-0 pl-3">
                            <strong></strong>
                        </label>
                    </div>
                    <div class="col-12 col-lg-6 py-2">

                    </div>
                </div>
            </div>



            <div class="row mx-0 form-table-cell" style="display: ${displayIntroduceNot};">
                <div class="col-lg-6 row m-0 p-0">&nbsp;</div>
                <div class="col-lg-6 row m-0 p-0">

                    <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">
                      <label for='introduction_not${currentIndex}'>紹介不可</label>
                    </label>

                    <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 ">
                        <input type="hidden" value=0 name="commissionInfo[${currentIndex}][introduction_not]" />
                        <input type="checkbox" id='introduction_not${currentIndex}' class="custom-control-input" value="1" name="commissionInfo[${currentIndex}][introduction_not]" disabled />
                        <label class="custom-control-label custome-label" for="introduction_not${currentIndex}"></label>
                    </div>
                </div>

            </div>


            <div class="row mx-0 form-table-cell">
                <div class="col-12 row m-0 p-0">
                    <div class="col-12 col-lg-3 px-0 form__label form__label--white-light d-flex align-items-center ">
                        <label class="m-0 pl-3">
                            <strong>注意事項</strong>
                        </label>
                    </div>
                    <div class="col-12 col-lg-9 py-2">
                        <div class="form-group d-flex mb-lg-0 pt-3 pb-3 pr-3 pl-0 h-100">
                            ${commission.attention}
                            <input type="hidden" name="commissionInfo[${currentIndex}][attention]" value="${commission.attention || ''}" />
                        </div>
                    </div>
                </div>
            </div>



            <div class="row mx-0 form-table-cell">
                <div class="col-12 row m-0 p-0">
                    <div class="col-12 col-lg-3 px-0 form__label form__label--white-light d-flex align-items-center ">
                        <label class="m-0 pl-3">
                            <strong>長期連休状況</strong>
                        </label>
                    </div>
                    <div class="col-12 col-lg-9 py-2">
                        <table class="table table-bordered table-list">
                            <thead>
                            <tr>
                                ${holidayLabels}
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                ${holidayValues}
                            </tr>
                            <tr>
                                <td class="text-center">備考</td>
                                <td class="text-center" colspan="7">
                                    <div id='select_long_vacation_note' style="display:inline-block">
                                        ${commission.long_vacation_note || ''}
									</div>
									<input type="hidden" value="${commission.long_vacation_note || ''}" name="commissionInfo[${currentIndex}][mCorpNewYear][long_vacation_note]"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <input type="hidden" value="${commission.long_vacation_note}" name="commissionInfo[${currentIndex}][mCorpNewYear][note]"/>
            <input type="hidden" value="${commission.order_fee_val}" name="commissionInfo[${currentIndex}][mCorpCategory][order_fee]"/>
            <input type="hidden" value="${commission.order_fee_unit}" name="commissionInfo[${currentIndex}][mCorpCategory][order_fee_unit]"/>
            <input type="hidden" value="${commission.m_corp_category_note}" name="commissionInfo[${currentIndex}][mCorpCategory][note]"/>
            <input type="hidden" value="${commission.fax}" name="commissionInfo[${currentIndex}][mCorp][fax]"/>
            <input type="hidden" value="${commission.mailaddress_pc}" name="commissionInfo[${currentIndex}][mCorp][mailaddress_pc]"/>
            <input type="hidden" value="${commission.coordination_method}" name="commissionInfo[${currentIndex}][mCorp][coordination_method]"/>
            <input type="hidden" value="${commission.contactable_time}" name="commissionInfo[${currentIndex}][mCorp][contactable_time]"/>
            <input type="hidden" value="${commission.holiday}" name="commissionInfo[${currentIndex}][mCorp][holiday]"/>
            <input type="hidden" value="${commission.commission_dial}" name="commissionInfo[${currentIndex}][mCorp][commission_dial]"/>
            <input type="hidden" value="${commission.corp_id}" name="commissionInfo[${currentIndex}][mCorp][id]"/>
            <input type="hidden" value="${commission.potition || ''}" name="commissionInfo[${currentIndex}][position]"/>

            <input type="hidden" value="" name="commissionInfo[${currentIndex}][send_mail_fax_datetime]"/>
            <input type="hidden" value="${commission.send_mail_fax || ''}" name="commissionInfo[${currentIndex}][send_mail_fax]"/>
            <input type="hidden" value="${commission.corp_commission_type != 2 ? 0 : 1}" name="commissionInfo[${currentIndex}][commission_type]"/>
            <input type="hidden" value="${commission.commission_unit_price || ''}" name="commissionInfo[${currentIndex}][select_commission_unit_price]"/>
            <input type="hidden" value="${commission.commission_unit_price_rank || ''}" name="commissionInfo[${currentIndex}][select_commission_unit_price_rank]"/>
            <input type="hidden" value="${commission.send_mail_fax_sender || ''}" name="commissionInfo[${currentIndex}][send_mail_fax_sender]"/>
            <input type="hidden" value="${commission.send_mail_fax_othersend || ''}" name="commissionInfo[${currentIndex}][send_mail_fax_othersend]"/>
            <input type="hidden" value="${commission.order_fee_unit || ''}" name="commissionInfo[${currentIndex}][order_fee_unit]"/>
            <input type="hidden" value='${commission.attention}' name="commissionInfo[${currentIndex}][attention]" />
            ${typeof demandInfoId !== "undefined" ? '<input type="hidden" value=' + demandInfoId + ' name="commissionInfo[' + currentIndex + '][demand_id]"/>' : ''}

            <input type="hidden" value="${commission.id || ''}" name="commissionInfo[${currentIndex}][id]"/>
            <input type="hidden" value="${commission.attention}" name="commissionInfo[${currentIndex}][affiliationInfo][attention]" />
        </div>`;
};
