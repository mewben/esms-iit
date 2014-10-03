<?php

class ReportsController extends \BaseController {

	protected $model;

	public function __construct(Report $model)
	{
		$this->model = $model;
	}

	public function certbilling($id)
	{
		$data = array(
			'studid' => $id,
			'sy' => Session::get('user.sem.sy', '2014-2015'),
			'sem' => Session::get('user.sem.sem', '1')
		);
		return Response::json($this->model->getCertBilling($data));
	}

	public function collections()
	{
		if (Input::get('preview'))
			return Response::json($this->model->getCollectionSummary(Input::all()));
		else {
			$data = $this->model->getCollections(Input::all());
			Excel::create('Collections', function($excel) use ($data) {
				$excel->sheet('New Sheet', function($sheet) use ($data) {
					$sheet->loadView('collections')->with('data', $data);
				});
			})->export('csv');
		}
	}

	public function refunds()
	{
		if (Input::get('preview'))
			return Response::json($this->model->getRefundSummary(Input::all()));
		else {
			$data = $this->model->getRefunds(Input::all());
			Excel::create('Refunds', function($excel) use ($data) {
				$excel->sheet('New Sheet', function($sheet) use ($data) {
					$sheet->loadView('collections')->with('data', $data);
				});
			})->export('csv');
		}
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
