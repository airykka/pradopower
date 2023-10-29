<?php

/* Meter */ 
Route::group(['prefix' => 'meters'], function () {
    Route::get('/', 'App\Http\Controllers\Meter\MeterController@index');
    Route::get('/unasigned', 'App\Http\Controllers\Meter\MeterController@index');
    Route::get('/meter-list', 'App\Http\Controllers\Meter\MeterController@get_list');
    Route::get('/list', 'App\Http\Controllers\DataController@remoteControl');
    Route::get('/{id}', 'Meter\MeterController@show')->where('id', '[0-9]+');
    Route::post('/', 'Meter\MeterController@store');
    Route::put('/{id}', 'Meter\MeterController@update')->where('id', '[0-9]+');
    Route::get('/search', 'Meter\MeterController@Search');
    Route::delete('/{ownerId}', 'Meter\MeterController@destroy');
    Route::get('{serialNumber}/revenue', 'RevenueController@meterRevenue');

    Route::group(['prefix' => 'operations'], function() {
        Route::post('/assign-customer', 'App\Http\Controllers\Meter\MeterController@assignCustomer');
        Route::post('/remote-control', 'App\Http\Controllers\Meter\MeterController@remoteControl');
        Route::post('/remote-reading', 'App\Http\Controllers\Meter\MeterController@remoteReading');
        Route::post('/remote-token', 'App\Http\Controllers\Meter\MeterController@remoteToken');
        Route::post('/status', 'App\Http\Controllers\Meter\MeterController@meterStatus');
        Route::post('/customer', 'App\Http\Controllers\Meter\MeterController@posCustomer');
        Route::post('/purchase', 'App\Http\Controllers\Meter\MeterController@purchase');
        Route::post('/customer', 'App\Http\Controllers\Meter\MeterController@customer');

        Route::group(['prefix' => 'data'], function() {
            Route::post('/daily', 'App\Http\Controllers\Meter\MeterController@dailyData');
            Route::post('/hourly', 'App\Http\Controllers\Meter\MeterController@hourlyData');
            Route::post('/monthly', 'App\Http\Controllers\Meter\MeterController@monthlyData');
            Route::get('/customers-list', 'App\Http\Controllers\Meter\MeterController@customerList');
        });
    });

    Route::group(['prefix' => 'steama'], function() {
        Route::get('/', 'App\Http\Controllers\Meter\SteamaController@get_list');
    });

    /* Meter types */
    Route::group(['prefix' => 'types'], function () {
        Route::get('/', 'MeterTypeController@index');
        Route::get('/{id}', 'MeterTypeController@show');
        Route::post('/', 'MeterTypeController@store');
        Route::put('/{id}', 'MeterTypeController@update');
        Route::get('/{id}/list', 'MeterTypeController@meterList');
    });

    Route::get('/{id}/all', 'Meter\MeterController@allRelations');

    Route::group(['prefix' => 'parameters'], function () {
        Route::get('/', 'Meter\MeterParaMeterController@index'); // list of all meters which are related to a customer
        Route::post('/', 'Meter\MeterParaMeterController@store');
        Route::get('/connection-types', 'Meter\MeterParaMeterController@connectionTypes');
    });

    Route::get('/parameters/{meterParameter}', 'Meter\MeterParaMeterController@show');
    Route::put('/{serialNumber}/parameters', 'Meter\MeterParaMeterController@update');
    Route::get('/{serialNumber}/transactions', 'Meter\MeterController@transactionList');
    Route::get('{serialNumber}/consumptions/{start}/{end}', 'Meter\MeterController@consumptionList');
    Route::get('/geoList', 'Meter\MeterController@meterGeoList');
});
