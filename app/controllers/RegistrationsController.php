<?php 

class RegistrationsController extends \BaseController {

	protected $model;

	public function __construct(Registration $model) {
		$this->model = $model;
		$this->data['sy'] = Session::get('user.sem.sy', '2014-2015');
		$this->data['sem'] = Session::get('user.sem.sem', '1');
	}

	public function updateGrade() {
		return Response::json($this->model->updateGrades());
	}
}

 ?>