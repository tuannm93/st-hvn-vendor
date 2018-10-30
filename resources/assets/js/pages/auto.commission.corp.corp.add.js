var AutoCommissionCorpCorpAdd = function() {
    return {
        //main function to initiate the module
        init: function() {
            var _token = $('#_token').val();
            var get_corp = $('#get_corp');
            var corp_id = $('#corp_id');
            var category_id = $('#category_id');
            var pref_cd = $('#pref_cd');
            var target_commission_corp_id = $('#target_commission_corp_id');
            var target_selection_corp_id = $('#target_selection_corp_id');
            var array_corp_id = [];
            var array_target_commission = [];
            var array_target_selection = [];
            var array_tmp = [];
            var array_rest = [];
            var search_key = '';
            
            if ($('#target_commission_corp_id option').length != 0) {
                $('#target_commission_corp_id option').each(function() {
                    array_target_commission.push($(this));
                });
            }
            if ($('#target_selection_corp_id option').length != 0) {
                $('#target_selection_corp_id option').each(function() {
                    array_target_selection.push($(this));
                });
            }
            if ($('#corp_id option').length != 0) {
                $('#corp_id option').each(function() {
                    array_corp_id.push($(this));
                });
            };
            $('#corp_add').on('click', function() {
                window.location.href = url_auto_commission_corp_index;
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            $('#search_key').on('change', function() {
                $('#' + $(this).find('option:not(:selected)').val()).hide();
                $('#' + $(this).find('option:selected').val()).show();
                return search_key = $(this).find('option:selected').val();
            }).trigger('change');
            //get genre by corp_id
            get_corp.on('click', function() {
                $('#ajax_messages').prev('.fa-check-circle').remove();
                if (search_key === 'search_by_name') {
                    var search_value = $('#' + search_key).val();
                } else {
                    var search_value = $('#search_by_id').val();
                }
                target_commission_corp_id.find('option').prop('selected', true);
                target_selection_corp_id.find('option').prop('selected', true);
                $.ajax({
                    type: 'post',
                    data: {
                        search_key: search_key,
                        search_value: search_value,
                        commission_corp_id: target_commission_corp_id.val(),
                        selection_corp_id: target_selection_corp_id.val(),
                        category_id: category_id.val(),
                        pref_cd: pref_cd.val()
                    },
                    url: url_get_corp_add_list,
                    beforeSend: function() {
                        get_corp.prop('disabled', true);
                        $('#ajax_messages').text(loading_message).removeClass();
                    },
                    success: function(results) {
                        get_corp.prop('disabled', false);
                        corp_id.children().remove();
                        $.each(results.results, function(key, item) {
                            corp_id.append($('<option>').text(item.corp_name).attr('value', item.id));
                        });
                        $('#corp_id option').each(function() {
                            array_corp_id.push($(this));
                        });
                        target_commission_corp_id.find('option').prop('selected', false);
                        target_selection_corp_id.find('option').prop('selected', false);
                        $('#ajax_messages').before("<i class='fa fa-check-circle' aria-hidden='true'></i> ").text('該当する' + results.count + '件のうち、上位' + results.results.length + '件を取得');
                    },
                    error: function() {
                        get_corp.prop('disabled', false);
                        $('#ajax_messages').text(loading_message_fail).removeClass().addClass('alert alert-danger');
                        target_commission_corp_id.find('option').prop('selected', false);
                        target_selection_corp_id.find('option').prop('selected', false);
                    }
                });
            });
            $('.btn--gradient-default').on('click', function() {
                let key = $(this).attr('data');
                switch (key) {
                    case 'corp_id_to_target_commission':
                        if (array_tmp.length != 0) {
                            changeData('corp_id_to_target_commission', corp_id, target_commission_corp_id);
                        }
                        break;
                    case 'corp_id_to_target_selection':
                        if (array_tmp.length != 0) {
                            changeData('corp_id_to_target_selection', corp_id, target_selection_corp_id);
                        }
                        break;
                    case 'target_commission_to_target_selection':
                        if (array_tmp.length != 0) {
                            changeData('target_commission_to_target_selection', target_commission_corp_id, target_selection_corp_id);
                        }
                        break;
                    case 'target_commission_to_corp_id':
                        if (array_tmp.length != 0) {
                            changeData('target_commission_to_corp_id', target_commission_corp_id, corp_id);
                        }
                        break;
                    case 'target_selection_to_target_commission':
                        if (array_tmp.length != 0) {
                            changeData('target_selection_to_target_commission', target_selection_corp_id, target_commission_corp_id);
                        }
                        break;
                    case 'target_selection_to_corp_id':
                        if (array_tmp.length != 0) {
                            changeData('target_selection_to_corp_id', target_selection_corp_id, corp_id);
                        }
                        break;
                    case 'commission_item_up':
                        if (array_tmp.length != 0) {
                            moveItem('commission_item_up', target_commission_corp_id);
                        }
                        break;
                    case 'commission_item_down':
                        if (array_tmp.length != 0) {
                            moveItem('commission_item_down', target_commission_corp_id);
                        }
                        break;
                    case 'selection_item_up':
                        if (array_tmp.length != 0) {
                            moveItem('selection_item_up', target_selection_corp_id);
                        }
                        break;
                    case 'selection_item_down':
                        if (array_tmp.length != 0) {
                            moveItem('selection_item_down', target_selection_corp_id);
                        }
                        break;
                }
            });
            $('#corp_select').on('click', function() {
                target_commission_corp_id.find('option').prop('selected', true);
                target_selection_corp_id.find('option').prop('selected', true);
            });

            $('#target_commission_corp_id').on('click', function() {
                resetSelected('target-commission-corp-id');
                array_tmp = [];
                $('#target_commission_corp_id').children().each(function() {
                    if ($(this).is(':selected')) {
                        array_tmp.push($(this));
                    }
                });
                getRestData(array_tmp, array_target_commission);
            });

            $('#target_selection_corp_id').on('click', function() {
                resetSelected('target-selection-corp-id');
                array_tmp = [];
                $('#target_selection_corp_id').children().each(function() {
                    if ($(this).is(':selected')) {
                        array_tmp.push($(this));
                    }
                });
                getRestData(array_tmp, array_target_selection);
            });

            $('#corp_id').on('click', function() {
                resetSelected('corp-id');
                array_tmp = [];
                $('#corp_id').children().each(function() {
                    if ($(this).is(':selected')) {
                        array_tmp.push($(this));
                    }
                });
                getRestData(array_tmp, array_corp_id);
            });

            function getRestData(array_selected, array_source) {
                array_rest = [];
                let selected_list = [];
                let source_list = [];
                for (let i = 0; i < array_selected.length; i++) {
                    selected_list.push(array_selected[i].prop('value'));
                }
                for (let i = 0; i < array_source.length; i++) {
                    source_list.push(array_source[i].prop('value'));
                }
                for (let i = 0; i < source_list.length; i++) {
                    if (!selected_list.includes(source_list[i])) {
                        array_rest.push(array_source[i]);
                    };
                }
            }

            function changeData(array_from, id_from, id_to) {
                let array_selected = [];
                let array_source = [];
                id_from.children().each(function() {
                    if ($(this).is(':selected')) {
                        array_selected.push($(this));
                    }
                    array_source.push($(this));
                });
                getRestData(array_selected, array_source);
                id_from.empty();
                $.each(array_selected, function(key, item) {
                    id_to.append($('<option selected>').text(item.prop('label')).attr('value', item.prop('value')));
                });
                $.each(array_rest, function(key, item) {
                    id_from.append($('<option>').text(item.prop('label')).attr('value', item.prop('value')));
                });
                switch (array_from) {
                    case 'corp_id_to_target_commission':
                        array_corp_id = array_rest;
                        last_destination = 'target-corp-commission';
                        for (let i = 0; i < array_tmp.length; i++) {
                            if (!array_target_commission.includes(array_tmp[i])) {
                                array_target_commission.push(array_tmp[i]);
                            }
                        }
                        break;
                    case 'corp_id_to_target_selection':
                        array_corp_id = array_rest;
                        last_destination = 'target-corp-selection';
                        for (let i = 0; i < array_tmp.length; i++) {
                            if (!array_target_selection.includes(array_tmp[i])) {
                                array_target_selection.push(array_tmp[i]);
                            }
                        }
                        break;
                    case 'target_commission_to_target_selection':
                        array_target_commission = array_rest;
                        last_destination = 'target-corp-selection';
                        for (let i = 0; i < array_tmp.length; i++) {
                            if (!array_target_selection.includes(array_tmp[i])) {
                                array_target_selection.push(array_tmp[i]);
                            }
                        }
                        break;
                    case 'target_commission_to_corp_id':
                        array_target_commission = array_rest;
                        last_destination = 'corp-id';
                        for (let i = 0; i < array_tmp.length; i++) {
                            if (!array_corp_id.includes(array_tmp[i])) {
                                array_corp_id.push(array_tmp[i]);
                            }
                        }
                        break;
                    case 'target_selection_to_target_commission':
                        array_target_selection = array_rest;
                        last_destination = 'target-corp-commission';
                        for (let i = 0; i < array_tmp.length; i++) {
                            if (!array_target_commission.includes(array_tmp[i])) {
                                array_target_commission.push(array_tmp[i]);
                            }
                        }
                        break;
                    case 'target_selection_to_corp_id':
                        array_target_selection = array_rest;
                        last_destination = 'corp-id';
                        for (let i = 0; i < array_tmp.length; i++) {
                            if (!array_corp_id.includes(array_tmp[i])) {
                                array_corp_id.push(array_tmp[i]);
                            }
                        }
                        break;
                }
            }

            function moveItem(key, array_id) {
                let tmp_item = [];
                let old_site = [];
                let old_array = [];
                let updown = 0;
                array_id.children().each(function() {
                    old_array.push($(this));
                });
                for (let i = 0; i < old_array.length; i++) {
                    if (old_array[i].prop('selected')) {
                        old_site.push(i);
                    }
                }

                switch (key) {
                    case 'commission_item_up':
                        updown = -1;
                        break;
                    case 'commission_item_down':
                        updown = 1;
                        break;
                    case 'selection_item_up':
                        updown = -1;
                        break;
                    case 'selection_item_down':
                        updown = 1;
                        break;
                }
                if (old_site[0] == 0 && updown == -1 || old_site[old_site.length - 1] == (old_array.length - 1) && updown == 1) {
                    return;
                }
                array_id.empty();
                switch (key) {
                    case 'commission_item_up':
                    case 'selection_item_up':
                        for (let i = 0; i < old_site.length; i++) {
                            tmp_item = old_array[old_site[i] + updown];
                            old_array[old_site[i] + updown] = old_array[old_site[i]];
                            old_array[old_site[i]] = tmp_item;
                        }
                        break;
                    case 'commission_item_down':
                    case 'selection_item_down':
                        for (let i = old_site.length - 1; i >= 0; i--) {
                            tmp_item = old_array[old_site[i] + updown];
                            old_array[old_site[i] + updown] = old_array[old_site[i]];
                            old_array[old_site[i]] = tmp_item;
                        }
                        break;
                }

                switch (key) {
                    case 'commission_item_up':
                    case 'commission_item_down':
                        array_target_commission = old_array;
                        $.each(array_target_commission, function(key, item) {
                            array_id.append($('<option>').text(item.prop('label')).attr({ 'value': item.prop('value'), 'selected': item.prop('selected') }));
                        });
                        break;
                    case 'selection_item_up':
                    case 'selection_item_down':
                        array_target_selection = old_array;
                        $.each(array_target_selection, function(key, item) {
                            array_id.append($('<option>').text(item.prop('label')).attr({ 'value': item.prop('value'), 'selected': item.prop('selected') }));
                        });
                        break;
                };
            }

            function resetSelected(key) {
                let field_remove_1 = '';
                let field_remove_2 = '';
                switch (key) {
                    case 'corp-id':
                        field_remove_1 = '#target_commission_corp_id';
                        field_remove_2 = '#target_selection_corp_id';
                        break;
                    case 'target-commission-corp-id':
                        field_remove_1 = '#corp_id';
                        field_remove_2 = '#target_selection_corp_id';
                        break;
                    case 'target-selection-corp-id':
                        field_remove_1 = '#corp_id';
                        field_remove_2 = '#target_commission_corp_id';
                        break;
                }

                $(field_remove_1).children().each(function() {
                    $(this).prop("selected", false);
                });
                $(field_remove_2).children().each(function() {
                    $(this).prop("selected", false);
                });
            }
        }
    }
}();