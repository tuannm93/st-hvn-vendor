<?php

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */

Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, Config::get('app.locales'))) {
        Session::put('locale', $locale);
    }

    return redirect()->back();
});

Route::get('home', 'Home\HomeController@index')->name('homepage');
Route::get('login', 'Auth\LoginController@handleLogin')->name('login');
Route::post('ios_login/login.json', 'Auth\MobileLoginController@iosLogin');
Route::post('android_login/login.json', 'Auth\MobileLoginController@androidLogin');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/guideline', 'Guideline\GuidelineController@index')->name('guideline.index');
Route::get('/guideline/index', function () {
    return redirect()->route('guideline.index');
});
Route::get('edit-progressmanagement', function () {
    return view('progress_management.edit');
});
Route::get('/accumulated_information/mail_open', [
        "uses" => "Accumulated\AccumulatedInformationController@mailOpen",
        "as" => "accumulated.mailOpen",
    ]);
/*
 * |---------------------------------------------
 * |            Custom Error Page
 * |---------------------------------------------
 */
Route::get('/400', [
        'uses' => 'ErrorHandlerController@error400',
        'as' => 'errors.400',
    ]);
Route::get('/401', [
        'uses' => 'ErrorHandlerController@error401',
        'as' => 'errors.401',
    ]);
Route::get('/404', [
        'uses' => 'ErrorHandlerController@error404',
        'as' => 'errors.404',
    ]);
Route::get('/500', [
        'uses' => 'ErrorHandlerController@error500',
        'as' => 'errors.500',
    ]);
Route::get('/503', [
        'uses' => 'ErrorHandlerController@error503',
        'as' => 'errors.503',
    ]);

Route::group([
    'middleware' => [
        'auth',
    ],
], function () {
    Route::get('/', 'Home\HomeController@index');
    Route::get('trader', function () {
        return response()->resposive('trader.index');
    })->name('trader.index');
    Route::get('/demand', function () {
        return redirect()->route('demand.get.create');
    });
    Route::get('/prefecture/{id?}', function () {
        return 'prefecture link';
    })->name('prefecture.index');
    Route::get('/user', function () {
        return response()->resposive('user.register');
    })->name('user.register');

    Route::post('demand/upload_attached_file/{demandId?}', [
        'uses' => 'DemandList\DemandListController@uploadAttachedFile',
        'as' => 'demand.upload_attached_file',
    ]);
    Route::post('demand/delete_attached_file/{id?}', [
        'uses' => 'DemandList\DemandListController@deleteAttachedFile',
        'as' => 'demand.delete_attached_file',
    ]);

    Route::get('ajax/category_list', [
        'uses' => 'Ajax\AjaxController@getCategoryListByGenreId',
        'as' => 'ajax.get_category_by_genre_id',
    ]);

    Route::get('ajax/genre_list', [
        'uses' => 'Ajax\AjaxController@getGenreListBySiteId',
        'as' => 'ajax.get_genre_list_by_site_id',
    ]);

    Route::get('ajax/inquiry_item_data', [
        'uses' => 'Ajax\AjaxController@getInquiryItemData',
        'as' => 'ajax.get_inquiry_item_data',
    ]);

    include('includes/ajax.php');

    Route::get('commission/history_input/{id?}', [
        'uses' => 'Commission\CommissionController@correspondEdit',
        'as' => 'commission.edit',
    ]);
    Route::post('commission/history_input/{id?}', [
        'uses' => 'Commission\CommissionController@correspondUpdate',
        'as' => 'commission.update',
    ]);
    Route::get('commission/detail/{id?}/{app?}', [
        'uses' => 'Commission\CommissionDetailController@index',
        'as' => 'commission.detail',
    ]);
    Route::post('commission/regist/{id?}', [
        'uses' => 'Commission\CommissionRegisterController@regist',
        'as' => 'commission.regist',
    ]);
    Route::get('commission/regist_tel_supports/{id?}/{datetime?}/{status?}/{responder?}/{failReason?}/{contents?}/{hopeDatetime?}', [
            'uses' => 'Commission\CommissionRegisterController@registTelSupports',
            'as' => 'commission.regist_tel_supports',
        ]);
    Route::get('commission/regist_visit_supports/{id?}/{datetime?}/{status?}/{responder?}/{failReason?}/{contents?}/{hopeDatetime?}', [
            'uses' => 'Commission\CommissionRegisterController@registVisitSupports',
            'as' => 'commission.regist_visit_supports',
        ]);
    Route::get('commission/regist_order_supports', [
            'uses' => 'Commission\CommissionRegisterController@registOrderSupports',
            'as' => 'commission.regist_order_supports',
        ]);
    Route::get('commission/list_supports/{id?}/{sup?}', [
            'uses' => 'Commission\CommissionRegisterController@listSupports',
            'as' => 'commission.list_supports',
        ]);
    Route::post('commission/application', [
            'uses' => 'Commission\CommissionController@application',
            'as' => 'commission.application',
        ]);
    Route::get('ajax/searchCorpTargetArea/{id}/{name}', [
            'uses' => 'Ajax\AjaxController@searchCorpTargetArea',
            'as' => 'ajax.searchCorpTargetArea',
        ]);
    Route::get('affiliation/agreement/check-auto-commission', [
            'uses' => 'Affiliation\AffiliationController@checkAutoCommission',
            'as' => 'affiliation.agreement.check-auto-commission',
        ]);
    Route::get('affiliation/agreement/{corpId}/{agreementId?}', [
            'uses' => 'Affiliation\AffiliationController@agreement',
            'as' => 'affiliation.agreement.index',
        ]);
    Route::post('affiliation/agreement-update/{corpId}/{agreementId?}', [
            'uses' => 'Affiliation\AffiliationController@agreementUpdate',
            'as' => 'affiliation.agreement.update',
        ]);
    Route::post('affiliation/agreement-update-reconfirmation/{corpId}/{agreementId?}', [
            'uses' => 'Affiliation\AffiliationController@updateReconfirmation',
            'as' => 'affiliation.agreement.update.reconfirmation',
        ]);
    Route::post('affiliation/agreement-upload-file/{corpId}/{agreementId?}', [
        'uses' => 'Affiliation\AffiliationController@agreementUploadFile',
        'as' => 'affiliation.agreement.upload.file',
    ]);

    Route::get('search_corp_target_area/{corpId?}/{address1?}', [
            'uses' => 'Affiliation\AffiliationAgreementPreviewController@searchCorpTargetArea',
            'as' => 'affiliation.agreement.searchCorpTargetArea',
        ]);
    Route::get('/affiliation/agreement_file_download/{fileId}', [
            'uses' => 'Affiliation\AffiliationController@downloadAgreementFile',
            'as' => 'affiliation.agreement.file.download',
        ]);
    Route::post('affiliation/agreement_report_download/{corpId}/{agreementId?}', [
            'uses' => 'Affiliation\AffiliationController@downloadAgreementReport',
            'as' => 'affiliation.agreement.report.download',
        ]);
    Route::get('affiliation/agreement_terms_download/{corpId}/{agreementId?}', [
            'uses' => 'Affiliation\AffiliationAgreementController@downloadAgreementTerms',
            'as' => 'affiliation.agreement.terms.download',
        ]);

    Route::get('/auction', [
            'uses' => 'Auction\AuctionController@index',
            'as' => 'auction.index',
        ]);

    Route::get('/auction/index', function () {
        return redirect()->route('auction.index');
    });

    Route::post('/auction/search', [
            'uses' => 'Auction\AuctionController@postSearch',
            'as' => 'auction.post.search',
        ]);
    Route::post('/auction/sort-for-kameiten', [
            'uses' => 'Auction\AuctionController@sortForKameiten',
            'as' => 'auction.post.sort.kameiten',
        ]);

    Route::post('/auction/delete', [
            'uses' => 'Auction\AuctionController@deleteAuction',
            'as' => 'auction.delete',
        ]);

    Route::get('/auction/proposal/{demandId}', [
            'uses' => 'Auction\AuctionController@proposal',
            'as' => 'auction.proposal',
        ])->where('demandId', '[0-9]+');

    Route::get('/auction/proposalJson/{demandId}', [
            'uses' => 'Auction\AuctionController@proposalJson',
            'as' => 'auction.proposal.json',
        ])->where('demandId', '[0-9]+');

    Route::get('/auction/refusal/{auctionId}', [
            'uses' => 'Auction\AuctionController@refusal',
            'as' => 'auction.get.refusal',
        ])->where('auctionId', '[0-9]+');

    Route::post('/auction/refusal/{auctionId}', [
            'uses' => 'Auction\AuctionController@postRefusal',
            'as' => 'auction.post.refusal',
        ])->where('auctionId', '[0-9]+');

    Route::get('/ajax/getCalenderView', [
            'uses' => 'Ajax\AjaxController@getCalenderView',
            'as' => 'ajax.get.calender.view',
        ]);

    Route::get('/ajax/exclusion_pattern/{pattern?}', [
            'uses' => 'Ajax\AjaxController@exclusionPattern',
            'as' => 'ajax.exclusion.pattern',
        ]);

    Route::get('/download/{target?}/{filename?}', [
            'uses' => 'Download\DownloadController@index',
            'as' => 'download.index',
        ]);

    Route::get('/ajax/searchMGeneralSearch', [
            'uses' => 'Ajax\AjaxController@searchMGeneralSearch',
            'as' => 'ajax.searchmgeneralsearch',
        ]);

    Route::post('/ajax/csvPreview', [
            'uses' => 'Ajax\AjaxController@csvPreview',
            'as' => 'ajax.csvpreview',
        ]);

    Route::get('auth_infos/agreement_link', [
            'uses' => 'Auth\AuthInfosController@agreementLink',
            'as' => 'auth_infos.agreement_link',
        ]);

    Route::get('report/jbr_commission', [
            'uses' => 'Report\ReportController@getJbrCommission',
            'as' => 'report.jbr_commission',
        ]);

    Route::match(['get', 'post'], 'report/jbr_ongoing', [
            'uses' => 'Report\ReportController@jbrOngoing',
            'as' => 'report.jbr_ongoing',
        ]);

    //thaihv
    Route::post('demand/regist', ['as' => 'demand.regist', 'uses' => 'Demand\DemandController@regist']);

    Route::get('corp_target_area_select/index/{corpId?}', [
            'uses' => 'CorpTargetAreaSelect\CorpTargetAreaSelectController@index',
            'as' => 'corp_target_area_select.index',
        ]);

    Route::post('corp_target_area_select/updatealldata', [
            'uses' => 'CorpTargetAreaSelect\CorpTargetAreaSelectController@updateAllData',
            'as' => 'corp_target_area_select.update_all_data',
        ]);

    Route::post('corp_target_area_select/deletealldata', [
            'uses' => 'CorpTargetAreaSelect\CorpTargetAreaSelectController@deleteAllData',
            'as' => 'corp_target_area_select.delete_all_data',
        ]);

    Route::post('corp_target_area_select/registdata', [
            'uses' => 'CorpTargetAreaSelect\CorpTargetAreaSelectController@registData',
            'as' => 'corp_target_area_select.regist_data',
        ]);

    Route::post('corp_target_area_select/registaddressdata', [
            'uses' => 'CorpTargetAreaSelect\CorpTargetAreaSelectController@registAddressData',
            'as' => 'corp_target_area_select.regist_address_data',
        ]);

    Route::get('ajax/searchTargetArea/{corpId}/{name}', [
            'uses' => 'Ajax\AjaxController@searchTargetArea',
            'as' => 'ajax.searchTargetArea',
        ]);

    Route::get('/webview_demand_list/index/{user_id}', [
            'uses' => 'Auction\AuctionController@index',
            'as' => 'webview.demand.list.index',
        ]);

    Route::get('/progress_management/update_confirm/{progImportFileId?}', [
        'uses' => 'ProgressManagement\ProgressManagementUpdateConfirmController@showUpdateConfirm',
        'as' => 'progress_management.show.update_confirm',
    ]);
    Route::post('/progress_management/update_confirm/{progImportFileId?}', [
        'uses' => 'ProgressManagement\ProgressManagementUpdateConfirmController@updateConfirm',
        'as' => 'progress_management.update_confirm',
    ]);
    Route::get('/progress_management/update_end', [
        'uses' => 'ProgressManagement\ProgressManagementUpdateConfirmController@showUpdateEnd',
        'as' => 'progress_management.show.update_end',
    ]);
    Route::get('/download/{target}/{file}', [
            'uses' => 'Download\DownloadController@index',
            'as' => 'download.index',
        ]);
    Route::get('/ajax/getNowDate', [
        'uses' => 'Ajax\AjaxController@getNowDate',
        'as' => 'ajax.get.now.view',
    ]);
});

Route::group([
    'middleware' => [
        'auth',
        'aff.roles',
    ],
], function () {
    Route::get('affiliation/corptargetarea/{id}/{initPref?}', [
        'uses' => 'Affiliation\AffiliationTargetAreaController@corpTargetArea',
        'as' => 'affiliation.corptargetarea',
    ]);
    Route::post('affiliation/corptargetarea/{id}/{initPref?}', [
        'uses' => 'Affiliation\AffiliationTargetAreaController@corpTargetArea',
        'as' => 'affiliation.corptargetarea',
    ]);

    Route::get('ajax/search-address-by-zip', [
            'uses' => 'Ajax\AjaxController@searchAddressByZip',
            'as' => 'ajax.searchAddressByZip',
        ]);

    Route::get('affiliation/targetarea/{corpCategoryId?}', [
            'uses' => 'Affiliation\AffiliationTargetAreaController@targetArea',
            'as' => 'affiliation.targetarea',
        ]);

    Route::post('affiliation/targetarea/{corpCategoryId?}', [
            'uses' => 'Affiliation\AffiliationTargetAreaController@targetAreaRegist',
            'as' => 'affiliation.targetarea',
        ]);

    Route::get('affiliation/category/{id}', [
            'uses' => 'Affiliation\AffiliationCategoryController@category',
            'as' => 'affiliation.category',
        ]);

    Route::post('affiliation/update-corp/{id}', [
            'uses' => 'Affiliation\AffiliationCategoryController@updateCorp',
            'as' => 'affiliation.updateCorp',
        ]);

    Route::post('affiliation/update-status-mcorp-category/{id}', [
            'uses' => 'Affiliation\AffiliationCategoryController@updateStatusMCorpCategory',
            'as' => 'affiliation.updateStatusMCorpCategory',
        ]);

    Route::get('affiliation/history_input/{id}', [
            'uses' => 'Affiliation\AffiliationDetailController@getAffiliationHistoryInput',
            'as' => 'affiliation.get.history.input',
        ]);

    Route::post('affiliation/history_input/{id}', [
            'uses' => 'Affiliation\AffiliationDetailController@postAffiliationHistoryInput',
            'as' => 'affiliation.post.history.input',
        ]);

    Route::post('affiliation/delete/{id}', [
        'uses' => 'Affiliation\AffiliationDetailController@deleteDetail',
        'as' => 'affiliation.detail.delete',
    ])->where('id', '[0-9]+');
});

Route::group([
    'middleware' => 'guest',
], function () {
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
    ],
], function () {
    Route::post('/demand_index', [
            'uses' => 'Demand\DemandController@index',
            'as' => 'demand.index',
        ]);
    Route::post('/demand_create', [
            'uses' => 'Demand\DemandController@index',
            'as' => 'demand.create',
        ]);
    Route::post('/demand_search', [
            'uses' => 'Demand\DemandController@index',
            'as' => 'demand.search',
        ]);
    Route::get('/report/application_admin', [
        'uses' => 'Report\ReportDevelopmentController@applicationAdmin',
        'as' => 'report.applicationAdmin',
    ]);
    Route::post('/commission/approval', [
            'uses' => 'Commission\CommissionController@approval',
            'as' => 'commission.approval',
        ]);
    /*
    * |--------------------------------------------------------------------------
    * | AutionSetting Routes
    * |--------------------------------------------------------------------------
    */
    // Route::resource('aution_settings', 'AutionSettingsController', ['except' => ['show']]);
    //Route::get('/auction_setting/flowing', [
    //        'uses' => 'Auction\AuctionSettingController@getFlowing',
    //        'as' => 'auction.setting.get.flowing',
    //    ]);
    //Route::post('/auction_setting/flowing', [
    //        'uses' => 'Auction\AuctionSettingController@postFlowing',
    //        'as' => 'auction.setting.post.flowing',
    //    ]);

    Route::get('report/corp_category_application_admin/{groupId}', [
        'uses' => 'Report\ReportAdminController@getCorpCategoryAppAdmin',
        'as' => 'report.getCorpCategoryAppAdmin',
    ]);

    Route::post('report/corp_category_application_admin/{groupId}', [
        'uses' => 'Report\ReportAdminController@postCorpCategoryAppAdmin',
        'as' => 'report.postCorpCategoryAppAdmin',
    ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'popular',
        'accounting_admin',
        'accounting',
        'affiliation',
    ],
], function () {
    Route::get('/corp_target_area_select/{mCorpId}', function () {
        return "Page corp_target_area_select";
    })->name('corp.target.area');

    Route::get('/addition/{demandId?}', [
            'uses' => 'AdditionInfos\AdditionInfosController@index',
            'as' => 'addition.index',
        ]);
    Route::post('/addition/delete', [
            'uses' => 'AdditionInfos\AdditionInfosController@delete',
            'as' => 'addition.delete',
        ]);
    Route::post('/addition', [
            'uses' => 'AdditionInfos\AdditionInfosController@regist',
            'as' => 'addition.regist',
        ]);

    Route::get('/auction/support/{id}', [
            'uses' => 'Auction\AuctionController@support',
            'as' => 'auction.support',
        ]);

    Route::post('/auction/support', [
            'uses' => 'Auction\AuctionController@updateSupport',
            'as' => 'auction.handle.support',
        ]);

    Route::post('/auction/complete', [
            'uses' => 'Auction\AuctionController@complete',
            'as' => 'auction.handle.complete',
        ]);

    Route::post('/auction/support/update_jbr_status', [
            'uses' => 'Auction\AuctionController@updateJbrStatus',
            'as' => 'auction.support.updateJbrStatus',
        ]);

    Route::get('/target_area/{cropId}', [
            'uses' => 'TargetArea\TargetAreaController@detail',
            'as' => 'target.area.detail',
        ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'popular',
        'accounting_admin',
        'accounting',
    ],
], function () {
    Route::get('affiliation/add_agreement/{corp_id?}', [
            'uses' => 'Affiliation\AffiliationController@getAddAgreement',
            'as' => 'affiliation.getAddAgreement',
        ]);
    Route::post('/add_agreement/{corp_id}', [
            'uses' => 'Agreement\AgreementController@postAddAgreement',
        ]);
    Route::post('affiliation/add_agreement/{corp_id?}', [
            'uses' => 'Affiliation\AffiliationController@postAddAgreement',
            'as' => 'affiliation.postAddAgreement',
        ]);
    Route::get('report/antisocial_follow', [
            'uses' => 'Report\ReportController@antisocialFollow',
            'as' => 'report.antisocial_follow',
        ]);
    Route::post('report/antisocial_follow', [
        'uses' => 'Report\ReportController@antisocialFollowUpdate',
        'as' => 'report.antisocial_follow',
    ]);
    Route::get('affiliation/agreement_preview/{corpId?}/{corpAgreementId?}', [
        'uses' => 'Affiliation\AffiliationAgreementPreviewController@getAgreementPreview',
        'as' => 'affiliation.agreement.preview',
    ]);

    Route::get('general_search/{mGeneralId?}', [
            'uses' => 'GeneralSearch\GeneralSearchController@index',
            'as' => 'general_search.index',
        ]);

    Route::get('general_search/index/{mGeneralId?}', function ($generalId = null) {
        return redirect()->route('general_search.index', ['mGeneralId' => $generalId]);
    });

    Route::post('general_search/regist', [
            'uses' => 'GeneralSearch\GeneralSearchController@regist',
            'as' => 'general_search.regist',
        ]);

    Route::post('general_search/delete', [
            'uses' => 'GeneralSearch\GeneralSearchController@delete',
            'as' => 'general_search.delete',
        ]);

    Route::post('general_search/csv', [
            'uses' => 'GeneralSearch\GeneralSearchController@getCsv',
            'as' => 'general_search.csv',
        ]);

    Route::get('/affiliation/{phoneNumber?}/{corpStatus?}', [
            'uses' => 'Affiliation\AffiliationController@index',
            'as' => 'affiliation.index',
        ])->where(['phoneNumber' => '[0-9]+', 'corpStatus' => '[0-9]+']);

    Route::get('/affiliation/index/{phoneNumber?}/{corpStatus?}', function ($phoneNumber = null, $corpStatus = null) {
        return redirect()->route('affiliation.index', ['phoneNumber' => $phoneNumber, 'corpStatus' => $corpStatus]);
    });

    Route::post('/affiliation/search', [
            'uses' => 'Affiliation\AffiliationController@search',
            'as' => 'affiliation.search',
        ]);

    Route::get('/affiliation/setSession', [
       'uses' => 'Affiliation\AffiliationDetailController@setSessionForBackAffiliationSearch',
       'as' => 'affiliation.set.session'
    ]);

    Route::post('/affiliation/download', [
            'uses' => 'Affiliation\AffiliationController@downloadCSVAffiliation',
            'as' => 'affiliation.download',
        ]);

    Route::match(['get', 'post'], '/report/jbr_receipt_follow', [
            'uses' => 'Report\ReportController@getListReceiptFollow',
            'as' => 'report.getListReceiptFollow',
        ]);
    Route::post('/report/jbr_receipt_follow/getcsv', [
            'uses' => 'Report\ReportController@getCsvListReceiptFollow',
            'as' => 'report.getCsvListReceiptFollow',
        ]);

    Route::get('/report/corp_commission', [
        'uses' => 'Report\ReportDevelopmentController@indexCorpCommission',
        'as' => 'report.get.corp.commission',
    ]);

    Route::get('/report/search_corp_commission', [
        'uses' => 'Report\ReportDevelopmentController@searchCorpCommission',
        'as' => 'report.search.corp.commission',
    ]);
    Route::post('/report/corp_commission/delete_session', [
        'uses' => 'Report\ReportDevelopmentController@deleteSesssionSearch',
        'as' => 'delete.session.corp.commission',
    ]);
    Route::post('/report/corp_commission/register', [
        'uses' => 'Report\ReportDevelopmentController@registerCorpCommission',
        'as' => 'report.register.corp.commission',
    ]);
    Route::post('/report/corp_commission/delete', [
        'uses' => 'Report\ReportDevelopmentController@deleteCorpCommission',
        'as' => 'report.delete.corp.commission',
    ]);

    Route::get('/report/corp_selection', [
        'uses' => 'Report\ReportDevelopmentController@indexCorpSelection',
        'as' => 'report.get.corp.selection',
    ]);

    Route::get('/report/real_time_report', [
        'uses' => 'Report\ReportDevelopmentController@realTimeReport',
        'as' => 'report.get.real.time.report',
    ]);

    Route::get('/report/corp_category_group_application_admin', [
        'uses' => 'Report\ReportCategoryController@corpCategoryGroupApplicationAdmin',
        'as' => 'report.get.corp.category.group.application.admin',
    ]);

    Route::get('/report/corp_category_group_application_answer', [
        'uses' => 'Report\ReportCategoryController@corpCategoryGroupApplicationAnswer',
        'as' => 'report.get.corp.category.group.application.answer',
    ]);

    Route::get('/report/corp_category_group_application_answer_search', [
        'uses' => 'Report\ReportCategoryController@searchCorpCategoryGroupApplicationAnswer',
        'as' => 'report.search.corp.category.group.application.answer',
    ]);

    Route::post('/report/export_corp_category_group_application_answer', [
        'uses' => 'Report\ReportCategoryController@exportCorpCategoryGroupApplicationAnswer',
        'as' => 'export.csv.corp.category.group.application.answer',
    ]);

    Route::get('/report/corp_category_application_answer/{id}', [
            'uses' => 'Report\ReportAdminController@getCorpCategoryAppAnswer',
            'as' => 'report.get.corp.category.application.answer',
        ]);

    Route::get('/report/sales_support', [
        'uses' => 'Report\ReportCategoryController@salesSupport',
        'as' => 'report.sales.support',
    ]);
    Route::post('/report/update_sales_support', [
        'uses' => 'Report\ReportCategoryController@updateSalesSupport',
        'as' => 'report.update.sales.support',
    ]);

    Route::get('/demand_list/{id?}/{bCheck?}', [
            'uses' => 'DemandList\DemandListController@index',
            'as' => 'demandlist.search',
        ]);

    Route::post('demand_list', [
            'uses' => 'DemandList\DemandListController@searchDemand',
            'as' => 'demandlist.post.search',
        ]);
    Route::get('demand/history_input/{id?}', [
            'uses' => 'Demand\DemandController@historyInput',
            'as' => 'demand.history_input',
        ]);
    Route::post('demand/history_input', [
            'uses' => 'Demand\DemandController@update',
            'as' => 'demand.update',
        ]);

    Route::get('commission_print/{demand_id}', [
            'uses' => 'Commission\CommissionPrintController@detail',
            'as' => 'commission.print.detail',
        ]);
    Route::get('commission_print/print_commission/{commission_id}', [
            'uses' => 'Commission\CommissionPrintController@exportWord',
            'as' => 'commission.print.exportWord',
        ]);
    /* Route::get('/auction_setting/follow', [
            'uses' => 'Auction\AuctionSettingController@follow',
            'as' => 'auction.setting.follow',
        ]);
    Route::post('/auction_setting/follow', [
            'uses' => 'Auction\AuctionSettingController@ajaxFollow',
            'as' => 'ajax.auction.setting.follow',
        ]); */

    /*
    * --------------------------------------------------------
    * |        Thaihv: progress management
    * |                START
    * --------------------------------------------------------
    */
    Route::get('/progress_management/admin_demand_detail/{pCorpId}', [
            'uses' => 'ProgressManagement\ProgressManagementController@adminDemandDetail',
            'as' => 'get.progress.management.admin_demand_detail',
        ]);

    Route::put('/progress_management/progress_demand_info/{pDMandInfoId}', [
            'uses' => 'ProgressManagement\ProgressManagementController@updateProgDemandInfo',
            'as' => 'put.progress.management.updateProgDemandInfo',
        ]);

    Route::post('/progress_management/admin_demand_detail/{pCorpId}', [
            'uses' => 'ProgressManagement\ProgressManagementController@progAddDemandInfoDetail',
            'as' => 'post.progress.management.admin_demand_detail',
        ]);

    Route::put('/progress_management/reacquisition/{pDMandInfoId}', [
            'uses' => 'ProgressManagement\ProgressManagementController@reacquisition',
            'as' => 'put.progress.management.reacquisition',
        ]);

    Route::post('/progress_management/progress_demand_info/pDemand/multipleUpdate', [
            'uses' => 'ProgressManagement\ProgressManagementController@multipleUpdate',
            'as' => 'put.progress.management.multipleUpdate',
        ]);

    Route::match(['GET', 'POST'], '/progress_management/corp_index/{fileId?}', [
            'uses' => 'ProgressManagement\ProgressManagementController@corpIndex',
            'as' => 'progress.corpIndex',
        ]);

    Route::get('/progress_management', [
            'uses' => 'ProgressManagement\ProgressManagementController@index',
            'as' => 'progress.management.index',
        ]);

    Route::get('/progress_management/index', function () {
        return redirect()->route('progress.management.index');
    });

    Route::put('/progress_management/corp_index/{fileId}/prog_corp/{pCorpId}', [
            'uses' => 'ProgressManagement\ProgressManagementController@updateProgressCorp',
            'as' => 'progress.corpIndex.update.pcorp',
        ]);

    Route::post('/progress_management/corp_index/prog_corp/{pCorpId}/mail/{fileId}', [
        'uses' => 'ProgressManagement\ProgressManagementHandleFileController@sendEmail',
        'as' => 'progress.corpIndex.progcorp.email',
    ]);

    Route::post('/progress_management/corp_index/prog_corp/{pCorpId}/fax', [
        'uses' => 'ProgressManagement\ProgressManagementHandleFileController@sendFax',
        'as' => 'progress.corpIndex.progcorp.fax',
    ]);

    Route::post('/progress_management/corp_index/prog_corp/{pCorpId}/fax-mail/{fileId}', [
        'uses' => 'ProgressManagement\ProgressManagementHandleFileController@sendMailFax',
        'as' => 'progress.corpIndex.progcorp.fax-mail',
    ]);

    Route::post('/progress_management/corp_index/send-notify/multi-mail/{fileId}', [
        'uses' => 'ProgressManagement\ProgressManagementHandleFileController@sendMultipleEmail',
        'as' => 'progress.corpIndex.progcorp.multi-mail',
    ]);
    Route::post('/progress_management/corp_index/send-notify/multi-fax', [
        'uses' => 'ProgressManagement\ProgressManagementHandleFileController@sendMultipleFax',
        'as' => 'progress.corpIndex.progcorp.multi-fax',
    ]);
    Route::post('/progress_management/corp_index/send-notify/multi-mail-fax/{fileId}', [
        'uses' => 'ProgressManagement\ProgressManagementHandleFileController@sendMultipleMailFax',
        'as' => 'progress.corpIndex.progcorp.multi-mail-fax',
    ]);
    Route::get('/progress_management/output_corp_csv/{pCorpId}', [
        'uses' => 'ProgressManagement\ProgressManagementHandleFileController@outputCSV',
        'as' => 'progress.corpIndex.progcorp.outcsv',
    ]);
    Route::get('/progress_management/output_file_csv/{pCorpId}', [
        'uses' => 'ProgressManagement\ProgressManagementHandleFileController@outputFileCSV',
        'as' => 'progress.corpIndex.progcorp.outcsv.file',
    ]);
    Route::get('/progress_management/output_corp_pdf/{pCorpId}', [
        'uses' => 'ProgressManagement\ProgressManagementHandleFileController@outputPDF',
        'as' => 'progress.corpIndex.progcorp.outpdf',
    ]);

    Route::post('/progress_management/delete-file/{fileId}', [
            'uses' => 'ProgressManagement\ProgressManagementController@fileDelete',
            'as' => 'progress.file.delete',
        ]);
    /*
    * --------------------------------------------------------
    * |        Thaihv: progress management
    * |                THE END
    * --------------------------------------------------------
    */
    Route::get('/report', [
            'uses' => 'Report\ReportController@index',
            'as' => 'report.index',
        ]);

    Route::get('/report/index', function () {
        return redirect()->route('report.index');
    });

    Route::get('report/corp_agreement_category', [
            'uses' => 'Report\ReportController@corpAgreementCategory',
            'as' => 'report.corp.agreement.category',
        ]);

    Route::get('report/corp_agreement_category_ajax', [
            'uses' => 'Report\ReportController@corpAgreementCategoryAjax',
            'as' => 'report.corp.agreement.category.ajax',
        ]);

    Route::post('report/corp_agreement_category', [
            'uses' => 'Report\ReportController@exportCsvCorpAgreementCategory',
            'as' => 'report.export.csv.corp.agreement.category',
        ]);
    Route::get('/report/auction_fall', [
            'uses' => 'Report\ReportController@auctionFall',
            'as' => 'report.auctionfall',
        ]);

    Route::get('/report/auction_fall_table', [
            'uses' => 'Report\ReportController@auctionFallTable',
            'as' => 'report.auctionfalltable',
        ]);

    Route::get('report/application_answer', [
        'uses' => 'Report\ReportDevelopmentController@applicationAnswer',
        'as' => 'report.application_answer',
    ]);

    Route::get('report/application_answer_ajax', [
        'uses' => 'Report\ReportDevelopmentController@applicationAnswerAjax',
        'as' => 'report.application_answer_ajax',
    ]);

    Route::post('report/application_answer', [
        'uses' => 'Report\ReportDevelopmentController@applicationAnswerCsv',
        'as' => 'report.application_answer',
    ]);

    Route::get('report/development', [
        'uses' => 'Report\ReportDevelopmentController@development',
        'as' => 'report.development',
    ]);


    Route::get('report/development_search/', [
        'uses' => 'Report\ReportDevelopmentController@getDevelopmentSearch',
        'as' => 'report.development.search',
    ]);

    Route::get('report/development_search/data', [
        'uses' => 'Report\ReportDevelopmentController@getDevelopmentSearchData',
        'as' => 'report.development.data',
    ]);

    Route::get('report/development_search/{status}/{address}', [
        'uses' => 'Report\ReportDevelopmentController@getDevelopmentSearchByParams',
        'as' => 'report.development.search.params',
    ]);

    Route::post('report/development_search', [
        'uses' => 'Report\ReportDevelopmentController@postDevelopmentSearch',
        'as' => 'report.development.search',
    ]);

    Route::get('/demand/auction_detail/{demandId?}', [
            'uses' => 'Demand\DemandController@auctionDetail',
            'as' => 'demand.auction_detail',
        ]);

    Route::match(['get', 'post'], 'report/jbr_ongoing', [
            'uses' => 'Report\ReportController@jbrOngoing',
            'as' => 'report.jbr_ongoing',
        ]);

    Route::get('/demand/cti/{customerTel?}/{siteTel?}', [
        'uses' => 'Demand\DemandController@cti',
        'as' => 'demand.cti',
    ]);

    Route::get('/demand/detail/{id}', [
            'uses' => 'DemandList\DemandListController@getDetail',
            'as' => 'demand.detail',
        ]);

    Route::post('/demand/delete/{id}', [
            'uses' => 'Demand\DemandController@delete',
            'as' => 'demand.delete',
        ]);

    Route::get('/demand/cross/{id?}', [
            'uses' => 'Demand\DemandController@cross',
            'as' => 'demand.detail.cross',
        ]);

    Route::get('demand/detail', [
            'uses' => 'Demand\DemandController@create',
            'as' => 'demand.get.create',
        ]);

    Route::get('demand/copy/{id}', [
            'uses' => 'Demand\DemandController@copy',
            'as' => 'demand.detail.copy',
        ]);

    Route::post('demand/update', [
            'uses' => 'Demand\DemandController@regist',
            'as' => 'demand.register',
        ]);

    Route::get('/report/reputation_follow', [
            'uses' => 'Report\ReportReputationFollowController@reportReputationFollow',
            'as' => 'report.reputation.follow',
        ]);

    Route::post('/report/reputation_follow', [
            'uses' => 'Report\ReportReputationFollowController@reportReputationFollowPaging',
            'as' => 'report.reputation.follow.search',
        ]);

    Route::get('/report/reputation_follow/download', [
            'uses' => 'Report\ReportReputationFollowController@downloadReputationFollow',
            'as' => 'report.reputation.follow.download',
        ]);

    Route::post('/report/reputation_follow/update', [
            'uses' => 'Report\ReportReputationFollowController@updateReputationFollow',
            'as' => 'report.reputation.follow.update',
        ]);

    Route::get('demand/demand_file_download/{id}', [
            'uses' => 'Demand\DemandController@demandFileDownload',
            'as' => 'demand.file.download',
        ]);

    Route::get('affiliation/detail', [
        'uses' => 'Affiliation\AffiliationDetailController@create',
        'as' => 'affiliation.detail.create',
    ]);

    Route::post('affiliation/detail', [
        'uses' => 'Affiliation\AffiliationDetailController@postDetail',
        'as' => 'affiliation.detail.post',
    ]);

    Route::get('affiliation/detail/{id}', [
        'uses' => 'Affiliation\AffiliationDetailController@detail',
        'as' => 'affiliation.detail.edit',
    ])->where('id', '[0-9]+');

    Route::post('affiliation/detail/{id}', [
        'uses' => 'Affiliation\AffiliationDetailController@updateDetail',
        'as' => 'affiliation.detail.update',
    ])->where('id', '[0-9]+');
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'accounting_admin',
        'popular',
        'accounting',
    ],
], function () {
    Route::any('/commission_select/index', [
            'uses' => 'CommissionSelect\CommissionSelectController@index',
            'as' => 'commissionselect.index',
        ]);
    Route::any('/commission_select/display', [
        'uses' => 'CommissionSelect\CommissionSelectController@display',
        'as' => 'commissionselect.display',
    ]);

    Route::post('/commission_select/check_credit', [
        'uses' => 'CommissionSelect\CommissionSelectController@checkCredit',
        'as' => 'commissionselect.check_credit',
    ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'accounting_admin',
    ],
], function () {

    Route::get('/admin', [
        'uses' => 'Admin\AdminController@index',
        'as' => 'admin.index',
    ]);

    Route::get('/admin/index', function () {
        return redirect()->route('admin.index');
    });

    Route::get('/auto_call', [
        'uses' => 'AutoCall\AutoCallController@index',
        'as' => 'autocall.index',
    ]);

    Route::post('/auto_call', [
        'uses' => 'AutoCall\AutoCallController@update',
        'as' => 'autocall.update',
    ]);

    Route::get('/auto_call/index', function () {
        return redirect()->route('autocall.index');
    });

    Route::get('/progress_management/delete_commission_infos/{fileId}', [
        'uses' => 'ProgressManagement\ProgressManagementCommissionController@getDeleteCommissionInfos',
        'as' => 'get.progress.management.delete.commission.infos',
    ])->where('fileId', '[0-9]+');

    Route::post('/progress_management/delete_commission_infos/{fileId}', [
        'uses' => 'ProgressManagement\ProgressManagementCommissionController@postDeleteCommissionInfos',
        'as' => 'post.progress.management.delete.commission.infos',
    ])->where('fileId', '[0-9]+');

    Route::get('/genre', [
        'uses' => 'Genre\GenreController@index',
        'as' => 'genre.index',
    ]);

    Route::post('/genre', [
        'uses' => 'Genre\GenreController@regist',
        'as' => 'genre.regist',
    ]);

    Route::get('/genre/index', function () {
        return redirect()->route('genre.index');
    });

    /*
     * |--------------------------------------------------------------------------
     * | AutionSetting Routes
     * |--------------------------------------------------------------------------
     */
    Route::get('/auction_setting', [
        'uses' => 'Auction\AuctionSettingController@index',
        'as' => 'auction_setting.index',
    ]);

    Route::get('/auction_setting/index', function () {
        return redirect()->route('auction_setting.index');
    });

    Route::get('/auction_setting/genre_detail/{genreId?}', [
        'uses' => 'Auction\AuctionSettingController@genreDetail',
        'as' => 'auction.setting.genre.detail',
    ]);

    Route::post('/auction_setting/genre_detail', [
        'uses' => 'Auction\AuctionSettingController@genreDetailRegist',
        'as' => 'auction.setting.genre.detail',
    ]);

    Route::get('/auction_setting/prefecture/{genreId?}', [
        'uses' => 'Auction\AuctionSettingController@getPrefecture',
        'as' => 'auction_setting.prefecture',
    ]);

    Route::get('/auction_setting/prefecture_detail/{genreId?}/{prefCd?}', [
        'uses' => 'Auction\AuctionSettingController@getPrefectureDetail',
        'as' => 'auction_setting.prefecture.detail',
    ]);

    Route::post('/auction_setting/prefecture_detail/{genreId?}/{prefCd?}', [
        'uses' => 'Auction\AuctionSettingController@postPrefectureDetail',
        'as' => 'auction_setting.prefecture.detail',
    ]);

    Route::get('auction_setting/ranking', [
        'uses' => 'Auction\AuctionSettingController@ranking',
        'as' => 'auction_setting.ranking',
    ]);

    Route::get('auction_setting/ranking/export_csv', [
        'uses' => 'Auction\AuctionSettingController@exportCSVRanking',
        'as' => 'auction_setting.ranking.csv',
    ]);

    Route::post('auction_setting/ranking', [
        'uses' => 'Auction\AuctionSettingController@ranking',
        'as' => 'auction_setting.ranking',
    ]);

    /*
     * |--------------------------------------------------------------------------
     * | AutionSetting Routes
     * |--------------------------------------------------------------------------
     */
    // Route::resource('aution_settings', 'AutionSettingsController', ['except' => ['show']]);
    Route::get('/auction_setting/flowing', [
        'uses' => 'Auction\AuctionSettingController@getFlowing',
        'as' => 'auction.setting.get.flowing',
    ]);

    Route::get('/progress_management/import_commission_infos/{fileId?}', [
        'uses' => 'ProgressManagement\ProgressManagementCommissionController@getImportCommissionInfos',
        'as' => 'get.import.commission.infos',
    ]);

    Route::post('/progress_management/import_commission_infos/{fileId?}', [
        'uses' => 'ProgressManagement\ProgressManagementCommissionController@postImportCommissionInfos',
        'as' => 'post.import.commission.infos',
    ]);

    Route::get('/daily_list', [
        'uses' => 'DailyList\DailyListController@index',
        'as' => 'daily_list.index',
    ]);

    Route::get('/daily_list/download_file', [
        'uses' => 'DailyList\DailyListController@downloadFile',
        'as' => 'dailylist.downloadfile',
    ]);

    Route::get('/daily_list/index', function () {
        return redirect()->route('daily_list.index');
    });

    Route::get('user', [
        'uses' => 'User\UserController@index',
        'as' => 'user.index',
    ]);

    Route::get('/user/back', function () {
        Session::put('isBack', true);
        return redirect()->route('user.index');
    });

    Route::get('/user/index', function () {
        return redirect()->route('user.index');
    });

    Route::get('user/search', [
        'uses' => 'User\UserController@search',
        'as' => 'user.search',
    ]);

    Route::post('user/search', [
        'uses' => 'User\UserController@search',
        'as' => 'user.search',
    ]);

    Route::get('/user/detail', [
        'uses' => 'User\UserController@getCreate',
        'as' => 'user.create',
    ]);

    Route::post('/user/detail', [
        'uses' => 'User\UserController@create',
        'as' => 'user.create',
    ]);

    Route::get('user/detail/{id}', [
        'uses' => 'User\UserController@edit',
        'as' => 'user.edit',
    ]);

    Route::post('user/detail/{id}', [
        'uses' => 'User\UserController@updateUser',
        'as' => 'user.edit',
    ]);

    Route::get('/selection', [
        'uses' => 'Selection\SelectionController@index',
        'as' => 'selection.index',
    ]);

    Route::post('/selection', [
        'uses' => 'Selection\SelectionController@post',
        'as' => 'selection.post',
    ]);

    Route::get('/selection/prefecture/{id}', [
        'uses' => 'Selection\SelectionController@prefecture',
        'as' => 'selection.prefecture',
    ]);

    Route::post('/selection/prefecture/{id}', [
        'uses' => 'Selection\SelectionController@prefecturePost',
        'as' => 'selection.prefecture.post',
    ]);

    Route::get('/target_demand_flag', [
        'uses' => 'TargetDemandFlag\TargetDemandFlagController@index',
        'as' => 'get.target.demand.flag',
    ]);

    Route::post('/target_demand_flag', [
        'uses' => 'TargetDemandFlag\TargetDemandFlagController@postTargetDemandFlag',
        'as' => 'post.target.demand.flag',
    ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'accounting',
        'system',
        'accounting_admin',
    ],
], function () {
    Route::get('/bill', [
            'uses' => 'Bill\BillController@index',
            'as' => 'bill.index',
        ]);

    Route::get('/bill/index', function () {
        return redirect()->route('bill.index');
    });

    Route::get('bill/bill_list/{id}', [
        'uses' => 'Bill\BillController@getBillList',
        'as' => 'bill.getBillList',
    ]);
    Route::post('bill/bill_search/{id?}', [
        'uses' => 'Bill\BillController@postBillSearch',
        'as' => 'bill.postBillSearch',
    ]);
    Route::post('bill/bill_save/{id?}', [
        'uses' => 'Bill\BillController@postBillSave',
        'as' => 'bill.postBillSave',
    ]);
    Route::post('bill/bill_download/{id?}', [
        'uses' => 'Bill\BillController@postBillDownload',
        'as' => 'bill.postBillDownload',
    ]);

    Route::get('/bill/mcorp_list', [
        'uses' => 'Bill\BillController@mCorpList',
        'as' => 'bill.mCorpList',
    ]);

    Route::post('/bill/mcorp_search', [
        'uses' => 'Bill\BillController@mCorpSearch',
        'as' => 'bill.mCorpSearch',
    ]);

    Route::get('bill/money_correspond/{corpId?}', [
            'uses' => 'Bill\BillController@moneyCorrespond',
            'as' => 'bill.moneyCorrespond',
        ]);

    Route::post('bill/money_correspond/order', [
            'uses' => 'Bill\BillController@orderMoneyCorrespond',
            'as' => 'bill.orderMoneyCorrespond',
        ]);

    Route::post('bill/money_correspond_add_deposit', [
            'uses' => 'Bill\BillController@moneyAddDeposit',
            'as' => 'bill.moneyAddDeposit',
        ]);

    Route::post('bill/money_correspond_remove_deposit', [
            'uses' => 'Bill\BillController@removeDeposit',
            'as' => 'bill.removeMoneyDeposit',
        ]);

    Route::get('bill/bill_output', [
            'uses' => 'Bill\BillController@output',
            'as' => 'bill.bill_output',
        ]);

    Route::post('bill/bill_output', [
            'uses' => 'Bill\BillController@downloadBillCsv',
            'as' => 'bill.download_csv',
        ]);

    Route::get('bill/bill_detail/{id}', [
            'uses' => 'Bill\BillController@billDetail',
            'as' => 'bill.bill_detail',
        ]);

    Route::post('bill/bill_detail/{id}', [
            'uses' => 'Bill\BillController@billDetailUpdate',
            'as' => 'bill.bill_detail.update',
        ]);

    Route::post('bill/save_session_bill/', [
        'uses' => 'Bill\BillController@saveSessionBill',
        'as' => 'bill.save.session',
    ]);

    Route::post('bill/check_session_bill/', [
        'uses' => 'Bill\BillController@checkSessionSearch',
        'as' => 'bill.check.session',
    ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
    ],
], function () {
    Route::get('/auto_commission_corp/add', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@getAdd',
            'as' => 'autoCommissionCorp.getAdd',
        ]);
    Route::post('/auto_commission_corp/add', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@postAdd',
            'as' => 'autoCommissionCorp.postAdd',
        ]);
    Route::post('/auto_commission_corp/get_corp_list', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@getCorpList',
            'as' => 'autoCommissionCorp.getCorpList',
        ]);
    Route::post('/auto_commission_corp/get_genre_list_by_corp_id', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@getGenreList',
            'as' => 'autoCommissionCorp.getGenreList',
        ]);
    Route::post('/auto_commission_corp/get_category_list_by-genre_id_corp_id', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@getCategoryList',
            'as' => 'autoCommissionCorp.getCategoryList',
        ]);
    Route::get('/auto_commission_corp', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@index',
            'as' => 'autoCommissionCorp.index',
        ]);

    Route::get('/auto_commission_corp/index', function () {
        return redirect()->route('autoCommissionCorp.index');
    });

    Route::get('/auto_commission_corp/corp_add', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@getCorpAdd',
            'as' => 'autoCommissionCorp.getCorpAdd',
        ]);
    Route::post('/auto_commission_corp/corp_add', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@postCorpAdd',
            'as' => 'autoCommissionCorp.postCorpAdd',
        ]);
    Route::post('/auto_commission_corp/get_corp_add_list', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@getCorpAddList',
            'as' => 'autoCommissionCorp.getCorpAddList',
        ]);
    Route::get('/auto_commission_corp/ajax_search_auto_commission_corp', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@searchAutoCommissionCorp',
            'as' => 'ajax.search.auto.commission.corp',
        ]);
    Route::get('/auto_commission_corp/auto_commission_corp_all', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@searchAutoCommissionCorpAll',
            'as' => 'auto.commission.corp.all',
        ]);

    Route::get('/auto_commission_corp/corp_select', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@getCorpSelect',
            'as' => 'autoCommissionCorp.cropSelect',
        ]);
    Route::post('/auto_commission_corp/corp_select', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@editCorpSelect',
            'as' => 'autoCommissionCorp.cropSelectRegister',
        ]);
    Route::post('/auto_commission_corp/get_category_list_by_genre_id', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@getCategoryByGenreId',
            'as' => 'autoCommissionCorp.getCategoryByGenreId',
        ]);
    Route::post('/auto_commission_corp/get_list_corp_by_genre_cate_pref', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@getListCorpByCategoryAndPref',
            'as' => 'autoCommissionCorp.getListCorpByGenreCatePref',
        ]);
    Route::post('/auto_commission_corp/search_corp', [
            'uses' => 'AutoCommissionCorp\AutoCommissionCorpController@searchCorpList',
            'as' => 'autoCommissionCorp.searchCorpByCatePref',
        ]);
});

Route::group([
    'prefix' => 'agreement-system',
    'middleware' => [
        'auth',
        'roles',
        'corp.info',
    ],
    'roles' => [
        'affiliation',
    ],
], function () {
    Route::get('/step0', [
            'uses' => 'Agreement\AgreementSystemController@getStep0',
            'as' => 'agreementSystem.getStep0',
        ]);

    Route::get('/step0/back', [
            'uses' => 'Agreement\AgreementSystemController@getStep0',
            'as' => 'agreementSystem.back.getStep0',
        ]);

    Route::post('/step0', [
            'uses' => 'Agreement\AgreementSystemController@postStep0Proceed',
            'as' => 'agreementSystem.postStep0Proceed',
        ]);
    Route::get('/step0/stepForward', [
            'uses' => 'Agreement\AgreementSystemController@getStep1',
            'as' => 'agreementSystem.getStep1',
        ]);
    Route::post('/step1', [
            'uses' => 'Agreement\AgreementSystemController@postStep1Proceed',
            'as' => 'agreementSystem.postStep1Proceed',
        ]);

    Route::get('/step1/stepForward', [
            'uses' => 'Agreement\AgreementSystemController@getStep2',
            'as' => 'agreementSystem.getStep2',
        ]);

    Route::get('/step2/stepBack', [
            'uses' => 'Agreement\AgreementSystemController@getStep1',
            'as' => 'agreementSystem.back.getStep2',
        ]);

    Route::post('/step2', [
            'uses' => 'Agreement\AgreementSystemController@postStep2',
            'as' => 'agreementSystem.postStep2',
        ]);

    Route::get('/step2/stepForward', [
            'uses' => 'Agreement\AgreementSystemController@getStep3',
            'as' => 'agreementSystem.getStep3',
        ]);

    Route::get('/step3/stepBack', [
            'uses' => 'Agreement\AgreementSystemController@getStep2',
            'as' => 'agreementSystem.back.getStep3',
        ]);

    Route::post('/step3', [
            'uses' => 'Agreement\AgreementSystemController@postStep3',
            'as' => 'agreementSystem.postStep3',
        ]);

    Route::get('/step3/stepForward', [
            'uses' => 'Agreement\AgreementSystemController@getStep4',
            'as' => 'agreementSystem.getStep4',
        ]);

    Route::post('/step4', [
            'uses' => 'Agreement\AgreementSystemController@postStep4',
            'as' => 'agreementSystem.postStep4',
        ]);

    Route::get('/step4/stepBack', [
            'uses' => 'Agreement\AgreementSystemController@getStep3',
            'as' => 'agreementSystem.back.getStep4',
        ]);

    Route::get('/step4/stepForward', [
            'uses' => 'Agreement\AgreementSystemController@getStep5',
            'as' => 'agreementSystem.getStep5',
        ]);

    Route::get('/step5/stepBack', [
            'uses' => 'Agreement\AgreementSystemController@getStep4',
            'as' => 'agreementSystem.back.getStep5',
        ]);

    Route::post('/step5/fileUpload', [
            'uses' => 'Agreement\AgreementSystemController@uploadFile',
            'as' => 'agreementSystem.step5.fileUpload',
        ]);

    Route::post('/step5/fileDelete', [
            'uses' => 'Agreement\AgreementSystemController@deleteFile',
            'as' => 'agreementSystem.step5.fileDelete',
        ]);

    Route::get('/step5/fileUpload', [
            'uses' => 'Agreement\AgreementSystemController@getStep5',
            'as' => 'agreementSystem.step5.get.fileUpload',
        ]);

    Route::get('/step5/fileDelete', [
            'uses' => 'Agreement\AgreementSystemController@getStep5',
            'as' => 'agreementSystem.step5.get.fileDelete',
        ]);

    Route::get('/image/thumbnail2/', [
            'uses' => 'Agreement\AgreementSystemController@getThumbnail2',
            'as' => 'agreementSystem.get.thumbnail2',
        ]);

    Route::get('/image/showImage/', [
        'uses' => 'Agreement\AgreementSystemController@getAttachFile',
        'as' => 'agreementSystem.get.attachfile',
    ]);

    Route::post('/step5', [
            'uses' => 'Agreement\AgreementSystemController@postStep5',
            'as' => 'agreementSystem.postStep5',
        ]);

    Route::get('/step5/stepForward', [
            'uses' => 'Agreement\AgreementSystemController@getConfirm',
            'as' => 'agreementSystem.confirm',
        ]);

    Route::get('/category-dialog', [
            'uses' => 'Agreement\AgreementSystemController@getListCategoryDialog',
            'as' => 'get.category_dialog',
        ]);

    Route::post('/category-dialog', [
            'uses' => 'Agreement\AgreementSystemController@postListCategoryDialog',
            'as' => 'post.category_dialog',
        ]);
    Route::get('/area-dialog', [
            'uses' => 'Agreement\AgreementSystemController@getListAreaDialog',
            'as' => 'get.area_dialog',
        ]);

    Route::post('/area-dialog', [
            'uses' => 'Agreement\AgreementSystemController@postAreaDialog',
            'as' => 'post.area_dialog',
        ]);

    Route::post('/view-area-dialog', [
            'uses' => 'Agreement\AgreementSystemController@postViewAreaDialog',
            'as' => 'view_area_dialog',
        ]);

    Route::post('/post-dialog', [
            'uses' => 'Agreement\AgreementSystemController@postPostDialog',
            'as' => 'post.post_dialog',
        ]);

    Route::get('/confirm/stepBack', [
            'uses' => 'Agreement\AgreementSystemController@getStep5',
            'as' => 'agreementSystem.back.getConfirm',
        ]);

    Route::post('/confirm', [
            'uses' => 'Agreement\AgreementSystemController@postConfirm',
            'as' => 'agreementSystem.postConfirm',
        ]);

    Route::get('/complete/in', [
            'uses' => 'Agreement\AgreementSystemController@getComplete',
            'as' => 'agreementSystem.getComplete',
        ]);
});

Route::group([
    'prefix' => 'agreement-admin',
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
    ],
], function () {
    // routes for agreement-admin/dashboard
    Route::get('/dashboard', [
        'uses' => 'Agreement\AgreementAdminController@index',
        'as' => 'agreement.dashboard',
    ]);
    Route::get('/dashboard/data-processing', [
            'uses' => 'Agreement\AgreementAdminController@dataProcessing',
            'as' => 'dashboard.dataProcessing',
        ]);
    Route::get('/dashboard/export-excel', [
            'uses' => 'Agreement\AgreementAdminController@exportExcel',
            'as' => 'dashboard.exportExcel',
        ]);
    Route::get('/dashboard/export-csv', [
            'uses' => 'Agreement\AgreementAdminController@exportCsv',
            'as' => 'dashboard.exportCsv',
        ]);

    // routes for agreement-admin/agreement-provisions
    Route::get('/agreement-provisions', [
            'uses' => 'Agreement\AgreementProvisionsController@getAgreementProvisions',
            'as' => 'agreement.provisions',
        ]);
    Route::get('/agreement-provisions/data', [
            'uses' => 'Agreement\AgreementProvisionsController@getViewData',
            'as' => 'agreement.provisions.data',
        ]);
    Route::get('/agreement-provisions/provision-data', [
            'uses' => 'Agreement\AgreementProvisionsController@getAgreementProvisionData',
            'as' => 'agreement.provisions.provision-data',
        ]);
    Route::get('/agreement-provisions/version-up', [
            'uses' => 'Agreement\AgreementProvisionsController@versionUp',
            'as' => 'agreement.provisions.versionUp',
        ]);
    Route::post('/agreement-provisions', [
            'uses' => 'Agreement\AgreementProvisionsController@postAgreementProvision',
            'as' => 'post.agreement.provision',
        ]);
    Route::post('/agreement-provisions/item', [
            'uses' => 'Agreement\AgreementProvisionsController@postAgreementProvisionItem',
            'as' => 'post.agreement.provision.item',
        ]);
    Route::delete('/agreement-provisions/delete-provision/{id}', [
            'uses' => 'Agreement\AgreementProvisionsController@deleteProvision',
            'as' => 'agreement.provisions.delete-provision',
        ]);
    Route::delete('/agreement-provisions/delete-item/{id}', [
            'uses' => 'Agreement\AgreementProvisionsController@deleteItem',
            'as' => 'agreement.provisions.delete-item',
        ]);

    // routes for agreement-admin/customize
    Route::get('/customize', [
            'uses' => 'Agreement\AgreementCustomizeController@getAgreementCustomizePage',
            'as' => 'agreement.customize',
        ]);
    Route::get('/customize/get-data', [
            'uses' => 'Agreement\AgreementCustomizeController@getAllAgreementCustomize',
            'as' => 'agreement.customize.data',
        ]);
    Route::delete('/customize/delete/{id}', [
            'uses' => 'Agreement\AgreementCustomizeController@deleteAgreementCustomize',
            'as' => 'agreement.customize.delete',
        ]);
    Route::post('/customize/update/{id}', [
            'uses' => 'Agreement\AgreementCustomizeController@updateAgreementCustomize',
            'as' => 'agreement.customize.update',
        ]);

    // routes for admin-customize/corp
    Route::get('/customize/corp/{corpId}', [
            'uses' => 'Agreement\AgreementCustomizeController@getAgreementCustomizeWithCorp',
            'as' => 'agreement.customize.with.corp',
        ]);
    Route::get('/customize/corp/data/{corpId}', [
            'uses' => 'Agreement\AgreementCustomizeController@getAgreementCustomizeWithCorpViewData',
            'as' => 'agreement.customize.with.corp.data',
        ]);
    Route::get('/customize/corp/provisions/{corpId}', [
            'uses' => 'Agreement\AgreementCustomizeController@getAgreementCustomizeProvisionsWithCorp',
            'as' => 'agreement.customize.with.corp.provisions',
        ]);
    Route::post('/customize/corp/update-provision', [
            'uses' => 'Agreement\AgreementCustomizeController@updateAgreementCustomizeProvisionWithCorp',
            'as' => 'agreement.customize.with.corp.update-provision',
        ]);
    Route::post('/customize/corp/update-item', [
        'uses' => 'Agreement\AgreementCustomizeController@updateAgreementCustomizeItemWithCorp',
        'as' => 'agreement.customize.with.corp.update-item',
    ]);
    Route::post('/customize/corp/delete-provision', [
        'uses' => 'Agreement\AgreementCustomizeController@deleteAgreementCustomizeProvisionWithCorp',
        'as' => 'agreement.customize.with.corp.delete-provision',
    ]);
    Route::post('/customize/corp/delete-item', [
        'uses' => 'Agreement\AgreementCustomizeController@deleteAgreementCustomizeItemWithCorp',
        'as' => 'agreement.customize.with.corp.delete-item',
    ]);


    // routes for agreement/admin/categories
    Route::get('/categories', [
            'uses' => 'Agreement\AgreementAdminCategoryController@getAgreementCategoriesPage',
            'as' => 'agreement.admin.categories',
        ]);
    Route::get('/categories/get-data', [
        'uses' => 'Agreement\AgreementAdminCategoryController@getAgreementCategoriesData',
        'as' => 'agreement.admin.categories.data',
    ]);
    Route::get('/categories/get-category-added-license/{id}', [
        'uses' => 'Agreement\AgreementAdminCategoryController@getAgreementCategoryAddedLicense',
        'as' => 'agreement.admin.categories.get-category-added-license',
    ]);
    Route::put('/categories/update-category-license/{id}', [
        'uses' => 'Agreement\AgreementAdminCategoryController@updateAgreementCategoryLicense',
        'as' => 'agreement.admin.categories.update-category-license',
    ]);
    Route::get('/categories/get-license-condition-type', [
            'uses' => 'Agreement\AgreementAdminCategoryController@getLicenseConditionType',
            'as' => 'agreement.admin.categories.license-condition-type',
        ]);
    Route::get('/categories/export-excel', [
            'uses' => 'Agreement\AgreementAdminCategoryController@exportExcel',
            'as' => 'agreement.admin.categories.export-excel',
        ]);
    Route::get('/categories/export-csv', [
            'uses' => 'Agreement\AgreementAdminCategoryController@exportCsv',
            'as' => 'agreement.admin.categories.export-csv',
        ]);

    // routes for agreement-admin/contract-terms-revision-history
    Route::get('/contract-terms-revision-history', [
            'uses' => 'Agreement\AgreementProvisionsController@getContractTermsRevisionHistoryView',
            'as' => 'contract.terms.revision.history',
        ]);
    Route::get('/contract-terms-revision-history/get-data', [
            'uses' => 'Agreement\AgreementProvisionsController@getContractTermsRevisionHistoryData',
            'as' => 'contract.terms.revision.history.data',
        ]);
    Route::get('/contract-terms-revision-history/get-detail/{id}', [
            'uses' => 'Agreement\AgreementProvisionsController@getContractTermsRevisionHistoryDetail',
            'as' => 'contract.terms.revision.history.detail',
        ]);

    // routes for agreement-admin/license
    Route::get('/license', [
            'uses' => 'Agreement\AgreementAdminLicenseController@getLicensePage',
            'as' => 'agreement.admin.license',
        ]);
    Route::get('/license/get-data', [
            'uses' => 'Agreement\AgreementAdminLicenseController@getLicenseData',
            'as' => 'agreement.admin.license.get-data',
        ]);
    Route::post('/license/add', [
            'uses' => 'Agreement\AgreementAdminLicenseController@addLicense',
            'as' => 'agreement.admin.license.add',
        ]);
    Route::get('/license/detail/{id}', [
            'uses' => 'Agreement\AgreementAdminLicenseController@getLicenseDetail',
            'as' => 'agreement.admin.license.detail',
        ]);
    Route::put('/license/update', [
            'uses' => 'Agreement\AgreementAdminLicenseController@updateLicense',
            'as' => 'agreement.admin.license.update',
        ]);
    Route::delete('/license/delete/{id}', [
            'uses' => 'Agreement\AgreementAdminLicenseController@deleteLicense',
            'as' => 'agreement.admin.license.delete',
        ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'affiliation',
        'system',
        'admin',
        'accounting_admin',
        'accounting',
        'popular'
    ],
], function () {
    Route::get('/agreement', function () {
        return response()->resposive('agreement.index');
    })->name('agreement.index');

    Route::get('/agreement/index', function () {
        return redirect()->route('agreement.index');
    });

    Route::post('/progress_management/demand_detail/redirect', [
        'uses' => 'ProgressManagement\ProgressManagementAffUserController@redirectToUpdateConfirm',
        'as' => 'post.progress_management.demand_detail.redirect',
    ]);

    Route::post('/progress_management/demand_detail/saveSession', [
        'uses' => 'ProgressManagement\ProgressManagementAffUserController@saveSession',
        'as' => 'post.progress_management.demand_detail.saveSession',
    ]);
    Route::any('/progress_management/demand_detail/{fileId?}', [
        'uses' => 'ProgressManagement\ProgressManagementAffUserController@affDemandDetail',
        'as' => 'get.progress_management.demand_detail',
    ]);
});

Route::group([
    'middleware' => ['auth', 'roles'],
    'roles' => ['system', 'admin', 'accounting_admin'],
], function () {
    Route::get('/auction_setting/genre', [
            'uses' => 'Auction\AuctionSettingController@getSelectedGenres',
            'as' => 'auction_setting.genre',
        ]);

    Route::post('/auction_setting', [
            'uses' => 'Auction\AuctionSettingController@update',
            'as' => 'auction_setting.update',
        ]);

    Route::get('notice_infos/edit/{noticeId?}', [
            'uses' => 'NoticeInfo\NoticeInfoController@edit',
            'as' => 'notice_info.edit',
        ]);

    Route::get('notice_infos/download_csv_answer/{noticeId?}', [
            'uses' => 'NoticeInfo\NoticeInfoController@downloadAnswerCSV',
            'as' => 'notice_info.download_csv_answer',
        ]);

    Route::post('notice_infos/update', [
            'uses' => 'NoticeInfo\NoticeInfoController@update',
            'as' => 'notice_info.update',
        ]);

    Route::post('notice_infos/create', [
            'uses' => 'NoticeInfo\NoticeInfoController@create',
            'as' => 'notice_info.create',
        ]);

    Route::post('notice_infos/remove/{noticeId}', [
            'uses' => 'NoticeInfo\NoticeInfoController@removeNotice',
            'as' => 'notice_info.remove',
        ]);

    Route::get('notice_infos/affiliation_list', [
        'uses' => 'NoticeInfo\NoticeInfoController@getListAffiliation',
        'as' => 'notice_info.affiliation_list',
    ]);

    Route::get('auction_setting/exclusion', [
        'uses' => 'Auction\AuctionSettingController@getExclusion',
        'as' => 'AuctionSetting.exclusion'
    ]);

    Route::post('auction_setting/exclusion', [
        'uses' => 'Auction\AuctionSettingController@postExclusion',
        'as' => 'auction_setting.postExclusion'
    ]);
});

Route::group([
    'middleware' => ['auth', 'roles'],
    'roles' => ['affiliation', 'system', 'admin', 'accounting_admin'],
], function () {
    Route::get('notice_infos/detail/{noticeId?}', [
            'uses' => 'NoticeInfo\NoticeInfoController@detail',
            'as' => 'notice_info.detail',
        ]);

    Route::post('notice_infos/answer/{noticeId}', [
            'uses' => 'NoticeInfo\NoticeInfoController@answer',
            'as' => 'notice_info.answer',
        ]);

    Route::get('/notice_infos', [
            'uses' => 'NoticeInfo\NoticeInfoController@index',
            'as' => 'notice_info.index',
        ]);

    Route::get('/notice_infos/index', function () {
        return redirect()->route('notice_info.index');
    });

    Route::post('notice_infos/ajax-get-list', [
            'uses' => 'NoticeInfo\NoticeInfoController@ajaxGetListNoticeInfo',
            'as' => 'notice_info.ajax.get.list',
        ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'popular',
        'accounting_admin',
        'accounting',
    ],
], function () {

    Route::get('report/jbr_commission', [
            'uses' => 'Report\ReportController@getJbrCommission',
            'as' => 'report.jbr_commission',
        ]);

    Route::get('/affiliation/genre_resigning/{idCorp}', [
            'uses' => 'Affiliation\AffiliationGenreResignController@index',
            'as' => 'affiliation.genre.resign.index',
        ]);

    Route::post('/affiliation/genre_resigning/', [
            'uses' => 'Affiliation\AffiliationGenreResignController@updateGenreResign',
            'as' => 'affiliation.genre.resign.update',
        ]);

    Route::post('/affiliation/genre_resigning/reconfirm', [
            'uses' => 'Affiliation\AffiliationGenreResignController@reconfirmContract',
            'as' => 'affiliation.genre.resign.reconfirm',
        ]);

    Route::get('/affiliation/resigning/{idCorp}', [
            'uses' => 'Affiliation\AffiliationResignController@index',
            'as' => 'affiliation.resign.index',
        ]);

    Route::post('/affiliation/resigning/', [
            'uses' => 'Affiliation\AffiliationResignController@updateInfoResign',
            'as' => 'affiliation.resign.update',
        ]);

    Route::post('/affiliation/resigning/reconfirm', [
            'uses' => 'Affiliation\AffiliationResignController@updateReconfirm',
            'as' => 'affiliation.resign.reconfirm',
        ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'accounting_admin',
    ],
], function () {
    Route::get('/not_correspond', [
            'uses' => 'NotCorrespond\NotCorrespondController@index',
            'as' => 'not_correspond.index',
        ]);

    Route::get('/not_correspond/index', function () {
        return redirect()->route('not_correspond.index');
    });

    Route::post('not_correspond/{notCorrespondItem}', [
            'uses' => 'NotCorrespond\NotCorrespondController@update',
            'as' => 'not_correspond.update',
        ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'accounting_admin',
    ],
], function () {
    Route::get('/commission_select/mcop_display', [
            'uses' => 'CommissionSelect\CommissionSelectController@mCorpDisplay',
            'as' => 'commission_select.m_corp_display',
        ]);
    Route::get('/commission_select/mcop_search', [
            'uses' => 'CommissionSelect\CommissionSelectController@mCorpSearch',
            'as' => 'commission_select.m_corp_search',
        ]);
});
Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'accounting_admin',
        'accounting',
        'popular',
    ],
], function () {
    Route::get('/progress_management/item_edit', [
            'uses' => 'ProgressManagement\ProgressManagementController@itemEdit',
            'as' => 'progress.item_edit',
        ]);
    Route::post('/progress_management/item_edit/{progressItemId}', [
            'uses' => 'ProgressManagement\ProgressManagementController@updateItemEdit',
            'as' => 'progress.update_item_edit',
        ]);
    Route::get('/report/unsent_list', [
        'uses' => 'Report\ReportCategoryController@unsentList',
        'as' => 'report.unsent_list',
    ]);
});
Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'popular',
        'accounting_admin',
        'accounting',
        'affiliation',
    ],
], function () {

    Route::get('/commission/search/{affiliationId?}', [
            'uses' => 'Commission\CommissionController@search',
            'as' => 'commission.search',
        ]);
    Route::post('/commission/search/{affiliationId?}', [
            'uses' => 'Commission\CommissionController@postSearch',
            'as' => 'commission.postSearch',
        ]);
    Route::get('/commission/{affiliationId?}', [
            'uses' => 'Commission\CommissionController@index',
            'as' => 'commission.index',
        ]);

    Route::get('/commission/index/{affiliationId?}', function ($affiliationId = null) {
        return redirect()->route('commission.index', ['affiliationId' => $affiliationId]);
    });

    Route::get('notice_infos/near', [
            'uses' => 'NoticeInfo\NoticeInfoController@near',
            'as' => 'notice_info.near',
        ]);

    Route::post('notice_infos/near', [
            'uses' => 'NoticeInfo\NoticeInfoController@updateArea',
            'as' => 'notice_info.save.near',
        ]);
});

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'accounting_admin',
    ],
], function () {
    Route::get('/vacation_edit', [
            'uses' => 'VacationEdit\VacationEditController@index',
            'as' => 'vacation_edit.index',
        ]);

    Route::get('/vacation_edit/index', function () {
        return redirect()->route('vacation_edit.index');
    });

    Route::post('/vacation_edit', [
            'uses' => 'VacationEdit\VacationEditController@update',
            'as' => 'vacation_edit.update',
        ]);
});

Route::get('/commission/commission_file_download/{id?}', [
    'uses' => 'Commission\CommissionDetailController@commissionFileDownload',
    'as' => 'commission.commission_file_download',
]);

Route::group([
    'middleware' => [
        'auth',
        'roles',
    ],
    'roles' => [
        'system',
        'admin',
        'accounting_admin',
        'accounting',
        'popular',
    ],
], function () {
    Route::get('/report/addition', [
        'uses' => 'Report\ReportCategoryController@addition',
        'as' => 'report.addition',
    ]);
    Route::post('/report/addition/update', [
        'uses' => 'Report\ReportCategoryController@additionUpdate',
        'as' => 'report.additionUpdate',
    ]);
    Route::get('/report/addition/exportCSV', [
        'uses' => 'Report\ReportCategoryController@additionExportCSV',
        'as' => 'report.additionExportCSV',
    ]);
});
