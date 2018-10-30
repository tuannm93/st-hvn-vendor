<?php
Route::get(
    'ajax/getCommissionMaxLimit',
    [
    'as' => 'ajax.get_commission_max_limit',
    'uses' => 'Ajax\AjaxController@getCommissionMaxLimit'
    ]
);

Route::get(
    'ajax/category_list',
    [
    'uses' => 'Ajax\AjaxController@getCategoryListByGenreId',
    'as' => 'ajax.get_category_by_genre_id',
    ]
);

Route::get(
    'ajax/genre_list',
    [
    'uses' => 'Ajax\AjaxController@getGenreListBySiteId',
    'as' => 'ajax.get_genre_list_by_site_id',
    ]
);

Route::get(
    'ajax/inquiry_item_data',
    [
    'uses' => 'Ajax\AjaxController@getInquiryItemData',
    'as' => 'ajax.get_inquiry_item_data',
    ]
);

Route::post(
    'ajax/count_browse/{demand_id?}',
    [
    'uses' => 'Ajax\AjaxController@countBrowse',
    'as' => 'ajax.demand.count_browse',
    ]
);

Route::post(
    'ajax/count_browse_list/',
    [
        'uses' => 'Ajax\AjaxController@countBrowseList',
        'as' => 'ajax.demand.count_browse_list',
    ]
);

Route::get(
    'ajax/write_browse/{demand_id?}',
    [
    'uses' => 'Ajax\AjaxController@writeBrowse',
    'as' => 'ajax.demand.write_browse',
    ]
);

Route::get(
    'ajax/site_data',
    [
    'uses' => 'Ajax\AjaxController@getSiteData',
    'as' => 'ajax.demand.site_data',
    ]
);

Route::get(
    'ajax/selection_system_list',
    [
    'uses' => 'Ajax\AjaxController@getSelectionSystemList',
    'as' => 'ajax.demand.selection_system_list',
    ]
);
Route::get(
    'ajax/load_m_corp/{corp_id}/{category_id?}',
    [
    'uses' => 'Ajax\AjaxController@getMCorp',
    'as' => 'ajax.demand.load_m_corp',
    ]
);

Route::get(
    'ajax/travel_expenses',
    [
    'uses' => 'Ajax\AjaxController@getTravelExpenses',
    'as' => 'ajax.travel_expenses',
    ]
);

Route::get(
    'ajax/exists_auto_commission_corps',
    [
    'uses' => 'Ajax\AjaxController@checkExistsAutoCommissionCorps',
    'as' => 'ajax.exists_auto_commission_corps',
    ]
);

Route::get(
    'ajax/category_list2',
    [
    'as' => 'ajax.demand.category_list2',
    'uses' => 'Ajax\AjaxController@getCategoryList2'
    ]
);

Route::get(
    'ajax/get_user_list',
    [
    'as' => 'demand.get_user_list',
    'uses' => 'Ajax\AjaxController@getUserList',
    ]
);

Route::get(
    'ajax/get_default_fee',
    [
    'as' => 'demand.get_default_fee',
    'uses' => 'Ajax\AjaxController@getDefaultFee',
    ]
);

Route::get(
    'ajax/attention_data/{genre_id?}',
    [
    'uses' => 'Ajax\AjaxController@attentionData',
    'as' => 'ajax.demand.attention_data'
    ]
);

Route::get(
    'ajax/commission_change/{category_id?}/{corp_id?}',
    [
    'uses' => 'Ajax\AjaxController@commissionChange',
    'as' => 'ajax.demand.commission_change'
    ]
);

Route::get(
    'ajax/inquiry_list/{category?}',
    [
    'uses' => 'Ajax\AjaxController@inquiryList',
    'as' => 'ajax.demand.inquiry_list'
    ]
);

Route::get(
    'ajax/get_ms_text',
    [
    'uses' => 'Ajax\AjaxController@getMsText',
    'as' => 'ajax.get.ms.text',
    ]
);
Route::get(
    '/ajax/search_tax_rate/{date}',
    [
    'uses' => 'Ajax\AjaxController@getTaxRate',
    'as' => 'ajax.get.tax.rate',
    ]
);
Route::get(
    '/ajax/search_tax_rate_only/{date}',
    [
    'uses' => 'Ajax\AjaxController@getTaxRateOnly',
    'as' => 'ajax.get.tax.rate.only',
    ]
);
Route::post(
    '/ajax/calculate_bill_info',
    [
    'uses' => 'Ajax\AjaxController@calculateBillInfo',
    'as' => 'ajax.calculate.bill_info',
    ]
);

Route::post('/ajax/countaffiliation', [
    'uses' => 'Ajax\AjaxController@countAffiliation',
    'as' => 'ajax.affiliation.count'
]);

Route::get('ajax/decrease_browser_cache', [
    'uses' => 'Ajax\AjaxController@decreaseBrowserCache',
    'as' => 'ajax.decrease_browser_cache'
]);
