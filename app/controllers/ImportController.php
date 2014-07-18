<?php

class ImportController extends \BaseController {

	protected $model;

	public function __construct(Import $model)
	{
		$this->model = $model;
	}

	/**
	 * Import multiple payments from csv
	 */
	public function payments()
	{
		return Response::json($this->model->payments(Input::all()));
	}

	/**
	 * Save single payment
	 */
	public function payment()
	{
		return Response::json($this->model->payment(Input::all()));
	}
}