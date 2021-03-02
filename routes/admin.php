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
Route::get('/', function(){
	return \Redirect::route('admin.dashboard.index.get');
});

Route::get('/authen', [
	'uses' 	=> 'AuthenController@getLogin',
	'as'	=> 'admin.authen.login.get'
]);
Route::post('/authen', [
	'uses' 	=> 'AuthenController@postLogin',
	'as'	=> 'admin.authen.login.post'
]);
Route::get('/authen/logout', [
	'uses' 	=> 'AuthenController@getLogout',
	'as'	=> 'admin.authen.logout.get'
]);

// ADMIN AJAX CENTER.
Route::post('/admin_ajax', [
	'middleware' => 'admin.authen',
	'uses'		 => 'AjaxController@ajaxCenter',
	'as'		 => 'admin.ajax_center.post'
]);

Route::group(['middleware' => 'admin.authen'], function(){

	Route::get('/', [
		'uses' 	=> 'DashboardController@getIndex',
		'as'	=> 'admin.dashboard.index.get'
	]);

	// BOOKING.
	Route::get('/booking', [
		'uses' 	=> 'BookingController@getIndex',
		'as' 	=> 'admin.booking.index.get',
	]);
	Route::get('/booking/{id}/update', [
		'uses' 	=> 'BookingController@getUpdate',
		'as' 	=> 'admin.booking.update.get',
	]);
	Route::post('/booking/{id}/update', [
		'uses' 	=> 'BookingController@postUpdate',
		'as' 	=> 'admin.booking.update.post',
	]);
	Route::post('/booking/update_msg', [
		'uses' 	=> 'BookingController@postUpdateMsg',
		'as' 	=> 'admin.booking.update_msg.post',
	]);
	Route::post('/booking/update/add_point', [
		'uses' 	=> 'BookingController@postAddPoint',
		'as' 	=> 'admin.booking.add_point.post',
	]);
	Route::post('/booking/remove', [
		'uses' 	=> 'BookingController@postRemove',
		'as' 	=> 'admin.booking.remove.post',
	]);

	// JOBS.
	Route::get('/job', [
		'uses' 	=> 'JobController@getIndex',
		'as' 	=> 'admin.job.index.get',
	]);
	Route::post('/job/update_msg', [
		'uses' 	=> 'JobController@postUpdateMsg',
		'as' 	=> 'admin.job.update_msg.post',
	]);
	Route::post('/job/approve', [
		'uses' 	=> 'JobController@postApprove',
		'as' 	=> 'admin.job.approve.post',
	]);
	Route::post('/job/send', [
		'uses' 	=> 'JobController@postSend',
		'as' 	=> 'admin.job.send.post',
	]);
	Route::post('/job/update_photo', [
		'uses' 	=> 'JobController@postUpdatePhoto',
		'as' 	=> 'admin.job.update_photo.post',
	]);


	// CONNOTE.
	Route::get('/connote', [
		'uses' 	=> 'ConnoteController@getIndex',
		'as' 	=> 'admin.connote.index.get',
	]);
	Route::get('/connote/{id}/update', [
		'uses' 	=> 'ConnoteController@getUpdate',
		'as' 	=> 'admin.connote.update.get',
	]);
	Route::post('/connote/{id}/update', [
		'uses' 	=> 'ConnoteController@postUpdate',
		'as' 	=> 'admin.connote.update.post',
	]);
	Route::post('/connote/{id}/gen_data', [
		'uses' 	=> 'ConnoteController@postGenData',
		'as' 	=> 'admin.connote.gen_data.post',
	]);
	Route::post('/connote/{id}/cancel', [
		'uses' 	=> 'ConnoteController@postCancel',
		'as' 	=> 'admin.connote.cancel.post',
	]);

	// EMPLOYEE.
	Route::get('/employee', [
		'uses' 	=> 'EmployeeController@getIndex',
		'as' 	=> 'admin.employee.index.get',
	]);
	Route::get('/employee/{id}/update', [
		'uses' 	=> 'EmployeeController@getUpdate',
		'as' 	=> 'admin.employee.update.get',
	]);
	Route::post('/employee/{id}/update', [
		'uses' 	=> 'EmployeeController@postUpdate',
		'as' 	=> 'admin.employee.update.post',
	]);
	Route::post('/employee/{id}/update_pwd', [
		'uses' 	=> 'EmployeeController@postUpdatePwd',
		'as' 	=> 'admin.employee.update_pwd.post',
	]);
	Route::post('/employee/{id}/remove', [
		'uses' 	=> 'EmployeeController@postRemove',
		'as' 	=> 'admin.employee.remove.post',
	]);


	//CUSTOMER
	Route::get('/customer', [
		'uses'	=> 'CustomerController@getIndex',
		'as'	=> 'admin.customer.index.get',
	]);
	Route::get('/customer/{id}/update', [
		'uses' 	=> 'CustomerController@getUpdate',
		'as' 	=> 'admin.customer.update.get',
	]);
	Route::post('/customer/{id}/update', [
		'uses' 	=> 'CustomerController@postUpdate',
		'as' 	=> 'admin.customer.update.post',
	]);
	Route::post('/customer/{id}/update_pwd', [
		'uses' 	=> 'CustomerController@postUpdatePwd',
		'as' 	=> 'admin.customer.update_pwd.post',
	]);
	Route::post('/customer/{id}/remove', [
		'uses' 	=> 'CustomerController@postRemove',
		'as' 	=> 'admin.customer.remove.post',
	]);

	//POINT.
	Route::get('/customer/{ctm_id}/point', [
		'uses'	=>  'CustomerController@getPoint',
		'as'	=>	'admin.customer.point.get',
	]);
	Route::get('/customer/{ctm_id}/point/{p_id}/update', [
		'uses'	=>  'CustomerController@getPointUpdate',
		'as'	=>	'admin.customer.point_update.get',
	]);
	Route::post('/customer/{ctm_id}/point/{p_id}/update', [
		'uses'	=>  'CustomerController@postPointUpdate',
		'as'	=>	'admin.customer.point_update.post',
	]);
	Route::post('/customer/{ctm_id}/point/{p_id}/remove', [
		'uses'	=>  'CustomerController@postPointRemove',
		'as'	=>	'admin.customer.point_remove.post',
	]);
	Route::get('/customer/{ctm_id}/point/import_excel', [
		'uses'	=>  'CustomerController@getPointExcel',
		'as'	=>	'admin.customer.point_excel.get',
	]);
	Route::post('/customer/{ctm_id}/point/import_excel', [
		'uses'	=>  'CustomerController@postPointImportExcel',
		'as'	=>	'admin.customer.import_excel.post',
	]);
	Route::post('/customer/{ctm_id}/point/read_excel', [
		'uses'	=>  'CustomerController@postPointReadExcel',
		'as'	=>	'admin.customer.read_excel.post',
	]);

	//POSITION
	Route::get('/position', [
		'uses'	=> 'PositionController@getIndex',
		'as'	=> 'admin.position.index.get',
	]);
	Route::post('/position/update', [
		'uses' 	=> 'PositionController@postUpdate',
		'as' 	=> 'admin.position.update.post',
	]);

		// PROFILE.
	Route::get('/profile', [
		'uses' 	=> 'ProfileController@getIndex',
		'as' 	=> 'admin.profile.index.get',
	]);
	Route::post('/profile/{id}/update', [
		'uses' 	=> 'ProfileController@postUpdate',
		'as' 	=> 'admin.profile.update.post',
	]);

	// REPORT.
	Route::get('/report', [
		'uses' 	=> 'ReportController@getIndex',
		'as'	=> 'admin.report.index.get'
	]);
	Route::get('/reportnew', [
		'uses' 	=> 'ReportNewController@getIndex',
		'as'	=> 'admin.report.index_new.get'
	]);
	Route::post('/report/get_data', [
		'uses' 	=> 'ReportController@postGetData',
		'as'	=> 'admin.report.get_data.post'
	]);
	Route::post('/report/adjust_report', [
		'uses' 	=> 'ReportController@postAdjustReport',
		'as'	=> 'admin.report.adjust_report.post'
	]);
	Route::post('/report/gen_excel', [
		'uses' 	=> 'ReportController@postGenerateExcel',
		'as'	=> 'admin.report.gen_excel.post'
	]);

	// REPORT.
	Route::get('/report/booking', [
		'uses' 	=> 'ReportBookingController@getIndex',
		'as'	=> 'admin.report_booking.index.get'
	]);
	Route::post('/report/booking/get_data', [
		'uses' 	=> 'ReportBookingController@postGetData',
		'as'	=> 'admin.report_booking.get_data.post'
	]);
	Route::post('/report/booking/adjust_report', [
		'uses' 	=> 'ReportBookingController@postAdjustReport',
		'as'	=> 'admin.report_booking.adjust_report.post'
	]);
	Route::post('/report/booking/gen_excel', [
		'uses' 	=> 'ReportBookingController@postGenerateExcel',
		'as'	=> 'admin.report_booking.gen_excel.post'
	]);

	// REPORT MESSENGER.
	Route::get('/report/messenger', [
		'uses' 	=> 'ReportMessengerController@getIndex',
		'as'	=> 'admin.report_messenger.index.get'
	]);
	Route::post('/report/messenger/get_data', [
		'uses' 	=> 'ReportMessengerController@postGetData',
		'as'	=> 'admin.report_messenger.get_data.post'
	]);
	Route::post('/report/messenger/adjust_report', [
		'uses' 	=> 'ReportMessengerController@postAdjustReport',
		'as'	=> 'admin.report_messenger.adjust_report.post'
	]);
	Route::post('/report/messenger/gen_excel', [
		'uses' 	=> 'ReportMessengerController@postGenerateExcel',
		'as'	=> 'admin.report_messenger.gen_excel.post'
	]);

	// REPORT CUSTOMER.
	// Route::get('/report/customer', [
	// 	'uses' 	=> 'ReportCustomerController@getIndex',
	// 	'as'	=> 'admin.report_customer.index.get'
	// ]);
	// Route::post('/report/customer/get_data', [
	// 	'uses' 	=> 'ReportCustomerController@postGetData',
	// 	'as'	=> 'admin.report_customer.get_data.post'
	// ]);
	// Route::post('/report/customer/adjust_report', [
	// 	'uses' 	=> 'ReportCustomerController@postAdjustReport',
	// 	'as'	=> 'admin.report_customer.adjust_report.post'
	// ]);
	// Route::post('/report/customer/gen_excel', [
	// 	'uses' 	=> 'ReportCustomerController@postGenerateExcel',
	// 	'as'	=> 'admin.report_customer.gen_excel.post'
	// ]);
});

