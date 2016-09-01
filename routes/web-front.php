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
    Route::match(['get','post'],'/','UserController@login');
    
    Route::get('/user/{id}', 'UserController@display');
    Route::get('/profile', 'UserController@profile');
    Route::get('/logout', 'UserController@logout');
