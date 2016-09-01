<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::group(['prefix' => 'Api/v1'], function () {
    Route::get('/user/{id}','UserController@display');
    Route::post('/user/register','UserController@register');
    Route::get('/verify-password','UserController@verifyPassword');
    Route::get('/profile/{clientMasterId}','UserController@profile');
});
