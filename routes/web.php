<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::group(['prefix' => 'admin'], function() {
    Route::resource('customers', 'App\Http\Controllers\Customer\CustomerController');
    Route::resource('agents', 'App\Http\Controllers\Agents\AgentController');  
    
    Route::group(['prefix' => 'auth'], function() {
        Route::post('login', 'App\Http\Controllers\Admin\AuthController@login');
        Route::post('logout', 'App\Http\Controllers\Admin\AuthController@logout');
        Route::post('change-password', 'App\Http\Controllers\Admin\AuthController@changePassword');
    });
    
    Route::group(['prefix' => 'profile'], function() {
        Route::get('/', 'App\Http\Controllers\Admin\AdminController@index');
        Route::post('/', 'App\Http\Controllers\Admin\AdminController@store');
        Route::get('/{id}', 'App\Http\Controllers\Admin\AdminController@show');
        Route::put('/{id}', 'App\Http\Controllers\Admin\AdminController@update');
        Route::delete('/{id}/delete', 'App\Http\Controllers\Admin\AdminController@destroy');
    });

    Route::group(['prefix' => 'settings'], function() {
        Route::post('/', 'App\Http\Controllers\SettingController@update');
        Route::get('/', 'App\Http\Controllers\SettingController@getSettings');
        Route::get('/analytic', 'App\Http\Controllers\SettingController@dashboardAnalytics');
        Route::get('/transactions', 'App\Http\Controllers\SettingController@transactions');
    });

    Route::get('/messages', 'App\Http\Controllers\SettingController@getMessages');
    Route::get('/transactions', 'App\Http\Controllers\SettingController@getMessages');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
