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

Route::get('test', function() {
	// get the refund of a student in a semester.
	// get-refund/003498
	// use session to get the semester
	// returns
		// feecode
		// amt

	// logic:
		// compare total assessment to paid. if excess, get the excess feecode

	$total_ass = 0;
	$total_paid = 0;
	$studid = '013515';
	//$studid = '016797';
	$sy = Session::get('user.sy', '2014-2015');
	$sem = Session::get('user.sem', '1');

	// get total assessment
	$ass = DB::select("SELECT * FROM ass_details WHERE studid=? AND sy=? AND sem=?", array($studid, $sy, $sem));
	$paid = DB::select("SELECT * FROM get_paiddetails(?,?,?)", array($studid, $sy, $sem));
	
	foreach ($ass as $v) {
		$total_ass += $v->amt;
		$ass2[$v->feecode] = $v->amt;
	}
	foreach ($paid as $v) {
		$total_paid += $v->amt;
		$paid2[$v->feecode] = $v->amt;
	}

	$excess = $total_paid - 0 - $total_ass;
	if ($excess <= 0)	throw new Exception('No amount refundable for this semester.', 409);

	// return the excess value to refund.
	// return $excess;
	print_r($ass2);
	print_r($paid2);

	$diff = array_diff_assoc($ass2, $paid2);
	print_r($diff);
	$x = 0;
	foreach ($diff as $k => $v) {
		$ins[$x]['feecode'] = $k;
		$ass2[$k] = isset($ass2[$k]) ? $ass2[$k] : 0;
		$paid2[$k] = isset($paid2[$k]) ? $paid2[$k] : 0;
		$ins[$x]['amt'] = $paid2[$k] - $ass2[$k];	
		$x++;
	}
	print_r($ins);

	$data = array(
		'refno' => 'TEST2',
		'sy' => $sy,
		'sem' => $sem,
		'studid' => $studid,
		'payee' => 'TEST NAME',
		'bcode' => 'RF',
		'paydate' => '2014-07-31',
		'remarks' => 'REMARKS TEST'
	);
	foreach ($ins as $v) {
		if ($v['amt'] < 0) {
			// insert to bulk_collection header
			$p['details'][] = array(
				'feecode' => $v['feecode'],
				'amt' => $v['amt'] * -1
			);
		} else {
			$r['details'][] = array(
				'feecode' => $v['feecode'],
				'amt' => $v['amt']
			);
		}
	}
	if (count($p['details']) > 0) {
		// save to bulk_collection_header
		$p['h'] = $data;
		(new Import)->payment($p);
	}

	if (count($r['details']) > 0) {
		// save to refund_header
		$r['h'] = $data;
		(new Refund)->make($r);
	}

	print_r($p);
	print_r($r);
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
	header('Access-Control-Allow-Origin: *');

	Route::post('change_semester', 'AdminController@change_semester');
	Route::post('import-payment', 'ImportController@payments');
	Route::post('payment', 'ImportController@payment');
	Route::post('refund', 'RefundsController@save');

	Route::post('delete-payment', 'FeesController@destroy');

	Route::get('fees', 'FeesController@search');
	Route::get('load-unpaid', 'FeesController@unpaid');
	Route::get('payment', 'FeesController@payment');
	Route::get('refund/{id}', 'RefundsController@show');
	Route::get('students', 'StudentsController@search');
	Route::get('reports/certbilling/{id}', 'ReportsController@certbilling');
	Route::get('reports/collections', 'ReportsController@collections');
	Route::get('reports/ledgers/{id}', 'ReportsController@ledger');
	Route::get('reports/receivables', 'ReportsController@receivables');
	Route::get('reports/sumbilling', 'ReportsController@sumbilling');
});

Route::get('/', array('before' => 'auth.custom', function()
{
	// get from session
	$with = Session::get('user');
	return View::make('main', $with);
}));
