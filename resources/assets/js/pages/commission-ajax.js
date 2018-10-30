var ajaxCommon = (function() {

    // global variable
    var bodyRoot = $('body'),
        progressBlock = $('.progress-block'),
        progressBar = $('.progress');

    // control progress class
    var controlProgress = function(isShow) {
        if (isShow) {
            progressBlock.show();
            progressBar.show();
        } else {
            progressBlock.hide();
            progressBar.hide();
        }
    };

    var createXHR = function() {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(evt) {
            var percentComplete = evt.loaded / evt.total;
            progressBar.css({
                width: percentComplete * 100 + "%"
            });
        }, false);
        xhr.addEventListener("progress", function(evt) {
            var percentComplete = evt.loaded / evt.total;
            progressBar.css({
                width: percentComplete * 100 + "%"
            });
        }, false);
        return xhr;
    };

    var hashPage = function(page, controlEl) {
        if (page > 1) {
            location.hash = page;
        }
        if (page == 0)
            ++page;

        setPage(page, controlEl);
    };

    var getSorted = function(el) {
        var sorted = {};
        if ($(el).attr('data-sort-name')) {
            sorted.name = $(el).attr('data-sort-name');
        }
        if ($(el).attr('data-order-by')) {
            sorted.orderBy = $(el).attr('data-order-by');
        }
        return sorted;
    };

    var setSorted = function(sorted, controlEl) {
        if (controlEl.hasOwnProperty('nextPage')) {
            $(controlEl.nextPage).attr('data-sort-name', sorted.name);
            $(controlEl.nextPage).attr('data-order-by', sorted.orderBy);
        }
        if (controlEl.hasOwnProperty('prevPage')) {
            $(controlEl.nextPage).attr('data-sort-name', sorted.name);
            $(controlEl.nextPage).attr('data-order-by', sorted.orderBy);
        }
    };

    var getPage = function(el) {
        var page = 0;
        if ($(el).attr('data-cur-page'))
            page = parseInt($(el).attr('data-cur-page'));

        return page;
    };

    var setPage = function(page, controlEl) {
        if (controlEl.hasOwnProperty('nextPage')) {
            $(controlEl.nextPage).attr('data-cur-page', page);
        }
        if (controlEl.hasOwnProperty('prevPage')) {
            $(controlEl.prevPage).attr('data-cur-page', page);
        }

        if (controlEl.hasOwnProperty('sorts')) {
            // set attribute data-cur-page for all sort item
            $.each(controlEl.sorts, function(index, el) {
                $(el).attr('data-cur-page', page);
            });
        }
    };

    var createMessageBlock = function(msgState, msgContent) {
        var html = '';
        if (msgState == 'warning') {
            html += '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
        } else if (msgState == 'error') {
            html += '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        } else {
            html += '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        }

        html += msgContent;
        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        html += '    <span aria-hidden="true">&times;</span>';
        html += '  </button>';
        html += '</div>';

        bodyRoot.append(html);
    };

    var getPostAndSortingAndPaging = function(url, page, sorted, controlEl) {
        var curPage = 0;
        var methodRequest = 'get';
        var urlSearch = url;

        if (typeof page != "undefined" && page != null)
            curPage = parseInt(page);

        if (curPage > 0)
            urlSearch = urlSearch + '?page=' + curPage;

        if (controlEl.hasOwnProperty('formId') && controlEl.formId != '')
            methodRequest = $(controlEl.formId).attr('method');

        if (typeof sorted != "undefined" && !jQuery.isEmptyObject(sorted)) {
            if(curPage > 0) {
                urlSearch = urlSearch + '&sortName=' + sorted.name + '&orderBy=' + sorted.orderBy;
            } else {
                urlSearch = urlSearch + '?sortName=' + sorted.name + '&orderBy=' + sorted.orderBy;
            }
        }
        $.ajax({
            type: methodRequest,
            url: urlSearch,
            data: controlEl.hasOwnProperty('formId') ? $(controlEl.formId).serialize() : '',
            processData: false,
            xhr: function() {
                return createXHR();
            },
            beforeSend: function() {
                controlProgress(true);
            },
            complete: function() {
                controlProgress(false);
                if (controlEl.hasOwnProperty('isInitSearch') && controlEl.isInitSearch == true) {
                    controlEl.isInitSearch = !controlEl.isInitSearch;
                }
            },
            success: function(data) {
                if (data.length) {
                    $(controlEl.resultArea).html(data).promise().done(function(){
                        var showCSV = jQuery('#temp_display').attr('data-display');
                        if (showCSV == 1) {
                            if (jQuery('#btnExport').hasClass('d-none')){
                                jQuery('#btnExport').removeClass('d-none');
                            }
                        } else {
                            if (!jQuery('#btnExport').hasClass('d-none'))
                                jQuery('#btnExport').addClass('d-none');
                        }
                    });
                    hashPage(curPage, controlEl);
                    if (typeof sorted != "undefined" || sorted != null) {
                        setSorted(sorted, controlEl);
                    }
                    if (typeof controlEl.scrollBar != "undefined") {
                        initScrollBar.initPseudoScrollBar();
                    }
                    window.scrollTo(0, 0);
                }
            },
            error: function(err) {
                console.log(err.message);
            }
        });
    };

    var exportFile = function(url, formRequest) {
        var methodRequest = 'get';

        if (formRequest != null)
            methodRequest = $(formRequest).attr('method');

        $.ajax({
            type: methodRequest,
            url: url,
            dataType: 'json',
            data: (typeof formRequest != "undefined" || formRequest != null) ? $(formRequest).serialize() : '',
            processData: false,
            xhr: function() {
                return createXHR();
            },
            beforeSend: function() {
                controlProgress(true);
            },
            complete: function() {
                controlProgress(false);
            },
            success: function(data) {
                window.open(data.url, '_blank');
                createMessageBlock('success', 'Downloaded');
            },
            error: function(err) {
                console.log(err.message);
            }
        });
    };

    var getCurrentPage = function (page) {
        var hashPage = location.hash;
        if (hashPage != '' && hashPage != null){
            page = hashPage.replace( /\D+/g, '');
        }
        return page;
    };

    var search = function(url, controlEl) {
        var sorted = {};
        var page = 0;

        //load current page
        page = getCurrentPage(page);
        //load page when click enter

        if (controlEl.hasOwnProperty('isInitSearch') && controlEl.isInitSearch == true) {
            getPostAndSortingAndPaging(url, page == 0 ? null : page, sorted, controlEl);
        }

        // search button
        if (controlEl.hasOwnProperty('searchEl')) {
            $(controlEl.searchEl).on('click', function() {
                if ($(this)[0].type !== 'submit') {
                    $(controlEl.formId).valid();
                }
                if ($(controlEl.formId).valid()) {
                    getPostAndSortingAndPaging(url, null, sorted, controlEl);
                }
            });
        }

        if (controlEl.hasOwnProperty('nextPage')) {
            $(document).on('click', controlEl.nextPage, function(e) {
                page = getPage(controlEl.nextPage);
                sorted = getSorted(controlEl.nextPage);
                getPostAndSortingAndPaging(url, ++page, sorted, controlEl);
                e.preventDefault();
            });
        }
        if (controlEl.hasOwnProperty('prevPage')) {
            $(document).on('click', controlEl.prevPage, function(e) {
                page = getPage(controlEl.prevPage);
                sorted = getSorted(controlEl.prevPage);
                getPostAndSortingAndPaging(url, --page, sorted, controlEl);
                e.preventDefault();
            });
        }

        if (controlEl.hasOwnProperty('sorts')) {
            $.each(controlEl.sorts, function(idx, el) {
                $(document).on('click', el, function(e) {
                    sorted = getSorted(el);
                    page = getPage(el);

                    getPostAndSortingAndPaging(url, page, sorted, controlEl);
                    e.preventDefault();
                });
            });
        }
    };

    var download = function() {};

    var exportCsv = function(url, controlEl) {
        var formRequest = null;
        if (controlEl.hasOwnProperty('formId') && controlEl.formId != '')
            formRequest = controlEl.formId;

        if (controlEl.hasOwnProperty('exportEl')) {
            $(controlEl.exportEl).on('click', function() {
                exportFile(url, formRequest);
            });
        }
    };

    return {
        search: search,
        download: download,
        exportCsv: exportCsv
    };
})();

var progressCommon = (function() {

    var progressNode = '.progress-block',
        progressBar = '.progress';

    function progressCommon() {

    }

    progressCommon.prototype.createXHR = function() {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(evt) {
            var percentComplete = evt.loaded / evt.total;
            $(progressBar).css({
                width: percentComplete * 100 + "%"
            });
        }, false);
        xhr.addEventListener("progress", function(evt) {
            var percentComplete = evt.loaded / evt.total;
            $(progressBar).css({
                width: percentComplete * 100 + "%"
            });
        }, false);
        return xhr;
    };

    progressCommon.prototype.controlProgress = function(isShow) {
        if (isShow) {
            $(progressNode).show();
            $(progressBar).show();
        } else {
            $(progressNode).hide();
            $(progressBar).hide();
        }
    };

    return progressCommon;
})();

var popupCommon = (function() {
    function popupCommon(type, info) {
        // set object
        this.info = info || {};

        this.header = "<div class=\"modal fade\"  tabindex=\"-1\" role=\"dialog\"  aria-hidden=\"true\">";
        this.header += "<div class=\"modal-dialog\" role=\"document\">";
        this.header += "<div class=\"modal-content\">";
        this.header += "<div class=\"modal-body\">";
        this.header += "<p class=\"help-block\">" + (this.info.msg || "") + "<\/p>";
        this.header += "<\/div>";
        this.preContentFooter = "<div class=\"modal-footer\">";
        this.sufContentFooter = "<\/div>";
        this.footer = "<\/div>";
        this.footer += "<\/div>";
        this.footer += "<\/div>";
        this.actionClose = "<button type=\"button\" class=\"btn btn--gradient-gray st-pp-close\" data-dismiss=\"modal\">" + (this.info.close || "Close") + "<\/button>";
        this.actionConfirm = "<button type=\"button\" class=\"btn btn--gradient-green st-pp-confirm\">" + (this.info.confirm || "Save changes") + "<\/button>";

        // set default type is alert popup
        var popupType = 0;
        try {
            popupType = parseInt(type);
            if (popupType < 0 || popupType > 2) {
                popupType = 0;
            }
        } catch (error) {
            console.log(error);
        }
        this.type = popupType;
    }

    popupCommon.prototype.renderView = function() {
        if (this.type == 0) { // alert
            return this.header + this.preContentFooter + this.actionClose + this.sufContentFooter + this.footer;
        } else if (this.type == 1) { //confirm
            return this.header + this.preContentFooter + this.actionConfirm + this.actionClose + this.sufContentFooter + this.footer;
        } else if (this.type == 2) { //confirm inverse
            return this.header + this.preContentFooter + this.actionClose + this.actionConfirm + this.sufContentFooter + this.footer;
        } else {
            return this.header + this.footer;
        }
    }

    return popupCommon;
})();
