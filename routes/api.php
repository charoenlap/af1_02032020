<?php

use Illuminate\Http\Request;

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

// API RESTful.
Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function(){

	Route::resource('/docs', 'ApiDocController');
	Route::resource('/login', 'ApiControllers\ApiUserLoginController');
	Route::resource('/register', 'ApiControllers\ApiUserRegisterController');
	Route::resource('/profile', 'ApiControllers\ApiUserProfileController');
	Route::resource('/history', 'ApiControllers\ApiUserHistoryController');
	Route::resource('/feed', 'ApiControllers\ApiNewFeedController');
	Route::resource('/booking_list', 'ApiControllers\ApiBookingListController');
	Route::resource('/booking_detail', 'ApiControllers\ApiBookingDetailController');
	// Route::resource('/booking_cod_detail', 'ApiControllers\ApiBookingCodDetailController');
	Route::resource('/booking_update', 'ApiControllers\ApiBookingUpdateController');
	Route::resource('/job_list', 'ApiControllers\ApiJobListController');
	Route::resource('/job_detail', 'ApiControllers\ApiJobDetailController');
	Route::resource('/job_list_for_sup', 'ApiControllers\ApiJobListForSupController');
	Route::resource('/job_create', 'ApiControllers\ApiJobCreateController');
	Route::resource('/job_update', 'ApiControllers\ApiJobUpdateController');
	Route::resource('/job_approve', 'ApiControllers\ApiJobApproveController');
	Route::resource('/msg_list_for_sup', 'ApiControllers\ApiMsgListForSupController');
});
