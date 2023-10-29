<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function() {
    Route::get('/', function() {
        return 'Prado Energy Management System (PEMS) API version 1.0';
    });
    Route::group(['prefix' => 'auth'], function() {
        Route::post('/login', 'App\Http\Controllers\AuthController@login');
        Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    });

    Route::group(['prefix' => 'agent'], function() {
        Route::get('/{id}', 'App\Http\Controllers\Agents\AgentController@show');
        Route::get('/meters', 'App\Http\Controllers\Agents\AgentController@index');
        Route::put('/{id}/update', 'App\Http\Controllers\Agents\AgentController@update');
        Route::get('/customers', 'App\Http\Controllers\Agents\AgentController@customers');
        Route::get('/credit-user', 'App\Http\Controllers\Agents\AgentController@userWalletTopup');
        Route::get('/tickets', 'App\Http\Controllers\Agents\AgentController@userWalletTopup');
        Route::delete('/{id}/delete', 'App\Http\Controllers\Agents\AgentController@destroy');
    });

    Route::group(['prefix' => 'customer'], function() {
        Route::get('/', 'App\Http\Controllers\Customer\CustomerController@index');
        Route::get('/{id}', 'App\Http\Controllers\Customer\CustomerController@getCustomer');
        Route::put('/{id}/update', 'App\Http\Controllers\Customer\CustomerController@update');
        //Route::get('/tickets', 'App\Http\Controllers\Customer\CustomerController@tickets');
        Route::get('/{id}/transactions', 'App\Http\Controllers\Customer\CustomerController@transactions');
        Route::delete('/{id}/delete', 'App\Http\Controllers\Customer\CustomerController@destroy');
    }); 

    Route::group(['prefix' => 'support'], function() {
        Route::get('/', 'App\Http\Controllers\Support\TicketController@index');
        Route::get('/{id}', 'App\Http\Controllers\Support\TicketController@show');
        Route::post('/', 'App\Http\Controllers\Support\SupportController@store');
        Route::post('/reply-ticket', 'App\Http\Controllers\Support\SupportController@comment');
        Route::put('/ticket/{id}', 'App\Http\Controllers\Support\SupportController@update');
        Route::delete('/ticket/{id}', 'App\Http\Controllers\Support\SupportController@destroy');
    });
    
    // Mini grid routes
    Route::group(['prefix' => 'mini-grids'], static function () {
        Route::get('/', 'MiniGridController@index');
        Route::get('/{id}', 'MiniGridController@show');
        Route::post('/{id}/transactions', 'RevenueController@transactionRevenuePerMiniGrid');
        Route::post('/{id}/energy', 'RevenueController@soldEnergyPerMiniGrid');
        Route::get('/{id}/batteries', 'BatteryController@showByMiniGrid');
        Route::get('/{id}/solar', 'SolarController@showByMiniGrid'); 
    });

    Route::group(['prefix' => 'sites'], function () {
        Route::get('/', 'App\Http\Controllers\Site\SiteController@index');
        Route::post('/', 'App\Http\Controllers\Site\SiteController@store');
        Route::put('/{id}', 'App\Http\Controllers\Site\SiteController@update');
        Route::delete('/{id}', 'App\Http\Controllers\Site\SiteController@destroy');
    });
    
    // Import meter routes
    require_once 'resources/Meter.php';
   
    // Import site routes
    // require_once 'resources/Site.php';
});

