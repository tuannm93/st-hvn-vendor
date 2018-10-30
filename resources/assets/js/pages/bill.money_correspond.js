var MoneyCorrespond = function() {
    var moneyId;
    var bodyTable   = $('table tbody');
    var corp_id     = $('#m_corp').val();
    var searchValue = $("input[name='search_nominee']").val();
    const ASC  = 'asc';
    const DESC = 'desc';

    function orderDepositDate(){
        $('.order-asc').click(function () {
            getListMoneyData(ASC);
        });

        $('.order-desc').click(function () {
            getListMoneyData(DESC);
        });
    }

    function emptyData() {
        bodyTable.empty()
    }

    function getListMoneyData(order) {
        var args = {
            type: 'POST',
            data: {
                order       : order,
                searchValue : searchValue,
                corp_id     : corp_id
            },
            url: urlGetListMoneyData,
            beforeSend: function ()
            {
            },
            error: function (data, status, errThrown)
            {
            },
            success: function (data)
            {
                emptyData();
                makeTable(data.listMoneyData);
            }
        };

        $.ajax(args);
    }

    function makeTable(listMoneyData){
        var content = '';
        for(var i in listMoneyData){
            content += '<tr><td class="text-center w-16-7">' + listMoneyData[i].payment_date + '</td>';
            content += '<td class="text-left w-16-7">' + listMoneyData[i].nominee + '</td>';
            content += '<td class="text-right w-20">' +  listMoneyData[i].payment_amount + '</td>';
            content += '<td class="text-center w-5">';
            content += '<button class="btn btn-sm btn--gradient-gray btn-remove-deposit" data-id="'+ listMoneyData[i].id +'">削除</button>' ;
            content += '</td></tr>';
        }
        bodyTable.append(content);
    }

    function bindConfirmRemoveDeposit() {
        $(document).on('click', '.btn-remove-deposit', function (){
            moneyId = $(this).data('id');
            $('#dialogConfirmDelete').modal('show');
        });
    }

    function bindRemoveDeposit() {
        $('#btn_confirm').click(function() {
            $.ajax({
                url: $('#route_remove').data('route'),
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: moneyId
                }, success: function(data) {
                    window.location.reload();
                }, error: function(data) {
                    alert('ERROR');
                }
            })
        })
    }

    function handleBtnInsert() {
        $('#btn-insert').click(function (e) {
            $('#form-bill-money-correspond').submit();
            $(this).attr('disabled',true);
        })
    }

    function removeMessageSuccess() {
        $('.input-insert').click(function () {
            $('.alert-success').remove();
            $('#btn-insert').attr('disabled',false);
        });
    }

    function init() {
        bindConfirmRemoveDeposit();
        bindRemoveDeposit();
        orderDepositDate();
        handleBtnInsert();
        removeMessageSuccess();
    }

    return {
        init: init
    }
}();

jQuery(document).ready(function () {
    MoneyCorrespond.init();
    Datetime.initForDatepicker();
    FormUtil.validate('#form-bill-money-correspond');
});
