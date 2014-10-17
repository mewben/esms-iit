<?php 

class SubjectsController extends \BaseController {

	protected $model;

	public function __construct(Subject $model) {
		$this->model = $model;
	}

	public function search() {
		//Search subject
		// if (Input::get('q'))
		// 	return Response::json($this->model->search(Input::all()));

		// return Response::json(['err'=>'Error'], 409);
		return Response::json($this->model->getOfferedSubjects('HUM 2', '2014-2015', '1'));
	}
}

 ?>