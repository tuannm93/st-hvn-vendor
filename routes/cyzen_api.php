<?php

Route::get('cyzen/demand/detail/{id}', [
    'uses' => 'Api\CyzenApiController@getDemandDetail',
    'as' => 'cyzen.demand.detail',
]);

Route::post('cyzen/demand/update', [
    'uses' => 'Api\CyzenApiController@updateDemandStatus',
    'as' => 'cyzen.demand.update',
]);

Route::post('cyzen/logout', [
    'uses' => 'Api\CyzenApiController@logoutPIC',
    'as' => 'cyzen.logout',
]);
