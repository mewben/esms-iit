<?php

class GradesController extends \BaseController {

	protected $model;
	protected $data = array();

	public function __construct(Grade $model)
	{
		$this->model = $model;
		$this->data['sy'] = Session::get('user.sem.sy', '2014-2015');
		$this->data['sem'] = Session::get('user.sem.sem', '1');
	}

	/**
	 * Returns the students with grades by subjcode by section
	 */
	public function getBySubjectBySection($subjcode, $section)
	{
		$this->data['subjcode'] = $subjcode;
		$this->data['section'] = $section;

		return Response::json($this->model->getStudentsBySection($this->data));
	}

}