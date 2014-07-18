<?php

class FeesController extends \BaseController {

	protected $model;

	public function __construct(Fee $model)
	{
		$this->model = $model;
	}

	// search student
	public function search()
	{
		return Response::json($this->model->getFees());
	}

	public function unpaid()
	{
		return Response::json($this->model->loadUnpaidAssessment(Input::all()));
	}

	// search payment
	public function payment()
	{
		return Response::json($this->model->getPayment(Input::all()));
	}

	public function destroy()
	{
		return Response::json($this->model->deletePayment(Input::get('q')));
	}
}
