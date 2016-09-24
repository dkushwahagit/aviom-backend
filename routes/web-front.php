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
    
  Route::group(['middleware' => 'CheckSession'],function () {  
      
    Route::get('/user/{id}', 'UserController@display');
    Route::get('/my-properties', 'UserController@displayMyAllProperty');
    Route::get('/my-payment-schedule', 'UserController@displayMyPaymentSchedule');
    Route::get('/profile', 'UserController@profile');
    Route::get('/logout', 'UserController@logout');
    Route::get('/my-loans', 'UserController@displayMyLoan');
    Route::get('/my-credit-notes', 'UserController@displayMyCreditNotes');
    Route::put('/update-my-profile', 'UserController@updateMyProfile');
    Route::post('/update-my-profile-pic','UserController@updateMyProfilePic');
    Route::get('/exclusive-deals/{cityid?}', 'UserController@displayExclusiveDeals');
    Route::post('/reset-password', 'UserController@resetPassword');
    Route::get('/service-request-list', 'UserController@serviceRequestList');
    Route::get('/service-request-detail/{cIId}', 'UserController@serviceRequestDetails');
    Route::post('/generate-service-ticket','UserController@generateServiceRequest');
    Route::post('/reply-service-ticket','UserController@replyServiceRequest');
    Route::get('/my-referral-list','UserController@referralList');
    Route::post('/add-referral','UserController@addReferral');
  });  
  
  Route::match(['get','post'],'/','UserController@login');
  Route::post('/forgot-password','UserController@forgotPassword');
  Route::get('/forgot-password/{encrypted_email?}','UserController@forgotPassword');