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

	public function save() {
		// $data = [
		// 	[
		// 		'grade_enc' => null,
		// 		'lock' => null,

		// 		'prelim1' => null,
		// 		'prelim2' => null,
		// 		'grade' => null,
		// 		'gcompl' => null,

		// 		'studid' => null,
		// 		'subjcode' => null,
		// 		'section' => null,
		// 		'sy' => null,
		// 		'sem' => null
		// 	],
		// 	[
		// 		'grade_enc' => '',
		// 		'lock' => '',

		// 		'prelim1' => '1.3',
		// 		'prelim2' => '1.3',
		// 		'grade' => '',
		// 		'gcompl' => '',

		// 		'studid' => '009322',
		// 		'subjcode' => 'EE 514',
		// 		'section' => 'EE5',
		// 		'sy' => '2014-2015',
		// 		'sem' => '1'
		// 	],
		// 	[
		// 		'grade_enc' => '',
		// 		'lock' => '',

		// 		'prelim1' => '3.2',
		// 		'prelim2' => '3.1',
		// 		'grade' => '',
		// 		'gcompl' => '3.0',

		// 		'studid' => '008784',
		// 		'subjcode' => 'EE 514',
		// 		'section' => 'EE5',
		// 		'sy' => '2014-2015',
		// 		'sem' => '1'
		// 	]
		// ];
		//return Response::json($this->model->save($data));
	}

}