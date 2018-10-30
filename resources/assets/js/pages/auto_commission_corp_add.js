$(document).ready(function() {
    var _token = $('#_token').val();
    var get_corp = $('#get_corp');
    var corp_id = $('#corp_id');
    var genre_id = $('#genre_id');
    var category_id = $('#category_id');
    var jis_cd = $('#jis_cd');
    var search_key = '';
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
        genre_id.find('option').remove();
        category_id.find('option').remove();
        $("#genre_id").multiselect('refresh');
        $("#category_id").multiselect('refresh');

        $('#ajax_response').parent().find('.fa').remove();
        if (search_key === 'search_by_name') {
            var search_value = $('#' + search_key).val();
        } else {
            var search_value = $('#search_by_id').val();
        }
        $.ajax({
            type: 'post',
            data: {
                search_key: search_key,
                search_value: search_value
            },
            url: url_get_corp_list,
            beforeSend: function() {
                get_corp.prop('disabled', true);
                $('#ajax_response').text(ajax_message_loading).removeClass();
            },
            success: function(results) {
                get_corp.prop('disabled', false);
                corp_id.children().remove();
                $.each(results.results, function(key, item) {
                    corp_id.append($('<option>').text(item.corp_name).attr('value', item.id));
                });
                $('#ajax_response').before("<i class='fa fa-check-circle text-success' aria-hidden='true'></i> ").text('該当する' + results.count + '件のうち、上位' + results.results.length + '件を取得');
            },
            error: function() {
                get_corp.prop('disabled', false);
                $('#ajax_response').before("<i class='fa fa-exclamation-circle text-danger' aria-hidden='true'></i> ").text(ajax_message_loading_kameiten_fail);
            },
        });
    });

    //get category id by corp_id
    corp_id.on('change', function() {
        category_id.find('option').remove();
        corp_id.nextAll('span').remove();
        $("#category_id").multiselect('refresh');
        if (corp_id.val()) {
            $.ajax({
                type: 'post',
                url: url_get_genre_list_by_corp_id,
                data: { corp_id: corp_id.val() },
                success: function(data) {
                    genre_id.children().remove();
                    genre_id.append($('<option>').text(un_select).attr('value', 'default'));
                    $.each(data, function(key) {
                        genre_id.append($('<option>').text(data[key].genre_name).attr('value', data[key].m_genres_id));
                    });
                    $("#genre_id").multiselect('refresh');
                },
                error: function() {

                }
            });
        } else {
            genre_id.children().remove();
            category_id.empty();
        }
    });

    //get category by genre_id
    genre_id.on('change', function() {
        genre_id.nextAll('span').remove();
        if (genre_id.val() && corp_id.val()) {
            $.ajax({
                type: 'post',
                url: url_get_category_by_genre_id_corp_id,
                data: {
                    genre_id: genre_id.val(),
                    corp_id: corp_id.val()
                },
                success: function(data) {
                    category_id.children().remove();
                    $.each(data, function(key) {
                        category_id.append($('<option>').text(data[key].category_name).attr('value', data[key].m_category_id));
                    });
                    $("#category_id").multiselect('refresh');
                },
                error: function() {}
            });
        } else {
            category_id.empty();
        }
    });
    category_id.on('change', function() {
        category_id.nextAll('span').remove();
    });
    jis_cd.on('change', function() {
        jis_cd.nextAll('span').remove();
    });
    genre_id.multiselect({
        multiple: false,
        selectedList: 5,
        noneSelectedText: un_select,
    }).multiselectfilter({
        label: ''
    });
    category_id.multiselect({
        checkAllText: check_all,
        uncheckAllText: un_check_all,
        selectedList: 5,
        noneSelectedText: un_select,
    }).multiselectfilter({
        label: ''
    });
    jis_cd.multiselect({
        checkAllText: check_all,
        uncheckAllText: un_check_all,
        selectedList: 5,
        noneSelectedText: un_select,
    }).multiselectfilter({
        label: ''
    });
    $('#save').on('click', function() {
        var count = 0;
        var corp_id_val = $('#corp_id').val();
        var genre_id_val = $('#genre_id').val();
        var category_id_val = $('#category_id').val();
        var jis_cd_val = $('#jis_cd').val();
        if (corp_id_val == null) {
            corp_id.nextAll('span').remove();
            corp_id.after($('<span class="text-danger"></span>').text(required_corp_id));
            count++;
        }
        if (isNaN(genre_id_val)) {
            genre_id.nextAll('span').remove();
            genre_id.next().after($('<span class="text-danger"></span>').text(required_genre_id));
            count++;
        }
        if (category_id_val.length == 0) {
            category_id.nextAll('span').remove();
            category_id.next().after($('<span class="text-danger"></span>').text(required_category_id));
            count++;
        }
        if (jis_cd_val.length == 0) {
            jis_cd.nextAll('span').remove();
            jis_cd.next().after($('<span class="text-danger"></span>').text(required_jicd));
            count++;
        }
        if (!$("input[name='process_type']:checked").val()) {
            $('#required_process_type').html(required_process_type);
            count++;
        }
        $("input[name='process_type']").on('click', function() {
            $('#required_process_type').html('');
        });
        if (count !== 0) {
            return false;
        } else {
            return true;
        }
    });
    $('#back').on('click', function() {
        window.location.href = url_auto_commission_corp_index;
    });
});