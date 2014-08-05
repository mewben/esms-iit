<?php

class RefundsController extends \BaseController {

	protected $model;

	public function __construct(Refund $model)
	{
		$this->model = $model;
	}

	/**
	 * Get the refundable amount of a particular student by semester
	 */
	public function show($id)
	{
		$data = array(
			'studid' => $id,
			'sy' => Session::get('user.sy', '2014-2015'),
			'sem' => Session::get('user.sem', '1')
		);
		return Response::json($this->model->show($data));
	}

	/**
	 * Save a refund
	 */
	public function save()
	{
		return true;
		$data = Input::all();
		$data['sy'] = Session::get('user.sy', '2014-2015');
		$data['sem'] = Session::get('user.sem', '1');

		return Response::json($this->model->process($data));
	}
}