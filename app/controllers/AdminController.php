<?php

class AdminController extends \BaseController {

	public function change_semester()
	{
		$data = Input::all();

		// TODO: change session here
		$sem = Semester::where('sy', $data['sy'])
					->where('sem', $data['sem'])
					->get()
					->toArray();

		if (!$sem)	throw new Exception('Invalid Semester', 409);
		
		Session::put('user.sem', $sem[0]);
		return Response::json($sem[0]);
	}

	public function doLogin()
	{
		$con = Config::get('database.connections.pgsql');

		$con['username'] = Input::get('username');
		$con['password'] = Input::get('password');

		Config::set('database.connections.pgsql', $con);
		try {
			DB::connection()->getDatabaseName();
			$sem = Semester::where('current', '=', 't')->get()->toArray();
			if (!$sem) {
				$sem[0] = array('sy' => '2014-2014', 'sem' => '1');
			} 
			$currentDate = date('Y-m-d');
			
			Session::put('user.con', $con);
			Session::put('user.sem', $sem[0]);
			Session::put('user.currentDate', $currentDate);

			return Redirect::to('/');
		} catch (Exception $e) {
			return Redirect::to('login')->with('err', 'Login Failed.');
		}
	}
}
