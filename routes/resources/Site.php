<?php 


Route::group(['prefix' => 'sites'], function() {
  Route::get('/', 'App\Http\Controllers\Site\SiteController@index');
  Route::post('/', 'App\Http\Controllers\Site\SiteController@store');
});