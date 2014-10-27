<?php

class StudentsController extends \BaseController {

	protected $model;

	public function __construct(Student $model)
	{
		$this->model = $model;
		$this->data['sy'] = Session::get('user.sem.sy', '2014-2015');
		$this->data['sem'] = Session::get('user.sem.sem', '1');
	}

	// search student
	public function search()
	{
		if (Input::get('q'))
			return Response::json($this->model->search(Input::all()));

		return Response::json(['err'=>'Error'], 409);
	}

	// search student by sy by sem
	public function searchReg()
	{	
		$this->data['search'] = Input::get('q');
		//dd($this->data);
		return Response::json($this->model->searchBySyBySem($this->data));

		// if (Input::get('q'))
		// 	return Response::json($this->model->search(Input::all()));

		// return Response::json(['err'=>'Error'], 409);
	}

}
