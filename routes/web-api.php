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
    Route::get('/my-properties', 'UserController@displayAllProperty');
    Route::get('/my-payment-schedule', 'UserController@displayPaymentSchedule');
    Route::get('/my-loans', 'UserController@displayMyLoan');
    Route::get('/my-credit-notes', 'UserController@displayMyCreditNotes');
    Route::put('/update-my-profile/{cmId}', 'UserController@updateMyProfile');
    Route::post('/update-my-profile-pic/{cmId}','UserController@updateMyProfilePic');
    Route::get('/exclusive-deals', 'UserController@displayExclusiveDeals');
    Route::get('/city-list', 'UserController@cityList');
    Route::put('/reset-password', 'UserController@resetPassword');
    Route::get('/service-request-list', 'UserController@serviceRequestList');
    Route::get('/service-request-detail', 'UserController@serviceRequestDetails');
});
