<?php

class StudentsController extends \BaseController {

	protected $model;

	public function __construct(Student $model)
	{
		$this->model = $model;
	}

	// search student
	public function search()
	{
		if (Input::get('q'))
			return Response::json($this->model->search(Input::all()));

		return Response::json(['err'=>'Error'], 409);
	}

}
