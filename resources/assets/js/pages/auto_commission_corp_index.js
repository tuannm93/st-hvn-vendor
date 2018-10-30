var AutoCommissionCorpIndex = function() {
    var _token = $('#_token').val();
    var genre_id = $('#genre_id');
    var pageData = $('#page-data');

    function multiSelect() {
        genre_id.multiselect({
            checkAllText: check_all,
            uncheckAllText: un_check_all,
            selectedList: 5,
            noneSelectedText: un_select,
        }).multiselectfilter({
            label: ''
        });
    }

    function beforeSendAjax() {
        $('#ajax_message').text(ajax_message_loading);
        $('#seach_genre_button').prop('disabled', true);
        $('#genre_id').prop('disabled', true);
    }

    function doneAjax(res) {
        $('#ajax_message').before("<i class='fa fa-check-circle text-success' aria-hidden='true'></i> ").text(ajax_message_success);
        $('#seach_genre_button').prop('disabled', false);
        $('#genre_id').prop('disabled', false);

        var row_body_table_content = '';
        if (res.status == 200) {
            $.each(res.category, function(genre_category_key, genre_category_val) {
                var row_style = '';
                var selectionName = res.selection[genre_category_val.select_type];
                selectionName = selectionName === undefined ? '' : selectionName;

                row_body_table_content += '<div class="d-flex">';
                row_body_table_content += '<span' + row_style + ' class="p-1 p-sm-2 fix-w-50 fix-w-sm-100 text-wrap item z-index-1002">' + genre_category_val.genre_name + '</span>';
                row_body_table_content += '<span' + row_style + ' class="p-1 p-sm-2 fix-w-50 fix-w-sm-100 text-wrap item z-index-1002">' + genre_category_val.category_name + '</span>';
                row_body_table_content += '<span' + row_style + ' class="p-1 p-sm-2 fix-w-50 fix-w-sm-100 text-wrap item z-index-1002">' + selectionName + '</span>';

                $.each(res.pref_list, function(prep_key, pref_val) {
                    row_body_table_content += '<span class="p-1 p-sm-2 fix-w-50 fix-w-sm-100 text-wrap item">';
                    $.each(res.corp, function(key, val) {
                        if (pref_val.pref_id == val.pref_cd && genre_category_val.id == val.category_id) {
                            if (val.process_type == "1") {
                                row_body_table_content += "<div class='auto_selection_text text-primary'>・" +
                                    "<a href=\"/auto_commission_corp/corp_add?genre_id=" + val.genre_id + "&category_id=" + val.category_id + "&pref_cd=" + val.pref_cd + "\" class='text-primary'>" +
                                    val.corp_name + "</a>";
                                row_body_table_content += '</div>';
                            } else {
                                row_body_table_content += "<div class='auto_commission_text text-danger'>・" +
                                    "<a href=\"/auto_commission_corp/corp_add?genre_id=" + val.genre_id + "&category_id=" + val.category_id + "&pref_cd=" + val.pref_cd + "\" class='text-danger'>" +
                                    val.corp_name +
                                    "</a>";
                                row_body_table_content += '</div>';
                            }
                        }
                    });

                    row_body_table_content += '</span>';
                });

                row_body_table_content += '</div>';
            });
        }
        $('.autocommission-loading').css('display', 'none');
        $('.body-table-content').html(row_body_table_content);
        $('.fix-body-sidebar-right').html(row_body_table_content);
        if (smartDevice.checkMobile()) {
            window.history.replaceState({ dataBack: row_body_table_content }, '', '');
        }
    }

    function failAjax() {
        table_html = '';
        $('#ajax_table_data').html(table_html);
        $('#ajax_message').text(ajax_message_loading_fail);
        $('#seach_genre_button').prop('disabled', false);
        $('#genre_id').prop('disabled', false);
        $('.autocommission-loading-fail').css('display', 'contents');
    }

    function alwayAjax() {
        $('#seach_genre_button').prop('disabled', false);
        $('#genre_id').prop('disabled', false);
    }

    function searchAtGenre() {
        var genre_id = $('#genre_id').val();
        $('#error-inner').text("");
        $('#ajax_message').prev('.fa-check-circle').remove();

        $.ajax({
            type: 'get',
            url: pageData.data('url-search'),
            data: {
                'genre_id': genre_id
            },
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            beforeSend: function(jqXHR, settings) {
                beforeSendAjax();
            }
        }).done(function(res, textStatus, jqXHR) {
            doneAjax(res);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            failAjax();
        }).always(function(data, textStatus, jqXHR) {
            alwayAjax();
        });
    }

    function searchButton() {
        $('#seach_genre_button').click(function() {
            searchAtGenre();
        });
    }

    function getAllGenre() {
        $.ajax({
            type: 'get',
            url: pageData.data('url-all'),
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            beforeSend: function(jqXHR, settings) {
                beforeSendAjax();
            }
        }).done(function(res, textStatus, jqXHR) {
            doneAjax(res);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            failAjax();
        }).always(function(data, textStatus, jqXHR) {
            alwayAjax();
        });
    }

    return {
        init: function() {
            multiSelect();
            searchButton();
            if (!window.history.state) {
                getAllGenre();
            }
            $(document).ready(function () {
                if (window.history.state && window.history.state.dataBack) {
                    $('.body-table-content').html(window.history.state.dataBack);
                    $('.fix-body-sidebar-right').html(window.history.state.dataBack);
                    $('.autocommission-loading').css('display', 'none');
                }
                var offset_top_header_table = $('.mark-up-header');
                var height_btn_multiselect = $('.ui-multiselect').height();
                var offset_top_header = 0;
                var tablet_width = 576;
                if ($(window).width() > tablet_width) {
                    offset_top_header = offset_top_header_table.offset().top - 19;
                } else {
                    offset_top_header = offset_top_header_table.offset().top;
                }
                var colspan_rest = Number($('.header-right').attr('data-colspan-rest'));
                $('.fix-header-sidebar-left, .fix-header-sidebar-right, .pseudo-border-left, .pseudo-border-right').css({ 'position': 'fixed', 'top': offset_top_header, 'display': 'block' });
                $('.pseudo-border-left, .pseudo-border-right').css('height', $(window).height());
                if ($(window).width() > tablet_width) {
                    if ($(window).width() < 678) {
                        $('.fix-header-sidebar-left, .fix-header-sidebar-right').css({ 'position': 'fixed', 'top': offset_top_header + 19, 'display': 'block' });
                    }
                    $('.fix-body-sidebar-right').css({ 'position': 'fixed', 'top': offset_top_header + 76, 'display': 'block' });
                    $('.fix-header-sidebar-right').css('left', 308);
                    $('.colspan-rest').css('width', colspan_rest * 100);
                } else {
                    $('.fix-body-sidebar-right').css({ 'position': 'fixed', 'top': offset_top_header + 95, 'display': 'block' });
                    $('.fix-header-sidebar-right').css('left', 158);
                    $('.colspan-rest').css('width', colspan_rest * 50);
                }
                $('.autocommission-index').closest('.container').removeClass('container');
                $('.pseudo-table-scroll-bar').scroll(function() {
                    var left = Number($('.pseudo-table-scroll-bar').scrollLeft());
                    if ($(window).width() > tablet_width) {
                        $('.fix-header-sidebar-right').css('left', 308 - left);
                        $('.fix-body-sidebar-right').css('left', -left + 8);
                    } else {
                        $('.fix-header-sidebar-right').css('left', 158 - left);
                        $('.fix-body-sidebar-right').css('left', -left + 8);
                    }
                });
                $('#genre_id').change(function() {
                    setTimeout(function() {
                        offset_top_header = offset_top_header_table.offset().top;
                        var current_height = $('.ui-multiselect').height();
                        if (current_height != height_btn_multiselect) {
                            $('.fix-header-sidebar-left, .fix-header-sidebar-right, .pseudo-border-left, .pseudo-border-right').css('top', offset_top_header - window.pageYOffset);
                            if ($(window).width() > tablet_width) {
                                $('.fix-body-sidebar-right').css('top', offset_top_header + 76 - window.pageYOffset);
                            } else {
                                $('.fix-body-sidebar-right').css('top', offset_top_header + 95 - window.pageYOffset);
                            }
                        } else {
                            $('.fix-header-sidebar-left, .fix-header-sidebar-right,  .pseudo-border-left, .pseudo-border-right').css('top', offset_top_header - window.pageYOffset);
                            if ($(window).width() > tablet_width) {
                                $('.fix-body-sidebar-right').css('top', offset_top_header + 76 - window.pageYOffset);
                            } else {
                                $('.fix-body-sidebar-right').css('top', offset_top_header + 95 - window.pageYOffset);
                            }
                        }
                    }, 50);
                });
                window.onscroll = function() {
                    offset_top_header = offset_top_header_table.offset().top;
                    if (window.pageYOffset < offset_top_header && window.pageYOffset != offset_top_header) {
                        $('.fix-header-sidebar-left, .fix-header-sidebar-right, .pseudo-border-left, .pseudo-border-right').css('top', offset_top_header - window.pageYOffset);
                        if ($(window).width() > tablet_width) {
                            $('.fix-body-sidebar-right').css('top', offset_top_header + 76 - window.pageYOffset);
                        } else {
                            $('.fix-body-sidebar-right').css('top', offset_top_header + 95 - window.pageYOffset);
                        }
                    } else {
                        $('.fix-header-sidebar-left, .fix-header-sidebar-right, .pseudo-border-left, .pseudo-border-right').css('top', 0);
                        $('.fix-body-sidebar-right').css('top', offset_top_header + 76 - window.pageYOffset);
                        if ($(window).width() > tablet_width) {
                            $('.fix-body-sidebar-right').css('top', offset_top_header + 76 - window.pageYOffset);
                        } else {
                            $('.fix-body-sidebar-right').css('top', offset_top_header + 95 - window.pageYOffset);
                        }
                    }
                    if ($(window).width() > tablet_width) {
                        if ($(window).scrollTop() + $(window).height() == $(document).height()) {
                            $('.pseudo-table-scroll-bar').css('bottom', 52);
                        } else {
                            $('.pseudo-table-scroll-bar').css('bottom', 0);
                        }
                    } else if ($(window).width() < tablet_width) {
                        var endScroll = $(document).height() - 97;
                        if ($(window).scrollTop() + $(window).height() > endScroll) {
                            $('.pseudo-table-scroll-bar').css('bottom', 97 - ($(document).height() - $(window).scrollTop() - $(window).height()));
                            return;
                        }
                        $('.pseudo-table-scroll-bar').css({'bottom': 0, 'width': $(window).width() - 16, 'margin-left': 8});
                    }
                };
            });
        }
    }
}();
window.onbeforeunload = function() {
    window.scrollTo(0, 0);
}