<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::post('token', function() {
	$ret = array(
		'access_token' => 'ABCDE',
		'refresh_token' => '12345',
		'expires_in' => 3600,
		'token_type' => 'Bearer'
	);
	return Response::json($ret);
});

Route::get('login', function() {
	return View::make('login')->with('bg', rand(1, 6));
});
Route::post('login', 'AdminController@doLogin');
Route::get('logout', function() {
	Session::flush();
	return Redirect::to('/login');
});

Route::group(['prefix' => 'api/v1', 'before' => 'auth.custom'], function() {
	//header("Access-Control-Allow-Origin: *");
	//header("Access-Control-Allow-Methods: POST, GET, DELETE");

	Route::post('change_semester', 'AdminController@change_semester');
	Route::post('import-payment', 'ImportController@payments');
	Route::post('payment', 'ImportController@payment');
	Route::post('refund', 'RefundsController@save');

	Route::post('delete-payment', 'FeesController@destroy');
	Route::post('delete-refund', 'RefundsController@destroy');

	// ember-data
	Route::resource('bcodes', 'BcodesController');

	Route::get('fees', 'FeesController@search');
	Route::get('load-unpaid', 'FeesController@unpaid');
	Route::get('payment', 'FeesController@payment');
	
	Route::get('refund/{id}', 'RefundsController@show');
	Route::get('refund-check/{id}', 'RefundsController@check');
	Route::get('refund-search', 'RefundsController@search');

	Route::get('students', 'StudentsController@search');
	Route::get('reports/certbilling/{id}', 'ReportsController@certbilling');
	Route::get('reports/collections', 'ReportsController@collections');
	Route::get('reports/ledgers/{id}', 'ReportsController@ledger');
	Route::get('reports/receivables', 'ReportsController@receivables');
	Route::get('reports/refunds', 'ReportsController@refunds');
	Route::get('reports/sumbilling', 'ReportsController@sumbilling');

	Route::get('subjects', 'SubjectsController@search');

Route::get('/', array('before' => 'auth.custom', function()
{
	// get from session
	$with = Session::get('user');
	return View::make('main', $with);
}));
