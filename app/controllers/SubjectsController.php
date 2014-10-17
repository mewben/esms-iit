<?php 

class SubjectsController extends \BaseController {

	protected $model;

	public function __construct(Subject $model) {
		$this->model = $model;
		$this->data['sy'] = Session::get('user.sem.sy', '2014-2015');
		$this->data['sem'] = Session::get('user.sem.sem', '1');
	}

	public function search($subjcode) {
		$this->data['subjcode'] = $subjcode;

		return Response::json($this->model->search($this->data));
	}
}

 ?>