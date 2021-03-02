<?php

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

Route::group(['middleware' => 'home.authen'], function(){

	// BOOKING.
	Route::get('/booking', [
		'uses' 	=> 'BookingController@getIndex',
		'as' 	=> 'home.booking.index.get',
	]);
	Route::post('/booking', [
		'uses' 	=> 'BookingController@postCreate',
		'as' 	=> 'home.booking.create.post',
	]);
	// Route::get('/booking_cod', [
	// 	'uses' 	=> 'BookingCodController@getIndex',
	// 	'as' 	=> 'home.booking_cod.index.get',
	// ]);
	// Route::post('/booking_cod', [
	// 	'uses' 	=> 'BookingCodController@postCreate',
	// 	'as' 	=> 'home.booking_cod.create.post',
	// ]);
	Route::post('/booking/{customer_id}/add_point', [
		'uses' 	=> 'BookingController@postAddPoint',
		'as' 	=> 'home.booking.add_point.post',
	]);
	Route::post('/booking/{customer_id}/remove_point', [
		'uses' 	=> 'BookingController@postRemovePoint',
		'as' 	=> 'home.booking.remove_point.post',
	]);

	// PROFILE.
	Route::get('/profile', [
		'uses' 	=> 'ProfileController@getIndex',
		'as' 	=> 'home.profile.index.get',
	]);
	Route::post('/profile/{id}/update', [
		'uses' 	=> 'ProfileController@postUpdate',
		'as' 	=> 'home.profile.update.post',
	]);

	// TRACKING.
	Route::get('/tracking', [
		'uses' 	=> 'TrackingController@getIndex',
		'as' 	=> 'home.tracking.index.get',
	]);
	Route::post('/tracking', [
		'uses' 	=> 'TrackingController@postIndex',
		'as' 	=> 'home.tracking.index.post',
	]);

	// HISTORY.
	Route::get('/history', [
		'uses' 	=> 'HistoryController@getIndex',
		'as' 	=> 'home.history.index.get',
	]);
});

