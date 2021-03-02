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

Route::get('/', [
	'uses' 	=> 'MainController@getIndex',
	'as'	=> 'home.main.index.get'
]);

Route::get('/privacy_policy', function(){
	return view('policy');
});

Route::get('/gen_connote/{connote_id}', [
	'uses' 	=> 'MainController@getGenConnote',
	'as' 	=> 'home.gen_connote.index.get',
]);
Route::get('gen_connote/{ctm_key}/point/{id}/{con_key}/{cod}/{cr}/{price}', [
	'uses' 	=> 'MainController@getGenNewConnote',
	'as' 	=> 'home.gen_connote.new.get',
]);

Route::get('gen_connote/{ctm_key}/point/{id}/{con_key}', [
	'uses' 	=> 'MainController@getGenNewConnote',
	'as' 	=> 'home.gen_connote.new.get',
]);


Route::get('/authen', [
	'uses' 	=> 'AuthenController@getLogin',
	'as'	=> 'home.authen.login.get'
]);
Route::post('/authen', [
	'uses' 	=> 'AuthenController@postLogin',
	'as'	=> 'home.authen.login.post'
]);
Route::get('/authen/logout', [
	'uses' 	=> 'AuthenController@getLogout',
	'as'	=> 'home.authen.logout.get'
]);

Route::get('/public_tracking', [
	'uses' 	=> 'PublicTrackingController@getIndex',
	'as'	=> 'home.public_tracking.index.get'
]);
Route::post('/public_tracking', [
	'uses' 	=> 'PublicTrackingController@postIndex',
	'as'	=> 'home.public_tracking.index.post'
]);
Route::get('/clear-cache', function() {
	// echo "test";
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});
