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

	/* ThingSee One data endpoints */
	Route::get('/envs', 'ThingseeController@getDevices');
	Route::get('/envs/{id}', 'ThingseeController@getDevice');
	Route::get('/envs/{id}/data', 'ThingseeController@getDeviceData');

	/* Placemeter data endpoints */
	Route::get('/measurementpoints', 'PlacemeterController@getMeasurementPoints');
	Route::get('/measurementpoints/{id}', 'PlacemeterController@getMeasurementPoint');
	Route::get('/measurementpoints/{id}/data', 'PlacemeterController@getMeasurementPointData');

	/* Innorange data endpoints */
	Route::get('/cellmeasurementpoints', 'InnorangeController@index');
	Route::get('/cellmeasurementpoints/{id}', 'InnorangeController@getMesurementPoint');
	Route::get('/cellmeasurementpoints/{id}/data', 'InnorangeController@getMeasurementPointData');
});