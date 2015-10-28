<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function() {
	return view('dashboard');
});

Route::group(['prefix' => 'v1'], function() {
	Route::post('/events', 'ThingseeController@postEvents');
	Route::get('/events', 'ThingseeController@getEvents');
	Route::get('/devices', 'ThingseeController@getDevices');
});