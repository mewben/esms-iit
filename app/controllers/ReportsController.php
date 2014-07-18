<?php

class ReportsController extends \BaseController {

	protected $model;

	public function __construct(Report $model)
	{
		$this->model = $model;
	}

	public function collections()
	{
		return Response::json($this->model->getCollections(Input::all()));
	}

	public function receivables()
	{
		return Response::json($this->model->getReceivables(Input::all()));
	}

	// sub ledger for student
	public function ledger($id)
	{
		return Response::json($this->model->getSubLedger($id));
	}

	// summary of billing
	public function sumbilling()
	{
		return Response::json($this->model->getSumBilling(Input::all()));
	}
}
